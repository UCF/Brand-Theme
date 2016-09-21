<?php

/**
 * Base Shortcode class
 **/
abstract class Shortcode {
	public
		$name        = 'Shortcode', // The name of the shortcode.
		$command     = 'shortcode', // The command used to call the shortcode.
		$description = 'This is the description of the shortcode.', // The description of the shortcode.
		$callback    = 'callback',
		$wysiwyg     = True; // Whether to add it to the shortcode Wysiwyg modal.

	/*
	 * Register the shortcode.
	 * @since v0.1.0
	 * @author Jim Barnes
	 * @return void
	 */
	public function register_shortcode() {
		add_shortcode( $this->command, array( $this, $this->callback ) );
	}

	/*
	 * Returns parameters for the shortcode.
	 * @since v0.1.0
	 * @author Jo Dickson
	 * @return array
	 */
	public function params() {
		return array();
	}

	/*
	 * Returns an individual parameter options (e.g. 'default', 'choices') by
	 * the parameter's ID.  Specify a specific $option to just return that option.
	 * @since v0.1.0
	 * @author Jo Dickson
	 * @return mixed
	 */
	public function get_param_options( $param_id, $option=null ) {
		$params = $this->params();
		$retval = false;

		if ( $params ) {
			foreach ( $params as $param ) {
				if ( $param['id'] === $param_id ) {
					if ( $option && isset( $param[$option] ) ) {
						return $param[$option];
					}
					else {
						return $param;
					}
				}
			}
		}

		return $retval;
	}

	/*
	 * Returns an array of shortcode attributes with default values applied
	 * as necessary.
	 * @since v0.1.0
	 * @author Jo Dickson
	 * @return array
	 */
	public function get_shortcode_atts( $atts ) {
		$params = $this->params();
		$retval = $pairs = array();

		if ( is_array( $params ) && !empty( $params ) ) {
			foreach ( $params as $param ) {
				$pairs[$param['id']] = isset( $param['default'] ) ? $param['default'] : '';
			}

			$retval = shortcode_atts( $pairs, $atts, $this->command );
		}

		return $retval;
	}

	/*
	 * Returns the html option markup.
	 * @since v0.1.0
	 * @author Jim Barnes
	 * @return string
	 */
	public function get_option_markup() {
		return sprintf('<option value="%s">%s</option>', $this->command, $this->name);
	}

	/*
	 * Returns the description html markup.
	 * @since v0.1.0
	 * @author Jim Barnes
	 * @return string
	 */
	public function get_description_markup() {
		return sprintf('<li class="shortcode-%s">%s</li>', $this->command, $this->description);
	}

	/*
	 * Returns the form html markup.
	 * @since v0.1.0
	 * @author Jim Barnes
	 * @return string
	 */
	public function get_form_markup() {
		ob_start();
?>
		<li class="shortcode-<?php echo $this->command; ?>">
			<h3><?php echo $this->name; ?> Options</h3>

			<?php if ( !$this->params() ): ?>
			<em>This shortcode has no options.</em>
			<?php else: ?>

			<table class="form-table">
				<tbody>
					<?php
					foreach( $this->params() as $param ) :
						if ( !isset( $param['wysiwyg'] ) || $param['wysiwyg'] === true ):
					?>
					<tr>
						<th>
							<?php echo $this->get_field_label( $param, $this->command ); ?>
						</th>
						<td>
							<?php if ( $param['help_text'] ): ?>
							<p class="help"><?php echo $param['help_text']; ?></p>
							<?php endif; ?>
							<?php echo $this->get_field_input( $param, $this->command ); ?>
						</td>
					</tr>
					<?php
						endif;
					endforeach;
					?>
				</tbody>
			</table>

			<?php endif; ?>
		</li>
<?php
		return ob_get_clean();
	}

