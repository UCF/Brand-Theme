<?php
require_once 'functions/base.php';      // Base theme functions
require_once 'functions/feeds.php';     // Where functions related to feed data live
require_once 'custom-taxonomies.php';   // Where per theme taxonomies are defined
require_once 'custom-post-types.php';   // Where per theme post types are defined
require_once 'functions/config.php';    // Where per theme settings are registered
require_once 'functions/admin.php';     // Admin/login functions
require_once 'shortcodes.php';          // Per theme shortcodes

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
 * Authenticates the username/password combination with LDAP.
 *
 * @param string  $username The username to authenticate.
 * @param string  $password The password to authenticate.
 * @return bool True if username/password was authenticated, otherwise false
 *
 * @author Brandon T. Groves
 */
function ldap_auth( $username, $password ) {
	$ldapbind = false;
	$ldap = ldap_connect( LDAP_HOST );
	if ( $ldap ) {
		$ldapbind = ldap_bind( $ldap, $username . '@' . LDAP_HOST, $password );
	} else {
		echo 'Could not connect.';
	}
	return $ldapbind;
}


/**
 * Sets the session data for ldap authentication.
 *
 * @author Brandon T. Groves
 */
function ldap_set_session_data( $user ) {
	$timeout = 15 * 60;
	$_SESSION['timeout'] = time() + $timeout;
	$_SESSION['user'] = $user;
	$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
}


/**
 * Returns true/false depending on if the current user is already authenticated
 * successfully against LDAP.
 **/
function ldap_is_authenticated() {
	return isset( $_SESSION['user'] ) && isset( $_SESSION['ip'] ) && $_SESSION['ip'] == $_SERVER['REMOTE_ADDR'];
}


/**
 * Returns true/false if the current user's LDAP session has expired.
 **/
function ldap_session_timed_out() {
	return isset( $_SESSION['timeout'] ) && $_SESSION['timeout'] < time();
}


/**
 * Destroys the session data for ldap authentication.
 *
 * @author Brandon T. Groves
 */
function ldap_destroy_session() {
	$_SESSION = array();
	session_destroy();
}


/**
 * Aborts loading a template if the user hasn't authenticated.
 * Intended for use at the top of individual template files, before
 * get_header().
 *
 * NOTE: this is probably the easiest solution for this site without knowing
 * ahead of time which templates need LDAP authentication.  If we end up
 * needing the entire site to be behind an authentication wall, hooking into
 * 'init' or 'after_setup_theme' might be better.
 **/
