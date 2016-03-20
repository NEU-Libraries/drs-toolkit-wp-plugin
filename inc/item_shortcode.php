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
  $html = "<div class='drs-item'>";

  $jwplayer = false; // note: unneeded if there is only one canonical_object type

  if (isset($data->canonical_object)){
    foreach($data->canonical_object as $key=>$val){
      if ($val == 'Video File' || $val == 'Audio File'){
        $html .= insert_jwplayer($key, $val, $data, $thumbnail);
        $jwplayer = true;
      }
    }
  }

  if (!$jwplayer) {
    $html .= "<a href='".drstk_home_url()."item/".$atts['id']."'><img class='drs-item-img' id='".$atts['id']."-img' src='".$thumbnail."'";

    if (isset($atts['align'])){
      $html .= " data-align='".$atts['align']."'";
    }

    if (isset($atts['zoom']) && $atts['zoom'] == 'on'){
      $html .= " data-zoom-image='".$master."' data-zoom='on'";
      if (isset($atts['zoom_position'])){
        $html .= " data-zoom-position='".$atts['zoom_position']."'";
      }
    }

    $html .= "/></a>";
  }

  // start item meta data
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
    $html .= "<div class='wp-caption-text drstk-caption'";
    if (isset($atts['caption-align'])){
      $html .= " data-caption-align='".$atts['caption-align']."'";
    }
    if (isset($atts['caption-position'])){
      $html .= " data-caption-position='".$atts['caption-position']."'";
    }
    $html .= ">".$img_metadata."</div>";
  }

  // start hidden fields
  $html .= "<div class=\"hidden\">";
  $meta = $data->mods;
  foreach($meta as $field){
    if (is_array($field)){
      foreach($field as $field_val){
        $html .= $field_val . "<br/>";
      }
    } else {
      $html .= $field[0] . "<br/>";
    }
  }
  $html .= "</div></div>";
  $cache_output = $html;
  $cache_time = 1000;
  set_transient(md5('DRSTK'.serialize($atts)) , $cache_output, $cache_time * 60);
  return $html;
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
    wp_register_script('drstk_jwplayer', plugins_url('../assets/js/jwplayer/jwplayer.js', __FILE__), array(), $VERSION, false );
    wp_enqueue_script('drstk_jwplayer');
  }
}
add_action( 'wp_enqueue_scripts', 'drstk_item_shortcode_scripts');
