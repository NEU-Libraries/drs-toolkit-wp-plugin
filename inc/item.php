<?php
global $item_pid, $data, $collection, $meta_options, $errors;
$collection = drstk_get_pid();
$meta_options = get_option('drstk_item_page_metadata');
$assoc_meta_options = drstk_get_assoc_meta_options();
$errors = drstk_get_errors();

function get_item_details($data, $meta_options){
  global $errors;
  if (check_for_bad_data($data)){
    return false;
  }
  $html = '';
  if (isset($data->mods)){ //mods
    $html .= parse_metadata($data->mods, $meta_options, $html);
  } else if (isset($data->_source)){//solr_only = true
    $html .= parse_metadata($data->_source, $meta_options, $html, true);
  }
  $niec_facets = get_option('drstk_niec_metadata');
  $niec_facets_to_display = array();
  if (is_array($niec_facets)){
    foreach($niec_facets as $facet){
      $niec_facets_to_display[$facet] = drstk_get_facet_name($facet, true);
    }
  }
  if (get_option('drstk_niec') == 'on' && count($niec_facets_to_display) > 0 && isset($data->niec)){
    $html = parse_metadata($data->niec, $niec_facets_to_display, $html);
  }
  return $html;
}

function parse_metadata($data, $meta_options, $html, $solr=false){
  if ($solr){//this is necessary to not use default solr ordering
    $arr1 = (array) $data;
    $arr2 = $meta_options;
    $data = array();
    foreach ($arr2 as $key=>$val) {
      $data[$val] = $arr1[$val];
    }
  }

  foreach($data as $key => $value){
    if (($meta_options == NULL) || in_array($key, $meta_options) || array_key_exists($key, $meta_options)){
      $html .= "<div class='drs-field-label'><b>";
      if (!isset($meta_options[$key])){
        $html .= titleize($key);
      } else {
        $html .= $meta_options[$key];
      }
      $html .= "</b></div><div class='drs-field-value'>";
      if (is_array($value)){
        for ($i =0; $i<count($value); $i++){
          if (substr($value[$i], 0, 4) == "http"){
            $html .= '<a href="'.$value[$i].'" target="_blank">'.$value[$i].'</a>';
          } else {
            $html .= $value[$i];
          }
          if ($i != count($value)-1){
            $html .= "<br/> ";
          }
        }
      } else {
        $html .= $value;
      }
      $html .= "</div>";
    }
  }
  return $html;
}

function get_download_links(){
  global $data;
  if (check_for_bad_data($data)){
    return false;
  }
  echo "<br/><h4>Downloads</h4>";
  foreach($data->content_objects as $key=>$val){
    if ($val != "Thumbnail Image"){
      if ($val == 'Video File'){
        $av_pid = explode("/", $key);
        $av_pid = end($av_pid);
        echo " <a href='".drstk_home_url()."download/".$av_pid."' class='themebutton button btn' data-label='download' data-pid='".$data->pid."'>".$val."</a> ";
      } else {
        echo " <a href='".$key."' target='_blank' class='themebutton button btn' data-label='download' data-pid='".$data->pid."'>".$val."</a> ";
      }
    }
  }
}

function get_item_title(){
  global $item_pid, $data, $url;
  $url = "https://repository.library.northeastern.edu/api/v1/files/" . $item_pid;
  $data = get_response($url);
  $data = json_decode($data);
  if (check_for_bad_data($data)){
    return false;
  }
  echo $data->mods->Title[0];
}

function get_item_breadcrumbs(){
  global $item_pid, $data, $breadcrumb_html, $collection;
  if (check_for_bad_data($data)){
    return false;
  }
  $breadcrumb_html = array();
  $end = false;
  $breadcrumbs = $data->breadcrumbs;
  if (array_key_exists($collection,$breadcrumbs)){
    foreach($breadcrumbs as $pid=>$title){
      if ($pid == $item_pid){
        $breadcrumb_html[]= "<a href='".drstk_home_url()."item/".$pid."'> ".$title."</a>";
      } else if ($pid == $collection){
        $breadcrumb_html[]= "<a href='".drstk_home_url()."browse'>Browse</a>";
        $end = true;
      } else if ($end == true) {
      } else {
        $breadcrumb_html[]= "<a href='".drstk_home_url()."collection/".$pid."'> ".$title."</a>";
      }
    }
  } else {
    $breadcrumb_html[]= "<a href='".drstk_home_url()."item/".$item_pid."'> ".$data->mods->Title[0]."</a>";
    $breadcrumb_html[] = "<a href='".drstk_home_url()."browse'>Browse</a>";
  }
  echo implode(" > ", array_reverse($breadcrumb_html));
}

