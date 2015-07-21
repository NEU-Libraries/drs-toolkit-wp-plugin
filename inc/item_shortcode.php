<?php
/* side box content for video playlist shortcode */
function drstk_add_item( $post ) {
    wp_nonce_field( 'drstk_add_item', 'drstk_add_item_nonce' );
    echo '<label for="drstk_item_url">Item URL: </label><input type="text" id="drstk_item_url" name="drstk_item_url" /><br/>';
    echo '<label for="drstk_item_zoom">Enable Zoom</label><input type="checkbox" id="drstk_item_zoom" name="drstk_item_zoom" /><br/>';
    echo '<a href="#" id="drstk_get_item_meta" class="button" title="Get Metadata">Get Metadata</a><br/>';
    echo '<div class="item-metadata"></div>';
    echo '<a href="#" id="drstk_item_insert_shortcode" class="button" title="Insert shortcode">Insert shortcode</a>';
}

/* adds shortcode */
add_shortcode( 'drstk_item', 'drstk_item' );
function drstk_item( $atts ){
  // echo "https://repository.library.northeastern.edu/api/v1/files/neu:5m60qs151";
  $url = "https://repository.library.northeastern.edu/api/v1/files/" . $atts['id'];
  // echo $url;
  $data = get_response($url);
  $data = json_decode($data);
  // return print_r($data);
  $thumbnail = end($data->thumbnails);
  $img_html = "<img class='drs-item-img' id='".$atts['id']."-img' src='".$thumbnail."'";
  if (isset($atts['zoom']) && $atts['zoom'] == 'on'){
    if ($data->canonical_object[0][1] == 'Master Image'){
      $master = $data->canonical_object[0][0];
    }
    $img_html .= " data-zoom-image='".$master."' data-zoom='on'";
  }
  $img_metadata = "";
  // $img_metadata = "<pre>".print_r($atts)."</pre>";
  foreach($atts as $attr => $val){
    if ($attr != 'zoom' && $attr != 'id'){
      $img_metadata .= $val . "<br/>";
    }
  }
  $img_html .= "/>";
  $img_html .= "<div class='wp-caption-text drstk-caption'>".$img_metadata."</div>";
  return $img_html;
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
}

function drstk_item_shortcode_scripts() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_item') ) {
    wp_register_script('drstk_elevatezoom',
        plugins_url('/assets/js/elevatezoom/jquery.elevateZoom-3.0.8.min.js', __FILE__),
        array( 'jquery' ));
    wp_enqueue_script('drstk_elevatezoom');
    wp_enqueue_script( 'drstk_zoom',
        plugins_url( '/assets/js/zoom.js', __FILE__ ),
        array( 'jquery' )
    );
	}
}
add_action( 'wp_enqueue_scripts', 'drstk_item_shortcode_scripts');
