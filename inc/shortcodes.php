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
        'drstk_itemid',
        __( 'Add Item from DRS', 'drstk_textdomain' ),
        'drstk_add_item',
        'page',
        'side'
    );
}
