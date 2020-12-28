<?php
	$relation = 'AND';
	if(!isset($id_category))
	{
		$id_category =  is_tax() ? get_queried_object()->term_id : 0;
	}
	
	$base_url = get_permalink( wc_get_page_id( 'shop' ) );
	if(!isset($chosen_attributes)){
		$chosen_attributes = array();
		if($attribute){
			foreach($attribute as $att){
				if(isset($_GET['filter_'.$att]) && $_GET['filter_'.$att]){
					$chosen_attributes['pa_'.$att]['terms'] = explode(',',$_GET['filter_'.$att]);
					$chosen_attributes['pa_'.$att]['query_type'] = 'and';
				}
			}
		}					
		if(isset($_GET['min_price']) && $_GET['min_price']){
			$chosen_attributes['min_price'] = $_GET['min_price'];
		}
		if(isset($_GET['max_price']) && $_GET['max_price']){
			$chosen_attributes['max_price'] = $_GET['max_price'];
		}	
		
		
		if(isset($_GET['only_sale']) && $_GET['only_sale']){
			$chosen_attributes['only_sale'] = $_GET['only_sale'];
		}

		if(isset($_GET['in_stock']) && $_GET['in_stock']){
			$chosen_attributes['in_stock'] = $_GET['in_stock'];
		}
		
		if(isset($_GET['filter_brand']) && $_GET['filter_brand']){
			$chosen_attributes['pa_brand']['terms'] = explode(',',$_GET['filter_brand']);
			$chosen_attributes['pa_brand']['query_type'] = 'and';
		}			
		
	}
?>
<div  class="bwp-filter-ajax">
	<form id="bwp_form_filter_product">	
	<?php	
	//Tax Query	
	if(!isset($tax_query)){
		if($id_category != 0){
			$tax_query = array(
				array(
					'taxonomy'      => 'product_cat',
					'field' 		=> 'term_id', //This is optional, as it defaults to 'term_id'
					'terms'         => $id_category,
					'operator'      => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
				)
			);
		}
		else
			$tax_query = array();
	}	
	//Meta Query
	if(!isset($meta_query))
		$meta_query	= array();
		
	//Get Min Max Price
	if(!isset($default_min_price) || !isset($default_max_price)){
		$prices = $this->get_filtered_price($meta_query,$tax_query);
		$default_min_price    = floor( $prices->min_price );
		$default_max_price    = ceil( $prices->max_price );
	}		
		
	//Filter Only Sale
	$chosen_only_sale = false;
	if(isset($chosen_attributes['only_sale']) && $chosen_attributes['only_sale'])
		$chosen_only_sale = true;
	//Stock Product
	$chosen_in_stock = false;
	if(isset($chosen_attributes['in_stock']) && $chosen_attributes['in_stock'])
		$chosen_in_stock = true;		
	?>
	<?php if($show_only_sale || $show_in_stock) : ?>
		<div class="bwp-filter bwp-stock-status">
			<h3><?php echo esc_html__('Stock status','wpbingo'); ?></h3>
			<ul>
			<?php if($show_only_sale): ?>
			<li class="filter_only_sale">
				<span <?php echo ( $chosen_only_sale ?  'class="active"' : "" ); ?>>
					<input  id="bwp_only_sale" value="only" name="only_sale"  type="checkbox" <?php echo ( $chosen_only_sale ?  "checked" : "" ); ?>>
				</span>
				<label class="count"><?php echo esc_html__('Only Sale','wpbingo'); ?></label>
			</li>
			<?php endif; ?>
			
			<?php if($show_in_stock): ?>
			<li class="filter_in_stock">
				<span <?php echo ( $chosen_in_stock ?  'class="active"' : "" ); ?>>
					<input  id="bwp_in_stock" value="in" name="in_stock"  type="checkbox" <?php echo ( $chosen_in_stock ?  "checked" : "" ); ?>>
				</span>
				<label class="count"><?php echo esc_html__('In Stock','wpbingo'); ?></label>
			</li>	
			<?php endif;?>	
			</ul>
		</div>
	<?php endif; ?>
	
	<?php
	//Filter Price
	if($show_price)
		$this->woocommerce_filter_price($chosen_attributes,$default_min_price,$default_max_price);
	//list atribute 
	if($attribute)
		$this->woocommerce_filter_atribute($attribute,$tax_query,$meta_query,$chosen_attributes,$relation,$showcount);	
	//Filter Brand
	if($show_brand)
		$this->woocommerce_filter_brand($id_category,$tax_query,$meta_query,$chosen_attributes,$showcount);		
	?>
	</form>
</div>

