<?php   
if( $category ){	
	$numberposts = (int)$numberposts;
	$cat_selected = '';
	$widget_id = 'tab_category_'.rand().time();
	$numberposts = (int)$numberposts;
?>
<div class="bwp-filter-homepage tab-category slider <?php echo esc_attr($layout);?>" data-numberposts = "<?php echo esc_attr($numberposts); ?>"  data-showmore="<?php echo esc_attr($columns); ?>">
	<div class="row">
		<div class="vc_col-sm-3/5">
			<div class="bwp-filter-heading">
				<?php if(isset($title1) && $title1) { ?>
				<div class="title-block">
					<h2><?php echo esc_html($title1); ?></h2>
				</div>
				<?php } ?>
				<ul class="category-tab-nav row">
				<?php
					foreach($category as $key => $cat){	?>
						<?php
						$term = get_term_by('slug', $cat, 'product_cat');
						$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
						$icon_category = get_term_meta( $term->term_id, 'category_icon', true );
						$thumb = wp_get_attachment_url( $thumbnail_id );
						if(!$thumb)
							$thumb = wc_placeholder_img_src();
						?>
						<?php if($cat == 'all'){?>
							<li class="category col-sm-6 <?php if( ( $key + 1 ) == 1 ){echo 'active'; $cat_selected = $cat;}?>" data-value="<?php echo esc_attr($cat); ?>">
								<a href="#<?php echo esc_attr($widget_id).'_' . $cat; ?>" data-toggle="tab">
									<?php echo esc_html__( "All", 'wpbingo'); ?>
								</a>
							</li>
						<?php }else{?>
							<?php $terms = get_term_by('slug', $cat, 'product_cat');
							if($terms) : ?>
							<li class="category col-sm-6 <?php if( ( $key + 1 ) == 1 ){echo 'active'; $cat_selected = $cat;}?>" data-value="<?php echo esc_attr($cat); ?>">					
								<a class="name-category" href="#<?php echo esc_attr($widget_id).'_'. $cat; ?>" data-toggle="tab">
									<?php echo esc_html($terms->name); ?>
								</a>
								<?php if($term->description): ?>
								<p class="description-category">
									<?php echo esc_html($term->description); ?>
								</p>
								<?php endif; ?>
								<a href="#<?php echo esc_attr($widget_id).'_'. $cat; ?>" data-toggle="tab">
									<?php if(isset($show_thumbnail) && $show_thumbnail) : ?>
										<img src="<?php echo esc_url($thumb); ?>" alt="<?php echo $term->slug ;?>" />
									<?php endif;?>
								</a>
							</li>
							<?php endif; ?>
						<?php }?>
					<?php } ?>
				</ul>
			</div>
		</div>
		<div class="vc_col-sm-2/5">
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
					<div class="content products-list grid slick-carousel row" data-item_row="<?php echo esc_attr($item_row); ?>" data-nav="<?php echo esc_attr($show_nav);?>" data-columns4="<?php echo $columns4; ?>" data-columns3="<?php echo $columns3; ?>" data-columns2="<?php echo $columns2; ?>" data-columns1="<?php echo $columns1; ?>" data-columns="<?php echo $columns; ?>">
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
</div>
<?php } ?>