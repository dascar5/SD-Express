<?php
	/**
	 * Layout Default ajax
	 * @version     1.0.0
	 **/
	global $wp_query;
	$filter 		= (isset($_POST['filter']) && $_POST['filter'] ) ? $_POST['filter'] : array();
	$tax_query = array();
	$meta_query	= array();	
	
	if(isset($filter['category']) && $filter['category'] != 'all'){
		$tax_query[] =         
			array(
				'taxonomy'      => 'product_cat',
				'field' 		=> 'slug', 
				'terms'         => $filter['category'],
				'operator'      => 'IN'
			);	
	}
	
	if(isset($filter['brand']) && $filter['brand']){
		$tax_query[] =         array(
			'taxonomy'      => 'product_brand',
			'field' 		=> 'slug',
			'terms'         	=> $filter['brand'],
			'operator'      => 'IN'
		);
	}
	
	$attribute_taxonomies = wc_get_attribute_taxonomies();	
	if($attribute_taxonomies){
		foreach( $attribute_taxonomies as $att ){
			if(isset($filter['pa_'.$att->attribute_name]) && $filter['pa_'.$att->attribute_name]){
				$tax_query[] =         array(
					'taxonomy'      => 'pa_'.$att->attribute_name,
					'field' 		=> 'slug', 
					'terms'         	=>	$filter['pa_'.$att->attribute_name],
					'operator'      => 'IN' 
				);
			}			
		}		
	}	
	
	$min_price = (isset($filter['min_price']) && $filter['min_price'] ) ? $filter['min_price'] : '' ;
	$max_price = (isset($filter['max_price']) && $filter['max_price'] ) ? $filter['max_price'] : '' ;
	if($min_price && $max_price){
		$meta_query[] =  array(
			'key'          => '_price',
			'value'        => array( $min_price, $max_price ),
			'compare'      => 'BETWEEN',
			'type'         => 'DECIMAL',
		);	
	}
	
	
	$paged = (isset($filter['paged']) && $filter['paged']) ? $filter['paged'] : 1;

	$default_posts_per_page = (isset($filter['product_count']) && $filter['product_count'] ) ? $filter['product_count'] : 8;	
	$args = array(
		'post_type'             => 'product',
		'post_status'           => 'publish',
		'paged' 				=>	$paged,
		'posts_per_page'        => $default_posts_per_page
	);
	$orderby = '';
	$order = '';
	$orderby_value = isset( $filter['orderby'] ) ? wc_clean( $filter['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	// Get order + orderby args from string
	$orderby_value = explode( '-', $orderby_value );
	$orderby       = esc_attr( $orderby_value[0] );
	$order         = ! empty( $orderby_value[1] ) ? $orderby_value[1] : $order;

	$orderby = strtolower( $orderby );
	$order   = strtoupper( $order );
	// default - menu_order
	$args['orderby']  = 'title';
	$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
	$args['meta_key'] = '';
	// end default - menu_order
	switch ( $orderby ) {
		case 'rand' :
			$args['orderby']  = 'rand';
		break;
		case 'date' :
			$args['orderby']  = 'date ID';
			$args['order']    = $order == 'ASC' ? 'ASC' : 'DESC';
		break;
		case 'price' :
			$args['orderby']  = "meta_value_num ID";
			$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
			$args['meta_key'] = '_price';
		break;
		case 'rating':
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = '_wc_average_rating';
			$args['order'] = 'desc';
			break;
		case 'popularity':
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = 'total_sales';
			$args['order'] = 'desc';
			break;
		case 'title' :
			$args['orderby']  = 'title';
			$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
		case 'featured':
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();
			$tax_query[] = 	array(
								'taxonomy' => 'product_visibility',
								'field'    => 'term_taxonomy_id',
								'terms'    => $product_visibility_term_ids['featured'],
							);			
		break;
	}
	
	$args['meta_query']	=	$meta_query;
	$args['tax_query']	=	$tax_query;
	
	$list = new WP_Query($args);
	$class_col = (isset($filter['class_col']) && $filter['class_col'] ) ? $filter['class_col'] : '' ;
	$result = new stdClass();
	ob_start();
		$item_row = (isset($filter['item_row']) && $filter['item_row']) ? $filter['item_row'] : 1;
		include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-filter-homepage/products.php');
		$products = ob_get_contents();
		$result->products = $products;
	ob_end_clean();
	
	$args['posts_per_page'] = -1;
	$list_total = new WP_Query($args);
	if(isset($filter['loadmore']) && $filter['loadmore'] == 1)
		$result->loadmore = ($list_total->post_count > ($paged * $default_posts_per_page)) ? 1 : 0 ;	
	else{	
		$result->loadmore = ($list_total->post_count > $list->post_count) ? 1 : 0 ;
	}
	die (json_encode($result));
