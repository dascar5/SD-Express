<div class="bwp-breadcrumb">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__('Home', 'wpbingo'); ?></a><span class="delimiter"></span>
	<?php if ($id_category != 0 ): ?>
		<a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php echo apply_filters( 'woocommerce_page_title', esc_html__('Shop', 'wpbingo') ); ?></a><span class="delimiter"></span>			
	<?php endif; ?>
	<span class="current"><?php echo woocommerce_page_title(); ?></span>
</div>