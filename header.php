<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
	</head>
	<body ontouchstart <?php echo body_class(); ?>>
		<div class="container-fluid site-header-container">
			<div class="row">
				<div class="header-image" style="background-image: url(<?php echo THEME_IMG_URL . '/brand-header.jpg' ?>)"></div>
			</div>
			<div class="row">
				<div class="container site-header">
					<header class="row">
						<div class="site-title-container col-md-3">
							<?php echo display_site_title(); ?>
						</div>
						<div class="col-md-9">
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
					</header>
				</div>
			</div>
		</div>
		<main class="site-main">
