<?php
if(!function_exists('bwp_create_type_megamenu')  ){
    function bwp_create_type_megamenu(){
		register_post_type( 'bwp_megamenu',
		array(
		  'labels' => array(
			'name' => __( 'Mega Menu','wpbingo' ),
			'singular_name' => __( 'Mega Menu','wpbingo' )
		  ),
		  'public' => true,
		  'has_archive' => true,
		  'rewrite' => array('slug' => 'megamenus'),
		  'menu_position' => 8,
		  'show_in_menu' => false,
		)
		);
		if($bwp_js_content_types = get_option('bwp_js_content_types')){
			if(!in_array('bwp_megamenu',$bwp_js_content_types)){
				$bwp_js_content_types[] = 'bwp_megamenu';
			}
			$options[] = 'bwp_megamenu';
			update_option( 'bwp_js_content_types',$bwp_js_content_types );
		}else{
			$options = array('page','bwp_megamenu');
		}
	}
	add_action( 'init','bwp_create_type_megamenu',2 );
}

