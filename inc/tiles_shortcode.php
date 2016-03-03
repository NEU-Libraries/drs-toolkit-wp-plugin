<?php
/* adds shortcode */
add_shortcode( 'drstk_tiles', 'drstk_tiles' );
function drstk_tiles( $atts ){
  global $errors;
  $cache = get_transient(md5('PREFIX'.serialize($atts)));

  if($cache) {
      return $cache;
  }
  $imgs = explode(", ",$atts['id']);
  $img_html = "";
  foreach($imgs as $img){
    $url = "https://repository.library.northeastern.edu/api/v1/files/" . $img;
    $data = get_response($url);
    $data = json_decode($data);
    $type = $atts['type'];
    if (!isset($data->error)){
      $pid = $data->pid;
      if (isset($atts['image-size'])){
        $num = $atts['image-size']-1;
      } else {
        $num = 4;
      }
      $thumbnail = $data->thumbnails[$num];
      if (isset($atts['metadata'])){
        $img_metadata = '';
        $metadata = explode(",",$atts['metadata']);
        foreach($metadata as $field){
          $this_field = $data->mods->$field;
          if (isset($this_field[0])){
            $img_metadata .= $this_field[0] . "<br/>";
          }
        }
      }
      if ($type == 'pinterest-below' || $type == 'pinterest'){
        $img_html .= "<div class='brick'><a href='".site_url()."/item/".$pid."'><img src='".$thumbnail."'></a><div class='info wp-caption-text'><a href='".site_url()."/item/".$pid."'>".$img_metadata."</a>";
      }
      if ($type == 'pinterest-hover'){
        $img_html .= "<div class='brick brick-hover'><img src='".$thumbnail."' style='width:100%'><div class='info wp-caption-text'><a href='".site_url()."/item/".$pid."'>".$img_metadata."</a>";
      }
      if ($type == 'even-row' || $type == 'square'){
        $img_html .= "<div class='cell' data-thumbnail='".$thumbnail."'><div class='info wp-caption-text'><a href='".site_url()."/item/".$pid."'>".$img_metadata."</a>";
      }
      $img_html .= "<div class=\"hidden\">";
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
      $img_html .= "</div></div>";
    } else {
      $img_html = $errors['shortcodes']['fail'];
    }

  }
  $shortcode = "<div class='freewall' id='freewall' data-type='".$type."'";
  if (isset($atts['cell-height'])){ $shortcode .= " data-cell-height='".$atts['cell-height']."'";} else {$shortcode .= " data-cell-height='200'";}
  if (isset($atts['cell-width'])){ $shortcode .= " data-cell-width='".$atts['cell-width']."'";} else {$shortcode .= " data-cell-width='200'";}
  if (isset($atts['text-align'])){ $shortcode .= " data-text-align='".$atts['text-align']."'";} else {$shortcode .= " data-text-align='center'";}
  $shortcode .= ">".$img_html."</div>";
  $cache_output = $shortcode;
  $cache_time = 1000;
  set_transient(md5('PREFIX'.serialize($atts)) , $cache_output, $cache_time * 60);
  return $shortcode;
}

function drstk_tile_shortcode_scripts() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_tiles') ) {
    wp_register_script('drstk_freewall',
        plugins_url('../assets/js/freewall/freewall.js', __FILE__),
        array( 'jquery' ));
    wp_enqueue_script('drstk_freewall');
    wp_register_script( 'drstk_tiles',
        plugins_url( '../assets/js/tiles.js', __FILE__ ),
        array( 'jquery' ));
    wp_enqueue_script('drstk_tiles');
	}
}
add_action( 'wp_enqueue_scripts', 'drstk_tile_shortcode_scripts');
