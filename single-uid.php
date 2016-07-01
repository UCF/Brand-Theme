<?php disallow_direct_load( 'single.php' ); ?>
<?php ldap_required(); ?>
<?php get_header(); the_post(); ?>

<article class="single-uid">
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
			<div class="col-md-6">
	            <h1><?php the_title(); ?></h1>
                <img src="<?php echo get_amazon_url() . $post->post_name . "/" . $post->post_name;?>.png" width="100%">
                <a href="<?php echo get_amazon_url() . $post->post_name . "/" . $post->post_name;?>.zip" class="btn btn-ucf btn-download">Download <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span></a>
			</div>
		</div>
	</div>
</article>

<?php get_footer(); ?>