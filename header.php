<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
	</head>
	<body ontouchstart <?php echo body_class(); ?>>
		<header class="container-fluid site-header-container">
			<div class="row">
				<div class="callout main-header" style="background-image: url(<?php echo wp_get_attachment_url( $post->page_background_image ); ?>);">
					<div class="container">
						<div class="row content-wrap">
							<div class="col-md-12 callout-inner text-left"><h2><?php echo $post->page_header_copy; ?></h2></div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="container site-header">
					<div class="row">
						<div class="col-md-12 nav-container">
							<nav class="site-nav">
								<?php
								wp_nav_menu( array(
									'theme_location' => 'header-menu',
									'container' => false,
									'menu_class' => 'list-inline site-header-menu',
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
