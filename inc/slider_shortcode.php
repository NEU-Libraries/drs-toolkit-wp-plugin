<?php
/* adds shortcode */
add_shortcode( 'drstk_gallery', 'drstk_gallery' );
function drstk_gallery( $atts ){
  global $errors;
  $cache = get_transient(md5('DRSTK'.serialize($atts)));

  if($cache) {
      return $cache;
  }
  if (isset($atts['id'])){
    $images = array_map('trim', explode(',', $atts['id']));
    $img_html = '';
    $height = $width = 0;
    $i = 0;
   foreach($images as $id){
       $url = "https://repository.library.northeastern.edu/api/v1/files/" . $id . "?solr_only=true";
       $data = get_response($url);
       $data = json_decode($data);
       $data = $data->_source;
       if (!isset($data->error)){
        $pid = $data->id;
         if (isset($atts['image-size'])){
           $num = $atts['image-size']-1;
         } else {
           $num = 4;
         }
         $thumbnail = "http://repository.library.northeastern.edu".$data->fields_thumbnail_list_tesim[$num];
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
        $title = $data->full_title_ssi;
         $img_html .= "<div class='item";
         if ($i == 0){
           $img_html .= " active";
         }
         $img_html .= "'><a href='".drstk_home_url()."item/".$pid."'><img";
         if ($i == 0){
           $img_html .= " src='".$thumbnail."'";
         } else {
           $img_html .= " data-src='".$thumbnail."'";
         }
         $img_html .= "  alt='".$title."'></a>";
         if (isset($atts['caption']) && $atts['caption'] == "on"){
           $img_metadata = "";
           if (isset($atts['metadata'])){
             $metadata = explode(",",$atts['metadata']);
             foreach($metadata as $field){
                if (isset($data->$field)){
                  $this_field = $data->$field;
                 if (isset($this_field)){
                   if (is_array($this_field)){
                     foreach($this_field as $val){
                       $img_metadata .= $val ."<br/>";
                     }
                   } else {
                     $img_metadata .= $this_field . "<br/>";
                   }
                 }
               }
             }
           }
           $img_html .= "<div class='carousel-caption'";
           if (isset($atts['caption-align']) || isset($atts['caption-position'])){
             $img_html .= "style='";
             if (isset($atts['caption-align'])){
               $img_html .= "text-align:".$atts['caption-align'];
             }
             if (isset($atts['caption-position'])){
               $img_html .= "; position:".$atts['caption-position'];
             }
             if (isset($atts['caption-width']) && $atts['caption-width'] == "100%"){
               $img_html .= "; width:".$atts['caption-width'];
             }
             $img_html .= "'";
           }
           if (isset($atts['caption-width']) && $atts['caption-width'] != "100%"){
             $img_html .= " data-caption-width='image'";
           }
           $img_html .= "><a href='".drstk_home_url()."item/".$pid."'>".$img_metadata."</a></div>";
           $img_html .= "<div class=\"hidden\">";
            foreach($data as $key=>$field){
              if ($key != "all_text_timv" && $key != "object_profile_ssm"){
                if (is_array($field)){
                  foreach($field as $key=>$field_val){
                    $img_html .= $field_val . "<br/>";
                  }
                } else {
                  $img_html .= $field . "<br/>";
                }
              }
            }
           $img_html .= "</div>";
         }
         $img_html .= "</div>";
       } else {
         $img_html .= $errors['shortcodes']['fail'];
       }
      $i++;
   }
   if (isset($atts['speed']) && $atts['speed'] > 1){
     $interval = $atts['speed'];
   }
   if (isset($atts['auto']) && $atts['auto'] == 'on'){
     if (!isset($interval)){
      $interval = 5000;
     }
   } else if (isset($atts['auto']) && $atts['auto'] == 'off'){
     $interval = 'false';
   }
   $rand = rand();
   $gallery_html = '<div class="carousel slide" id="carousel-'.$rand.'" data-height="'.$height.'" data-width="'.$width.'" data-interval="'.$interval.'"';
   if (isset($atts['max-height'])){
     $gallery_html .= " data-max-height='".$atts['max-height']."'";
   }
   if (isset($atts['max-width'])){
     $gallery_html .= " data-max-width='".$atts['max-width']."'";
   }
   $gallery_html .= '>';
   if (isset($atts['pager']) && $atts['pager'] == 'on'){
     $gallery_html .= '<ol class="carousel-indicators">';
     $i = 0;
     foreach($images as $id){
       $gallery_html .= '<li data-target="#carousel-'.$rand.'" data-slide-to="'.$i.'" class="';
       if ($i == 0){ $gallery_html .= "active";}
       $gallery_html .= '"></li>';
       $i++;
     }
     $gallery_html .= '</ol>';
   }
   $gallery_html .= '<div class="carousel-inner">'.$img_html.'</div>';
   if (isset($atts['nav']) && $atts['nav'] == 'on'){
     $gallery_html .= '<a class="left carousel-control" href="#carousel-'.$rand.'" role="button" data-slide="prev"><i class="glyphicon-chevron-left fa fa-chevron-left" aria-hidden="true"></i><span class="sr-only">Previous</span></a><a class="right carousel-control" href="#carousel-'.$rand.'" role="button" data-slide="next"><i class="glyphicon-chevron-right fa fa-chevron-right" aria-hidden="true"></i><span class="sr-only">Next</span></a>';
   }
   $gallery_html .= '</div>';
   $cache_output = $gallery_html;
   $cache_time = 1000;
   set_transient(md5('DRSTK'.serialize($atts)) , $cache_output, $cache_time * 60);
   return $gallery_html;
  }
}

function drstk_gallery_shortcode_scripts() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_gallery') && !isset($wp_query->query_vars['drstk_template_type']) ) {
    wp_register_script( 'drstk_gallery',
        plugins_url( '../assets/js/gallery.js', __FILE__ ),
        array( 'jquery' ));
    wp_enqueue_script('drstk_gallery');
	}
}
add_action( 'wp_enqueue_scripts', 'drstk_gallery_shortcode_scripts');
