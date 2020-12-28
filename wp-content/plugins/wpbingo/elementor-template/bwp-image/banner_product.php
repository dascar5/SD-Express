<div class="bwp-widget-banner <?php echo esc_html( $layout ); ?>">
	<?php if( $title1) : ?>
		<div class="title-banner"><h3><?php echo esc_html( $title1 ); ?></h3></div>
	<?php endif;?>
	<div class="bg-banner">
		<?php  if($product_id):
			$product = wc_get_product( $product_id );
			$symboy = get_woocommerce_currency_symbol( get_woocommerce_currency() );
			$attributes = $product->get_attributes();			
		?>
		<div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="image">
				<a href="<?php echo get_permalink( $product_id );  ?>">
						<img src="<?php echo esc_url($image); ?>" alt=""></a>
				<?php endif;?>
				<div class="content-animation"><div></div><div></div><div></div></div>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="products-content"> 
					<h3 class="product-title"><a href="<?php echo get_permalink( $product_id );  ?>"><?php echo $product->get_title(); ?></a></h3>
					<div class="product-description"><?php echo wp_trim_words( $product->get_short_description() ); ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
