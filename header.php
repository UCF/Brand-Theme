<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
	</head>
	<body ontouchstart <?php echo body_class(); ?>>
		<header>
			<?php
				$background_image = THEME_STATIC_URL . "/img/brand-header.jpg";
				if( $post->page_background_image ) {
					$background_image = wp_get_attachment_url( $post->page_background_image );
				}
			?>
			<div class="header-image-container" style="background-image: url(<?php echo $background_image ?>);">
				<div class="container">
					<div class="row">
						<div class="col-sm-6 col-sm-offset-3">
							<h2><?php echo $post->page_header_copy; ?></h2>
						</div>
					</div>
				</div>
			</div>
			<div class="nav-container container-fluid">
				<div class="navbar-header">
					<span class="navbar-title"></span>
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