function get_item_image(){
  global $item_pid, $data, $errors;
  if (check_for_bad_data($data)){
    echo check_for_bad_data($data);
    return false;
  }
  if (isset($data->thumbnails)){
    $img = $data->thumbnails[count($data->thumbnails)-2];
  }
  if (isset($data->canonical_object)){
    $val = current($data->canonical_object);
    $key = key($data->canonical_object);
    if ($val == 'Master Image'){
      $zoom_img = $data->thumbnails[count($data->thumbnails)-1];
      echo  '<img id="drs-item-img" src="'.$img.'" data-zoom-image="'.$zoom_img.'"/>';
      echo '<script type="text/javascript"> jQuery("#drs-item-img").elevateZoom();</script>';
    } else if ($val == 'PDF'){
      if (isset($data->mods->Location) && strpos($data->mods->Location[0], "issuu") !== FALSE){
        $location_href = explode("'", strval(htmlentities($data->mods->Location[0])));
        if (count($location_href) == 1){
          $location_href = explode('"', strval(htmlentities($data->mods->Location[0])));
        }
        $issu_id = explode('?',$location_href[1]);
        $issu_id = explode('=',$issu_id[1]);
        $issu_id = $issu_id[1];
        echo '<div data-configid="'.$issu_id.'" style="width:100%; height:500px;" class="issuuembed"></div><script type="text/javascript" src="//e.issuu.com/embed.js" async="true"></script>';
      } else {
        echo  '<img id="drs-item-img" src="'.$img.'" />';
      }
    } else if ($val == 'Video File' || $val == 'Audio File'){
      print(insert_jwplayer($key, $val, $data, $img));
    }
  } else {
    //case where there is no canonical_objects set
    echo  '<img id="drs-item-img" src="'.$img.'" />';
  }
  if (isset($data->page_objects)){
    $pages = $data->page_objects;
    if (count($pages) > 0){
      $gallery_html = '<div class="carousel slide" id="single_carousel">';
      $img_html = "";
      $i = 0;
      foreach($pages as $img=>$ordinal_value){
        $img_html .= "<div class='item";
        if ($i == 0){
          $img_html .= " active";
        }
        $img_html .= "'><a href='' data-toggle='modal' data-target='#drs_item_modal' class='drs_page_image' data-img='".$img."' data-ordinal_value='".$ordinal_value."'><img";
        if ($i == 0){
          $img_html .= " src='".$img."'";
        } else {
          $img_html .= " data-src='".$img."'";
        }
        $img_html .= "/></a><div class='carousel-caption'><a href='' data-toggle='modal' data-target='drs_item_modal' class='drs_item_modal' data-img='".$img."' data-ordinal_value='".$ordinal_value."'>Page ".$ordinal_value."</a></div></div>";
        $i++;
      }
      $gallery_html .= '<div class="carousel-inner">'.$img_html.'</div>';
      $gallery_html .= '<a class="left carousel-control" href="#single_carousel" role="button" data-slide="prev"><i class="glyphicon-chevron-left fa fa-chevron-left" aria-hidden="true"></i><span class="sr-only">Previous</span></a><a class="right carousel-control" href="#single_carousel" role="button" data-slide="next"><i class="glyphicon-chevron-right fa fa-chevron-right" aria-hidden="true"></i><span class="sr-only">Next</span></a>';
      $gallery_html .= '</div>';
      $gallery_html .= '<div class="modal fade" id="drs_item_modal"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">Page Images</h4></div><div class="modal-body"><nav class="pagination"><ul class="pagination"><li><a href="#" class="drs_page_image prev"><span class="fa fa-chevron-left"></span></a></li>';
      foreach($pages as $img=>$ordinal_value){
        $gallery_html .= "<li><a href='#' class='drs_page_image' data-img='".$img."' data-ordinal_value='".$ordinal_value."'>".$ordinal_value."</a></li>";
      }
      $gallery_html .= '<li><a href="#" class="drs_page_image next"><span class="fa fa-chevron-right"></span></a></li></ul></nav><div class="body"></div></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div><!-- /.modal-content --></div><!-- /.modal-dialog --></div><!-- /.modal -->';
      echo $gallery_html;
    }
  }
}

function get_associated_files(){
  global $data, $errors, $assoc_meta_options;
  if (isset($data->associated) && ($data->associated != NULL) && (get_option('drstk_assoc') == 'on')){
    $associated_html = '';
    $title = (get_option('drstk_assoc_title') != '') ? get_option('drstk_assoc_title') : 'Associated Files';
    $associated_html .= "<div class='panel panel-default assoc_files'><div class='panel-heading'>".$title."</div><div class='panel-body'>";
    // foreach($data->associated as $assoc_pid => $assoc_title){ //disabling multivalued associated files until a new less resource intensive api call for associated files exists
      $assoc_pid = key(get_object_vars($data->associated)); //using this just to get the first title
      $assoc_title = $data->associated->$assoc_pid; //using this just to get the first title
      $url = "https://repository.library.northeastern.edu/api/v1/files/" . $assoc_pid . "?solr_only=true";
      $assoc_data = get_response($url);
      $assoc_data = json_decode($assoc_data);
      if (check_for_bad_data($assoc_data)){
        return false;
      } else {
        if (isset($assoc_data->_source->fields_thumbnail_list_tesim)){
          $associated_html .= "<a href='".drstk_home_url()."item/".$assoc_data->_source->id."'><img src='https://repository.library.northeastern.edu".$assoc_data->_source->fields_thumbnail_list_tesim[1]."'/></a>";
        }
        $associated_html .= get_item_details($assoc_data, $assoc_meta_options);
      }
    // }
    $associated_html .= "</div></div>";
    echo $associated_html;
  }
}

