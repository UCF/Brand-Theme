<?php

/**
 * Conditionally displays a <h1> or <span> for the site's primary title
 * depending on the current page being viewed.
 **/
function display_site_title() {
	$elem = ( is_home() || is_front_page() ) ? 'h1' : 'span';
	ob_start();
?>
	<<?php echo $elem; ?> class="site-title">
		<a href="<?php echo bloginfo( 'url' ); ?>"><?php echo bloginfo( 'name' ); ?></a>
	</<?php echo $elem; ?>>
<?php
	return ob_get_clean();
}

function get_file_path( $title ) {
	$title = strtolower ( str_replace( " ", "-", $title ) );
	echo "https://s3.amazonaws.com/ucf/uid/" . $title . "/" . $title;
}


function display_file_search() {
	$attachments = get_posts( array(
		'post_type' => 'attachment',
		'post_status' => 'any',
		'numberposts' => -1
	) );
	ob_start();
?>
	<div class="uid-search" ng-app="UIDSearch" ng-cloak>
		<div class="row" ng-controller="UIDSearchController as search">
			<div class="col-md-12">
				<h2>Welcome, <span>RJ Bruneel</span>.</h2>
				<h3>Seach for a unit identifier</h3>
				<p class="desc">Enter a collge or department name to see if we have it in our archives.</p>
			</div>
			<div class="col-md-9">
				<input id="uid-search" class="form-control input-lg"
					ng-model="search.searchQuery.term" ng-model-options="{ debounce: 300 }"
					placeholder="Enter a unit name such as 'College of Sciences' or 'Registars Office'">
			</div>
			<div class="col-md-12"  ng-if="search.results.length" ng-cloak>
				<hr>
				<h4>Results</h4>
				<div class="row">
					<div class="col-md-4" ng-repeat="result in search.results">
						<h5>{{ result.title.rendered }}</h5>
						<img ng-src="https://s3.amazonaws.com/web.ucf.edu/uid/{{ result.slug }}/{{ result.slug }}.png" width="100%">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<hr>
				<?php
					echo do_shortcode('[gravityform id="1" title="true" description="true"]');
				?>
			</div>
		</div>
	</div>
<?php
	return ob_get_clean();
}
?>
