<?php
require_once 'functions/base.php';      // Base theme functions
require_once 'functions/feeds.php';     // Where functions related to feed data live
require_once 'custom-taxonomies.php';   // Where per theme taxonomies are defined
require_once 'custom-post-types.php';   // Where per theme post types are defined
require_once 'functions/config.php';    // Where per theme settings are registered
require_once 'functions/theme.php';     // Theme-specific functions should be added here
require_once 'functions/admin.php';     // Admin/login functions
require_once 'shortcodes.php';          // Per theme shortcodes

/**
 * NOTE: functions specific to this theme should be defined in
 * functions/theme.php instead of this file.  Note the load order of files
 * listed above.
 **/

function api_get_uids( $request ) {
	$posts = get_posts( array(
		'post_type' => 'uid',
		'post_per_page' => -1,
		's' => $request->get_param( 's' )
	) );

	if ( empty( $posts ) ) {
		return null;
	}

	return $posts;
}

add_action( 'rest_api_init', function() {
	register_rest_route( 'wp/v2', '/uids', array(
		'methods'  => 'GET',
		'callback' => 'api_get_uids'
	) );
} );

?>
