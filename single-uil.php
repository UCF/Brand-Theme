<?php disallow_direct_load( 'single.php' ); ?>
<?php get_header(); the_post(); ?>

<article class="single-uid">
	<div class="container">
		<div class="row">
			<div class="content col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
				<h1><?php the_title(); ?></h1>
				<img class="uil-img" src="<?php echo get_amazon_url() . $post->post_name . "/" . $post->post_name;?>.png">
			</div>
			<div class="content margin-bottom-50 col-sm-12 col-md-10 col-md-offset-1 col-lg-8 col-lg-offset-2">
				<a href="#" data-file="<?php echo $post->post_name . "/" . $post->post_name;?>.zip"
					data-toggle="modal" data-target="#uilFormModal"
					class="btn btn-ucf btn-download">
					Download
				</a>
			</div>
		</div>
	</div>
</article>

<?php get_footer(); ?>
