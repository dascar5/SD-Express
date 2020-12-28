<?php
namespace ElementorWpbingo\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Bwp_Image extends Widget_Base {
	public function get_name() {
		return 'bwp_image';
	}
	public function get_title() {
		return __( 'Wpbingo Image', 'wpbingo' );
	}
	public function get_icon() {
		return 'fa fa-picture-o';
	}	
	public function get_categories() {
		return [ 'general' ];
	}
	protected function _register_controls() {
		$terms = get_terms( 'product_cat', array( 'hide_empty' => false ) );
		if( count( $terms ) == 0 ){
			return ;
		}
		foreach( $terms as $cat ){
			$term[$cat->slug] = $cat -> name;
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
			'subtitle',
			[
				'label' => __( 'Sub Title', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your sub title here', 'wpbingo' ),
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
			'time_deal',
			[
				'label' => __( 'Time Coundown', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your Time Coundown here(Ex : 2019-5-25)', 'wpbingo' ),
				'condition'   => [
                    'layout' => ['banner_countdown','banner_countdown-2','product_countdown','product_countdown_2'],
                ]
			]
		);
		$this->add_control(
			'show_count',
			[
				'label' => __( 'Show Count Product Categories', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'1'  => __( '1', 'wpbingo' ),
					'0' => __( '0', 'wpbingo' ),
				],
				'condition'   => [
                    'layout' => ['category3','category4','category5'],
                ]				
			]
		);
		$this->add_control(
			'category',
			[
				'label' => __( 'Select Categories', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $term,
				'condition'   => [
                    'layout' => ['category','category2','category3','category4','category5','category6'],
                ]				
			]
		);
		$this->add_control(
			'product_id',
			[
				'label' => __( 'Product Id', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'placeholder' => __( 'Type your Product Id here', 'wpbingo' ),
				'condition'   => [
                    'layout' => ['product','product_countdown','product_countdown_2'],
                ]
			]
		);
		$this->add_control(
			'image',
			[
				'label' => __( 'Choose Image', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
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
					'default2' => __( 'Style Default2', 'wpbingo' ),
					'default3' => __( 'Style Default3', 'wpbingo' ),
					'default4' => __( 'Style Default4', 'wpbingo' ),
					'layout-1' => __( 'Style Layout 1', 'wpbingo' ),
					'layout-2' => __( 'Style Layout 2', 'wpbingo' ),
					'layout-3' => __( 'Style Layout 3', 'wpbingo' ),
					'banner_countdown' => __( 'Banner Countdown', 'wpbingo' ),
					'product_countdown' => __( 'Banner Product Countdown', 'wpbingo' ),
					'product' => __( 'Banner Product', 'wpbingo' ),
					'style2' => __( 'Style 2', 'wpbingo' ),
				],
			]
		);		
		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		extract( shortcode_atts(
			array(
				'title1' 	=> '',
				'subtitle' 	=> '',
				'description' => '',
				'link' 		=> '#',
				'label' 	=> '',
				'image' 	=> '',
				'time_deal' => '25-5-2019',
				'category' 	=> '',
				'show_count' => '0',
				'product_id'	=> '',
				'layout'  	=> 'default',
			), $settings )
		);
		$image		 = 	( $settings['image'] && $settings['image']['url'] ) ? $settings['image']['url'] : '';
		$widget_id = 'bwp_banner_image_'.rand().time();
		if( $layout == 'default' ){
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/default.php' );
		}elseif( $layout == 'default2'){
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/default2.php' );
		}elseif( $layout == 'default3'){
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/default3.php' );
		}elseif( $layout == 'default4'){
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/default4.php' );
		}elseif( $layout == 'nostyle' | $layout == 'banner_about'){
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/nostyle.php' );
		}elseif( $layout == 'banner_countdown') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/banner_countdown.php' );
		}elseif( $layout == 'banner_countdown-2') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/banner_countdown-2.php' );
		}elseif( $layout == 'style2') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/style2.php' );
		}elseif( $layout == 'style3') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/style3.php' );
		}elseif( $layout == 'style4') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/style4.php' );
		}elseif( $layout == 'banner_title') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/banner_title.php' );
		}elseif( $layout == 'category' | $layout == 'category2') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/banner_category.php' );
		}elseif( $layout == 'category3' | $layout == 'category4') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/banner_category-2.php' );
		}elseif( $layout == 'category5') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/banner_category-3.php' );
		}elseif( $layout == 'category6') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/banner_category-4.php' );
		}elseif( $layout == 'product') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/banner_product.php' );
		}elseif( $layout == 'product_countdown') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/banner_product_countdown.php' );
		}elseif( $layout == 'product_countdown_2') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/banner_product_countdown-2.php' );
		}elseif( $layout == 'layout-1' || $layout == 'layout-2' || $layout == 'layout-3') {
			include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-image/layout.php' );
		}
	}
}