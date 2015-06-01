<?php
add_action( 'wp_ajax_get_browse', 'my_ajax_handler' ); //for auth users
add_action( 'wp_ajax_nopriv_get_browse', 'my_ajax_handler' ); //for nonauth users
function my_ajax_handler() {
  // Handle the ajax request
  check_ajax_referer( 'browse_drs' );
  $collection = get_option('drstk_collection');
  if ($collection == '' || $collection == NULL) {
      $data = array('error'=>'Please enter a correct collection or community id in order to configure the search and browse functionality. Please proceed to /wp-admin to enter a Collection id');
      $data = json_encode($data);
      wp_send_json($data);
  } elseif ($collection == "neu:1") {
    $data = array('error'=>'Please enter a correct collection or community id in order to configure the search and browse functionality. Please proceed to /wp-admin to enter a Collection id');
    $data = json_encode($data);
    wp_send_json($data);
  } else {
    $url = "http://cerberus.library.northeastern.edu/api/v1/search/".$collection."?";
    if ($_POST['query'] ){
      $url .= "q=". $_POST['query'];
    }
    if ($_POST['per_page']) {
      $url .= "&per_page=" . $_POST['per_page'];
    }
    if ($_POST['page']) {
      $url .= "&page=" . $_POST['page'];
    }
    if ($_POST['f']) {
      foreach($_POST['f'] as $facet=>$facet_val){
        $url .= "&f[" . $facet . "][]=" . $facet_val;
      }
    }
    if ($_POST['sort']) {
      $url .= "&sort=" . $_POST['sort'];
    }
    $data = get_response($url);

    wp_send_json($data);
  }
}


/**
 * Basic curl response mechanism.
 */
 function get_response( $url ) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

    // if it returns a 403 it will return no $output
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
