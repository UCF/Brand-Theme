<?php disallow_direct_load( 'front-page.php' ); ?>
<?php get_header(); the_post(); ?>

<article>
	<?php the_content(); ?>
</article>

<?php get_footer(); ?>
