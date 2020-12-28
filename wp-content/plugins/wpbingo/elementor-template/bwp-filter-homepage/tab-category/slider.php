<?php
if( $category ){ 	
	$numberposts = (int)$numberposts;
	$cat_selected = '';
	$widget_id = 'tab_category_'.rand().time();
	$numberposts = (int)$numberposts;
?>
<div class="bwp-filter-homepage slider <?php echo esc_attr($layout);?>" data-numberposts = "<?php echo esc_attr($numberposts); ?>"  data-showmore="<?php echo esc_attr($columns); ?>">
	<div class="box-content">
		<div class="bwp-filter-heading">
			<div class="category-tab-nav">
				<?php if(isset($title1) && $title1) { ?>
					<div class="title-block">
						<h2><?php echo esc_html($title1); ?></h2>
					</div>
				<?php } ?>
				<ul class="filter-category">
					<?php
					foreach($category as $key => $cat){	?>
						<?php
						$term = get_term_by('slug', $cat, 'product_cat');
						?>
						<?php if($cat == 'all'){?>
							<li class="category <?php if( ( $key + 1 ) == 1 ){echo 'active'; $cat_selected = $cat;}?>" data-value="<?php echo esc_attr($cat); ?>">
								<a href="#<?php echo esc_attr($widget_id).'_' . $cat; ?>" data-toggle="tab">
									<?php echo esc_html__( "All", 'wpbingo'); ?>
								</a>
							</li>
						<?php }else{?>
							<?php $terms = get_term_by('slug', $cat, 'product_cat');
							if($terms) : ?>
							<li class="category <?php if( ( $key + 1 ) == 1 ){echo 'active'; $cat_selected = $cat;}?>" data-value="<?php echo esc_attr($cat); ?>">					
								<a class="name-category" href="#<?php echo esc_attr($widget_id).'_'. $cat; ?>" data-toggle="tab">
									<?php echo esc_html($terms->name); ?>
								</a>
							</li>
							<?php endif; ?>
						<?php }?>
					<?php } ?>
				</ul>
				<a class="button-category" href="#"><?php  echo esc_html__("all category","wpbingo") ?></a>
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
			$j = 0;
			?>
			<div class="content-product-list content-products-<?php echo esc_attr($cat_selected); ?>">
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
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>