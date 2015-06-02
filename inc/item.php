<?php
add_action( 'wp_ajax_get_item', 'item_ajax_handler' ); //for auth users
add_action( 'wp_ajax_nopriv_get_item', 'item_ajax_handler' ); //for nonauth users
function item_ajax_handler() {
  // Handle the ajax request
  check_ajax_referer( 'item_drs' );
    $url = "http://cerberus.library.northeastern.edu/api/v1/files/";
    if ($_POST['pid'] ){
      $url .= $_POST['pid'];
    }
    $data = get_response($url);
    wp_send_json($data);
}
