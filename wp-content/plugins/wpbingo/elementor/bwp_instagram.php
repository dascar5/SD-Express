<?php
namespace ElementorWpbingo\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit;
class Bwp_Instagram extends Widget_Base {
	public function get_name() {
		return 'bwp_instagram';
	}
	public function get_title() {
		return __( 'Wpbingo Instagram', 'wpbingo' );
	}
	public function get_icon() {
		return 'fa fa-instagram';
	}	
	public function get_instagram() {
		return [ 'general' ];
	}
	protected function _register_controls() {
		$number = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6,'7' => 7, '8' => 8, '9' => 9, '10' => 10, '11' => 11, '12' => 12);
        $this->start_controls_section('content_tab_settings',
            [
                'label' => esc_html__('Content Instagram', 'wpbingo'),
            ]
        );
		$this->add_control(
			'image_slider',
			[		
				'label' => __( 'Image Instagram', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
		$this->add_control(
			'link_instagram',
			[
				'label' => __( 'Link Image Instagram', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '#',
				'placeholder' => __( 'Type your link image instagram here', 'wpbingo' ),
			]
		);		
        $this->add_control('list_tab',
            [
                'label'  => esc_html__('List Instagram', 'wpbingo'),
                'type'   => \Elementor\Controls_Manager::REPEATER,
                'fields' => array_values($this->get_controls()),
            ]
        );
		$this->end_controls_section();
		
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Garenal', 'wpbingo' ),
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
				'placeholder' => __( 'Type your description here', 'wpbingo' ),
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
					'true'  => __( 'Yes', 'wpbingo' ),
					'false' => __( 'No', 'wpbingo' ),
				],
				'default' => 'false'
			]
		);
		$this->add_control(
			'show_pag',
			[
				'label' => __( 'Show Pagination', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'true'  => __( 'Yes', 'wpbingo' ),
					'false' => __( 'No', 'wpbingo' ),
				],
				'default' => 'false'
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
					'slider'  => __( 'Slider', 'wpbingo' ),
					'slider2'  => __( 'Slider 2', 'wpbingo' )
				],
			]
		);		
		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		$title1 = ( $settings['title1'] ) ? $settings['title1'] : '';
		$subtitle = ( $settings['subtitle'] ) ? $settings['subtitle'] : '';
		$description = ( $settings['description'] ) ? $settings['description'] : '';
		$link_instagram = ( $settings['link_instagram'] ) ? $settings['link_instagram'] : '';
		$item_row	= 	( $settings['item_row'] ) ? $settings['item_row'] : 1;
		$columns	= 	( $settings['columns'] ) ? $settings['columns'] : 1;
		$columns1	= 	( $settings['columns1'] ) ? $settings['columns1'] : 1;
		$columns2	= 	( $settings['columns2'] ) ? $settings['columns2'] : 1;
		$columns3	= 	( $settings['columns3'] ) ? $settings['columns3'] : 1;
		$columns4	= 	( $settings['columns4'] ) ? $settings['columns4'] : 1;
		$show_nav	= 	( $settings['show_nav'] ) ? $settings['show_nav'] : 'false';
		$show_pag	= 	( $settings['show_pag'] ) ? $settings['show_pag'] : 'false';
		$layout		= 	( $settings['layout'] ) ? $settings['layout'] : 'default';
		if( $layout == 'slider' || $layout == 'slider2' ){
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-instagram/slider.php' );
		}else{
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-instagram/default.php' );
		}
	}
}