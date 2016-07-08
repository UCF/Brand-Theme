<?php disallow_direct_load( 'page.php' ); ?>
<?php the_post(); ?>
<?php
$post = attach_post_metadata_properties( $post );
$page_protected = filter_var( $post->page_protected_page, FILTER_VALIDATE_BOOLEAN );
if ( $page_protected ) {
 	ldap_required();
}
?>

<?php get_header(); ?>

<article>
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'left-menu',
					'container' => false,
					'menu_class' => 'list-inline site-left-menu'
				) );
				?>
			</div>
			<div class="col-md-9">
				<h1><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</article>

<?php get_footer(); ?>
