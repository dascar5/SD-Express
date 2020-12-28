<?php

do_action( 'before' ); 
if ( $list -> have_posts() ){ ?>
	<div id="<?php echo $widget_id; ?>" class="bwp-countdown <?php echo esc_attr($layout); ?> <?php echo esc_attr($class); ?> <?php if(empty($title1)) echo 'no-title'; ?>">       
		<?php if($title1) { ?>
		<div class="block">
			<?php if($description) { ?>
			<div class="page-description"><?php echo $description; ?></div>
			<?php } ?> 
			<div class="title-block"><h2><?php echo $title1; ?></h2></div>   
		</div> 
		<?php } ?>
		<div class="content-product-list">	
			<div class="slider products-list grid slick-carousel" data-dots="<?php echo esc_attr($show_pag);?>" data-nav="<?php echo esc_attr($show_nav);?>" data-columns4="<?php echo $columns4; ?>" data-columns3="<?php echo $columns3; ?>" data-columns2="<?php echo $columns2; ?>" data-columns1="<?php echo $columns1; ?>" data-columns="<?php echo $columns; ?>">	
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
					<div class="item-product-content products-entry clearfix product-wapper">
						<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
						<div class="grid-content">
							<?php if ( has_post_thumbnail() ) { ?>
							<div class="products-thumb">
								<div class='product-button'>
									<?php do_action('woocommerce_after_shop_loop_item'); ?>
								</div>
								<a  href="<?php esc_url(the_permalink()); ?>">
									<?php echo get_the_post_thumbnail( $post->ID, 'shop_single'); ?>
								</a>
								<div class="item-countdown">
									<div class="product-countdown"  
										data-day="<?php echo esc_html__("Days","wpbingo"); ?>"
										data-hour="<?php echo esc_html__("Hours","wpbingo"); ?>"
										data-min="<?php echo esc_html__("Mins","wpbingo"); ?>"
										data-sec="<?php echo esc_html__("Secs","wpbingo"); ?>"	
										data-date="<?php echo esc_attr( $date ); ?>"  
										data-sttime="<?php echo esc_attr( $start_time ); ?>" 
										data-cdtime="<?php echo esc_attr( $countdown_time ); ?>" 
										data-id="<?php echo $widget_id; ?>">
									</div>
								</div>
							</div>
							<?php } ?>
							<div class="products-content">
								<?php $discount = bwp_get_product_discount(); ?>
								<h3 class="product-title"><a href="<?php esc_url(the_permalink()); ?>"><?php esc_html(the_title()); ?></a></h3>
								<div class="price"><?php echo $product->get_price_html(); ?></div>
							</div>	
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