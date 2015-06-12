<?php
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

add_action( 'wp_ajax_get_import', 'import_ajax_handler' ); //for auth users
function import_ajax_handler() {
  global $data;
  $data = array();
  $data['count'] = 0;
  $data['existing_count'] = 0;
  $data['objects'] = array();
  // global $email;
  // Handle the ajax request
  check_ajax_referer( 'import_drs' );
  $collection_pid = $_POST['pid'];
  $url = "http://cerberus.library.northeastern.edu/api/v1/export/".$collection_pid."?per_page=2&page=1";
  $drs_data = get_response($url);
  $json = json_decode($drs_data);
  // $email = '';
  if ($json->pagination->table->total_count > 0){
    $email .= $json->pagination->table->total_count;
    for ($x = 1; $x <= $json->pagination->table->num_pages; $x++) {
      $url = "http://cerberus.library.northeastern.edu/api/v1/export/".$collection_pid."?per_page=2&page=".$x;
      $drs_data = get_response($url);
      $json = json_decode($drs_data);
      drstk_get_image_data($json);
    }
  }
  wp_send_json(json_encode($data));
}

function drstk_get_image_data($json){
  global $data;
  foreach($json->items as $doc) {
    $title = $doc->mods->Title[0];
    $core_pid = $doc->pid;
    //$data['objects'][$core_pid]['title'] = $title;
    //if its an image just send the master image
    if ($doc->canonical_object[0][1] == 'Master Image'){
      $image_url = $doc->canonical_object[0][0];
      //$data['objects'][$core_pid]['canonical_object'] = $image_url;
      $image_url_backup = $doc->thumbnails[4];
    } else {
      //if its not an image send a thumbnail
      $image_url = $doc->thumbnails[4];
      //$data['objects'][$core_pid]['thumbnail'] = $image_url;
      $image_url_backup = NULL;
    }
    if ($doc->mods->Creator){
      $creator = $doc->mods->Creator[0];
      //$data['objects'][$core_pid]['creator'] = $creator;
    } else {
      $creator = NULL;
    }
    if ($doc->mods->{'Date created'}[0]){
      $date = $doc->mods->{'Date created'}[0];
      //$data['objects'][$core_pid]['date_created'] = $date;
    } else if ($doc->mods->{'Copyright date'}[0]){
      $date = $doc->mods->{'Copyright date'}[0];
      //$data['objects'][$core_pid]['date_created'] = $date;
    } else {
      $date = NULL;
    }
    if ($doc->mods->{'Abstract/Description'}[0]){
      $description = $doc->mods->{'Abstract/Description'}[0];
      //$data['objects'][$core_pid]['description'] = $description;
    } else {
      $description = NULL;
    }
    $metadata = $doc->mods;
    drstk_process_image($image_url, $title, $creator, $date, $description, $metadata, $core_pid, $image_url_backup);
  }
}


  function drstk_process_image($image_url, $title, $creator = NULL, $date = NULL, $description = NULL, $metadata, $core_pid, $image_url_backup = NULL){
    global $data;
    //global $email;
    $query_images_args = array(
       'post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => -1,
     );
    $query_images = new WP_Query( $query_images_args );
    $images = array();
    foreach ( $query_images->posts as $image) {
       $images[]= basename(wp_get_attachment_url( $image->ID ));
    }
    $data['images'] = $images;

    $pid = explode("/", $image_url);
    $pid = explode("?", end($pid));
    $pid = str_replace(":","",$pid[0]);
    if (!in_array($pid, $images)){
      $data['count'] = $data['count']+1;
      //$data['objects'][$core_pid]['image_status'] = "is not in images";
      $tmp = download_url( $image_url );
      $post_id = 0;
      $file_array = array();
      // Set variables for storage
      $file_array['name'] = $pid;
      $file_array['type'] = mime_content_type($tmp);
      $file_array['error'] = 0;
      $file_array['tmp_name'] = $tmp;
      $file_array['size'] = filesize($tmp);
      //$data['objects'][$core_pid]['file_info'] = $file_array;

      // If error storing temporarily, unlink
      if ( is_wp_error( $tmp ) ) {
        @unlink($file_array['tmp_name']);
        $file_array['tmp_name'] = '';
        return $tmp;
      }
      //need to check for files that aren't jpgs becuase wp doesn't like them
       if ($file_array['type']!='image/jpeg'){
        $tmp = download_url($image_url_backup);
        $file_array['tmp_name'] = $tmp;
        $file_array['type'] = mime_content_type($tmp);
        $file_array['size'] = filesize($tmp);
      }

      // do the validation and storage stuff
      $post_data = array('post_title'=>$title,'post_name'=>$title, 'post_excerpt'=>$description);
      $id = media_handle_sideload( $file_array, 0, $description, $post_data);

      // If error storing permanently, unlink
      if ( is_wp_error($id) ) {
        @unlink($file_array['tmp_name']);
        return $id;
      }

      $src = wp_get_attachment_url( $id );
      $image_id = drstk_get_image_id($src);

      //$data['objects'][$core_pid]['image_id'] = $image_id;
      update_post_meta($image_id, 'drstk-drs-metadata', $metadata);
      if ($creator != NULL){
        update_post_meta($image_id, 'drstk-creator', $creator);
      }
      if ($date != NULL){
        update_post_meta($image_id, 'drstk-date-created', $date);
      }
      update_post_meta($image_id, 'drstk-pid', $core_pid);
    } else {
      //have $pid -> need $image_id
      $image_id = drstk_get_image_by_pid($core_pid);
      //$data['objects'][$core_pid]['image_id'] = $image_id;
      //$data['objects'][$core_pid]['image_status'] = "is already in images";
      $data['existing_count'] = $data['existing_count']+1;
      if ($creator != get_post_meta($image_id, 'drstk-creator', true)) {
        $data['objects'][$core_pid]['creator'] = array();
        $data['objects'][$core_pid]['creator']['wp]'] = get_post_meta($image_id, 'drstk-creator', true);
        $data['objects'][$core_pid]['creator']['drs'] = $creator;
      }
      if ($title != get_the_title($image_id)) {
        $data['objects'][$core_pid]['title'] = array();
        $data['objects'][$core_pid]['title']['wp'] = get_the_title($image_id);
        $data['objects'][$core_pid]['title']['drs'] = $title;
      }
      if ($date != get_post_meta($image_id, 'drstk-date-created', true)){
        $data['objects'][$core_pid]['date_created'] = array();
        $data['objects'][$core_pid]['date_created']['wp'] = get_post_meta($image_id, 'drstk-date-created', true);
        $data['objects'][$core_pid]['date_created']['drs'] = $date;
      }
      if ($description != get_the_excerpt($image_id)){
        $data['objects'][$core_pid]['caption'] = array();
        $data['objects'][$core_pid]['caption']['wp'] = get_the_excerpt($image_id);
        $data['objects'][$core_pid]['caption']['drs'] = $description;
      }
      update_post_meta($image_id, 'drstk-drs-metadata', $metadata);
    }
  }

  function drstk_get_image_id($image_url) {
  	global $wpdb;
  	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
          return $attachment[0];
  }

  function drstk_get_image_by_pid($core_pid){
    global $wpdb;
		$querystr = "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'drstk-pid' AND meta_value = '".$core_pid."';";
		$postid = $wpdb->get_col($wpdb->prepare($querystr, $core_pid));
    return $postid[0];
  }


  add_action( 'wp_ajax_get_import_data', 'import_data_ajax_handler' ); //for auth users

  function import_data_ajax_handler(){
    check_ajax_referer( 'import_drs' );

    $collection_pid = get_option('drstk_collection');
    $url = "http://cerberus.library.northeastern.edu/api/v1/search/".$collection_pid."?f['id'][]=".$_POST['pid'];
    $drs_data = get_response($url);
    $json = json_decode($drs_data);
    wp_send_json(json_encode($data));
  }
