<?php

/**
* The heart of Inizio
*
* This is a collection of useful functions to use in your wordpress theme.
* You should not add theme specific functions to this class.
* Make yourself a new class with the name of your theme and add your functions there.
*
* If you miss a function that you feel is general, add it here.
* Also, if you like to share it you can either contact me or fork this project and make a pull request with your newly added function.
*
* @author  Tobias Bleckert <tbleckert@gmail.com>
* @author  Eddie Machado <http://themble.com>
* @version 1.0
*/

// Include init functions
require_once('initial.php');

class Inizio extends Initial {

	/**
	 * init
	 *
	 * This function should be called in your functions.php.
	 * This function will clean up wordpress and is recomended to use
	 *
	 * @author Eddie Machado <http://themble.com>
	 */
	
	public function init() {
		add_action('after_setup_theme', self::_init(), 15);
	}
	
	/**
	 * _init
	 *
	 * Called by init and should not be called directly
	 *
	 * @author Eddie Machado <http://themble.com>
	 */
	
	public function _init() {
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
	
	/**
	 * Theme Support
	 *
	 * A handy wrapper to add theme supports via add_theme_support
	 * Call it like this
	 *
	 *    $support = array(
	 *   	 	'post-thumbnails',
	 *   	 	'automatic-feed-links',
	 *   	 	'post-formats'      => array(
	 *   	 		'aside',             // title less blurb
	 *   	 		'gallery',           // gallery of images
	 *   	 		'link',              // quick link to other site
	 *   	 		'image',             // an image
	 *   	 		'quote',             // a quick quote
	 *   	 		'status',            // a Facebook like status update
	 *  	 		'video',             // video 
	 *   	 		'audio',             // audio
	 *   	 		'chat'               // chat transcript 
	 *   	 	),
	 *   	 	'menus'
	 *   	);
	 *
	 *    Inizio::themeSupport($support)
	 *
	 * @param  array see accepted input by visiting the link
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 * @link   http://codex.wordpress.org/Function_Reference/add_theme_support
	 */
	
	public function themeSupport($support = array()) {
		if (is_array($support)) {
			$register_theme_support = function () use ($support) {
				foreach ($support as $feature => $specific) {
					if (is_array($specific)) {
						add_theme_support($feature, $specific);
					} else {
						add_theme_support($specific);
					}
				}
			};
			
			add_action('after_setup_theme', $register_theme_support);
		}
	}
	
	/**
	 * Assets
	 *
	 * This little function handles all your assets
	 * Call it like this
	 *
	 *    $assets = array(
	 *  	 	array(
	 *   	 		'do'      => 'enqueue',
	 *   	 		'type'    => 'style',
	 *   	 		'handle'  => 'inizio-stylesheet',
	 *   	 		'src'     => get_stylesheet_directory_uri() . '/assets/css/style.css',
	 *   	 		'deps'    => false,
	 *   	 		'version' => VERSION
	 *   	 	),
	 *   	 	array(
	 *   	 		'do'      => 'enqueue',
	 *   	 		'type'    => 'script',
	 *   	 		'handle'  => 'inizio-js',
	 *   	 		'src'     => get_stylesheet_directory_uri() . '/assets/js/scripts.js',
	 *   	 		'deps'    => array('jquery'),
	 *   	 		'version' => VERSION,
	 *   	 		'in_footer' => true
	 *   	 	),
	 *   	 );
	 *   	 
	 *   	 Inizio::assets($assets);
	 *
	 * @param  array
	 *
	 *         possible values:
	 *         - do:   register | enqueue (default register)
	 *         - type: script | style (default script)
	 *
	 *         - and all attributes listed in the link
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 * @link   http://codex.wordpress.org/Function_Reference/wp_enqueue_script
	 */
	
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
					$asset = wp_parse_args($asset, $defaults);
					extract($asset, EXTR_SKIP);
					
					if ($type == 'script') {
						if ($do == 'enqueue') {
							wp_enqueue_script( $handle, $src, $deps, $version, $in_footer);
						} else {
							wp_register_script( $handle, $src, $deps, $version, $in_footer);
						}
					} else {
						if ($do == 'enqueue') {
							wp_enqueue_style( $handle, $src, $deps, $version, $in_footer);
						} else {
							wp_register_style($handle, $src, $deps, $version, $in_footer);
						}
					}
				}
				
