<?php
//allows modals in admin
add_thickbox();

add_action('media_buttons', 'add_drs_button', 15);
function add_drs_button() {
    echo '<a href="#TB_inline?width=750&height=675&inlineId=drs-tile-modal" id="insert-drs" class="button thickbox" title="Add DRS Item(s)">Add DRS Item(s)</a>';
    echo '<div id="drs-tile-modal" style="display:none;padding:10px;">';
    echo '<div id="tabs"><ul><li><a href="#tabs-1">Tile Gallery</a></li><li><a href="#tabs-2">Gallery Slider</a></li><li><a href="#tabs-3">Single Item</a></li><li><a href="#tabs-4">Video Playlist</a></li></ul><div id="tabs-1">';
    echo '<h4>Tile Gallery</h4><br/><label for="search">Search for an item: </label><input type="text" name="search" id="search-tile" /><button class="themebutton" id="search-button-tile">Search</button><br/><button class="tile-options button"><span class="dashicons dashicons-admin-generic"></span></button>';
    echo '<div class="hidden tile-options"><label for="tile-type">Type Layout Type</label><select name="tile-type" id="drstk-tile-type"><option value="pinterest">Pinterest style with caption below</option><option value="even-row">Even rows with caption on hover</option><option value="square">Even Squares with caption on hover</option></select><div class="drstk-tile-metadata">
      <h5>Metadata for Captions</h5>
      <label><input type="checkbox" name="Title" checked="checked"/>Title</label><br/>
      <label><input type="checkbox" name="Contributor"/>Creator</label><br/>
      <label><input type="checkbox" name="Date created"/>Date Created</label><br/>
      <label><input type="checkbox" name="Abstract/Description"/>Abstract/Description</label>
    </div></div>';
    echo '<div class="drs-items">Loading...</div><div class="drs-pagination"></div><input type="hidden" class="selected-tile" /></div><div id="tabs-2">';
    echo '<h4>Gallery Slider</h4><br/><label for="search">Search for an item: </label><input type="text" name="search" id="search-gallery" /><button class="themebutton" id="search-button-gallery">Search</button><br/>';
    echo '<button class="gallery-options button"><span class="dashicons dashicons-admin-generic"></span></button>';
    echo '<div class="hidden gallery-options">
    <label for="drstk-slider-auto"><input type="checkbox" name="drstk-slider-auto" id="drstk-slider-auto" value="yes" checked="checked" />Auto rotate</label><br/>
    <label for="drstk-slider-nav"><input type="checkbox" name="drstk-slider-nav" id="drstk-slider-nav" value="yes" checked="checked" />Next/Prev Buttons</label><br/>
    <label for="drstk-slider-pager"><input type="checkbox" name="drstk-slider-pager" id="drstk-slider-pager" value="yes" checked="checked" />Dot Pager</label><br/>
    <label for="drstk-slider-speed">Rotation Speed<input type="text" name="drstk-slider-speed" id="drstk-slider-speed" /></label><br/>
    <label for="drstk-slider-timeout">Time between Slides<input type="text" name="drstk-slider-timeout" id="drstk-slider-timeout" /></label><br/>
    <label for="drstk-slider-caption"><input type="checkbox" name="drstk-slider-caption" id="drstk-slider-caption" value="yes" checked="checked"/>Enable captions</label><br/>
    <div class="drstk-slider-metadata">
      <h5>Metadata for Captions</h5>
      <label><input type="checkbox" name="Title"/>Title</label><br/>
      <label><input type="checkbox" name="Contributor"/>Creator</label><br/>
      <label><input type="checkbox" name="Date created"/>Date Created</label><br/>
      <label><input type="checkbox" name="Abstract/Description"/>Abstract/Description</label>
    </div>
    </div>';
    echo '<div class="drs-items">Loading...</div><div class="drs-pagination"></div><input type="hidden" class="selected-gallery" /></div><div id="tabs-3">';
    echo '<h4>Item</h4><br/><label for="search">Search for an item: </label><input type="text" name="search" id="search-item" /><button class="themebutton" id="search-button-item">Search</button><br/>';
    echo '<button class="zoom-options button"><span class="dashicons dashicons-admin-generic"></span></button>';
    echo '<div class="hidden zoom-options">';
    echo '<label for="drsitem-zoom"><input id="drsitem-zoom" name="drsitem-zoom" value="yes" type="checkbox" />Enable zoom</label><br/><label for="drsitem-zoom-inner"><input id="drsitem-zoom-inner" name="drsitem-zoom-inner" value="yes" type="checkbox" />Zoom inside image</label><br/>';
    echo '<label for="drsitem-zoom-window">Zoom position (outside image)<select name="drsitem-zoom-window" id="drsitem-zoom-window"><option value="0">Select Position</option><option value="1">Top Right</option><option value="2">Middle Right</option><option value="3">Bottom Right</option><option value="4">Bottom Corner Right</option><option value="5">Under Right</option><option value="6">Under Middle</option><option value="7">Under Left</option><option value="8">Bottom Corner Left </option><option value="9">Bottom Left</option><option value="10">Middle Left</option><option value="11">Top Left</option><option value="12">Top Corner Left</option><option value="12">Above Left</option><option value="14">Above Middle</option><option value="15">Above Right</option><option value="16">Top Right Corner</option></select><br><i>Recommended and Default position:Top Right</i></div>';
    echo '<hr/><div class="item-metadata"></div>';
    echo '<div class="drs-items">Loading...</div><div class="drs-pagination"></div></div><div id="tabs-4">';
    echo '<div class="drs-items">Loading...</div><div class="drs-pagination"></div></div></div>';
    echo '</div>';
}

/*enques extra js*/
add_action('admin_enqueue_scripts', 'drstk_enqueue_page_scripts');
function drstk_enqueue_page_scripts( $hook ) {
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

/* side box content for tile gallery shortcode */
add_action( 'wp_ajax_get_tile_code', 'drstk_add_tile_gallery' ); //for auth users
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
    if ($json->error) {
      wp_send_json(json_encode( "There was an error: " . $json->error));
      return;
    }
    wp_send_json($data);
}

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
                width: 39em;
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
