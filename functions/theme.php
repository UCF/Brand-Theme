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

function get_file_path( $title ) {
	$title = strtolower ( str_replace( " ", "-", $title ) );
	echo "https://s3.amazonaws.com/ucf/uid/" . $title . "/" . $title;
}

function display_file_search() {
	// TODO
	return;
}

?>
