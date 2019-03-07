<?php

add_settings_section('drstk_project', "Project", null, 'drstk_options');
add_settings_field('drstk_collection', 'Project Collection or Set URL', 'drstk_collection_callback', 'drstk_options', 'drstk_project');
register_setting( 'drstk_options', 'drstk_collection' );
add_settings_field('drstk_home_url', 'Permalink/URL Base', 'drstk_home_url_callback', 'drstk_options', 'drstk_project');
register_setting( 'drstk_options', 'drstk_home_url', 'drstk_home_url_validation' );

/*callback functions for display fields on settings page*/
function drstk_collection_callback() {
  $collection_pid = (get_option('drstk_collection') != '') ? get_option('drstk_collection') : 'https://repository.library.northeastern.edu/collections/neu:1';
  echo '<input name="drstk_collection" type="text" value="'.$collection_pid.'" style="width:100%;"></input><br/>
     <small>Ie. <a href="https://repository.library.northeastern.edu/collections/neu:6012">https://repository.library.northeastern.edu/collections/neu:6012</a></small>';
  
  if (WP_DEBUG) {
    $commonPidsHtml = "
    <p>Reference PIDs for dev and testing:</p>
    <ul>
      <li>ETD: https://repository.library.northeastern.edu/sets/neu:cj82r3884</li>
      <li>What's New: https://repository.library.northeastern.edu/collections/neu:cj82q862v</li>
    </ul>
    ";
    echo $commonPidsHtml;
  }
  
}

function drstk_home_url_callback() {
  $url_base = get_option('drstk_home_url');
  echo '<input name="drstk_home_url" type="text" value="'.$url_base.'"></input><br/>
     <small>This sets the URL permalink base for /browse/, /search/, /item/, and /collection/<br/>
     Currently, yours will look like: <strong>'.drstk_home_url().'browse/</strong></small>';
}

/**
 * Basic validation and standardization of the $url_base entry;
 * should return a safe string that ends with a forward slash
 * (and does not begin with one).
 */
function drstk_home_url_validation($input){
  $url_base = '';
  $parts = explode("/", $input);
  foreach ($parts as $part) {
    if ($part != '') {
      $safe_part = sanitize_title($part);
      if ($safe_part) {
        $url_base .= sanitize_title($part);
        $url_base .= '/';
      }
    }
  }
  return $url_base;
}