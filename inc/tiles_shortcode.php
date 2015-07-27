<?php
/* side box content for tile gallery shortcode */
function drstk_add_tile_gallery() {
    $col_pid = drstk_get_pid();
    wp_nonce_field( 'drstk_add_tile_gallery', 'drstk_add_tile_gallery_nonce' );
    $collection = array();
    $url = "http://cerberus.library.northeastern.edu/api/v1/export/".$col_pid."?per_page=2&page=1";
    $drs_data = get_response($url);
    $json = json_decode($drs_data);
    if ($json->error) {
      echo "There was an error: " . $json->error;
      return;
    }
    if ($json->pagination->table->total_count > 0){
      for ($x = 1; $x <= $json->pagination->table->num_pages; $x++) {
        $url = "http://cerberus.library.northeastern.edu/api/v1/export/".$col_pid."?per_page=10&page=".$x;
        $drs_data = get_response($url);
        $json = json_decode($drs_data);
        foreach ($json->items as $item){
            $img = array(
              'pid' => $item->pid,
              'thumbnail' => $item->thumbnails[0],
              'title' => $item->mods->Title[0],
            );
            $collection[] = $img;
        }
      }
    }
 echo '<h4>Tile Gallery</h4><a href="#" id="drstk_insert_tile_gallery" class="button" title="Insert shortcode">Insert shortcode</a>';

    echo '<ol id="sortable-tile-list">';
    foreach ($collection as $key => $doc) {
        echo '<li style="display:inline-block;padding:10px;">';
        echo '<label for="drstile-', $key, '"><img src="', $doc['thumbnail'], '" width="150" /><br/>';
        echo '<input id="drstile-', $key, '" type="checkbox" class="drstk-include-tile" value="'.$doc['pid'].'" />';
        echo '<span style="width:100px;display:inline-block">'.$doc['title'].'</span></label>';
        echo '</li>';
    }
    echo '</ol>';
        echo '<p>Drag and drop the thumbnails in the order you want them to appear in the playlist. You can un-check the images you wish to exclude entirely.';
}

/* adds shortcode */
add_shortcode( 'drstk_tiles', 'drstk_tiles' );
function drstk_tiles( $atts ){
  $imgs = explode(", ",$atts['id']);
  $img_html = "";
  foreach($imgs as $img){
    $url = "http://cerberus.library.northeastern.edu/api/v1/files/" . $img;
    $data = get_response($url);
    $data = json_decode($data);
    if (!$data->error){
      $pid = $data->pid;
      $thumbnail = end($data->thumbnails);
      $title = $data->mods->Title[0];
      $creator = $data->mods->Creator[0];
      $img_html .= "<div class='brick'><a href='".site_url()."/item/".$pid."'><img src='".$thumbnail."'></a><div class='info'><h5><a href='".site_url()."/item/".$pid."'>".$title."</a></h5>".$creator."</div></div>";
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
