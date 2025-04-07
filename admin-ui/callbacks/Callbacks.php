<?php

namespace Drstk\AdminUI;


/*
    @todo This needs to get sorted into different files/class
*/


// @todo unfuck this
//this creates the form for the drstk settings page
function drstk_display_settings() {
    $html = "div class='wrap'>" . 
          "<h1>CERES Settings</h1>" . 
          "<form method='post' action='options.php name='options'>";
    settings_fields("drstk_options");
    do_settings_sections("drstk_options");
    submit_button();
    $html = "</form></div>";
}

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
        <li>Class podcasts: https://repository.library.northeastern.edu/collections/neu:f1881z41n</li>
        <li>LGBTAQ+ : https://repository.library.northeastern.edu/sets/neu:f1882114b</li>
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
  
  function drstk_is_podcast_callback() {
    $is_podcast = get_option('drstk_is_podcast');
    if (get_option('drstk_is_podcast')) {
      $checked_attribute = "checked='checked'";
    } else {
      $checked_attribute = '';
    }
    echo "<input name='drstk_is_podcast' type='checkbox' $checked_attribute></input>";
  }
  
  function drstk_podcast_poster_callback() {
    $is_podcast = get_option('drstk_podcast_poster');
    if (get_option('drstk_podcast_poster')) {
      $checked_attribute = "checked='checked'";
    } else {
      $checked_attribute = '';
    }
    echo "<input name='drstk_podcast_poster' type='checkbox' $checked_attribute></input>";
  }
  
  
  function drstk_podcast_page_callback() {
    $selected = get_option('drstk_podcast_page');
    wp_dropdown_pages( array(
                              'selected' => $selected,
                              'name' => 'drstk_podcast_page',
                              'id' => 'drstk_podcast_page',
                              'class' => 'drstk_podcast_options'
                            )
    );
    echo "<p><a href='" . get_page_link($selected, false) . "'>" . get_the_title($selected) .   "</a></p>";
  }
  
  function leaflet_api_key_callback(){
    $leaflet_api_key = (get_option('leaflet_api_key') != '') ? get_option('leaflet_api_key') : '';
    echo '<input name="leaflet_api_key" type="text" value="'.$leaflet_api_key.'" style="width:100%;"></input><br/>
       <small>Ie. pk.eyJ1IjoiZGhhcmFtbWFuaWFyIiwiYSI6ImNpbTN0cjJmMTAwYmtpY2tyNjlvZDUzdXMifQ.8sUclClJc2zSBNW0ckJLOg</small>';
  }
  
  function leaflet_project_key_callback(){
      $leaflet_project_key = (get_option('leaflet_project_key') != '') ? get_option('leaflet_project_key') : '';
      echo '<input name="leaflet_project_key" type="text" value="'.$leaflet_project_key.'" style="width:100%;"></input><br/>
       <small>Ie. dharammaniar.pfnog3b9</small>';
  }
  
  function drstk_search_page_title_callback(){
    echo '<input type="text" name="drstk_search_page_title" value="';
    if (get_option('drstk_search_page_title') == ''){ echo 'Search';} else { echo get_option('drstk_search_page_title'); }
    echo '" />';
  }
  
  function drstk_search_placeholder_callback(){
    echo '<input type="text" name="drstk_search_placeholder" value="';
    if (get_option('drstk_search_placeholder') == ''){ echo 'Search ...';} else { echo get_option('drstk_search_placeholder'); }
    echo '" />';
  }
  
  function drstk_search_metadata_callback(){
    $search_meta_options = array('Title','Creator','Abstract/Description','Date Created');
    $options = get_option('drstk_search_metadata');
    foreach($search_meta_options as $option){
      echo '<label><input type="checkbox" name="drstk_search_metadata[]" value="'.$option.'"';
      if (is_array($options) && in_array($option, $options)){ echo 'checked="checked"';}
      echo '/> '.$option.'</label><br/>';
    }
  }
  
  function drstk_search_related_content_title_callback(){
    echo '<input type="text" name="drstk_search_related_content_title" value="';
    if (get_option('drstk_search_related_content_title') == ''){ echo 'Related Content';} else { echo get_option('drstk_search_related_content_title'); }
    echo '" />';
  }
  
  function drstk_browse_page_title_callback(){
    echo '<input type="text" name="drstk_browse_page_title" value="';
    if (get_option('drstk_browse_page_title') == ''){ echo 'Browse';} else { echo get_option('drstk_browse_page_title'); }
    echo '" />';
  }
  
  function drstk_browse_metadata_callback(){
    $browse_meta_options = array('Title','Creator','Abstract/Description','Date Created');
    $options = get_option('drstk_browse_metadata');
    foreach($browse_meta_options as $option){
      echo '<label><input type="checkbox" name="drstk_browse_metadata[]" value="'.$option.'"';
      if (is_array($options) && in_array($option, $options)){ echo 'checked="checked"';}
      echo '/> '.$option.'</label><br/>';
    }
  }
  
  function drstk_default_sort_callback(){
    $sort_options = array("title_ssi%20asc"=>"Title A-Z","title_ssi%20desc"=>"Title Z-A", "score+desc%2C+system_create_dtsi+desc"=>"Relevance", "creator_ssi%20asc"=>"Creator A-Z","creator_ssi%20desc"=>"Creator Z-A","system_modified_dtsi%20asc"=>"Date (earliest to latest)","system_modified_dtsi%20desc"=>"Date (latest to earliest)");
    $default_sort = get_option('drstk_default_sort');
    echo '<select name="drstk_default_sort">';
    foreach($sort_options as $val=>$option){
      echo '<option value="'.$val.'"';
      if ($default_sort == $val){ echo 'selected="true"';}
      echo '/> '.$option.'</option>';
    }
    echo '</select><br/>';
  }
  
  function drstk_default_browse_per_page_callback(){
    $per_page_options = array("10"=>"10","20"=>"20", "50"=>"50");
    $default_per_page = get_option('drstk_default_browse_per_page');
    echo '<select name="drstk_default_browse_per_page">';
    foreach($per_page_options as $val=>$option){
      echo '<option value="'.$val.'"';
      if ($default_per_page == $val){ echo 'selected="true"';}
      echo '/> '.$option.'</option>';
    }
    echo '</select><br/>';
  }
  
  function drstk_browse_show_facets_callback(){
    echo '<input type="checkbox" name="drstk_browse_show_facets" ';
    if (get_option('drstk_browse_show_facets') == 'on'){ echo 'checked="checked"';}
    echo '/>Yes</label>';
  }
  
  function drstk_default_search_per_page_callback(){
    $per_page_options = array("10"=>"10","20"=>"20", "50"=>"50");
    $default_per_page = get_option('drstk_default_search_per_page');
    echo '<select name="drstk_default_search_per_page">';
    foreach($per_page_options as $val=>$option){
      echo '<option value="'.$val.'"';
      if ($default_per_page == $val){ echo 'selected="true"';}
      echo '/> '.$option.'</option>';
    }
    echo '</select><br/>';
  }
  
  function drstk_search_show_facets_callback(){
    echo '<input type="checkbox" name="drstk_search_show_facets" ';
    if (get_option('drstk_search_show_facets') == 'on'){ echo 'checked="checked"';}
    echo '/>Yes</label>';
  }
  
  function drstk_facets_callback(){
    $facet_options = drstk_facets_get_option('drstk', true);
    $facets_to_display = drstk_get_facets_to_display();
    echo "<table class='drstk_facets'><tbody id='facets_sortable'>";
    foreach($facets_to_display as $option){
      echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_facets[]" value="'.$option.'" checked="checked"/> <label> <span class="dashicons dashicons-sort"></span> '.titleize($option).'</label></td>';
      echo '<td style="padding:0;" class="title"><input type="text" name="drstk_'.$option.'_title" value="'.get_option('drstk_'.$option.'_title').'"></td></tr>';
    }
    foreach($facet_options as $option){
      if (!in_array($option, $facets_to_display)){
        echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_facets[]" value="'.$option.'"/> <label> <span class="dashicons dashicons-sort"></span> '.titleize($option).'</label></td>';
        echo '<td style="padding:0;display:none" class="title"><input type="text" name="drstk_'.$option.'_title" value="'.get_option('drstk_'.$option.'_title').'"></td></tr>';
      }
    }
    echo "</tbody></table>";
  }
  
  function drstk_facet_title_callback(){
    echo '';
  }
  
  function drstk_facet_sort_callback(){
    $sort_options = array("fc_desc"=>"Facet Count (Highest to Lowest)","fc_asc"=>"Facet Count (Lowest to Highest)","abc_asc"=>"Facet Title (A-Z)","abc_desc"=>"Facet Title (Z-A)");
    $default_sort = get_option('drstk_facet_sort_order');
    echo '<select name="drstk_facet_sort_order">';
    foreach($sort_options as $val=>$option){
      echo '<option value="'.$val.'"';
      if ($default_sort == $val){ echo 'selected="true"';}
      echo '/> '.$option.'</option>';
    }
    echo '</select><br/>';
  }
  
  function drstk_niec_callback(){
    echo '<input type="checkbox" name="drstk_niec" ';
    if (get_option('drstk_niec') == 'on'){ echo 'checked="checked"';}
    echo '/>Yes</label>';
  }
  
  
  function drstk_niec_metadata_callback(){
    $niec_facet_options = drstk_facets_get_option('niec', true);;
    $niec_facets_to_display = get_option('drstk_niec_metadata');
    echo "<table class='drstk_facets drstk_niec_facets'><tbody id='niec_facets_sortable'>";
    if (is_array($niec_facets_to_display)){
      foreach($niec_facets_to_display as $option){
        echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_niec_metadata[]" value="'.$option.'" checked="checked"/> <label> <span class="dashicons dashicons-sort"></span> '.titleize($option).'</label></td>';
        echo '<td style="padding:0;" class="title"><input type="text" name="drstk_niec_'.$option.'_title" value="'.get_option('drstk_niec_'.$option.'_title').'"></td></tr>';
      }
    }
    foreach($niec_facet_options as $option){
      if (!is_array($niec_facets_to_display) || (is_array($niec_facets_to_display) && !in_array($option, $niec_facets_to_display))){
        echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_niec_metadata[]" value="'.$option.'"/> <label> <span class="dashicons dashicons-sort"></span> '.titleize($option).'</label></td>';
        echo '<td style="padding:0;display:none" class="title"><input type="text" name="drstk_niec_'.$option.'_title" value="'.get_option('drstk_niec_'.$option.'_title').'"></td></tr>';
      }
    }
    echo "</tbody></table>";
  }
  
  function drstk_niec_metadata_title_callback(){
    echo '';
  }
  
  function drstk_collections_page_title_callback(){
    echo '<input type="text" name="drstk_collections_page_title" value="';
    if (get_option('drstk_collections_page_title') == ''){ echo 'Browse Collections';} else { echo get_option('drstk_collections_page_title'); }
    echo '" />';
  }
  
  function drstk_collection_page_title_callback(){
    echo '<input type="text" name="drstk_collection_page_title" value="';
    if (get_option('drstk_collection_page_title') == ''){ echo 'Collection';} else { echo get_option('drstk_collection_page_title'); }
    echo '" />';
  }
  
  function drstk_mirador_callback(){
    echo '<input type="checkbox" name="drstk_mirador" ';
    if (get_option('drstk_mirador') == 'on'){ echo 'checked="checked"';}
    echo '/>Display</label>';
  }
  
  function drstk_mirador_page_title_callback(){
    echo '<input type="text" name="drstk_mirador_page_title" value="';
    if (get_option('drstk_mirador_page_title') == ''){ echo 'Book View';} else { echo get_option('drstk_mirador_page_title'); }
    echo '" />';
  }
  
  function drstk_mirador_url_callback() {
    $mirador_url = get_option('drstk_mirador_url') == '' ? 'mirador' : get_option('drstk_mirador_url');
    echo '<input name="drstk_mirador_url" type="text" value="'.$mirador_url.'"></input><br/>
       <small>This sets the URL path for the mirador viewer<br/>
       Currently, yours will look like: <strong>'.drstk_home_url().'mirador/</strong></small>';
  }
  
  function drstk_item_page_metadata_callback(){
    global $all_meta_options;
    $item_options = get_option('drstk_item_page_metadata') != "" ? get_option('drstk_item_page_metadata') : array();
    echo '<table class="drstk_item_metadata"><tbody id="item_metadata_sortable">';
    foreach($item_options as $option){
      echo'<tr><td style="padding:0"><label><input type="checkbox" name="drstk_item_page_metadata[]" value="'.$option.'" ';
      if (is_array($item_options) && in_array($option, $item_options)){echo'checked="checked"';}
      echo'/> <span class="dashicons dashicons-sort"></span> '.$option.' </label></td></tr>';
    }
    foreach($all_meta_options as $option){
      if (!in_array($option, $item_options)){
        echo'<tr><td style="padding:0"><label><input type="checkbox" name="drstk_item_page_metadata[]" value="'.$option.'" ';
        if (is_array($item_options) && in_array($option, $item_options)){echo'checked="checked"';}
        echo'/> <span class="dashicons dashicons-sort"></span> '.$option.' </label></td></tr>';
      }
    }
    echo '</tbody></table>';
    echo '<a href="" class="add-item-meta button"><span class="dashicons dashicons-plus"></span>Add Metadata Field</a>';
  }
  
  function drstk_appears_callback(){
    echo '<input type="checkbox" name="drstk_appears" ';
    if (get_option('drstk_appears') == 'on'){ echo 'checked="checked"';}
    echo '/>Display</label>';
  }
  
  function drstk_appears_title_callback(){
    echo '<input type="text" name="drstk_appears_title" value="';
    if (get_option('drstk_appears_title') == ''){ echo 'Item Appears In';} else { echo get_option('drstk_appears_title'); }
    echo '" />';
  }
  
  function drstk_assoc_callback(){
    echo '<input type="checkbox" name="drstk_assoc" ';
    if (get_option('drstk_assoc') == 'on'){ echo 'checked="checked"';}
    echo '/>Display</label>';
  }
  
  function drstk_assoc_title_callback(){
    echo '<input type="text" name="drstk_assoc_title" value="';
    if (get_option('drstk_assoc_title') == ''){ echo 'Associated Files';} else { echo get_option('drstk_assoc_title'); }
    echo '" />';
  }
  
  function drstk_assoc_file_metadata_callback(){
    global $all_assoc_meta_options;
    $assoc_options = drstk_get_assoc_meta_options();
    foreach($all_assoc_meta_options as $option){
      echo'<label><input type="checkbox" name="drstk_assoc_file_metadata[]" value="'.$option.'" ';
      if (is_array($assoc_options) && in_array($option, $assoc_options)){echo'checked="checked"';}
      echo'/> '.titleize($option).'</label><br/>';
    }
  }
  
  function drstk_annotations_callback(){
    echo '<input type="checkbox" name="drstk_annotations" ';
    if (get_option('drstk_annotations') == 'on'){ echo 'checked="checked"';}
    echo '/>Display</label>';
  }
  
  function drstk_item_extensions_callback(){
    echo '<input type="checkbox" name="drstk_item_extensions" ';
    if (get_option('drstk_item_extensions') == 'on'){ echo 'checked="checked"';}
    echo '/>Enable</label>';
  }
  
  function drstk_podcast_author_callback() {
    $author = get_option('drstk_podcast_author');
    echo "<input name='drstk_podcast_author' type='text'
                 class = 'drstk_podcast_options'
                 value='$author' class='drstk_podcast_author_setting'>
          </input><br/>";
  }
  
  function drstk_itunes_link_callback() {
    $link = get_option('drstk_itunes_link');
    echo "<input name='drstk_itunes_link' type='text'
                 class = 'drstk_podcast_options'
                 value='$link' class='drstk_podcast_link_setting'>
          </input><br/>";
    echo DRSTK_PODCAST_REGISTER_HTML;
  }
  
  function drstk_spotify_link_callback() {
    $link = get_option('drstk_spotify_link');
    echo "<input name='drstk_spotify_link' type='text'
                 class = 'drstk_podcast_options'
                 value='$link' class='drstk_podcast_link_setting'>
          </input><br/>";
    echo DRSTK_PODCAST_REGISTER_HTML;
  }
  
  function drstk_googleplay_link_callback() {
    $link = get_option('drstk_googleplay_link');
    echo "<input name='drstk_googleplay_link' type='text'
                 class = 'drstk_podcast_options'
                 value='$link' class='drstk_podcast_link_setting'>
          </input><br/>";
    echo DRSTK_PODCAST_REGISTER_HTML;
  }
  
  function drstk_overcast_link_callback() {
    $link = get_option('drstk_overcast_link');
    echo "<input name='drstk_overcast_link' type='text'
                 class = 'drstk_podcast_options'
                 value='$link' class='drstk_podcast_link_setting'>
          </input><br/>";
    echo DRSTK_PODCAST_REGISTER_HTML;
  }
  
  function drstk_stitcher_link_callback() {
    $link = get_option('drstk_stitcher_link');
    echo "<input name='drstk_stitcher_link' type='text'
                 class = 'drstk_podcast_options'
                 value='$link' class='drstk_podcast_link_setting'>
          </input><br/>";
    echo DRSTK_PODCAST_REGISTER_HTML;
  }
  
  function drstk_podcast_image_url_callback() {
    $url = get_option('drstk_podcast_image_url') ? get_option('drstk_podcast_image_url') : 'https://brand.northeastern.edu/wp-content/uploads/logotype-250x85.png';
    echo "<input name='drstk_podcast_image_url' type='text'
                 class = 'drstk_podcast_options drstk_podcast_link_setting'
                 value='$url'>
          </input><br/>
          <small>URL to an image to use in your podcast feed (usually something you upload to Media).</small>
          <br/>
          <img src='$url' />
          ";
  }
  