<?php
/* adds shortcode */
add_shortcode( 'drstk_item', 'drstk_item' );
add_shortcode( 'drstk_single', 'drstk_item' );
function drstk_item( $atts ){
  $cache = get_transient(md5('DRSTK'.serialize($atts)));

  if($cache) {
      return $cache;
  }
  $repo = drstk_get_repo_from_pid($atts['id']);
  if ($repo != "drs"){$pid = explode(":",$atts['id']); $pid = $pid[1];} else {$pid = $atts['id'];}
  if (isset($atts['image-size'])){
    $num = $atts['image-size']-1;
  } else {
    $num = 3;
  }
  if ($repo == "drs"){
    $url = "https://repository.library.northeastern.edu/api/v1/files/" . $pid;
    $data = get_response($url);
    $data = json_decode($data);
    $thumbnail = $data->thumbnails[$num];
    $master = $data->thumbnails[4];
    foreach($data->content_objects as $key=>$val){
      if ($val == 'Large Image'){
        $master = $key;
      }
    }
  }
  if ($repo == "wp"){
    $post = get_post($pid);
    $data = new StdClass;
    $data->canonical_object = new StdClass;
    $url = $post->guid;
    if (strpos($post->post_mime_type, "audio") !== false){
      $type = "Audio File";
    } else if (strpos($post->post_mime_type, "video") !== false){
      $type = "Video File";
    } else {
      $type = "Master Image";
    }
    $data->canonical_object->$url = $type;
    $meta = wp_get_attachment_metadata($pid); //get sizes
    $thumb_base = wp_get_attachment_thumb_url($pid);
    if (isset($meta['sizes'])){
      $thumb_base = explode("/",$thumb_base);
      $arr = array_pop($thumb_base);
      $thumb_base = implode("/", $thumb_base);
      if ($num == 1){ $thumbnail = $thumb_base."/".$meta['sizes']['thumbnail']['file'];}
      if ($num == 2){ $thumbnail = $thumb_base."/".$meta['sizes']['medium']['file'];}
      if ($num == 3){ $thumbnail = $thumb_base."/".$meta['sizes']['medium']['file'];}
      if ($num == 4){
       if (isset($meta['sizes']['large'])){
         $thumbnail = $thumb_base."/".$meta['sizes']['large']['file'];
       } else {
         $thumbnail = drstk_home_url()."/wp-content/uploads/".$meta['file'];
       }
      }
      if ($num == 5){
       if (isset($meta['sizes']['large'])){
         $thumbnail = $thumb_base."/".$meta['sizes']['large']['file'];
       } else {
         $thumbnail = drstk_home_url()."/wp-content/uploads/".$meta['file'];
       }
      }
    } else {
      $thumbnail = null;
    }
    $master = $post->guid;
    $data->mods = new StdClass;
    $data->mods->title = array($post->post_title);
    $data->mods->caption = array($post->post_excerpt);
    $data->id = $post->ID;
  }
  if ($repo == "dpla"){
    $dpla = get_response("http://api.dp.la/v2/items/".$pid."?api_key=b0ff9dc35cb32dec446bd32dd3b1feb7");
    $dpla = json_decode($dpla);
    if (isset($dpla->docs[0]->object)){
      $url = $dpla->docs[0]->object;
    } else {
      $url = "https://dp.la/info/wp-content/themes/berkman_custom_dpla/images/logo.png";
    }
    $data = new StdClass;
    $data->canonical_object = new StdClass;
    $type = "Master Image";
    $data->canonical_object->$url = $type;
    $thumbnail = $url;
    $master = null;
    $data->mods = new StdClass;
    $title = array($dpla->docs[0]->sourceResource->title);
    $data->mods->Title = $title;
    if (isset($dpla->docs[0]->sourceResource->description)){
      $description = $dpla->docs[0]->sourceResource->description;
    } else {
      $description = "";
    }
    $abs = "Abstract/Description";
    $data->mods->$abs = $description;
    if (isset($dpla->docs[0]->sourceResource->creator)){
      $data->mods->Creator = is_array($dpla->docs[0]->sourceResource->creator) ? $dpla->docs[0]->sourceResource->creator : array($dpla->docs[0]->sourceResource->creator);
    }
    $dat = "Date Created";
    $data->mods->$dat = array($dpla->docs[0]->sourceResource->date->displayDate);
    $data->id = $pid;
  }



  $html = "<div class='drs-item'>";

  $jwplayer = false; // note: unneeded if there is only one canonical_object type

  if (isset($atts['display-video']) && isset($data->canonical_object)){
    foreach($data->canonical_object as $key=>$val){
      if (($val == 'Video File' || $val == 'Audio File') && $atts['display-video'] == "true" ){
        if ($repo == "wp"){
          $html .= do_shortcode('[video src="'.$master.'"]');
        } else {
          $html .= insert_jwplayer($key, $val, $data, $thumbnail);
        }
        $jwplayer = true;
      }
    }
  }

  if (!$jwplayer) {
    if (isset($atts['display-issuu']) && isset($data->mods->Location) && strpos($data->mods->Location[0], "issuu") !== FALSE){
      $location_href = explode("'", strval(htmlentities($data->mods->Location[0])));
      if (count($location_href) == 1){
        $location_href = explode('"', strval(htmlentities($data->mods->Location[0])));
      }
      $issu_id = explode('?',$location_href[1]);
      $issu_id = explode('=',$issu_id[1]);
      $issu_id = $issu_id[1];
      $html .= '<div data-configid="'.$issu_id.'" style="width:100%; height:500px;" class="issuuembed"></div><script type="text/javascript" src="//e.issuu.com/embed.js" async="true"></script>';
      $html .= "<a href='".drstk_home_url()."item/".$atts['id']."'>View Item Details</a>";
    } else {
      $html .= "<a href='".drstk_home_url()."item/".$atts['id']."'><img class='drs-item-img' id='".$atts['id']."-img' src='".$thumbnail."'";

      if (isset($atts['align'])){
        $html .= " data-align='".$atts['align']."'";
      }
      if (isset($atts['float'])){
        $html .= " data-float='".$atts['float']."'";
      }

      if (isset($atts['zoom']) && $atts['zoom'] == 'on' && $master != null && check_master($master) == true){
        $html .= " data-zoom-image='".$master."' data-zoom='on'";
        if (isset($atts['zoom_position'])){
          $html .= " data-zoom-position='".$atts['zoom_position']."'";
        }
      }

      $html .= "/></a>";
    }
  }

  // start item meta data
  $img_metadata = "";
  if (isset($atts['metadata'])){
    $metadata = explode(",",$atts['metadata']);
    foreach($metadata as $field){
      $this_field = $data->mods->$field;
      if (isset($this_field)){
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
    $html .= "<div class='wp-caption-text drstk-caption'";
    if (isset($atts['caption-align'])){
      $html .= " data-caption-align='".$atts['caption-align']."'";
    }
    if (isset($atts['caption-position'])){
      $html .= " data-caption-position='".$atts['caption-position']."'";
    }
    $html .= "><a href='".drstk_home_url()."item/".$atts['id']."'>".$img_metadata."</a></div>";
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

add_action( 'wp_ajax_get_item_solr_admin', 'item_solr_admin_ajax_handler' ); //for auth users

function item_solr_admin_ajax_handler() {
  $data = array();
  // Handle the ajax request
  check_ajax_referer( 'item_admin_nonce' );
  $url = "https://repository.library.northeastern.edu/api/v1/files/" . $_POST['pid'] . "?solr_only=true";
  $data = get_response($url);
  $data = json_decode($data);
  wp_send_json(json_encode($data));
  wp_die();
}

function drstk_item_shortcode_scripts() {
  global $post, $VERSION, $wp_query, $DRS_PLUGIN_URL;
  if( is_a( $post, 'WP_Post' ) && (has_shortcode( $post->post_content, 'drstk_item') || has_shortcode( $post->post_content, 'drstk_single')) && !isset($wp_query->query_vars['drstk_template_type']) ) {
    wp_register_script('drstk_elevatezoom', $DRS_PLUGIN_URL.'/assets/js/elevatezoom/jquery.elevateZoom-3.0.8.min.js', array( 'jquery' ));
    wp_enqueue_script('drstk_elevatezoom');
    wp_register_script( 'drstk_zoom', $DRS_PLUGIN_URL . '/assets/js/zoom.js', array( 'jquery' ));
    wp_enqueue_script('drstk_zoom');
    wp_register_script('drstk_jwplayer', $DRS_PLUGIN_URL.'/assets/js/jwplayer/jwplayer.js', array(), $VERSION, false );
    wp_enqueue_script('drstk_jwplayer');
  }
}

function check_master($master){
  // Create a cURL handle
  $ch = curl_init($master);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  // Execute
  curl_exec($ch);
  // Check HTTP status code
  if (!curl_errno($ch)) {
    switch ($http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE)) {
      case 200:  # OK
        return true;
        break;
      default:
        return false;
    }
  }
  curl_close($ch);
}
add_action( 'wp_enqueue_scripts', 'drstk_item_shortcode_scripts');
