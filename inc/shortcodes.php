<?php
//allows modals in admin

function add_drs_button() {
  echo '<a href="#" id="drs-backbone_modal" class="button" title="Add Toolkit Shortcodes">Add Toolkit Shortcodes</a>';
}
add_action('media_buttons', 'add_drs_button', 1000);


/*enques extra js*/
function drstk_enqueue_page_scripts( $hook ) {
  global $errors, $DRS_PLUGIN_PATH, $DRS_PLUGIN_URL;
  // add_thickbox();
    if ($hook == 'post.php' || $hook == 'post-new.php') {
    wp_register_script('drstk_admin_js',
    $DRS_PLUGIN_URL . '/assets/js/admin.js',
        array('jquery', 'jquery-ui-tabs'));
    wp_enqueue_script( 'drstk_admin_js' );
    // wp_enqueue_script('jquery-ui-sortable');
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

   include $DRS_PLUGIN_PATH.'templates/modal.php';
   wp_enqueue_script( 'backbone_modal', $DRS_PLUGIN_URL . '/assets/js/modal.js', array(
     'jquery',
     'backbone',
     'underscore',
     'wp-util',
     'jquery-ui-sortable'
   ) );
   wp_localize_script( 'backbone_modal', 'drstk_backbone_modal_l10n',
     array(
       'replace_message' => __( 'Choose a method of embedding DRS and/or DPLA item(s).<br/><br/><table><tr><td><a class="button" href="#one">Single Item</a></td><td><a class="button" href="#four">Media Playlist</a></td></tr><tr><td><a class="button" href="#two">Tile Gallery</a></td><td><a class="button" href="#five">Map</a></td></tr><tr><td><a class="button" href="#three">Gallery Slider</a></td><td><a class="button" href="#six">Timeline</a></td></tr></table>', 'backbone_modal' )
     ) );
   wp_enqueue_style( 'backbone_modal', $DRS_PLUGIN_URL . '/assets/css/modal.css' );

 } else {
   return;
 }
}
add_action('admin_enqueue_scripts', 'drstk_enqueue_page_scripts');
add_action( 'wp_ajax_get_drs_code', 'drstk_get_drs_items' ); //for auth users

/* side box content for tile gallery shortcode */
function drstk_get_drs_items(){
  check_ajax_referer( 'drs_ajax_nonce' );
  $col_pid = drstk_get_pid();
    $url = "https://repository.library.northeastern.edu/api/v1/search/".$col_pid."?per_page=20";
    if (isset($_POST['params']['q'])){
      $url .= "&q=". urlencode(sanitize_text_field($_POST['params']['q']));
      if (isset($_POST['params']['avfilter'])){
        $url .= 'AND%20canonical_class_tesim%3A"AudioFile"%20OR%20canonical_class_tesim%3A"VideoFile"';
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

/* side box content for tile gallery shortcode */
function drstk_get_dpla_items(){
  check_ajax_referer( 'dpla_ajax_nonce' );
    $url = "http://api.dp.la/v2/items?api_key=b0ff9dc35cb32dec446bd32dd3b1feb7&page_size=20";
    if (isset($_POST['params']['q'])){
      $url .= "&q=". urlencode(sanitize_text_field($_POST['params']['q']));
      // if (isset($_POST['params']['avfilter'])){ //TODO figure this out
      //   $url .= 'AND%20canonical_class_tesim%3A"AudioFile"%20OR%20canonical_class_tesim%3A"VideoFile"';
      // }
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

/* POST for individual items*/
function get_json_data_from_neu_item(){
  check_ajax_referer( 'item_admin_nonce' );
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
          .tablenav-pages a.current-page {
            border-color: #5b9dd9;
            color: #fff;
            background: #00a0d2;
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
			#add_custom_item{
				position: fixed;
				background: #808080;
				display: none;
				top: 20px;
				left: 50px;
				width: 300px;
				height: 350px;
				border: 1px solid #000;
				border-radius: 5px;
				padding: 5px;
				color: #fff;
			}

			#submit_custom_item , #timeline_submit_custom_item {
			  font: bold 11px Arial;
			  text-decoration: none;
			  background-color: #EEEEEE;
			  color: #333333;
			  padding: 2px 6px 2px 6px;
			  border-top: 1px solid #CCCCCC;
			  border-right: 1px solid #333333;
			  border-bottom: 1px solid #333333;
			  border-left: 1px solid #CCCCCC;
			}

			#close_add_custom_item {
			  font: bold 11px Arial;
			  text-decoration: none;
			  background-color: #EEEEEE;
			  color: #333333;
			  padding: 2px 6px 2px 6px;
			  border-top: 1px solid #CCCCCC;
			  border-right: 1px solid #333333;
			  border-bottom: 1px solid #333333;
			  border-left: 1px solid #CCCCCC;
			}

			#custom_item_submit {
			  font: bold 11px Arial;
			  text-decoration: none;
			  background-color: #EEEEEE;
			  color: #333333;
			  padding: 2px 6px 2px 6px;
			  border-top: 1px solid #CCCCCC;
			  border-right: 1px solid #333333;
			  border-bottom: 1px solid #333333;
			  border-left: 1px solid #CCCCCC;
			}

			option[value="red"] {
				color: red;
			}

			option[value="blue"] {
				color: blue;
			}

			option[value="green"] {
				color: green;
			}

			option[value="yellow"] {
				color: gold;
			}

			option[value="orange"] {
				color: orange;
			}
         </style>';
}

add_action('admin_head', 'thickbox_styles');
