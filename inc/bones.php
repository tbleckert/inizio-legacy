<?php
/* Welcome to Bones :)
This is the core Bones file where most of the
main functions & features reside. If you have 
any custom functions, it's best to put them
in the functions.php file.

Developed by: Eddie Machado
URL: http://themble.com/bones/
*/

/*********************
LAUNCH BONES
Let's fire off all the functions
and tools. I put it up here so it's
right up top and clean.
*********************/

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
    
    // launching this stuff after theme setup
    add_action('after_setup_theme', array('Bones', 'theme_support'));	
    // adding sidebars to Wordpress (these are created in functions.php)
    add_action( 'widgets_init', array('Bones', 'register_sidebars'));
    // adding the bones search form (created in functions.php)
    add_filter( 'get_search_form', array('Bones', 'wpsearch') );
    
    // cleaning up random code around images
    add_filter('the_content', array('Bones', 'filter_ptags_on_images'));
    // cleaning up excerpt
    add_filter('excerpt_more', array('Bones', 'excerpt_more'));
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
	
	// the main menu 
	public function main_nav() {
		// display the wp3 menu if available
	    wp_nav_menu(array( 
	    	'container' => false,                           // remove nav container
	    	'container_class' => 'menu clearfix',           // class of container (should you choose to use it)
	    	'menu' => 'The Main Menu',                      // nav name
	    	'menu_class' => 'nav top-nav clearfix',         // adding custom nav class
	    	'theme_location' => 'main-nav',                 // where it's located in the theme
	    	'before' => '',                                 // before the menu
	        'after' => '',                                  // after the menu
	        'link_before' => '',                            // before each link
	        'link_after' => '',                             // after each link
	        'depth' => 0,                                   // limit the depth of the nav
	    	'fallback_cb' => 'main_nav_fallback'      // fallback function
		));
	} /* end bones main nav */
	
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
	    	'fallback_cb' => 'footer_links_fallback'  // fallback function
		));
	} /* end bones footer link */
	
}