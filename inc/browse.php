<?php
add_action( 'wp_ajax_get_browse', 'browse_ajax_handler' ); //for auth users
add_action( 'wp_ajax_nopriv_get_browse', 'browse_ajax_handler' ); //for nonauth users
function browse_ajax_handler() {
  // Handle the ajax request
  check_ajax_referer( 'browse_drs' );
  $collection = drstk_get_pid();
  if ($collection == '' || $collection == NULL) {
      $data = array('error'=>'Please enter a correct collection or community id in order to configure the search and browse functionality. Please proceed to /wp-admin to enter a Collection id');
      $data = json_encode($data);
      wp_send_json($data);
  } elseif ($collection == "https://repository.library.northeastern.edu/collections/neu:1") {
    $data = array('error'=>'Please enter a correct collection or community id in order to configure the search and browse functionality. Please proceed to /wp-admin to enter a Collection id');
    $data = json_encode($data);
    wp_send_json($data);
  } else {
    $url = "https://repository.library.northeastern.edu/api/v1/search/".$collection."?";
    if ($_POST['params']['q'] ){
      $url .= "q=". urlencode(sanitize_text_field($_POST['params']['q']));
    }
    if ($_GET['q'] ){
      $url .= "q=". urlencode(sanitize_text_field($_GET['q']));
    }
    if ($_POST['params']['per_page']) {
      $url .= "&per_page=" . $_POST['params']['per_page'];
    }
    if ($_POST['params']['page']) {
      $url .= "&page=" . $_POST['params']['page'];
    }
    if ($_POST['params']['f']) {
      foreach($_POST['params']['f'] as $facet=>$facet_val){
        $url .= "&f[" . $facet . "][]=" . urlencode($facet_val);
      }
    }
    if ($_POST['params']['sort']) {
      $url .= "&sort=" . $_POST['params']['sort'];
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


add_action('wp_ajax_wp_search', 'ajax_wp_search');
add_action('wp_ajax_nopriv_wp_search', 'ajax_wp_search');
function ajax_wp_search(){
  global $wp_query;
  global $paged;
  $query_string = $_GET['query'];
  $paged = $_GET['page'];
  if (isset($_GET['query']) && $query_string != ''){
    $query_args = array( 's' => $query_string, 'post_type'=>array('post', 'page'), 'posts_per_page'=>3 , 'paged'=>$paged);
    $wp_query = new WP_Query( $query_args );
    $rel_query = relevanssi_do_query($wp_query);
    if ( $wp_query->have_posts() ) {
      		$wp_query->the_post();
          get_template_part( 'partials/content', 'normal' );
      } else {
        echo "No related content was found";
      }
  } else {
    echo "Please enter a search term to retreive related content";
  }
  wp_reset_postdata();
  die();
}
