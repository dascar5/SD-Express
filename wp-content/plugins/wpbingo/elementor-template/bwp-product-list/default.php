
<?php
$widget_id = isset( $widget_id ) ? $widget_id : 'bwp_woo_slider_'.rand().time();
$class_col_lg = ($columns == 5) ? '2-4'  : (12/$columns);
$class_col_md = ($columns1 == 5) ? '2-4'  : (12/$columns1);
$class_col_sm = ($columns2 == 5) ? '2-4'  : (12/$columns2);
$class_col_xs = ($columns3 == 5) ? '2-4'  : (12/$columns3);
$attributes = 'col-xl-'.$class_col_lg .' col-lg-'.$class_col_md .' col-md-'.$class_col_sm .' col-'.$class_col_xs; 
do_action( 'before' ); 
if ( $list -> have_posts() ){ ?>
	<div id="<?php echo $widget_id; ?>" class="<?php echo $widget_class; ?> <?php echo esc_attr($layout); ?> <?php echo esc_attr($class); ?> <?php if(empty($title1)) echo 'no-title'; ?>">
		<?php if($title1 || $description || $label) { ?>
		<div class="product-list-top">
			<?php if($title1) { ?>
			<div class="title-block">
				<?php if($description) { ?>
				<div class="page-description"><?php echo esc_html($description); ?></div>
				<?php } ?>  
				<h2><?php echo esc_html($title1); ?></h2>
			</div> 
			<?php } ?>
			<?php if($label) { ?>
			<div class="btn-product-list">
				<a href="<?php echo esc_html($link); ?>"><?php echo esc_html($label);?></a>
			</div>
			<?php } ?>
		</div>
		<?php } ?>
		<div class="content products-list grid row">	
		<?php while($list->have_posts()): $list->the_post();
		global $product, $post, $wpdb, $average; ?>
			<div class="item-product <?php echo esc_attr($attributes); ?>">
				<?php include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'content-product.php'); ?>
			</div>
		<?php endwhile; wp_reset_postdata();?>
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