<?php

//Add tab for product video at frontend
add_filter( 'woocommerce_product_tabs', 'vtfw_custom_video_tab_frontend' );

function vtfw_custom_video_tab_frontend( $tabs ) {
    
    global $post;       

    $enable_tab     = get_post_meta($post->ID, '_vtwf_enable_tab', true );
    $tab_title      = get_post_meta($post->ID, '_vtwf_tab_title', true );
 
    if( 'yes' === $enable_tab ){

        if( !empty( $tab_title ) ){

            $video_tab_title = esc_html( $tab_title );

        }else {

            $video_tab_title = __( 'Product Video', 'video-tab-for-woocommerce' );

        }
        

        $tabs['product_video'] = array(
            'title'     => esc_html( $video_tab_title ),
            'priority'  => 53,
            'callback'  => 'vtfw_custom_video_tab_content_render'
            );
        
        return $tabs;

    } else {

        return $tabs;

    }
}

function vtfw_custom_video_tab_content_render() {

    global $post, $product;

    $enable_tab     = get_post_meta($post->ID, '_vtwf_enable_tab', true);
    $tab_title      = get_post_meta($post->ID, '_vtwf_tab_title', true);
    $hide_title     = get_post_meta($post->ID, '_vtwf_hide_title', true);
    $tab_content    = get_post_meta($post->ID, '_vtwf_video_content', true);

    if( 'yes' === $enable_tab ){ 

        if( 'yes' !== $hide_title ){ 

            echo '<h2 class="woocommerce-video-title">'.esc_html( $tab_title ).'</h2>';

        }

        if( !empty($tab_content)){

            echo apply_filters('the_content', $tab_content);

        }

    }
    
}