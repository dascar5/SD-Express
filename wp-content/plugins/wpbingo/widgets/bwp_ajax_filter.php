<?php
class bwp_ajax_filter_widget extends WP_Widget {

	function __construct(){
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'bwp_ajax_filte', 'description' => __('Allows you to filter atribute,price products', 'wpbingo') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'bwp_ajax_filte' );

		/* Create the widget. */
		parent::__construct( 'bwp_ajax_filte', __('Wpbingo Ajax Filter', 'wpbingo'), $widget_ops, $control_ops );
		add_action('admin_enqueue_scripts', array( $this, 'bwp_filter_admin_script' ) );
		add_action('woocommerce_before_shop_loop', array( $this, 'bwp_filter_title'), 45);
		/* Ajax Call*/
		add_action( 'wp_ajax_bwp_filter_products_callback', array( $this, 'bwp_filter_products_callback') );
		add_action( 'wp_ajax_nopriv_bwp_filter_products_callback', array( $this, 'bwp_filter_products_callback') );
	}
	function bwp_filter_title(){
		$chosen_attributes	 = array();
		$html = '<div class="woocommerce-filter-title"></div>';
		echo $html;
	}	
	function bwp_filter_products_callback(){
		global $wpdb;
		$dir =	WPBINGO_WIDGET_TEMPLATE_PATH.'bwp-ajax-filter/default_ajax.php';
		include $dir;
	}	

	function bwp_filter_admin_script(){
		wp_enqueue_style( 'wp-color-picker' );
	}
	/**
	 * Display the widget on the screen.
	 */
	public function widget( $args, $instance ) {
		extract($args);
		echo $before_widget;
		$title1 = apply_filters( 'widget_title', empty( $instance['title1'] ) ? '' : $instance['title1'], $instance, $this->id_base );
		if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { 
			_e('Please active woocommerce plugin or install woomcommerce plugin first!', 'wpbingo');
			return false;
		}
		extract($instance);

		if ( $tpl = $this->getTemplatePath( 'default' ) ){ 
			$link_img = plugins_url('images/', __FILE__);
			$widget_id = $args['widget_id'];		
			include $tpl;
		}
				
		/* After widget (defined by themes). */
		echo $after_widget;
	}    
	
	protected function get_current_term_slug() {
		return absint( is_tax() ? get_queried_object()->slug : 0 );
	}

	protected function get_current_term_id() {
		return absint( is_tax() ? get_queried_object()->term_id : 0 );
	}	
	
	protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type,$tax_query,$meta_query ) {
		global $wpdb;

		if ( 'or' === $query_type ) {
			foreach ( $tax_query as $key => $query ) {
				if ( $taxonomy === $query['taxonomy'] ) {
					unset( $tax_query[ $key ] );
				}
			}
		}

		$meta_query      = new WP_Meta_Query( $meta_query );
		$tax_query       = new WP_Tax_Query( $tax_query );
		$meta_query_sql  = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql   = $tax_query->get_sql( $wpdb->posts, 'ID' );

		// Generate query
		$query           = array();
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) as term_count, terms.term_id as term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];
		$query['where']   = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			" . $tax_query_sql['where'] . $meta_query_sql['where'] . "
			AND terms.term_id IN (" . implode( ',', array_map( 'absint', $term_ids ) ) . ")
		";
		$query['group_by'] = "GROUP BY terms.term_id";
		$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
		$query             = implode( ' ', $query );
		$results           = $wpdb->get_results( $query );

		return wp_list_pluck( $results, 'term_count', 'term_count_id' );
	}
	protected function get_page_base_url( $taxonomy ) {
		if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url();
		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
			$link = get_post_type_archive_link( 'product' );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} else {
			$link = get_term_link( get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
		}

		// Min/Max
		if ( isset( $_GET['min_price'] ) ) {
			$link = add_query_arg( 'min_price', wc_clean( $_GET['min_price'] ), $link );
		}

		if ( isset( $_GET['max_price'] ) ) {
			$link = add_query_arg( 'max_price', wc_clean( $_GET['max_price'] ), $link );
		}

		// Orderby
		if ( isset( $_GET['orderby'] ) ) {
			$link = add_query_arg( 'orderby', wc_clean( $_GET['orderby'] ), $link );
		}

		/**
		 * Search Arg.
		 * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
		 */
		if ( get_search_query() ) {
			$link = add_query_arg( 's', rawurlencode( htmlspecialchars_decode( get_search_query() ) ), $link );
		}

		// Post Type Arg
		if ( isset( $_GET['post_type'] ) ) {
			$link = add_query_arg( 'post_type', wc_clean( $_GET['post_type'] ), $link );
		}

		// Min Rating Arg
		if ( isset( $_GET['min_rating'] ) ) {
			$link = add_query_arg( 'min_rating', wc_clean( $_GET['min_rating'] ), $link );
		}

		// All current filters
		if ( $_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes() ) {
			foreach ( $_chosen_attributes as $name => $data ) {
				if ( $name === $taxonomy ) {
					continue;
				}
				$filter_name = sanitize_title( str_replace( 'pa_', '', $name ) );
				if ( ! empty( $data['terms'] ) ) {
					$link = add_query_arg( 'filter_' . $filter_name, implode( ',', $data['terms'] ), $link );
				}
				if ( 'or' == $data['query_type'] ) {
					$link = add_query_arg( 'query_type_' . $filter_name, 'or', $link );
				}
			}
		}

		return $link;
	}	

	protected function getTemplatePath($tpl='default', $type=''){
		$file = '/'.$tpl.$type.'.php';
		$dir = WPBINGO_WIDGET_TEMPLATE_PATH.'bwp-ajax-filter';
		if ( file_exists( $dir.$file ) ){
			return $dir.$file;
		}
		return $tpl=='default' ? false : $this->getTemplatePath('default', $type);
	}
	
	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		// strip tag on text field
		$instance['title1'] = strip_tags( $new_instance['title1'] );	
				
		if ( array_key_exists('attribute', $new_instance) ){
			if ( is_array($new_instance['attribute']) ){
				$instance['attribute'] = array_map( 'strval', $new_instance['attribute'] );
			} else {
				$instance['attribute'] = $new_instance['attribute'];
			}
		}else{
			$instance['attribute'] = '';
		}
		
		if ( array_key_exists('show_price', $new_instance) ){
			$instance['show_price'] = strip_tags( $new_instance['show_price'] );
		}				
		
		if ( array_key_exists('showcount', $new_instance) ){
			$instance['showcount'] = strip_tags( $new_instance['showcount'] );
		}	

		if ( array_key_exists('show_only_sale', $new_instance) ){
			$instance['show_only_sale'] = strip_tags( $new_instance['show_only_sale'] );
		}

		if ( array_key_exists('show_in_stock', $new_instance) ){
			$instance['show_in_stock'] = strip_tags( $new_instance['show_in_stock'] );
		}	
		if ( array_key_exists('show_brand', $new_instance) ){
			$instance['show_brand'] = strip_tags( $new_instance['show_brand'] );
		}			
        
					
        
		return $instance;
	}	

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	public function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array();
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		$default_attribute = array();
		$attribute_taxonomies = wc_get_attribute_taxonomies();	
		$count_atribute = 0 ;
		if($attribute_taxonomies){
			foreach ( $attribute_taxonomies as $value ) :
				$default_attribute[] = $value->attribute_name;
				$c_taxonomy     = wc_attribute_taxonomy_name( $value->attribute_name );
				$c_terms = get_terms( $c_taxonomy);
				$count_atribute = $count_atribute + count($c_terms);
			endforeach;
		}	
		$title1 				= isset( $instance['title1'] )    ? 	strip_tags($instance['title1']) : '';			
		$attribute				= isset( $instance['attribute'] )  && is_array($instance['attribute']) ? $instance['attribute'] : $default_attribute;
		$showcount   			= isset( $instance['showcount'] ) ? strip_tags($instance['showcount']) : 1;  
		$show_price   			= isset( $instance['show_price'] ) ? strip_tags($instance['show_price']) : 1;  	
		$show_only_sale   		= isset( $instance['show_only_sale'] ) ? strip_tags($instance['show_only_sale']) : 1;  	
		$show_in_stock   		= isset( $instance['show_in_stock'] ) ? strip_tags($instance['show_in_stock']) : 1;  	
		$show_brand   			= isset( $instance['show_brand'] ) ? strip_tags($instance['show_brand']) : 1;  	
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title1'); ?>"><?php _e('Title', 'wpbingo')?></label>
			<br />
			<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>"
				type="text"	value="<?php echo esc_attr($title1); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_price'); ?>"><?php _e("Show Filter Price", 'wpbingo')?></label>
			<br/>
			<select class="widefat"
				id="<?php echo $this->get_field_id('show_price'); ?>"	name="<?php echo $this->get_field_name('show_price'); ?>">
				<option value="1" <?php if ($show_price==1){?> selected="selected"
				<?php } ?>>
					<?php _e('Yes', 'wpbingo')?>
				</option>
				<option value="0" <?php if ($show_price==0){?> selected="selected"
				<?php } ?>>
					<?php _e('No', 'wpbingo')?>
				</option>				
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_only_sale'); ?>"><?php _e("Show Only Sale", 'wpbingo')?></label>
			<br/>
			<select class="widefat"
				id="<?php echo $this->get_field_id('show_only_sale'); ?>"	name="<?php echo $this->get_field_name('show_only_sale'); ?>">
				<option value="1" <?php if ($show_only_sale==1){?> selected="selected"
				<?php } ?>>
					<?php _e('Yes', 'wpbingo')?>
				</option>
				<option value="0" <?php if ($show_only_sale==0){?> selected="selected"
				<?php } ?>>
					<?php _e('No', 'wpbingo')?>
				</option>				
			</select>
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id('show_in_stock'); ?>"><?php _e("Show In Stock", 'wpbingo')?></label>
			<br/>
			<select class="widefat"
				id="<?php echo $this->get_field_id('show_in_stock'); ?>"	name="<?php echo $this->get_field_name('show_in_stock'); ?>">
				<option value="1" <?php if ($show_in_stock==1){?> selected="selected"
				<?php } ?>>
					<?php _e('Yes', 'wpbingo')?>
				</option>
				<option value="0" <?php if ($show_in_stock==0){?> selected="selected"
				<?php } ?>>
					<?php _e('No', 'wpbingo')?>
				</option>				
			</select>
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id('showcount'); ?>"><?php _e("Show Number Of Product", 'wpbingo')?></label>
			<br/>
			<select class="widefat"
				id="<?php echo $this->get_field_id('showcount'); ?>"	name="<?php echo $this->get_field_name('showcount'); ?>">
				<option value="1" <?php if ($showcount==1){?> selected="selected"
				<?php } ?>>
					<?php _e('Yes', 'wpbingo')?>
				</option>
				<option value="0" <?php if ($showcount==0){?> selected="selected"
				<?php } ?>>
					<?php _e('No', 'wpbingo')?>
				</option>				
			</select>
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id('show_brand'); ?>"><?php _e("Show Brand", 'wpbingo')?></label>
			<br/>
			<select class="widefat"
				id="<?php echo $this->get_field_id('show_brand'); ?>"	name="<?php echo $this->get_field_name('show_brand'); ?>">
				<option value="1" <?php if ($show_brand==1){?> selected="selected"
				<?php } ?>>
					<?php _e('Yes', 'wpbingo')?>
				</option>
				<option value="0" <?php if ($show_brand==0){?> selected="selected"
				<?php } ?>>
					<?php _e('No', 'wpbingo')?>
				</option>				
			</select>
		</p>		
		<?php if($attribute_taxonomies && $count_atribute > 0) : ?>
		<p>
			<label for="<?php echo $this->get_field_id('attribute'); ?>"><?php _e('Attribute', 'wpbingo')?></label>
			<br />
			<select class="widefat"	id="<?php echo $this->get_field_id('attribute'); ?>" name="<?php echo $this->get_field_name('attribute'); ?>[]" multiple="multiple">
				<?php
				$option ='';
				foreach ( $attribute_taxonomies as $value ) :
					$get_terms_args['menu_order'] = 'ASC';
					$taxonomy     = wc_attribute_taxonomy_name( $value -> attribute_name );
					$terms = get_terms( $taxonomy, $get_terms_args );
					if(count($terms) > 0){				
						$option .= '<option value="' . $value -> attribute_name  . '" ';
						if ( is_array( $attribute ) && in_array( $value -> attribute_name, $attribute ) ){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$value -> attribute_label.'</option>';
					}
				endforeach;
				echo $option;
				?>
			</select>
		</p>
		<?php endif;?>
		<p>
			<table>
			<?php 
				foreach( $attribute as $att ){
					if( preg_match('/'.__('color', 'wpbingo').'/', $att, $matches) ){ ?>
						<?php 
						$taxonomy     = wc_attribute_taxonomy_name( $att );
						$orderby = wc_attribute_orderby( $taxonomy );
						switch ( $orderby ) {
							case 'name' :
								$get_terms_args['orderby']    = 'name';
								$get_terms_args['menu_order'] = false;
							break;
							case 'id' :
								$get_terms_args['orderby']    = 'id';
								$get_terms_args['order']      = 'ASC';
								$get_terms_args['menu_order'] = false;
							break;
							case 'menu_order' :
								$get_terms_args['menu_order'] = 'ASC';
							break;
						}					
						?>
					<?php } 
				}
			?>
			</table>
		</p>		
	<?php
	}		
	
	function woocommerce_filter_price($chosen_attributes,$default_min_price,$default_max_price){
		$currency_symbol = get_woocommerce_currency_symbol();
		$min_price = (isset($chosen_attributes['min_price']) && $chosen_attributes['min_price']) ? $chosen_attributes['min_price'] : $default_min_price; 
		$max_price = (isset($chosen_attributes['max_price']) && $chosen_attributes['max_price']) ? $chosen_attributes['max_price'] : $default_max_price; 
		echo '
		<div class="bwp-filter-price">
		    <h3>'.esc_html__('Price','wpbingo').'</h3>
			<div class="content-filter-price">
				<div id="bwp_slider_price" data-min="'.$default_min_price.'" data-max="'.$default_max_price.'"></div>
				<div class="price-input">
					<span>'.esc_html__('Range : ','wpbingo').'</span>
					'.$currency_symbol.'<span class="input-text text-price-filter" id="text-price-filter-min-text">'.$min_price.'</span> -
					'.$currency_symbol.'<span class="input-text text-price-filter" id="text-price-filter-max-text">'.$max_price.'</span>	
					<input class="input-text text-price-filter hidden" id="price-filter-min-text" type="text" value="'.$min_price.'">
					<input class="input-text text-price-filter hidden" id="price-filter-max-text" type="text" value="'.$max_price.'">
				</div>
			</div>
		</div>';
	}
	
	protected function get_filtered_price($meta_query,$tax_query) {
		global $wpdb, $wp_the_query;
		
		$meta_query = new WP_Meta_Query( $meta_query );
		$tax_query  = new WP_Tax_Query( $tax_query );

		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );

		$sql  = "SELECT min( CAST( price_meta.meta_value AS UNSIGNED ) ) as min_price, max( CAST( price_meta.meta_value AS UNSIGNED ) ) as max_price FROM {$wpdb->posts} ";
		$sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
		$sql .= " 	WHERE {$wpdb->posts}.post_type = 'product'
					AND {$wpdb->posts}.post_status = 'publish'
					AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', apply_filters( 'woocommerce_price_filter_meta_keys', array( '_price' ) ) ) ) . "')
					AND price_meta.meta_value > '' ";
		$sql .= $tax_query_sql['where'] . $meta_query_sql['where'];

		return $wpdb->get_row( $sql );
	}	
	
	function woocommerce_filter_atribute($attribute,$tax_query,$meta_query,$chosen_attributes,$relation,$showcount){
		$query_type = $relation ;
		
		if($attribute){
		foreach( $attribute as $att ){
			$taxonomy     = wc_attribute_taxonomy_name( $att );
			$orderby = wc_attribute_orderby( $taxonomy );
			if($orderby ){
				switch ( $orderby ) {
					case 'name' :
						$get_terms_args['orderby']    = 'name';
						$get_terms_args['menu_order'] = false;
					break;
					case 'id' :
						$get_terms_args['orderby']    = 'id';
						$get_terms_args['order']      = 'ASC';
						$get_terms_args['menu_order'] = false;
					break;
					case 'menu_order' :
						$get_terms_args['menu_order'] = 'ASC';
					break;
				}
			}else{
				$get_terms_args    = array();
			}

			$get_terms_args['tax_query'] = $tax_query;
			$terms = get_terms( $taxonomy, $get_terms_args );
			$count_terms = 0;
			if (!empty($terms) && !is_wp_error($terms)){
				foreach( $terms as $term ){
					$d_term_counts      = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type ,$tax_query,$meta_query);	
					$d_count            = isset( $d_term_counts[ $term->term_id ] ) ? $d_term_counts[ $term->term_id ] : 0;
					$count_terms 		= $count_terms + $d_count;
				}
			}
			if ($count_terms > 0) {	
				if(count($terms)>0):
					$name_att = wc_attribute_label($taxonomy);	?>
				<div class="bwp-filter bwp-filter-<?php echo esc_attr($att);?>">
					<h3><?php echo ucfirst( $name_att ); ?></h3>
				<ul id="<?php echo esc_attr( 'pa_'.$att ); ?>">
					<?php
						$term_counts        = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type ,$tax_query,$meta_query);				
						foreach( $terms as $term ){
							$current_values    = isset( $chosen_attributes[ $taxonomy ]['terms'] ) ? $chosen_attributes[ $taxonomy ]['terms'] : array();
							$option_is_set     = in_array( $term->slug, $current_values );	
							$count             = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;
							if($count > 0 ){
								$tax_attribute = bwp_get_tax_attribute($taxonomy);
								$text_count		= 	$showcount ? '(' . absint( $count ) . ')' : "";
								$text_count2	= 	$showcount ? '' . absint( $count ) . '' : "";
								if(isset($tax_attribute->attribute_type) && $tax_attribute->attribute_type == "color"){
									$color = get_term_meta( $term->term_id, 'color', true );
									echo '<li class="filter_color ' . ( $option_is_set ?  "active ". esc_attr( $term -> slug ) ."" : "". esc_attr( $term -> slug ) ."" ) . '">';
											echo '	<span style="background-color:'.esc_attr($color).';"> 
														<input value="'. esc_attr( $term -> slug ) .'" name="filter_'.esc_attr( $att ).'" type="checkbox" ' . ( $option_is_set ?  "checked"  : "" ) . '>
													</span>';		
											echo apply_filters( 'woocommerce_layered_nav_count', '<label class="count">'.esc_html( $term->name ).esc_html($text_count).'</label>', $count, $term );
									echo	'</li> ';
								}elseif(isset($tax_attribute->attribute_type) && $tax_attribute->attribute_type == "image"){
									$bg_image = '';
									$id_image = get_term_meta( $term->term_id, 'image', true );
									$image_attributes = wp_get_attachment_image_src( $id_image );
									if($image_attributes){
										$bg_image = 'style="background-image:url('.esc_url($image_attributes[0]).');"';
									}
									echo '<li class="filter_image">';
											echo '	<span ' . ( $option_is_set ?  "class='active ". esc_attr( $term -> slug ) ."'" : "class='". esc_attr( $term -> slug ) ."'" ) . ' '.$bg_image.'> 
														<input value="'. esc_attr( $term -> slug ) .'" name="filter_'.esc_attr( $att ).'" type="checkbox" ' . ( $option_is_set ?  "checked"  : "" ) . '>
													</span>';		
											echo apply_filters( 'woocommerce_layered_nav_count', '<label class="count">'.esc_html( $term->name ).esc_html($text_count).'</label>', $count, $term );
									echo	'</li> ';
								}
								else{
									echo '<li>';
											echo '<span ' . ( $option_is_set ?  "class='active'" : "" ) . '>
													<input  value="'. esc_attr( $term -> slug ) .'" name="filter_'.esc_attr( $att ).'"  type="checkbox" ' . ( $option_is_set ?  "checked" : "" ) . '>
													<label class="name">'.esc_html( $term->name ).'</label>';
													echo apply_filters( 'woocommerce_layered_nav_count', '<label class="count">'.esc_html($text_count).'</label>', $count, $term );
											echo '</span>';
									echo '</li> ';
								}
							}
						}
					?>
				</ul>
				</div>
				<?php endif;
				}
			}
		}
	}
	
	function woocommerce_filter_brand($id_category,$tax_query,$meta_query,$chosen_attributes,$showcount){
		global $dimita_settings;
		$category_style = (isset($dimita_settings['category_style']) && $dimita_settings['category_style']) ? $dimita_settings['category_style'] : 'sidebar';
		$terms = get_terms( 'product_brand', 
								array( 'parent' => '', 
								'hide_empty' => 0,
								'tax_query' => array(
									array(
									'taxonomy'      => 'product_cat',
									'field' 		=> 'slug', //This is optional, as it defaults to 'term_id'
									'terms'         => $id_category,
									)
								)
						)
					);
		$current_values    = isset( $chosen_attributes[ 'pa_brand' ]['terms'] ) ? $chosen_attributes[ 'pa_brand' ]['terms'] : array();
		if($terms){	
			echo '<div class="bwp-filter bwp-filter-brand">';
			echo '<h3>'.esc_html__('Brands','wpbingo').'</h3>';
			echo '<ul id="pa_brand" class="filter_brand_product">';
			foreach ($terms as $term) {
				$option_is_set     = in_array( $term->slug, $current_values ); 		
				$this_tax_query = $tax_query;  
				$this_tax_query[] =         array(
					'taxonomy'      => 'product_brand',
					'field' 		=> 'slug', 
					'terms'         => $term->slug,
					'operator'      => 'IN'
				);  
				
				$args = array(
					'post_type'             => 'product',
					'post_status'           => 'publish',
					'ignore_sticky_posts'   => 1,
					'posts_per_page' => 1000,
					'meta_query'            => $meta_query,
					'tax_query'             => $this_tax_query
				);		
				$_list_count = new WP_Query($args);	
				$count = $_list_count->post_count;
				if($count > 0){
					echo '<li>';
							echo '<span ' . ( $option_is_set ?  'class="active"' : '' ) . '>';
								echo '<input value="'.$term->slug.'" name="filter_brand" type="checkbox" ' . ( $option_is_set ?  "checked" : "" ) . '>';
								$thumb 	= ( get_term_meta( $term->term_id, 'thumbnail_bid', true ) );
								$thubnail = !empty($thumb) ? $thumb : "http://placehold.it/200x200";
								echo '<img src="'.esc_url($thubnail).'" alt="'.esc_html($term->name).'">';
							echo '</span>';
							$text_count			= 	$showcount ? '(' . absint( $count ) . ')' : "";		
							echo apply_filters( 'woocommerce_brand_nav_count', '<label class="count">'.esc_html( $term->name ).esc_html($text_count).'</label>', $count, $term );
							
					echo '</li> ';
				}	
			}
			echo '</ul></div>';
		}		
		
	}		

}
?>