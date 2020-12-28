
<?php
$widget_id = isset( $widget_id ) ? $widget_id : 'bwp_woo_slider_'.rand().time();

$count = $list->post_count;
$j = 1;
do_action( 'before' ); 
if ( $list -> have_posts() ){ ?>
	<div id="<?php echo $widget_id; ?>" class="bwp_list_default woo-slider-2 <?php echo esc_attr($layout); ?> <?php if(empty($title1)) echo 'no-title'; ?>">
		<?php if($title1) { ?>
		<div class="title-block">	  
			<h2><?php echo esc_html($title1); ?></h2>
		</div> 
		<?php } ?>
		<?php if($label) { ?>
		<div class="btn-product-list">
			<a href="<?php echo esc_html($link); ?>"><?php echo esc_html($label);?></a>
		</div>
		<?php } ?>
		<div class="content-product-list">	
			<div class="slider products-list grid slick-carousel" data-nav="<?php echo esc_attr($show_nav);?>" data-columns4="<?php echo $columns4; ?>" data-columns3="<?php echo $columns3; ?>" data-columns2="<?php echo $columns2; ?>" data-columns1="<?php echo $columns1; ?>" data-columns1500="<?php echo $columns1500; ?>" data-columns="<?php echo $columns; ?>">	
			<?php while($list->have_posts()): $list->the_post();global $product, $post, $wpdb, $average; ?>
				<?php	if( ($j == 1) ||  ( $j % $item_row  == 1 ) || ( $item_row == 1 )) { ?>
					<div class="item-product">
				<?php } ?>	
					<?php include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'content-product.php'); ?>
				<?php if( ($j == $count) || ($j % $item_row == 0) || ($item_row == 1)){?> 
					</div><!-- #post-## -->
				<?php  } $j++;?>
					
			<?php endwhile; wp_reset_postdata();?>
			</div>
		</div>
		<?php if(!empty($banner)) { ?>
		<div class="banner-block"> 
			<img src="<?php echo esc_url($banner)?>" alt="banner-image">
		</div> 
		<?php } ?>	
	</div>
	<?php
	}
?>