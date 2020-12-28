<?php
if(!function_exists('bwp_create_type_testimonial')  ){
    function bwp_create_type_testimonial(){
	$labels_testimonial = array(
		'name' => __( 'Testimonial', "wpbingo" ),
		'singular_name' => __( 'Testimonial', "wpbingo" ),
		'add_new' => __( 'Add New Testimonial', "wpbingo" ),
		'add_new_item' => __( 'Add New Testimonial', "wpbingo" ),
		'edit_item' => __( 'Edit Testimonial', "wpbingo" ),
		'new_item' => __( 'New Testimonial', "wpbingo" ),
		'view_item' => __( 'View Testimonial', "wpbingo" ),
		'search_items' => __( 'Search Testimonials', "wpbingo" ),
		'not_found' => __( 'No Testimonials found', "wpbingo" ),
		'not_found_in_trash' => __( 'No Testimonials found in Trash', "wpbingo" ),
		'parent_item_colon' => __( 'Parent Testimonial:', "wpbingo" ),
		'menu_name' => __( 'Testimonials', "wpbingo" ),
	);

	$args_testimonial = array(
	  'labels' => $labels_testimonial,
	  'hierarchical' => true,
	  'description' => __( 'List Testimonial', "wpbingo" ),
	  'supports' => array( 'title', 'editor', 'thumbnail','excerpt'),
	  'public' => true,
	  'show_ui' => true,
	  'show_in_menu' => true,
	  'menu_position' => 5,
	  'show_in_menu' => false,
	  'show_in_nav_menus' => true,
	  'publicly_queryable' => true,
	  'exclude_from_search' => false,
	  'has_archive' => true,
	  'query_var' => true,
	  'can_export' => true,
	  'rewrite' => true,
	  'capability_type' => 'post'
	);
	register_post_type( 'testimonial', $args_testimonial ); 
  }
  add_action( 'init','bwp_create_type_testimonial',0 );
}

