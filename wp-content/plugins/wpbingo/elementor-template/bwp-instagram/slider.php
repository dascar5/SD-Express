<?php $j = 0; ?>
<div class="bwp-instagram <?php echo esc_attr($layout); ?>">
 <div class="block">
 	<?php if(isset($title1) && $title1) {?>
	<div class="block-title">
		<div class="instagram-title">
			<?php if($subtitle) { ?>
			<div class="subtitle"><?php echo $subtitle; ?></div>
			<?php } ?> 
			<?php
				echo '<h2>'. esc_html($title1) .'</h2>';
			?>
		</div>
		<?php if(isset($description) && $description) {?>
		<div class="description"><?php echo $description; ?></div>
		<?php } ?>
	</div>
	<?php } ?>
	<div class="block_content">
		<div class="instagram">
			<div class="slick-carousel" data-dots="<?php echo esc_attr($show_pag);?>" data-item_row="<?php echo esc_attr($item_row); ?>" data-nav="<?php echo esc_attr($show_nav);?>" data-columns4="<?php echo esc_attr($columns4); ?>" data-columns3="<?php echo esc_attr($columns3); ?>" data-columns2="<?php echo esc_attr($columns2); ?>" data-columns1="<?php echo esc_attr($columns1); ?>" data-columns="<?php echo esc_attr($columns); ?>">
				<?php foreach ($settings['list_tab'] as  $item){ ?>
					<?php if( $item['image_slider'] && $item['image_slider']['url'] ){ ?>
					<?php	if( ( $j % $item_row ) == 0 && $item_row !=1) { ?>
						<div class="item-instagram">
					<?php } ?>
						<div  class="image-instagram">
							<a class="image" href="<?php echo esc_url($item['link_instagram']); ?>">
								<img src="<?php echo esc_url($item['image_slider']['url']); ?>" alt="<?php echo esc_attr__('Image Slider','wpbingo'); ?>">
							</a>
						</div>
					<?php if((($j + 1) % $item_row == 0 || $j== count($settings['list_tab'])) && $item_row !=1  ){?>
						</div>
					<?php  } $j++;?>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	</div>
  <div class="description"><?php echo $description; ?></div>
 </div>
</div>