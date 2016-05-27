<?php disallow_direct_load( 'single-uid.php' ); ?>
<?php get_header(); the_post();

function get_file_path( $title ) {
	$title = strtolower ( str_replace( " ", "-", $title ) );
	echo "https://s3.amazonaws.com/ucf/uid/" . $title . "/" . $title;
}
 ?>

<article>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h1><?php the_title(); ?></h1>
				<img src="<?php get_file_path(get_the_title() ); ?>.png" width="100%">
				<ul class="download-uid-links">
					<li><a href="<?php get_file_path(get_the_title() ); ?>.eps" class="btn btn-primary">Download EPS</a></li>
					<li><a href="<?php get_file_path(get_the_title() ); ?>.pdf" class="btn btn-primary">Download PDF</a></li>
				</ul>
			</div>
		</div>
	</div>
</article>

<?php get_footer();?>