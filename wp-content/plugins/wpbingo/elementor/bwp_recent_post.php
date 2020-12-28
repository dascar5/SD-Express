<?php
namespace ElementorWpbingo\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Bwp_Recent_Post extends Widget_Base {
	public function get_name() {
		return 'bwp_recent_post';
	}
	public function get_title() {
		return __( 'Wpbingo Recent Post', 'wpbingo' );
	}
	public function get_icon() {
		return 'fa fa-pencil-square-o';
	}	
	public function get_categories() {
		return [ 'general' ];
	}
	protected function _register_controls() {
		$number = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6);
		$categories = get_categories();
		$terms = array();
		if($categories){
			$terms = array( '' => __( 'All Categories', "wpbingo" ) );
			foreach ( $categories as $category ) {
				$terms[$category->slug] = $category->name;
			}			
		}
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
			'description',
			[
				'label' => __( 'Description', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your Description here', 'wpbingo' ),
			]
		);		
		$this->add_control(
			'category',
			[
				'label' => __( 'Category', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $terms,
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
				'label' => __( 'Go to news', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '#',
				'placeholder' => __( 'Type your link Go to news here', 'wpbingo' ),
			]
		);
		$this->add_control(
			'limit',
			[
				'label' => __( 'Number of Posts', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 6,
				'description' => __( 'Type your Number of Posts', 'wpbingo' ),
			]
		);
		$this->add_control(
			'length',
			[
				'label' => __( 'Excerpt length (in words)', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 25,
				'description' => __( 'Type your Excerpt length (in words)', 'wpbingo' ),
			]
		);
		$this->add_control(
			'item_row',
			[
				'label' => __( 'Number row of Posts', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your Number row of Testimonial here', 'wpbingo' ),
				'default' => 1,
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
				'default' => '0'
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
				'default' => '0'
			]
		);		
		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'  => __( 'Default', 'wpbingo' ),
					'sidebar'  => __( 'Sidebar', 'wpbingo' ),
					'sidebar2'  => __( 'Sidebar2', 'wpbingo' ),
					'slider'  => __( 'Slider', 'wpbingo' ),
					'slider2'  => __( 'Slider 2', 'wpbingo' ),
					'slider3'  => __( 'Slider 3', 'wpbingo' ),
					'slider4'  => __( 'Slider 4', 'wpbingo' ),
					'slider5'  => __( 'Slider 5', 'wpbingo' ),
				],
			]
		);		
		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
			extract( shortcode_atts(
				array(
					'number'   => '',
					'style'   => '',
					'title1'   => '',
					'description'   => '',
					'category' => '',
					'limit'	   => 8,
					'length'	=> 25,
					'label' => '',
					'link' => '#',
					'item_row' => 1,	
					'columns'  => 3,
					'columns1' => 3,
					'columns2' => 3,
					'columns3' => 1,
					'columns4' => 1,
					'show_nav'  => '1',
					'show_pag'  => '1',					
					'layout'   => 'default',
				), $settings )
			);
		$tag_id = 'recent_post_' .rand().time();
		
		if( $layout == 'default' ) {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-recent-post/default.php' );
		}elseif( $layout == 'sidebar' || $layout == 'slider4' ) {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-recent-post/sidebar.php' );
		}elseif( $layout == 'slider') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-recent-post/slider.php' );
		}elseif( $layout == 'slider2') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-recent-post/slider2.php' );
		}elseif( $layout == 'slider3') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-recent-post/slider3.php' );
		}elseif( $layout == 'slider5') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-recent-post/slider4.php' );
		}elseif( $layout == 'sidebar2') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-recent-post/sidebar-2.php' );
		}
	}
}