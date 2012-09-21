<?php

add_action( 'admin_menu', 'theme_info_add_page' );

/**
 * Load up the menu page
 */
function theme_info_add_page() {
	add_theme_page( __( 'Theme Info', 'inizio' ), __( 'Theme Info', 'inizio' ), 'edit_theme_options', 'theme_info', 'theme_info_do_page' );
}

function add_theme_info_assets() {
	wp_enqueue_style('inizio-theme_info-style', get_stylesheet_directory_uri() . '/admin/assets/css/theme_info.css', false, VERSION);
	wp_enqueue_script('inizio-theme_info-script', get_stylesheet_directory_uri() . '/admin/assets/js/theme_info.js', array('jquery'), VERSION, true);
}

add_action('admin_enqueue_scripts', 'add_theme_info_assets');

function theme_info_do_page () { 

?>

<div id="inizio-theme-info" class="wrap">
</div>

<?php } // end theme_info_do_page ?>