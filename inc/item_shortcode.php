<?php
/* adds shortcode */
add_shortcode( 'drstk_item', 'drstk_item' );
function drstk_item( $atts ){
  $cache = get_transient(md5('DRSTK'.serialize($atts)));

  if($cache) {
      return $cache;
  }
  $url = "https://repository.library.northeastern.edu/api/v1/files/" . $atts['id'];
  $data = get_response($url);
  $data = json_decode($data);
  if (isset($atts['image-size'])){
    $num = $atts['image-size']-1;
  } else {
    $num = 3;
  }
  $thumbnail = $data->thumbnails[$num];
  $master = $data->thumbnails[4];
  foreach($data->content_objects as $key=>$val){
    if ($val == 'Large Image'){
      $master = $key;
    }
  }
  $img_html = "<div class='drs-item'><a href='".drstk_home_url()."item/".$atts['id']."'><img class='drs-item-img' id='".$atts['id']."-img' src='".$thumbnail."'";
  if (isset($atts['align'])){
    $img_html .= " data-align='".$atts['align']."'";
  }

  if (isset($atts['zoom']) && $atts['zoom'] == 'on'){
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
        if (isset($this_field[0])){
          $img_metadata .= $this_field[0] . "<br/>";
        }
      }
    }
  }
  $img_html .= "/>";
  $img_html .= "<div class='wp-caption-text drstk-caption'";
  if (isset($atts['caption-align'])){
    $img_html .= " data-caption-align='".$atts['caption-align']."'";
  }
  $img_html .= ">".$img_metadata."</div>";
  $img_html .= "</a><div class=\"hidden\">";
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
  $img_html .= "</div></div>";
  $cache_output = $img_html;
  $cache_time = 1000;
  set_transient(md5('DRSTK'.serialize($atts)) , $cache_output, $cache_time * 60);
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
  wp_die();
}

function drstk_item_shortcode_scripts() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_item') ) {
    wp_register_script('drstk_elevatezoom', plugins_url('../assets/js/elevatezoom/jquery.elevateZoom-3.0.8.min.js', __FILE__), array( 'jquery' ));
    wp_enqueue_script('drstk_elevatezoom');
    wp_register_script( 'drstk_zoom', plugins_url( '../assets/js/zoom.js', __FILE__ ), array( 'jquery' ));
    wp_enqueue_script('drstk_zoom');
	}
}
add_action( 'wp_enqueue_scripts', 'drstk_item_shortcode_scripts');
