<?php
/**
 * Wpbingo Custom Menu
 * Plugin URI: http://www.wpbingosite.com
 * Version: 1.0
 * This Widget help you to show images of product as a beauty tab reponsive slideshow
 */
class Bwp_WP_Nav_Menu_Widget extends WP_Widget {

	/**
	 * Sets up a new Custom Menu widget instance.
	 *
	 * @since 3.0.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'description' => esc_html__( 'Add a custom menu to your sidebar.','wpbingo' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'nav_menu', esc_html__('Wpbingo Custom Menu','wpbingo'), $widget_ops );
		
	}
	
	public function widget( $args, $instance ) {
		$class_vertical = dimita_dropdown_vertical_menu();
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
		if ( !$nav_menu )
			return;
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		$vertical = ( isset($instance['vertical']) &&  !empty($instance['vertical']) ) ? $instance['vertical'] : 'false';
		echo $args['before_widget']; ?>
		<?php if($vertical != 'false'): ?>
		<div class="categories-vertical-menu <?php echo esc_attr($class_vertical); ?>"
			data-textmore="<?php echo esc_html__("Other","wpbingo"); ?>" 
			data-textclose="<?php echo esc_html__("Close","wpbingo"); ?>" 
			data-max_number_1530="<?php echo esc_attr(dimita_limit_verticalmenu()->max_number_1530); ?>" 
			data-max_number_1200="<?php echo esc_attr(dimita_limit_verticalmenu()->max_number_1200); ?>" 
			data-max_number_991="<?php echo esc_attr(dimita_limit_verticalmenu()->max_number_991); ?>">
			<div class="widget-custom-menu bwp-vertical-navigation">
				<?php 
				if ( !empty($instance['title']) )
					echo '<h2 class="widget-title"><i class="fa fa-bars" aria-hidden="true"></i>'.esc_html($instance['title']).'</h2>';
				$nav_menu_args = array(
					'fallback_cb' 	=> '',
					'menu'      => $nav_menu,
					'walker' 	=> new dimita_mega_menu_walker,
				); 
				wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance ) ); ?> 
			</div>
		</div>
		<?php else: ?>
			<div class="widget-custom-menu <?php echo ( (isset($instance['class']) && ($instance['class'])) ?  esc_attr($instance['class']) : '' ); ?>">
				<?php 
				if ( !empty($instance['title']) )
					echo $args['before_title'] . $instance['title'] . $args['after_title'];
				$nav_menu_args = array(
					'fallback_cb' 	=> '',
					'menu'      => $nav_menu,
					'walker' 	=> new dimita_mega_menu_walker,
				); 
				wp_nav_menu( apply_filters( 'widget_nav_menu_args', $nav_menu_args, $nav_menu, $args, $instance ) ); ?> 
			</div>
		<?php endif; ?>
		<?php echo $args['after_widget'];	
	}

	/**
	 * Handles updating settings for the current Custom Menu widget instance.
	 *
	 * @since 3.0.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = sanitize_text_field( $new_instance['title'] );
		}
		if ( ! empty( $new_instance['nav_menu'] ) ) {
			$instance['nav_menu'] = $new_instance['nav_menu'];
		}
		if ( ! empty( $new_instance['vertical'] ) ) {
			$instance['vertical'] = $new_instance['vertical'];
		}	
		return $instance;
	}

	/**
	 * Outputs the settings form for the Custom Menu widget.
	 *
	 * @since 3.0.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 * @global WP_Customize_Manager $wp_customize
	 */
	public function form( $instance ) {
		global $wp_customize;
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';
		$vertical     = isset( $instance['vertical'] ) ? ($instance['vertical']) : 'false';
		// Get menus
		$menus = wp_get_nav_menus();
		// If no menus exists, direct the user to go and create some.
		?>
		<p class="nav-menu-widget-no-menus-message" <?php if ( ! empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<?php
			if ( $wp_customize instanceof WP_Customize_Manager ) {
				$url = 'javascript: wp.customize.panel( "nav_menus" ).focus();';
			} else {
				$url = admin_url( 'nav-menus.php' );
			}
			?>
			<?php echo sprintf( esc_html__( 'No menus have been created yet. <a href="%s">Create some</a>.' ,'wpbingo'), esc_attr( $url ) ); ?>
		</p>
		<div class="nav-menu-widget-form-controls" <?php if ( empty( $menus ) ) { echo ' style="display:none" '; } ?>>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo esc_html__( 'Title:','wpbingo' ) ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>"/>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('vertical'); ?>"><?php _e("Vetical Menu", "wpbingo" )?></label>
				<br/>			
				<select class="widefat"
					id="<?php echo $this->get_field_id('vertical'); ?>"	name="<?php echo $this->get_field_name('vertical'); ?>">
					<option value="true" <?php if ($vertical=='true'){?> selected="selected" <?php } ?>>
						<?php _e('Yes', "wpbingo")?>
					</option>
					<option value="false" <?php if ($vertical=='false'){?> selected="selected" <?php } ?>>
						<?php _e('No', "wpbingo")?>
					</option>
				</select>
			</p>			
			<p>
				<label for="<?php echo $this->get_field_id( 'nav_menu' ); ?>"><?php echo esc_html__( 'Select Menu:' ,'wpbingo'); ?></label>
				<select id="<?php echo $this->get_field_id( 'nav_menu' ); ?>" name="<?php echo $this->get_field_name( 'nav_menu' ); ?>">
					<option value="0"><?php esc_html__( '&mdash; Select &mdash;' ,'wpbingo'); ?></option>
					<?php foreach ( $menus as $menu ) : ?>
						<option value="<?php echo esc_attr( $menu->slug ); ?>" <?php selected( $nav_menu, $menu->slug ); ?>>
							<?php echo esc_html( $menu->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</p>
			<?php if ( $wp_customize instanceof WP_Customize_Manager ) : ?>
				<p class="edit-selected-nav-menu" style="<?php if ( ! $nav_menu ) { echo 'display: none;'; } ?>">
					<button type="button" class="button"><?php esc_html__( 'Edit Menu','wpbingo' ) ?></button>
				</p>
			<?php endif; ?>
		</div>
		<?php
	}
}

add_action( 'widgets_init', 'bwp_register_widget_custom_menu' );
function bwp_register_widget_custom_menu(){
	unregister_widget('WP_Nav_Menu_Widget');
	register_widget( 'Bwp_WP_Nav_Menu_Widget');
}