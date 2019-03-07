<?php

add_settings_section('drstk_browse_settings', 'Browse', null, 'drstk_options');
add_settings_field('drstk_browse_page_title', 'Browse Page Title', 'drstk_browse_page_title_callback', 'drstk_options', 'drstk_browse_settings');
register_setting( 'drstk_options', 'drstk_browse_page_title' );
add_settings_field('drstk_browse_metadata', 'Metadata to Display', 'drstk_browse_metadata_callback', 'drstk_options', 'drstk_browse_settings');
register_setting( 'drstk_options', 'drstk_browse_metadata' );
add_settings_field('drstk_default_sort', 'Default Sort', 'drstk_default_sort_callback', 'drstk_options', 'drstk_browse_settings');
register_setting( 'drstk_options', 'drstk_default_sort' );
add_settings_field('drstk_default_browse_per_page', 'Default Per Page', 'drstk_default_browse_per_page_callback', 'drstk_options', 'drstk_browse_settings');
register_setting( 'drstk_options', 'drstk_default_browse_per_page' );
add_settings_field('drstk_browse_show_facets', 'Show Facets', 'drstk_browse_show_facets_callback', 'drstk_options', 'drstk_browse_settings');
register_setting( 'drstk_options', 'drstk_browse_show_facets' );

add_settings_section('drstk_facet_settings', 'Facets', null, 'drstk_options');
add_settings_field('drstk_facets', 'Facets to Display<br/><small>Select which facets you would like to display on the search and browse pages. Once selected, you may enter custom names for these facets. Drag and drop the order of the facets to change the order of display.</small>', 'drstk_facets_callback', 'drstk_options', 'drstk_facet_settings');
register_setting( 'drstk_options', 'drstk_facets' );

$facet_options = drstk_facets_get_option('drstk', true);
foreach($facet_options as $option){
  add_settings_field('drstk_'.$option.'_title', null, 'drstk_facet_title_callback', 'drstk_options', 'drstk_facet_settings', array('class'=>'hidden'));
  register_setting( 'drstk_options', 'drstk_'.$option.'_title');
}
add_settings_field('drstk_facet_sort_order', 'Default Facet Sort', 'drstk_facet_sort_callback', 'drstk_options', 'drstk_facet_settings');
register_setting('drstk_options', 'drstk_facet_sort_order');

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


