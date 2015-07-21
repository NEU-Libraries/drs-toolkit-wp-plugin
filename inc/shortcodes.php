<?php
/* adds the side box */
add_action( 'add_meta_boxes', 'drstk_add_page_submenu' );
function drstk_add_page_submenu() {
    add_meta_box(
        'drstk_sectionid',
        __( 'Add Video Playlist from DRS', 'drstk_textdomain' ),
        'drstk_add_video_playlist',
        'page',
        'side'
    );
    add_meta_box(
        'drstk_tileid',
        __( 'Add Tile Gallery from DRS', 'drstk_textdomain' ),
        'drstk_add_tile_gallery',
        'page',
        'side'
    );
    add_meta_box(
        'drstk_itemid',
        __( 'Add Item from DRS', 'drstk_textdomain' ),
        'drstk_add_item',
        'page',
        'side'
    );
}

/*enques extra js*/
add_action('admin_enqueue_scripts', 'drstk_enqueue_page_scripts');
function drstk_enqueue_page_scripts( $hook ) {
    if ($hook != 'post.php') {
        return;
    }

    wp_register_script('drstk_admin_js',
        plugins_url('../assets/js/admin.js', __FILE__),
        array());
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
}
