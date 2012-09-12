<?php
/*
Author: Tobias Bleckert
URL: htp://tb-one.se

This theme was forked from Eddie Machado (https://github.com/eddiemachado/bones) 
and now updated to fit my needs and hopefully yours.
*/

/* Include files */
require_once('inc/bones.php'); // required
require_once('inc/custom-post-type.php'); // optional

Bones::ahoy();

$assets = array(
	array(
		'do'      => 'enqueue',
		'type'    => 'script',
		'handle'  => 'bones-modernizr',
		'src'     => get_stylesheet_directory_uri() . '/assets/js/libs/modernizr.custom.min.js',
		'deps'    => false,
		'version' => '2.5.3'
	),
	
	array(
		'do'      => 'enqueue',
		'type'    => 'style',
		'handle'  => 'bones-stylesheet',
		'src'     => get_stylesheet_directory_uri() . '/assets/css/style.css',
		'deps'    => false,
		'version' => VERSION
	),
	
	array(
		'do'      => 'enqueue',
		'type'    => 'script',
		'handle'  => 'bones-js',
		'src'     => get_stylesheet_directory_uri() . '/assets/js/scripts.js',
		'deps'    => array('jquery'),
		'version' => VERSION,
		'in_footer' => true
	),
);

Bones::assets($assets);

// Thumbnail sizes
$image_sizes = array(
	'default'         => array(
		'width'  => 125,
		'height' => 125,
		'crop'   => true
	),
	'bones-thumb-600' => array(
		'label'  => __('Bones Thumb 600', 'bonestheme'),
		'width'  => 600,
		'height' => 150,
		'crop'   => true
	),
	'bones-thumb-300' => array(
		'label'  => __('Bones Thumb 300', 'bonestheme'),
		'width'  => 300,
		'height' => 100,
		'crop'   => true
	)
);

// We use this function to add the image sizes and also to show them in the media box
Bones::addImageSizes($image_sizes);

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

Bones::themeSupport($support);  

// registering wp3+ menus          
/*register_nav_menus(                      
	array( 
		'main-nav' => __( 'The Main Menu', 'bonestheme' ),   // main nav in header
		'footer-links' => __( 'Footer Links', 'bonestheme' ) // secondary nav in footer
	)
);*/

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

Bones::addSidebars($sidebars);

?>