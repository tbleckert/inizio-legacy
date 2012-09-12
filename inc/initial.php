<?php

class Initial {

	public function head_cleanup() {
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
	
	public function rss_version() { return ''; }
	
	// remove injected CSS for recent comments widget
	public function remove_wp_widget_recent_comments_style() {
	   if ( has_filter('wp_head', 'wp_widget_recent_comments_style') ) {
	      remove_filter('wp_head', 'wp_widget_recent_comments_style' );
	   }
	}
		
	// remove injected CSS from recent comments widget
	public function remove_recent_comments_style() {
	  global $wp_widget_factory;
	  if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
	    remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
	  }
	}
	
	// remove injected CSS from gallery
	public function gallery_style($css) {
	  return preg_replace("!<style type='text/css'>(.*?)</style>!s", '', $css);
	}
		
	// Adding WP 3+ Functions & Theme Support
	public function theme_support() {
		
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
	 
	// this is the fallback for header menu
	public function main_nav_fallback() { 
		wp_page_menu( 'show_home=Home' ); 
	}
	
	// this is the fallback for footer menu
	public function footer_links_fallback() { 
		/* you can put a default here if you like */ 
	}
	
	/*********************
	RELATED POSTS FUNCTION
	*********************/	
		
	// Related Posts Function (call using related_posts(); )
	public function related_posts() {
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
	public function page_navi($before = '', $after = '') {
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
		echo $before.'<nav class="page-navigation"><ol class="page_navi clearfix">'."";
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
	public function filter_ptags_on_images($content){
	   return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	}
	
	// This removes the annoying […] to a Read More link
	public function excerpt_more($more) {
		global $post;
		// edit here if you like
		return '...  <a href="'. get_permalink($post->ID) . '" title="Read '.get_the_title($post->ID).'">Read more &raquo;</a>';
	}
	
	// Sidebars & Widgetizes Areas
	public function register_sidebars() {
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

}