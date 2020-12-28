<?php
namespace ElementorWpbingo\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Elementor Bwp Policy
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Bwp_Policy extends Widget_Base {
	public function get_name() {
		return 'bwp_policy';
	}
	public function get_title() {
		return __( 'Wpbingo Policy', 'wpbingo' );
	}
	public function get_icon() {
		return 'fa fa-gavel';
	}	
	public function get_categories() {
		return [ 'general' ];
	}
	protected function _register_controls() {
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
			'desc',
			[
				'label' => __( 'Description', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => '',
				'placeholder' => __( 'Type your description here', 'wpbingo' ),
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
					'layout1' => __( 'Layout 1', 'wpbingo' ),
					'layout2' => __( 'Layout 2', 'wpbingo' ),
				],
			]
		);		
		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		$title1 = ( $settings['title1'] ) ? $settings['title1'] : '';
		$desc		 = 	( $settings['desc'] ) ? $settings['desc'] : '';
		$image		 = 	( $settings['image'] && $settings['image']['url'] ) ? $settings['image']['url'] : '';
		$layout		 = 	( $settings['layout'] ) ? $settings['layout'] : 'default';
		if( $layout == 'default' || $layout == 'layout2' ){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-policy/default.php' );
		}elseif( $layout == 'layout1'){
			include( WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-policy/layout1.php' );
		}
	}
}