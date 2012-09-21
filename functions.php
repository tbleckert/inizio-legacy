<?php
/*
Author: Tobias Bleckert
URL: htp://tb-one.se

This theme was forked from Eddie Machado (https://github.com/eddiemachado/bones) 
and now updated to fit my needs and hopefully yours.
*/

/* Include files */
require_once('inc/inizio.php'); // required
require_once('inc/custom-post-type.php'); // optional

define('VERSION', time()); // Change this to your asset version (you can use time() when developing)
define('WEBROOT', TEMPLATEPATH . '/');
define('DOCROOT', get_bloginfo('template_directory') . '/'); 

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

Inizio::assets($assets);

// Thumbnail sizes
$image_sizes = array(
	'default'  => array(
		'width'  => 125,
		'height' => 125,
		'crop'   => true
	),
	'inizio-thumb-600' => array(
		'label'  => __('Inizio Thumb 600', 'iniziotheme'),
		'width'  => 600,
		'height' => 150,
		'crop'   => true
	),
	'inizio-thumb-300' => array(
		'label'  => __('Inizio Thumb 300', 'iniziotheme'),
		'width'  => 300,
		'height' => 100,
		'crop'   => true
	)
);

// We use this function to add the image sizes and also to show them in the media box
Inizio::addImageSizes($image_sizes);

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

Inizio::themeSupport($support);  

// Add menus
Inizio::addMenus(array( 
	'main-nav' => __( 'The Main Menu', 'iniziotheme' ),   // main nav in header
	'footer-links' => __( 'Footer Links', 'iniziotheme' ) // secondary nav in footer
));

$sidebars = array(
	array(
		'id' => 'sidebar1',
		'name' => 'Sidebar 1',
		'description' => 'The first (primary) sidebar.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	)
);

Inizio::addSidebars($sidebars);

// Customize the login page
Inizio::customizeLogin(get_stylesheet_directory_uri() . '/admin/assets/css/login.css');

// Remove a admin menu item
Inizio::removeFromMenu('comments');

?>