<?php
/**
 * Add Recent Posts Widget.
 *
 * @package ThinkUpThemes
 */


/* ----------------------------------------------------------------------------------
	Recent Posts
---------------------------------------------------------------------------------- */

class thinkup_widget_recentposts extends WP_Widget {

	/* Register widget description. */
	function thinkup_widget_recentposts() {
		$widget_ops = array('classname' => 'thinkup_widget_recentposts', 'description' => 'Display your recent posts.' );
		$this->WP_Widget('thinkup_widget_recentposts', 'ThinkUpThemes: Recent Posts', $widget_ops);
	}

	/* Add widget structure to Admin area. */
	function form($instance) {
		$default_entries = array( 'title' => '', 'postcount' => '5', 'imageswitch' => '', 'postdate' => '' );
		$instance = wp_parse_args( (array) $instance, $default_entries );

		$title = $instance['title'];
		$postcount = $instance['postcount'];
		$imageswitch = $instance['imageswitch'];
		$postdate = $instance['postdate'];

	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title', 'lan-thinkupthemes' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" style="width: 80px;margin-left: 114px;" /></label></p>

		<p><label for="<?php echo $this->get_field_id('postcount'); ?>"><?php _e( 'Number of posts', 'lan-thinkupthemes' ); ?>: <input class="widefat" id="<?php echo $this->get_field_id('postcount'); ?>" name="<?php echo $this->get_field_name('postcount'); ?>" type="text" value="<?php echo $postcount; ?>" style="width: 80px;margin-left: 46px;" /></label></p>

		<p><label for="<?php echo $this->get_field_id('imageswitch'); ?>"><?php _e( 'Enable post thumbnail', 'lan-thinkupthemes' ); ?>?</label>&nbsp;<input id="<?php echo $this->get_field_id('imageswitch'); ?>" name="<?php echo $this->get_field_name('imageswitch'); ?>" type="checkbox" <?php checked( $imageswitch, "on" ); ?> style="margin-left: 74px;" /></p>

		<p><label for="<?php echo $this->get_field_id('postdate'); ?>"><?php _e( 'Show post date', 'lan-thinkupthemes' ); ?>?</label>&nbsp;<input id="<?php echo $this->get_field_id('postdate'); ?>" name="<?php echo $this->get_field_name('postdate'); ?>" type="checkbox" <?php checked( $postdate, "on" ); ?> style="margin-left: 113px;" /></p>
	<?php
	}

	/* Assign variable values. */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['postcount'] = $new_instance['postcount'];
		$instance['imageswitch'] = $new_instance['imageswitch'];
		$instance['postdate'] = $new_instance['postdate'];
		return $instance;
	}

	/* Output widget to front-end. */
	function widget($args, $instance) {
	global $post;

		extract($args, EXTR_SKIP);

		echo $before_widget;
		$title = empty($instance['title']) ? __( 'Recent Posts', 'lan-thinkupthemes' ) : apply_filters('widget_title', $instance['title']);
		if (!empty($title))
			echo $before_title . $title . $after_title;

		$posts = new WP_Query('orderby=date&posts_per_page=' . $instance['postcount'] . '');
		while ($posts->have_posts()) : $posts->the_post();

			/* Insert post date if needed. */
			if ( $instance['postdate'] == 'on' ) {
				$date_input = '<a href="' . get_permalink() . '" class="date">' .  get_the_date( 'M j, Y' ) . '</a>';
			}
		
			/* HTML output */
			echo '<div class="recent-posts">';
				if ( has_post_thumbnail() and $instance['imageswitch'] == 'on' ) {
				echo	'<div class="image">',
						'<a href="' . get_permalink() . '" title="' . get_the_title() . '">' . get_the_post_thumbnail( $post->ID, array(65,65) ) . '<div class="image-overlay"></div></a>',
						'</div>',
						'<div class="main">',
						'<a href="' . get_permalink() . '">' . get_the_title() . '</a>',
						$date_input,
						'</div>';
				} else {
				echo	'<div class="main">',
						'<a href="' . get_permalink() . '">' . get_the_title() . '</a>',
						$date_input,
						'</div>';
				}
			echo '</div>';
		endwhile;

		echo $after_widget;
	}
	 
}

add_action( 'widgets_init', function(){
     register_widget( 'thinkup_widget_recentposts' );
});
?>