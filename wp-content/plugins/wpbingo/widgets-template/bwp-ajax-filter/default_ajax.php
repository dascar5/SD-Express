<?php
/**
 * Layout Default ajax
 * @version     1.0.0
 **/
	global $wp_query,$dimita_settings;
	
	$filter 		= (isset($_POST['filter']) && $_POST['filter'] ) ? $_POST['filter'] : array();
	$data 			= (isset($filter['data']) && $filter['data']) ? $filter['data'] : array();
	$base_url 		= $_POST['base_url'] ? $_POST['base_url'] : '';
	$id_category 	= $_POST['id_category'] ? $_POST['id_category'] : 0;
	$attribute 		= $_POST['attribute'] ? explode(',',$_POST['attribute']) : array();
	$relation 		= $_POST['relation'] ? $_POST['relation'] : 'AND';
	$widget_template = (isset($_POST['widget_template']) && $_POST['widget_template']) ? $_POST['widget_template'] : 'default';
	$showcount 		= (isset($_POST['showcount']) && $_POST['showcount'] ) ? $_POST['showcount'] : 0 ;
	$show_price 	= (isset($_POST['show_price']) && $_POST['show_price'] ) ? $_POST['show_price'] : 0 ;
	$show_only_sale = (isset($_POST['show_only_sale']) && $_POST['show_only_sale'] ) ? $_POST['show_only_sale'] : 0 ;
	$show_price 	= (isset($_POST['show_price']) && $_POST['show_price'] ) ? $_POST['show_price'] : 0 ;
	$show_in_stock 	= (isset($_POST['show_in_stock']) && $_POST['show_in_stock'] ) ? $_POST['show_in_stock'] : 0;
	$show_brand 	= (isset($_POST['show_brand']) && $_POST['show_brand'] ) ? $_POST['show_brand'] : 0;
	$show_bestseller 	= (isset($_POST['show_bestseller']) && $_POST['show_bestseller'] ) ? $_POST['show_bestseller'] : 0;
	$show_banner 		= (isset($_POST['show_banner']) && $_POST['show_banner'] ) ? $_POST['show_banner'] : 0;
	$array_value_url 	= 	(isset($_POST['array_value_url']) && $_POST['array_value_url'] ) ? unserialize(base64_decode($_POST['array_value_url'])) : array();
	$tax_query = array();
	if( $id_category == 0 ){
		$link = $base_url;
	}else{
		$link = get_category_link( $id_category );
	}
	$check_filter = array('only_sale','in_stock','min_price','max_price','paged','orderby','filter_brand');
	if($attribute){	
		foreach($attribute as $att){
			$check_filter[] = 'filter_'.$att;
		}
	}

	$meta_query	= array();
	
	if($array_value_url){
		foreach($array_value_url as $key=>$value_url)
		{
			if($key == "s")
				$check_search = $value_url;
			if(!in_array($key,$check_filter))
				$link = add_query_arg( $key, $value_url, $link );
		}
	}	
	
	if($id_category != 0){
		$tax_query[] =         
			array(
				'taxonomy'      => 'product_cat',
				'field' 		=> 'term_id', //This is optional, as it defaults to 'term_id'
				'terms'         => $_POST['id_category'],
				'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
			);	
	}
	
	$filter_size = array();
	$chosen_attributes	 = array();
	$chosen_att	 = array();
	if($data){
		$f_data = array();
		foreach($data as $d){
			$f_data[$d['name']][] = $d['value'];
		}

		foreach($f_data as $key=>$p){
			if($key == 'only_sale'){
				$meta_query[]	= array(
						'relation' => 'OR',
						array( // Simple products type
							'key'           => '_sale_price',
							'value'         => 0,
							'compare'       => '>',
							'type'          => 'numeric'
						),
						array( // Variable products type
							'key'           => '_min_variation_sale_price',
							'value'         => 0,
							'compare'       => '>',
							'type'          => 'numeric'
						)
					);
				$link = add_query_arg( 'only_sale', 'only', $link );		
				$chosen_attributes['only_sale'] = 'only';
				
			}elseif($key == 'in_stock'){
				$meta_query[]	= array(
				  'key' => '_stock_status',
				  'value' => 'outofstock',
				  'compare' => '!='
				);				
				$link = add_query_arg( 'in_stock', 'in', $link );		
				$chosen_attributes['in_stock'] = 'in';	
			}
			elseif($key == 'filter_brand'){
				$tax_query[] =         array(
					'taxonomy'      => 'product_brand',
					'field' 		=> 'slug', 
					'terms'         => $p,
					'operator'      => 'AND'
				);
				$link = add_query_arg( 'filter_brand', implode( ',', $p ), $link );	
				$chosen_attributes['pa_brand']['terms'] = $p;
				$chosen_attributes['pa_brand']['query_type'] = 'and';
			}			
			else{
				$att = str_replace("filter_","",$key);
				$tax_query[] =         array(
					'taxonomy'      => 'pa_'.$att,
					'field' 		=> 'slug', //This is optional, as it defaults to 'term_id'
					'terms'         => $p,
					'operator'      => $relation // Possible values are 'IN', 'NOT IN', 'AND'.
				);
				$chosen_att[$att] = $p;
				$chosen_attributes['pa_'.$att]['terms'] = $p;
				$chosen_attributes['pa_'.$att]['query_type'] = $relation;
			}	
		}			
		foreach($attribute as $att){
			if($chosen_att[$att]){
				$link = add_query_arg( 'filter_'.$att, implode( ',', $chosen_att[$att] ), $link );
			}	
		}	
	}

	$default_min_price = isset($filter['default_min_price']) ? $filter['default_min_price'] : '' ;
	$default_max_price = isset($filter['default_max_price']) ? $filter['default_max_price'] : '' ;
	$min_price = isset($filter['min_price']) ? $filter['min_price'] : '' ;
	$max_price = isset($filter['max_price']) ? $filter['max_price'] : '' ;
	
	if(($min_price && ($min_price != $default_min_price)) || ($max_price && ($max_price != $default_max_price))){
		$link = add_query_arg( 'min_price', $min_price, $link );
		$link = add_query_arg( 'max_price', $max_price, $link );
		$chosen_attributes['min_price'] = $min_price;		
		$chosen_attributes['max_price'] = $max_price;		
		$meta_query[] =  array(
			'key'          => '_price',
			'value'        => array( $min_price, $max_price ),
			'compare'      => 'BETWEEN',
			'type'         => 'DECIMAL',
		);
	}	
	
	
	$paged = $filter['paged'] ? $filter['paged'] : 1;
	
	$per_page 	=   (isset($dimita_settings['product_count']) && $dimita_settings['product_count'])  ? (int)$dimita_settings['product_count'] : 12;
	$default_posts_per_page = (isset($filter['product_count']) && $filter['product_count'] ) ? $filter['product_count'] : $per_page;
	$args = array(
		'post_type'             => 'product',
		'post_status'           => 'publish',
		'ignore_sticky_posts'   => 1,
		'paged' 					=>	$paged,
		'posts_per_page'        => $default_posts_per_page,
		'meta_query'            => $meta_query,
		'tax_query'             => $tax_query
	);	
	
	if(isset($check_search)){
		$args['s'] = $check_search;
	}

	$orderby = '';
	$order = '';
	$orderby_value = $l_orderby =  isset( $filter['orderby'] ) ? wc_clean( $filter['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
	// Get order + orderby args from string
	$orderby_value = explode( '-', $orderby_value );
	$orderby       = esc_attr( $orderby_value[0] );
	$order         = ! empty( $orderby_value[1] ) ? $orderby_value[1] : $order;

	$orderby = strtolower( $orderby );
	$order   = strtoupper( $order );
	// default - menu_order
	$args['orderby']  = 'menu_order title';
	$args['order']    = $order == 'DESC' ? 'DESC' : 'ASC';
	$args['meta_key'] = '';
	
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
		break;
	}	
	
	if($l_orderby != 'menu_order')
		$link = add_query_arg( 'orderby', $l_orderby, $link );
	
	$args_count 	= 	$args;
	$args_count['posts_per_page'] 	= 	-1;

	$wp_query_count = new WP_Query($args_count);	
	$total = $wp_query_count->post_count;
		
	$wp_query = new WP_Query($args);
	$result = new stdClass();
	
	$result->base_url = $link;
	$category_bg_breadcrumb = get_term_meta( $id_category, 'category_bg_breadcrumb', true ) ? get_term_meta( $id_category, 'category_bg_breadcrumb', true ) : "";
	$result->result_background = $category_bg_breadcrumb;
	ob_start();
		$category_view_mode = $filter['views'] ? $filter['views'] : 'grid';
		include(WPBINGO_WIDGET_TEMPLATE_PATH.'bwp-ajax-filter/products.php');
		$products = ob_get_contents();
		$result->products = $products;
	ob_end_clean();

	ob_start();
		wc_get_template( 'loop/result-count.php' );
		$result_count = ob_get_contents();
		$result->result_count = $result_count;
	ob_end_clean();

	ob_start();
		include(WPBINGO_WIDGET_TEMPLATE_PATH.'bwp-ajax-filter/filter.php');
		$left_nav = ob_get_contents();
		$result->left_nav = $left_nav;
	ob_end_clean();
	
	ob_start();
		include(WPBINGO_WIDGET_TEMPLATE_PATH.'bwp-ajax-filter/title.php');
		$title = ob_get_contents();
		$result->result_title = $title;
	ob_end_clean();
	
	ob_start();
		include(WPBINGO_WIDGET_TEMPLATE_PATH.'bwp-ajax-filter/breadcrumb.php');
		$breadcrumb = ob_get_contents();
		$result->result_breadcrumb = $breadcrumb;
	ob_end_clean();	

	ob_start();
		wc_get_template( 'loop/pagination.php' );
		$pagination = ob_get_contents();
		$result->pagination = $pagination;
	ob_end_clean();
	
	ob_start();
		wc_get_template( 'loop/woocommerce-found-posts.php' );
		$total_html = ob_get_contents();
		$result->total_html = $total_html;
	ob_end_clean();	

	die (json_encode($result));
