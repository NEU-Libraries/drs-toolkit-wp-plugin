<?php
/**
 * Add Categories Widget.
 *
 * @package ThinkUpThemes
 */


/* ----------------------------------------------------------------------------------
	Categories
---------------------------------------------------------------------------------- */

class thinkup_widget_categories extends WP_Widget {

	/* Register widget description. */
	function thinkup_widget_categories() {
		$widget_ops = array('classname' => 'thinkup_widget_categories', 'description' => 'List your blog categories.' );
		$this->WP_Widget('thinkup_widget_categories', 'ThinkUpThemes: Categories', $widget_ops);
	}

	/* Add widget structure to Admin area. */
	function form($instance) {
		$default_entries = array( 'title' => '', 'postswitch' => '', 'displayswitch' => '', 'orderswitch' => '', 'directionswitch' => '', 'exclude' => '' );
		$instance = wp_parse_args( (array) $instance, $default_entries );

		$title           = $instance['title'];
		$postswitch      = $instance['postswitch'];
		$displayswitch   = $instance['displayswitch'];
		$orderswitch     = $instance['orderswitch'];
		$directionswitch = $instance['directionswitch'];
		$exclude         = $instance['exclude'];

		echo '<p><label for="' . $this->get_field_id('title') . '">' . __( 'Title', 'lan-thinkupthemes' ) . ': <input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" style="width: 95px;margin-left: 98px;" /></label></p>';

		echo '<p><label for="' . $this->get_field_id('postswitch') . '">' . __( 'Show post count', 'lan-thinkupthemes' ) . ':</label>&nbsp;<input id="' . $this->get_field_id('postswitch') . '" name="' . $this->get_field_name('postswitch') . '" type="checkbox" '; ?><?php checked( $postswitch, "on" ); ?><?php echo ' style="margin-left: 110px;" /></p>';

		echo '<p><label for="' . $this->get_field_id('displayswitch') . '" >' . __( 'Display type', 'lan-thinkupthemes' ) . ':
			<select name="' . $this->get_field_name('displayswitch') . '" id="' . $this->get_field_id('displayswitch') . '" style="margin-left: 56px;width: 95px;" >
			<option '; ?><?php selected( $displayswitch, "1" ); ?><?php echo ' value="1">list</option>
			<option '; ?><?php selected( $displayswitch, "2" ); ?><?php echo ' value="2">dropdown</option>
			</select>
		</label></p>';

		echo '<p><label for="' . $this->get_field_id('orderswitch') . '">' . __( 'Order by', 'lan-thinkupthemes' ) . ': 
			<select name="' . $this->get_field_name('orderswitch') . '" id="' . $this->get_field_id('orderswitch') . '" style="margin-left: 76px;width: 96px;" >
			<option '; ?><?php selected( $orderswitch, "1" ); ?><?php echo ' value="1">name</option>
			<option '; ?><?php selected( $orderswitch, "2" ); ?><?php echo ' value="2">posts</option>
			<option '; ?><?php selected( $orderswitch, "3" ); ?><?php echo ' value="3">ID</option>
			<option '; ?><?php selected( $orderswitch, "4" ); ?><?php echo ' value="4">slug</option>
			</select>
		</label></p>';

		echo '<p><label for="' . $this->get_field_id('directionswitch') . '">' . __( 'Order direction', 'lan-thinkupthemes' ) . ': 
			<select name="' . $this->get_field_name('directionswitch') . '" id="' . $this->get_field_id('directionswitch') . '" style="margin-left: 41px;" >
			<option '; ?><?php selected( $directionswitch, "1" ); ?><?php echo ' value="1">ascending</option>
			<option '; ?><?php selected( $directionswitch, "2" ); ?><?php echo ' value="2">descending</option>
			</select>
		</label></p>';

		echo '<p><label for="' . $this->get_field_id('exclude') . '">' . __( 'Exclude categories', 'lan-thinkupthemes' ) . ': <input class="widefat" id="' . $this->get_field_id('exclude') . '" name="' . $this->get_field_name('exclude') . '" type="text" value="' . esc_attr($exclude) . '" style="width: 95px;margin-left: 20px;" /></label></p>';
	}

	/* Assign variable values. */
	function update($new_instance, $old_instance) {
		$instance                    = $old_instance;
		$instance['title']           = $new_instance['title'];		
		$instance['postswitch']      = $new_instance['postswitch'];
		$instance['displayswitch']   = $new_instance['displayswitch'];
		$instance['orderswitch']     = $new_instance['orderswitch'];
		$instance['directionswitch'] = $new_instance['directionswitch'];
		$instance['exclude']         = $new_instance['exclude'];
		return $instance;
	}

	/* Output widget to front-end. */
	function widget($args, $instance) {
		if (empty($instance['displayswitch']) or $instance['displayswitch'] == '1' ) {
			$displayswitch = 'option1';
		} else {
			$displayswitch = 'option2';
		}
		if ($instance['postswitch'] == 'on') {
			$postswitch = '1';
		} else {
			$postswitch = '0';
		}
		if (empty($instance['orderswitch']) or $instance['orderswitch'] == '1') {
			$orderswitch ='name';
		} else if ($instance['orderswitch'] == '2') {
			$orderswitch = 'count';
		} else if ($instance['orderswitch'] == '3') {
			$orderswitch = 'ID';
		} else if ($instance['orderswitch'] == '4') {
			$orderswitch = 'slug';		
		}
		if (empty($instance['directionswitch']) or $instance['directionswitch'] == '1') {
			$directionswitch = 'ASC';
		} else if ($instance['directionswitch'] == '2') {
			$directionswitch = 'DESC';
		}
		if (!empty($instance['exclude'])) {
			$exclude = $instance['exclude'];
		} else {
			$exclude = '';
		}

		$categories = array(
			'show_count' => $postswitch,
			'orderby'    => $orderswitch,
			'order'      => $directionswitch,
			'exclude'    => $exclude,
			'title_li'   => '',
		);
		
		extract($args, EXTR_SKIP);
	 
		echo $before_widget;
		$title = empty($instance['title']) ? __( 'Categories', 'lan-thinkupthemes' ) : apply_filters('widget_title', $instance['title']);
		if (!empty($title))
		  echo $before_title . $title . $after_title;

		if ($displayswitch == 'option1') {
			$terms = get_categories($categories);
			if ($terms) {
				echo '<ul>';
					foreach( $terms as $term ) {
					  echo '<li class="cat-item cat-item-24">',
							'<a href="' . get_category_link( $term->term_id ) . '" title="' . sprintf( __( 'View all posts in %s', 'lan-thinkupthemes' ), $term->name ) . '" ' . '><span>' . $term->name;
								if ($instance['postswitch'] == 'on') {
									echo '<span>('.$term->count.')</span>';
								}
					  echo 	'</a></li> ';
					}
				echo '</ul>';
			}
		} else {
			wp_dropdown_categories( $categories );
		}

		echo $after_widget;
	  }

}

add_action( 'widgets_init', function(){
     register_widget( 'thinkup_widget_categories' );
});
?>