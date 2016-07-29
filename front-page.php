<?php disallow_direct_load( 'front-page.php' ); ?>

<?php the_post(); ?>
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
	</head>
	<body ontouchstart <?php echo body_class(); ?>>
		<main class="site-main">
			<article>
				<?php the_content(); ?>
			</article>

<?php get_footer(); ?>
