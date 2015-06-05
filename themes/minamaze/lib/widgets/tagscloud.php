<?php
/**
 * Add Categories Widget.
 *
 * @package ThinkUpThemes
 */


/* ----------------------------------------------------------------------------------
	Categories
---------------------------------------------------------------------------------- */

class thinkup_widget_tagscloud extends WP_Widget {

	/* Register widget description. */
	function thinkup_widget_tagscloud() {
		$widget_ops = array('classname' => 'thinkup_widget_tagscloud', 'description' => 'A cool tag cloud.' );
		$this->WP_Widget('thinkup_widget_tagscloud', 'ThinkUpThemes: Tags Cloud', $widget_ops);
	}

	/* Add widget structure to Admin area. */
	function form($instance) {
		$default_entries = array( 'title' => '', 'orderby' => '', 'order' => '', 'exclude' => '' );
		$instance = wp_parse_args( (array) $instance, $default_entries );

		$title           = $instance['title'];

		echo '<p><label for="' . $this->get_field_id('title') . '">' . __( 'Title', 'lan-thinkupthemes' ) . ': <input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . esc_attr($title) . '" style="width: 95px;margin-left: 98px;" /></label></p>';

	}

	/* Assign variable values. */
	function update($new_instance, $old_instance) {
		$instance                    = $old_instance;
		$instance['title']           = $new_instance['title'];
		return $instance;
	}

	/* Output widget to front-end. */
	function widget($args, $instance) {		
		extract($args, EXTR_SKIP);
	 
		echo $before_widget;
		$title = empty($instance['title']) ? __( 'Tags', 'lan-thinkupthemes' ) : apply_filters('widget_title', $instance['title']);

		/* Title widget area */
		if (!empty($title))
		  echo $before_title . $title . $after_title;

		/* Main widget area */
		$tags = get_tags();
		$html = '<div class="post_tags">';
			foreach ( $tags as $tag ) {
				$tag_link = get_tag_link( $tag->term_id );
				$html .= "<a href='{$tag_link}' title='{$tag->name} Tag'>";
				$html .= "{$tag->name}</a>";
			}
		$html .= '</div>';
		echo $html;

		echo $after_widget;
	  }

}

add_action( 'widgets_init', function(){
     register_widget( 'thinkup_widget_tagscloud' );
});
?>