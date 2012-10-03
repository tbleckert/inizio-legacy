<?php

/**
 * Plugin Name: Extended Text Widget
 * Description: A text widget with the ability to add an image
 * Version: 0.1.5
 * Author: Tobias Bleckert @ Vinnovera
 * Author URI: http://vinnovera.se
 * License: WTFPL
 * License URI: http://sam.zoy.org/wtfpl/
 */
 
include_once( DOCROOT . 'inc/wph-widget-class.php' );

class ExtendedText extends WPH_Widget {

	public function __construct() {
		$args = array(
			'label'       => __( 'Text (Extended)', LANG_DOMAIN ),
			'description' => __( 'A text widget with the ability to add an image', LANG_DOMAIN )
		);
		
		$args['fields'] = array(
			array(
				'name'     => __( 'Title', LANG_DOMAIN ),
				'desc'     => __( 'Enter the widget title', LANG_DOMAIN ),
				'id'       => 'title',
				'type'     => 'text',
				'class'    => 'widefat',
				'validate' => 'alpha_dash',
				'filter'   => 'strip_tags|esc_attr'
			),
			array(
				'id'       => 'imgsrc',
				'type'     => 'hidden',
				'validate' => 'numeric'
			),
			array(
				'id'       => 'body',
				'type'     => 'textarea',
				'class'    => 'widefat',
				'rows'     => '16',
				'cols'     => '20' 
			)
		);
		
		$this->create_widget( $args );
	}
	
	public function widget( $args, $instance ) {
		$out  = $args['before_title'];
		$out .= $instance['title'];
		$out .= $args['after_title'];
		$out .= apply_filters('the_content', $instance['body']);
		
		echo $out;
	}

}