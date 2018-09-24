<?php

/**
 * Set theme constants
 **/
define( 'THEME_URL', get_bloginfo( 'stylesheet_directory' ) );
define( 'THEME_ADMIN_URL', get_admin_url() );
define( 'THEME_DIR', get_stylesheet_directory() );
define( 'THEME_INCLUDES_DIR', THEME_DIR.'/includes' );
define( 'THEME_STATIC_URL', THEME_URL.'/static' );
define( 'THEME_IMG_URL', THEME_STATIC_URL.'/img' );
define( 'THEME_JS_URL', THEME_STATIC_URL.'/js' );
define( 'THEME_CSS_URL', THEME_STATIC_URL.'/css' );

define( 'GA_ACCOUNT', get_theme_mod_or_default( 'ga_account' ) );

define( 'LDAP_HOST', 'net.ucf.edu' );
define( 'LDAP_PORT', 636 );

define( 'THEME_CUSTOMIZER_PREFIX', 'ucfgeneric_' ); // a unique prefix for panel/section IDs

define('AMAZON_AWS_URL', 'https://s3.amazonaws.com/' );


/**
 * Set config values including meta tags, registered custom post types, styles,
 * scripts, and any other statically defined assets that belong in the Config
 * object.
 **/


Config::$custom_post_types = array(
	'Page',
	'Attachment',
	'Uil'
);


Config::$custom_taxonomies = array(

);

Config::$shortcodes = array(
	'UIDSearchSC',
	'SideBarSC',
	'HeadingSC',
	'CalloutSC'
);


Config::$links = array(
	array( 'rel' => 'shortcut icon', 'href' => THEME_IMG_URL.'/favicon.ico', ),
	array( 'rel' => 'alternate', 'type' => 'application/rss+xml', 'href' => get_bloginfo( 'rss_url' ), ),
);


Config::$styles = array(
	array( 'admin' => True, 'src' => THEME_CSS_URL.'/admin.min.css', ),
	THEME_CSS_URL . '/style.min.css'
);

if ( get_theme_mod_or_default( 'cloud_typography_key' ) ) {
	Config::$styles[] = array( 'name' => 'font-cloudtypography', 'src' => get_theme_mod_or_default( 'cloud_typography_key' ) );
}


Config::$scripts = array(
	array( 'admin' => True, 'src' => THEME_JS_URL.'/admin.min.js', ),
	array( 'name' => 'ucfhb-script', 'src' => '//universityheader.ucf.edu/bar/js/university-header.js?use-1200-breakpoint=1', ),
);


Config::$metas = array(
	array( 'charset' => 'utf-8', ),
	array( 'http-equiv' => 'X-UA-Compatible', 'content' => 'IE=Edge' ),
	array( 'name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0' ),
);


if ( get_theme_mod_or_default( 'gw_verify' ) ) {
	Config::$metas[] = array(
		'name'    => 'google-site-verification',
		'content' => htmlentities( get_theme_mod_or_default( 'gw_verify' ) ),
	);
}


/**
 * Define customizer setting defaults here to make them accessible when calling
 * get_theme_mod()/get_theme_mod_or_default().
 **/

Config::$setting_defaults = array(
	'events_max_items' => 4,
	'events_url' => 'http://events.ucf.edu/feed.rss',
	'news_max_items' => 2,
	'news_url' => 'http://today.ucf.edu/feed/',
	'enable_google' => 1,
	'search_per_page' => 10,
	'cloud_typography_key' => '//cloud.typography.com/730568/675644/css/fonts.css', // Main site css key,
	'amazon_bucket' => 'web.ucf.edu',
	'amazon_folder' => 'uid'
);


/**
 * Configure the WP Customizer with panels, sections, settings and
 * controls.
 *
 * Serves as a replacement for Config::$theme_options in this theme.
 *
 * NOTE: Panel and Section IDs should be prefixed with THEME_CUSTOMIZER_PREFIX
 * to avoid conflicts with plugins that may add their own panels/sections to
 * the Customizer.
 *
 * See developer docs for more info:
 * https://developer.wordpress.org/themes/advanced-topics/customizer-api/
 **/

