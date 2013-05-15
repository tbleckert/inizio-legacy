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
	 * @param  string directory where you keep your language files (relative to your theme directory)
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 * @author Eddie Machado <http://themble.com>
	 */
	
	public function init( $language_directory = 'languages' ) {
		global $locale_directory;
		$locale_directory = get_template_directory() . '/languages/';
		
		add_action( 'after_setup_theme', self::_init(), 15 );
	}
	
	/**
	 * _init
	 *
	 * Called by init and should not be called directly
	 *
	 * @author Eddie Machado <http://themble.com>
	 */
	
	public function _init() {
		global $locale_directory;
		$locale = get_locale();
		
		load_theme_textdomain( LANG_DOMAIN, $locale_directory );
		$locale_file = $locale_directory . '/' . $locale . '.php';
		
		if ( is_readable( $locale_file ) )
			require_once( $locale_file );
		
		// launching operation cleanup
		add_action( 'init', array( 'Inizio', 'head_cleanup' ) );
		
		// remove WP version from RSS
		add_filter( 'the_generator', array('Inizio', 'rss_version' ) );
		
		// remove pesky injected css for recent comments widget
		add_filter( 'wp_head', array( 'Inizio', 'remove_wp_widget_recent_comments_style' ), 1);
		
		// clean up comment styles in the head
		add_action( 'wp_head', array( 'Inizio', 'remove_recent_comments_style' ), 1 );
		
		// clean up gallery output in wp
		add_filter( 'gallery_style', array( 'Inizio', 'gallery_style' ) );
		
		// cleaning up random code around images
		add_filter( 'the_content', array( 'Inizio', 'filter_ptags_on_images' ) );
	}
	
	/**
	 * Theme Info
	 *
	 * Adds a menu page that shows some information about the theme.
	 * Call it like this: Inizio::info()
	 *
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 * @link   http://codex.wordpress.org/Function_Reference/add_menu_page
	 */
	
	public function info() {
		$register_theme_info_menu_page = function () {
			$content = function () {
				include( TEMPLATEPATH . '/admin/theme-info.php' );
			};
		
			add_menu_page( 'Inizio theme info', 'Inizio', 'manage_options', 'theme-info.php', false, 'div', 61 );
		};
		
		add_action( 'admin_menu', $register_theme_info_menu_page );
		
		// Theme info icon
		$themeinfo_icon = function () { ?>
			<style type="text/css" media="screen">
					#toplevel_page_theme-info .wp-menu-image { background: url(<?php echo get_stylesheet_directory_uri() . '/admin/assets/img/icons/information.png' ?>) no-repeat 6px -17px !important; }
					#toplevel_page_theme-info:hover .wp-menu-image, #toplevel_page_theme-info.wp-has-current-submenu .wp-menu-image { background-position:6px 7px!important; }
			</style>
		<?php };
		
		add_action( 'admin_head', $themeinfo_icon );
	}
	
	public function artDirectedPosts() {
		// Add custom CSS if it's a single post or page, or if the posts per page count is 1
		$addCustomCSS = function () {
			global $post;
			
			$posts = get_option('posts_per_page');
			
			if ( is_single() || is_page() || 1 == $posts ) {
				echo '<style>' . get_post_meta( $post->ID, '_custom-css', true ) . '</style>';
			}
		};
		
		add_action( 'wp_head', $addCustomCSS );
		
		$addCustomJS = function () {
			global $post;
			
			$posts = get_option('posts_per_page');
			
			if ( is_single() || is_page() || 1 == $posts ) {
				echo '<script>' . get_post_meta( $post->ID, '_custom-js', true ) . '</script>';
			}
		};
		
		add_action( 'wp_footer', $addCustomJS );
			
		// Custom CSS
		function add_custom_css_box() {
		  add_meta_box( 'custom-css-meta-box', __( 'Custom CSS', LANG_DOMAIN ), 'custom_css_box_inside', 'post', 'normal', 'high' );
		  add_meta_box( 'custom-css-meta-box', __( 'Custom CSS', LANG_DOMAIN ), 'custom_css_box_inside', 'page', 'normal', 'high' );
		}
		
		add_action('add_meta_boxes', 'add_custom_css_box', 1);
		
		function custom_css_box_inside( $post ) {
		  include( DOCROOT . 'admin/custom-css-meta-box.php' );
		}
		
		function save_custom_css_box($post_id) {
		  if ( ! isset( $_POST['custom_css_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['custom_css_meta_box_nonce'], 'custom_css_meta_box' ) ) {
		    return $post_id;
		  }
		  
		  // check capabilities
		  if ( 'post' == $_POST['post_type'] ) {
		    if ( ! current_user_can( 'edit_post', $post_id ) ) {
		      return $post_id;
		    }
		  } elseif ( ! current_user_can( 'edit_page', $post_id ) ) {
		    return $post_id;
		  }
		  
		  // exit on autosave
		  if ( defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		    return $post_id;
		  }
		  
		  if ( isset( $_POST['_custom-css'] ) ) {
		    update_post_meta( $post_id, '_custom-css', $_POST['_custom-css'] );
		  } else {
		    delete_post_meta( $post_id, '_custom-css' );
		  }
		}
		
		add_action( 'save_post', 'save_custom_css_box' );
		
		// Custom JS
		function add_custom_js_box() {
		  add_meta_box( 'custom-js-meta-box', __( 'Custom JS', LANG_DOMAIN ), 'custom_js_box_inside', 'post', 'normal', 'high' );
		  add_meta_box( 'custom-js-meta-box', __( 'Custom JS', LANG_DOMAIN ), 'custom_js_box_inside', 'page', 'normal', 'high' );
		}
		
		add_action('add_meta_boxes', 'add_custom_js_box', 1);
		
		function custom_js_box_inside( $post ) {
		  include( DOCROOT . 'admin/custom-js-meta-box.php' );
		}
		
		function save_custom_js_box($post_id) {
		  if ( ! isset( $_POST['custom_js_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['custom_js_meta_box_nonce'], 'custom_js_meta_box' ) ) {
		    return $post_id;
		  }
		  
		  // check capabilities
		  if ( 'post' == $_POST['post_type'] ) {
		    if ( ! current_user_can( 'edit_post', $post_id ) ) {
		      return $post_id;
		    }
		  } elseif ( ! current_user_can( 'edit_page', $post_id ) ) {
		    return $post_id;
		  }
		  
		  // exit on autosave
		  if ( defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		    return $post_id;
		  }
		  
		  if ( isset( $_POST['_custom-js'] ) ) {
		    update_post_meta( $post_id, '_custom-js', $_POST['_custom-js'] );
		  } else {
		    delete_post_meta( $post_id, '_custom-js' );
		  }
		}
		
		add_action( 'save_post', 'save_custom_js_box' );
		
		// Assets for custom CSS and JS
		function add_custom_css_js_assets( $hook_suffix ) {
		  if ( 'post.php' == $hook_suffix || 'page.php' == $hook_suffix ) {
		  	wp_enqueue_style( 'codemirror-style', WEBROOT . '/admin/assets/css/lib/codemirror.css', false, '3.12');
		  	wp_enqueue_script( 'codemirror', WEBROOT . '/admin/assets/js/lib/codemirror/codemirror.js', false, '3.12', true);
		  	wp_enqueue_script( 'codemirror-js', WEBROOT . '/admin/assets/js/lib/codemirror/mode/javascript.js', array('codemirror'), '3.12', true);
		  	wp_enqueue_script( 'codemirror-css', WEBROOT . '/admin/assets/js/lib/codemirror/mode/css.js', array('codemirror'), '3.12', true);
		  	wp_enqueue_script( 'custom-css-js', WEBROOT . '/admin/assets/js/custom-css-js.js', array('codemirror'), VERSION, true);
		  }
		}
		
		add_action('admin_enqueue_scripts', 'add_custom_css_js_assets');
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
	
	public function themeSupport( $support = array() ) {
		if ( is_array( $support ) ) {
			$register_theme_support = function () use ( $support ) {
				foreach ( $support as $feature => $specific ) {
					if ( is_array( $specific ) ) {
						add_theme_support( $feature, $specific );
					} else {
						add_theme_support( $specific );
					}
				}
			};
			
			add_action( 'after_setup_theme', $register_theme_support );
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
					'version'   => false,
					'media'     => 'all',
					'fallback'  => false
				);
				
				foreach($assets as $asset) {					
					$asset = wp_parse_args( $asset, $defaults );
					
					if ( $asset['fallback'] && ! @fopen( $asset['src'], 'r' ) ) {
						$asset['src'] = $asset['fallback'];						
					}
					
					if ($asset['type'] == 'script') {
						if ($asset['do'] == 'enqueue') {
							wp_enqueue_script( $asset['handle'], $asset['src'], $asset['deps'], $asset['version'], $asset['in_footer'] );
						} else {
							wp_register_script( $asset['handle'], $asset['src'], $asset['deps'], $asset['version'], $asset['in_footer'] );
						}
					} else {
						if ($asset['do'] == 'enqueue') {
							wp_enqueue_style( $asset['handle'], $asset['src'], $asset['deps'], $asset['version'], $asset['media'] );
						} else {
							wp_register_style( $asset['handle'], $asset['src'], $asset['deps'], $asset['version'], $asset['media'] );
						}
					}
				}
				
				wp_deregister_script( 'jquery' ); // deregisters the default WordPress jQuery
				
				$jquery_url = 'http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js';
				
				if ( @fopen($jquery_url, 'r') ) {
					wp_enqueue_script( 'jquery', $jquery_url, false, '1.8.2', true ); // register the external file
				} else {
					wp_enqueue_script( 'jquery', get_bloginfo('template_url') . '/assets/js/libs/jquery.js', false, '1.8.2', true );
				}
			}
		};
		
		add_action('wp_enqueue_scripts', $addAssets, 999);
	}
	
	/**
	 * Enqueue assets
	 *
	 * Helps you enqueue page specific styles or scripts.
	 *
	 *     $assets = array(
	 *       'stylehandle'  => 'style',
	 *       'scripthandle' => 'script'
	 *     );
	 *
	 *     Inizio::enqueueAssets( $assets );
	 *
	 * @param  array
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 * @link   http://codex.wordpress.org/Function_Reference/register_nav_menus
	 */
	 
	public function enqueueAssets( $assets ) {
		$addAsset = function () use ($assets) {
			foreach ($assets as $asset => $type) {
				if ($type == 'style') {
					wp_enqueue_style( $asset );
				} else {
					wp_enqueue_style( $script );
				}
			}
		};
		
		add_action('wp_enqueue_scripts', $addAsset, 999);
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
	
	public function addMenus( $menus ) {
		$register_menus = function () use ( $menus ) {
			register_nav_menus( $menus );
		};
	
		add_action( 'after_setup_theme', $register_menus );
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
	
	public function addImageSizes( $sizes ) {		
		if ( is_array( $sizes ) ) {
			$register_image_sizes = function () use ( $sizes ) {
				$defaults = array(
					'width'  => 0,
					'height' => 0,
					'crop'   => false
				);
				
				add_theme_support( 'post_thumbnails' );
				$labels = array();
				
				foreach ( $sizes as $name => $size ) {
					$size = wp_parse_args( $size, $defaults );

					add_image_size( $name, $size['width'], $size['height'], $size['crop'] );
					if ( isset( $size['label'] ) )
						$labels[$name] = $size['label'];
				}
				
				$image_sizes = function ( $sizes ) use ( $labels ) {
					$newsizes = array_merge( $sizes, $labels );
					return $newsizes;
				};
			
				add_filter( 'image_size_names_choose', $image_sizes );
			};
			
			add_action( 'after_setup_theme', $register_image_sizes );
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
	
	public function addSidebars( $addSidebars = array() ) {
		if ( is_array($addSidebars) ) {
			$register_new_sidebars = function () use ( $addSidebars ) {
				foreach( $addSidebars as $sidebar ) {
					register_sidebar( $sidebar );
				}
			};
			
			add_action( 'widgets_init', $register_new_sidebars );
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
	
	public function customizeLogin( $stylesheet ) {
		$login_css = function () use ( $stylesheet ) {
			wp_enqueue_style( 'login-css', $stylesheet );
		};
		
		add_action( 'login_head', $login_css );
		
		function bones_login_title() { 
			return get_option( 'blogname' ); 
		}
		
		add_filter('login_headertitle', 'bones_login_title');
	}
		
	/* http://wp-snippets.com/pagination-without-plugin/ */
	public function pagination( $prev = '«', $next = '»' ) {
		global $wp_query, $wp_rewrite;
		$current = ( $wp_query->query_vars['paged'] > 1 ) ? $wp_query->query_vars['paged'] : 1;
		
		$pagination = array(
			'base'      => @add_query_arg( 'paged', '%#%' ),
			'format'    => '',
			'total'     => $wp_query->max_num_pages,
			'current'   => $current,
			'prev_text' => $prev,
			'next_text' => $next,
			'type'      => 'plain'
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
	public function removeFromMenu( $remove ) {	
		$my_remove_menu_pages = function () use ( $remove ) {
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
			
			if ( is_array( $remove ) ) {
				foreach ( $remove as $item ) {
					if ( isset( $menu[ $item ] ) ) 
						remove_menu_page( $menu[ $item ] );
					
					if ( $item == 'comments' ) 
						Inizio::hideFromDashboard('recent_comments');
				}
			} else {
				remove_menu_page( $menu[ $remove ] );
				if ( $remove == 'comments' ) 
					Inizio::hideFromDashboard( 'recent_comments' );
			}
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
		return isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) AND strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest';
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
	 
	public function getPostsWithThumbnail( $post_type = 'post', $limit = 5 ) {
		$posts = get_posts(array(
			'post_type'   => $post_type, 
			'numberposts' => $limit, 
			'meta_key'    => '_thumbnail_id'
		));
		
		return $posts;
	}
	
	/* http://wp-snippets.com/limit-excerpt-words/ */
	public function limit_words( $string, $word_limit ) {
		$words = explode( ' ', $string );
		return implode( ' ', array_slice( $words, 0, $word_limit ) );
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
	 
	public function hideFromDashboard( $widgets ) {
		$dashboard_widgets = function () use ( $widgets ) {
			$real_widget = array(
				'right_now'       => array( 'normal', 'dashboard_right_now' ),
				'recent_comments' => array( 'normal', 'dashboard_recent_comments' ),
				'incoming_links'  => array( 'normal', 'dashboard_incoming_links' ),
				'recent_drafts'   => array( 'side',   'dashboard_recent_drafts' ),
				'blog'            => array( 'side',   'dashboard_primary' ),
				'news'            => array( 'side',   'dashboard_secondary' ),
				'quick_press'     => array( 'side',   'dashboard_quick_press' ),
				'plugins'         => array( 'normal', 'dashboard_plugins' )
			);
			
			if ( is_array( $widgets ) ) {
				foreach ( $widgets as $widget ) {
					remove_meta_box( $real_widget[$widget][1], 'dashboard', $real_widget[$widget][0] );
				}
			} else {
				remove_meta_box( $real_widget[$widgets][1], 'dashboard', $real_widget[$widgets][0] );
			}
		};
		
		add_action( 'wp_dashboard_setup', $dashboard_widgets );
	}
	
	public function featured_image( $id = false, $class = false, $size = 'medium' ) {
		global $post;
		$id = ( $id ) ? $id : $post->ID;
		
		if ( has_post_thumbnail( $id ) ) {
			$html  = ( $class ) ? '<figure class="' . $class . '">' : '<figure>';
			$html .= get_the_post_thumbnail( $id, $size );
						
			if ( $caption = get_post( get_post_thumbnail_id() )->post_excerpt )
				$html .= '<figcaption>' . $caption . '</figcaption>';
				
			$html .= '</figure>';
			
			return $html;
		} else {
			return false;
		}
	}
	
	/**
	 * Nice time
	 *
	 * Formats the post date to something like "4 hours ago".
	 *
	 * @param string post date (get_post_date())
	 * @author getButterfly <http://getbutterfly.com/nice-date-feature-facebook-style/>
	 * @author Tobias Bleckert <tbleckert@gmail.com>
	 */
	
	public function niceTime( $date ) {
		if ( empty( $date ) ) {
			return __('No date provided', LANG_DOMAIN);
		}
		
		$periods = array( 
			__( 'second', LANG_DOMAIN ), 
			__( 'minute', LANG_DOMAIN ), 
			__( 'hour', LANG_DOMAIN ),
			__( 'day', LANG_DOMAIN ), 
			__( 'week', LANG_DOMAIN ),
			__( 'month', LANG_DOMAIN ),
			__( 'year', LANG_DOMAIN ),
			__( 'decade', LANG_DOMAIN )
		);
		
		$lengths = array( 
			'60',
			'60',
			'24',
			'7',
			'4.35',
			'12',
			'10'
		);
		
		$now  = strtotime( date_i18n( 'Y-m-d H:i:s' ) );
		$date = strtotime( $date );
		
		if ( empty( $date ) )
			return __( 'Invalid date', LANG_DOMAIN );
			
		if ( $now > $date ) {
			$difference = $now - $date;
			$tense      = __( 'ago', LANG_DOMAIN );
		} else {
			$difference = $date - $now;
			$tense      = __( 'from now', LANG_DOMAIN );
		}
		
		for ( $j = 0; $difference >= $lengths[$j] && $j < count( $lengths ) - 1; $j++ ) {
			$difference /= $lengths[$j];
		}
		
		$difference = round( $difference );
		
		if ( $difference != 1 ) {
			$periods[$j] .= __( 's', LANG_DOMAIN );
		}
		
		return "{$difference} $periods[$j] {$tense}";
	}
}