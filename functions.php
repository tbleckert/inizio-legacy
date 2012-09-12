<?php
/*
Author: Tobias Bleckert
URL: htp://tb-one.se

Original author: Eddie Machado
URL: htp://themble.com/bones/

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
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );


?>