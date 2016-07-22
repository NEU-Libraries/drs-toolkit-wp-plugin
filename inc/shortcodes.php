<?php
//allows modals in admin

function add_drs_button() {
  echo '<a href="#" id="drs-backbone_modal" class="button" title="Add Toolkit Shortcodes">Add Toolkit Shortcodes</a>';
}
add_action('media_buttons', 'add_drs_button', 1000);


/*enques extra js*/
function drstk_enqueue_page_scripts( $hook ) {
  global $errors, $DRS_PLUGIN_PATH, $DRS_PLUGIN_URL;
    if ($hook == 'post.php' || $hook == 'post-new.php') {

      include $DRS_PLUGIN_PATH.'templates/modal.php';
      wp_enqueue_script( 'drstk_admin_js', $DRS_PLUGIN_URL . '/assets/js/admin.js', array(
        'jquery',
        'backbone',
        'underscore',
        'wp-util',
        'jquery-ui-sortable'
      ) );
      wp_localize_script( 'drstk_admin_js', 'drstk_backbone_modal_l10n',
        array(
          'replace_message' => __( 'Choose a method of embedding DRS and/or DPLA item(s).<br/><br/><table><tr><td><a class="button" href="#one">Single Item</a></td><td><a class="button" href="#four">Media Playlist</a></td></tr><tr><td><a class="button" href="#two">Tile Gallery</a></td><td><a class="button" href="#five">Map</a></td></tr><tr><td><a class="button" href="#three">Gallery Slider</a></td><td><a class="button" href="#six">Timeline</a></td></tr></table>', 'backbone_modal' )
        ) );
      wp_enqueue_style( 'drstk_admin_js', $DRS_PLUGIN_URL . '/assets/css/admin.css' );

   //this creates a unique nonce to pass back and forth from js/php to protect
   $item_admin_nonce = wp_create_nonce( 'item_admin_nonce' );
   //this allows an ajax call from admin.js

   wp_localize_script( 'drstk_admin_js', 'item_admin_obj', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'item_admin_nonce'    => $item_admin_nonce,
      'pid' => '',
      'errors' => json_encode($errors),
   ) );

   $drs_ajax_nonce = wp_create_nonce( 'drs_ajax_nonce');
   wp_localize_script( 'drstk_admin_js', 'drs_ajax_obj', array(
     'ajax_url' => admin_url('admin-ajax.php'),
     'drs_ajax_nonce' => $drs_ajax_nonce,
   ));

   $dpla_ajax_nonce = wp_create_nonce( 'dpla_ajax_nonce');
   wp_localize_script( 'drstk_admin_js', 'dpla_ajax_obj', array(
     'ajax_url' => admin_url('admin-ajax.php'),
     'dpla_ajax_nonce' => $dpla_ajax_nonce,
   ));


 } else {
   return;
 }
}
add_action('admin_enqueue_scripts', 'drstk_enqueue_page_scripts');
add_action( 'wp_ajax_get_drs_code', 'drstk_get_drs_items' ); //for auth users

function drstk_get_drs_items(){
  check_ajax_referer( 'drs_ajax_nonce' );
  $col_pid = drstk_get_pid();
    $url = "https://repository.library.northeastern.edu/api/v1/search/".$col_pid."?per_page=20";
    if (isset($_POST['params']['q'])){
      $url .= "&q=". urlencode(sanitize_text_field($_POST['params']['q']));
      if (isset($_POST['params']['avfilter'])){
        $url .= 'AND%20canonical_class_tesim%3A"AudioFile"%20OR%20canonical_class_tesim%3A"VideoFile"';
      }
    } else {
      if (isset($_POST['params']['avfilter'])){
        $url .= '&q=%20canonical_class_tesim%3A"AudioFile"%20OR%20canonical_class_tesim%3A"VideoFile"';
      }
    }

    if (isset($_POST['params']['page'])) {
      $url .= "&page=" . $_POST['params']['page'];
    }
    $data = get_response($url);
    $json = json_decode($data);
    if (isset($json->error)) {
      wp_send_json(json_encode( "There was an error: " . $json->error));
      wp_die();
      return;
    }
    wp_send_json($data);
    wp_die();
}

add_action( 'wp_ajax_get_dpla_code', 'drstk_get_dpla_items' ); //for auth users

function drstk_get_dpla_items(){
  check_ajax_referer( 'dpla_ajax_nonce' );
    $url = "http://api.dp.la/v2/items?api_key=b0ff9dc35cb32dec446bd32dd3b1feb7&page_size=20";
    if (isset($_POST['params']['q'])){
      $url .= "&q=". urlencode(sanitize_text_field($_POST['params']['q']));
    }
    // if (isset($_POST['params']['avfilter'])){
    //   $url .= '&sourceResource.type=%22moving%20image%22+OR+%22sound%22';
    // } //This won't work because there are no links avail through the api for the actual files
    if (isset($_POST['params']['spatialfilter'])){
      $url .= '&sourceResource.spatial=**';
    }
    if (isset($_POST['params']['timefilter'])){
      $url .= '&sourceResource.date.displayDate=**';
    }
    if (isset($_POST['params']['page'])) {
      $url .= "&page=" . $_POST['params']['page'];
    }
    if (isset($_POST['params']['sort'])) {
      $sort = $_POST['params']['sort'];
      switch ($sort) {
        case "title":
            $sort = "sourceResource.title";
            break;
        case "creator":
            $sort = "sourceResource.contributor";
            break;
        case "date":
            $sort = "sourceResource.date.begin";
            break;
      }
      if ($sort != ""){
        $url .= "&sort_by=".$sort;
      }
    }
    if (isset($_POST['params']['facets'])){
      $facets = $_POST['params']['facets'];
      foreach($facets as $facet_name=>$facet_val){
        if ($facet_name == "creator"){
          $url .= "&sourceResource.contributor=\"".urlencode($facet_val)."\"";
        }
        if ($facet_name == "type"){
          $url .= "&sourceResource.type=\"".urlencode($facet_val)."\"";
        }
        if ($facet_name == "subject"){
          $url .= "&sourceResource.subject.name=\"".urlencode($facet_val)."\"";
        }
        if ($facet_name == "date"){
          // $url .= "&sourceResource.date.=\"".urlencode($facet_val)."\"";  
        }
      }
    }
    $url .= "&facets=sourceResource.contributor,sourceResource.date.begin,sourceResource.subject.name,sourceResource.type";
    $data = get_response($url);
    $json = json_decode($data);
    if (isset($json->error)) {
      wp_send_json(json_encode( "There was an error: " . $json->error));
      wp_die();
      return;
    }
    wp_send_json($data);
    wp_die();
}


add_action('wp_ajax_get_custom_meta', 'drstk_get_custom_meta');
function drstk_get_custom_meta(){
  check_ajax_referer('item_admin_nonce');
  $id = $_POST['pid'];
  $data = get_post_custom($id);
  wp_send_json($data);
  wp_die();
}

add_action('wp_ajax_get_post_meta', 'drstk_get_post_meta');
function drstk_get_post_meta(){
  check_ajax_referer('item_admin_nonce');
  $id = $_POST['pid'];
  $data = get_post($id);
  wp_send_json($data);
  wp_die();
}