function check_for_bad_data($data){
  global $errors;
  if ($data == null) {
    return $errors['item']['fail'];
  } else if (isset($data->error)) {
    return $errors['item']['no_results'];
  }
}

function insert_jwplayer($av_pid, $canonical_object_type, $data, $drs_item_img) {
  global $errors;
  $av_pid = explode("/", $av_pid);
  $av_pid = end($av_pid);
  $encoded_av_pid = str_replace(':','%3A', $av_pid);
  $av_dir = substr(md5("info:fedora/".$av_pid."/content/content.0"), 0, 2);
  $av_type = "";
  if ($data->thumbnails){
    $av_poster = $data->thumbnails[3];
  }
  if ($canonical_object_type == 'Video File'){
    $av_provider = 'video';
    $av_type = "MP4";
  }
  if ($canonical_object_type == 'Audio File'){
    $av_provider = 'sound';
    $av_type = "MP3";
  }
  $user_agent = $_SERVER['HTTP_USER_AGENT'];
  if (stripos( $user_agent, 'Chrome') !== false){
    $av_for_ext = $av_type;
    $full_pid = "info%3Afedora%2F".$encoded_av_pid."%2Fcontent%2Fcontent.0";
  } elseif (stripos( $user_agent, 'Safari') !== false) {
    $av_for_ext = strtolower($av_type);
    $full_pid = urlencode("info%3Afedora%2F".$encoded_av_pid."%2Fcontent%2Fcontent.0");
  } else {
    $av_for_ext = strtolower($av_type);
    $full_pid = "info%3Afedora%2F".$encoded_av_pid."%2Fcontent%2Fcontent.0";
  }

  $numeric_pid = str_replace(":", "-", $av_pid);
  $id_img = 'drs-item-img-'.$numeric_pid;
  $id_video = 'drs-item-video-'.$numeric_pid;

  $html = '<img id="'.$id_img.'" src="'.$drs_item_img.'" />';
  $html .= '<div id="'.$id_video.'"></div>';
  $html .= '<script type="text/javascript">
  jwplayer.key="gi5wgpwDtAXG4xdj1uuW/NyMsECyiATOBxEO7A==";
  var primary = "flash";
  if (typeof swfobject == "undefined" || swfobject.getFlashPlayerVersion().major == 0) {
    primary = "html5";
  }
  jQuery(document).ready(function($){
  $("#'.$id_img.'").hide();
  jwplayer("'.$id_video.'").setup({
    sources:
    [
    { file: "rtmp://libwowza.neu.edu:1935/vod/_definst_/'.$av_type.':datastreamStore/cerberusData/newfedoradata/datastreamStore/'.$av_dir.'/info%3Afedora%2F'.$encoded_av_pid.'%2Fcontent%2Fcontent.0"},
    { file: "http://libwowza.neu.edu:1935/vod/_definst_/datastreamStore/cerberusData/newfedoradata/datastreamStore/'.$av_dir.'/'.$av_type.':'.$full_pid.'/playlist.m3u8", type:"'.$av_for_ext.'"},
    { file: "http://libwowza.neu.edu/datastreamStore/cerberusData/newfedoradata/datastreamStore/'.$av_dir.'/'.urlencode($full_pid).'", type:"'.strtolower($av_for_ext).'"}
    ],
    image: "'.$av_poster.'",
    provider: "'.$av_provider.'",
    fallback: "false",
    androidhls: "true",
    primary: primary,
    width: "100%",
    height: 400,
  });

  var errorMessage = function(e) {
    $("#'.$id_img.'").before("<div class=\'alert alert-warning\'>'.$errors['item']['jwplayer_fail'].'<br /><strong>Error Message:</strong> "+e.message+"</div>");
    $("#'.$id_img.'").show();
    $("#'.$id_video.'").hide();
  };
  jwplayer().onError(errorMessage);
  jwplayer().onSetupError(errorMessage);
  jwplayer().onBuffer(function() {
    theTimeout = setTimeout(function(e) {
      errorMessage(e);
    }, 5000);
  });
  });</script>';

  return $html;
}
