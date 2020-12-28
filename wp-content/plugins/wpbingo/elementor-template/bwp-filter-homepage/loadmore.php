<?php   
if( $category || $checkbox_order){
	$arr_category = explode( ',', $category ); 	
	$numberposts = (int)$numberposts;
	$count_loadmore = ceil($numberposts/$columns);
	$class_col_lg = ($columns == 5) ? '2-4'  : (12/$columns);
	$class_col_md = ($columns1 == 5) ? '2-4'  : (12/$columns1);
	$class_col_sm = ($columns2 == 5) ? '2-4'  : (12/$columns2);
	$class_col_xs = ($columns3 == 5) ? '2-4'  : (12/$columns3);
	$class_col = 'col-lg-'.$class_col_lg .' col-md-'.$class_col_md .' col-sm-'.$class_col_sm .' col-xs-'.$class_col_xs; 	
	$cat_selected = '';	
	$meta_query	= array();
	if (empty($category) || in_array("all", $arr_category)){
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

	if( $select_category && $checkbox_order ){
		if( !is_array( $checkbox_order ) ){
			$checkbox_order = explode( ',', $checkbox_order );
		}	
	}
?>
<div class="bwp-filter-homepage loadmore <?php echo esc_attr($class); ?>" data-class_col = "<?php echo esc_attr($class_col); ?>" data-numberposts = "<?php echo esc_attr($numberposts); ?>"  data-showmore="<?php echo esc_attr($columns); ?>">
	<div class="bwp-filter-heading">
		<?php if(isset($title1) && $title1) { ?>
		<div class="title-block">
			<?php if($description) { ?>
			<div class="page-description"><?php echo $description; ?></div>
			<?php } ?> 
			<h2><?php echo esc_html($title1); ?></h2>
		</div>
		<?php } ?>
		<?php if(!empty($category)){?>
		<div class="category-nav">
			<ul class="filter-category">
			<?php
				foreach($arr_category as $key => $cat){	?>
						<?php if($cat == 'all'){?>
							<li class="<?php if( ( $key + 1 ) == 1 ){echo 'active'; $cat_selected = $cat;}?>" data-value="<?php echo esc_attr($cat); ?>">
								<div class="item-thumbnail">
									<img src="<?php echo get_template_directory_uri() . '/images/thumbnail1-all.png'; ?>" alt="<?php echo esc_attr($cat); ;?>" />
								</div>
								<span><?php echo esc_html__( "All Products", 'wpbingo'); ?></span>
							</li>
						<?php }else{?>
							<?php 
							$terms = get_term_by('slug', $cat, 'product_cat');
							$thumbnail_id1 = get_term_meta( $terms->term_id, 'thumbnail_id1', true );
							$thumb1 = $thumbnail_id1;
							if(!$thumb1)
								$thumb1 = wc_placeholder_img_src();								
							if($terms) : ?>
							<li class="<?php if( ( $key + 1 ) == 1 ){echo 'active'; $cat_selected = $cat;}?>" data-value="<?php echo esc_attr($cat); ?>">
								<?php if(isset($thumb1) && $thumb1) : ?>
									<div class="item-thumbnail">
										<img src="<?php echo esc_url($thumb1); ?>" alt="<?php echo esc_attr($terms->slug) ;?>" />
									</div>
								<?php endif;?>								
								<span><?php echo esc_html($terms->name); ?></span>
							</li>	
							<?php endif; ?>		
						<?php }?>			
				<?php } ?>
			</ul>		
		</div>
		<?php } ?>
		<?php if(!empty($checkbox_order)){?>
		<div class="filter-order-by">
			<ul class="filter-orderby">
				<?php $i=0; foreach($checkbox_order as $option){
					$tab_title = '';						
					switch ($option) {
						case 'date':
							$tab_title = __( 'Latest Products', "wpbingo" );
						break;
						case 'popularity':
							$tab_title = __( 'Best Sellers', "wpbingo" );
						break;						
						case 'featured':
							$tab_title = __( 'Featured Products', "wpbingo" );
						break;
						case 'rating':
							$tab_title = __( 'Top Rating', "wpbingo" );
						break;
				} ?>			
				<li data-value="<?php echo esc_attr($option); ?>" <?php if($i == 0) echo 'class="active"'?>><?php echo $tab_title; ?></li>
				<?php $i++;} ?>				
			</ul>
		</div>
		<?php } ?>
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
		if(empty($category) || $cat_selected == 'all')
			unset($args['tax_query']);
		$list = new WP_Query( $args );
		
		$args['posts_per_page'] = -1;
		$list_total = new WP_Query( $args );
		$total = $list_total->post_count;
		?>
		<div class="content products-list grid row">
			<?php while($list->have_posts()): $list->the_post();
				global $product, $post, $wpdb, $average; ?>
				<div class="item-product <?php echo $class_col; ?>">
					<?php include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'content-product.php'); ?>
				</div>
			<?php endwhile; wp_reset_postdata();?>
		</div>
	</div>
	<div class="products_loadmore" <?php if($numberposts >= $total) echo 'style="display:none;"' ?>>
		<button type="button" class="btn btn-default loadmore" name="loadmore">
			<i class="fa fa-refresh" aria-hidden="true"></i>
			<span><?php echo esc_html__('View all', 'wpbingo'); ?></span>
		</button>
		<input type="hidden"  data-default = "<?php echo esc_attr($count_loadmore + 1); ?>" value="<?php echo esc_attr($count_loadmore + 1); ?>" class="count_loadmore" />
	</div>	
</div>
<?php } ?>