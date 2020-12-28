<div class="bwp-widget-banner <?php echo esc_html( $layout ); ?>">
	<div class="title-block">
		<?php if( $title1) : ?>
		<h2 class="title-banner"><?php echo esc_html( $title1 ); ?></h2>
		<?php endif; ?>
	</div>
	<?php  if($image): ?>
		<div class="bwp-image">
			<?php  if($link): ?>
			<a href="<?php echo esc_url($link);?>">
					<img src="<?php echo esc_url($image); ?>" alt=""></a>
			<?php endif;?>
			<?php if( $subtitle) : ?>
			<div class="bwp-image-subtitle">
				<?php if(isset($subtitle) && $subtitle){?>						
					<?php echo ($subtitle); ?>							
				<?php }?>
			</div>
			<?php endif; ?>
		</div>
	<?php endif;?>
</div>
