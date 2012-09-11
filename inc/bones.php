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

class Bones {
	
	public function ahoy() {
		add_action('after_setup_theme', self::init(), 15);
	}
	
	public function init() {
		// launching operation cleanup
    add_action('init', array('Bones', 'bones_head_cleanup'));
    // remove WP version from RSS
    add_filter('the_generator', array('Bones', 'bones_rss_version'));
    // remove pesky injected css for recent comments widget
    add_filter( 'wp_head', array('Bones', 'bones_remove_wp_widget_recent_comments_style'), 1);
    // clean up comment styles in the head
    add_action('wp_head', array('Bones', 'bones_remove_recent_comments_style'), 1);
    // clean up gallery output in wp
    add_filter('gallery_style', array('Bones', 'bones_gallery_style'));

    // enqueue base scripts and styles
    add_action('wp_enqueue_scripts', array('Bones', 'bones_scripts_and_styles'), 999);
    
    // launching this stuff after theme setup
    add_action('after_setup_theme', array('Bones', 'bones_theme_support'));	
    // adding sidebars to Wordpress (these are created in functions.php)
    add_action( 'widgets_init', array('Bones', 'bones_register_sidebars'));
    // adding the bones search form (created in functions.php)
    add_filter( 'get_search_form', array('Bones', 'bones_wpsearch') );
    
    // cleaning up random code around images
    add_filter('the_content', array('Bones', 'bones_filter_ptags_on_images'));
    // cleaning up excerpt
    add_filter('excerpt_more', array('Bones', 'bones_excerpt_more'));
	}
	
