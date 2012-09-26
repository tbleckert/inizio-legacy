<?php

/**
 * Initial
 *
 * This is a collection of useful functions that will autorun in Inizio.
 *
 * Wordpress are in some places a mess, this setup of functions
 * will simply remove or fix stuff in wordpress.
 *
 * @author  Eddie Machado <http://themble.com>
 * @version 1.0
 */
class Initial {

	public function head_cleanup() {
		// category feeds
		remove_action( 'wp_head', 'feed_links_extra', 3 );                    
		// post and comment feeds
		remove_action( 'wp_head', 'feed_links', 2 );                          
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
	
	// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
	public function filter_ptags_on_images($content){
		 return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
	}

}