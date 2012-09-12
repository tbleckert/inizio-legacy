<?php
/* Bones Custom Post Type Example
This page walks you through creating 
a custom post type and taxonomies. You
can edit this one or copy the following code 
to create another one. 

I put this in a separate file so as to 
keep it organized. I find it easier to edit
and change things if they are concentrated
in their own file.

Developed by: Eddie Machado
URL: http://themble.com/bones/
*/

/* Include post type helper. This is not needed but it will make it easier for you. */
require_once('custom-post-type-helper.php');

function register_custom_post_example() {
	new Custom_Post_Type_Helper('article',
		array(
			'labels'               => array(
				'name'               => __('Articles', 'bonestheme'), /* This is the Title of the Group */
				'singular_name'      => __('Article', 'bonestheme'), /* This is the individual type */
				'all_items'          => __('All articles', 'bonestheme'), /* the all items menu item */
				'add_new'            => __('Add New', 'bonestheme'), /* The add new menu item */
				'add_new_item'       => __('Add New Article', 'bonestheme'), /* Add New Display Title */
				'edit'               => __( 'Edit', 'bonestheme' ), /* Edit Dialog */
				'edit_item'          => __('Edit Articles', 'bonestheme'), /* Edit Display Title */
				'new_item'           => __('New Article', 'bonestheme'), /* New Display Title */
				'view_item'          => __('View Article', 'bonestheme'), /* View Display Title */
				'search_items'       => __('Search Article', 'bonestheme'), /* Search Custom Type Title */ 
				'not_found'          =>  __('Nothing found in the Database.', 'bonestheme'), /* This displays if there are no entries yet */ 
				'not_found_in_trash' => __('Nothing found in Trash', 'bonestheme'), /* This displays if there is nothing in the trash */
				'parent_item_colon'  => ''
			),
			'description'          => __( 'This is the example custom post type', 'bonestheme' ), /* Custom Type Description */
			'public'               => true,
			'publicly_queryable'   => true,
			'exclude_from_search'  => false,
			'show_ui'              => true,
			'query_var'            => true,
			'menu_position'        => 8, /* this is what order you want it to appear in on the left hand side menu */ 
			'menu_icon'            => get_stylesheet_directory_uri() . '/assets/img/custom-post-icon.png', /* the icon for the custom post type menu */
			'rewrite'	             => false, /* you can specify its url slug */
			'has_archive'          => 'articles', /* you can rename the slug here */
			'capability_type'      => 'post',
			'hierarchical'         => true
		),
		array(
			'front'                => 'articles', //Archive slug, where all articles are listed
			'structure'            => '/%year%/%monthnum%/%day%/%article_title%' //Custom permalink structure
		),
		array(
			'category',
			'post_tag'
		)
	);
	
	register_taxonomy('custom_cat', 
		array('article'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
		array('hierarchical' => true,     /* if this is true, it acts like categories */             
			'labels' => array(
			'name' => __( 'Custom Categories', 'bonestheme' ), /* name of the custom taxonomy */
			'singular_name' => __( 'Custom Category', 'bonestheme' ), /* single taxonomy name */
			'search_items' =>  __( 'Search Custom Categories', 'bonestheme' ), /* search title for taxomony */
			'all_items' => __( 'All Custom Categories', 'bonestheme' ), /* all title for taxonomies */
			'parent_item' => __( 'Parent Custom Category', 'bonestheme' ), /* parent title for taxonomy */
			'parent_item_colon' => __( 'Parent Custom Category:', 'bonestheme' ), /* parent taxonomy title */
			'edit_item' => __( 'Edit Custom Category', 'bonestheme' ), /* edit custom taxonomy title */
			'update_item' => __( 'Update Custom Category', 'bonestheme' ), /* update title for taxonomy */
			'add_new_item' => __( 'Add New Custom Category', 'bonestheme' ), /* add new title for taxonomy */
			'new_item_name' => __( 'New Custom Category Name', 'bonestheme' ) /* name title for taxonomy */
		),
			'show_ui' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'custom-slug' ),
		)
	);   
	
	// now let's add custom tags (these act like categories)
	register_taxonomy('custom_tag', 
		array('article'),
		array('hierarchical' => false,               
			'labels' => array(
			'name' => __( 'Custom Tags', 'bonestheme' ),
			'singular_name' => __( 'Custom Tag', 'bonestheme' ),
			'search_items' =>  __( 'Search Custom Tags', 'bonestheme' ),
			'all_items' => __( 'All Custom Tags', 'bonestheme' ),
			'parent_item' => __( 'Parent Custom Tag', 'bonestheme' ),
			'parent_item_colon' => __( 'Parent Custom Tag:', 'bonestheme' ),
			'edit_item' => __( 'Edit Custom Tag', 'bonestheme' ),
			'update_item' => __( 'Update Custom Tag', 'bonestheme' ),
			'add_new_item' => __( 'Add New Custom Tag', 'bonestheme' ),
			'new_item_name' => __( 'New Custom Tag Name', 'bonestheme' )
		),
			'show_ui' => true,
			'query_var' => true,
		)
	); 
}

// adding the function to the Wordpress init
add_action( 'init', 'register_custom_post_example');

?>