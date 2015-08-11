<?php
/* side box content for video playlist shortcode */
add_action( 'wp_ajax_get_video_code', 'drstk_add_video_playlist' ); //for auth users
// function drstk_add_video_playlist( $post ) {
function drstk_add_video_playlist() {
    check_ajax_referer( 'video_ajax_nonce' );
    // wp_nonce_field( 'drstk_add_video_playlist', 'drstk_add_video_playlist_nonce' );
    global $post;
    $post_id = $post->ID;
    $col_pid = drstk_get_pid();
    $orig_collection = get_collection_from_post( $post_id );
    // echo "collection is " . $collection;
    $collection = array();
    $url = "https://repository.library.northeastern.edu/api/v1/export/".$col_pid."?per_page=2&page=1";
    $drs_data = get_response($url);
    $json = json_decode($drs_data);
    // $return = '';
    if ($json->error) {
      $return = "There was an error: " . $json->error;
      wp_send_json($return);
      return;
    }
    if ($json->pagination->table->total_count > 0){
      for ($x = 1; $x <= $json->pagination->table->num_pages; $x++) {
        $url = "https://repository.library.northeastern.edu/api/v1/export/".$col_pid."?per_page=10&page=".$x;
        $drs_data = get_response($url);
        $json = json_decode($drs_data);
        foreach ($json->items as $item){
          if (!in_array($item->pid, $collection)){
            $encoded = str_replace(':','%3A', $item->pid);
            $dir = substr(md5("info:fedora/".$item->pid."/content/content.0"), 0, 2);
            $video = array(
              'include' => true,
              // 'rtmp' => 'rtmp://libwowza.neu.edu:1935/vod/_definst_/MP4:datastreamStore/repositoryData/newfedoradata/datastreamStore/'.$dir.'/info%3Afedora%2F'.$encoded.'%2Fcontent%2Fcontent.0',
              // 'playlist' => 'http://libwowza.neu.edu:1935/vod/_definst_/datastreamStore/repositoryData/newfedoradata/datastreamStore/'.$dir.'/MP4:'. urlencode("info%3Afedora%2F".$encoded."%2Fcontent%2Fcontent.0") .'/playlist.m3u8',
              'download' => 'download',
              'poster' => end($item->thumbnails),
              'title' => $item->mods->Title[0],
              // 'type' => 'MP4'
              // 'provider' => 'video'
            );
            if ($item->canonical_object[0][1] == 'Audio File'){
              $video['rtmp'] = 'rtmp://libwowza.neu.edu:1935/vod/_definst_/MP3:datastreamStore/repositoryData/newfedoradata/datastreamStore/'.$dir.'/info%3Afedora%2F'.$encoded.'%2Fcontent%2Fcontent.0';
              $video['playlist'] = 'http://libwowza.neu.edu:1935/vod/_definst_/datastreamStore/repositoryData/newfedoradata/datastreamStore/'.$dir.'/MP3:'. urlencode("info%3Afedora%2F".$encoded."%2Fcontent%2Fcontent.0") .'/playlist.m3u8';
              $video['type'] = 'MP3';
              $video['provider'] = 'audio';
            }
            if ($item->canonical_object[0][1] == 'Video File'){
              $video['rtmp'] = 'rtmp://libwowza.neu.edu:1935/vod/_definst_/MP4:datastreamStore/repositoryData/newfedoradata/datastreamStore/'.$dir.'/info%3Afedora%2F'.$encoded.'%2Fcontent%2Fcontent.0';
              $video['playlist'] = 'http://libwowza.neu.edu:1935/vod/_definst_/datastreamStore/repositoryData/newfedoradata/datastreamStore/'.$dir.'/MP4:'. urlencode("info%3Afedora%2F".$encoded."%2Fcontent%2Fcontent.0") .'/playlist.m3u8';
              $video['type'] = 'MP4';
              $video['provider'] = 'video';
            }
            $collection[] = $video;
          }
        }
      }
      update_post_meta( $post_id, 'drstk_collection_json', encode_to_safe_json($collection) );
    }
    $return = array('collection' => $collection, 'safe_collection' => encode_to_safe_json($collection), 'orig_collection'=>$orig_collection);
    wp_send_json(json_encode($return));
    return;
}


/* save data */
add_action( 'save_post', 'drstk_save_collection_id' );
function drstk_save_collection_id( $post_id ) {
  // echo "hello?";
  // echo $_POST['drstk_collection_json'];
    // Check if our nonce is set.
    // if ( ! isset( $_POST['drstk_add_video_playlist_nonce'] ) )
    //     return $post_id;
    // $nonce = $_POST['drstk_add_video_playlist_nonce'];
      // Verify that the nonce is valid.
    // if ( ! wp_verify_nonce( $nonce, 'drstk_add_video_playlist' ) )
      // return $post_id;
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
    // print_r($arr);
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
    echo $collection;
    $playlists = '';

    // foreach ($collection as $key => $video) {
    //     if ($video['include']) {
    //         $playlists .= '{ sources: [ { file: "' .  $video['rtmp'] . '"},';
    //         $playlists .= '{ file: "' . $video['playlist'] . '"}, { file: "' . $video['download'] . '",';
    //         $playlists .=  ' type: "' . $video['type'] . '" } ], image: "' . $video['poster'] . '", title: "' . $video['title'] . '" },';
    //     };
    // }

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
