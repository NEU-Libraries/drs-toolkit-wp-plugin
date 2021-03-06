<?php

add_action( 'wp_ajax_reload_filtered_set', 'reload_filtered_set_ajax_handler' ); //for auth users
add_action( 'wp_ajax_nopriv_reload_filtered_set', 'reload_filtered_set_ajax_handler' ); //for nonauth users
function reload_filtered_set_ajax_handler()
{
  $collection_pid = drstk_get_pid();
    if ($_POST['reloadWhat'] == "mapReload") {
        echo drstk_map($_POST['atts'], $_POST['params']);
    }
    else if($_POST['reloadWhat'] == "facetReload") {
        if (isset($_POST['atts']['collection_id'])) {
            $url = drstk_api_url("drs", $collection_pid, "search", "geo", "per_page=10");
            if (isset($_POST['params']['f'])) {
                foreach ($_POST['params']['f'] as $facet => $facet_val) {
                    $url .= "&f[" . $facet . "][]=" . urlencode($facet_val);
                }
            }
            if (isset($_POST['params']['q']) && $_POST['params']['q'] != ''){
                $url .= "&q=". urlencode(sanitize_text_field($_POST['params']['q']));
            }
            $response = get_response($url);
            $facets_info_data = json_decode($response['output']);
            wp_send_json($facets_info_data);
        }
    }
    die();
}


add_action( 'wp_ajax_reloadRemainingMap', 'reloadRemainingMap_ajax_handler' ); //for auth users
add_action( 'wp_ajax_nopriv_reloadRemainingMap', 'reloadRemainingMap_ajax_handler' ); //for nonauth users
function reloadRemainingMap_ajax_handler()
{
    $a = get_post($_POST['post_id'])->post_content;
    $parsed_a = shortcode_parse_atts($a);
    echo drstk_map($parsed_a, $_POST['params']);
    die();
}

