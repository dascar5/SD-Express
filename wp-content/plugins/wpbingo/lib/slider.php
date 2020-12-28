<?php

if(!function_exists('bwp_create_type_slider')  ){
    function bwp_create_type_slider(){
    $labels_slider = array(
        'name' => __( 'Slider', "wpbingo" ),
        'singular_name' => __( 'Slider', "wpbingo" ),
        'add_new' => __( 'Add New Slider', "wpbingo" ),
        'add_new_item' => __( 'Add New Slider', "wpbingo" ),
        'edit_item' => __( 'Edit Slider', "wpbingo" ),
        'new_item' => __( 'New Slider', "wpbingo" ),
        'view_item' => __( 'View Slider', "wpbingo" ),
        'search_items' => __( 'Search Sliders', "wpbingo" ),
        'not_found' => __( 'No Sliders found', "wpbingo" ),
        'not_found_in_trash' => __( 'No Sliders found in Trash', "wpbingo" ),
        'parent_item_colon' => __( 'Parent Slider:', "wpbingo" ),
        'menu_name' => __( 'Sliders', "wpbingo" ),
      );

    $args_slider = array(
          'labels' => $labels_slider,
          'hierarchical' => true,
          'description' => __( 'List slider', "wpbingo" ),
          'supports' => array( 'title','thumbnail','excerpt'),
          'public' => true,
          'show_ui' => true,
          'show_in_menu' => true,
          'menu_position' => 7,
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
    register_post_type( 'bwp_slider', $args_slider ); 
  }
  add_action( 'init','bwp_create_type_slider',0 );
}


