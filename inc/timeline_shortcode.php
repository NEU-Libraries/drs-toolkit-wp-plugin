<?php
/* adds shortcode */
add_shortcode( 'drstk_timeline', 'drstk_timeline' );
function drstk_timeline( $atts ){
  global $errors;
  $cache = get_transient(md5('PREFIX'.serialize($atts)));

  if($cache) {
      return $cache;
  }
  $neu_ids = explode(", ",$atts['id']);
  $timeline_increments = $atts['increments'];
  $color_codes = array("red", "green", "blue", "yellow", "orange");
  $current_color_code_id_values = array();
  $current_color_legend_desc_values = array();
  $index_color_pair = array();
  foreach($color_codes as $color_code){
	  $current_color_code_id_string = $color_code . "_id";
	  $current_color_legend_desc_string = $color_code . "_desc";
	  $current_color_code_id_value = $atts[$current_color_code_id_string];
	  $current_color_legend_desc_value = $atts[$current_color_legend_desc_string];
	  
	  if(!is_null($current_color_code_id_value)){
		  $current_color_code_ids = explode(",", $current_color_code_id_value);
		  foreach($current_color_code_ids as $current_color_code_id){
			  $current_color_code_id_values[str_replace(' ', '', $current_color_code_id)] = $color_code;
		  }
	  }
	  if(!is_null($current_color_legend_desc_value)){$current_color_legend_desc_values[$color_code] = $current_color_legend_desc_value;}
  }
  
  $event_list = array();
  $timeline_html = "";
  $counter = 1;
  foreach($neu_ids as $current_key => $neu_id){
    $url = "https://repository.library.northeastern.edu/api/v1/files/" . $neu_id;
    $data = get_response($url);
    $data = json_decode($data);
    
    if (!isset($data->error)){
      $pid = $data->pid;
      $key_date = $data->key_date;
      $current_array = array();
      $breadcrumbs = $data->breadcrumbs;      
      
      $thumbnail_url = $data->thumbnails[2];
      
      $caption_headline_tag = "neu:5m60qx652";
      $caption = $breadcrumbs->$caption_headline_tag;
      
      $headline = $breadcrumbs->$caption_headline_tag;
      $text = $breadcrumbs->$pid;
     
      $keys = (array)$key_date;      
      $key_date_explode = explode("/",array_keys($keys)[0]);
      
      
      $timeline_html .= "<div class='timelineclass' data-url='".$thumbnail_url."' data-caption='".$caption."' data-credit=' ' data-year='".$key_date_explode[0]."' data-month='".$key_date_explode[1]."' data-day='".$key_date_explode[2]."' data-headline='".$headline."' data-text='".$text."'>";
      $timeline_html .= "</div>";
    }else {
      $timeline_html = $errors['shortcodes']['fail'];
    }
    $present_id_color = $current_color_code_id_values[str_replace(' ', '', $neu_id)];
	$index_color_pair[array_keys($keys)[0]] = $present_id_color;
  }
  $color_ids_html_data = '';
  $color_desc_html_data = '';
  $sample_id_html_data = '';
  forEach($current_color_legend_desc_values as $key => $value){
	  $color_desc_html_data .= "<tr><td width=\"1%\" bgcolor=\"". $key ."\"></td><td>" . $value ."</td></tr>";
  }
  forEach($index_color_pair as $key_index => $color_value){
	  $color_ids_html_data .= " data-" . str_replace('/', '', $key_index) . "='" . $color_value . "' ";
  }
  
  if (isset($atts['custom_timeline_urls']) && ($atts['custom_timeline_urls'] != '')) {
    $custom_timeline_urls = explode(",",$atts['custom_timeline_urls']);
    $custom_timeline_titles = explode(",",$atts['custom_timeline_titles']);
    $custom_timeline_descriptions = explode(",",$atts['custom_timeline_descriptions']);
    $custom_timeline_date = explode(",",$atts['custom_timeline_date']);
    $custom_timeline_color_groups = explode(",",$atts['custom_timeline_color_groups']);

    foreach($custom_timeline_urls as $key=>$value) {
      $url = $value;
      $title = $custom_timeline_titles[$key];
      $title = trim($title,'\'');
      $description = $custom_timeline_descriptions[$key];
      $description = trim($description,'\'');
      $date = explode('/',$custom_timeline_date[$key]);
      $year = trim($date[0], '\'');
      $month = $date[1];
      $day = trim($date[2], '\'');
      $colorGroup = $custom_timeline_color_groups[$key];

      $timeline_custom_html .= "<div class='custom-timeline' data-url=".$url." data-year='".$year."' data-month='".$month."' data-day='".$day."' data-title='".htmlspecialchars($title, ENT_QUOTES, 'UTF-8')."' data-description='".htmlspecialchars($description, ENT_QUOTES, 'UTF-8')."' data-colorGroup=".$colorGroup."";
      $timeline_custom_html .= "></div>";
    }
  }
  
  $shortcode = "<div id='timeline-embed' style=\"width: 100%; height: 500px\"></div>";
  $shortcode .= "<div id='timeline-table'><table id='timeline-table-id' style=\" float: right; width: 200px;\">". $color_desc_html_data ."</table></div>";
  $shortcode .= "<div id='timeline'>".$timeline_html."</div>";
  $shortcode .= "<div id='timeline-increments' data-increments='".$timeline_increments."'></div>";
  $shortcode .= "<div id='timeline-custom-data'>".$timeline_custom_html."</div>";
  
	if($color_ids_html_data != '' || $color_desc_html_data != ''){
	  $shortcode .= "<div id='timeline-color-ids'" . $color_ids_html_data . "></div>";
	}
  $cache_output = $shortcode;
  $cache_time = 1000;
  set_transient(md5('PREFIX'.serialize($atts)) , $cache_output, $cache_time * 60);
  return $shortcode;
}

function drstk_timeline_shortcode_scripts() {
	global $post;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_timeline') ) {
    wp_register_script( 'drstk_timelinejs',
        plugins_url( '../assets/js/timeline/timeline.js', __FILE__ ),
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
