<?php
add_action( 'wp_ajax_get_gallery_code', 'drstk_add_gallery' ); //for auth users
function drstk_add_gallery(){
  check_ajax_referer( 'gallery_ajax_nonce' );
  $col_pid = drstk_get_pid();
  $collection = array();
  $url = "https://repository.library.northeastern.edu/api/v1/export/".$col_pid."?per_page=20&page=1";
  $drs_data = get_response($url);
  $json = json_decode($drs_data);
  $data = '';
  if ($json->error) {
    $data = "There was an error: " . $json->error;
    wp_send_json($data);
    return;
  }
  // if ($json->pagination->table->total_count > 0){
  //   for ($x = 1; $x <= $json->pagination->table->num_pages; $x++) {
  //     $url = "https://repository.library.northeastern.edu/api/v1/export/".$col_pid."?per_page=10&page=".$x;
  //     $drs_data = get_response($url);
  //     $json = json_decode($drs_data);
      foreach ($json->items as $item){
          $img = array(
            'pid' => $item->pid,
            'thumbnail' => $item->thumbnails[0],
            'title' => $item->mods->Title[0],
          );
          $collection[] = $img;
      }
    // }
  // }
$data .= '<h4>Gallery Slider</h4><a href="#" id="drstk_insert_gallery" class="button" title="Insert shortcode">Insert shortcode</a>';
$data .= '<button class="gallery-options button"><span class="dashicons dashicons-admin-generic"></span></button>';
$data .= '<div class="hidden gallery-options">
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
       $url = "https://repository.library.northeastern.edu/api/v1/files/" . $id;
       $data = get_response($url);
       $data = json_decode($data);
       if (!$data->error){
         $pid = $data->pid;
         $thumbnail = end($data->thumbnails);
         $this_height = getimagesize($thumbnail);
         $this_height = $this_height[1];
         if ($this_height > $height){
           $height = $this_height;
         }
         $this_width = getimagesize($thumbnail);
         $this_width = $this_width[0];
         if ($this_width > $width){
           $width = $this_width;
         }
         $title = $data->mods->Title[0];
         $creator = $data->mods->Creator[0];
         $img_html .= "<li><a href='".site_url()."/item/".$pid."'><img src='".$thumbnail."'  alt='".$title."'></a>";
         if ($atts['caption'] && $atts['caption'] == "on"){
           $img_metadata = "";
           if (isset($atts['metadata'])){
             $metadata = explode(",",$atts['metadata']);
             foreach($metadata as $field){
               $this_field = $data->mods->$field;
               $img_metadata .= $this_field[0] . "<br/>";
             }
           }
           $img_html .= "<p class='caption'>".$img_metadata."</p>";
         }
         $img_html .= "</li>";
       } else {
         $img_html .= "There was an error";
       }
   }
   $slide_data = '';
   if ($atts['auto'] && $atts['auto'] == 'on'){
     $slide_data .= " data-auto='true'";
   }
   if ($atts['nav'] && $atts['nav'] == 'on'){
     $slide_data .= " data-nav='true'";
   }
   if ($atts['pager'] && $atts['pager'] == 'on'){
     $slide_data .= " data-pager='true'";
   }
   if ($atts['speed'] && $atts['speed']){
     $slide_data .= " data-speed='".$atts['speed']."'";
   }
   if ($atts['timeout'] && $atts['timeout']){
     $slide_data .= " data-timeout='".$atts['timeout']."'";
   }
   return '<div class="rslides-drstk" data-height="'.$height.'" data-width="'.$width.'" ><div class="rslidesd-container"><div class="rslidesd-inner"><ul class="slides" '.$slide_data.'>'.$img_html.'</ul></div></div></div><div class="clearboth"></div>';

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