/* adds shortcode */
add_shortcode( 'drstk_map', 'drstk_map' );
function drstk_map( $atts , $params) {
  $errors = drstk_get_errors();
  $cache = get_transient(md5('PREFIX'.serialize($atts)));
  if($cache != NULL
      && ! WP_DEBUG
      && (!(isset($params))
          || $params == NULL)
      && !(isset($atts['collection_id']))
      ) {
         return $cache;
  }
      
  if(!isset($atts['collection_id'])) {
    $items = array_map('trim', explode(',', $atts['id']));
  }
  
  $map_api_key = drstk_get_map_api_key();
  $map_project_key = drstk_get_map_project_key();
  $story = isset($atts['story']) ? $atts['story'] : "no";
  $map_html = "";

  $shortcode = "<div id='map' data-story='".$story."' data-map_api_key='".$map_api_key."' data-map_project_key='".$map_project_key."'";
  foreach($atts as $key => $value){
        if(preg_match('/(.*)_color_desc_id/',$key)) {
            $shortcode .= " data-".$key."='".$atts[$key]."'";
        }
        if(preg_match('/(.*)_color_hex/',$key)) {
            $shortcode .= " data-".$key."='".$atts[$key]."'";
        }
    }

  /*
    If collection_id attribute is set, then load the DRS items directly using the search API.
  */
    $collectionItemsId = array();

    $facets_info_data = array();
    if(isset($atts['collection_id'])) {
        $url = drstk_api_url("drs", drstk_get_pid(), "search", "geo", "per_page=10");
        if(isset($params['page_no'])){
            $url .= "&page=" . $params['page_no'];
        }

        if (isset($params['f'])) {
            foreach ($params['f'] as $facet => $facet_val) {
                $url .= "&f[" . $facet . "][]=" . urlencode($facet_val);
            }
        }

        if (isset($params['q']) && $params['q'] != ''){
            $url .= "&q=". urlencode(sanitize_text_field($params['q']));
        }

        $response = get_response($url);
        $responseData = json_decode($response['output']);
        $num_pages = $responseData->pagination->table->num_pages;

        // @TODO make sense of response data
        if($num_pages == 0){
            return "No Result";
        }
        
        // @TODO this is a little backwards: I'd prefer to make make the request at
        // all if we've paged through everything, but that looks like big JS refactoring
        if(isset($params['page_no']) && $params['page_no'] > $num_pages) {
            return "All_Pages_Loaded";
        }

        $docs2 = $responseData->response->response->docs;
        foreach($docs2 as $docItem){
            $collectionItemsId [] = $docItem->id;
        }
        $items = $collectionItemsId;
    }
    foreach($items as $item) {
      $repo = drstk_get_repo_from_pid($item);
      if ($repo != "drs") {
        $pid = explode(":",$item); $pid = $pid[1];
      } else {
          $pid = $item;
      }
      if ($repo == "drs") {
        $url = drstk_api_url("drs", $item, "files", NULL, "solr_only=true");
        $response = get_response($url);
        $data = json_decode($response['output']);
        if (!isset($data->error)) {
          $data = $data->_source;
          $pid = $data->id;

          $coordinates = "";
          if(isset($data->subject_cartographics_coordinates_tesim)) {
            $coordinates = $data->subject_cartographics_coordinates_tesim[0];
          } else if (isset($data->subject_geographic_tesim)) {
            $location = $data->subject_geographic_tesim[0];
            $locationUrl = "https://maps.google.com/maps/api/geocode/json?address=" . urlencode($location) . '&key=' . GOOGLE_MAPS_GEOCODING_KEY;
            $response = get_response($locationUrl);
            $locationData = json_decode($response['output']);
            if (!isset($locationData->error)) {
              $coordinates = $locationData->results[0]->geometry->location->lat . "," . $locationData->results[0]->geometry->location->lng;
            }
          } else { //no geo data, skip it
            $coordinates = "";
          }
          if ($coordinates == "") {
            continue;
          }

        $title = $data->full_title_ssi;
        $permanentUrl = drstk_home_url() . "item/".$pid;
        $map_html .= "<div class='coordinates' data-pid='".$pid."' data-url='".$permanentUrl."' data-coordinates='".$coordinates."' data-title='".htmlspecialchars($title, ENT_QUOTES, 'UTF-8')."'";
        if (isset($atts['metadata'])) {
          $map_metadata = '';
          $metadata = explode(",",$atts['metadata']);
          foreach($metadata as $field) {
             if (isset($data->$field)) {
               $this_field = $data->$field;
              if (isset($this_field)) {
                if (is_array($this_field)) {
                  foreach($this_field as $val) {
                    if (is_array($val)) {
                      $map_metadata .= implode("<br/>",$val) . "<br/>";
                    } else {
                      $map_metadata .= $val ."<br/>";
                    }
                  }
                } else {
                  $map_metadata .= $this_field . "<br/>";
                }
              }
            }
          }
          $map_html .= " data-metadata='".$map_metadata."'";
        }


        $canonical_object = "";
        if (isset($data->canonical_class_tesim)) {
          if ($data->canonical_class_tesim[0] == "AudioFile" || $data->canonical_class_tesim[0] == "VideoFile"){
            $objects_url = "https://repository.library.northeastern.edu/api/v1/files/" . $item . "/content_objects";
            $response = get_response($objects_url);
            $objects_data = json_decode($response['output']);
            $data = (object) array_merge((array) $data, (array) $objects_data);
            if (isset($objects_data->canonical_object)){
              $canonical_object = insert_jwplayer($key, $val, $data, $data->fields_thumbnail_list_tesim[2]);
            }
          } else {
            $canonical_object = '<img src="https://repository.library.northeastern.edu'.$data->fields_thumbnail_list_tesim[2].'"/>';
          }
        }
        $map_html .= " data-media-content='".str_replace("'","\"", htmlentities($canonical_object))."'";

        $map_html .= "></div>";

      } else {
        $map_html = $errors['shortcodes']['fail'];
      }
    }
    if ($repo == "wp") {
      $post = get_post($pid);
      $url = $post->guid;
      $title = $post->post_title;
      $description = $post->post_excerpt;
      $custom = get_post_custom($pid);
      if (isset($custom['_map_coords'])) {
        $coordinates = $custom['_map_coords'][0];
      } else {
        $coordinates = "";
        continue;
      }
      $data = new StdClass;
      $data->full_title_ssi = array($post->post_title);
      $data->abstract_tesim = array($post->post_excerpt);
      $data->date_ssi = array($custom['_timeline_date'][0]);
      $data->canonical_object = new StdClass;
      $url = $post->guid;
      if (strpos($post->post_mime_type, "audio") !== false) {
        $type = "AudioFile";
      } else if (strpos($post->post_mime_type, "video") !== false) {
        $type = "VideoFile";
      } else {
        $type = "ImageMasterFile";
      }
      $data->canonical_class_tesim = $type;
      $data->canonical_object->$url = $type;
      $data->id=$post->ID;
      if(!is_numeric($coordinates[0])) {
        $location = $coordinates;
        $locationUrl = "https://maps.google.com/maps/api/geocode/json?location=" . urlencode($location) . '&key=' . GOOGLE_MAPS_GEOCODING_KEY;
        $response = get_response($locationUrl);
        $locationData = json_decode($response['output']);
        if (!isset($locationData->error)) {
          $coordinates = $locationData->results[0]->geometry->location->lat . "," . $locationData->results[0]->geometry->location->lng;
        }
      }
      $permanentUrl = drstk_home_url() . "item/wp:".$post->ID;
      $map_html .= "<div class='coordinates' data-pid='".$pid."' data-url='".$permanentUrl."' data-coordinates='".$coordinates."' data-title='".htmlspecialchars($title, ENT_QUOTES, 'UTF-8')."'";

      if (isset($atts['metadata'])) {
        $map_metadata = '';
        $metadata = explode(",",$atts['metadata']);
        foreach($metadata as $field){
           if (isset($data->$field)){
             $this_field = $data->$field;
            if (isset($this_field)){
              if (is_array($this_field)){
                foreach($this_field as $val){
                  if (is_array($val)){
                    $map_metadata .= implode("<br/>",$val) . "<br/>";
                  } else {
                    $map_metadata .= $val ."<br/>";
                  }
                }
              } else {
                $map_metadata .= $this_field . "<br/>";
              }
            }
          }
        }
        $map_html .= " data-metadata='".$map_metadata."'";
      }
      $canonical_object = "";
      if (isset($data->canonical_object)) {
        foreach($data->canonical_object as $key=>$val) {
          if ($val == 'VideoFile' || $val == 'AudioFile'){
            $canonical_object = do_shortcode('[video src="'.$post->guid.'"]');
          } else {
            $canonical_object = '<img src="'.$post->guid.'"/>';
          }
        }
      }
      $map_html .= " data-media-content='".str_replace("'","\"", htmlentities($canonical_object))."'";

      $map_html .= "></div>";
    }
    if ($repo == "dpla") {
      $url = drstk_api_url("dpla", $pid, "items");
      $response = get_response($url);
      $data = json_decode($response['output']);
      if (isset($data->docs[0]->object)){
        $url = $data->docs[0]->object;
      } else {
        $url = DPLA_FALLBACK_IMAGE_URL;
      }
      $title = $data->docs[0]->sourceResource->title;
      if (isset($data->docs[0]->sourceResource->description)) {
        $description = $data->docs[0]->sourceResource->description;
      } else {
        $description = "";
      }
      if (!is_array($title)) {
        $title = array($title);
      }
      $data->full_title_ssi = $title;
      $data->abstract_tesim = $description;
      $cre = "Creator,Contributor";
      if (isset($data->docs[0]->sourceResource->creator)) {
        $data->creator_tesim = $data->docs[0]->sourceResource->creator;
      } else {
        $data->creator_tesim = "";
      }
      $date = "Date Created";
      $data->key_date_ssi = isset($data->docs[0]->sourceResource->date->displayDate) ? $data->docs[0]->sourceResource->date->displayDate : array();
      $data->canonical_object = new StdClass;
      $data->canonical_object->$url = "Master Image";
      if (!isset($data->docs[0]->sourceResource->spatial)) {
        $coordinates = "";
        continue;
      }

      if(!isset($data->docs[0]->sourceResource->spatial[0]->coordinates)) {
        $location = $data->docs[0]->sourceResource->spatial[count($data->docs[0]->sourceResource->spatial)-1]->name;// . $data->docs[0]->sourceResource->spatial[0]->state;
        $locationUrl = "https://maps.google.com/maps/api/geocode/json?address=" . urlencode($location) . '&key=' . GOOGLE_MAPS_GEOCODING_KEY;
        $response = get_response($locationUrl);
        $locationData = json_decode($response['output']);
        if (!isset($locationData->error)) {
          $coordinates = $locationData->results[0]->geometry->location->lat . "," . $locationData->results[0]->geometry->location->lng;
        }
      } else {
        $coordinates = $data->docs[0]->sourceResource->spatial[0]->coordinates;
      }
      $permanentUrl = drstk_home_url() . "item/dpla:".$pid;
      $map_html .= "<div class='coordinates' data-pid='".$pid."' data-url='".$permanentUrl."' data-coordinates='".$coordinates."' data-title='".htmlspecialchars($title[0], ENT_QUOTES, 'UTF-8')."'";

      if (isset($atts['metadata'])) {
        $map_metadata = '';
        $metadata = explode(",",$atts['metadata']);
        foreach($metadata as $field) {
           if (isset($data->$field)) {
             $this_field = $data->$field;
            if (isset($this_field)) {
              if (is_array($this_field)) {
                foreach($this_field as $val) {
                  if (is_array($val)){
                    $map_metadata .= implode("<br/>",$val) . "<br/>";
                  } else {
                    $map_metadata .= $val ."<br/>";
                  }
                }
              } else {
                $map_metadata .= $this_field . "<br/>";
              }
            }
          }
        }
        $map_html .= " data-metadata='".$map_metadata."'";
      }
      $canonical_object = '<img src="'.$url.'"/>';
      $map_html .= " data-media-content='".str_replace("'","\"", htmlentities($canonical_object))."'";

      $map_html .= "></div>";
    }
  }


  if (isset($atts['custom_map_urls']) && ($atts['custom_map_urls'] != '')) {
    $custom_map_urls = explode(",",$atts['custom_map_urls']);
    $custom_map_titles = explode(",",$atts['custom_map_titles']);
    $custom_map_descriptions = explode(",",$atts['custom_map_descriptions']);
    $custom_map_locations = explode(",",$atts['custom_map_locations']);
    $custom_map_color_groups = explode(",",$atts['custom_map_color_groups']);
    foreach($custom_map_urls as $key=>$value) {
      $url = $value;
      $title = $custom_map_titles[$key];
      $title = trim($title,'\'');
      $description = $custom_map_descriptions[$key];
      $description = trim($description,'\'');
      $location = $custom_map_locations[$key];
      $colorGroup = $custom_map_color_groups[$key];

      $coordinates = "";
      $locationUrl = "https://maps.google.com/maps/api/geocode/json?address=" . urlencode($location) . '&key=' . GOOGLE_MAPS_GEOCODING_KEY;
      $response = get_response($locationUrl);
      $locationData = json_decode($response['output']);
      if (!isset($locationData->error)) {
        $coordinates = $locationData->results[0]->geometry->location->lat . "," . $locationData->results[0]->geometry->location->lng;
      }

      $map_html .= "<div class='custom-coordinates' data-url=".$url." data-coordinates='".$coordinates."' data-title='".htmlspecialchars($title, ENT_QUOTES, 'UTF-8')."' data-description='".htmlspecialchars($description, ENT_QUOTES, 'UTF-8')."' data-colorGroup=".$colorGroup."";
      $map_html .= "></div>";
    }
  }

  $shortcode .= ">".$map_html."</div>";
  $cache_output = $shortcode;
  $cache_time = 1000;
  set_transient(md5('PREFIX'.serialize($atts)) , $cache_output, $cache_time * 60);

    if(isset($atts['collection_id'])) {
        wp_register_script('drstk_map_col', DRS_PLUGIN_URL . '/assets/js/mapCollection.js', array('jquery'));
        wp_enqueue_script('drstk_map_col');

        $reload_filtered_set_drs_nonce = wp_create_nonce('reload_filtered_set_drs');

        $map_nonce = wp_create_nonce('map_nonce');

        $map_obj = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $map_nonce,
            'home_url' => drstk_home_url()
        );

        $facets_info_data_obj = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $reload_filtered_set_drs_nonce,
            'data' => $responseData,
            'home_url' => drstk_home_url(),
            "atts" => $atts,
            "map_obj" => $map_obj
        );
        wp_localize_script('drstk_map_col', 'facets_info_data_obj', $facets_info_data_obj);
    }

    return $shortcode;
}

