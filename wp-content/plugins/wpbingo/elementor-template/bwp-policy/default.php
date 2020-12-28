
<div class="bwp-policy <?php echo esc_attr($layout); ?>">
	<?php  if($image || $title1): ?>
		<div class="policy-icon">
			<img src="<?php echo esc_url($image); ?>" alt="">
		</div>
	<?php endif;?>
	<?php if($desc) : ?>
		<div class="policy-info">
		
			<?php if(isset($title1) && $title1) : ?>
				<h3 class="title-policy"><?php echo esc_html( $title1 ); ?></h3>
			<?php endif; ?>
			<?php if( $desc) : ?>
				<div class="desc-policy"><?php echo esc_html( $desc ); ?></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div><!-- .bwp-policy -->
