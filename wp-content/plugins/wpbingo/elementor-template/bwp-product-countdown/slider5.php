<?php

do_action( 'before' ); 
if ( $list -> have_posts() ){ ?>
	<div id="<?php echo $widget_id; ?>" class="bwp-countdown <?php echo esc_attr($layout); ?><?php echo esc_attr($class); ?> <?php if(empty($title1)) echo 'no-title'; ?>">       
		<?php if($title1) { ?>
		<div class="block-title">
			<?php if($description) { ?>
			<div class="page-description"><?php echo $description; ?></div>
			<?php } ?> 
			<h2 class="title-sidebar"><?php echo $title1; ?></h2>   
		</div> 
		<?php } ?>
		<div class="content-product-list">	
			<div class="slider slider-for products-list grid slick-carousel" data-asnavfor=".slider-nav" data-dots="<?php echo esc_attr($show_pag);?>" data-nav="<?php echo esc_attr($show_nav);?>" data-columns4="<?php echo $columns4; ?>" data-columns3="<?php echo $columns3; ?>" data-columns2="<?php echo $columns2; ?>" data-columns1="<?php echo $columns1; ?>" data-columns="<?php echo $columns; ?>">	
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
				<div class="item-product">	
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
	</div>
	<?php
}
?>