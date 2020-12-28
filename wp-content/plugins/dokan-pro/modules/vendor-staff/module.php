<?php

namespace WeDevs\DokanPro\Modules\VendorStaff;

class Module {

    /**
     * Class cosntructor
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses is_admin()
     * @uses add_action()
     */
    public function __construct() {
        $this->define_constant();
        $this->includes();
        $this->initiate();

        add_filter( 'dokan_get_dashboard_nav', array( $this, 'add_staffs_page' ), 15 );
        add_filter( 'dokan_query_var_filter', array( $this, 'add_endpoint' ) );
        add_action( 'dokan_load_custom_template', array( $this, 'load_staff_template' ), 16 );
        add_filter( 'dokan_set_template_path', array( $this, 'load_vendor_staff_templates' ), 11, 3 );
        add_action( 'dokan_rewrite_rules_loaded', array( $this, 'add_rewrite_rules' ) );
        add_action( 'admin_init', array( $this, 'disable_backend_access' ) );
        add_filter( 'show_admin_bar', array( $this, 'disable_admin_bar' ) );
        add_filter( 'woocommerce_email_classes', array( $this, 'load_staff_emails' ), 40 );

        add_action( 'dokan_activated_module_vendor_staff', array( self::class, 'activate' ) );
        add_action( 'dokan_deactivated_module_vendor_staff', array( self::class, 'deactivate' ) );
    }

    /**
     * Define all constant
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function define_constant() {
        define( 'DOKAN_VENDOR_staff_DIR', dirname( __FILE__ ) );
        define( 'DOKAN_VENDOR_staff_INC_DIR', DOKAN_VENDOR_staff_DIR . '/includes' );
    }

    /**
     * Includes all files
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function includes() {
        require_once DOKAN_VENDOR_staff_INC_DIR . '/functions.php';
        require_once DOKAN_VENDOR_staff_INC_DIR . '/class-staffs.php';
    }

    /**
     * Inistantiate all class
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function initiate() {
        new \Dokan_staffs();
    }

    /**
     * Enqueue admin scripts
     *
     * Allows plugin assets to be loaded.
     *
     * @uses wp_enqueue_script()
     * @uses wp_localize_script()
     * @uses wp_enqueue_style
     */
    public function enqueue_scripts() {

    }

    /**
     * Flush rewrite endpoind after activation
     *
     * @since 1.5.2
     *
     * @return void
     */
    function add_rewrite_rules() {
        if ( get_transient( 'dokan-vendor-staff' ) ) {
            flush_rewrite_rules( true );
            delete_transient( 'dokan-vendor-staff' );
        }
    }

    /**
     * Disable backend access of vendor_staff
     *
     * @since 2.7.6
     *
     * @return void
     */
    public function disable_backend_access() {
        if ( is_super_admin() ) {
            return;
        }

        if ( ! current_user_can( 'vendor_staff' ) ) {
            return;
        }

        if ( is_admin() && ! wp_doing_ajax() ) {
            wp_redirect( dokan_get_navigation_url( 'dashboard' ) );
            exit;
        }
    }

    /**
     * Disable admin bar when the user role is vendor_staff
     * @since 2.7.6
     *
     * @return bool
     */
    public function disable_admin_bar( $show_admin_bar ) {
        if ( is_super_admin() ) {
            return $show_admin_bar;
        }

        if ( current_user_can( 'vendor_staff' ) ) {
            return false;
        }

        return $show_admin_bar;
    }

