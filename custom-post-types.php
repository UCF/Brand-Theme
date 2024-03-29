<?php

/**
 * Abstract class for defining custom post types.
 **/
abstract class CustomPostType {
	public
		$name           = 'custom_post_type',
		$plural_name    = 'Custom Posts',
		$singular_name  = 'Custom Post',
		$add_new_item   = 'Add New Custom Post',
		$edit_item      = 'Edit Custom Post',
		$new_item       = 'New Custom Post',
		$public         = True,  // I dunno...leave it true
		$use_title      = True,  // Title field
		$use_editor     = True,  // WYSIWYG editor, post content field
		$use_revisions  = True,  // Revisions on post content and titles
		$use_thumbnails = False, // Featured images
		$use_order      = False, // Wordpress built-in order meta data
		$use_metabox    = False, // Enable if you have custom fields to display in admin
		$use_shortcode  = False, // Auto generate a shortcode for the post type
		                         // (see also objectsToHTML and toHTML methods)
		$taxonomies     = array( 'post_tag' ),
		$built_in       = False,
		// Optional default ordering for generic shortcode if not specified by user.
		$default_orderby = null,
		$default_order   = null;


	/**
	 * Wrapper for get_posts function, that predefines post_type for this
	 * custom post type.  Any options valid in get_posts can be passed as an
	 * option array.  Returns an array of objects.
	 * */
	public function get_objects( $options=array() ) {
		$defaults = array(
			'numberposts'   => -1,
			'orderby'       => 'title',
			'order'         => 'ASC',
			'post_type'     => $this->options( 'name' ),
		);
		$options = array_merge( $defaults, $options );
		$objects = get_posts( $options );
		return $objects;
	}


	/**
	 * Similar to get_objects, but returns array of key values mapping post
	 * title to id if available, otherwise it defaults to id=>id.
	 **/
	public function get_objects_as_options( $options ) {
		$objects = $this->get_objects( $options );
		$opt     = array();
		foreach ( $objects as $o ) {
			switch ( True ) {
			case $this->options( 'use_title' ):
				$opt[$o->post_title] = $o->ID;
				break;
			default:
				$opt[$o->ID] = $o->ID;
				break;
			}
		}
		return $opt;
	}


	/**
	 * Return the instances values defined by $key.
	 * */
	public function options( $key ) {
		$vars = get_object_vars( $this );
		return $vars[$key];
	}


	/**
	 * Additional fields on a custom post type may be defined by overriding this
	 * method on an descendant object.
	 * */
	public function fields() {
		return array();
	}


	/**
	 * Using instance variables defined, returns an array defining what this
	 * custom post type supports.
	 * */
	public function supports() {
		// Default support array
		$supports = array();
		if ( $this->options( 'use_title' ) ) {
			$supports[] = 'title';
		}
		if ( $this->options( 'use_order' ) ) {
			$supports[] = 'page-attributes';
		}
		if ( $this->options( 'use_thumbnails' ) ) {
			$supports[] = 'thumbnail';
		}
		if ( $this->options( 'use_editor' ) ) {
			$supports[] = 'editor';
		}
		if ( $this->options( 'use_revisions' ) ) {
			$supports[] = 'revisions';
		}
		return $supports;
	}


	/**
	 * Creates labels array, defining names for admin panel.
	 * */
	public function labels() {
		return array(
			'name'          => __( $this->options( 'plural_name' ) ),
			'singular_name' => __( $this->options( 'singular_name' ) ),
			'add_new_item'  => __( $this->options( 'add_new_item' ) ),
			'edit_item'     => __( $this->options( 'edit_item' ) ),
			'new_item'      => __( $this->options( 'new_item' ) ),
		);
	}


	/**
	 * Creates metabox array for custom post type. Override method in
	 * descendants to add or modify metaboxes.
	 * */
	public function metabox() {
		if ( $this->options( 'use_metabox' ) ) {
			return array(
				'id'       => $this->options( 'name' ).'_metabox',
				'title'    => __( $this->options( 'singular_name' ).' Fields' ),
				'page'     => $this->options( 'name' ),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => $this->fields(),
			);
		}
		return null;
	}


	/**
	 * Registers metaboxes defined for custom post type.
	 * */
	public function register_metaboxes() {
		if ( $this->options( 'use_metabox' ) ) {
			$metabox = $this->metabox();
			add_meta_box(
				$metabox['id'],
				$metabox['title'],
				'show_meta_boxes',
				$metabox['page'],
				$metabox['context'],
				$metabox['priority']
			);
		}
	}


	/**
	 * Registers the custom post type and any other ancillary actions that are
	 * required for the post to function properly.
	 * */
	public function register() {
		$registration = array(
			'labels'       => $this->labels(),
			'supports'     => $this->supports(),
			'public'       => $this->options( 'public' ),
			'taxonomies'   => $this->options( 'taxonomies' ),
			'_builtin'     => $this->options( 'built_in' ),
		);

		if ( $this->options( 'use_order' ) ) {
			$registration = array_merge( $registration, array( 'hierarchical' => True, ) );
		}

		register_post_type( $this->options( 'name' ), $registration );

		if ( $this->options( 'use_shortcode' ) ) {
			add_shortcode( $this->options( 'name' ).'-list', array( $this, 'shortcode' ) );
		}
	}

