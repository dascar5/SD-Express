<?php
namespace ElementorWpbingo\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit;
class Bwp_Portfolio extends Widget_Base {
	public function get_name() {
		return 'bwp_portfolio';
	}
	public function get_title() {
		return __( 'Wpbingo Portfolio', 'wpbingo' );
	}
	public function get_icon() {
		return 'fa fa-table';
	}	
	public function get_categories() {
		return [ 'general' ];
	}
	protected function _register_controls() {
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
			'description',
			[
				'label' => __( 'Description', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => '',
				'placeholder' => __( 'Type your description here', 'wpbingo' ),
			]
		);
		$this->add_control(
			'portfolio_slug',
			[
				'label' => __( 'Portfolios', 'wpbingo' ),
				'multiple' => true,
				'type' => \Elementor\Controls_Manager::SELECT2,
				'default' => array(),
				'options' => self::getPortfolioCategories(),
			]
		);		
		$list_order = array('name' => 'Name', 'author' => 'Author', 'date' => 'Date', 'title' => 'Title', 'modified' => 'Modified', 'parent' => 'Parent', 'ID' => 'ID', 'rand' =>'Random', 'comment_count' => 'Comment Count');
		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $list_order
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
			'number',
			[
				'label' => __( 'Number of Portfolios', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 6,
				'description' => __( 'Type your Number of Portfolios', 'wpbingo' ),
			]
		);		
		$this->add_control(
			'col1',
			[
				'label' => __( 'Number of Columns >1200px', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $number,
				'default' => 1
			]
		);
		$this->add_control(
			'col2',
			[
				'label' => __( 'Number of Columns on 992px to 1199px', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $number,
				'default' => 1
			]
		);
		$this->add_control(
			'col3',
			[
				'label' => __( 'Number of Columns on 768px to 991px', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $number,
				'default' => 1
			]
		);
		$this->add_control(
			'col4',
			[
				'label' => __( 'Number of Columns on mobile', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $number,
				'default' => 1
			]
		);	
		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'wpbingo' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'fitrows',
				'options' => [
					'fitrows'  => __( 'FitRows', 'wpbingo' ),
					'masonry'  => __( 'Masonry', 'wpbingo' )
				],
			]
		);		
		$this->end_controls_section();
	}
	protected function render() {
		$settings = $this->get_settings_for_display();
		extract( shortcode_atts(
			array(
				'title' 		=> '',
				'description' 	=> '',
				'portfolio_slug' 	=> '',
				'orderby' 		=> '',
				'order'			=> '',
				'number' 		=> 5,
				'col1' 			=> 4,
				'col2' 			=> 4,
				'col3' 			=> 3,
				'col4' 			=> 2,						
				'style'  		=> 'fitRows',
			), $settings )
		);		
		$portfolio = array();
		if( $portfolio_slug ){
			$portfolio = $portfolio_slug;
		}
		include(WPBINGO_ELEMENTOR_TEMPLATE_PATH.'bwp-portfolio/portfolio-item.php' );		
	}
	function getPortfolioCategories(){
		$term = array();
		if(taxonomy_exists('category_portfolio')){
			$terms = get_terms('category_portfolio',array( 'orderby' => 'id' ));	
			$term = array();	
			if( count( $terms ) > 0 ){
				$term = array( 'all' =>  __( 'All Category Portfolio', "wpbingo" ) );
				foreach( $terms as $cat ){
					$term[$cat->slug] = $cat->name;
				}
			}
		}
		return $term;
	}	
}