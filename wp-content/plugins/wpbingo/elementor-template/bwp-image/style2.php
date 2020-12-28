<div class="bwp-widget-banner <?php echo esc_html( $layout ); ?>">
	<?php  if($image): ?>	
	<div class="bg-banner">		
		<div class="banner-wrapper">
			<div class="bwp-image">
				<?php  if($link): ?>
				<a href="<?php echo esc_url($link);?>">
					<img src="<?php echo esc_url($image); ?>" alt="">
				</a>
				<?php endif;?>
				<div class="banner-info"></div>
				<div class="content-animation"><div></div><div></div><div></div></div>
			</div>
		</div>
	</div>
	<?php endif;?>
</div>
