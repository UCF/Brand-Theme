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


/**
 * Adds post meta data values as $post object properties for convenience.
 * Excludes WordPress' internal custom keys (prefixed with '_').
 **/
function attach_post_metadata_properties( $post ) {
	$metas = get_post_meta( $post->ID );
	foreach ( $metas as $key => $val ) {
		if ( substr( $key, 0, 1 ) !== '_' ) {
			$val = is_array( $val ) ? maybe_unserialize( $val[0] ) : maybe_unserialize( $val );
			$post->$key = $val;
		}
	}
	return $post;
}

?>
