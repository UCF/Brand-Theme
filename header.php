<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
	</head>
	<body ontouchstart <?php echo body_class(); ?>>
		<header>
			<div class="header-image-container" style="background-image: url(<?php echo wp_get_attachment_url( $post->page_background_image ); ?>);">
				<div class="container">
					<div class="row">
						<div class="col-md-5 col-md-offset-7">
							<h2><?php echo $post->page_header_copy; ?></h2>
						</div>
					</div>
				</div>
			</div>
			<div class="nav-container container">
				<div class="row">
					<nav class="col-md-12">
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
		</header>
		<main class="site-main">
