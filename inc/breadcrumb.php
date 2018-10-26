<?php
add_action( 'wp_ajax_get_breadcrumb', 'breadcrumb_ajax_handler' ); //for auth users
add_action( 'wp_ajax_nopriv_get_breadcrumb', 'breadcrumb_ajax_handler' ); //for nonauth users
function breadcrumb_ajax_handler() {
  // Handle the ajax request
  $collection = drstk_get_pid();
  check_ajax_referer( 'breadcrumb_drs' );
    $url = "https://repository.library.northeastern.edu/api/v1/search/".$collection."?";
    if ($_POST['pid'] ){
      $url .= 'f["id"][]='.$_POST['pid'];
    }
    $data = get_response($url);
    $data = json_decode($data, true);
    $data['home_url'] = drstk_home_url();
    wp_send_json(json_encode($data));
    wp_die();
}
