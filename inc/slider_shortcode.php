<?php
add_action( 'wp_ajax_get_gallery_code', 'drstk_add_gallery' ); //for auth users
function drstk_add_gallery(){
  check_ajax_referer( 'gallery_ajax_nonce' );
  $col_pid = drstk_get_pid();
  $collection = array();
  $url = "http://cerberus.library.northeastern.edu/api/v1/export/".$col_pid."?per_page=2&page=1";
  $drs_data = get_response($url);
  $json = json_decode($drs_data);
  $data = '';
  if ($json->error) {
    $data = "There was an error: " . $json->error;
    wp_send_json($data);
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
$data .= '<h4>Gallery Slider</h4><a href="#" id="drstk_insert_gallery" class="button" title="Insert shortcode">Insert shortcode</a>';
$data .= '<button class="gallery-options button"><span class="dashicons dashicons-admin-generic"></span></button>';
$data .= '<div class="hidden gallery-options">
<label for="drstk-slider-caption"><input type="checkbox" name="drstk-slider-caption" id="drstk-slider-caption" />Enable captions</label>

</div>';
$data .= '<ol id="sortable-gallery-list">';
  foreach ($collection as $key => $doc) {
      $data .= '<li style="display:inline-block;padding:10px;">';
      $data .= '<label for="drsitem-'. $key. '"><img src="'. $doc['thumbnail']. '" width="150" /><br/>';
      $data .= '<input id="drsitem-'. $key. '" type="checkbox" class="drstk-include-gallery" value="'.$doc['pid'].'" />';
      $data .= '<span style="width:100px;display:inline-block">'.$doc['title'].'</span></label>';
      $data .= '</li>';
  }
  $data .= '</ol>';
  $data .= '<p>Drag and drop the videos in the order you want them to appear in the playlist. You can un-check the videos you wish to exclude entirely.';
  wp_send_json($data);
  return;
}

/* adds shortcode */
add_shortcode( 'drstk_gallery', 'drstk_gallery' );
function drstk_gallery( $atts ){
  if ($atts['id']){
    $images = explode(", ",$atts['id']);
    $img_html = '';
    $height = $width = 0;
   foreach($images as $id){
       $url = "http://cerberus.library.northeastern.edu/api/v1/files/" . $id;
       $data = get_response($url);
       $data = json_decode($data);
       if (!$data->error){
         $pid = $data->pid;
         $thumbnail = end($data->thumbnails);
         $this_height = getimagesize($thumbnail)[1];
         if ($this_height > $height){
           $height = $this_height;
         }
         $this_width = getimagesize($thumbnail)[0];
         if ($this_width > $width){
           $width = $this_width;
         }
         $title = $data->mods->Title[0];
         $creator = $data->mods->Creator[0];
         $img_html .= "<li><a href='".site_url()."/item/".$pid."'><img src='".$thumbnail."'  alt='".$title."'></a><p class='caption'>".$title."<br/>".$creator."</p></li>";
       } else {
         $img_html .= "There was an error";
       }
   }
   echo '<div class="rslides-drstk" data-height="'.$height.'" data-width="'.$width.'" ><div id="slider-core"><div class="rslides-container"><div class="rslides-inner"><ul class="slides">'.$img_html.'</ul></div></div></div></div><div class="clearboth"></div>';
  }
}

function drstk_gallery_shortcode_scripts() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_gallery') ) {
    wp_enqueue_script( 'drstk_tiles',
        plugins_url( '../assets/js/gallery.js', __FILE__ ),
        array( 'jquery' )
    );
	}
}
add_action( 'wp_enqueue_scripts', 'drstk_gallery_shortcode_scripts');
