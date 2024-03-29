<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
	</head>
	<body ontouchstart <?php echo body_class(); ?>>
		<?php echo google_tag_manager(); ?>
		<a class="skip-navigation bg-complementary text-inverse sr-only" href="#content">Skip to main content</a>
		<div id="ucfhb" style="min-height: 50px; background-color: #000;"></div>
		<header>
			<?php
				if ( $background_image = get_post_meta( $post->ID, 'page_background_image', TRUE ) ) {
					$background_image = wp_get_attachment_url( $background_image );
				} else {
					$background_image = get_theme_mod_or_default( 'default_header' );
				}
			?>
			<div class="header-image-container" style="background-image: url(<?php echo $background_image ?>);">
			<?php if( $header_copy = get_post_meta( $post->ID, 'page_header_copy', TRUE ) ) : ?>
				<div class="container">
					<div class="row">
						<div class="col-sm-6 col-sm-offset-3">
							<h2>
								<?php echo $header_copy; ?>
							</h2>
						</div>
					</div>
				</div>
			<?php endif; ?>
			</div>
			<div class="nav-container container-fluid">
				<div class="navbar-header">
					<span class="visible-xs navbar-title">Navigation</span>
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#mobile-nav" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="collapse navbar-collapse" id="mobile-nav">
					<div class="container">
						<div class="row">
							<nav class="col-md-12">
								<?php
								wp_nav_menu( array(
									'theme_location' => 'header-menu',
									'container' => false,
									'menu_class' => 'nav navbar-nav',
									'menu_id' => 'mobile-header-menu',
									'walker' => new Bootstrap_Walker_Nav_Menu()
								) );
								?>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</header>
		<main class="site-main">
