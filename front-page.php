<?php disallow_direct_load( 'front-page.php' ); ?>
<?php get_header(); the_post(); ?>

<article>
	<div class="container">
		<div class="row">
			<div class="content col-md-12">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</article>

<?php get_footer(); ?>
