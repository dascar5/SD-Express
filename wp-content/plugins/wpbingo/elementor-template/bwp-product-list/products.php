<?php if ( $wp_query->have_posts() ) : ?>
		<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
			<div class="item-product <?php echo $attributes; ?>">
				<?php include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'content-product.php'); ?>
			</div>
		<?php endwhile;
endif; ?>	