	public function bones_head_cleanup() {
		// category feeds
		// remove_action( 'wp_head', 'feed_links_extra', 3 );                    
		// post and comment feeds
		// remove_action( 'wp_head', 'feed_links', 2 );                          
		// EditURI link
		remove_action( 'wp_head', 'rsd_link' );                               
		// windows live writer
		remove_action( 'wp_head', 'wlwmanifest_link' );                       
		// index link
		remove_action( 'wp_head', 'index_rel_link' );                         
		// previous link
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );            
		// start link
		remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );             
		// links for adjacent posts
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 ); 
		// WP version
		remove_action( 'wp_head', 'wp_generator' );                           
	}
	
	public function bones_rss_version() { return ''; }
	
	// remove injected CSS for recent comments widget
	public function bones_remove_wp_widget_recent_comments_style() {
	   if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) {
	      remove_filter('wp_head', 'wp_widget_recent_comments_style' );
	   }
	}
		
	// remove injected CSS from recent comments widget
	public function bones_remove_recent_comments_style() {
	  global $wp_widget_factory;
	  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
	    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	  }
	}
	
	// remove injected CSS from gallery
	public function bones_gallery_style($css) {
	  return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
	}
	
	// loading modernizr and jquery, and reply script 
	public function bones_scripts_and_styles() {
	  if (!is_admin()) {
	  
	    // modernizr (without media query polyfill)
	    wp_enqueue_script( 'bones-modernizr', get_stylesheet_directory_uri() . '/assets/js/libs/modernizr.custom.min.js', array(), '2.5.3');
	 
	    // register main stylesheet
	    wp_enqueue_style( 'bones-stylesheet', get_stylesheet_directory_uri() . '/assets/css/style.css', array(), VERSION, 'all' );
	    
	    // comment reply script for threaded comments
	    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
	      wp_enqueue_script( 'comment-reply' );
	    }
	    
	    // adding scripts file in the footer
	    wp_enqueue_script( 'bones-js', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array( 'jquery' ), VERSION, true );
	  
	  	// load jquery from cdn and in the footer
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
	}
		
	// Adding WP 3+ Functions & Theme Support
	public function bones_theme_support() {
		
		// wp thumbnails (sizes handled in functions.php)
		add_theme_support('post-thumbnails');   
		
		// default thumb size   
		set_post_thumbnail_size(125, 125, true);   
		
		// wp custom background (thx to @bransonwerner for update)
		add_theme_support( 'custom-background',
		    array( 
		    'default-image' => '',  // background image default
		    'default-color' => '', // background color default (dont add the #)
		    'wp-head-callback' => '_custom_background_cb',
		    'admin-head-callback' => '',
		    'admin-preview-callback' => ''
		    )
		);      
		
		// rss thingy           
		add_theme_support('automatic-feed-links'); 
		
		// to add header image support go here: http://themble.com/support/adding-header-background-image-support/
		
		// adding post format support
		add_theme_support( 'post-formats',  
			array( 
				'aside',             // title less blurb
				'gallery',           // gallery of images
				'link',              // quick link to other site
				'image',             // an image
				'quote',             // a quick quote
				'status',            // a Facebook like status update
				'video',             // video 
				'audio',             // audio
				'chat'               // chat transcript 
			)
		);	
		
		// wp menus
		add_theme_support( 'menus' );  
		
		// registering wp3+ menus          
		register_nav_menus(                      
			array( 
				'main-nav' => __( 'The Main Menu', 'bonestheme' ),   // main nav in header
				'footer-links' => __( 'Footer Links', 'bonestheme' ) // secondary nav in footer
			)
		);
	} /* end bones theme support */
	
	
	/*********************
	MENUS & NAVIGATION
	*********************/	
	 
	// the main menu 
	public function bones_main_nav() {
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
	    	'fallback_cb' => 'bones_main_nav_fallback'      // fallback function
		));
	} /* end bones main nav */
	
	// the footer menu (should you choose to use one)
	public function bones_footer_links() { 
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
	    	'fallback_cb' => 'bones_footer_links_fallback'  // fallback function
		));
	} /* end bones footer link */
	 
	// this is the fallback for header menu
	public function bones_main_nav_fallback() { 
		wp_page_menu( 'show_home=Home' ); 
	}
	
	// this is the fallback for footer menu
	public function bones_footer_links_fallback() { 
		/* you can put a default here if you like */ 
	}
	
	/*********************
	RELATED POSTS FUNCTION
	*********************/	
		
	// Related Posts Function (call using bones_related_posts(); )
	public function bones_related_posts() {
		echo '<ul id="bones-related-posts">';
		global $post;
		$tags = wp_get_post_tags($post->ID);
		if($tags) {
			foreach($tags as $tag) { $tag_arr .= $tag->slug . ','; }
	        $args = array(
	        	'tag' => $tag_arr,
	        	'numberposts' => 5, /* you can change this to show more */
	        	'post__not_in' => array($post->ID)
	     	);
	        $related_posts = get_posts($args);
	        if($related_posts) {
	        	foreach ($related_posts as $post) : setup_postdata($post); ?>
		           	<li class="related_post"><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
		        <?php endforeach; } 
		    else { ?>
	            <?php echo '<li class="no_related_post">No Related Posts Yet!</li>'; ?>
			<?php }
		}
		wp_reset_query();
		echo '</ul>';
	} /* end bones related posts function */
	
	/*********************
	PAGE NAVI
	*********************/	
	
	// Numeric Page Navi (built into the theme by default)
	public function bones_page_navi($before = '', $after = '') {
		global $wpdb, $wp_query;
		$request = $wp_query->request;
		$posts_per_page = intval(get_query_var('posts_per_page'));
		$paged = intval(get_query_var('paged'));
		$numposts = $wp_query->found_posts;
		$max_page = $wp_query->max_num_pages;
		if ( $numposts <= $posts_per_page ) { return; }
		if(empty($paged) || $paged == 0) {
			$paged = 1;
		}
		$pages_to_show = 7;
		$pages_to_show_minus_1 = $pages_to_show-1;
		$half_page_start = floor($pages_to_show_minus_1/2);
		$half_page_end = ceil($pages_to_show_minus_1/2);
		$start_page = $paged - $half_page_start;
		if($start_page <= 0) {
			$start_page = 1;
		}
		$end_page = $paged + $half_page_end;
		if(($end_page - $start_page) != $pages_to_show_minus_1) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}
		if($end_page > $max_page) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page = $max_page;
		}
		if($start_page <= 0) {
			$start_page = 1;
		}
		echo $before.'<nav class="page-navigation"><ol class="bones_page_navi clearfix">'."";
		if ($start_page >= 2 && $pages_to_show < $max_page) {
			$first_page_text = "First";
			echo '<li class="bpn-first-page-link"><a href="'.get_pagenum_link().'" title="'.$first_page_text.'">'.$first_page_text.'</a></li>';
		}
		echo '<li class="bpn-prev-link">';
		previous_posts_link('<<');
		echo '</li>';
		for($i = $start_page; $i  <= $end_page; $i++) {
			if($i == $paged) {
				echo '<li class="bpn-current">'.$i.'</li>';
			} else {
				echo '<li><a href="'.get_pagenum_link($i).'">'.$i.'</a></li>';
			}
		}
		echo '<li class="bpn-next-link">';
		next_posts_link('>>');
		echo '</li>';
		if ($end_page < $max_page) {
			$last_page_text = "Last";
			echo '<li class="bpn-last-page-link"><a href="'.get_pagenum_link($max_page).'" title="'.$last_page_text.'">'.$last_page_text.'</a></li>';
		}
		echo '</ol></nav>'.$after."";
	} /* end page navi */
	
	/*********************
	RANDOM CLEANUP ITEMS
	*********************/	
	
	// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
	public function bones_filter_ptags_on_images($content){
	   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	}
	
	// This removes the annoying […] to a Read More link
	public function bones_excerpt_more($more) {
		global $post;
		// edit here if you like
		return '...  <a href="'. get_permalink($post->ID) . '" title="Read '.get_the_title($post->ID).'">Read more &raquo;</a>';
	}
	
	// Sidebars & Widgetizes Areas
	public function bones_register_sidebars() {
	    register_sidebar(array(
	    	'id' => 'sidebar1',
	    	'name' => 'Sidebar 1',
	    	'description' => 'The first (primary) sidebar.',
	    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	    	'after_widget' => '</div>',
	    	'before_title' => '<h4 class="widgettitle">',
	    	'after_title' => '</h4>',
	    ));
	    
	    /* 
	    to add more sidebars or widgetized areas, just copy
	    and edit the above sidebar code. In order to call 
	    your new sidebar just use the following code:
	    
	    Just change the name to whatever your new
	    sidebar's id is, for example:
	    
	    register_sidebar(array(
	    	'id' => 'sidebar2',
	    	'name' => 'Sidebar 2',
	    	'description' => 'The second (secondary) sidebar.',
	    	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	    	'after_widget' => '</div>',
	    	'before_title' => '<h4 class="widgettitle">',
	    	'after_title' => '</h4>',
	    ));
	    
	    To call the sidebar in your template, you can just copy
	    the sidebar.php file and rename it to your sidebar's name.
	    So using the above example, it would be:
	    sidebar-sidebar2.php
	    
	    */
	} // don't remove this bracket!
			
	// Comment Layout
	public function bones_comments($comment, $args, $depth) {
	   $GLOBALS['comment'] = $comment; ?>
		<li <?php comment_class(); ?>>
			<article id="comment-<?php comment_ID(); ?>" class="clearfix">
				<header class="comment-author vcard">
				    <?php /*
				        this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
				        echo get_avatar($comment,$size='32',$default='<path_to_url>' );
				    */ ?>
				    <!-- custom gravatar call -->
				    <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5($bgauthemail); ?>&s=32" class="load-gravatar avatar avatar-48 photo" height="32" width="32" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
				    <!-- end custom gravatar call -->
					<?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
					<time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time('F jS, Y'); ?> </a></time>
					<?php edit_comment_link(__('(Edit)', 'bonestheme'),'  ','') ?>
				</header>
				<?php if ($comment->comment_approved == '0') : ?>
	       			<div class="alert info">
	          			<p><?php _e('Your comment is awaiting moderation.', 'bonestheme') ?></p>
	          		</div>
				<?php endif; ?>
				<section class="comment_content clearfix">
					<?php comment_text() ?>
				</section>
				<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
			</article>
	    <!-- </li> is added by WordPress automatically -->
	<?php
	} // don't remove this bracket!
	
	/************* SEARCH FORM LAYOUT *****************/
	
	// Search Form
	public function bones_wpsearch($form) {
    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <label class="screen-reader-text" for="s">' . __('Search for:', 'bonestheme') . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.esc_attr__('Search the Site...','bonestheme').'" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
    </form>';
    return $form;
	}
	
}