function ldap_required() {
	session_start();

	$ldap_error = false;

	if ( ldap_session_timed_out() ) {
		ldap_destroy_session();
	}

	// Set session data and continue if the user is already authenticated or
	// authenticates successfully.  Else, load the login screen.
	if ( ldap_is_authenticated() ) {
		ldap_set_session_data( $_SESSION['user'] );
		session_write_close();
	}
	else if (
		isset( $_POST['uid-submit-auth'] )
		&& isset( $_POST['uid-username'] )
		&& strlen( $_POST['uid-username'] ) > 0
		&& isset( $_POST['uid-password'] )
		&& strlen( $_POST['uid-password'] ) > 0
	) {
		if (
			ldap_auth( $_POST['uid-username'], $_POST['uid-password'] )
			&& wp_verify_nonce( $_REQUEST['uid_auth_nonce'], 'uid-auth' )
		) {
			ldap_set_session_data( $_POST['uid-username'] );
			session_write_close();
		}
		else {
			ldap_destroy_session();

			$ldap_error = true;
			require_once THEME_INCLUDES_DIR . '/ldap-login.php';
			die;
		}
	}
	else {
		ldap_destroy_session();

		require_once THEME_INCLUDES_DIR . '/ldap-login.php';
		die;
	}
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


/**
 * Methods used to display the footer
 **/
function display_social() {
	$facebook_url   = get_theme_mod_or_default( 'facebook_url' );
	$twitter_url    = get_theme_mod_or_default( 'twitter_url' );
	$googleplus_url = get_theme_mod_or_default( 'googleplus_url' );
	$linkedin_url   = get_theme_mod_or_default( 'linkedin_url' );
	$instagram_url   = get_theme_mod_or_default( 'instagram_url' );
	$pinterest_url   = get_theme_mod_or_default( 'pinterest_url' );
	$youtube_url   = get_theme_mod_or_default( 'youtube_url' );
	ob_start();
?>
	<div class="social">
	<?php if ( $facebook_url ) : ?>
		<a href="<?php echo $facebook_url; ?>" target="_blank" class="social-icon ga-event-link">
			<span class="fa fa-facebook"></span>
			<span class="sr-only">Like us on Facebook</span>
		</a>
	<?php endif; ?>
	<?php if ( $twitter_url ) : ?>
		<a href="<?php echo $twitter_url; ?>" target="_blank" class="social-icon ga-event-link">
			<span class="fa fa-twitter"></span>
			<span class="sr-only">Follow us on Twitter</span>
		</a>
	<?php endif; ?>
	<?php if ( $googleplus_url ) : ?>
		<a href="<?php echo $googleplus_url; ?>" target="_blank" class="social-icon ga-event-link">
			<span class="fa fa-google-plus"></span>
			<span class="sr-only">Follow us on Google+</span>
		</a>
	<?php endif; ?>
	<?php if ( $linkedin_url ) : ?>
		<a href="<?php echo $linkedin_url; ?>" target="_blank" class="social-icon ga-event-link">
			<span class="fa fa-linkedin"></span>
			<span class="sr-only">View our LinkedIn page</span>
		</a>
	<?php endif; ?>
	<?php if ( $instagram_url ) : ?>
		<a href="<?php echo $instagram_url; ?>" target="_blank" class="social-icon ga-event-link">
			<span class="fa fa-instagram"></span>
			<span class="sr-only">View our Instagram page</span>
		</a>
	<?php endif; ?>
	<?php if ( $pinterest_url ) : ?>
		<a href="<?php echo $pinterest_url; ?>" target="_blank" class="social-icon ga-event-link">
			<span class="fa fa-pinterest-p"></span>
			<span class="sr-only">View our Pinterest page</span>
		</a>
	<?php endif; ?>
	<?php if ( $youtube_url ) : ?>
		<a href="<?php echo $youtube_url; ?>" target="_blank" class="social-icon ga-event-link">
			<span class="fa fa-youtube"></span>
			<span class="sr-only">View our YouTube page</span>
		</a>
	<?php endif; ?>
	</div>
<?php
	echo ob_get_clean();
}


function get_remote_menu( $menu_name ) {
	global $wp_customize;
	$customizing = isset( $wp_customize );
	$result_name = $menu_name.'_json';
	$result = get_transient( $result_name );
	if ( false === $result || $customizing ) {
		$opts = array(
			'http' => array(
				'timeout' => 15
			)
		);
		$context = stream_context_create( $opts );
		$file_location = get_theme_mod_or_default( $menu_name.'_feed' );
		if ( empty( $file_location ) ) {
			return;
		}
		$headers = get_headers( $file_location );
		$response_code = substr( $headers[0], 9, 3 );
		if ( $response_code !== '200' ) {
			return;
		}
		$result = json_decode( file_get_contents( $file_location, false, $context ) );
		if ( ! $customizing ) {
			set_transient( $result_name, $result, (60 * 60 * 24) );
		}
	}
	return $result;
}


function display_footer_menu() {
	$menu = get_remote_menu( 'footer_menu' );
	if ( empty( $menu) ) {
		return;
	}
	ob_start();
	?>
		<ul class="list-inline site-footer-menu">
	<?php foreach( $menu->items as $item ) : ?>
		<li><a href="<?php echo $item->url; ?>"><?php echo $item->title; ?></a></li>
	<?php endforeach; ?>
		</ul>
	<?php
	echo ob_get_clean();
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
