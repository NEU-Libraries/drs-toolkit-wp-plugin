<?php
global $item_pid, $data, $collection;
$item_pid = get_query_var( 'pid' );
$collection = drstk_get_pid();
$url = "https://repository.library.northeastern.edu/api/v1/files/" . $item_pid;
$data = get_response($url);
$data = json_decode($data);

function get_item_details(){
  global $item_pid, $data;
  foreach($data->mods as $key => $value){
    echo "<div><b>".$key."</b></div><div>";
    if (count($value) > 1){
      foreach($value as $val){
        echo $val ."<br/>";
      }
    } else {
      echo $value[0];
    }
    echo "</div>";
  }
}

function get_item_title(){
  global $item_pid, $data;
  echo $data->mods->Title[0];
}

function get_item_breadcrumbs(){
  global $item_pid, $data, $breadcrumb_html, $collection;
  $breadcrumb_html = '';
  $breadcrumb_url = "https://repository.library.northeastern.edu/api/v1/search/".$collection."?"."f['id'][]=".$item_pid;
  get_this_breadcrumb($breadcrumb_url);
  echo $breadcrumb_html;
}

function get_this_breadcrumb($breadcrumb_url){
  global $breadcrumb_html;
  $breadcrumb_data = get_response($breadcrumb_url);
  $breadcrumb_data = json_decode($breadcrumb_data);
  $doc = $breadcrumb_data->response->response->docs[0];
  parse_this_breadcrumb($doc);
}

function parse_this_breadcrumb($doc){
  global $collection, $breadcrumb_html;
  $title = $doc->full_title_ssi;
  $object_type = $doc->active_fedora_model_ssi;
  if ($object_type == 'CoreFile'){
    $object_url = '/item/'.$doc->id;
  } else if ($object_type == 'Collection') {
    $object_url = '/collection/'.$doc->id;
  }
  $breadcrumb_html = " > <a href='".site_url().$object_url."'>".$title."</a>" . $breadcrumb_html;
  if ($doc->fields_parent_id_tesim){
    $parent = $doc->fields_parent_id_tesim[0];
    if ($parent != $collection){
      $breadcrumb_url = "https://repository.library.northeastern.edu/api/v1/search/".$collection."?"."f['id'][]=".$parent;
      get_this_breadcrumb($breadcrumb_url);
    } else {
      $breadcrumb_html = "<a href='".site_url()."/browse'>Browse</a>" . $breadcrumb_html;
    }
  }
}

function get_item_image(){
  global $item_pid, $data;
  echo end($data->thumbnails);
}
