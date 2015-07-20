<?php
/* side box content for video playlist shortcode */
function drstk_add_item( $post ) {
    $post_id = $post->ID;
    wp_nonce_field( 'drstk_add_item', 'drstk_add_item_nonce' );
    echo '<label for="drstk_item_url">Item URL: </label><input type="text" id="drstk_item_url" name="drstk_item_url" /><br/>';
    echo '<label for="drstk_item_zoom">Enable Zoom</label><input type="checkbox" id="drstk_item_zoom" name="drstk_item_zoom" />';
 ?>   <a href="#" id="drstk_item_insert_shortcode" class="button" title="Insert shortcode">Insert shortcode</a> <?php

}



/* adds shortcode */
add_shortcode( 'drstk_item', 'drstk_item' );
function drstk_item( $atts ){
  // echo "http://cerberus.library.northeastern.edu/api/v1/files/neu:5m60qs151";
  $url = "http://cerberus.library.northeastern.edu/api/v1/files/" . $atts['id'];
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
    $img_html .= " data-zoom-image='".$master."' data-zoom='on'/>";
  } else {
    $img_html .= "/>";
  }
  return $img_html;
}
