<?php @header( 'HTTP/1.1 404 Not found', true, 404 ); ?>
<?php disallow_direct_load( '404.php' ); ?>

<?php get_header(); ?>

<article>
	<h1>Page Not Found</h1>
	<?php
	$page = get_page_by_title( '404' );
	if ( $page && $content = $page->post_content ) {
		echo apply_filters( 'the_content', $content );
	}
	else {
		echo 'Sorry, the page you requested does not exist.';
	}
	?>
</article>

<?php get_footer(); ?>