	/*
	 * Returns the appropriate label markup for the field.
	 * @since v0.1.0
	 * @author Jo Dickson
	 * @return string
	 */
	private function get_field_label( $field, $command ) {
		$type = isset( $field['type'] ) ? $field['type'] : 'text';
		$retval = '';

		switch ( $type ) {
			case 'checkbox':
				// Labels for checkboxes are printed adjacent to the input
				$retval = $field['name'];
				break;
			default:
				$retval = '<label for="'. $command . '-' . $field['id'] .'">'. $field['name'] .'</label>';
				break;
		}

		return $retval;
	}

	/*
	 * Returns the appropriate markup for the field.
	 * @since v0.1.0
	 * @author Jim Barnes
	 * return string
	 */
	private function get_field_input( $field, $command ) {
		$name      = $field['name'];
		$id        = isset( $field['id'] ) ? $field['id'] : '';
		$type      = isset( $field['type'] ) ? $field['type'] : 'text';
		$default   = isset( $field['default'] ) ? $field['default'] : '';
		$template  = isset( $field['template'] ) ? $field['template'] : '';
		$input_id  = $command . '-' . $id;
		$retval    = '';

		switch( $type ) {
			case 'text':
			case 'date':
			case 'email':
			case 'url':
			case 'number':
			case 'color':
				$retval .= '<input class="shortcode-editor-input" id="'. $input_id .'" type="'. $type .'" name="'. $input_id .'" value="'. $default .'" data-parameter="'. $id .'">';
				break;
			case 'dropdown':
				$choices = is_array( $field['choices'] ) ? $field['choices'] : array();
				$retval .= '<select class="shortcode-editor-input" id="' . $input_id . '" type="text" name="' . $input_id . '" value="" data-parameter="' . $id . '">';
				foreach ( $choices as $choice ) {
					$selected = '';
					if ( $default == $choice['value'] ) {
						$selected = 'selected';
					}

					$retval .= '<option value="' . $choice['value'] . '" '. $selected .'>' . $choice['name'] . '</option>';
				}
				$retval .= '</select>';
				break;
			case 'checkbox':
				$checked = filter_var( $default, FILTER_VALIDATE_BOOLEAN ) ? 'checked' : '';
				$retval .= '<input class="shortcode-editor-input" id="'. $input_id .'" type="'. $type .'" name="'. $input_id .'" '. $checked .' value="'. $default .'" data-parameter="'. $id .'"><label for="'. $input_id .'">'. $name .'</label>';
				break;
		}

		return $retval;
	}
}

/**
 * Create a floating sidebar.
 **/
class SideBarSC extends Shortcode {
	public
		$name        = 'SideBar', // The name of the shortcode.
		$command     = 'sidebar', // The command used to call the shortcode.
		$description = 'Creates a floating sidebar, in which any text, media or shortcode content can be added.', // The description of the shortcode.
		$callback    = 'callback',
		$wysiwyg     = True; // Whether to add it to the shortcode Wysiwyg modal.

	public function params() {
		return array(
			array(
				'name'		=> 'Background color',
				'id'		=> 'background_color',
				'help_text'	=> '(Optional) The background color of the sidebar.  Font color can be modified by selecting text within this shortcode and picking a color from the text editor\'s Font Color dropdown menu.',
				'type'		=> 'text',
				'default'	=> '#eeeeee'
			),
			array(
				'name'		=> 'Select a position',
				'id'		=> 'position',
				'type'		=> 'dropdown',
				'choices'	=> array(
					array(
						'name'	=> 'Left',
						'value'	=> 'left'
					),
					array(
						'name'	=> 'Right',
						'value'	=> 'right'
					)
				)
			),
			array(
				'name'		=> 'Content Alignment',
				'id'		=> 'content_align',
				'type'		=> 'dropdown',
				'choices'	=> array(
					array(
						'name'	=> 'Left',
						'value'	=> 'left'
					),
					array(
						'name'	=> 'Center',
						'value'	=> 'center'
					),
					array(
						'name'	=> 'Right',
						'value'	=> 'right'
					)
				)
			)
		);
	}

