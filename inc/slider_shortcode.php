<?php
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
