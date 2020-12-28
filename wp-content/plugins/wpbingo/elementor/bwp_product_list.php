<?php
namespace ElementorWpbingo\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit;
class Bwp_Product_List extends Widget_Base {
	public function get_name() {
		return 'bwp_product_list';
	}
	public function get_title() {
		return __( 'Wpbingo Product List', 'wpbingo' );
	}
	public function get_icon() {
		return 'fa fa-sliders';
	}	
	public function get_categories() {
		return [ 'general' ];
	}
	protected function _register_controls() {
		$terms = get_terms( 'product_cat', array( 'parent' => 0, 'hide_empty' => false ) );
		$term = array( '' => __( 'All Categories', "wpbingo" ) );
		foreach( $terms as $cat ){
			$term[$cat->slug] = $cat -> name;
		}	
		$number = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6);
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'wpbingo' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);		
		$this->add_control(
			'title1',
			[
				'label' => __( 'Title', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your title here', 'wpbingo' ),
			]
		);
		$this->add_control(
			'class',
			[
				'label' => __( 'Extra Class', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your Class here', 'wpbingo' ),
			]
		);		
		$this->add_control(
			'description',
			[
				'label' => __( 'Extra Description', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your Description here', 'wpbingo' ),
			]
		);
		$this->add_control(
			'label',
			[
				'label' => __( 'Button label', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your Button label here', 'wpbingo' ),
			]
		);
		$this->add_control(
			'link',
			[
				'label' => __( 'Link', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '#',
				'placeholder' => __( 'Type your Link here', 'wpbingo' ),
			]
		);		
		$this->add_control(
			'category',
			[
				'label' => __( 'Category', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $term			
			]
		);
		$this->add_control(
			'source',
			[
				'label' => __( 'Source Product', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => array( 'default' => 'Default', 'featured' => 'Featured Product ', 'sale' => 'Sale Product', 'toprating' => 'Top Rating', 'bestsales' => 'Best Sales', 'childcat' => 'Child Category')			
			]
		);
		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array('name' => 'Name', 'author' => 'Author', 'date' => 'Date', 'title' => 'Title', 'modified' => 'Modified', 'parent' => 'Parent', 'ID' => 'ID', 'rand' =>'Random', 'comment_count' => 'Comment Count')
			]
		);
		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'DESC'  => __( 'Descending', 'wpbingo' ),
					'ASC' => __( 'No', 'Ascending' ),
				],
				'default' => 'ASC'
			]
		);
		$this->add_control(
			'numberposts',
			[
				'label' => __( 'Number Of Products', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '5',
				'placeholder' => __( 'Number Of Products', 'wpbingo' ),
			]
		);	
		$this->add_control(
			'item_row',
			[
				'label' => __( 'Number row per column', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5),
				'default' => 1
			]
		);
		$this->add_control(
			'columns',
			[
				'label' => __( 'Number of Columns >1500px', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $number,
				'default' => 1
			]
		);
		$this->add_control(
			'columns1500',
			[
				'label' => __( 'Number of Columns 1200px to 1500px', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $number,
				'default' => 1
			]
		);
		$this->add_control(
			'columns1',
			[
				'label' => __( 'Number of Columns on 992px to 1199px', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $number,
				'default' => 1
			]
		);
		$this->add_control(
			'columns2',
			[
				'label' => __( 'Number of Columns on 768px to 991px', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $number,
				'default' => 1
			]
		);
		$this->add_control(
			'columns3',
			[
				'label' => __( 'Number of Columns on 480px to 767px', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $number,
				'default' => 1
			]
		);
		$this->add_control(
			'columns4',
			[
				'label' => __( 'Number of Columns in 480px or less than', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $number,
				'default' => 1
			]
		);
		$this->add_control(
			'banner',
			[
				'label' => __( 'Choose Banner', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'show_nav',
			[
				'label' => __( 'Show Navigation', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1'  => __( 'Yes', 'wpbingo' ),
					'0' => __( 'No', 'wpbingo' ),
				],
				'default' => '0',
				'condition'   => [
                    'layout' => ["slider","slider2","slider3","slider4","slider5","slider6"],
                ]				
			]
		);		
		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' 	=> __( 'Default', 'wpbingo' ),
					'default2'  => __( 'Default2', 'wpbingo' ),
					'slider'  	=> __( 'Slider', 'wpbingo' ),
					'slider2'  	=> __( 'Slider 2', 'wpbingo' ),
					'slider3'  	=> __( 'Slider 3', 'wpbingo' ),
					'slider4'  	=> __( 'Slider 4', 'wpbingo' ),
					'slider5'  	=> __( 'Slider 5', 'wpbingo' ),
					'slider6'  	=> __( 'Slider 6', 'wpbingo' ),
					'loadmore' 	=> __( 'Load More', 'wpbingo' )
				],
			]
		);		
		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		extract( shortcode_atts(
			array(
				'title1' => '',
				'description' => '',
				'label' 	=> '',
				'link' 		=> '#',
				'class' => '',				
				'orderby' => '',
				'order'	=> '',
				'category' => '',
				'numberposts' => 5,
				'length' => 25,
				'item_row'=> 1,
				'columns' => 4,
				'columns1500' => 4,
				'columns1' => 4,
				'columns2' => 3,
				'columns3' => 2,
				'columns4' => 1,
				'show_nav'	=> '0',
				'source'  => 'default',
				'banner' => '',
				'link' => '#',					
				'layout'  => 'default',
			), $settings )
		);
		$banner		 = 	( $settings['banner'] && $settings['banner']['url'] ) ? $settings['banner']['url'] : '';
		switch ($source) {
		case 'default':
			$default = array();
			if( $category){
				$default = array(
					'post_type' => 'product',
					'tax_query' => array(
					array(
						'taxonomy'  => 'product_cat',
						'field'     => 'slug',
						'terms'     => $category ) ),
					'orderby' => $orderby,
					'order' => $order,
					'post_status' => 'publish',
					'showposts' => $numberposts
				);
			}else{
				$default = array(
					'post_type' => 'product',		
					'orderby' => $orderby,
					'order' => $order,
					'post_status' => 'publish',
					'showposts' => $numberposts
				);
			}
			$widget_id = 'bwp_default_'.rand().time();
			$widget_class = 'bwp_list_default';
			$list = new \WP_Query( $default );
			break;
		case 'featured':
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();
			if( $category){
				$default = array(
					'post_type'				=> 'product',
					'post_status' 			=> 'publish',
					'tax_query'	=> array(
						array(
							'taxonomy'	=> 'product_cat',
							'field'		=> 'slug',
							'terms'		=> $category
						),
						array(
							'taxonomy' => 'product_visibility',
							'field'    => 'term_taxonomy_id',
							'terms'    => $product_visibility_term_ids['featured'],
						)						
					),
					'ignore_sticky_posts'	=> 1,
					'posts_per_page' 		=> $numberposts,
					'orderby' 				=> $orderby,
					'order' 				=> $order
				);
			}else{
				$default = array(
					'post_type'				=> 'product',
					'post_status' 			=> 'publish',
					'ignore_sticky_posts'	=> 1,
					'posts_per_page' 		=> $numberposts,
					'orderby' 				=> $orderby,
					'order' 				=> $order,
					'tax_query'	=> array(
						array(
							'taxonomy' => 'product_visibility',
							'field'    => 'term_taxonomy_id',
							'terms'    => $product_visibility_term_ids['featured'],
						)						
					)
				);
			}
			$widget_id = 'bwp_featured_'.rand().time();
			$widget_class = 'bwp_list_featured';
			$list = new \WP_Query( $default );
			break;
		case 'toprating':
			if( $category){
			$default = array(
				'post_type'		=> 'product',
				'tax_query' => array(
					array(
						'taxonomy'	=> 'product_cat',
						'field'		=> 'slug',
						'terms'		=> $category,
						'operator' 	=> 'IN'
					)
				),
				'post_status' 	=> 'publish',
				'no_found_rows' => 1,					
				'showposts' 	=> $numberposts						
			);
			}else{
				$default = array(
					'post_type'		=> 'product',		
					'post_status' 	=> 'publish',
					'no_found_rows' => 1,					
					'showposts' 	=> $numberposts						
				);
			}
			$default['meta_query'] = WC()->query->get_meta_query();
			add_filter( 'posts_clauses', 'order_by_rating_post_clauses' );
			$widget_id = 'bwp_toprated_'.rand().time();
			$widget_class = 'bwp_list_toprated';
			$list = new \WP_Query( $default );
			break;
		case 'sale':
			if( $category){
				$default = array(
					'post_type' 			=> 'product',
					'tax_query' => array(
						array(
							'taxonomy'	=> 'product_cat',
							'field'	=> 'slug',
							'terms'	=> $category,
							'operator' => 'IN'
						)
					),
					'meta_query'	=> array(
						'relation' => 'OR',
						array( // Simple products type
							'key'           => '_sale_price',
							'value'         => 0,
							'compare'       => '>',
							'type'          => 'numeric'
						),
						array( // Variable products type
							'key'           => '_min_variation_sale_price',
							'value'         => 0,
							'compare'       => '>',
							'type'          => 'numeric'
						)
					),			
					'post_status' 			=> 'publish',
					'ignore_sticky_posts'   => 1,
					'showposts'				=> $numberposts
				);
			}else{
				$default = array(
					'post_type' 			=> 'product',		
					'post_status' 			=> 'publish',
					'ignore_sticky_posts'   => 1,
					'showposts'				=> $numberposts,
					'meta_query'	=> array(
						'relation' => 'OR',
						array( // Simple products type
							'key'           => '_sale_price',
							'value'         => 0,
							'compare'       => '>',
							'type'          => 'numeric'
						),
						array( // Variable products type
							'key'           => '_min_variation_sale_price',
							'value'         => 0,
							'compare'       => '>',
							'type'          => 'numeric'
						)
					)
				);
			}
			$widget_id = 'bwp_sale_product_'.rand().time();
			$widget_class = 'bwp_sale_product';
			$list = new \WP_Query( $default );
			break;	
		case 'bestsales':
			if( $category){
				$default = array(
					'post_type' 			=> 'product',
					'tax_query' => array(
						array(
							'taxonomy'	=> 'product_cat',
							'field'	=> 'slug',
							'terms'	=> $category,
							'operator' => 'IN'
						)
					),
					'post_status' 			=> 'publish',
					'ignore_sticky_posts'   => 1,
					'paged'	=> 1,
					'showposts'				=> $numberposts,
					'meta_key' 		 		=> 'total_sales',
					'orderby' 		 		=> 'meta_value_num'
				);
			}else{
				$default = array(
					'post_type' 			=> 'product',		
					'post_status' 			=> 'publish',
					'ignore_sticky_posts'   => 1,
					'showposts'				=> $numberposts,
					'meta_key' 		 		=> 'total_sales',
					'orderby' 		 		=> 'meta_value_num'
				);
			}
			$widget_id = 'bwp_bestsales_'.rand().time();
			$widget_class = 'bwp_list_bestsales';
			$list = new \WP_Query( $default );
			break;
			case 'childcat':
			$default = array();
			$default = array(
				'post_type' => 'product',
				'tax_query' => array(
				array(
					'taxonomy'  => 'product_cat',
					'field'     => 'slug',
					'terms'     => $category ) ),
				'orderby' => $orderby,
				'order' => $order,
				'post_status' => 'publish',
				'showposts' => $numberposts
			);
			$term = get_term_by( 'slug', $category, 'product_cat' );
			$widget_id = 'bwp_childcat_'.rand().time();	
			$list = new \WP_Query( $default );				
			break;
		}
		if( $layout == 'default' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-list/default.php' );				
		}elseif( $layout == 'default2' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-list/default_2.php' );			
		}elseif( $layout == 'slider' || $layout == 'slider4' || $layout == 'slider6' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-list/slider.php' );			
		}elseif( $layout == 'slider2' || $layout == 'slider5'){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-list/slider2.php' );			
		}elseif( $layout == 'slider3' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-list/slider3.php' );
		}elseif( $layout == 'loadmore' ){
			$args_count 	= 	$default;	
			$args_count['showposts'] 	= 	-1;
			$wp_query_count = new \WP_Query($args_count);	
			$total = $wp_query_count->post_count;
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-list/loadmore.php' );
		}	
	}
}