	public static function callback ( $attr, $content='' ) {
		ob_start();

		$pull = ( $attr['position'] == ( 'left' || 'right' ) ) ? 'pull-' . $attr['position'] : 'pull-right';
		$bgcolor = $attr['background_color'] ? $attr['background_color'] : '#f0f0f0';
		$content_align = $attr['content_align'] ? 'text-' . $attr['content_align'] : '';
		$content = do_shortcode( $content );

		?>
		<div class="col-md-5 col-sm-6 <?php echo $pull ?> sidebar">
			<section class="sidebar-inner <?php echo $content_align ?>" style="background-color: <?php echo $bgcolor ?>">
				<?php echo $content ?>
			</section>
		</div>
		<?php
		return ob_get_clean();
	}
}


function get_all_images() {
	$query_images_args = array(
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'post_status'    => 'inherit',
		'posts_per_page' => - 1,
	);

	$query_images = new WP_Query( $query_images_args );

	$images = array(
			'name'	=> '',
			'value'	=> ''
	);
	foreach ( $query_images->posts as $image ) {
		$images[] = array(
			'name'	=> get_the_title( $image->ID ),
			'value'	=> wp_get_attachment_url( $image->ID )
		);
	}

	return $images;
}

/**
 * Create a heading.
 **/
class HeadingSC extends Shortcode {
	public
		$name        = 'Heading', // The name of the shortcode.
		$command     = 'heading', // The command used to call the shortcode.
		$description = 'Creates a full-width heading specifically for the homepage.', // The description of the shortcode.
		$callback    = 'callback',
		$wysiwyg     = True; // Whether to add it to the shortcode Wysiwyg modal.

	public function params() {
		return array(
			array(
				'name'		=> 'Background Image',
				'id'		=> 'background_image',
				'type'		=> 'dropdown',
				'choices'	=> get_all_images()
			),
			array(
				'name'		=> 'Content Alignment',
				'id'		=> 'content_align',
				'type'		=> 'dropdown',
				'choices'	=> array(
					array(
						'name'	=> 'Left',
						'value'	=> 'left'
					),
					array(
						'name'	=> 'Center',
						'value'	=> 'center'
					),
					array(
						'name'	=> 'Right',
						'value'	=> 'right'
					)
				)
			),
			array(
				'name'		=> 'CSS Classes',
				'id'		=> 'css_class',
				'help_text'	=> '(Optional) CSS classes to apply to the callout. Separate classes with a space.',
				'type'		=> 'text'
			),
			array(
				'name'		=> 'Close Container',
				'id'		=> 'close_container',
				'help_text' => 'Close container to extend heading full width.',
				'type'		=> 'dropdown',
				'choices'	=> array(
					array(
						'name'	=> 'True',
						'value'	=> 1
					),
					array(
						'name'	=> 'False',
						'value'	=> 0
					)
				)
			),
			array(
				'name'		=> 'Open Container',
				'id'		=> 'open_container',
				'help_text' => 'Open container to extend heading full width.',
				'type'		=> 'dropdown',
				'choices'	=> array(
					array(
						'name'	=> 'True',
						'value'	=> 1
					),
					array(
						'name'	=> 'False',
						'value'	=> 0
					)
				)
			)
		);
	}

	public static function callback ( $attr, $content='' ) {
		ob_start();
		$background_image = $attr['background_image'] ? 'background-image: url(' . $attr['background_image'] . ');' : '';
		$content_align = $attr['content_align'] ? 'text-' . $attr['content_align'] : '';
		$inline_css = $attr['inline_css'] ? $attr['inline_css'] : '';
		$css_class = $attr['css_class'] ? $attr['css_class'] : '';
		$content = do_shortcode( $content );

		// Close out our existing .span, .row and .container
		if( filter_var( $attr['close_container'], FILTER_VALIDATE_BOOLEAN ) ): ?>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div class="container-wide heading" style="<?php echo $inline_css ?><?php echo $background_image ?>">
			<div class="heading container">
				<div class="row">
					<div class="col-md-12 <?php echo $css_class ?> <?php echo $content_align ?>">
						<?php echo $content; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		// Reopen standard .container, .row and .span
		if( filter_var( $attr['open_container'], FILTER_VALIDATE_BOOLEAN ) ): ?>
		<div class="container">
			<div class="row content-wrap">
				<div class="col-md-12">
		<?php
		endif;
		return ob_get_clean();
	}
}

