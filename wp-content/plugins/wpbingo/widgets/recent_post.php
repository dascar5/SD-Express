<?php
/**
 * Wpbingo Recent Post Widget
 * Plugin URI: http://www.wpbingosite.com
 * Version: 1.0
 * This Widget help you to show images of product as a beauty tab reponsive slideshow
 */
if ( !class_exists('bwp_recent_post_widget') ) {
	class bwp_recent_post_widget extends WP_Widget {

		/**
		 * Widget setup.
		 */
		function __construct() {
			/* Widget settings. */
			$widget_ops = array( 'classname' => 'bwp_recent_post_widget', 'description' => __('Wpbingo Recent Post Widget', "wpbingo" ) );

			/* Widget control settings. */
			$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'bwp_recent_post_widget' );

			/* Create the widget. */
			parent::__construct( 'bwp_recent_post_widget', __('Wpbingo Recent Post', "wpbingo" ), $widget_ops, $control_ops );
		}	
		
		public function widget( $args, $instance ) {
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
			extract($args);
			echo $before_widget;
			$title1 = apply_filters( 'widget_title', empty( $instance['title1'] ) ? '' : $instance['title1'], $instance, $this->id_base );
			$limit		 = 	( $instance['limit'] ) ? $instance['limit'] : 5;
			$category		 = 	( $instance['category'] ) ? $instance['category'] : '';
			$item_row	 = 	( $instance['item_row'] ) ? $instance['item_row'] : 1;
			$length		 = 	( $instance['length'] ) ? $instance['length'] : 25;
			$columns		 = 	( $instance['columns'] ) ? $instance['columns'] : 1;
			$columns1		 = 	( $instance['columns1'] ) ? $instance['columns1'] : 1;
			$columns2		 = 	( $instance['columns2'] ) ? $instance['columns2'] : 1;
			$columns3		 = 	( $instance['columns3'] ) ? $instance['columns3'] : 1;
			$columns4		 = 	( $instance['columns4'] ) ? $instance['columns4'] : 1;
			$show_nav		 = 	( $instance['show_nav'] ) ? $instance['show_nav'] : 'false';
			$layout		 = 	( $instance['layout'] ) ? $instance['layout'] : 'default';
			
			$tag_id = 'recent_post_' .rand().time();
			if($category){				
				$args = array(
					'post_type' => 'post',
					'category_name' => $category, 
					'posts_per_page' => $limit
				);
			}else{
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $limit
				);				
			}
			$query = new WP_Query($args);
			$post_count = $query->post_count;
			
			if( $instance['layout'] == 'default' ){
				include(WPBINGO_WIDGET_TEMPLATE_PATH.'bwp-recent-post/default.php' );
			}
			elseif($instance['layout'] == 'sidebar'){
				include(WPBINGO_WIDGET_TEMPLATE_PATH.'bwp-recent-post/sidebar.php' );
			}
			echo $after_widget;
		}		
		
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;

			// strip tag on text field
			$instance['title1'] = strip_tags( $new_instance['title1'] );
			if ( array_key_exists('category', $new_instance) ){
				if ( is_array($new_instance['category']) ){
					$instance['category'] = array_map( 'strip_tags', $new_instance['category'] );
				} else {
					$instance['category'] = strip_tags($new_instance['category']);
				}
			}
			if ( array_key_exists('limit', $new_instance) ){
				$instance['limit'] = intval( $new_instance['limit'] );
			}	
			if ( array_key_exists('item_row', $new_instance) ){
				$instance['item_row'] = intval( $new_instance['item_row'] );
			}				
			if ( array_key_exists('length', $new_instance) ){
				$instance['length'] = intval( $new_instance['length'] );
			}
			if ( array_key_exists('columns', $new_instance) ){
				$instance['columns'] = intval( $new_instance['columns'] );
			}
			if ( array_key_exists('columns1', $new_instance) ){
				$instance['columns1'] = intval( $new_instance['columns1'] );
			}
			if ( array_key_exists('columns2', $new_instance) ){
				$instance['columns2'] = intval( $new_instance['columns2'] );
			}
			if ( array_key_exists('columns3', $new_instance) ){
				$instance['columns3'] = intval( $new_instance['columns3'] );
			}
			if ( array_key_exists('columns4', $new_instance) ){
				$instance['columns4'] = intval( $new_instance['columns4'] );
			}
			if ( array_key_exists('show_nav', $new_instance) ){
				$instance['show_nav'] = strip_tags( $new_instance['show_nav'] );
			}
			
			$instance['layout'] = strip_tags( $new_instance['layout'] );
			
			return $instance;
		}

		public function bwp_trim_words( $text, $num_words = 30, $more = null ) {
			$text = strip_shortcodes( $text);
			$text = apply_filters('the_content', $text);
			$text = str_replace(']]>', ']]&gt;', $text);
			return wp_trim_words($text, $num_words, $more);
		}
			
		function category_select( $field_name, $opts = array(), $field_value = null ){
			$default_options = array(
					'multiple' => false,
					'disabled' => false,
					'size' => 5,
					'class' => 'widefat',
					'required' => false,
					'autofocus' => false,
					'form' => false,
			);
			$opts = wp_parse_args($opts, $default_options);
		
			if ( (is_string($opts['multiple']) && strtolower($opts['multiple'])=='multiple') || (is_bool($opts['multiple']) && $opts['multiple']) ){
				$opts['multiple'] = 'multiple';
				if ( !is_numeric($opts['size']) ){
					if ( intval($opts['size']) ){
						$opts['size'] = intval($opts['size']);
					} else {
						$opts['size'] = 5;
					}
				}
			} else {
				// is not multiple
				unset($opts['multiple']);
				unset($opts['size']);
				if (is_array($field_value)){
					$field_value = array_shift($field_value);
				}
				if (array_key_exists('allow_select_all', $opts) && $opts['allow_select_all']){
					unset($opts['allow_select_all']);
					$allow_select_all = '<option value="">All Categories</option>';
				}
			}
		
			if ( (is_string($opts['disabled']) && strtolower($opts['disabled'])=='disabled') || is_bool($opts['disabled']) && $opts['disabled'] ){
				$opts['disabled'] = 'disabled';
			} else {
				unset($opts['disabled']);
			}
		
			if ( (is_string($opts['required']) && strtolower($opts['required'])=='required') || (is_bool($opts['required']) && $opts['required']) ){
				$opts['required'] = 'required';
			} else {
				unset($opts['required']);
			}
		
			if ( !is_string($opts['form']) ) unset($opts['form']);
		
			if ( !isset($opts['autofocus']) || !$opts['autofocus'] ) unset($opts['autofocus']);
		
			$opts['id'] = $this->get_field_id($field_name);
		
			$opts['name'] = $this->get_field_name($field_name);
			if ( isset($opts['multiple']) ){
				$opts['name'] .= '[]';
			}
			$select_attributes = '';
			foreach ( $opts as $an => $av){
				$select_attributes .= "{$an}=\"{$av}\" ";
			}
			
			$categories = get_categories();
			$all_category_ids = array();
			foreach ($categories as $cat) $all_category_ids[] = strip_tags($cat->slug);
			
			$is_valid_field_value = in_array($field_value, $all_category_ids);
			if (!$is_valid_field_value && is_array($field_value)){
				$intersect_values = array_intersect($field_value, $all_category_ids);
				$is_valid_field_value = count($intersect_values) > 0;
			}
			if (!$is_valid_field_value){
				$field_value = '0';
			}
		
			$select_html = '<select ' . $select_attributes . '>';
			if (isset($allow_select_all)) $select_html .= $allow_select_all;
			foreach ($categories as $cat){
				$select_html .= '<option value="' . $cat->slug . '"';
				if ($cat->slug == $field_value || (is_array($field_value)&&in_array($cat->slug, $field_value))){ $select_html .= ' selected="selected"';}
				$select_html .=  '>'.$cat->name.'</option>';
			}
			$select_html .= '</select>';
			return $select_html;
		}
	
		public function form( $instance ) {
			/* Set up some default widget settings. */
			$defaults = array();
			$instance = wp_parse_args( (array) $instance, $defaults );
			$title1 = isset( $instance['title1'] )    ? 	strip_tags($instance['title1']) : '';
			$categoryslug = isset( $instance['category'] )    ? $instance['category'] : '';
			$number     = isset( $instance['limit'] ) ? intval($instance['limit']) : 5;
			$item_row     = isset( $instance['item_row'] ) ? intval($instance['item_row']) : 1;
			$length     = isset( $instance['length'] ) ? intval($instance['length']) : 20;
			$columns     = isset( $instance['columns'] )      ? intval($instance['columns']) : 1;
			$columns1     = isset( $instance['columns1'] )      ? intval($instance['columns1']) : 1;
			$columns2     = isset( $instance['columns2'] )      ? intval($instance['columns2']) : 1;
			$columns3     = isset( $instance['columns3'] )      ? intval($instance['columns3']) : 1;
			$columns4     = isset( $instance['columns4'] )      ? intval($instance['columns4']) : 1;
			$show_nav     = isset( $instance['show_nav'] )      ? intval($instance['show_nav']) : 'false';
			$layout   = isset( $instance['layout'] ) ? strip_tags($instance['layout']) : 'default';
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title1'); ?>"><?php _e('Title', "wpbingo")?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('title1'); ?>" name="<?php echo $this->get_field_name('title1'); ?>"
					type="text"	value="<?php echo esc_attr($title1); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Category', 'wpbingo')?></label>
				<br />
				<?php echo $this->category_select('category', array('allow_select_all' => true), $categoryslug); ?>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Number of Posts', "wpbingo")?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>"
					type="text"	value="<?php echo esc_attr($number); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('item_row'); ?>"><?php _e('Number row of Posts', "wpbingo")?></label>
				<br />
				<input class="widefat" id="<?php echo $this->get_field_id('item_row'); ?>" name="<?php echo $this->get_field_name('item_row'); ?>"
					type="text"	value="<?php echo esc_attr($item_row); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('length'); ?>"><?php _e('Excerpt length (in words): ', "wpbingo")?></label>
				<br />
				<input class="widefat"
					id="<?php echo $this->get_field_id('length'); ?>" name="<?php echo $this->get_field_name('length'); ?>" type="text"
					value="<?php echo esc_attr($length); ?>" />
			</p>
			<?php $number = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6); ?>
			<p>
				<label for="<?php echo $this->get_field_id('columns'); ?>"><?php _e('Number of Columns >1200px: ', "wpbingo")?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('columns'); ?>"
					name="<?php echo $this->get_field_name('columns'); ?>">
					<?php
					$option ='';
					foreach ($number as $key => $value) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $columns){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('columns1'); ?>"><?php _e('Number of Columns on 992px to 1199px: ', "wpbingo")?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('columns1'); ?>"
					name="<?php echo $this->get_field_name('columns1'); ?>">
					<?php
					$option ='';
					foreach ($number as $key => $value) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $columns1){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('columns2'); ?>"><?php _e('Number of Columns on 768px to 991px: ', "wpbingo")?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('columns2'); ?>"
					name="<?php echo $this->get_field_name('columns2'); ?>">
					<?php
					$option ='';
					foreach ($number as $key => $value) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $columns2){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('columns3'); ?>"><?php _e('Number of Columns on 480px to 767px: ', "wpbingo")?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('columns3'); ?>"
					name="<?php echo $this->get_field_name('columns3'); ?>">
					<?php
					$option ='';
					foreach ($number as $key => $value) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $columns3){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('columns4'); ?>"><?php _e('Number of Columns in 480px or less than: ', "wpbingo")?></label>
				<br />
				<select class="widefat"
					id="<?php echo $this->get_field_id('columns4'); ?>"
					name="<?php echo $this->get_field_name('columns4'); ?>">
					<?php
					$option ='';
					foreach ($number as $key => $value) :
						$option .= '<option value="' . $value . '" ';
						if ($value == $columns4){
							$option .= 'selected="selected"';
						}
						$option .=  '>'.$key.'</option>';
					endforeach;
					echo $option;
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('show_nav'); ?>"><?php _e("Show Navigation", "wpbingo" )?></label>
				<br/>				
				<select class="widefat"
					id="<?php echo $this->get_field_id('show_nav'); ?>"	name="<?php echo $this->get_field_name('show_nav'); ?>">
					<option value="true" <?php if ($show_nav=='true'){?> selected="selected" <?php } ?>>
						<?php _e('Yes', "wpbingo")?>
					</option>
					<option value="false" <?php if ($show_nav=='false'){?> selected="selected" <?php } ?>>
						<?php _e('No', "wpbingo")?>
					</option>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e("Template", "wpbingo")?></label>
				<br/>
				<select class="widefat"
					id="<?php echo $this->get_field_id('layout'); ?>"	name="<?php echo $this->get_field_name('layout'); ?>">
					<option value="default" <?php if ($layout=='default'){?> selected="selected"
					<?php } ?>>
						<?php echo esc_html__('Default', "wpbingo");?>
					</option>
					<option value="sidebar" <?php if ($layout=='sidebar'){?> selected="selected"
					<?php } ?>>
						<?php echo esc_html__('Sidebar', "wpbingo");?>
					</option>
				</select>
			</p>
		<p>			
		<?php
		}		
	}
	add_action( 'widgets_init', 'bwp_register_recent_post_widget' );
	function bwp_register_recent_post_widget(){
		register_widget( 'bwp_recent_post_widget');
	}
}
?>