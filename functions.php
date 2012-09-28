<?php
/*
Author: Tobias Bleckert
URL: htp://tb-one.se

This theme was forked from Eddie Machado (https://github.com/eddiemachado/bones) 
and now updated to fit my needs and hopefully yours.
*/

/* Include files */
require_once( 'inc/inizio.php' ); // required
require_once( 'inc/custom-post-type.php' ); // custom post type example
require_once( 'admin/theme-info.php' ); // inizio theme info

define( 'VERSION', time() ); // Change this to your asset version (you can use time() when developing)
define( 'DOCROOT', TEMPLATEPATH . '/' );
define( 'WEBROOT', get_bloginfo( 'template_directory' ) . '/' );
define( 'LANG_DOMAIN', 'inizio' ); // Change this to your theme name (in lowercase, separate words via underscores)
define( 'LOCALE', 'en' ); // You can set this to use a different language than what is set in the admin

Inizio::init();

$assets = array(
	array(
		'do'      => 'enqueue',
		'type'    => 'script',
		'handle'  => 'inizio-modernizr',
		'src'     => WEBROOT . 'assets/js/libs/modernizr.custom.min.js',
		'deps'    => false,
		'version' => '2.5.3'
	),
	
	array(
		'do'      => 'enqueue',
		'type'    => 'style',
		'handle'  => 'inizio-stylesheet',
		'src'     => WEBROOT . 'assets/css/style.css',
		'deps'    => false,
		'version' => VERSION
	),
	
	array(
		'do'      => 'enqueue',
		'type'    => 'script',
		'handle'  => 'inizio-js',
		'src'     => WEBROOT . 'assets/js/scripts.js',
		'deps'    => array('jquery'),
		'version' => VERSION,
		'in_footer' => true
	),
);

Inizio::assets( $assets );

// Thumbnail sizes
$image_sizes = array(
	'default'  => array(
		'width'  => 125,
		'height' => 125,
		'crop'   => true
	),
	'inizio-thumb-600' => array(
		'label'  => __( 'Inizio Thumb 600', LANG_DOMAIN ),
		'width'  => 600,
		'height' => 150,
		'crop'   => true
	),
	'inizio-thumb-300' => array(
		'label'  => __( 'Inizio Thumb 300', LANG_DOMAIN ),
		'width'  => 300,
		'height' => 100,
		'crop'   => true
	)
);

// We use this function to add the image sizes and also to show them in the media box
Inizio::addImageSizes( $image_sizes );

// Theme features
$support = array(
	'post-thumbnails',
	'custom-background' => array(
		'default-image'          => '',  // background image default
		'default-color'          => '', // background color default (dont add the #)
		'wp-head-callback'       => '_custom_background_cb',
		'admin-head-callback'    => '',
		'admin-preview-callback' => ''
	),
	'automatic-feed-links',
	'post-formats'      => array(
		'aside',             // title less blurb
		'gallery',           // gallery of images
		'link',              // quick link to other site
		'image',             // an image
		'quote',             // a quick quote
		'status',            // a Facebook like status update
		'video',             // video 
		'audio',             // audio
		'chat'               // chat transcript 
	),
	'menus'
);

Inizio::themeSupport( $support );  

// Add menus
Inizio::addMenus(array( 
	'main-nav'     => __( 'The Main Menu', LANG_DOMAIN ), // main nav in header
	'footer-links' => __( 'Footer Links', LANG_DOMAIN ) // secondary nav in footer
));

// Add example sidebar
$sidebars = array(
	array(
		'id'            => 'sidebar1',
		'name'          => __( 'Sidebar 1', LANG_DOMAIN ),
		'description'   => __( 'The first (primary) sidebar.', LANG_DOMAIN ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widgettitle">',
		'after_title'   => '</h4>',
	)
);

Inizio::addSidebars( $sidebars );

// Customize the login page
Inizio::customizeLogin( get_stylesheet_directory_uri() . '/admin/assets/css/login.css' );

// Remove a admin menu item
Inizio::removeFromMenu( 'comments' );

// Hide the default wordpress widgets
Inizio::hideFromDashboard(array(
	//'recent_comments', auto removed when removing comments from the admin menu
	'incoming_links',
	'right_now',
	'plugins',
	'blog',
	'news',
	'quick_press',
	'recent_drafts'
));

?>