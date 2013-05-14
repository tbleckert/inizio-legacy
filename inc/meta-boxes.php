<?php

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
  add_meta_box( 'custom-js-meta-box', __( 'Custom CSS', LANG_DOMAIN ), 'custom_js_box_inside', 'post', 'normal', 'high' );
  add_meta_box( 'custom-js-meta-box', __( 'Custom CSS', LANG_DOMAIN ), 'custom_js_box_inside', 'page', 'normal', 'high' );
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
  }
}

add_action('admin_enqueue_scripts', 'add_custom_css_js_assets');