function define_customizer_panels( $wp_customize ) {
	$wp_customize->add_panel(
		THEME_CUSTOMIZER_PREFIX . 'home',
		array(
			'title'           => 'Home Page',
			'active_callback' => function() { return is_home() || is_front_page(); }
		)
	);
}
add_action( 'customize_register', 'define_customizer_panels' );


function define_customizer_sections( $wp_customize ) {
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX.'header',
		array(
			'title' => 'Header'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX.'remote_menus',
		array(
			'title' => 'Remote Menus'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'analytics',
		array(
			'title' => 'Analytics'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'footer',
		array(
			'title' => 'Header/Footer'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'uil',
		array(
			'title' => 'UIL'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'amazon_credentials',
		array(
			'title' => 'Amazon S3'
		)
	);
	$wp_customize->add_section(
		THEME_CUSTOMIZER_PREFIX . 'webfonts',
		array(
			'title' => 'Web Fonts'
		)
	);

	// Move 'Static Front Page' section to new 'Home Page' panel
	$wp_customize->get_section( 'static_front_page' )->panel = THEME_CUSTOMIZER_PREFIX . 'home';
}
add_action( 'customize_register', 'define_customizer_sections' );


/**
 * Register Customizer Controls and Settings here.
 *
 * Any new settings should be registered here with type 'theme_mod' (and NOT
 * 'option'/do not use an array key structure for ID names).
 **/

function define_customizer_fields( $wp_customize ) {

	// Remote menu
	$wp_customize->add_setting(
		'footer_menu_feed'
	);
	$wp_customize->add_control(
		'footer_menu_feed',
		array(
			'type'        => 'text',
			'label'       => 'Footer Menu Feed',
			'description' => 'The JSON feed of the www.ucf.edu footer menu.',
			'section'     => THEME_CUSTOMIZER_PREFIX.'remote_menus'
		)
	);

	// Analytics
	$wp_customize->add_setting(
		'gw_verify'
	);
	$wp_customize->add_control(
		'gw_verify',
		array(
			'type'        => 'text',
			'label'       => 'Google WebMaster Verification',
			'description' => 'Example: <em>9Wsa3fspoaoRE8zx8COo48-GCMdi5Kd-1qFpQTTXSIw</em>',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'analytics',
		)
	);

	$wp_customize->add_setting(
		'ga_account'
	);
	$wp_customize->add_control(
		'ga_account',
		array(
			'type'        => 'text',
			'label'       => 'Google Tag Manager ID',
			'description' => 'Example: <em>MTG-ABC123</em>. Leave blank for development.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'analytics'
		)
	);


	// Events
	$wp_customize->add_setting(
		'events_max_items',
		array(
			'default'     => get_setting_default( 'events_max_items' ),
		)
	);
	$wp_customize->add_control(
		'events_max_items',
		array(
			'type'        => 'select',
			'label'       => 'Events Max Items',
			'description' => 'Maximum number of events to display when outputting event information.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'events',
			'choices'     => array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5
			)
		)
	);

	$wp_customize->add_setting(
		'events_url',
		array(
			'default'     => get_setting_default( 'events_url' ),
		)
	);
	$wp_customize->add_control(
		'events_url',
		array(
			'type'        => 'text',
			'label'       => 'Events Calendar URL',
			'description' => 'Base URL for the calendar you wish to use. Example: <em>http://events.ucf.edu/mycalendar</em>',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'events'
		)
	);


	// News
	$wp_customize->add_setting(
		'news_max_items',
		array(
			'default'     => get_setting_default( 'news_max_items' ),
		)
	);
	$wp_customize->add_control(
		'news_max_items',
		array(
			'type'        => 'select',
			'label'       => 'News Max Items',
			'description' => 'Maximum number of articles to display when outputting news information.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'news',
			'choices'     => array(
				1 => 1,
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5
			)
		)
	);

	$wp_customize->add_setting(
		'news_url',
		array(
			'default'     => get_setting_default( 'news_url' ),
		)
	);
	$wp_customize->add_control(
		'news_url',
		array(
			'type'        => 'text',
			'label'       => 'News Feed',
			'description' => 'Use the following URL for the news RSS feed <br>Example: <em>http://today.ucf.edu/feed/</em>',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'news'
		)
	);


	// Search
	$wp_customize->add_setting(
		'enable_google',
		array(
			'default'     => get_setting_default( 'enable_google' ),
		)
	);
	$wp_customize->add_control(
		'enable_google',
		array(
			'type'        => 'checkbox',
			'label'       => 'Enable Google Search',
			'description' => 'Enable to use the google search appliance to power the search functionality.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'search'
		)
	);

	$wp_customize->add_setting(
		'search_domain'
	);
	$wp_customize->add_control(
		'search_domain',
		array(
			'type'        => 'text',
			'label'       => 'Search Domain',
			'description' => 'Domain to use for the built-in google search.  Useful for development or if the site needs to search a domain other than the one it occupies. Example: <em>some.domain.com</em>',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'search'
		)
	);

	$wp_customize->add_setting(
		'search_per_page',
		array(
			'default'     => get_setting_default( 'search_per_page' ),
			'type'        => 'option'
		)
	);
	$wp_customize->add_control(
		'search_per_page',
		array(
			'type'        => 'number',
			'label'       => 'Search Results Per Page',
			'description' => 'Number of search results to show per page of results',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'search',
			'input_attrs' => array(
				'min'  => 1,
				'max'  => 50,
				'step' => 1
			)
		)
	);


	// Header/Footer
	$wp_customize->add_setting(
		'default_header'
	);
	$wp_customize->add_control(
		'default_header',
		array(
			'type'        => 'text',
			'label'       => 'Default Header Image',
			'description' => 'Default header image if one is not specified for the page',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'footer'
		)
	);

	$wp_customize->add_setting(
		'contact_marketing'
	);
	$wp_customize->add_control(
		'contact_marketing',
		array(
			'type'        => 'textarea',
			'label'       => 'Contact Marketing Copy',
			'description' => 'Copy in the footer with information about how to contact marketing',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'footer'
		)
	);


	// Web Fonts
	$wp_customize->add_setting(
		'cloud_typography_key',
		array(
			'default'     => get_setting_default( 'cloud_typography_key' )
		)
	);
	$wp_customize->add_control(
		'cloud_typography_key',
		array(
			'type'        => 'text',
			'label'       => 'Cloud.Typography CSS Key URL',
			'description' => 'The CSS Key provided by Cloud.Typography for this project.  <strong>Only include the value in the "href" portion of the link
								tag provided; e.g. "//cloud.typography.com/000000/000000/css/fonts.css".</strong><br><br>NOTE: Make sure the Cloud.Typography
								project has been configured to deliver fonts to this site\'s domain.<br>
								See the <a target="_blank" href="http://www.typography.com/cloud/user-guide/managing-domains">Cloud.Typography docs on managing domains</a> for more info.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'webfonts'
		)
	);


	// UIL
	$wp_customize->add_setting(
		'uil_results_copy',
		array(
			'default'     => get_setting_default( 'uil_results_copy' )
		)
	);
	$wp_customize->add_control(
		'uil_results_copy',
		array(
			'type'        => 'textarea',
			'label'       => 'UIL Results Copy',
			'description' => 'Copy dispalyed after the UIL results heading.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'uil'
		)
	);


	// Amazon S3 Credentials
	$wp_customize->add_setting(
		'amazon_bucket',
		array(
			'default'     => get_setting_default( 'amazon_bucket' )
		)
	);
	$wp_customize->add_control(
		'amazon_bucket',
		array(
			'type'        => 'text',
			'label'       => 'Bucket',
			'description' => 'Bucket where files are stored.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'amazon_credentials'
		)
	);
	$wp_customize->add_setting(
		'amazon_folder',
		array(
			'default'     => get_setting_default( 'amazon_folder' )
		)
	);
	$wp_customize->add_control(
		'amazon_folder',
		array(
			'type'        => 'text',
			'label'       => 'Folder',
			'description' => 'Folder where files are stored under bucket.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'amazon_credentials'
		)
	);
	$wp_customize->add_setting(
		'access_key',
		array(
			'default'     => get_setting_default( 'access_key' )
		)
	);
	$wp_customize->add_control(
		'access_key',
		array(
			'type'        => 'text',
			'label'       => 'Access Key',
			'description' => 'Access key used to access the Amazon S3 API.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'amazon_credentials'
		)
	);
	$wp_customize->add_setting(
		'secret_key',
		array(
			'default'     => get_setting_default( 'secret_key' )
		)
	);
	$wp_customize->add_control(
		'secret_key',
		array(
			'type'        => 'text',
			'label'       => 'Secret Key',
			'description' => 'Secret key used to access the Amazon S3 API.',
			'section'     => THEME_CUSTOMIZER_PREFIX . 'amazon_credentials'
		)
	);

}
add_action( 'customize_register', 'define_customizer_fields' );


/**
 * Responsible for running code that needs to be executed as wordpress is
 * initializing.  Good place to register widgets, image sizes, and menus.
 *
 * @return void
 * @author Jared Lang
 * */
function __init__() {
	add_theme_support( 'menus' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'title-tag' );

	register_nav_menu( 'header-menu', __( 'Header Menu' ) );
	register_nav_menu( 'footer-menu', __( 'Footer Menu' ) );

	// add_image_size( 'my-image-size', 620 );

	// register_sidebar( array(
	// 	'name'          => __( 'Left' ),
	// 	'id'            => 'left',
	// 	'description'   => 'Sidebar found on two column page templates',
	// 	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	// 	'after_widget'  => '</aside>',
	// ) );
}
add_action( 'after_setup_theme', '__init__' );


/**
 * Register frontend scripts and stylesheets.
 **/
function enqueue_frontend_theme_assets() {
	wp_deregister_script( 'l10n' );

	// Register Config css, js
	foreach( Config::$styles as $style ) {
		if ( !isset( $style['admin'] ) || ( isset( $style['admin'] ) && $style['admin'] !== true ) ) {
			Config::add_css( $style );
		}
	}
	foreach( Config::$scripts as $script ) {
		if ( !isset( $script['admin'] ) || ( isset( $script['admin'] ) && $script['admin'] !== true ) ) {
			Config::add_script( $script );
		}
	}

	// Re-register jquery in document head
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', '//code.jquery.com/jquery-1.11.0.min.js' );
	wp_enqueue_script( 'jquery' );

	// Enqueue post-specific CSS.
	global $post;
	if ( $post ) {
		$custom_css_id = get_post_meta( $post->ID, $post->post_type . '_stylesheet', True );
		if ( $custom_css_id ) {
			wp_enqueue_style( $post->post_name . '-stylesheet', wp_get_attachment_url( $custom_css_id ) );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'enqueue_frontend_theme_assets' );

function localize_frontend_scripts() {
	wp_register_script( 'theme-script', THEME_JS_URL . '/script.min.js' );

	$translation_array = array(
		'BASE_URL' => home_url(),
	);

	wp_localize_script( 'theme-script', 'CONFIG', $translation_array );

	wp_enqueue_script( 'theme-script' );
}

add_action( 'wp_enqueue_scripts', 'localize_frontend_scripts' );


/**
 * Register backend scripts and stylesheets.
 **/
function enqueue_backend_theme_assets() {
	// Register Config css, js
	foreach( Config::$styles as $style ) {
		if ( isset( $style['admin'] ) && $style['admin'] == true ) {
			Config::add_css( $style );
		}
	}
	foreach( Config::$scripts as $script ) {
		if ( isset( $script['admin'] ) && $script['admin'] == true ) {
			Config::add_script( $script );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'enqueue_backend_theme_assets' );

?>
