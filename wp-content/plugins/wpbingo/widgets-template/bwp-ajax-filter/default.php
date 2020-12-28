<?php 
if( ((function_exists('is_shop') && is_shop()) ||  (function_exists('is_product_category')  && is_product_category()))  && !is_tax( 'product_brand' )) :
global $wp_query,$wp;
$widget_id			= 'bwp_filter_ajax'.rand().time();
$array_value_url 	= $_GET ? (base64_encode(serialize($_GET))) : "";
$show_bestseller	= dimita_get_config('show-bestseller-category',false);
$show_banner		= dimita_get_config('show-banner-category',false);
?>
<div id="<?php echo esc_attr($widget_id); ?>" class="bwp-woocommerce-filter-product">
<?php if ($title1 != '') :?>
	<div class="bwp-block-title">
		<!-- Title -->
		<?php if ($title1 != '') { ?>
			<h2><?php echo $title1; ?></h2>
		<?php } ?>
	</div>
<?php endif; ?>
<?php require(WPBINGO_WIDGET_TEMPLATE_PATH.'bwp-ajax-filter/filter.php'); ?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function( $ ) {
		$("#<?php echo $widget_id; ?>").binFilterProduct( {
			widget_id : $("#<?php echo esc_js($widget_id); ?>"),
			id_category:<?php echo  esc_js( $id_category); ?>,
			show_bestseller:<?php echo  esc_js( $show_bestseller); ?>,
			show_banner:<?php echo  esc_js( $show_banner); ?>,
			base_url: "<?php echo  esc_js( $base_url); ?>",
			attribute:"<?php echo  (isset($attribute) && $attribute) ? esc_js( implode(',',$attribute)) : ""; ?>",
			showcount:<?php echo  esc_js( $showcount); ?>,
			show_price:<?php echo  esc_js( $show_price); ?>,
			relation:"<?php echo  esc_js( $relation); ?>",
			show_only_sale:<?php echo  esc_js( $show_only_sale); ?>,
			show_in_stock:<?php echo  esc_js( $show_in_stock); ?>,
			show_brand:<?php echo  esc_js( $show_brand); ?>,
			array_value_url :	"<?php echo  esc_js( $array_value_url); ?>"
		});
	});
</script>
<?php endif; ?>