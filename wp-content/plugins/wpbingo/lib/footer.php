<?php
if(!function_exists('bwp_create_type_footer')  ){
    function bwp_create_type_footer(){
		register_post_type( 'bwp_footer',
		array(
		  'labels' => array(
			'name' => __( 'Footer','wpbingo' ),
			'singular_name' => __( 'Footer','wpbingo' )
		  ),
		  'public' => true,
		  'has_archive' => true,
		  'rewrite' => array('slug' => 'footers'),
		  'menu_position' => 8,
		  'show_in_menu' => false,
		)
		);

		if($bwp_js_content_types = get_option('bwp_js_content_types')){
			if(!in_array('bwp_footer',$bwp_js_content_types)){
			  $bwp_js_content_types[] = 'bwp_footer';
			}
			$options[] = 'bwp_footer';
			update_option( 'bwp_js_content_types',$bwp_js_content_types );
		}else{
			$options = array('page','bwp_footer');
		}
	}
	add_action( 'init','bwp_create_type_footer',2 );
}

