<?php  
if( $category == '' ){
	return ;
}
$show_name = (isset($show_name) && $show_name) ? $show_name : 'true'; 
if( !is_array( $category ) ){
	$category = explode( ',', $category );
}
$widget_id = isset( $widget_id ) ? $widget_id : 'category_slide_'.rand().time(); 
?>
<div id="<?php echo $widget_id; ?>" class="bwp-woo-categories <?php echo esc_attr($layout); ?>">		
	<?php if( $title1) : ?>
		<h3 class="bwp-categories-title title-sidebar"><?php echo esc_html( $title1 ); ?></h3>
	<?php endif; ?>
	<?php if( isset($subtitle) && $subtitle) : ?>
		<div class="bwp-categories-subtitle">					
			<?php echo ($subtitle); ?>							
		</div>	
	<?php endif;?>
	<div class="content-box">		
		<ul class="content-category">
			<?php
				foreach( $category as $j => $cat ){
					$term = get_term_by('slug', $cat, 'product_cat');
					if($term) :		
						$thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
						$thumbnail_id1 = get_term_meta( $term->term_id, 'thumbnail_id1', true );
						$thumb = wp_get_attachment_url( $thumbnail_id );
						if(!$thumb)
							$thumb = wc_placeholder_img_src();
						
						$thumb1 = $thumbnail_id1;
						if(!$thumb1)
							$thumb1 = wc_placeholder_img_src();
						?>
						<li class="item-category">
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
							<div class="item-inner">
								<?php if($show_name) : ?>
								<div class="item-title">
									<a href="<?php echo get_term_link( $term->term_id, 'product_cat' ); ?>"><?php echo esc_html( $term->name ); ?></a>
								</div>
								<?php endif;?>
								<?php if(isset($show_count) && $show_count) : ?>
								<div class="item-count">
									<span><?php echo esc_attr($term->count); ?><?php echo esc_html__(' Items','wpbingo');?></span>
								</div>
								<?php endif;?>	
							</div>
						</li>	
					<?php endif; ?>		
			<?php } ?>
		</ul>
	</div>
</div>