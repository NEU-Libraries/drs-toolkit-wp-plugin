<?php
/*enques extra js*/
add_action('admin_enqueue_scripts', 'drstk_enqueue_page_scripts');
function drstk_enqueue_page_scripts( $hook ) {
    if ($hook != 'post.php') {
        return;
    }

    wp_register_script('drstk_sort_collections',
        plugins_url('../assets/js/admin-collection-sorting.js', __FILE__),
        array());
    wp_enqueue_script( 'drstk_sort_collections' );
    wp_enqueue_script('jquery-ui-sortable');
}

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
}

/* side box content for video playlist shortcode */
function drstk_add_video_playlist( $post ) {
    $post_id = $post->ID;
    $col_pid = get_option('drstk_collection');
    $collection = get_collection_from_post( $post_id );
    wp_nonce_field( 'drstk_add_video_playlist', 'drstk_add_video_playlist_nonce' );
    $collection = array();
    $url = "https://repository.library.northeastern.edu/api/v1/export/".$col_pid."?per_page=2&page=1";
    $drs_data = get_response($url);
    $json = json_decode($drs_data);
    if ($json->error) {
      echo "There was an error: " . $json->error;
      return;
    }
    if ($json->pagination->table->total_count > 0){
      for ($x = 1; $x <= $json->pagination->table->num_pages; $x++) {
        $url = "https://repository.library.northeastern.edu/api/v1/export/".$col_pid."?per_page=10&page=".$x;
        $drs_data = get_response($url);
        $json = json_decode($drs_data);
        foreach ($json->items as $item){
          if ($item->canonical_object[0][1] == 'Video File' && !in_array($item->pid, $collection)){
            $encoded = str_replace(':','%3A', $item->pid);
            $dir = substr(md5("info:fedora/".$item->pid."/content/content.0"), 0, 2);
            $video = array(
              'include' => true,
              'rtmp' => 'rtmp://libwowza.neu.edu:1935/vod/_definst_/MP4:datastreamStore/repositoryData/newfedoradata/datastreamStore/'.$dir.'/info%3Afedora%2F'.$encoded.'%2Fcontent%2Fcontent.0',
              'playlist' => 'http://libwowza.neu.edu:1935/vod/_definst_/datastreamStore/repositoryData/newfedoradata/datastreamStore/'.$dir.'/MP4:'. urlencode("info%3Afedora%2F".$encoded."%2Fcontent%2Fcontent.0") .'/playlist.m3u8',
              'download' => 'download',
              'poster' => end($item->thumbnails),
              'title' => $item->mods->Title[0],
            );
            $collection[] = $video;
          }
        }
      }
    }
    update_post_meta( $post_id, 'drstk_collection_json', encode_to_safe_json($collection) );
 ?>   <a href="#" id="drstk_insert_shortcode" class="button" title="Insert shortcode">Insert shortcode</a> <?php

    echo '<input type="hidden" id="drstk_collection_json" name="drstk_collection_json" value="' . encode_to_safe_json($collection) . '" />';
    echo '<ol id="sortable-source-list">';
    foreach ($collection as $key => $doc) {
        echo '<li id="drsvideokey-', $key, '">';
        echo '<img src="', $doc['poster'], '" width="150" /><br/>';
        echo '<input type="checkbox" class="drstk-include-video" ', ( $doc['include'] ? 'checked' : '' ), ' />';
        echo $doc['title'];
        echo '</li>';
    }
    echo '</ol>';
        echo '<p>Drag and drop the videos in the order you want them to appear in the playlist. You can un-check the videos you wish to exclude entirely.';

}


/* save data */
add_action( 'save_post', 'drstk_save_collection_id' );
function drstk_save_collection_id( $post_id ) {
    // Check if our nonce is set.
    if ( ! isset( $_POST['drstk_add_video_playlist_nonce'] ) )
        return $post_id;
    $nonce = $_POST['drstk_add_video_playlist_nonce'];
      // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $nonce, 'drstk_add_video_playlist' ) )
      return $post_id;
      // Check the user's permissions.
    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
    update_post_meta( $post_id, 'drstk_collection_json', $_POST['drstk_collection_json'] );
}

// gets and decodes the collection information from the post
function get_collection_from_post($post_id) {
    $str = get_post_meta( $post_id, 'drstk_collection_json', true);
    $raw = rawurldecode($str);
    $arr = json_decode($raw, true);
    return $arr;
}

function encode_to_safe_json($obj) {
    $json = json_encode($obj);
    $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
    return strtr(rawurlencode($json), $revert);
}

/* adds shortcode */
add_shortcode( 'drstk_collection_playlist', 'drstk_collection_playlist' );
function drstk_collection_playlist( $atts ){
    $collection = get_collection_from_post( get_the_ID() );
    $playlists = '';

    foreach ($collection as $key => $video) {
        if ($video['include']) {
            $playlists .= '{ sources: [ { file: "' .  $video['rtmp'] . '"},';
            $playlists .= '{ file: "' . $video['playlist'] . '"}, { file: "' . $video['download'] . '",';
            $playlists .=  ' type: "MP4" } ], image: "' . $video['poster'] . '", title: "' . $video['title'] . '" },';
        };
    }

    return '<div id="drs-item-video">
        <img style="width: 100%;" src="' . $collection[0]["poster"] .'" />
      </div>
      <script type="text/javascript">
        jwplayer.key="gi5wgpwDtAXG4xdj1uuW/NyMsECyiATOBxEO7A==";
        jwplayer("drs-item-video").setup({
          width: "100%",
          rtmp: { bufferlength: 5 } ,
          fallback: true,
              listbar: {
                position: "right",
                size: 250,
                layout: "basic"
              },
          playlist: [ '. $playlists . ']
    });</script>';

}

function drstk_video_shortcode_scripts() {
    global $post;
    if( has_shortcode( $post->post_content, 'drstk_collection_playlist') ) {
        wp_enqueue_script( 'drstk_jwplayer');
    }
}
add_action( 'wp_enqueue_scripts', 'drstk_video_shortcode_scripts');
