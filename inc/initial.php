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

}