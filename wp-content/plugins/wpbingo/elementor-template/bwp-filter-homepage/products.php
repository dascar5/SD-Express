<?php $j = 0; ?>
<?php while($list->have_posts()): $list->the_post();
	global $product, $post, $wpdb, $average;
	if( ( $j % $item_row ) == 0 && $item_row !=1) { ?>
	<div class="item">
	<?php } ?>
		<div class="item-product <?php echo $class_col; ?>">
			<?php include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'content-product.php'); ?>
		</div>
	<?php if( ($j % $item_row == 1 || $j == $list->post_count) && $item_row !=1  ){?> 
	</div>
	<?php  } $j++;?>			
<?php endwhile; wp_reset_postdata(); ?>