	/**
	 * Shortcode for this custom post type.  Can be overridden for descendants.
	 * Defaults to just outputting a list of objects outputted as defined by
	 * toHTML method.
	 * */
	public function shortcode( $attr ) {
		$default = array(
			'type' => $this->options( 'name' ),
		);
		if ( is_array( $attr ) ) {
			$attr = array_merge( $default, $attr );
		}else {
			$attr = $default;
		}
		return sc_object_list( $attr );
	}


	/**
	 * Handles output for a list of objects, can be overridden for descendants.
	 * If you want to override how a list of objects are outputted, override
	 * this, if you just want to override how a single object is outputted, see
	 * the toHTML method.
	 * */
	public function objectsToHTML( $objects, $css_classes ) {
		if ( count( $objects ) < 1 ) { return '';}

		$class = get_custom_post_type( $objects[0]->post_type );
		$class = new $class;

		ob_start();
?>
		<ul class="<?php if ( $css_classes ):?><?php echo $css_classes?><?php else:?><?php echo $class->options( 'name' )?>-list<?php endif;?>">
			<?php foreach ( $objects as $o ):?>
			<li>
				<?php echo $class->toHTML( $o )?>
			</li>
			<?php endforeach;?>
		</ul>
		<?php
			$html = ob_get_clean();
		return $html;
	}


	/**
	 * Outputs this item in HTML.  Can be overridden for descendants.
	 * */
	public function toHTML( $object ) {
		$html = '<a href="'.get_permalink( $object->ID ).'">'.$object->post_title.'</a>';
		return $html;
	}
}

class Page extends CustomPostType {
	public
		$name           = 'page',
		$plural_name    = 'Pages',
		$singular_name  = 'Page',
		$add_new_item   = 'Add New Page',
		$edit_item      = 'Edit Page',
		$new_item       = 'New Page',
		$public         = True,
		$use_editor     = True,
		$use_thumbnails = False,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True,
		$built_in       = True;

	public function fields() {
		$prefix = $this->options( 'name' ).'_';
		return array(
			array(
				'name' => 'Stylesheet',
				'description' => '',
				'id' => $prefix.'stylesheet',
				'type' => 'file',
			),
			array(
				'name' => 'Background Image',
				'id' => $prefix.'background_image',
				'type' => 'file',
				'description' => 'Image displayed in the header of the page.'
			),
			array(
				'name' => 'Header Copy',
				'id' => $prefix.'header_copy',
				'type' => 'text',
				'description' => 'Copy displayed in the header of the page.'
			),
		);
	}
}

class Attachment extends CustomPostType {
	public
		$name           = 'attachment',
		$plural_name    = 'Attachments',
		$singular_name  = 'Attachment',
		$add_new_item   = 'Add New Attachment',
		$edit_item      = 'Edit Attachment',
		$new_item       = 'New Attachment',
		$public         = True,
		$use_editor     = False,
		$use_shortcode  = True,
		$taxonomies     = array( 'post_tag', 'category' );

	public function shortcode( $attr ) {
		$default = array(
			'type' => $this->options( 'name' ),
		);
		if ( is_array( $attr ) ) {
			$attr = array_merge( $default, $attr );
		} else {
			$attr = $default;
		}

		$attr['post_status'] = 'any';

		return sc_object_list( $attr );
	}
}

class Uil extends CustomPostType {
	public
		$name           = 'uil',
		$plural_name    = 'UILs',
		$singular_name  = 'UIL',
		$add_new_item   = 'Add New UIL',
		$edit_item      = 'Edit UIL',
		$new_item       = 'New UIL',
		$public         = True,
		$use_editor     = False,
		$use_order      = False,
		$use_title      = True,
		$use_metabox    = True,
		$use_shortcode  = True,
		$taxonomies     = array();

	public static function get_request_entries() {

		$end_datetime = strtotime( "today" );
		$start_datetime = strtotime( "-3 month" );

		$search_criteria = array(
			'start_date' => date( 'Y-m-d H:i:s', $start_datetime ),
			'end_date' => date(' Y-m-d H:i:s', $end_datetime )
		);

		if ( is_plugin_active( 'gravityforms/gravityforms.php' ) ) {
			$entries = GFAPI::get_entries( 1, $search_criteria );
			$entry_array['-- Select One --'] = 0;

			foreach ($entries as $entry) {
				$entry_array[$entry['1']] = $entry['id'];
			}

			return $entry_array;
		} else {
			$entry_array['Enable Gravity Forms'] = 0;
			return $entry_array;
		}

	}

	public function fields() {
		$prefix = $this->options( 'name' ).'_';
		return array(
			array (
				'name' => 'Requested UIL',
				'description' => 'Select client UIL request.',
				'id' => $prefix.'request',
				'type' => 'select',
				'choices' => $this->get_request_entries(),
			),
			array(
				'name' => 'UIL Artwork',
				'description' => 'Upload a PNG for display on the website and a ZIP file containing all the UIL artwork.',
				'id' => $prefix.'amazon',
				'type' => 'amazon',
			),
		);
	}
}

?>
