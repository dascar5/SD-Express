<div class="bwp-widget-banner <?php echo esc_html( $layout ); ?>">
	<div class="bg-banner">		
		<div class="banner-wrapper">
			<?php if( $title1) : ?>
			<div class="title-banner"><h2><?php echo esc_html( $title1 ); ?></h2></div>
			<?php endif; ?>
			<div class="banner-wrapper-centainer">
				<div class="banner-wrapper-infor">
					<?php if( $subtitle) : ?>
						<div class="bwp-image-subtitle">
							<?php if(isset($subtitle) && $subtitle){?>						
								<?php echo ($subtitle); ?>							
							<?php }?>
						</div>	
					<?php endif;?>
					<?php if( $description) : ?>
						<div class="bwp-image-description">
							<?php if(isset($description) && $description){?>						
								<?php echo ($description); ?>							
							<?php }?>
						</div>	
					<?php endif;?>
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
							data-id="<?php echo $widget_id; ?>">
						</div>
					</div>
				<?php endif;?>	
				<?php if( $label) : ?>
					<div class="button-banner">
						<a class="btn-banner" href="<?php echo esc_url($link);?>"><?php echo esc_html( $label ); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
