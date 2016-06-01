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

?>
