<?php
add_action( 'wp_ajax_get_breadcrumb', 'breadcrumb_ajax_handler' ); //for auth users
add_action( 'wp_ajax_nopriv_get_breadcrumb', 'breadcrumb_ajax_handler' ); //for nonauth users
function breadcrumb_ajax_handler() {
  // Handle the ajax request
  $collection = get_option('drstk_collection');
  check_ajax_referer( 'breadcrumb_drs' );
    $url = "http://cerberus.library.northeastern.edu/api/v1/search/".$collection."?";
    if ($_POST['pid'] ){
      $url .= 'f["id"][]='.$_POST['pid'];
    }
    $data = get_response($url);
    wp_send_json($data);
}