    /**
     * Activate functions
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function activate() {
        global $wp_roles;
        set_transient( 'dokan-vendor-staff', 1 );

        if ( class_exists( 'WP_Roles' ) && ! isset( $wp_roles ) ) {
            $wp_roles = new \WP_Roles();
        }

        add_role( 'vendor_staff', __( 'Vendor Staff', 'dokan' ), array(
            'read'     => true,
        ) );

        $users_query = new \WP_User_Query( array(
            'role' => 'vendor_staff'
        ) );

        $staffs = $users_query->get_results();
        $staff_caps = dokan_get_staff_capabilities();

        if ( count( $staffs ) > 0 ) {
            foreach ( $staffs as $staff ) {
                $staff->add_cap( 'dokandar' );
                $staff->add_cap( 'delete_pages' );
                $staff->add_cap( 'publish_posts' );
                $staff->add_cap( 'edit_posts' );
                $staff->add_cap( 'delete_published_posts' );
                $staff->add_cap( 'edit_published_posts' );
                $staff->add_cap( 'delete_posts' );
                $staff->add_cap( 'manage_categories' );
                $staff->add_cap( 'moderate_comments' );
                $staff->add_cap( 'unfiltered_html' );
                $staff->add_cap( 'upload_files' );
                $staff->add_cap( 'edit_shop_orders' );
                $staff->add_cap( 'edit_product' );

                foreach ( $staff_caps as $key => $staff_cap ) {
                    $staff->add_cap( $staff_cap );
                }
            }
        }
    }

    /**
     * Deactivate functions
     *
     * @since 1.0.0
     *
     * @return void
     */
    public static function deactivate() {
        $users_query = new \WP_User_Query( array(
            'role' => 'vendor_staff'
        ) );

        $staffs = $users_query->get_results();
        $staff_caps = dokan_get_staff_capabilities();

        if ( count( $staffs ) > 0 ) {
            foreach ( $staffs as $staff ) {
                $staff->remove_cap( 'dokandar' );
                $staff->remove_cap( 'delete_pages' );
                $staff->remove_cap( 'publish_posts' );
                $staff->remove_cap( 'edit_posts' );
                $staff->remove_cap( 'delete_published_posts' );
                $staff->remove_cap( 'edit_published_posts' );
                $staff->remove_cap( 'delete_posts' );
                $staff->remove_cap( 'manage_categories' );
                $staff->remove_cap( 'moderate_comments' );
                $staff->remove_cap( 'unfiltered_html' );
                $staff->remove_cap( 'upload_files' );
                $staff->remove_cap( 'edit_shop_orders' );
                $staff->remove_cap( 'edit_product' );

                foreach ( $staff_caps as $key => $staff_cap ) {
                    $staff->remove_cap( $staff_cap );
                }
            }
        }
    }

    /**
     * Add staffs endpoint to the end of Dashboard
     *
     * @param array $query_var
     */
    function add_endpoint( $query_var ) {
        $query_var['staffs'] = 'staffs';

        return $query_var;
    }

    /**
    * Get plugin path
    *
    * @since 2.8
    *
    * @return void
    **/
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
    * Load Dokan vendor_staff templates
    *
    * @since 2.8
    *
    * @return void
    **/
    public function load_vendor_staff_templates( $template_path, $template, $args ) {
        if ( isset( $args['is_vendor_staff'] ) && $args['is_vendor_staff'] ) {
            return $this->plugin_path() . '/templates';
        }

        return $template_path;
    }

    /**
     * Load tools template
     *
     * @since  1.0
     *
     * @param  array $query_vars
     *
     * @return string
     */
    function load_staff_template( $query_vars ) {

        if ( isset( $query_vars['staffs'] ) ) {
            if ( ! current_user_can( 'seller' ) ) {
                dokan_get_template_part('global/dokan-error', '', array( 'deleted' => false, 'message' => __( 'You have no permission to view this page', 'dokan' ) ) );
            } else {
                if ( isset( $_GET['view'] ) && $_GET['view'] == 'add_staffs' ) {
                    dokan_get_template_part( 'vendor-staff/add-staffs', '', array( 'is_vendor_staff' => true ) );
                } else if ( isset( $_GET['view'] ) && $_GET['view'] == 'manage_permissions' ) {
                    dokan_get_template_part( 'vendor-staff/permissions', '', array( 'is_vendor_staff' => true ) );
                }else {
                    dokan_get_template_part( 'vendor-staff/staffs', '', array( 'is_vendor_staff' => true ) );
                }
            }
        }
    }



    /**
     * Add staffs page in seller dashboard
     *
     * @param array $urls
     *
     * @return array $urls
     */
    public function add_staffs_page( $urls ) {
        if ( dokan_is_seller_enabled( get_current_user_id() ) && current_user_can( 'seller' ) ) {
            $urls['staffs'] = array(
                'title' => __( 'Staff', 'dokan' ),
                'icon'  => '<i class="fa fa-users"></i>',
                'url'   => dokan_get_navigation_url( 'staffs' ),
                'pos'   => 172
            );
        }

        return $urls;
    }

    /**
     * Load staff email class
     *
     * @param  array $emails
     *
     * @return array
     */
    public function load_staff_emails( $emails ) {
        $emails['Dokan_Staff_New_Order'] = include( DOKAN_VENDOR_staff_INC_DIR . '/class-staff-new-order-email.php' );

        return $emails;
    }
}
