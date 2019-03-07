<?php

add_settings_section('drstk_single_settings', 'Single Item Page', null, 'drstk_options');
add_settings_field('drstk_item_page_metadata', 'Metadata to Display<br/><small>If none are selected, all metadata will display in the default order. To reorder or limit the fields which display, select the desired fields and drag and drop to reorder. To add custom fields, click the add button and type in the label.</small>', 'drstk_item_page_metadata_callback', 'drstk_options', 'drstk_single_settings');
register_setting( 'drstk_options', 'drstk_item_page_metadata' );
add_settings_field('drstk_appears', 'Display Item Appears In', 'drstk_appears_callback', 'drstk_options', 'drstk_single_settings');
register_setting('drstk_options', 'drstk_appears');
add_settings_field('drstk_appears_title', 'Item Appears In Block Title', 'drstk_appears_title_callback', 'drstk_options', 'drstk_single_settings', array('class'=>'appears'));
register_setting('drstk_options', 'drstk_appears_title');
add_settings_field('drstk_assoc', 'Display Associated Files', 'drstk_assoc_callback', 'drstk_options', 'drstk_single_settings');
register_setting( 'drstk_options', 'drstk_assoc' );
add_settings_field('drstk_assoc_title', 'Associated Files Block Title', 'drstk_assoc_title_callback', 'drstk_options', 'drstk_single_settings', array('class'=>'assoc'));
register_setting( 'drstk_options', 'drstk_assoc_title' );
add_settings_field('drstk_assoc_file_metadata', 'Metadata to Display', 'drstk_assoc_file_metadata_callback', 'drstk_options', 'drstk_single_settings', array('class'=>'assoc'));
register_setting( 'drstk_options', 'drstk_assoc_file_metadata' );
add_settings_field('drstk_annotations', 'Display Annotations', 'drstk_annotations_callback', 'drstk_options', 'drstk_single_settings');
register_setting( 'drstk_options', 'drstk_annotations' );
add_settings_field('drstk_item_extensions', 'Enable Item Page Custom Text', 'drstk_item_extensions_callback', 'drstk_options', 'drstk_single_settings');
register_setting( 'drstk_options', 'drstk_item_extensions' );


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