function drstk_map_shortcode_scripts() {

  global $post, $wp_query;

    if( is_a( $post, 'WP_Post' ) 
        && has_shortcode( $post->post_content, 'drstk_map')
        && !isset($wp_query->query_vars['drstk_template_type']) ) {
    
          
        wp_register_style('drstk_cdn_leaflet_css', 'https://unpkg.com/leaflet@1.3.4/dist/leaflet.css');
        wp_enqueue_style('drstk_cdn_leaflet_css');
        
        wp_add_inline_style('drstk_cdn_leaflet_css', "#map {height: 600px}");
          
        wp_register_script('drstk_cdn_leaflet_js',
                           'https://unpkg.com/leaflet@1.3.4/dist/leaflet.js',
                            array( 'jquery' ));
        wp_enqueue_script('drstk_cdn_leaflet_js');
        

        
        wp_register_script('drstk_cdn_leaflet_marker_cluster_js',
                           'https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js',
                            array('jquery', 'drstk_cdn_leaflet_js')
            
            );
        wp_enqueue_script('drstk_cdn_leaflet_marker_cluster_js');
        
        
        wp_register_style('drstk_cdn_leaflet_marker_cluster_css',
            'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css');
        wp_enqueue_style('drstk_cdn_leaflet_marker_cluster_css');
        
        wp_register_style('drstk_cdn_leaflet_marker_cluster_default_css',
            'https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css');
        wp_enqueue_style('drstk_cdn_leaflet_marker_cluster_default_css');
        
        
        wp_register_script('drstk_cdn_leaflet_easy_button_js',
            'https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.js',
            array('jquery', 'drstk_cdn_leaflet_js')
            );
        wp_enqueue_script('drstk_cdn_leaflet_easy_button_js');
        
        wp_register_style('drstk_cdn_leaflet_easy_button_css',
                          'https://cdn.jsdelivr.net/npm/leaflet-easybutton@2/src/easy-button.css');
        wp_enqueue_style('drstk_cdn_leaflet_easy_button_css');
        
        wp_register_script( 'drstk_map',
            DRS_PLUGIN_URL. '/assets/js/map.js',
            array( 'jquery' ));
        wp_enqueue_script('drstk_map');
    
        $map_nonce = wp_create_nonce( 'map_nonce' );
        $temp =  shortcode_parse_atts($post->post_content);
        $collectionSet = "";
    
        if(isset($temp['collection_id']) && $temp['collection_id'] != '') {
            $collectionSet = "checked";
        }
        $map_obj = array(
          'ajax_url' => admin_url('admin-ajax.php'),
          'nonce'    => $map_nonce,
          'home_url' => drstk_home_url(),
          'post_id' => $post->ID,
          'collectionSet' => $collectionSet
        );
        wp_localize_script( 'drstk_map', 'map_obj', $map_obj );
  }
}
add_action( 'wp_enqueue_scripts', 'drstk_map_shortcode_scripts');



// the filter doesn't target based on what's passed,
// so make one general use of the filter and hope it doesn't get too long (it will)
function drstk_script_loader_tag_filter($tag, $handle, $src) {
  switch ($handle) {
    case 'drstk_add_cdn_leaflet_js_atts':
      $tag = '<script type="text/javascript" src="' . $src . '"
              integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
              crossorigin=""></script>';
      break;
      
  }
  return $tag;
}

function drstk_style_loader_tag_filter($tag, $handle, $href) {
  switch ($handle) {
    case 'drstk_add_cdn_leaflet_css_atts':
      $tag = '<link rel="stylesheet" href="' . $href . '"
              integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
              crossorigin=""></link>';
      break;
  }
  return $tag;
}

add_filter('script_loader_tag', 'drstk_script_loader_tag_filter', 10, 3);
add_filter('style_loader_tag', 'drstk_style_loader_tag_filter', 10, 3);
