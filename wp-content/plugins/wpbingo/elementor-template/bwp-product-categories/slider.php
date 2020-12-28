<?php  
if( $category == '' ){
	return ;
}

$item_row = (isset($item_row) && $item_row) ? $item_row : 1;
$show_name = (isset($show_name) && $show_name) ? $show_name : 'true'; 
if( !is_array( $category ) ){
	$category = explode( ',', $category );
}
$widget_id = isset( $widget_id ) ? $widget_id : 'category_slide_'.rand().time(); 
?>
<div id="<?php echo $widget_id; ?>" class="bwp-woo-categories <?php echo esc_attr($layout); ?>">
		<?php if( isset($subtitle) && $subtitle) : ?>
			<div class="bwp-categories-subtitle">					
				<?php echo ($subtitle); ?>							
			</div>	
		<?php endif;?>
		<?php if( $title1) : ?>
			<h3 class="bwp-categories-title"><?php echo esc_html( $title1 ); ?></h3>
		<?php endif; ?>
		<div class="content-category slick-carousel" data-nav="<?php echo esc_attr($show_nav);?>" data-columns4="<?php echo $columns4; ?>" data-columns3="<?php echo $columns3; ?>" data-columns2="<?php echo $columns2; ?>" data-columns1="<?php echo $columns1; ?>" data-columns="<?php echo $columns; ?>">
			<?php
				foreach( $category as $j => $cat ){
					$term = get_term_by('slug', $cat, 'product_cat');
					if($term) :		
						$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
						$thumbnail_id1 = get_term_meta( $term->term_id, 'thumbnail_id1', true );
						$icon_category = get_term_meta( $term->term_id, 'category_icon', true );
						$thumb = wp_get_attachment_url( $thumbnail_id );
						if(!$thumb)
							$thumb = wc_placeholder_img_src();
						
						$thumb1 = $thumbnail_id1;
						if(!$thumb1)
							$thumb1 = wc_placeholder_img_src();
						?>
						<?php	if( ( $j % $item_row ) == 0 ) { ?>
							<div class="item item-product-cat">	
						<?php } ?>
							<div class="item-product-cat-content">
								<?php if(isset($show_thumbnail) && $show_thumbnail) : ?>
								<div class="item-image">
									<?php if($thumb) : ?>
										<img src="<?php echo esc_url($thumb); ?>" alt="<?php echo $term->slug ;?>" />
									<?php endif ; ?>
								</div>
								<?php endif;?>
								<?php if(isset($show_thumbnail1) && $show_thumbnail1) : ?>
									<div class="item-thumbnail">
										<img src="<?php echo esc_url($thumb1); ?>" alt="<?php echo $term->slug ;?>" />
									</div>
								<?php endif;?>							
								<?php if(isset($show_icon) && $show_icon) : ?>
									<?php if(isset($icon_category) && $icon_category) : ?>
										<div class="item-icon">
											<i class="<?php echo esc_attr($icon_category); ?>"></i>
										</div>
									<?php endif;?>
								<?php endif;?>						
								<?php if($show_name) : ?>
								<div class="item-title">
									<a href="<?php echo get_term_link( $term->term_id, 'product_cat' ); ?>"><?php echo esc_html( $term->name ); ?></a>
								</div>
								<?php endif;?>
								<?php if(isset($show_count) && $show_count) : ?>
								<div class="item-count">
									<?php if($term->count == 1){?>
										<?php echo esc_attr($term->count) .'<span>'. esc_html__(' Item', 'wpbingo').'</span>'; ?>
									<?php }else{ ?>
										<?php echo esc_attr($term->count) .'<span>'. esc_html__(' Items', 'wpbingo').'</span>'; ?>
									<?php } ?>
								</div>
								<?php endif;?>
							</div>
						<?php if( ( $j+1 ) % $item_row == 0 || ( $j+1 ) == count($category) ){?> 
							</div>
						<?php  } ?>
					<?php endif; ?>		
			<?php } ?>
		</div>
	</div>