/**
 * Create a callout.
 **/
class CalloutSC extends Shortcode {
	public
		$name        = 'Callout', // The name of the shortcode.
		$command     = 'callout', // The command used to call the shortcode.
		$description = 'Creates a full-width callout box, in which any text, media or shortcode content can be added.', // The description of the shortcode.
		$callback    = 'callback',
		$wysiwyg     = True; // Whether to add it to the shortcode Wysiwyg modal.

	public function params() {
		return array(
			array(
				'name'		=> 'Background color',
				'id'		=> 'background_color',
				'help_text'	=> '(Optional) The background color of the callout box.  Font color can be modified by selecting text within this shortcode and picking a color from the text editor\'s Font Color dropdown menu.',
				'type'		=> 'text',
				'default'	=> '#eeeeee'
			),
			array(
				'name'		=> 'Image',
				'id'		=> 'image',
				'image'		=> 'Display image to the right of the copy.',
				'type'		=> 'dropdown',
				'choices'	=> get_all_images()
			),
			array(
				'name'		=> 'Image Alignment',
				'id'		=> 'image_align',
				'type'		=> 'dropdown',
				'choices'	=> array(
					array(
						'name'	=> 'Left',
						'value'	=> 'left'
					),
					array(
						'name'	=> 'Right',
						'value'	=> 'right'
					)
				)
			),
			array(
				'name'		=> 'Content Alignment',
				'id'		=> 'content_align',
				'type'		=> 'dropdown',
				'choices'	=> array(
					array(
						'name'	=> 'Left',
						'value'	=> 'left'
					),
					array(
						'name'	=> 'Center',
						'value'	=> 'center'
					),
					array(
						'name'	=> 'Right',
						'value'	=> 'right'
					)
				)
			),
			array(
				'name'		=> 'Enable affixing',
				'id'		=> 'affix',
				'help_text' => 'When set to \'True\', this callout box will affix to the top of the page when scrolled to. It will stay affixed until another affixable callout box is scrolled to, or when the end of the page is reached.',
				'type'		=> 'dropdown',
				'choices'	=> array(
					array(
						'name'	=> 'True',
						'value'	=> 1
					),
					array(
						'name'	=> 'False',
						'value'	=> 0
					)
				)
			),
			array(
				'name'		=> 'CSS Classes',
				'id'		=> 'css_class',
				'help_text'	=> '(Optional) CSS classes to apply to the callout. Separate classes with a space.',
				'type'		=> 'text'
			)
		);
	}

