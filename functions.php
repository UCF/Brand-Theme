<?php
require_once 'functions/base.php';      // Base theme functions
require_once 'functions/feeds.php';     // Where functions related to feed data live
require_once 'custom-taxonomies.php';   // Where per theme taxonomies are defined
require_once 'custom-post-types.php';   // Where per theme post types are defined
require_once 'functions/config.php';    // Where per theme settings are registered
require_once 'functions/admin.php';     // Admin/login functions
require_once 'shortcodes.php';          // Per theme shortcodes
require_once 'third-party/gw-gravity-forms-notes-merge-tag.php'; // Gravity Wiz // Gravity Forms // Notes Merge Tag

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

function api_get_uils( $request ) {
	$posts = get_posts( array(
		'orderby' => 'title',
		'order'   => 'ASC',
		'post_type' => 'uil',
		'nopaging' => true,
		'posts_per_page' => -1,
		's' => $request->get_param( 's' )
	) );

	if ( empty( $posts ) ) {
		return null;
	}

	return $posts;
}

add_action( 'rest_api_init', function() {
	register_rest_route( 'rest', '/uils', array(
		'methods'  => 'GET',
		'callback' => 'api_get_uils'
	) );
} );

function get_amazon_url() {
	return AMAZON_AWS_URL . get_theme_mod_or_default( 'amazon_bucket' ) . "/" . get_theme_mod_or_default( 'amazon_folder' ) . "/" ;
}

function google_tag_manager() {
	ob_start();
	$gtm_id = get_theme_mod_or_default( 'ga_account' );
	if ( $gtm_id ) :
?>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $gtm_id; ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo $gtm_id; ?>');</script>
<!-- End Google Tag Manager -->
<?php
	endif;
	return ob_get_clean();
}

// Template redirect, force ssl
function ucf_brand_force_ssl() {
	global $wp;

	if ( FORCE_SSL_ADMIN && ! is_ssl() ) {
		$url = home_url( $wp->request, 'https' );
		wp_redirect( $url, 301 );
	}
}

if ( FORCE_SSL_ADMIN && function_exists( 'ucf_brand_force_ssl' ) ) {
	add_action( 'template_redirect', 'ucf_brand_force_ssl' );
}

?>
