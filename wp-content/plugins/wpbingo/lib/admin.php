<?php
 /**
  * @author     Wpbingo  Team <wpbingo@gmail.com >
  * @copyright  Copyright (C) 2014 wpbingo.com. All Rights Reserved.
  * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
  * @website  http://www.wpbingo.com
  */


add_action('admin_menu', 'add_menu_admin');
function add_menu_admin(){
	add_menu_page('wpbingo', esc_html__( 'Wpbingo', 'wpbingo' ), 'manage_options', 'wpbingo','bwp_info_area_notice',plugins_url( 'wpbingo/assets/images/icon.png' ),5);
	add_submenu_page( 'wpbingo', esc_html__( 'Welcome', 'wpbingo' ), esc_html__( 'Welcome', 'wpbingo' ), 'manage_options', 'wpbingo' );
	add_submenu_page( 'wpbingo', esc_html__( 'Theme Options', 'wpbingo' ), esc_html__( 'Theme Options', 'wpbingo' ), 'manage_options', 'themes.php?page=dimita_settings');
	add_submenu_page( 'wpbingo', esc_html__( 'Footers', 'wpbingo' ), esc_html__( 'Footers', 'wpbingo' ), 'manage_options', 'edit.php?post_type=bwp_footer');
	add_submenu_page( 'wpbingo', esc_html__( 'Mega Menu', 'wpbingo' ), esc_html__( 'Mega Menu', 'wpbingo' ), 'manage_options', 'edit.php?post_type=bwp_megamenu');
	add_submenu_page( 'wpbingo', esc_html__( 'Testimonial', 'wpbingo' ), esc_html__( 'Testimonial', 'wpbingo' ), 'manage_options', 'edit.php?post_type=testimonial');
	add_submenu_page( 'wpbingo', esc_html__( 'Ourteam', 'wpbingo' ), esc_html__( 'Ourteam', 'wpbingo' ), 'manage_options', 'edit.php?post_type=ourteam');
	add_submenu_page( 'wpbingo', esc_html__( 'Slider', 'wpbingo' ), esc_html__( 'Slider', 'wpbingo' ), 'manage_options', 'edit.php?post_type=bwp_slider');
	add_submenu_page( 'wpbingo', esc_html__( 'Portfolio', 'wpbingo' ), esc_html__( 'Portfolio', 'wpbingo' ), 'manage_options', 'edit.php?post_type=portfolio');
	add_submenu_page( 'wpbingo', esc_html__( 'Categories Portfolio', 'wpbingo' ), esc_html__( 'Categories Portfolio', 'wpbingo' ), 'manage_options', 'edit-tags.php?taxonomy=category_portfolio');
}

function bwp_info_area_notice(){
	echo '<h2>'. esc_html__('Welcome to Wpbingo Farmework', 'wpbingo').'</h2>';
	echo '<p>' . esc_html__('Wpbingo is a very young team of developers and designers. Our goal is product quality and customer satisfaction, so we have gathered people who are driven by the passion to create an excellent product and be a helpful hand to their customers. If you are interested in Premium WordPress, PSD or Shopify Theme, one of our products may please you.

We love what we do and your review would be a big ‘Wpbingo’ for our development!','wpbingo'). '</p>';
}
