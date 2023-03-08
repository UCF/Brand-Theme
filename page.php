<?php disallow_direct_load( 'page.php' ); ?>
<?php the_post(); ?>
<?php
$post = attach_post_metadata_properties( $post );
?>

<?php get_header(); ?>

<article id="content">
	<div class="container">
		<div class="row">
			<div class="content col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</article>

<?php get_footer(); ?>
