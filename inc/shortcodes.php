<?php
//allows modals in admin

function add_drs_button() {
    echo '<a href="#TB_inline?width=750&height=675&inlineId=drs-tile-modal" id="insert-drs" class="button thickbox" title="Add DRS Item(s)">Add DRS Item(s)</a>';
    echo '<div id="drs-tile-modal" style="display:none;padding:10px;">';
    echo '<div id="tabs"><ul><li><a href="#tabs-1">Tile Gallery</a></li><li><a href="#tabs-2">Gallery Slider</a></li><li><a href="#tabs-3">Single Item</a></li><li><a href="#tabs-4">Media Playlist</a></li><li><a href="#tabs-5">Map</a></li><li><a href="#tabs-6">Timeline</a></li></ul><div id="tabs-1">';
    echo '</div><div id="tabs-2">';
    echo '</div><div id="tabs-3">';
    echo '</div><div id="tabs-4">';
	echo '</div><div id="tabs-5">';
	echo '</div><div id="tabs-6">';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
add_action('media_buttons', 'add_drs_button', 1000);


/*enques extra js*/
function drstk_enqueue_page_scripts( $hook ) {
  global $errors;
  add_thickbox();
    if ($hook == 'post.php' || $hook == 'post-new.php') {
    wp_register_script('drstk_admin_js',
        plugins_url('../assets/js/admin.js', __FILE__),
        array('jquery', 'jquery-ui-tabs'));
    wp_enqueue_script( 'drstk_admin_js' );
    wp_enqueue_script('jquery-ui-sortable');
   //this creates a unique nonce to pass back and forth from js/php to protect
   $item_admin_nonce = wp_create_nonce( 'item_admin_nonce' );
   //this allows an ajax call from admin.js

   wp_localize_script( 'drstk_admin_js', 'item_admin_obj', array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'item_admin_nonce'    => $item_admin_nonce,
      'pid' => '',
	  'leaflet_api_key' => get_option('leaflet_api_key'),
	  'leaflet_project_key' => get_option('leaflet_project_key'),
      'errors' => json_encode($errors),
   ) );

   $video_ajax_nonce = wp_create_nonce( 'video_ajax_nonce' );
   wp_localize_script('drstk_admin_js', 'video_ajax_obj', array(
     'ajax_url' => admin_url( 'admin-ajax.php' ),
     'video_ajax_nonce'    => $video_ajax_nonce,
   ));
   $tile_ajax_nonce = wp_create_nonce( 'tile_ajax_nonce');
   wp_localize_script( 'drstk_admin_js', 'tile_ajax_obj', array(
     'ajax_url' => admin_url('admin-ajax.php'),
     'tile_ajax_nonce' => $tile_ajax_nonce,
   ));

 } else {
   return;
 }
}
add_action('admin_enqueue_scripts', 'drstk_enqueue_page_scripts');

/* side box content for tile gallery shortcode */
function drstk_add_tile_gallery(){
  check_ajax_referer( 'tile_ajax_nonce' );
  $col_pid = drstk_get_pid();
    $url = "https://repository.library.northeastern.edu/api/v1/search/".$col_pid."?per_page=20";
    if ($_POST['params']['q'] ){
      $url .= "&q=". urlencode(sanitize_text_field($_POST['params']['q']));
    }
    if ($_POST['params']['page']) {
      $url .= "&page=" . $_POST['params']['page'];
    }
    $data = get_response($url);
    $json = json_decode($data);
    if (isset($json->error)) {
      wp_send_json(json_encode( "There was an error: " . $json->error));
      return;
    }
    wp_send_json($data);
}

add_action( 'wp_ajax_get_tile_code', 'drstk_add_tile_gallery' ); //for auth users

/* POST for individual items*/
function get_json_data_from_neu_item(){
	// The $_REQUEST contains all the data sent via ajax
    if ( isset($_REQUEST) ) {
        $item = $_REQUEST['item'];
		//Setting the correct URL
		$url = "https://repository.library.northeastern.edu/api/v1/files/".$item;
		
		//Adding response to data
		$data = get_response($url);
		$json = json_decode($data);
		if (isset($json->error)) {
			wp_send_json(json_encode( "There was an error: " . $json->error));
			return;
		}
		//returning json
        echo wp_send_json($json);
         
        // debugging purposes
        // print_r($_REQUEST);
    }
   die();
}

add_action( 'wp_ajax_get_json_data_from_neu_item', 'get_json_data_from_neu_item' ); //Searching for Maps and Timeline

function thickbox_styles() {
   echo '<style type="text/css">
           #TB_window{height:700px !important;}
           #TB_ajaxContent{width:750px !important;}
            .ui-tabs.ui-tabs-vertical {
                padding: 0;
                width: 53em;
            }
            .ui-tabs.ui-tabs-vertical .ui-widget-header {
                border: none;
            }
            .ui-tabs.ui-tabs-vertical .ui-tabs-nav {
                float: left;
                width: 10em;
                background: #CCC;
                border-right: 1px solid gray;
            }
            .ui-tabs.ui-tabs-vertical .ui-tabs-nav li {
                margin: 0.2em 0;
                border: 1px solid gray;
                border-radius: 4px 0 0 4px;
                position: relative;
                right: -2px;
            }
            .ui-tabs.ui-tabs-vertical .ui-tabs-nav li a {
                display: block;
                padding: 0.6em 1em;
            }
            .ui-tabs.ui-tabs-vertical .ui-tabs-nav li a:hover {
                cursor: pointer;
            }
            .ui-tabs.ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active {
                border-right: 1px solid white;
            }
            .ui-tabs.ui-tabs-vertical .ui-tabs-panel {
                float: left;
                width: 38em;
                background:#FFF;
                padding:26px;
                max-height:597px;
                overflow:auto;
            }
            .ui-tabs.ui-tabs-vertical .ui-tabs-panel h4{
              margin-top:0;
              margin-bottom:5px;
            }
            [id="9_section_group_li"], .redux-action_bar .promotion-button{
              display:none;
            }
            .wp-core-ui button.zoom-options.button, button.gallery-options.button, button.tile-options.button{
              padding: 2px 5px 0px 5px;
              margin-left:10px;
            }
            .item-metadata{
              float:left;
            }
            .drstk-slider-metadata{
              padding:5px;
            }
            .drstk-slider-metadata h5{
              margin:5px;
            }
         </style>';
}

add_action('admin_head', 'thickbox_styles');
