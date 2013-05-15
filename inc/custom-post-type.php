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

Developed by: Tobias Bleckert
URL: http://tb-one.se

Originaly developed by: Eddie Machado
URL: http://themble.com/bones/
*/

/* Include post type helper. This is not needed but it will make it easier for you. */
require_once('custom-post-type-helper.php');

function register_custom_post_example() {
	new Custom_Post_Type_Helper('article',
		array(
			'labels'               => array(
				'name'               => __('Articles', LANG_DOMAIN), /* This is the Title of the Group */
				'singular_name'      => __('Article', LANG_DOMAIN), /* This is the individual type */
				'all_items'          => __('All articles', LANG_DOMAIN), /* the all items menu item */
				'add_new'            => __('Add New', LANG_DOMAIN), /* The add new menu item */
				'add_new_item'       => __('Add New Article', LANG_DOMAIN), /* Add New Display Title */
				'edit'               => __( 'Edit', LANG_DOMAIN ), /* Edit Dialog */
				'edit_item'          => __('Edit Articles', LANG_DOMAIN), /* Edit Display Title */
				'new_item'           => __('New Article', LANG_DOMAIN), /* New Display Title */
				'view_item'          => __('View Article', LANG_DOMAIN), /* View Display Title */
				'search_items'       => __('Search Article', LANG_DOMAIN), /* Search Custom Type Title */ 
				'not_found'          => __('Nothing found in the Database.', LANG_DOMAIN), /* This displays if there are no entries yet */ 
				'not_found_in_trash' => __('Nothing found in Trash', LANG_DOMAIN), /* This displays if there is nothing in the trash */
				'parent_item_colon'  => ''
			),
			'description'          => __( 'This is the example custom post type', LANG_DOMAIN ), /* Custom Type Description */
			'public'               => true,
			'publicly_queryable'   => true,
			'exclude_from_search'  => false,
			'show_ui'              => true,
			'query_var'            => true,
			'menu_position'        => 8, /* this is what order you want it to appear in on the left hand side menu */ 
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
			'name' => __( 'Custom Categories', LANG_DOMAIN ), /* name of the custom taxonomy */
			'singular_name' => __( 'Custom Category', LANG_DOMAIN ), /* single taxonomy name */
			'search_items' =>  __( 'Search Custom Categories', LANG_DOMAIN ), /* search title for taxomony */
			'all_items' => __( 'All Custom Categories', LANG_DOMAIN ), /* all title for taxonomies */
			'parent_item' => __( 'Parent Custom Category', LANG_DOMAIN ), /* parent title for taxonomy */
			'parent_item_colon' => __( 'Parent Custom Category:', LANG_DOMAIN ), /* parent taxonomy title */
			'edit_item' => __( 'Edit Custom Category', LANG_DOMAIN ), /* edit custom taxonomy title */
			'update_item' => __( 'Update Custom Category', LANG_DOMAIN ), /* update title for taxonomy */
			'add_new_item' => __( 'Add New Custom Category', LANG_DOMAIN ), /* add new title for taxonomy */
			'new_item_name' => __( 'New Custom Category Name', LANG_DOMAIN ) /* name title for taxonomy */
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
			'name' => __( 'Custom Tags', LANG_DOMAIN ),
			'singular_name' => __( 'Custom Tag', LANG_DOMAIN ),
			'search_items' =>  __( 'Search Custom Tags', LANG_DOMAIN ),
			'all_items' => __( 'All Custom Tags', LANG_DOMAIN ),
			'parent_item' => __( 'Parent Custom Tag', LANG_DOMAIN ),
			'parent_item_colon' => __( 'Parent Custom Tag:', LANG_DOMAIN ),
			'edit_item' => __( 'Edit Custom Tag', LANG_DOMAIN ),
			'update_item' => __( 'Update Custom Tag', LANG_DOMAIN ),
			'add_new_item' => __( 'Add New Custom Tag', LANG_DOMAIN ),
			'new_item_name' => __( 'New Custom Tag Name', LANG_DOMAIN )
		),
			'show_ui' => true,
			'query_var' => true,
		)
	); 
}

// adding the function to the Wordpress init
add_action( 'init', 'register_custom_post_example');

// Setting icon
add_action( 'admin_head', 'cpt_icons' );
function cpt_icons() { ?>
	<style type="text/css" media="screen">
			#menu-posts-article .wp-menu-image { background: url(<?php echo get_stylesheet_directory_uri() . '/admin/assets/img/icons/pencil.png' ?>) no-repeat 6px -17px !important; }
			#menu-posts-article:hover .wp-menu-image, #menu-posts-article.wp-has-current-submenu .wp-menu-image { background-position:6px 7px!important; }
	</style>
<?php } ?>