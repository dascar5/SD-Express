<?php

// Register the video tab by hooking into the 'woocommerce_product_data_tabs' filter
add_filter( 'woocommerce_product_data_tabs', 'vtfw_add_product_video_tab' );

if( !function_exists( 'vtfw_add_product_video_tab' ) ){

    function vtfw_add_product_video_tab( $product_data_tabs ) {

        $product_data_tabs['vtfw-video-tab'] = array(
            'label'     => __( 'Product Video', 'video-tab-for-woocommerce' ),
            'target'    => 'vtfw_custom_video_tab_data',
        );

        return $product_data_tabs;
    }

}

// Call outputs like text boxes, select boxes, etc.
add_action('woocommerce_product_data_panels', 'vtfw_custom_video_tab_data_fields');

function vtfw_custom_video_tab_data_fields() { ?> 
    <div id = 'vtfw_custom_video_tab_data' class = 'panel woocommerce_options_panel'> 
        <div class = 'options_group' > 
            <?php

            woocommerce_wp_checkbox(
              array(
                'id'                => '_vtwf_enable_tab',
                'label'             => __('Enable Tab', 'video-tab-for-woocommerce' ),
                 'desc_tip'        => 'true',
                'description'       => __( 'Check this option to display video tab', 'video-tab-for-woocommerce' )
              )
            );
              
            woocommerce_wp_text_input(
                array(
                  'id'              => '_vtwf_tab_title',
                  'label'           => __( 'Tab Title', 'video-tab-for-woocommerce' ),
                  'placeholder'     => __( 'Enter title for the tab', 'video-tab-for-woocommerce' ),
                  'desc_tip'        => 'true',
                  'description'     => __( 'Text used here will be used for title of the tab', 'video-tab-for-woocommerce' )
                )
            );

            woocommerce_wp_checkbox(
              array(
                'id'                => '_vtwf_hide_title',
                'label'             => __('Hide Title', 'video-tab-for-woocommerce' ),
                'description'       => __( 'Check this option to hide title in content area of the tab', 'video-tab-for-woocommerce' )
              )
            );

            woocommerce_wp_textarea_input(
                array(
                  'id'              => '_vtwf_video_content',
                  'label'           => __( 'Video Source', 'video-tab-for-woocommerce' ),
                  'placeholder'     => __( 'Enter video embed code or youtube/vimeo video url', 'video-tab-for-woocommerce' ),
                  'description'     => __( 'Embed code, shortcodes or link of video from youtube, vimeo and other video sites can be used', 'video-tab-for-woocommerce' ),
                  'style'           => 'height:100px;',
                )
            );

            ?> 
        </div>
    </div><?php
}

// Hook callback function to save custom fields information
add_action( 'woocommerce_process_product_meta', 'vtfw_custom_video_tab_save_data'  );

function vtfw_custom_video_tab_save_data($post_id) {

    // Save enable tab
    $vtwf_enable_tab = isset($_POST['_vtwf_enable_tab']) ? 'yes' : 'no';
    update_post_meta( $post_id, '_vtwf_enable_tab', $vtwf_enable_tab );

    // Save tab title
    $vtwf_tab_title = $_POST['_vtwf_tab_title'];
    update_post_meta( $post_id, '_vtwf_tab_title', esc_attr($vtwf_tab_title) );

    // Save hide title
    $vtwf_hide_title = isset($_POST['_vtwf_hide_title']) ? 'yes' : 'no';
    update_post_meta( $post_id, '_vtwf_hide_title', $vtwf_hide_title );

    $allowed_tags = array( 
        'em'        => array(),
        'strong'    => array(),
        'iframe'    => array(
                            'src'             => array(),
                            'height'          => array(),
                            'width'           => array(),
                            'frameborder'     => array(),
                            'allowfullscreen' => array(),
                            'allow'           => array(),
                        ),
        'a'         => array(
                            'class' => array(),
                            'href'  => array(),
                            'rel'   => array(),
                            'title' => array(),
                        ),
        'p'         => array(
                            'class' => array(),
                        ),
        'span'      => array(
                            'class' => array(),
                        ),
        'h1'        => array(
                            'class' => array(),
                        ),
        'h2'        => array(
                            'class' => array(),
                        ),
        'h3'        => array(
                            'class' => array(),
                        ),
        'h4'        => array(
                            'class' => array(),
                        ),
        'h5'        => array(
                            'class' => array(),
                        ),
        'h6'        => array(
                            'class' => array(),
                        ),
    );

    // Save Textarea
    $vtwf_video_content = $_POST['_vtwf_video_content'];
    update_post_meta( $post_id, '_vtwf_video_content', wp_kses($vtwf_video_content, $allowed_tags) );
}


//Add style to tab added for product video
add_action( 'admin_head', 'vtfw_custom_video_tab_style' );

function vtfw_custom_video_tab_style() {
    ?><style>
        #woocommerce-product-data ul.wc-tabs li.vtfw-video-tab_tab a:before { font-family: Dashicons; content: "\f236"; }
    </style><?php
}