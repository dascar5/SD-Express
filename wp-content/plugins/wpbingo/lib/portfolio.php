<?php
if(!function_exists('bwp_create_type_portfolio')  ){
    function bwp_create_type_portfolio(){
	$labels_portfolio = array(
		'name' => __('Portfolio', "wpbingo" ),
		'singular_name' => __('Portfolio Item', "wpbingo" ),
		'add_new' => __('Add New', "wpbingo" ),
		'add_new_item' => __('Add New Portfolio Item', "wpbingo" ),
		'edit_item' => __('Edit Portfolio Item', "wpbingo" ),
		'new_item' => __('New Portfolio Item', "wpbingo" ),
		'view_item' => __('View Portfolio Item', "wpbingo" ),
		'search_items' => __('Search Portfolio', "wpbingo" ),
		'not_found' =>  __('Nothing found', "wpbingo" ),
		'not_found_in_trash' => __('Nothing found in Trash', "wpbingo" ),
		'parent_item_colon' => ''
	);

	$args_portfolio = array(
		'labels' => $labels_portfolio,
		'public' => true,
		'has_archive' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => 'dashicons-images-alt',
		'rewrite' =>  true,
		'capability_type' => 'post',
		'hierarchical' => true,			
		'menu_position' => 4,
		'show_in_menu' => false,
		'supports' => array( 'title','thumbnail', 'editor', 'author', 'revisions', 'comments' )
	  );
	register_post_type( 'portfolio', $args_portfolio );
	
	register_taxonomy(
		'category_portfolio', 
		'portfolio', 
		array(
			"hierarchical" => true, 
			"label" => "Categories Portfolio", 
			"singular_label" =>  __('Category Portfolio', "wpbingo" ), 
			'rewrite' => true
		));
  }
  add_action( 'init','bwp_create_type_portfolio',0 );
}

