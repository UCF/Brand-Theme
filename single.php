<?php disallow_direct_load( 'single.php' ); ?>
<?php get_header(); the_post(); ?>

<article>
	<h1><?php the_title(); ?></h1>
	<?php the_content(); ?>
</article>

<?php get_footer(); ?>
