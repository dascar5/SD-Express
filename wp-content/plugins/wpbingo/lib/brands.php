<?php
/**
 * Wpbingo Woo Tab Slider Widget
 * Plugin URI: http://www.wpbingo.com
 * Version: 1.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; 
}

class bwp_brands_WC_Admin_Taxonomies {


	public function __construct() {

		add_action( 'woocommerce_register_taxonomy', array( $this, 'create_taxonomies' ) );
		add_action( "delete_term", array( $this, 'delete_term' ), 5 );

		add_action( 'product_brand_add_form_fields', array( $this, 'add_brands_fields' ) );
		add_action( 'product_brand_edit_form_fields', array( $this, 'edit_brands_fields' ), 10, 2 );
		add_action( 'created_term', array( $this, 'save_brands_fields' ), 10, 3 );
		add_action( 'edit_term', array( $this, 'save_brands_fields' ), 10, 3 );
	}

	public function bwp_woocommerce_brands_metabox( $post ) {
		$taxonomy = 'product_brand';	 
		$tax = get_taxonomy($taxonomy);
		$terms = get_terms($taxonomy,array('hide_empty' => 0));	 
		$name = 'tax_input[' . $taxonomy . ']';
		$popular = get_terms( $taxonomy, array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 10, 'hierarchical' => false ) );
		$postterms = get_the_terms( $post->ID,$taxonomy );
		$current = ($postterms ? array_pop($postterms) : false);
		$current = ($current ? $current->term_id : 0);
		?>
	 
		<div id="taxonomy-<?php echo $taxonomy; ?>" class="bwp_brands">
	 
			<ul id="<?php echo $taxonomy; ?>-tabs" class="brands-tabs">
				<li class="tabs"><a href="#<?php echo $taxonomy; ?>-all" tabindex="3"><?php echo $tax->labels->all_items; ?></a></li>
				<li class="hide-if-no-js"><a href="#<?php echo $taxonomy; ?>-pop" tabindex="3"><?php _e( 'Most Used','wpbingo' ); ?></a></li>
			</ul>
	 
			<div id="<?php echo $taxonomy; ?>-all" class="tabs-panel">
				<ul id="<?php echo $taxonomy; ?>checklist" class="list:<?php echo $taxonomy?> brandschecklist form-no-clear">
					<?php   foreach($terms as $term){
						$id = $taxonomy.'-'.$term->term_id;
						echo "<li id='$id'><label class='selectit'>";
						echo "<input type='radio' id='in-$id' name='{$name}'".checked($current,$term->term_id,false)."value='$term->term_id' />$term->name<br />";
					   echo "</label></li>";
					}?>
			   </ul>
			</div>
	 
			<div id="<?php echo $taxonomy; ?>-pop" class="tabs-panel" style="display: none;">
				<ul id="<?php echo $taxonomy; ?>checklist-pop" class="brandschecklist form-no-clear" >
					<?php   foreach($popular as $term){
						$id = 'popular-'.$taxonomy.'-'.$term->term_id;
						echo "<li id='$id'><label class='selectit'>";
						echo "<input type='radio' id='in-$id'".checked($current,$term->term_id,false)."value='$term->term_id' />$term->name<br />";
						echo "</label></li>";
					}?>
			   </ul>
		   </div>
	 
		</div>
		<?php
	}

	public function create_taxonomies() {
		$shop_page_id = wc_get_page_id( 'shop' );

		$base_slug = $shop_page_id > 0 && get_page( $shop_page_id ) ? get_page_uri( $shop_page_id ) : 'shop';

		$brands_base = get_option('woocommerce_prepend_shop_page_to_urls') == "yes" ? trailingslashit( $base_slug ) : '';

		$cap = version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ? 'manage_woocommerce_products' : 'edit_products';		
		$labels = array(
			'name'              => __( 'Brands', 'wpbingo' ),
			'singular_name'     => __( 'Brands', 'wpbingo' ),
			'search_items'      => __( 'Search Genres', 'wpbingo' ),
			'all_items'         => __( 'All Brands', 'wpbingo' ),
			'parent_item'       => __( 'Parent Brands', 'wpbingo'),
			'parent_item_colon' => __( 'Parent Brands:', 'wpbingo' ),
			'edit_item'         => __( 'Edit Brands', 'wpbingo'),
			'update_item'       => __( 'Update Brands', 'wpbingo'),
			'add_new_item'      => __( 'Add New Brands', 'wpbingo'),
			'new_item_name'     => __( 'New Brands Name', 'wpbingo'),
			'menu_name'         => 'Brand',
		);
	
		$args = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui' 				=> true,
			'show_in_nav_menus' 	=> true,
			'capabilities'			=> array(
				'manage_terms' 		=> $cap,
				'edit_terms' 		=> $cap,
				'delete_terms' 		=> $cap,
				'assign_terms' 		=> $cap
			),
			'rewrite' 				=> array( 'slug' => $brands_base . __( 'brand', 'wpbingo' ), 'with_front' => false, 'hierarchical' => true )
		);
		register_taxonomy( 'product_brand', array('product'), apply_filters( 'register_taxonomy_product_brand',$args ));	
	}  

	public function delete_term( $term_id ) {

		$term_id = (int) $term_id;

		if ( ! $term_id )
			return;

		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->woocommerce_termmeta} WHERE `woocommerce_term_id` = " . $term_id );
	}
	public function add_brands_fields() {
			$image="";
		?>
		<div class="">
			<label for="display_type"><?php _e( 'Featured', 'wpbingo' ); ?></label>
            <input type="checkbox" name="featured" />
		</div>
		<?php
	}

	public function edit_brands_fields( $term, $taxonomy ) {
		$display_type	= get_term_meta( $term->term_id, 'featured', true );
		$image 			= '';
		$thumbnail_id 	= absint( get_term_meta( $term->term_id, 'thumbnail_id', true ) );
		if ( $thumbnail_id )
			$image = wp_get_attachment_thumb_url( $thumbnail_id );
		else
		{
			$image = wc_placeholder_img_src();	
		}
		?>
		<tr class="">
			<th scope="row" valign="top"><label><?php _e( 'Featured', 'wpbingo' ); ?></label></th>
			<td>
	  			 <input type="checkbox" name="featured" <?php checked( $display_type, 1 ); ?>/>
			</td>
		</tr>
		<?php
	}


	public function save_brands_fields( $term_id, $tt_id, $taxonomy ) {
		if ( isset( $_POST['featured'] ) ){

			update_term_meta( $term_id, 'featured', 1);
		}
		else{	
			update_term_meta( $term_id, 'featured', 0);
		}
		delete_transient( 'wc_term_counts' );
	}
}
new bwp_brands_WC_Admin_Taxonomies();
?>