				wp_deregister_script( 'jquery' ); // deregisters the default WordPress jQuery
				wp_enqueue_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', false, '1.7.2', true); // register the external file
			}
		};
		
		add_action('wp_enqueue_scripts', $addAssets, 999);
	}
	
	/**
	 * Add menus
	 *
	 * A simple function to simplify adding menus.
	 *
	 * @param  array
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 * @link   http://codex.wordpress.org/Function_Reference/register_nav_menus
	 */
	
	public function addMenus($menus) {
		$register_menus = function () use ($menus) {
			register_nav_menus($menus);
		};
	
		add_action('after_setup_theme', $register_menus);
	}
	
	/**
	 * Add image sizes
	 *
	 * Handles the adding of custom image sizes.
	 * You can specify the default thumbnail size and custom sizes.
	 * This function will also add those sizes to the admin media box.
	 *
	 * Use it like this
	 *
	 *   	 $image_sizes = array(
	 *   	 	'default'         => array(
	 *   	 		'width'  => 125,
	 *   	 		'height' => 125,
	 *   	 		'crop'   => true
	 *   	 	),
	 *   	 	'inizio-thumb-600' => array(
	 *   	 		'label'  => __('Inizio Thumb 600', 'iniziotheme'),
	 *   	 		'width'  => 600,
	 *   	 		'height' => 150,
	 *   	 		'crop'   => true
	 *   	 	),
	 *   	 	'inizio-thumb-300' => array(
	 *  	 		'label'  => __('Inizio Thumb 300', 'iniziotheme'),
	 *   	 		'width'  => 300,
	 *   	 		'height' => 100,
	 *   	 		'crop'   => true
	 *   	 	)
	 *   	 );
	 *   	 
	 *   	 Inizio::addImageSizes($image_sizes);
	 *
	 * @param  array
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 * @author Eddie Machado <http://themble.com>
	 * @link   http://codex.wordpress.org/Function_Reference/add_image_size
	 */
	
	public function addImageSizes($sizes) {
		$defaults = array(
			'width'  => 0,
			'height' => 0,
			'crop'   => false
		);
		
		if (is_array($sizes)) {
			$register_image_sizes = function () use ($sizes) {
				$labels = array();
				
				foreach($sizes as $name => $size) {
					$size = wp_parse_args($size, $defaults);
					extract($size, EXTR_SKIP);
					
					if ($name == 'default') {
						set_post_thumbnail_size($width, $height, $crop);
					} else {
						add_image_size($name, $width, $height, $crop);
						if ($label) $labels[$name] = $label;
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
	
	/**
	 * Add sidebars
	 *
	 * Use this to register sidebars.
	 * It's just a wrapper so that you don't have to call add_action yourself.
	 *
	 * @param  array
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 * @link   http://codex.wordpress.org/Function_Reference/register_sidebar
	 */
	
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
	
	/**
	 * Customize Login
	 *
	 * Function to let you customize the login page.
	 * Simply call this function with the link to a css file as a parameter
	 *
	 * @param string
	 * @author Eddie Machado <http://themble.com>
	 */
	
	public function customizeLogin($stylesheet) {
		$login_css = function () use ($stylesheet) {
		 wp_enqueue_style('login-css', $stylesheet);
		};
		
		add_action('login_head', $login_css);
		
		function bones_login_title() { return get_option('blogname'); }
		
		add_filter('login_headertitle', 'bones_login_title');
	}
		
	/* http://wp-snippets.com/pagination-without-plugin/ */
	public function pagination($prev = '«', $next = '»') {
		global $wp_query, $wp_rewrite;
		$wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
		
		$pagination = array(
			'base' => @add_query_arg('paged','%#%'),
			'format' => '',
			'total' => $wp_query->max_num_pages,
			'current' => $current,
			'prev_text' => __($prev),
			'next_text' => __($next),
			'type' => 'plain'
		);
		
		if( $wp_rewrite->using_permalinks() )
			$pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg( 's', get_pagenum_link( 1 ) ) ) . 'page/%#%/', 'paged' );
		
		if( !empty($wp_query->query_vars['s']) )
			$pagination['add_args'] = array( 's' => get_query_var( 's' ) );
		
		echo paginate_links( $pagination );
	}
	
	/**
	 * Remove from menu
	 *
	 * It's always good to remove/hide stuff that you don't use.
	 * This function simply remove menu items from the admin menu.
	 *
	 * @param  string/array string or array containing the items you want to remove
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 */
	public function removeFromMenu($remove) {
		global $menu;
		
		$menu = array(
			'posts'    => 'edit.php',
			'media'    => 'upload.php',
			'links'    => 'link-manager.php',
			'comments' => 'edit-comments.php',
			'themes'   => 'themes.php',
			'plugins'  => 'plugins.php',
			'users'    => 'users.php',
			'tools'    => 'tools.php',
			'options'  => 'options-general.php'
		);
		
		$my_remove_menu_pages = function () use ($remove) {
			global $menu;
			
			if (is_array($remove)) {
				foreach ($remove as $item) {
					if (isset($menu[$item])) remove_menu_page($menu[$item]);
					if ($item == 'comments') self::hideFromDashboard('recent_comments');
				}
			} else {
				remove_menu_page($menu[$remove]);
				if ($remove == 'comments') Inizio::hideFromDashboard('recent_comments');
			}
			
			unset($menu);
		};
		
		add_action( 'admin_menu', $my_remove_menu_pages );
	}
	
	/**
	 * Is ajax?
	 *
	 * Simple function to check if the request was an ajax request.
	 *
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 * @return bool true|false
	 */
	
	public function is_ajax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
	}
	
	/**
	 * Get posts with thumbnail
	 *
	 * Returns an array of posts that has a thumbnail.
	 *
	 * @param  string post type
	 * @param  int    number of posts
	 * @return array  posts
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 */
	 
	public function getPostsWithThumbnail($post_type = 'post', $limit = 5) {
		$posts = get_posts(array(
			'post_type'   => $post_type, 
			'numberposts' => $limit, 
			'meta_key'    => '_thumbnail_id'
		));
		
		return $posts;
	}
	
	/* http://wp-snippets.com/limit-excerpt-words/ */
	public function limit_words($string, $word_limit) {
		$words = explode(' ', $string);
		return implode(' ', array_slice($words, 0, $word_limit));
	}
	
	/**
	 * Hide dashboard widgets
	 *
	 * Hide widgets from the dashboards by adding a string or array of widget names
	 *
	 * @param string/array of widget names, right now you can use
	 *
	 *     right_now
	 *     recent_comments
	 *     incoming_links
	 *     recent_drafts
	 *     blog
	 *     news
	 *     quick_press
	 *     plugins
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 */
	 
	public function hideFromDashboard($widgets) {
		$dashboard_widgets = function () use ($widgets) {
			$real_widget = array(
				'right_now'       => array('normal', 'dashboard_right_now'),
				'recent_comments' => array('normal', 'dashboard_recent_comments'),
				'incoming_links'  => array('normal', 'dashboard_incoming_links'),
				'recent_drafts'   => array('side',   'dashboard_recent_drafts'),
				'blog'            => array('side',   'dashboard_primary'),
				'news'            => array('side',   'dashboard_secondary'),
				'quick_press'     => array('side',   'dashboard_quick_press'),
				'plugins'         => array('normal', 'dashboard_plugins')
			);
			
			if (is_array($widgets)) {
				foreach($widgets as $widget) {
					remove_meta_box( $real_widget[$widget][1], 'dashboard', $real_widget[$widget][0] );
				}
			} else {
				remove_meta_box( $real_widget[$widgets][1], 'dashboard', $real_widget[$widgets][0] );
			}
		};
		
		add_action('wp_dashboard_setup', $dashboard_widgets);
	}

}