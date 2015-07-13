<?php
add_action( 'wp_ajax_get_item', 'item_ajax_handler' ); //for auth users
add_action( 'wp_ajax_nopriv_get_item', 'item_ajax_handler' ); //for nonauth users
function item_ajax_handler() {
  // Handle the ajax request
  check_ajax_referer( 'item_drs' );
    $url = "http://repository.library.northeastern.edu/api/v1/files/";
    if ($_POST['pid'] ){
      $url .= $_POST['pid'];
    }
    $data = get_response($url);
    $data = json_decode($data, true);
    if ($data['canonical_object'][0][1] == 'Video File' || $data['canonical_object'][0][1] == 'Audio File'){
      $data['av_pid'] = $data['canonical_object'][0][0];
      $data['av_pid'] = explode("/", $data['av_pid']);
      $data['av_pid'] = end($data['av_pid']);
      $data['encoded_av_pid'] = str_replace(':','%3A', $data['av_pid']);
      $data['av_dir'] = substr(md5("info:fedora/".$data['av_pid']."/content/content.0"), 0, 2);
      $data['av_type'] = $data['canonical_object'][0][0];
      foreach ($data['content_objects'] as $key=>$val){
        if ($val[1] == 'Master Image'){
          $data['av_poster'] = $val[0];
        }
      }
      if ($data['canonical_object'][0][1] == 'Video File'){
        $data['av_provider'] = 'video';
        $data['av_type'] = "MP4";
      }
      if ($data['canonical_object'][0][1] == 'Audio File'){
        $data['av_provider'] = 'sound';
        $data['av_type'] = "MP3";
      }
    }


    wp_send_json(json_encode($data));
}
