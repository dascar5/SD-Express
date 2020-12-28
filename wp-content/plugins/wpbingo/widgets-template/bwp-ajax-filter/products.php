<?php
	wc_get_template(
		'archive-product-banner.php',
		array(
			'id_category' => $id_category,
			'show_banner' => $show_banner
		)
	);
?>
<?php
	wc_get_template(
		'archive-product-bestseller.php',
		array(
			'id_category' 		=> $id_category,
			'show_bestseller'	=> $show_bestseller
		)
	);
?>
<?php if ( $wp_query->have_posts() ) : ?>
	<?php woocommerce_product_loop_start(); ?>
	<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
		<li>
		<?php wc_get_template_part( 'content-'.esc_attr($category_view_mode), 'product' ); ?> 
		</li>
	<?php endwhile; // end of the loop. ?>
	<?php woocommerce_product_loop_end(); ?>
<?php else : ?>
	<?php wc_get_template( 'loop/no-products-found.php' ); ?>
<?php endif; ?>