<?php

/**
 * Conditionally displays a <h1> or <span> for the site's primary title
 * depending on the current page being viewed.
 **/
function display_site_title() {
	$elem = ( is_home() || is_front_page() ) ? 'h1' : 'span';
	ob_start();
?>
	<<?php echo $elem; ?> class="site-title">
		<a href="<?php echo bloginfo( 'url' ); ?>"><?php echo bloginfo( 'name' ); ?></a>
	</<?php echo $elem; ?>>
<?php
	return ob_get_clean();
}


function display_result( $attachment=false ) {
	$title = !$attachment ? '{{title.rendered}}' : $attachment->post_title;
	$img   = !$attachment ? '{{media_details.sizes.thumbnail.source_url}}' : wp_get_attachment_thumb_url( $attachment->ID );

	ob_start();
?>
	<div class="result thumbnail pull-left">
		<img class="result-thumb" src="<?php echo $img; ?>" alt="<?php echo $title; ?>">
		<div class="caption">
			<h2 class="h5"><?php echo $title; ?></h2>
		</div>
	</div>
<?php
	$retval = ob_get_clean();

	if ( !$attachment ) {
		$retval = '<script id="result-template" type="text/x-handlebars-template">' . $retval . '</script>';
	}

	return $retval;
}


function display_file_search() {
	$attachments = get_posts( array(
		'post_type' => 'attachment',
		'post_status' => 'any',
		'numberposts' => -1
	) );

	ob_start();
?>
	<input id="search" class="form-control input-lg">
	<hr>
	<div id="results" class="clearfix">
<?php
	foreach ( $attachments as $attachment ) {
		echo display_result( $attachment );
	}

	// Print Handlebars template for future requested results
	echo display_result( false );
?>
	</div>
<?php
	return ob_get_clean();
}

?>
