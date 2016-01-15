<?php
/* adds shortcode */
add_shortcode( 'drstk_collection_playlist', 'drstk_collection_playlist' );
function drstk_collection_playlist($atts){
  $cache = get_transient(md5('PREFIX'.serialize($atts)));

  if($cache) {
      return $cache;
  }
    $collection = explode(', ', $atts['id']);
    $playlists = '';
    if (isset($atts['height'])){
      $height = $atts['height'];
    } else {
      $height = '270';
    }
    if (isset($atts['width'])){
      $width = $atts['width'];
    } else {
      $width = '100%';
    }
    foreach($collection as $video){
        $url = "https://repository.library.northeastern.edu/api/v1/files/" . $video;
        $data = get_response($url);
        $data = json_decode($data);
        $poster;
        if (!$data->error){
          $poster[] = $data->thumbnails[4];
          $this_poster = $data->thumbnails[4];
          $title = $data->mods->Title[0];
          $pid = $data->canonical_object[0][0];
          $pid = explode("/", $pid);
          $pid = end($pid);
          $encoded = str_replace(':','%3A', $pid);
          $dir = substr(md5("info:fedora/".$pid."/content/content.0"), 0, 2);
          if ($data->canonical_object[0][1] == 'Audio File'){
            $rtmp = 'rtmp://libwowza.neu.edu:1935/vod/_definst_/MP3:datastreamStore/cerberusData/newfedoradata/datastreamStore/'.$dir.'/info%3Afedora%2F'.$encoded.'%2Fcontent%2Fcontent.0';
            $playlist = 'http://libwowza.neu.edu:1935/vod/_definst_/datastreamStore/cerberusData/newfedoradata/datastreamStore/'.$dir.'/MP3:'. urlencode("info%3Afedora%2F".$encoded."%2Fcontent%2Fcontent.0") .'/playlist.m3u8';
            $type = 'MP3';
            $provider = 'audio';
          }
          if ($data->canonical_object[0][1] == 'Video File'){
            $rtmp = 'rtmp://libwowza.neu.edu:1935/vod/_definst_/MP4:datastreamStore/cerberusData/newfedoradata/datastreamStore/'.$dir.'/info%3Afedora%2F'.$encoded.'%2Fcontent%2Fcontent.0';
            $playlist = 'http://libwowza.neu.edu:1935/vod/_definst_/datastreamStore/cerberusData/newfedoradata/datastreamStore/'.$dir.'/MP4:'. urlencode("info%3Afedora%2F".$encoded."%2Fcontent%2Fcontent.0") .'/playlist.m3u8';
            $type = 'MP4';
            $provider = 'video';
          }
          $download = 'download';
          $playlists .= '{ sources: [ { file: "' .  $rtmp . '"},';
          $playlists .= '{ file: "' . $playlist . '"} ], image: "' . $this_poster . '", title: "' . $title . '" },';
        } else {
          return $errors['shortcodes']['fail'];
        }
      }
    $cache_output = '<div id="drs-item-video">
        <img style="width: 100%;" src="' . $poster[0] .'" />
      </div>
      <script type="text/javascript">
        jwplayer.key="gi5wgpwDtAXG4xdj1uuW/NyMsECyiATOBxEO7A==";
        jwplayer("drs-item-video").setup({
          width: "'.$width.'",
          height: "'.$height.'",
          rtmp: { bufferlength: 5 } ,
          fallback: true,
              listbar: {
                position: "right",
                size: 250,
                layout: "basic"
              },
          playlist: [ '. $playlists . ']
    });</script>';
    $cache_time = 1000;
    set_transient(md5('PREFIX'.serialize($atts)) , $cache_output, $cache_time * 60);
    return $cache_output;

}

function drstk_video_shortcode_scripts() {
    global $post;
    if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_collection_playlist') ) {
      wp_register_script('drstk_jwplayer',
          plugins_url('/assets/js/jwplayer/jwplayer.js', __FILE__),
          array(), $VERSION, false );
        wp_enqueue_script( 'drstk_jwplayer');
    }
}
add_action( 'wp_enqueue_scripts', 'drstk_video_shortcode_scripts');
