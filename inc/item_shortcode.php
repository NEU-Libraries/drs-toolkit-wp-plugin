<?php
/* adds shortcode */
add_shortcode( 'drstk_item', 'drstk_item' );
function drstk_item( $atts ){
  $url = "https://repository.library.northeastern.edu/api/v1/files/" . $atts['id'];
  $data = get_response($url);
  $data = json_decode($data);
  $thumbnail = $data->thumbnails[3];
  $master = $data->thumbnails[4];
  $img_html = "<img class='drs-item-img' id='".$atts['id']."-img' src='".$thumbnail."'";
  if (isset($atts['zoom']) && $atts['zoom'] == 'on'){
    // if ($data->canonical_object[0][1] == 'Master Image'){
      // $master = $data->canonical_object[0][0];
    // }
    $img_html .= " data-zoom-image='".$master."' data-zoom='on'";
    if (isset($atts['zoom_position'])){
      $img_html .= " data-zoom-position='".$atts['zoom_position']."'";
    }
  }
  $img_metadata = "";
  if (isset($atts['metadata'])){
    $metadata = explode(",",$atts['metadata']);
    foreach($metadata as $field){
      $this_field = $data->mods->$field;
      if (is_array($this_field)){
        foreach($this_field as $field_val){
          $img_metadata .= $field_val . "<br/>";
        }
      } else {
        $img_metadata .= $this_field[0] . "<br/>";
      }
    }
  }
  $img_html .= "/>";
  $img_html .= "<div class='wp-caption-text drstk-caption'>".$img_metadata."</div>";
  $img_html .= "<div class='hidden'>";
  $meta = $data->mods;
  foreach($meta as $field){
    if (is_array($field)){
      foreach($field as $field_val){
        $img_html .= $field_val . "<br/>";
      }
    } else {
      $img_html .= $field[0] . "<br/>";
    }
  }
  $img_html .= "</div>";
  return $img_html;
}

add_action( 'wp_ajax_get_item_admin', 'item_admin_ajax_handler' ); //for auth users
function item_admin_ajax_handler() {
  $data = array();
  // Handle the ajax request
  check_ajax_referer( 'item_admin_nonce' );
  $url = "https://repository.library.northeastern.edu/api/v1/files/" . $_POST['pid'];
  $data = get_response($url);
  $data = json_decode($data);
  wp_send_json(json_encode($data));
}

function drstk_item_shortcode_scripts() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_item') ) {
    wp_register_script('drstk_elevatezoom', plugins_url('../assets/js/elevatezoom/jquery.elevateZoom-3.0.8.min.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('drstk_elevatezoom');
    wp_enqueue_script( 'drstk_zoom',
        plugins_url( '../assets/js/zoom.js', __FILE__ ),
        array( 'jquery' )
    );
	}
}
add_action( 'wp_enqueue_scripts', 'drstk_item_shortcode_scripts');
