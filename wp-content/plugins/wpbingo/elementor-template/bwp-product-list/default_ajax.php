<?php
	$source 		= (isset($_POST['source']) && $_POST['source'] ) ? $_POST['source'] : 'default';
	$category 		= (isset($_POST['category']) && $_POST['category'] ) ? $_POST['category'] : '';
	$orderby 		= (isset($_POST['orderby']) && $_POST['orderby'] ) ? $_POST['orderby'] : 'name';
	$order 			= (isset($_POST['order']) && $_POST['order'] ) ? $_POST['order'] : 'DESC';
	$numberposts 	= (isset($_POST['numberposts']) && $_POST['numberposts'] ) ? $_POST['numberposts'] : 4;
	$paged 			= (isset($_POST['paged']) && $_POST['paged'] ) ? $_POST['paged'] : 2;
	$total 			= (isset($_POST['total']) && $_POST['total'] ) ? $_POST['total'] : 0 ;
	$attributes 			= (isset($_POST['attributes']) && $_POST['attributes'] ) ? $_POST['attributes'] : "col-lg-4 col-sm-6 col-xs-12";
	
	if($source == 'default'){
		$default = array();
		if( $category){
			$default = array(
				'post_type' => 'product',
				'tax_query' => array(
				array(
					'taxonomy'  => 'product_cat',
					'field'     => 'slug',
					'terms'     => $category ) ),
				'orderby' => $orderby,
				'order' => $order,
				'post_status' => 'publish',
				'showposts' => $numberposts
			);
		}else{
			$default = array(
				'post_type' => 'product',		
				'orderby' => $orderby,
				'order' => $order,
				'post_status' => 'publish',
				'showposts' => $numberposts
			);
		}
		$widget_id = 'bwp_default_'.rand().time();
		$widget_class = 'bwp_list_default';
	}
	
	if($source == 'featured'){
		$product_visibility_term_ids = wc_get_product_visibility_term_ids();
		if( $category){
			$default = array(
				'post_type'				=> 'product',
				'post_status' 			=> 'publish',
				'tax_query'	=> array(
					array(
					'taxonomy'	=> 'product_cat',
					'field'		=> 'slug',
					'terms'		=> $category
					),
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'term_taxonomy_id',
						'terms'    => $product_visibility_term_ids['featured'],
					)
				),
				'ignore_sticky_posts'	=> 1,
				'posts_per_page' 		=> $numberposts,
				'orderby' 				=> $orderby,
				'order' 				=> $order
			);
		}else{
			$default = array(
				'post_type'				=> 'product',
				'post_status' 			=> 'publish',
				'ignore_sticky_posts'	=> 1,
				'posts_per_page' 		=> $numberposts,
				'orderby' 				=> $orderby,
				'order' 				=> $order,
				'tax_query'	=> array(
					array(
						'taxonomy' => 'product_visibility',
						'field'    => 'term_taxonomy_id',
						'terms'    => $product_visibility_term_ids['featured'],
					)
				)
			);
		}
		$widget_id = 'bwp_featured_'.rand().time();
		$widget_class = 'bwp_list_featured';
	}
	
	if($source == 'toprating'){
		if( $category){
		$default = array(
			'post_type'		=> 'product',
			'tax_query' => array(
				array(
					'taxonomy'	=> 'product_cat',
					'field'		=> 'slug',
					'terms'		=> $category,
					'operator' 	=> 'IN'
				)
			),
			'post_status' 	=> 'publish',
			'no_found_rows' => 1,					
			'showposts' 	=> $numberposts						
		);
		}else{
			$default = array(
				'post_type'		=> 'product',		
				'post_status' 	=> 'publish',
				'no_found_rows' => 1,					
				'showposts' 	=> $numberposts						
			);
		}
		$default['meta_query'] = WC()->query->get_meta_query();
		add_filter( 'posts_clauses', 'order_by_rating_post_clauses' );
		$widget_id = 'bwp_toprated_'.rand().time();
		$widget_class = 'bwp_list_toprated';
	
	}

	if($source == 'bestsales'){
		if( $category){
			$default = array(
				'post_type' 			=> 'product',
				'tax_query' => array(
					array(
						'taxonomy'	=> 'product_cat',
						'field'	=> 'slug',
						'terms'	=> $category,
						'operator' => 'IN'
					)
				),
				'post_status' 			=> 'publish',
				'ignore_sticky_posts'   => 1,
				'paged'	=> 1,
				'showposts'				=> $numberposts,
				'meta_key' 		 		=> 'total_sales',
				'orderby' 		 		=> 'meta_value_num'
			);
		}else{
			$default = array(
				'post_type' 			=> 'product',		
				'post_status' 			=> 'publish',
				'ignore_sticky_posts'   => 1,
				'showposts'				=> $numberposts,
				'meta_key' 		 		=> 'total_sales',
				'orderby' 		 		=> 'meta_value_num'
			);
		}
		$widget_id = 'bwp_bestsales_'.rand().time();
		$widget_class = 'bwp_list_bestsales';
		
	}
	
	if($source == 'childcat'){
		$default = array();
		$default = array(
			'post_type' => 'product',
			'tax_query' => array(
			array(
				'taxonomy'  => 'product_cat',
				'field'     => 'slug',
				'terms'     => $category ) ),
			'orderby' => $orderby,
			'order' => $order,
			'post_status' => 'publish',
			'showposts' => $numberposts
		);
		$term = get_term_by( 'slug', $category, 'product_cat' );
		$widget_id = 'bwp_childcat_'.rand().time();					
	}
	
	$default['paged'] = $paged;
	
	$wp_query = new WP_Query( $default );	
	$result = new stdClass();

	ob_start();
		include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-list/products.php');
		$products = ob_get_contents();
		$result->products = $products;
	ob_end_clean();			
	
	$result->check_loadmore = 0;
	
	if(($paged * $numberposts) >= $total)
		$result->check_loadmore = 1;
	die (json_encode($result));