	public static function callback ( $attr, $content='' ) {
		ob_start();

		$bgcolor = $attr['background_color'] ? $attr['background_color'] : '#f0f0f0';
		$image = $attr['image'] ? $attr['image'] : '';
		$grid_offset = ' col-md-offset-1 col-lg-offset-2 ';
		$copy_class = $attr['image'] ? 'col-sm-7 col-md-7 col-lg-6 text-left' : 'col-sm-10 col-md-10 col-lg-8';
		$content_align = $attr['content_align'] ? 'text-' . $attr['content_align'] : '';
		$image_align = $attr['image_align'] ? $attr['image_align'] : '';
		$css_class = $attr['css_class'] ? $attr['css_class'] : '';
		$inline_css = $attr['inline_css'] ? $attr['inline_css'] : '';
		$affix = $attr['affix'] ? filter_var( $attr['affix'], FILTER_VALIDATE_BOOLEAN ) : false;
		$content = do_shortcode( $content );

		if( $image == '' || $image_align == 'right' ) {
			$copy_class = $copy_class . $grid_offset;
		}

		$inline_css = 'background-color: ' . $bgcolor . ';' . $inline_css;
		if ( $affix ) {
			$css_class .= ' callout-affix';
		}

		// Close out our existing .span, .row and .container
		?>
				</div>
			</div>
		</div>
		<div class="container-wide callout-outer">
			<div class="callout <?php echo $css_class ?>" style="<?php echo $inline_css ?>">
				<div class="container">
					<div class="row content-wrap">
						<?php if( !empty( $image ) && $image_align == 'left' ) { ?>
						<div class="col-sm-3 col-md-3 <?php echo $grid_offset ?>">
							<img src="<?php echo $image ?>" class="img-responsive">
						</div>
						<?php } ?>
						<div class="<?php echo $copy_class ?> <?php echo $content_align ?>"><?php echo $content; ?></div>
						<?php if( !empty( $image ) && $image_align == 'right' ) { ?>
						<div class="col-sm-3 col-md-3">
							<img src="<?php echo $image ?>" class="img-responsive">
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		// Reopen standard .container, .row and .span
		?>
		<div class="container">
			<div class="row content-wrap">
				<div class="col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
		<?php
		return ob_get_clean();
	}
}

/**
 * Create a uid search field.
 **/
class UIDSearchSC extends Shortcode {
	public
		$name        = 'UIDSearch', // The name of the shortcode.
		$command     = 'uid-search', // The command used to call the shortcode.
		$description = 'Insert UIL Search field and results', // The description of the shortcode.
		$callback    = 'callback',
		$wysiwyg     = True; // Whether to add it to the shortcode Wysiwyg modal.
	public static function callback ( $attr, $content='' ) {
		ob_start();
		$bucket = get_theme_mod_or_default( 'amazon_bucket' );
		$folder = get_theme_mod_or_default( 'amazon_folder' );
?>
	<script>
		var creds = {
		bucket: '<?php echo $bucket ?>',
		folder: '<?php echo $folder ?>',
		access_key: '<?php echo get_theme_mod_or_default( 'access_key' ) ?>',
		secret_key: '<?php echo get_theme_mod_or_default( 'secret_key' ) ?>'
	};
	</script>
	<div ng-app="UIDSearch" class="uid-search" ng-controller="UIDSearchController as uidSearchCtrl" ng-cloak>
		<div class="uid-search-form-inner">
				<label for="uid-search" class="sr-only">Search for a Unit Identity Lockup</label>
				<input id="uid-search" class="form-control input-lg"
					ng-model="uidSearchCtrl.searchQuery.term" ng-model-options="{ debounce: 300 }"
					placeholder="Enter a unit name such as 'College of Sciences' or 'Registars Office'">
				<button class="btn btn-link" type="submit">Search</button>
		</div>
		<div class="glyphicon glyphicons-search"></div>
		<div class="error uid-error alert alert-danger" ng-show="uidSearchCtrl.error"><span class="glyphicon glyphicon-alert"></span> Error loading Unit Identifiers</div>
		<div class="loading" ng-show="uidSearchCtrl.loading"><span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Searching for Unit Identifiers</div>
		<div class="alert alert-info" ng-show="uidSearchCtrl.noResults">
			<span class="glyphicon glyphicon-comment"></span> We didn't find any unit indentity lockups that matched your search. You may request one below.
		</div>

		<div ng-if="uidSearchCtrl.results.length">
			<hr>
			<h2>Results</h2>
			<div class="row uid-result-container">
				<div ng-repeat="result in uidSearchCtrl.results">
					<div class="clearfix" ng-if="$index % 3 == 0"></div>
					<div class="col-sm-4 col-md-4 uid-result">
						<h3>{{ result.post_title }}</h3>
						<img ng-src="<?php echo get_amazon_url() ?>{{ result.post_name }}/{{ result.post_name }}.png" width="100%">
						<a href="<?php echo get_amazon_url() ?>{{ result.post_name }}/{{ result.post_name }}.zip"
							class="btn btn-ucf btn-download">
							Download
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
		return ob_get_clean();

	}
}

?>
