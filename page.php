<?php disallow_direct_load( 'page.php' ); ?>
<?php ldap_required(); ?>
<?php get_header(); the_post(); ?>

<article>
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<!--div id="sidebar_left" class="col-md-2 col-sm-2 col-md-pull-7 col-sm-pull-7" role="navigation"-->

					<?php
					wp_nav_menu( array(
						'theme_location' => 'left-menu',
						'container' => false,
						'menu_class' => 'list-inline site-left-menu'
					) );
					?>
				<!--/div-->
			</div>
			<div class="col-md-9">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</article>

<?php get_footer(); ?>
