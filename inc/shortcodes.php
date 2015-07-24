<?php
//allows modals in admin
add_thickbox();

add_action('media_buttons', 'add_drs_button', 15);
function add_drs_button() {
    echo '<a href="#TB_inline?width=750&height=650&inlineId=drs-tile-modal" id="insert-drs" class="button thickbox" title="Add DRS Item(s)">Add DRS Item(s)</a>';
    echo '<div id="drs-tile-modal" style="display:none;padding:10px;">';
    echo '<div id="tabs"><ul><li><a href="#tabs-1">Tile Gallery</a></li><li><a href="#tabs-2">Gallery Slider</a></li><li><a href="#tabs-3">Single Item</a></li><li><a href="#tabs-4">Video Playlist</a></li></ul><div id="tabs-1">';
    echo drstk_add_tile_gallery();
    echo '</div><div id="tabs-2">';
    // echo drstk_add_gallery();
    echo '</div><div id="tabs-3">';
    echo drstk_add_item();
    echo '</div><div id="tabs-4">';
    echo drstk_add_video_playlist();
    echo '</div></div>';
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
 } else {
   return;
 }
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
            }
            #sortable-tile-list, #sortable-item-list{
              height: 493px;
              overflow: scroll;
            }
            [id="9_section_group_li"], .redux-action_bar .promotion-button{
              display:none;
            }
         </style>';
}

add_action('admin_head', 'thickbox_styles');
