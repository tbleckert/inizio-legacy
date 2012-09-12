<?php
/* Welcome to Bones :)
This is the core Bones file where most of the
main functions & features reside. If you have 
any custom functions, it's best to put them
in the functions.php file.

Developed by: Eddie Machado
URL: http://themble.com/bones/
*/

require_once('initial.php');

class Bones extends Initial {
	public static $assetsArr = array();


	public function ahoy() {
		add_action('after_setup_theme', self::init(), 15);
	}
	
	public function init() {
		// launching operation cleanup
    add_action('init', array('Bones', 'head_cleanup'));
    // remove WP version from RSS
    add_filter('the_generator', array('Bones', 'rss_version'));
    // remove pesky injected css for recent comments widget
    add_filter( 'wp_head', array('Bones', 'remove_wp_widget_recent_comments_style'), 1);
    // clean up comment styles in the head
    add_action('wp_head', array('Bones', 'remove_recent_comments_style'), 1);
    // clean up gallery output in wp
    add_filter('gallery_style', array('Bones', 'gallery_style'));
    
    // cleaning up random code around images
    add_filter('the_content', array('Bones', 'filter_ptags_on_images'));
    // cleaning up excerpt
    add_filter('excerpt_more', array('Bones', 'excerpt_more'));
	}
	
	public function themeSupport($support = array()) {
		if (is_array($support)) {
			global $support;
			
			function register_theme_support() {
				global $support;
				
				foreach ($support as $feature => $specific) {
					if (is_array($specific)) {
						add_theme_support($feature, $specific);
					} else {
						add_theme_support($feature);
					}
				}
				
				unset($support);
			}
			
			add_action('after_setup_theme', 'register_theme_support');
		}
	}
	
	public static function assets(array $assets) {
		global $assets;
		
		add_action('wp_enqueue_scripts', array('Bones', '_assets'), 999);
	}
	
	public function _assets() {
		global $assets;
		
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
			
			$url = 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js'; // the URL to check against
			$test_url = @fopen($url,'r'); // test parameters
			
			if($test_url !== false) { // test if the URL exists
				wp_deregister_script( 'jquery' ); // deregisters the default WordPress jQuery
				wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', false, '1.7.2', true); // register the external file
			} else {
				function ds_print_jquery_in_footer( &$scripts) {
					if ( ! is_admin() )
						$scripts->add_data( 'jquery', 'group', 1 );
				}
				add_action( 'wp_default_scripts', 'ds_print_jquery_in_footer' );
			}
		}
		
		unset($assets);
	}
	
	public function addImageSizes($sizes) {
		if (is_array($sizes)) {
			$labels = array();
			global $image_sizes;
			
			$image_sizes = $sizes;
			
			function register_image_sizes() {
				global $image_sizes;
				global $labels;	
				
				foreach($image_sizes as $name => $size) {
					if ($name == 'default') {
						set_post_thumbnail_size($size['height'], $size['crop']);
					} else {
						add_image_size($name, $size['width'], $size['height'], $size['crop']);
						$labels[$name] = $size['label'];
					}
				}
				
				add_filter('image_size_names_choose', 'image_sizes');
				
				function image_sizes($sizes) {
					global $labels;
					
					$newsizes = array_merge($sizes, $labels);
					return $newsizes;
					
					unset($labels);
				}
			}
			
			unset($image_sizes);
			
			add_action('after_setup_theme', 'register_image_sizes');
		}
	}
	
	// the main menu 
	public function main_nav() {
		// display the wp3 menu if available
		wp_nav_menu(array( 
			'container'       => false,                  // remove nav container
			'container_class' => 'menu clearfix',        // class of container (should you choose to use it)
			'menu'            => 'The Main Menu',        // nav name
			'menu_class'      => 'nav top-nav clearfix', // adding custom nav class
			'theme_location'  => 'main-nav',             // where it's located in the theme
			'before'          => '',                     // before the menu
			'after'           => '',                     // after the menu
			'link_before'     => '',                     // before each link
			'link_after'      => '',                     // after each link
			'depth'           => 0,                      // limit the depth of the nav
			'fallback_cb'     => 'main_nav_fallback'     // fallback function
		));
	}
	
	// the footer menu (should you choose to use one)
	public function footer_links() { 
		// display the wp3 menu if available
    wp_nav_menu(array( 
    	'container' => '',                              // remove nav container
    	'container_class' => 'footer-links clearfix',   // class of container (should you choose to use it)
    	'menu' => 'Footer Links',                       // nav name
    	'menu_class' => 'nav footer-nav clearfix',      // adding custom nav class
    	'theme_location' => 'footer-links',             // where it's located in the theme
    	'before' => '',                                 // before the menu
      'after' => '',                                  // after the menu
      'link_before' => '',                            // before each link
      'link_after' => '',                             // after each link
      'depth' => 0,                                   // limit the depth of the nav
    	'fallback_cb' => 'footer_links_fallback'        // fallback function
		));
	}
	
	// Sidebars & Widgetizes Areas
	public function addSidebars($addSidebars = array()) {
		if (is_array($addSidebars)) {
			global $sidebars;
			$sidebars = $addSidebars;
			
			function register_new_sidebars() {
				global $sidebars;
				
				foreach($sidebars as $sidebar) {
					register_sidebar($sidebar);
				}
			}
				
			add_action('widgets_init', 'register_new_sidebars');
		}
	}
	
}