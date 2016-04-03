<?php
/* adds shortcode */
add_shortcode( 'drstk_map', 'drstk_map' );
function drstk_map( $atts ){
  global $errors;
  //$cache = get_transient(md5('PREFIX'.serialize($atts)));

  /*if($cache) {
    return $cache;
  }*/
  $items = explode(", ",$atts['id']);
  $map_html = "";
  foreach($items as $item){
    $url = "https://repository.library.northeastern.edu/api/v1/files/" . $item;
    $data = get_response($url);
    $data = json_decode($data);
    if (!isset($data->error)){
      $pid = $data->pid;

      if(isset($data->coordinates)) {
        $coordinates = $data->coordinates;

      } else {
        $location = $data->geographic[0];
        $locationUrl = "http://maps.google.com/maps/api/geocode/json?address=" . urlencode($location);
        $locationData = get_response($locationUrl);
        $locationData = json_decode($locationData);
        if (!isset($locationData->error)) {
          $coordinates = $locationData->results[0]->geometry->location->lat . "," . $locationData->results[0]->geometry->location->lng;
        }
      }

      $title = $data->mods->Title[0];
      $map_html .= "<div class='coordinates' data-coordinates='".$coordinates."' data-title='".htmlspecialchars($title, ENT_QUOTES, 'UTF-8')."'>";
      $map_html .= "</div>";

    } else {
      $map_html = $errors['shortcodes']['fail'];
    }

  }
  $shortcode = "<div id='map'>".$map_html."</div>";
  $cache_output = $shortcode;
  $cache_time = 1000;
  set_transient(md5('PREFIX'.serialize($atts)) , $cache_output, $cache_time * 60);
  return $shortcode;
}

function drstk_map_shortcode_scripts() {
  global $post;
  if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_map') ) {
    wp_register_script('drstk_leaflet',
        plugins_url('../assets/js/leaflet/leaflet.js', __FILE__),
        array( 'jquery' ));
    wp_enqueue_script('drstk_leaflet');
    wp_register_script('drstk_leaflet_marker_cluster',
        plugins_url('../assets/js/leaflet/leaflet.markercluster-src.js', __FILE__),
        array('jquery', 'drstk_leaflet'));
    wp_enqueue_script('drstk_leaflet_marker_cluster');
    wp_register_style('drstk_leaflet_css',
        plugins_url('../assets/css/leaflet.css', __FILE__));
    wp_enqueue_style('drstk_leaflet_css');
    wp_register_script( 'drstk_map',
        plugins_url( '../assets/js/map.js', __FILE__ ),
        array( 'jquery' ));
    wp_enqueue_script('drstk_map');
  }
}
add_action( 'wp_enqueue_scripts', 'drstk_map_shortcode_scripts');
