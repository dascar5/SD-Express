<div class="bwp-widget-banner <?php echo esc_html( $layout ); ?>">
	<?php if( $title1) : ?>
		<div class="title-banner"><h3><?php echo esc_html( $title1 ); ?></h3></div>
	<?php endif;?>
	<div class="bg-banner">
		<?php  if($product_id):
			$product = wc_get_product( $product_id );
			$start_time = get_post_meta( $product_id, '_sale_price_dates_from', true );
			$countdown_time = get_post_meta( $product_id, '_sale_price_dates_to', true );		
			$orginal_price = get_post_meta( $product_id, '_regular_price', true );	
			$sale_price = get_post_meta( $product_id, '_sale_price', true );	
			$symboy = get_woocommerce_currency_symbol( get_woocommerce_currency() );
			$date = bwp_timezone_offset( $countdown_time );			
		?>
		<a href="<?php echo get_permalink( $product_id );  ?>">
				<img src="<?php echo esc_url($image); ?>" alt=""></a>
		<?php endif;?>
		<div class="products-content">
			<?php $discount = bwp_get_product_discount(); ?> 
			<h3 class="product-title"><a href="<?php echo get_permalink( $product_id );  ?>"><?php echo $product->get_title(); ?></a></h3>
			<div class="product-price"><?php echo esc_html__('Price From ', 'wpbingo' ); ?><?php echo $product->get_price_html(); ?></div>
			<div class="product-description"><?php echo wp_trim_words( $product->get_short_description(),15); ?></div>
		</div>
		<?php if( $time_deal) : ?>
			<div class="countdown-deal">
				<?php
					$start_time = time();
					$countdown_time = strtotime($time_deal);
					$date = bwp_timezone_offset( $countdown_time );
				?>
				<div class="product-countdown"  
					data-day="<?php echo esc_html__("Days","wpbingo"); ?>"
					data-hour="<?php echo esc_html__("Hours","wpbingo"); ?>"
					data-min="<?php echo esc_html__("Mins","wpbingo"); ?>"
					data-sec="<?php echo esc_html__("Secs","wpbingo"); ?>"	
					data-date="<?php echo esc_attr( $date ); ?>"  
					data-sttime="<?php echo esc_attr( $start_time ); ?>" 
					data-cdtime="<?php echo esc_attr( $countdown_time ); ?>" 
					data-id="<?php echo esc_attr($widget_id); ?>">
				</div>
			</div>	
		<?php endif;?>
	</div>
</div>
