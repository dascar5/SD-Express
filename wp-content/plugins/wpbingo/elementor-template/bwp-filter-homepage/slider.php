<?php   
if( $category && $category ){
	$arr_category = $category ;
	$category = implode( ',', $category ); 	
	$numberposts = (int)$numberposts;
	$count_loadmore = ceil($numberposts/$columns);
	$class_col_lg = ($columns == 5) ? '2-4'  : (12/$columns);
	$class_col_md = ($columns1 == 5) ? '2-4'  : (12/$columns1);
	$class_col_sm = ($columns2 == 5) ? '2-4'  : (12/$columns2);
	$class_col_xs = ($columns3 == 5) ? '2-4'  : (12/$columns3);
	$class_col = 'col-lg-'.$class_col_lg .' col-md-'.$class_col_md .' col-sm-'.$class_col_sm .' col-xs-'.$class_col_xs; 	
	$cat_selected = '';
	$catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
		'menu_order' => esc_html__( 'Default sorting', 'wpbingo' ),
		'popularity' => esc_html__( 'Sort by popularity', 'wpbingo' ),
		'rating'     => esc_html__( 'Sort by average rating', 'wpbingo' ),
		'date'       => esc_html__( 'Sort by newness', 'wpbingo' ),
		'price'      => esc_html__( 'Sort by price: low to high', 'wpbingo' ),
		'price-desc' => esc_html__( 'Sort by price: high to low', 'wpbingo' ),
	));	
	$meta_query	= array();
	if (in_array("all", $arr_category)){
		$tax_query = array();		
	}else{
		$tax_query = array(
			array(
				'taxonomy'      => 'product_cat',
				'field' 		=> 'term_id', //This is optional, as it defaults to 'term_id'
				'terms'         => $category,
				'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
			)
		);
	}
	$prices = get_filtered_homepage_price($meta_query,$tax_query);
	$default_min_price    = floor( $prices->min_price );
	$default_max_price    = ceil( $prices->max_price );	
	$attribute_taxonomies = wc_get_attribute_taxonomies();	
	if($attribute_taxonomies){
		$atributes = array();
		foreach( $attribute_taxonomies as $att ){
			$atributes[] = 'pa_'.$att->attribute_name;
		}			
		$atributes = implode(',',$atributes);
	}else
		$atributes = '';
?>
<div class="bwp-filter-homepage filter <?php echo esc_attr($layout);?> <?php echo esc_attr($class); ?>" data-numberposts = "<?php echo esc_attr($numberposts); ?>"  data-showmore="<?php echo esc_attr($columns); ?>" data-atributes="<?php echo esc_attr($atributes); ?>">
	<div class="bwp-filter-heading">
		<ul class="filter-category">
		<?php
			foreach($arr_category as $key => $cat){	?>
					<?php if($cat == 'all'){?>
						<li class="<?php if( ( $key + 1 ) == 1 ){echo 'active'; $cat_selected = $cat;}?>" data-value="<?php echo esc_attr($cat); ?>">
							<span><?php echo esc_html__( "All products", 'wpbingo'); ?></span>
						</li>
					<?php }else{?>
						<?php 
						$terms = get_term_by('slug', $cat, 'product_cat');		
						if($terms) : ?>
						<li class="<?php if( ( $key + 1 ) == 1 ){echo 'active'; $cat_selected = $cat;}?>" data-value="<?php echo esc_attr($cat); ?>">
							<span><?php echo $terms->name; ?></span>
						</li>	
						<?php endif; ?>		
					<?php }?>			
			<?php } ?>
		</ul>
		<a class="bwp-filter-toggle"><?php echo  esc_html__( 'Filter', 'wpbingo' ); ?><i class="icon_adjust-horiz"></i></a>	
		<div class="filter-order-by dropdown">
			<button type="button" data-toggle="dropdown">
			<span class="text-orderby"><?php echo esc_html__( "Sort by", 'wpbingo'); ?></span>
			<span class="caret"></span></button>
			<ul class="filter-orderby dropdown-menu">
				<?php $i=0; foreach($catalog_orderby_options as $key => $option){	?>		  
				<li data-value="<?php echo esc_attr($key); ?>" <?php if($i == 0) echo 'class="active"'?>><?php echo $option; ?></li>
				<?php $i++;} ?>
			</ul>
		</div> 		
		<div class="bwp-filter-attribute">
			<div class="bwp-filter-attribute-inner">
				<?php woocommerce_filter_homepage_price($default_min_price,$default_max_price); ?>
				<?php woocommerce_filter_homepage_atribute(); ?>	
				<?php woocommerce_filter_homepage_brand(); ?>	
			</div>
			<div class="clear_all"><span><?php echo  esc_html__( 'Clear All', 'wpbingo' ); ?></span></div>	
		</div>	
	</div>
	<div class="bwp-filter-content">
		<?php
		$args  = array(
			'post_type' 			=> 'product',
			'post_status' 			=> 'publish',
			'ignore_sticky_posts'   => 1,
			'tax_query'	=> array(
					array(
						'taxonomy'	=> 'product_cat',
						'field'		=> 'slug',
						'terms'		=> $cat_selected
					)
				),
			'posts_per_page' 		=> $numberposts,
		);

		$orderby = '';
		$order = '';
		$orderby_value = apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
		$orderby_value = explode( '-', $orderby_value );
		$orderby       = esc_attr( $orderby_value[0] );
		$order         = ! empty( $orderby_value[1] ) ? $orderby_value[1] : $order;
		$orderby = strtolower( $orderby );
		$order   = strtoupper( $order );
		// default - menu_order
		$args['orderby']  = 'title';
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
		
		if($cat_selected == 'all')
			unset($args['tax_query']);	
		$list = new WP_Query( $args );
		
		$args['posts_per_page'] = -1;
		$list_total = new WP_Query( $args );
		$total = $list_total->post_count;
		$j = 0;
		?>
		<div class="content products-list grid slick-carousel row" data-item_row="<?php echo esc_attr($item_row); ?>" data-dots="<?php echo esc_attr($show_pag);?>" data-nav="<?php echo esc_attr($show_nav);?>" data-columns4="<?php echo $columns4; ?>" data-columns3="<?php echo $columns3; ?>" data-columns2="<?php echo $columns2; ?>" data-columns1="<?php echo $columns1; ?>" data-columns1500="<?php echo $columns1500; ?>" data-columns="<?php echo $columns; ?>">
			<?php while($list->have_posts()): $list->the_post();
				global $product, $post, $wpdb, $average; 
				if( ( $j % $item_row ) == 0 && $item_row !=1) { ?>
					<div class="item">
				<?php } ?>
					<div class="item-product">
						<?php include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'content-product.php'); ?>
					</div>
				<?php if( ($j % $item_row == 1 || $j == $list->post_count) && $item_row !=1  ){?>
					</div>
				<?php  } $j++;?>	
			<?php endwhile; wp_reset_postdata();?>
		</div>
	</div>
</div>
<?php } ?>