<?php
namespace ElementorWpbingo\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit;
class Bwp_Countdown_Product extends Widget_Base {
	public function get_name() {
		return 'bwp_countdown_product';
	}
	public function get_title() {
		return __( 'Wpbingo Countdown Product', 'wpbingo' );
	}
	public function get_icon() {
		return 'fa fa-address-card-o';
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
			'subtitle',
			[
				'label' => __( 'Sub Title', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your Sub Title here', 'wpbingo' ),
			]
		);
		$this->add_control(
			'description',
			[
				'label' => __( 'Description', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your Description here', 'wpbingo' ),
			]
		);
		$this->add_control(
			'class',
			[
				'label' => __( 'Extra Class', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your Extra Class here', 'wpbingo' ),
			]
		);
		$this->add_control(
			'category',
			[
				'label' => __( 'Select Categories', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $term,
				'default' => ''
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
			'item_row',
			[
				'label' => __( 'Number row per column', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => array('1' => 1, '2' => 2, '3' => 3),
				'default' => 1
			]
		);		
		$this->add_control(
			'columns',
			[
				'label' => __( 'Number of Columns >1200px', 'wpbingo' ),
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
                    'layout' => ["slider","slider2","slider3","slider4","slider5"],
                ]
			]
		);		
		$this->add_control(
			'show_pag',
			[
				'label' => __( 'Show Pagination', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1'  => __( 'Yes', 'wpbingo' ),
					'0' => __( 'No', 'wpbingo' ),
				],
				'default' => '0',
				'condition'   => [
                    'layout' => ["slider","slider2","slider3","slider4","slider5"],
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
					'slider'  	=> __( 'Slider', 'wpbingo' ),
					'slider2'  	=> __( 'Slider 2', 'wpbingo' ),
					'slider3'  	=> __( 'Slider 3', 'wpbingo' )
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
				'subtitle' => '',
				'description' => '',	
				'class' => '',	
				'orderby' => 'name',
				'order'	=> 'DESC',
				'category' => '',
				'numberposts' => 5,
				'item_row'=> 1,
				'columns' => 4,
				'columns1' => 4,
				'columns2' => 3,
				'columns3' => 2,
				'columns4' => 1,
				'show_nav'	=> '0',
				'show_pag'  => '0',		
				'layout'  => 'default',
			), $settings )
		);
		$term_name = esc_html__( 'All Categories', 'wpbingo' );
		$args = array(
			'post_type' => 'product',	
			'meta_query' => array(
				array(
					'key' => '_sale_price',
					'value' => 0,
					'compare' => '>',
					'type' => 'NUMERIC'
				),
				array(
					'key' => '_sale_price_dates_from',
					'value' => time(),
					'compare' => '<',
					'type' => 'NUMERIC'
				),
				array(
					'key' => '_sale_price_dates_to',
					'value' => 0,
					'compare' => '>',
					'type' => 'NUMERIC'
				)
			),
			'orderby' => $orderby,
			'order' => $order,
			'post_status' => 'publish',
			'showposts' => $numberposts	
		);
		if( $category != '' ){
			$term = get_term_by( 'slug', $category, 'product_cat' );
			if( $term ) :
				$term_name = $term->name;
			endif; 
			
			$args['tax_query'] = array(
				array(
					'taxonomy'  => 'product_cat',
					'field'     => 'slug',
					'terms'     => $category ));
		}
		
		$widget_id = 'bwp_countdown_'.rand().time();
		$list = new \WP_Query( $args );	
		if( $layout == 'default' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-countdown/default.php' );
		}elseif( $layout == 'slider' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-countdown/slider.php' );
		}elseif( $layout == 'slider2' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-countdown/slider2.php' );
		}elseif( $layout == 'slider3' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-countdown/slider3.php' );
		}elseif( $layout == 'slider4' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-countdown/slider4.php' );
		}elseif( $layout == 'slider5' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-countdown/slider5.php' );
		}		
	}
}