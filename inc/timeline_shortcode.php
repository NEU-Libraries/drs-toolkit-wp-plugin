<?php
/* adds shortcode */
add_shortcode( 'drstk_timeline', 'drstk_timeline' );
function drstk_timeline( $atts ){
  global $errors;
//   $cache = get_transient(md5('PREFIX'.serialize($atts)));

//   if($cache) {
//       return $cache;
//   }
  $neu_ids = explode(", ",$atts['id']);
  
  $event_list = array();
  foreach($neu_ids as $neu_id){
    // $url = "https://repository.library.northeastern.edu/api/v1/files/" . $neu_id;
    // $data = get_response($url);
    // $data = json_decode($data);
    
    // if (!isset($data->error)){
    //   $pid = $data->pid;
    //   $key_date = $data->key_date;
    //   $current_array = array();
    //   $breadcrumbs = $data->breadcrumbs;
      
    //   $event_object = array();
      
    //   $media = array();
    //   $start_date = array();
    //   $text_object = array();
      
    //   $thumbnail_url = $data->thumbnails[2];
    //   $caption = $breadcrumbs['neu:5m60qx652'];
      
    //   $headline = $breadcrumbs['neu:5m60qx652'];      
    //   $text = $breadcrumbs->[$pid];
            
    //   $media['url'] = $thumbnail_url;
    //   $media['caption'] = $caption;
    //   $media['credit'] = "";
      
    //   $key_date_explode = explode("/",$key_date);
    //   $start_date["year"] = $key_date_explode[0];  
    //   $start_date["month"] = $key_date_explode[1];
    //   $start_date["day"] = $key_date_explode[2];
      
    //   $text_object["headline"] = $headline;
    //   $text_object["text"] = $text;
      
    //   $event_object["media"] = $media;
    //   $event_object["start_date"] = $start_date;
    //   $event_object["text"] = $text_object;
      
    //   array_push($event_list, $event_object);
    }
  }
//   $shortcode = "<div id='timeline-embed' style=\"width: 100%; height: 600px\"></div>";
//   $shortcode .= "<script type=\"text/javascript\">";
//   $shortcode .= "window.timeline = new TL.Timeline('timeline-embed',";
//   $shortcode .= json_encode($event_list);
//   $shortcode .= ");";
//   $shortcode .= "</script>";
        $shortcode = "";
  $cache_output = $shortcode;
  $cache_time = 1000;
  set_transient(md5('PREFIX'.serialize($atts)) , $cache_output, $cache_time * 60);
  return $shortcode;
}

function drstk_timeline_shortcode_scripts() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_timeline') ) {
    wp_register_script( 'drstk_timelinejs',
        plugins_url( '../assets/js/timeline.js', __FILE__ ),
        array( 'jquery' ));
    wp_enqueue_script('drstk_timelinejs');
    wp_register_style( 'drstk_timelinejs_css',plugins_url('../assets/css/timeline.css', __FILE__));
    wp_enqueue_style( 'drstk_timelinejs_css');
    wp_register_script( 'drstk_timeline',
        plugins_url( '../assets/js/timelinepage.js', __FILE__ ),
        array( 'jquery' ));
    wp_enqueue_script('drstk_timeline');
	}
}
add_action( 'wp_enqueue_scripts', 'drstk_timeline_shortcode_scripts');
