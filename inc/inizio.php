<?php

/* Welcome to Inizio :)
This is the core Inizio file where most of the
main functions & features reside. If you have 
any custom functions, it's best to put them
in the functions.php file.

Developed by: Tobias Bleckert
URL: http://tbleckert.github.com/inizio/
*/

require_once('initial.php');

class Inizio extends Initial {
	public static $assetsArr = array();


	public function ahoy() {
		add_action('after_setup_theme', self::init(), 15);
	}
	
	public function init() {
		// launching operation cleanup
    add_action('init', array('Inizio', 'head_cleanup'));
    // remove WP version from RSS
    add_filter('the_generator', array('Inizio', 'rss_version'));
    // remove pesky injected css for recent comments widget
    add_filter( 'wp_head', array('Inizio', 'remove_wp_widget_recent_comments_style'), 1);
    // clean up comment styles in the head
    add_action('wp_head', array('Inizio', 'remove_recent_comments_style'), 1);
    // clean up gallery output in wp
    add_filter('gallery_style', array('Inizio', 'gallery_style'));
    
    // cleaning up random code around images
    add_filter('the_content', array('Inizio', 'filter_ptags_on_images'));
    // cleaning up excerpt
    add_filter('excerpt_more', array('Inizio', 'excerpt_more'));
	}
	
	public function themeSupport($support = array()) {
		if (is_array($support)) {
			$register_theme_support = function () use ($support) {
				foreach ($support as $feature => $specific) {
					if (is_array($specific)) {
						add_theme_support($feature, $specific);
					} else {
						add_theme_support($feature);
					}
				}
			};
			
			add_action('after_setup_theme', $register_theme_support);
		}
	}
	
	public static function assets(array $assets) {
		$addAssets = function () use ($assets) {
			if (!is_admin()) {
				$defaults = array(
					'do'        => 'register',
					'type'      => 'script',
					'in_footer' => false,
					'deps'      => array(),
					'version'   => false
				);
				
				foreach($assets as $asset) {
					
					if ($asset['type'] == 'script') {
						if ($asset['do'] == 'enqueue') {
							wp_enqueue_script( $asset['handle'], $asset['src'], $asset['deps'], $asset['version'], $asset['in_footer']);
						} else {
							wp_register_script( $asset['handle'], $asset['src'], $asset['deps'], $asset['version'], $asset['in_footer']);
						}
					} else {
						if ($asset['do'] == 'enqueue') {
							wp_enqueue_style( $asset['handle'], $asset['src'], $asset['deps'], $asset['version'], $asset['in_footer']);
						} else {
							wp_register_style( $asset['handle'], $asset['src'], $asset['deps'], $asset['version'], $asset['in_footer']);
						}
					}
				
				}
				
				wp_deregister_script( 'jquery' ); // deregisters the default WordPress jQuery
				wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', false, '1.7.2', true); // register the external file
			}
		};
		
		add_action('wp_enqueue_scripts', $addAssets, 999);
	}
	
	public function addMenus($menus) {
		$register_menus = function () use ($menus) {
			register_nav_menus($menus);
		};
		
		add_action('after_setup_theme', $register_menus);
	}
	
	public function addImageSizes($sizes) {
		if (is_array($sizes)) {
			$register_image_sizes = function () use ($sizes) {
				$labels = array();
				
				foreach($sizes as $name => $size) {
					if ($name == 'default') {
						set_post_thumbnail_size($size['height'], $size['crop']);
					} else {
						add_image_size($name, $size['width'], $size['height'], $size['crop']);
						$labels[$name] = $size['label'];
					}
				}
				
				$image_sizes = function ($sizes) use ($labels) {
					$newsizes = array_merge($sizes, $labels);
					return $newsizes;
				};
				
				add_filter('image_size_names_choose', $image_sizes);
			};
			
			add_action('after_setup_theme', $register_image_sizes);
		}
	}
	
	// Sidebars & Widgetizes Areas
	public function addSidebars($addSidebars = array()) {
		if (is_array($addSidebars)) {
			$register_new_sidebars = function () use ($addSidebars) {
				foreach($addSidebars as $sidebar) {
					register_sidebar($sidebar);
				}
			};
				
			add_action('widgets_init', $register_new_sidebars);
		}
	}
	
}