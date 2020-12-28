<?php
$class_col_lg = ($columns == 5) ? '2-4'  : (12/$columns);
$class_col_md = ($columns1 == 5) ? '2-4'  : (12/$columns1);
$class_col_sm = ($columns2 == 5) ? '2-4'  : (12/$columns2);
$class_col_xs = ($columns3 == 5) ? '2-4'  : (12/$columns3);
$attributes = 'col-xl-'.$class_col_lg .' col-lg-'.$class_col_md .' col-md-'.$class_col_sm .' col-'.$class_col_xs; 
do_action( 'before' ); 
if ( $list -> have_posts() ){ ?>
	<div class="bwp-countdown <?php echo esc_attr($class); ?> <?php if(empty($title1)) echo 'no-title'; ?>">
		<?php if($title1) { ?>
		<div class="block-title">
			<h2><?php echo $title1; ?></h2>
			<?php if($description) { ?>
			<div class="page-description"><?php echo $description; ?></div>
			<?php } ?>       
		</div> 
		<?php } ?>         
		<div class="row">	
		<?php while($list->have_posts()): $list->the_post();?>
			<?php
			global $product, $post, $wpdb, $average;
			$start_time = get_post_meta( $post->ID, '_sale_price_dates_from', true );
			$countdown_time = get_post_meta( $post->ID, '_sale_price_dates_to', true );		
			$orginal_price = get_post_meta( $post->ID, '_regular_price', true );	
			$sale_price = get_post_meta( $post->ID, '_sale_price', true );	
			$symboy = get_woocommerce_currency_symbol( get_woocommerce_currency() );
			$date = bwp_timezone_offset( $countdown_time );
			?>
			<div class="item-product <?php echo $attributes; ?>">
				<div class="item-product-content">
					<?php include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'content-product.php'); ?>
					<div class="item-countdown">
						<div class="product-countdown"  data-date="<?php echo esc_attr( $date ); ?>" data-price="<?php echo esc_attr( $symboy.$orginal_price ); ?>" data-sttime="<?php echo esc_attr( $start_time ); ?>" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>" data-id="<?php echo 'product_'.$widget_id.$post->ID; ?>"></div>
					</div>	
				</div>
			</div>
			<?php endwhile; wp_reset_postdata();?>
		</div>	
	</div>
	<?php
	}
?>