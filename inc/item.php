<?php
global $item_pid, $data, $collection, $meta_options, $errors;
$collection = drstk_get_pid();
$meta_options = drstk_get_meta_options();
$errors = drstk_get_errors();

function get_item_details(){
  global $item_pid, $data, $meta_options, $errors;
  if (check_for_bad_data()){
    return false;
  }
  foreach($data->mods as $key => $value){
    if (in_array($key, $meta_options)){
      echo "<div class='drs-field-label'><b>".$key."</b></div><div class='drs-field-value'>";
      if (count($value) > 0){
        for ($i =0; $i<count($value); $i++){
          if (substr($value[$i], 0, 4) == "http"){
            echo '<a href="'.$value[$i].'" target="_blank">'.$value[$i].'</a>';
          } else {
            echo $value[$i];
          }
          if ($i != count($value)-1){
            echo "<br/> ";
          }
        }
      } else if (is_array($value)) {
        echo "";
      } else {
        echo $value;
      }
      echo "</div>";
    }
  }
}

function get_download_links(){
  global $data;
  if (check_for_bad_data()){
    return false;
  }
  echo "<br/><h4>Downloads</h4>";
  foreach($data->content_objects as $num=>$content_object){
    if ($content_object[1] != "Thumbnail Image"){
      echo " <a href='".$content_object[0]."' target='_blank' class='themebutton button btn' data-label='download' data-pid='".$data->pid."'>".$content_object[1]."</a> ";
    }
  }
}

function get_item_title(){
  global $item_pid, $data, $url;
  $url = "https://repository.library.northeastern.edu/api/v1/files/" . $item_pid;
  $data = get_response($url);
  $data = json_decode($data);
  if (check_for_bad_data()){
    return false;
  }
  echo $data->mods->Title[0];
}

function get_item_breadcrumbs(){
  global $item_pid, $data, $breadcrumb_html, $collection;
  if (check_for_bad_data()){
    return false;
  }
  $breadcrumb_html = [];
  $end = false;
  $breadcrumbs = $data->breadcrumbs;
  if (array_key_exists($collection,$breadcrumbs)){
    foreach($breadcrumbs as $pid=>$title){
      if ($pid == $item_pid){
        $breadcrumb_html[]= "<a href='".site_url()."/item/".$pid."'> ".$title."</a>";
      } else if ($pid == $collection){
        $breadcrumb_html[]= "<a href='".site_url()."/browse'>Browse</a>";
        $end = true;
      } else if ($end == true) {
      } else {
        $breadcrumb_html[]= "<a href='".site_url()."/collection/".$pid."'> ".$title."</a>";
      }
    }
  } else {
    $breadcrumb_html[]= "<a href='".site_url()."/item/".$item_pid."'> ".$data->mods->Title[0]."</a>";
    $breadcrumb_html[] = "<a href='".site_url()."/browse'>Browse</a>";
  }
  echo implode(" > ", array_reverse($breadcrumb_html));
}

function get_item_image(){
  global $item_pid, $data, $errors;
  if (check_for_bad_data()){
    echo check_for_bad_data();
    return false;
  }
  if (isset($data->thumbnails)){
    $img = $data->thumbnails[count($data->thumbnails)-2];
  }
  if (isset($data->canonical_object)){
    if ($data->canonical_object[0][1] == 'Master Image'){
      $zoom_img = $data->thumbnails[count($data->thumbnails)-1];
      echo  '<img id="drs-item-img" src="'.$img.'" data-zoom-image="'.$zoom_img.'"/>';
      echo '<script type="text/javascript"> jQuery("#drs-item-img").elevateZoom();</script>';
    } else if ($data->canonical_object[0][1] == 'PDF'){
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
    } else if ($data->canonical_object[0][1] == 'Video File' || $data->canonical_object[0][1] == 'Audio File'){
      $av_pid = $data->canonical_object[0][0];
      $av_pid = explode("/", $av_pid);
      $av_pid = end($av_pid);
      $encoded_av_pid = str_replace(':','%3A', $av_pid);
      $av_dir = substr(md5("info:fedora/".$av_pid."/content/content.0"), 0, 2);
      $av_type = $data->canonical_object[0][0];
      if ($data->thumbnails){
        $av_poster = $data->thumbnails[3];
      }
      if ($data->canonical_object[0][1] == 'Video File'){
        $av_provider = 'video';
        $av_type = "MP4";
      }
      if ($data->canonical_object[0][1] == 'Audio File'){
        $av_provider = 'sound';
        $av_type = "MP3";
      }
      echo "<div id='drs-item-video'></div>";
      echo '<script type="text/javascript">
      jwplayer.key="gi5wgpwDtAXG4xdj1uuW/NyMsECyiATOBxEO7A=="
      var primary = "flash"
      if (typeof swfobject == "undefined" || swfobject.getFlashPlayerVersion().major == 0) {
        primary == "html5"
      }
      jwplayer("drs-item-video").setup({
      sources:
      [
      { file: "rtmp://libwowza.neu.edu:1935/vod/_definst_/'.$av_type.':datastreamStore/cerberusData/newfedoradata/datastreamStore/'.$av_dir.'/info%3Afedora%2F'.$encoded_av_pid.'%2Fcontent%2Fcontent.0"},
      { file: "http://libwowza.neu.edu:1935/vod/_definst_/datastreamStore/cerberusData/newfedoradata/datastreamStore/'.$av_dir.'/'.$av_type.':" + "info%3Afedora%2F'.$encoded_av_pid.'%2Fcontent%2Fcontent.0" + "/playlist.m3u8", type:"'.$av_type.'"}
      ],
      image: "'.$av_poster.'",
      provider: "'.$av_provider.'",
      fallback: "true",
      androidhls: "true",
      primary: primary,
      width: "100%",
      height: 400,
      })

      var errorMessage = function() {
        $("#drs-item-img").before("<div class=\'alert alert-warning\'>'.$errors['item']['jwplayer_fail'].'</div>");
        $("#drs-item-img").show();
        $("#drs-item-video").hide();
      };
     jwplayer().onError(errorMessage);
     jwplayer().onSetupError(errorMessage);
     jwplayer().onBuffer(function() {
       theTimeout = setTimeout(function() {
         errorMessage;
       }, 5000);
     });</script>';
    }
  }
}

function check_for_bad_data(){
  global $data, $errors;
  if ($data == null) {
    return $errors['item']['fail'];
  } else if (isset($data->error)) {
    return $errors['item']['no_results'];
  }
}
