<?php
/* adds shortcode */
add_shortcode( 'drstk_timeline', 'drstk_timeline' );
function drstk_timeline( $atts ){
  global $errors;
  $cache = get_transient(md5('PREFIX'.serialize($atts)));

  if($cache) {
      return $cache;
  }
  $neu_ids = array_map('trim', explode(',', $atts['id']));
  $timeline_increments = $atts['increments'];
  $color_codes = array("red", "green", "blue", "yellow", "orange");
  $current_color_code_id_values = array();
  $current_color_legend_desc_values = array();
  $index_color_pair = array();
  foreach($color_codes as $color_code){
	  $current_color_code_id_string = $color_code . "_id";
	  $current_color_legend_desc_string = $color_code . "_desc";
    if (isset($atts[$current_color_code_id_string])){
      $current_color_code_id_value = $atts[$current_color_code_id_string];
    } else {
      $current_color_code_id_value = NULL;
    }
    if (isset($atts[$current_color_legend_desc_string])){
      $current_color_legend_desc_value = $atts[$current_color_legend_desc_string];
    } else {
      $current_color_legend_desc_value = NULL;
    }

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

    $repo = drstk_get_repo_from_pid($neu_id);
    if ($repo != "drs"){$pid = explode(":",$neu_id); $pid = $pid[1];} else {$pid = $neu_id;}
    if($repo == "drs"){
      $url = "https://repository.library.northeastern.edu/api/v1/files/" . $neu_id;
      $data = get_response($url);
      $data = json_decode($data);

      if (!isset($data->error)){
        $pid = $data->pid;
        $key_date = $data->key_date;
        $current_array = array();
        $breadcrumbs = $data->breadcrumbs;

        $thumbnail_url = $data->thumbnails[2];

        if (isset($atts['metadata'])){
          $timeline_metadata = '';
          $metadata = explode(",",$atts['metadata']);
          foreach($metadata as $field){
            if (isset($data->mods->$field)) {
              $this_field = $data->mods->$field;
              if (isset($this_field[0])) {
                $timeline_metadata .= $this_field[0] . "<br/>";
              }
            }
          }
          $text = htmlentities($timeline_metadata);
        } else {
          $text = "<p>&nbsp;</p>";
        }
        if ($text == NULL || $text == ""){
          $text = "<p>&nbsp;</p>";
        }
        $caption = "";
        $headline = htmlentities($data->mods->Title[0]);

        $keys = (array)$key_date;
        $just_keys = array_keys($keys);
        $key_date_explode = explode("/",$just_keys[0]);


        $timeline_html .= "<div class=\"timelineclass\" data-url=\"".$thumbnail_url."\" data-caption=\"".$caption."\" data-credit=\" \" data-year=\"".$key_date_explode[0]."\" data-month=\"".$key_date_explode[1]."\" data-day=\"".$key_date_explode[2]."\" data-headline=\"".$headline."\" data-text=\"".$text."\" data-pid=\"".$pid."\" data-full=\"".drstk_home_url()."item/".$pid."\">";
        $timeline_html .= "</div>";
      }else {
        $timeline_html = $errors['shortcodes']['fail'];
      }
      if (isset($current_color_code_id_values[str_replace(' ', '', $neu_id)])) {
        $present_id_color = $current_color_code_id_values[str_replace(' ', '', $neu_id)];
      } else {
        $present_id_color = NULL;
      }
  	  $index_color_pair[str_replace(":","",$pid)] = $present_id_color;
    }
    if ($repo == "wp"){
      if (!isset($timeline_custom_html)){$timeline_custom_html = "";}
      $post = get_post($pid);
      $url = $post->guid;
      if (strpos($data->post_mime_type, "audio") !== false || strpos($data->post_mime_type, "video") !== false){
        $url = drstk_home_url()."/wp-includes/images/media/video.png";
      }
      $title = $post->post_title;
      $description = $post->post_excerpt;
      $custom = get_post_custom($pid);
      $date = $custom['_timeline_date'][0];
      if ($date != ""){
        $date = explode("/", $date);
        $year = $date[0];
        $month = $date[1];
        $day = $date[2];
        if (isset($current_color_code_id_values["wp:".$pid])){
          $colorGroup = $current_color_code_id_values["wp:".$pid];
          $index_color_pair["wp".$pid] = $colorGroup;
        }
        $timeline_custom_html .= "<div class='timelineclass' data-credit='' data-url=".$url." data-year='".$year."' data-month='".$month."' data-day='".$day."' data-caption='' data-headline='".htmlspecialchars($title, ENT_QUOTES, 'UTF-8')."' data-text='".htmlspecialchars($description, ENT_QUOTES, 'UTF-8')."' data-pid='wp".$post->ID."' data-full='".drstk_home_url()."item/wp:".$post->ID."'";
        $timeline_custom_html .= "></div>";
      } else {
        //no date
      }
    }
    if ($repo == "dpla"){
      if (!isset($timeline_custom_html)){$timeline_custom_html = "";}
      $data = get_response("http://api.dp.la/v2/items/".$pid."?api_key=b0ff9dc35cb32dec446bd32dd3b1feb7");
      $data = json_decode($data);
      if (isset($data->docs[0]->object)){
        $url = $data->docs[0]->object;
      } else {
        $url = "https://dp.la/info/wp-content/themes/berkman_custom_dpla/images/logo.png";
      }
      $title = $data->docs[0]->sourceResource->title;
      if (is_array($title)){
        $title = implode("<br/>",$title);
      }
      if (isset($data->docs[0]->sourceResource->description)){
        $description = $data->docs[0]->sourceResource->description[0];
      } else {
        $description = "";
      }
      $text = $description;
      $data->mods = new StdClass;
      $abs = "Abstract/Description";
      $data->mods->$abs = $description;
      if (isset($data->docs[0]->sourceResource->creator)){
        $data->mods->Creator = $data->docs[0]->sourceResource->creator;
      }
      if (isset($atts['metadata'])){
        $timeline_metadata = '';
        $metadata = explode(",",$atts['metadata']);
        foreach($metadata as $field){
          if (isset($data->mods->$field)) {
            $this_field = $data->mods->$field;
            if (is_array($this_field)) {
              $timeline_metadata .= $this_field[0] . "<br/>";
            } else {
              $timeline_metadata .= $this_field . "<br/>";
            }
          }
        }
        $text = htmlentities($timeline_metadata);
      }
      if (isset($data->docs[0]->sourceResource->rights)){
        if (is_array($data->docs[0]->sourceResource->rights)){
          $credit = implode("<br/>",$data->docs[0]->sourceResource->rights);
        } else {
          $credit = $data->docs[0]->sourceResource->rights;
        }
      } else {
        $credit = "";
      }
      if (isset($data->docs[0]->sourceResource->date->displayDate) && $data->docs[0]->sourceResource->date->displayDate != "Unknown"){
        $date = $data->docs[0]->sourceResource->date->displayDate;
        $date = explode("-", $date);
        $year = $date[0];
        if (strlen($year) > 4){
          $year = $data->docs[0]->sourceResource->date->begin;
        }
        $month = 1;
        $day = 1;
        if (isset($current_color_code_id_values["dpla:".$pid])){
          $colorGroup = $current_color_code_id_values["dpla:".$pid];
          $index_color_pair["dpla".$pid] = $colorGroup;
        } else {
          $colorGroup = "";
        }
        $timeline_custom_html .= "<div class='timelineclass' data-credit='".$credit."' data-url=".$url." data-year='".$year."' data-month='".$month."' data-day='".$day."' data-caption=' ' data-headline='".htmlspecialchars($title, ENT_QUOTES, 'UTF-8')."' data-text='".$text."' data-pid='dpla".$pid."' data-full='".drstk_home_url()."item/dpla:".$pid."' data-colorGroup=".$colorGroup."";
        $timeline_custom_html .= "></div>";
      } else {
        //no date
      }
    }
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
    if (!isset($timeline_custom_html)){$timeline_custom_html = "";}
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
  if (isset($timeline_custom_html)){
    $shortcode .= "<div id='timeline-custom-data'>".$timeline_custom_html."</div>";
  }

	if($color_ids_html_data != '' || $color_desc_html_data != ''){
	  $shortcode .= "<div id='timeline-color-ids'" . $color_ids_html_data . "></div>";
	}
  $cache_output = $shortcode;
  $cache_time = 1000;
  set_transient(md5('PREFIX'.serialize($atts)) , $cache_output, $cache_time * 60);
  return $shortcode;
}

function drstk_timeline_shortcode_scripts() {
	global $post, $wp_query, $DRS_PLUGIN_URL;
	if( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'drstk_timeline') && !isset($wp_query->query_vars['drstk_template_type']) ) {
    wp_register_script( 'drstk_timelinejs',
        $DRS_PLUGIN_URL . '/assets/js/timeline/timeline.js',
        array( 'jquery' ));
    wp_enqueue_script('drstk_timelinejs');
    wp_register_style( 'drstk_timelinejs_css', $DRS_PLUGIN_URL . '/assets/css/timeline.css');
    wp_enqueue_style( 'drstk_timelinejs_css');
    wp_register_script( 'drstk_timelinepage',
      $DRS_PLUGIN_URL . '/assets/js/timelinepage.js',
        array( 'jquery' ));
    wp_enqueue_script('drstk_timelinepage');
	}
}
add_action( 'wp_enqueue_scripts', 'drstk_timeline_shortcode_scripts');
