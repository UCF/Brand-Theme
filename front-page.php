<?php disallow_direct_load( 'front-page.php' ); ?>
<?php get_header(); the_post(); ?>

<div class="container">
	<?php echo display_file_search(); ?>
</div>

<?php get_footer(); ?>
