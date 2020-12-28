<?php
namespace ElementorWpbingo\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;
class Bwp_Product_Categories extends Widget_Base {
	public function get_name() {
		return 'bwp_product_categories';
	}
	public function get_title() {
		return __( 'Wpbingo Product Categories', 'wpbingo' );
	}
	public function get_icon() {
		return 'fa fa-users';
	}	
	public function get_categories() {
		return [ 'general' ];
	}
	protected function _register_controls() {
		$terms = get_terms( 'product_cat', array( 'hide_empty' => false ) );
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
				'label' => __( 'Subtitle', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your Extra Subtitle here', 'wpbingo' ),
			]
		);		
		$this->add_control(
			'category',
			[
				'label' => __( 'Categories', 'wpbingo' ),
				'multiple' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => array(),
				'options' => $term,
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
			'show_name',
			[
				'label' => __( 'Show Name Categories', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1'  => __( 'Yes', 'wpbingo' ),
					'0' => __( 'No', 'wpbingo' ),
				],
				'default' => '0'
			]
		);
		$this->add_control(
			'show_count',
			[
				'label' => __( 'Show Count Product Categories', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1'  => __( 'Yes', 'wpbingo' ),
					'0' => __( 'No', 'wpbingo' ),
				],
				'default' => '0'
			]
		);
		$this->add_control(
			'show_thumbnail',
			[
				'label' => __( 'Show Image Categories', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1'  => __( 'Yes', 'wpbingo' ),
					'0' => __( 'No', 'wpbingo' ),
				],
				'default' => '0'
			]
		);
		$this->add_control(
			'show_icon',
			[
				'label' => __( 'Show Icon Categories', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1'  => __( 'Yes', 'wpbingo' ),
					'0' => __( 'No', 'wpbingo' ),
				],
				'default' => '0'
			]
		);
		$this->add_control(
			'show_thumbnail1',
			[
				'label' => __( 'Show Thumbnail Categories', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1'  => __( 'Yes', 'wpbingo' ),
					'0' => __( 'No', 'wpbingo' ),
				],
				'default' => '0'
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
                    'layout' => ["slider", "slider1" ,"slider2","slider3"],
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
					'default' => __( 'Default', 'wpbingo' ),
					'list'	  => __( 'List', 'wpbingo' ),
					'slider'  => __( 'Slider', 'wpbingo' ),
					'slider1'  => __( 'Slider 1', 'wpbingo' ),
					'slider2'  => __( 'Slider 2', 'wpbingo' ),
					'slider3'  => __( 'Slider 3', 'wpbingo' )
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
				'orderby' => '',
				'category' => '',
				'item_row' => 1,
				'numberposts' => 5,
				'columns' => 4,
				'columns1' => 4,
				'columns2' => 3,
				'columns3' => 2,
				'columns4' => 1,
				'show_name' => 1,
				'show_count' => 1,
				'show_thumbnail' => 1,
				'show_thumbnail1' => 1,
				'show_icon' => 0,
				'show_nav'	=> '0',
				'layout'  => 'default',
			), $settings )
		);
		if( $layout == 'default' || $layout == 'layout2' || $layout == 'layout3' || $layout == 'layout4'){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-categories/default.php' );	
		}elseif( $layout == 'slider' || $layout == 'slider3' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-categories/slider.php' );
		}elseif( $layout == 'slider1' || $layout == 'slider2' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-categories/slider1.php' );
		}elseif( $layout == 'list' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-product-categories/list.php' );
		}
	}
}
