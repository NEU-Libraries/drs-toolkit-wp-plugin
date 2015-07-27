<?php
/* side box content for video playlist shortcode */

add_action( 'wp_ajax_get_item_code', 'drstk_add_item' ); //for auth users
function drstk_add_item() {
    // wp_nonce_field( 'drstk_add_item', 'drstk_add_item_nonce' );
    check_ajax_referer( 'item_ajax_nonce' );
    $col_pid = drstk_get_pid();
    $collection = array();
    $url = "http://cerberus.library.northeastern.edu/api/v1/export/".$col_pid."?per_page=2&page=1";
    $drs_data = get_response($url);
    $json = json_decode($drs_data);
    $data = '';
    if ($json->error) {
      $data = "There was an error: " . $json->error;
      wp_send_json($data);
      return;
    }
    if ($json->pagination->table->total_count > 0){
      for ($x = 1; $x <= $json->pagination->table->num_pages; $x++) {
        $url = "http://cerberus.library.northeastern.edu/api/v1/export/".$col_pid."?per_page=10&page=".$x;
        $drs_data = get_response($url);
        $json = json_decode($drs_data);
        foreach ($json->items as $item){
            $img = array(
              'pid' => $item->pid,
              'thumbnail' => $item->thumbnails[0],
              'title' => $item->mods->Title[0],
            );
            $collection[] = $img;
        }
      }
    }
 $data .= '<h4>Item</h4><a href="#" id="drstk_insert_item" class="button" title="Insert shortcode">Insert shortcode</a>';
 $data .= '<label for="drsitem-zoom"><input id="drsitem-zoom" name="drsitem-zoom" value="yes" type="checkbox" />Enable zoom</label>';
 $data .= '<div class="item-metadata"></div>';
    $data .= '<ol id="sortable-item-list">';
    foreach ($collection as $key => $doc) {
        $data .= '<li style="display:inline-block;padding:10px;">';
        $data .= '<label for="drsitem-'. $key. '"><img src="'. $doc['thumbnail']. '" width="150" /><br/>';
        $data .= '<input id="drsitem-'. $key. '" type="checkbox" class="drstk-include-item" value="'.$doc['pid'].'" />';
        $data .= '<span style="width:100px;display:inline-block">'.$doc['title'].'</span></label>';
        $data .= '</li>';
    }
    $data .= '</ol>';
    wp_send_json($data);
    return;
}

/* adds shortcode */
add_shortcode( 'drstk_item', 'drstk_item' );
function drstk_item( $atts ){
  $url = "http://cerberus.library.northeastern.edu/api/v1/files/" . $atts['id'];
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
  if (isset($atts['metadata'])){
    $metadata = explode(",",$atts['metadata']);
    foreach($metadata as $field){
      $this_field = $data->mods->$field;
      $img_metadata .= $this_field[0] . "<br/>";
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
  $url = "http://cerberus.library.northeastern.edu/api/v1/files/" . $_POST['pid'];
  $data = get_response($url);
  $data = json_decode($data);
  wp_send_json(json_encode($data));
}

function drstk_item_shortcode_scripts() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_item') ) {
    wp_register_script('drstk_elevatezoom',
        plugins_url('../assets/js/elevatezoom/jquery.elevateZoom-3.0.8.min.js', __FILE__),
        array( 'jquery' ));
    wp_enqueue_script('drstk_elevatezoom');
    wp_enqueue_script( 'drstk_zoom',
        plugins_url( '../assets/js/zoom.js', __FILE__ ),
        array( 'jquery' )
    );
	}
}
add_action( 'wp_enqueue_scripts', 'drstk_item_shortcode_scripts');
