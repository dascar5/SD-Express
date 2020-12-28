<div class="bwp-widget-banner <?php echo esc_html( $layout ); ?>">
	<?php  if($image): ?>	
	<div class="bg-banner">		
		<div class="banner-wrapper">
			<div class="title-block">
			<?php if( $title1) : ?>
			<h2 class="title-banner"><?php echo esc_html( $title1 ); ?></h2>
			<?php endif; ?>
			</div>
			<div class="bwp-image">
				<?php  if($link): ?>
				<a href="<?php echo esc_url($link);?>">
						<img src="<?php echo esc_url($image); ?>" alt=""></a>
				<?php endif;?>
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
							data-id="<?php echo $widget_id; ?>">
						</div>
					</div>
				<?php endif;?>
			</div>
		</div>
	</div>
	<?php endif;?>
</div>
