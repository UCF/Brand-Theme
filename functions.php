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
	register_rest_route( 'rest', '/uids', array(
		'methods'  => 'GET',
		'callback' => 'api_get_uids'
	) );
} );

function get_amazon_url() {
	return AMAZON_AWS_URL . get_theme_mod_or_default( 'amazon_bucket' ) . "/" . get_theme_mod_or_default( 'amazon_folder' ) . "/" ;
}

function display_submenu ( $post ) {
	ob_start();

	if ( is_page() && $post->post_parent )
		$childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->post_parent . '&echo=0' );
	else
		$childpages = wp_list_pages( 'sort_column=menu_order&title_li=&child_of=' . $post->ID . '&echo=0' );

	if ( $childpages ) {
		?>
			<ul class="site-left-menu"><?php echo $childpages ?></ul>
		<?php
	}

	return ob_get_clean();
}

?>
