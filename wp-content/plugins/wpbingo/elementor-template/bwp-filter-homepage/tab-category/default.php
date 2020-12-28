<?php   
if( $category ){	
	$numberposts = (int)$numberposts;
	$class_col_lg = ($columns == 5) ? '2-4'  : (12/$columns);
	$class_col_md = ($columns1 == 5) ? '2-4'  : (12/$columns1);
	$class_col_sm = ($columns2 == 5) ? '2-4'  : (12/$columns2);
	$class_col_xs = ($columns3 == 5) ? '2-4'  : (12/$columns3);
	$class_col = 'col-xl-'.$class_col_lg .' col-lg-'.$class_col_md .' col-md-'.$class_col_sm .' col-'.$class_col_xs; 	
	$cat_selected = '';	
?>
<div class="bwp-filter-homepage tab-category tab-category-default <?php echo esc_attr($class); ?>" data-class_col = "<?php echo esc_attr($class_col); ?>" data-numberposts = "<?php echo esc_attr($numberposts); ?>"  data-showmore="<?php echo esc_attr($columns); ?>">
	<div class="bwp-filter-heading">
		<?php if(isset($title1) && $title1) { ?>
		<div class="title-block">
			<?php if($description) { ?>
			<div class="page-description"><?php echo $description; ?></div>
			<?php } ?> 
			<h2><?php echo esc_html($title1); ?></h2>
		</div>
		<?php } ?>
		<div class="category-nav">
			<ul class="filter-category">
			<?php
				foreach($category as $key => $cat){	?>
						<?php if($cat == 'all'){?>
							<li class="<?php if( ( $key + 1 ) == 1 ){echo 'active'; $cat_selected = $cat;}?>" data-value="<?php echo esc_attr($cat); ?>">
								<span><?php echo esc_html__( "All", 'wpbingo'); ?></span>
							</li>
						<?php }else{?>
							<?php 
							$terms = get_term_by('slug', $cat, 'product_cat');
							$icon_category = get_term_meta( $terms->term_id, 'category_icon', true );							
							if($terms) : ?>
							<li class="<?php if( ( $key + 1 ) == 1 ){echo 'active'; $cat_selected = $cat;}?>" data-value="<?php echo esc_attr($cat); ?>">							
								<?php if(isset($show_icon) && $show_icon) : ?>
									<?php if(isset($icon_category) && $icon_category) : ?>
										<div class="item-icon">
											<i class="<?php echo esc_attr($icon_category); ?>"></i>
										</div>
									<?php endif;?>
								<?php endif;?>
								<span><?php echo $terms->name; ?></span>
							</li>	
							<?php endif; ?>		
						<?php }?>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="bwp-filter-content">
		<?php
		$args = array(
			'post_type' 			=> 'product',
			'post_status' 			=> 'publish',
			'posts_per_page' 		=> $numberposts,	
		);
		$tax_query = array();
		if($cat_selected != 'all'){
			$tax_query[] = array(
							'taxonomy'	=> 'product_cat',
							'field'		=> 'slug',
							'terms'		=> $cat_selected );
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
		?>
		<div class="content-product-list content-products-<?php echo esc_attr($cat_selected); ?>">
			<div class="content products-list grid row">
				<?php while($list->have_posts()): $list->the_post();
					global $product, $post, $wpdb, $average; ?>
					<div class="item-product <?php echo $class_col; ?>">
						<div class="products-entry clearfix product-wapper">
						<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
							<div class="products-thumb">
								<?php
									/**
									 * woocommerce_before_shop_loop_item_title hook
									 *
									 * @hooked woocommerce_show_product_loop_sale_flash - 10
									 * @hooked woocommerce_template_loop_product_thumbnail - 10
									 */
									do_action( 'woocommerce_before_shop_loop_item_title' );
								?>
								<div class='product-button'>
									<?php do_action('woocommerce_after_shop_loop_item'); ?>
								</div>
							</div>
							<div class="products-content">
								<h3 class="product-title"><a href="<?php esc_url(the_permalink()); ?>"><?php esc_html(the_title()); ?></a></h3>
								<?php woocommerce_template_loop_rating(); ?>
								<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
							</div>
						</div>
					</div>
				<?php endwhile; wp_reset_postdata();?>
			</div>
		</div>
	</div>	
</div>
<?php } ?>