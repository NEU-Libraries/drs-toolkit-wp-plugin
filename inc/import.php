<?php
add_action( 'wp_ajax_get_import', 'import_ajax_handler' ); //for auth users
add_action( 'wp_ajax_nopriv_get_import', 'import_ajax_handler' ); //for nonauth users
function import_ajax_handler() {
  // Handle the ajax request
  check_ajax_referer( 'import_drs' );
    $url = "http://cerberus.library.northeastern.edu/api/v1/search/";
    if ($_POST['pid'] ){
      $url .= $_POST['pid'];
    }
    $data = get_response($url);
    //this should process the images - old functions are below - needs major refactor - waiting on export functionality from API

    wp_send_json($data);
}



 function drstk_get_media_images($collection_pid) {
   //echo $collection_pid;

   //first we get the existing images
   $query_images_args = array(
      'post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => -1,
    );
   $query_images = new WP_Query( $query_images_args );
   $images = array();
   foreach ( $query_images->posts as $image) {
      $images[]= basename(wp_get_attachment_url( $image->ID ));
   }
    //print_r($images);
    //get all the core_files from the drs
    $drs_url = "http://cerberus.library.northeastern.edu/api/v1/search/".$collection_pid."?per_page=20";
    //will there be some kind of helper with the api to get them all back at once? don't want to waste time writing a function that would loop through the pages of results
    $json = get_response($drs_url);
    //print_r($json);
    $json = json_decode($json);
    if ($json->response->response->numFound > 0) {
      foreach($json->response->response->docs as $doc) {
        $title = $doc->mods->title;
        //if its an image just send the master image
        if ($doc->Format == 'Image'){
          $url = $doc->canonical_object;
        } else {
          //if its not an image send a thumbnail
          $url = $doc->thumbnails[4];
        }
        //assign $title, $creator, $date, $description
        if ($doc->mods->creator){
          $creator = $doc->mods->creator;
        }
        $date = $doc->mods['Copyright date'];
        $description = $doc->mods['Abstract/Description'];
        $metadata = $doc->mods;
        $core_pid = $doc->pid
        echo $url;
        drstk_process_image($url, $images, $title, $creator, $date, $description, $metadata, $core_pid);
      }
    }
  }

  function drstk_process_image($url, $images, $title, $creator, $date, $description, $metadata, $core_pid){
    $pid = explode("/", $url);
    $pid = explode("?", end($pid));
    $pid = str_replace(":","",$pid[0]);
    echo $pid;
    if (!in_array($pid, $images)){
      $tmp = download_url( $url );
      $post_id = 0;
      $file_array = array();

      // Set variables for storage
      $file_array['name'] = $pid;
      $file_array['type'] = 'image/jpeg';
      $file_array['error'] = 0;
      $file_array['tmp_name'] = $tmp;
      $file_array['size'] = filesize($tmp);
      //print_r($file_array);

      // If error storing temporarily, unlink
      if ( is_wp_error( $tmp ) ) {
        @unlink($file_array['tmp_name']);
        $file_array['tmp_name'] = '';
      }

      // do the validation and storage stuff
      $id = media_handle_sideload( $file_array, 0);
      echo $id . "<br/>";

      // If error storing permanently, unlink
      if ( is_wp_error($id) ) {
        @unlink($file_array['tmp_name']);
        return $id;
      }

      $src = wp_get_attachment_url( $id );
      $image_id = drstk_get_image_id($src);
      echo $src . "<br/>";
      echo $image_id . "<br/>";
      $image_post = array(
        'ID' => $image_id,
        'post_title' => $title,
        'post_excerpt' => $description,
      );
      wp_update_post($image_post);
      update_post_meta($image_id, 'drstk-drs-metadata', $metadata);
      update_post_meta($image_id, 'drstk-creator', $creator);
      update_post_meta($image_id, 'drstk-date-created', $date);
      update_post_meta($image_id, 'drstk-pid', $core_pid);
      //set core file pid so we can redirect the link
      //permalink redirect from image url to item level page?

    }
  }

  function drstk_get_image_id($image_url) {
  	global $wpdb;
  	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
          return $attachment[0];
  }
