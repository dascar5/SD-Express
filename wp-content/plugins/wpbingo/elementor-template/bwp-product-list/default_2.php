
<?php
$widget_id = isset( $widget_id ) ? $widget_id : 'bwp_woo_slider_'.rand().time();
$class_col_lg = ($columns == 5) ? '2-4'  : (12/$columns);
$class_col_md = ($columns1 == 5) ? '2-4'  : (12/$columns1);
$class_col_sm = ($columns2 == 5) ? '2-4'  : (12/$columns2);
$class_col_xs = ($columns3 == 5) ? '2-4'  : (12/$columns3);
$attributes = 'col-xl-'.$class_col_lg .' col-lg-'.$class_col_md .' col-md-'.$class_col_sm .' col-'.$class_col_xs;
$count = $list->post_count;
$j = 1;
do_action( 'before' ); 
if ( $list -> have_posts() ){ ?>
	<div id="<?php echo $widget_id; ?>" class="bwp_list_default <?php echo $widget_class; ?> <?php echo esc_attr($layout); ?> <?php echo esc_attr($class); ?> <?php if(empty($title1)) echo 'no-title'; ?>">
		<div class="content products-list grid row">	
		<?php while($list->have_posts()): $list->the_post();
		global $product, $post, $wpdb, $average; ?>
			<?php	if( ($j == 1) ||  ( $j % $item_row  == 1 ) || ( $item_row == 1 )) { ?>
				<div class="item-product <?php echo esc_attr($attributes); ?>">
			<?php } ?>
				<?php global $product, $woocommerce_loop, $post; ?>
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
						<?php woocommerce_template_loop_rating(); ?>
						<h3 class="product-title"><a href="<?php esc_url(the_permalink()); ?>"><?php esc_html(the_title()); ?></a></h3>
						<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
					</div>
				</div>
			<?php if( ($j == $count) || ($j % $item_row == 0) || ($item_row == 1)){?> 
				</div><!-- #post-## -->
			<?php  } $j++;?>
		<?php endwhile; wp_reset_postdata(); ?>		
		</div>			
	</div>
	<?php
	}
?>