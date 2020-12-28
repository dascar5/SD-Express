<?php
	$class_col_lg = ($columns == 5) ? '2-4'  : (12/$columns);
	$class_col_md = ($columns1 == 5) ? '2-4'  : (12/$columns1);
	$class_col_sm = ($columns2 == 5) ? '2-4'  : (12/$columns2);
	$class_col_xs = ($columns3 == 5) ? '2-4'  : (12/$columns3); 
	$attributes = 'col-xl-'.$class_col_lg .' col-lg-'.$class_col_md .' col-md-'.$class_col_sm .' col-'.$class_col_xs; 	
?>
<div class="bwp-instagram default" >
	<div class="block">
		<?php if(isset($title1) && $title1) {?>
			<div class="instagram-title">
				<div class="content-title">
					<?php
						echo '<h2>'. esc_html($title1) .'</h2>';
					?>
					<?php if ($subtitle) { ?>
						<div class="subtitle"><?php echo esc_html($subtitle) ?></div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
		<div class="block_content row">
		<?php foreach ($settings['list_tab'] as  $item){ ?>
			<?php if( $item['image_slider'] && $item['image_slider']['url'] ){ ?>
				<div  class="image-instagram <?php echo esc_attr($attributes); ?>">
					<a class="image" href="<?php echo esc_url($item['link_instagram']); ?>">
						<img src="<?php echo esc_url($item['image_slider']['url']); ?>" alt="<?php echo esc_attr__('Image Slider','wpbingo'); ?>">
					</a>
				</div>
			<?php } ?>
		<?php } ?>
		</div>
	</div>
</div>