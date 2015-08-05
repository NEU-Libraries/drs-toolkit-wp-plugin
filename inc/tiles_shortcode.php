<?php
/* adds shortcode */
add_shortcode( 'drstk_tiles', 'drstk_tiles' );
function drstk_tiles( $atts ){
  $imgs = explode(", ",$atts['id']);
  $img_html = "";
  foreach($imgs as $img){
    $url = "https://repository.library.northeastern.edu/api/v1/files/" . $img;
    $data = get_response($url);
    $data = json_decode($data);
    if (!$data->error){
      $pid = $data->pid;
      $thumbnail = end($data->thumbnails);
      $title = $data->mods->Title[0];
      $creator = $data->mods->Creator[0];
      $img_html .= "<div class='brick'><a href='".site_url()."/item/".$pid."'><img src='".$thumbnail."'></a><div class='info'><h5><a href='".site_url()."/item/".$pid."'>".$title."</a></h5>".$creator;
      $img_html .= "<div class='hidden'>";
      $meta = $data->mods;
      foreach($meta as $field){
        if (is_array($field)){
          foreach($field as $field_val){
            $img_html .= $field_val . "<br/>";
          }
        } else {
          $img_html .= $field[0] . "<br/>";
        }
      }
      $img_html .= "</div>";
      $img_html .= "</div></div>";
    } else {
      $img_html = "There was an error";
    }

  }
  $shortcode = "<div class='freewall' id='freewall'>".$img_html."</div>";
  return $shortcode;
}

function drstk_tile_shortcode_scripts() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_tiles') ) {
    wp_register_script('drstk_freewall',
        plugins_url('../assets/js/freewall/freewall.js', __FILE__),
        array( 'jquery' ));
    wp_enqueue_script('drstk_freewall');
    wp_enqueue_script( 'drstk_tiles',
        plugins_url( '../assets/js/tiles.js', __FILE__ ),
        array( 'jquery' )
    );
	}
}
add_action( 'wp_enqueue_scripts', 'drstk_tile_shortcode_scripts');
