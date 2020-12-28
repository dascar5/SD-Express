<?php   
if( $select_category && $checkbox_order ){
	$numberposts = (int)$numberposts;
?>
<div class="bwp-filter-homepage slider tab-product tab-product-slider <?php echo esc_attr($layout); ?>" data-numberposts = "<?php echo esc_attr($numberposts); ?>">
	<div class="bwp-filter-heading">
		<?php if(isset($title1) && $title1) { ?>
		<div class="title-block">
			<h2><?php echo esc_html($title1); ?></h2>
			<?php if($description) { ?>
			<div class="page-description"><?php echo $description; ?></div>
			<?php } ?> 
		</div>
		<?php } ?>
		<ul class="filter-category hidden">
			<li class="active" data-value="<?php echo esc_attr($select_category); ?>">
				<span><?php echo esc_html($select_category); ?></span>
			</li>	
		</ul>
		<div class="filter-order-by">
			<ul class="filter-orderby">
				<?php $i=0; foreach($checkbox_order as $option){
					$tab_title = '';						
					switch ($option) {
						case 'date':
							$tab_title = __( 'Lastest Products', "wpbingo" );
						break;
						case 'popularity':
							$tab_title = __( 'Best Selling', "wpbingo" );
						break;						
						case 'featured':
							$tab_title = __( 'Featured', "wpbingo" );
						break;
						case 'rating':
							$tab_title = __( 'Top Rating', "wpbingo" );
						break;
				} ?>			
				<li data-value="<?php echo esc_attr($option); ?>" <?php if($i == 0) echo 'class="active"'?>><?php echo $tab_title; ?></li>
				<?php $i++;} ?>				
			</ul>
		</div>		
	</div>
	<div class="bwp-filter-content">
		<?php
		$select_order = (isset($checkbox_order[0]) && $checkbox_order[0]) ? $checkbox_order[0] : 'date';
		$args = array(
			'post_type' 			=> 'product',
			'post_status' 			=> 'publish',
			'posts_per_page' 		=> $numberposts,	
		);
		$tax_query = array();
		if($select_category != 'all'){
			$tax_query[] = array(
							'taxonomy'	=> 'product_cat',
							'field'		=> 'slug',
							'terms'		=> $select_category );
		}
		$meta_query = array();
		switch ($select_order) {
			case 'date':
				$args['orderby']	= 'date';
			break;
			case 'rating':
				add_filter( 'posts_clauses',  'order_by_rating_post_clauses'  );				
			break;
			case 'popularity':
				$args['meta_key']	= 'total_sales';
				$args['orderby']	= 'meta_value_num';
			break;
			case 'featured':
				$product_visibility_term_ids = wc_get_product_visibility_term_ids();
				$tax_query[] = 	array(
									'taxonomy' => 'product_visibility',
									'field'    => 'term_taxonomy_id',
									'terms'    => $product_visibility_term_ids['featured'],
								);		
			break;
		}	
		$args['tax_query'] = $tax_query;
		$args['meta_query'] = $meta_query;
		$list = new WP_Query( $args );
		$j = 0;
		?>
		<ul class="filter-orderby hidden">	  
			<li data-value="<?php echo esc_attr($select_order); ?>" class="active"><?php echo esc_html($select_order); ?></li>
		</ul>
		<div class="content-product-list content-products-<?php echo esc_attr($select_order); ?>">
			<div class="content products-list grid slick-carousel row" data-dots="<?php echo esc_attr($show_pag);?>" data-item_row="<?php echo esc_attr($item_row); ?>" data-nav="<?php echo esc_attr($show_nav);?>" data-columns4="<?php echo esc_attr($columns4); ?>" data-columns3="<?php echo esc_attr($columns3); ?>" data-columns2="<?php echo esc_attr($columns2); ?>" data-columns1="<?php echo esc_attr($columns1); ?>" data-columns="<?php echo esc_attr($columns); ?>" data-columns1500="<?php echo esc_attr($columns1500); ?>">
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
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</div>	
</div>
<?php } ?>