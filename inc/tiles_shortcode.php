<?php
/* adds shortcode */
add_shortcode( 'drstk_tiles', 'drstk_tiles' );
function drstk_tiles( $atts ){
  global $errors;
  $cache = get_transient(md5('DRSTK'.serialize($atts)));

  if($cache) {
      return $cache;
  }
  $imgs = array_map('trim', explode(',', $atts['id']));
  $img_html = "";
  foreach($imgs as $img){
    $url = "https://repository.library.northeastern.edu/api/v1/files/" . $img . "?solr_only=true";
    $data = get_response($url);
    $data = json_decode($data);
    $data = $data->_source;
    $type = $atts['type'];
    if (!isset($data->error)){
      $pid = $data->id;
      if (isset($atts['image-size'])){
        $num = $atts['image-size']-1;
      } else {
        $num = 4;
      }
      $thumbnail = "http://repository.library.northeastern.edu".$data->fields_thumbnail_list_tesim[$num];
      if (isset($atts['metadata'])){
        $img_metadata = '';
        $metadata = explode(",",$atts['metadata']);
        foreach($metadata as $field){
           if (isset($data->$field)){
             $this_field = $data->$field;
            if (isset($this_field)){
              if (is_array($this_field)){
                foreach($this_field as $val){
                  $img_metadata .= $val ."<br/>";
                }
              } else {
                $img_metadata .= $this_field . "<br/>";
              }
            }
          }
        }
      }
      if ($type == 'pinterest-below' || $type == 'pinterest'){
        $img_html .= "<div class='brick'><a href='".drstk_home_url()."item/".$pid."'><img src='".$thumbnail."'></a><div class='info wp-caption-text'><a href='".drstk_home_url()."item/".$pid."'>".$img_metadata."</a>";
      }
      if ($type == 'pinterest-hover'){
        $img_html .= "<div class='brick brick-hover'><img src='".$thumbnail."' style='width:100%'><div class='info wp-caption-text'><a href='".drstk_home_url()."item/".$pid."'>".$img_metadata."</a>";
      }
      if ($type == 'even-row' || $type == 'square'){
        $img_html .= "<div class='cell' data-thumbnail='".$thumbnail."'><div class='info wp-caption-text'><a href='".drstk_home_url()."item/".$pid."'>".$img_metadata."</a>";
      }
      $img_html .= "<div class=\"hidden\">";
      foreach($data as $key=>$field){
        if ($key != "all_text_timv" && $key != "object_profile_ssm"){
          if (is_array($field)){
            foreach($field as $key=>$field_val){
              $img_html .= $field_val . "<br/>";
            }
          } else {
            $img_html .= $field . "<br/>";
          }
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
  set_transient(md5('DRSTK'.serialize($atts)) , $cache_output, $cache_time * 60);
  return $shortcode;
}

function drstk_tile_shortcode_scripts() {
	global $post, $wp_query, $DRS_PLUGIN_URL;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_tiles') && !isset($wp_query->query_vars['drstk_template_type']) ) {
    wp_register_script('drstk_freewall',
        $DRS_PLUGIN_URL . "/assets/js/freewall/freewall.js",
        array( 'jquery' ));
    wp_enqueue_script('drstk_freewall');
    wp_register_script( 'drstk_tiles',
        $DRS_PLUGIN_URL . '/assets/js/tiles.js',
        array( 'jquery' ));
    wp_enqueue_script('drstk_tiles');
	}
}
add_action( 'wp_enqueue_scripts', 'drstk_tile_shortcode_scripts');
