<?php
	function order_by_rating_post_clauses( $args ) {
		global $wpdb;

		$args['fields'] .= ", AVG( $wpdb->commentmeta.meta_value ) as average_rating ";

		$args['where'] .= " AND ( $wpdb->commentmeta.meta_key = 'rating' OR $wpdb->commentmeta.meta_key IS null ) ";

		$args['join'] .= "
			LEFT OUTER JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID)
			LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
		";

		$args['orderby'] = "average_rating DESC, $wpdb->posts.post_date DESC";

		$args['groupby'] = "$wpdb->posts.ID";

		return $args;
	}
	function bwp_timezone_offset( $countdowntime ){
		$timeOffset = 0;	
		if( get_option( 'timezone_string' ) != '' ) :
			$timezone = get_option( 'timezone_string' );
			$dateTimeZone = new DateTimeZone( $timezone );
			$dateTime = new DateTime( "now", $dateTimeZone );
			$timeOffset = $dateTimeZone->getOffset( $dateTime );
		else :
			$dateTime = get_option( 'gmt_offset' );
			$dateTime = intval( $dateTime );
			$timeOffset = $dateTime * 3600;
		endif;
		$offset =  ( $timeOffset < 0 ) ? '-' . gmdate( "H:i", abs( $timeOffset ) ) : '+' . gmdate( "H:i", $timeOffset );
		
		$date = date( 'Y/m/d H:i:s', $countdowntime );
		$date1 = new DateTime( $date );
		$cd_date =  $date1->format('Y-m-d H:i:s') . $offset;
		
		return strtotime( $cd_date ); 
	}		

	if ( ! function_exists( 'wpbingo_posted_on' ) ) :

	function wpbingo_posted_on() {
		if ( is_sticky() && is_home() && ! is_paged() ) {
			echo '<span class="featured-post">' . esc_html__( 'Sticky', 'wpbingo' ) . '</span>';
		}

		// Set up and print post meta information.
		printf( '<span class="entry-date"><time class="entry-date" datetime="%2$s">%3$s</time></span>',
			esc_url( get_permalink() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date('M d, Y') )
		);		
	}
	endif;

	if ( ! function_exists( 'wpbingo_posted_on2' ) ) :

	function wpbingo_posted_on2() {
		if ( is_sticky() && is_home() && ! is_paged() ) {
			echo '<span class="featured-post">' . esc_html__( 'Sticky', 'wpbingo' ) . '</span>';
		}

		// Set up and print post meta information.
		printf( '<span class="entry-date"><time class="entry-date" datetime="%2$s">%3$s, %4$s, %5$s, %6$s</time></span>',
			esc_url( get_permalink() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date('l')),
			esc_html( get_the_date('F j')),
			esc_html( get_the_date('Y')),
			esc_html( get_the_date('g:i a') )
		);			

	}
	endif;

	if ( ! function_exists( 'wpbingo_posted_on3' ) ) :

	function wpbingo_posted_on3() {
		if ( is_sticky() && is_home() && ! is_paged() ) {
			echo '<span class="featured-post">' . esc_html__( 'Sticky', 'wpbingo' ) . '</span>';
		}

		printf( '<span class="entry-date"><time class="entry-date" datetime="%2$s"><span class="date">%3$s %4$s %5$s</span><span class="hour">%6$s</span></time></span>',
			esc_url( get_permalink() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date('d')),
			esc_html( get_the_date('M')),
			esc_html( get_the_date('Y')),
			esc_html( get_the_date('g:i a') )
		);			

	}
	endif;
	
	if ( ! function_exists( 'wpbingo_posted_on4' ) ) :

	function wpbingo_posted_on4() {
		if ( is_sticky() && is_home() && ! is_paged() ) {
			echo '<span class="featured-post">' . esc_html__( 'Sticky', 'wpbingo' ) . '</span>';
		}

		// Set up and print post meta information.
		printf( '<span class="entry-date"><time class="entry-date" datetime="%2$s"><span class="date">%3$s</span> %4$s <span class="year">%5$s</span></span></time></span>',
			esc_url( get_permalink() ),
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date('d')),
			esc_html( get_the_date('M')),
			esc_html( get_the_date('Y')),
			esc_html( get_the_date('g:i a') )
		);			

	}
	endif;
	
	if ( ! function_exists( 'wpbingo_strip_description' ) ) :
	function wpbingo_strip_description($content, $limit = 45, $more_link = true, $more_style_block = false) {
		if (!$limit) {
			$limit = 45;
		}
		$content = wpbingo_strip_tags( apply_filters( 'the_content', $content ) );
		$content = explode(' ', $content, $limit);

		if (count($content) >= $limit) {
			array_pop($content);
			$content = implode(" ",$content).'... ';
		} else {
			$content = implode(" ",$content);
		}
		$content = '<p class="post-excerpt">'.$content.'</p>';	
		return $content;
	}
	endif;	

	if ( ! function_exists( 'wpbingo_get_excerpt' ) ) :
	function wpbingo_get_excerpt($limit = 45, $more_link = true, $more_style_block = false) {
		if (!$limit) {
			$limit = 45;
		}

		if (has_excerpt()) {
			$content = get_the_excerpt();
		} else {
			$content = get_the_content();
		}

		$content = wpbingo_strip_tags( apply_filters( 'the_content', $content ) );
		$content = explode(' ', $content, $limit);

		if (count($content) >= $limit) {
			array_pop($content);
			$content = implode(" ",$content).'... ';
		} else {
			$content = implode(" ",$content);
		}
		
		$content = '<p class="post-excerpt">'.$content.'</p>';

		if ($more_link) {
			$content .= '<div class="btn-read-more"><a class="read-more" href="'.esc_url( apply_filters( 'the_permalink', get_permalink() ) ).'">'.esc_html__('read More', 'wpbingo').'</a></div>';
		}		
	
		return $content;
	}
	endif;

	if ( ! function_exists( 'wpbingo_strip_tags' ) ) :
	function wpbingo_strip_tags( $content ) {
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = preg_replace("/<script.*?\/script>/s", "", $content) ? : $content;
		$content = preg_replace("/<style.*?\/style>/s", "", $content) ? : $content;
		$content = strip_tags( $content );
		return $content;
	}	
	endif;

	if(!function_exists('get_header_types')) :
		function get_header_types() {
			$header = array('' => esc_html__( 'Default', 'wpbingo' ));
			$path = get_template_directory().'/templates/headers/';
			$files = array_diff(scandir($path), array('..', '.'));
			if(count($files)>0){
				foreach ($files as  $file) {
					$name = str_replace( '.php', '', basename($file) );
					$value = str_replace( 'header-', '',$name);
					$name =  str_replace( '-', ' ', ucwords($name) );
					$header[$value] = $name;
				}
			}		
			return $header;
		}
	endif;

	if(!function_exists('get_footers_types')) :
		function get_footers_types() {
			$footer = array('' => esc_html__( 'Default', 'wpbingo' ));
			$footers = get_posts( array('posts_per_page'=>-1,
										'post_type'=>'bwp_footer',
										'orderby'          => 'name',
										'order'            => 'ASC'
								) );
			foreach ($footers as  $value) {
				$footer[$value->ID] = $value->post_title;
			}
			return $footer;
		}
	endif;
	
	function bwp_get_product_discount(){
		global $product;
		$discount = 0;
		if ($product->is_on_sale() && $product->is_type( 'variable' )){
			$available_variations = $product->get_available_variations();
			for ($i = 0; $i < count($available_variations); ++$i) {
				$variation_id=$available_variations[$i]['variation_id'];
				$variable_product1= new WC_Product_Variation( $variation_id );
				$regular_price = $variable_product1->get_regular_price();
				$sales_price = $variable_product1->get_sale_price();
				$percentage= round((( ( $regular_price - $sales_price ) / $regular_price ) * 100),1) ;
				if ($percentage > $discount) {
					$discount = $percentage;
				}
			}
		}elseif($product->is_on_sale() && $product->is_type( 'simple' )){
			$discount = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
		}
		return 	$discount;
	}
	function woocommerce_filter_homepage_price($default_min_price,$default_max_price){	 
		$currency_symbol = get_woocommerce_currency_symbol();
		echo '
		<div class="bwp-filter-price">
		    <h6>'.esc_html__('Choose Price').'</h6>
			<div class="bwp_slider_price" data-min="'.$default_min_price.'" data-max="'.$default_max_price.'"></div>
			<div class="price-input">
				<span>'.esc_html__('Range : ').'</span>
				'.$currency_symbol.'<span class="text-price-filter text-price-filter-min-text">'.$default_min_price.'</span> -
				'.$currency_symbol.'<span class="text-price-filter text-price-filter-max-text">'.$default_max_price.'</span>	
				<input class="price-filter-min-text hidden"  type="text" value="'.$default_min_price.'">
				<input class="price-filter-max-text hidden"  type="text" value="'.$default_max_price.'">
			</div>
		</div>';
	}	
	function woocommerce_filter_homepage_atribute(){
		$attribute_taxonomies = wc_get_attribute_taxonomies();	
		foreach( $attribute_taxonomies as $att ){
			$taxonomy   = 	wc_attribute_taxonomy_name( $att->attribute_name );
			$orderby 	=	$att->attribute_orderby;
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
			$tax_query = array();
			$get_terms_args['tax_query'] = $tax_query;
			$terms = get_terms( $taxonomy, $get_terms_args );
			if(count($terms)>0):?>
			<div class="bwp-filter-<?php echo esc_attr($att->attribute_name);?>">
				<h6><?php echo esc_html__('Choose ','wpbingo'); ?><?php echo ucfirst( $att->attribute_name ); ?></h6>
				<?php 								
					if(isset($att->attribute_type) && $att->attribute_type == "color"){?>	
						<ul class="<?php echo esc_attr( 'pa_'.$att->attribute_name ); ?>">
							<?php			
								foreach( $terms as $term ){
										$color = get_term_meta( $term->term_id, 'color', true ); 
										echo '<li data-value="'. esc_attr( $term -> slug ) .'">';
												echo '<span class="color" style="background-color:'.esc_attr($color).';"></span>';
												echo '<span>'. esc_html( $term->name ) .'</span>';
										echo '</li> ';
								} ?>
						</ul>						
					<?php }else{?>
						<ul class="<?php echo esc_attr( 'pa_'.$att->attribute_name ); ?>">
							<?php			
								foreach( $terms as $term ){
										echo '<li data-value="'. esc_attr( $term -> slug ) .'">';
												echo '<span>'. esc_html( $term->name ) .'</span>';
										echo '</li> ';
								} ?>
						</ul>
				<?php } ?>
			</div>
			<?php endif;
		}		
	}	
	function get_filtered_homepage_price($meta_query,$tax_query) {
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
	function woocommerce_filter_homepage_brand(){
		$terms = get_terms( 'product_brand', array( 'parent' => '', 'hide_empty' => 0 ) );
		if( count( $terms ) > 0 ){ ?>
			<div class="bwp-filter-brand">
				<h6><?php echo  esc_html__( 'Choose Brands', 'wpbingo' ); ?></h6>
				<ul class="filter-brand">
				<?php foreach( $terms as $term ){
					echo '<li data-value="'. esc_attr( $term -> slug ) .'">';
							echo '<span>'. esc_html( $term->name ) .'</span>';
					echo '</li> ';				
				} ?>
				</ul>
			</div>
		<?php }
	}
function bwp_display_woocommerce_attribute(){
		global $product;
		if ( $product->is_type( 'variable' ) ){
			$variations = $product->get_available_variations();
			if($variations){
				echo'<div class="product-attribute">';
				echo'<h2>'.esc_html__("color :","wpbingo").'</h2>';
				foreach($variations as $key => $variation){
					$attributes 	= $variation['attributes'] ? $variation['attributes'] : array();
					if($attributes){
						foreach($attributes as $key => $attribute){
							$key = str_replace("attribute_","",$key);
							$tax_attribute 	= bwp_get_tax_attribute($key);
							$term = get_term_by('slug', $attribute, $key);
							if(isset($tax_attribute->attribute_type) && $tax_attribute->attribute_type == "color"){
								$color = get_term_meta( $term->term_id, 'color', true );
								$image = wp_get_attachment_image_src( $variation['image_id'], 'woocommerce_thumbnail');
								if($image){
									echo'<div class="image-attribute" data-image="'.esc_attr($image[0]).'"><span style="background-color:'.esc_attr($color).';">'.esc_html($term->name).'</span></div>';						
								}
							}
						}		
					}
				}
				echo'</div>';
			}			
		}
	}
	function bwp_get_tax_attribute( $taxonomy ) {
		global $wpdb;
		$attr = substr( $taxonomy, 3 );
		$attr = $wpdb->get_row( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = '$attr'" );
		return $attr;
	}	
	