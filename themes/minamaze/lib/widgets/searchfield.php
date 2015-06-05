<?php
/**
 * Add Search Field Widget.
 *
 * @package ThinkUpThemes
 */


/* ----------------------------------------------------------------------------------
	Search Field
---------------------------------------------------------------------------------- */

class thinkup_widget_search extends WP_Widget {

	/* Register widget description. */
	function thinkup_widget_search() {
		$widget_ops = array('classname' => 'thinkup_widget_search', 'description' => 'Display a simple search field.' );
		$this->WP_Widget('thinkup_widget_search', 'ThinkUpThemes: Search', $widget_ops);
	}

	/* Add widget structure to Admin area. */
	function form($instance) {
		$default_entries = array( 'title' => '', 'buttonswitch' => '' , 'buttontext' => '' );
		$instance = wp_parse_args( (array) $instance, $default_entries );

		$title        = $instance['title'];
		$buttonswitch = $instance['buttonswitch'];
		$buttontext   = $instance['buttontext'];

		echo '<p><label for="' . $this->get_field_id('title') . '">' . __( 'Title', 'lan-thinkupthemes' ) . ': <input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" /></label></p>';
	}

	/* Assign variable values. */
	function update($new_instance, $old_instance) {
		$instance                 = $old_instance;
		$instance['title']        = $new_instance['title'];
		$instance['buttonswitch'] = $new_instance['buttonswitch'];
		$instance['buttontext']   = $new_instance['buttontext'];
		return $instance;
	}

	/* Output widget to front-end. */
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$title = empty($instance['title']) ? __( 'Search Form', 'lan-thinkupthemes' ) : apply_filters('widget_title', $instance['title']);
		if (!empty($title))
			echo $before_title . $title . $after_title;;

		get_search_form();

		echo $after_widget;
	}

}

add_action( 'widgets_init', function(){
     register_widget( 'thinkup_widget_search' );
});
?>