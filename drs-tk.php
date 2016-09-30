<?php
/**
 * Plugin Name: CERES: Exhibit Toolkit Plugin
 * Plugin URI:
 * Version: 1.1.1
 * Author: Eli Zoller
 * Description: This plugin provides the core functionality of the CERES: Exhibit Toolkit and brings the content of a project from the DRS into Wordpress using the DRS API.
 */

require_once( plugin_dir_path( __FILE__ ) . 'inc/errors.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/item.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/browse.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/breadcrumb.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/shortcodes.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/video_shortcode.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/item_shortcode.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/tiles_shortcode.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/slider_shortcode.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/map_shortcode.php');
require_once( plugin_dir_path( __FILE__ ) . 'inc/timeline_shortcode.php' );



define( 'ALLOW_UNFILTERED_UPLOADS', true ); //this will allow files without extensions - aka from fedora
$DRS_PLUGIN_PATH = plugin_dir_path( __FILE__ );
$DRS_PLUGIN_URL = plugin_dir_url( __FILE__ );

$VERSION = '1.1.1';

// Set template names here so we don't have to go into the code.
$TEMPLATE = array(
    'browse_template' => dirname(__FILE__) . '/templates/browse.php',
    'item_template' => dirname(__FILE__) . '/templates/item.php',
    'download_template' => dirname(__FILE__) . '/templates/download.php',
);

$TEMPLATE_THEME = array(
    'browse_template' => 'overrides/drstk-browse.php',
    'item_template' => 'overrides/drstk-item.php',
    'download_template' => 'overrides/drstk-download.php',
);

 register_activation_hook( __FILE__, 'drstk_install' );
 register_deactivation_hook( __FILE__, 'drstk_deactivation' );

 $all_meta_options = array("Title","Alternative Title","Creator","Contributor","Publisher","Type of Resource","Genre","Language","Physical Description","Abstract/Description","Table of contents","Notes","Subjects and keywords","Related item","Identifier","Access condition","Location","uri","Format","Permanent URL","Date created","Date issued","Copyright date","Biographical/Historical","Biográfica/histórica", "Issuance","Frequency","Digital origin","Map data","Use and reproduction","Restriction on access");
 $all_assoc_meta_options = array("full_title_ssi","creator_tesim","abstract_tesim");
 $facet_options = array("creator_sim", "creation_year_sim", "subject_sim", "type_sim", "community_name_ssim", "drs_department_ssim", "drs_degree_ssim", "drs_course_number_ssim", "drs_course_title_ssim");
 $niec_facet_options = array("niec_gender_ssim", "niec_age_ssim", "niec_race_ssim", "niec_sign_pace_ssim", "niec_fingerspelling_extent_ssim", "niec_fingerspelling_pace_ssim", "niec_numbers_pace_ssim", "niec_numbers_extent_ssim", "niec_classifiers_extent_ssim", "niec_use_of_space_extent_ssim", "niec_how_space_used_ssim", "niec_text_type_ssim", "niec_register_ssim", "niec_conversation_type_ssim", "niec_audience_ssim", "niec_signed_language_ssim", "niec_spoken_language_ssim", "niec_lends_itself_to_classifiers_ssim", "niec_lends_itself_to_use_of_space_ssim");

 /**
  * Rewrite rules for the plugin.
  */
 add_action('init', 'drstk_rewrite_rule');
 function drstk_rewrite_rule() {

    $home_url = get_option('drstk_home_url');
    add_rewrite_rule('^'.$home_url.'browse/?$', 'index.php?post_type=drs&drstk_template_type=browse', 'top');
    add_rewrite_rule('^'.$home_url.'search/?$', 'index.php?post_type=drs&drstk_template_type=search', 'top');
    add_rewrite_rule('^'.$home_url.'item/([^/]*)/?([^/]*)*', 'index.php?post_type=drs&drstk_template_type=item&pid=$matches[1]&js=$matches[2]', 'top');
    add_rewrite_rule('^'.$home_url.'download/([^/]*)/?', 'index.php?post_type=drs&drstk_template_type=download&pid=$matches[1]', 'top');
    add_rewrite_rule('^'.$home_url.'collections/?$', 'index.php?post_type=drs&drstk_template_type=collections', 'top');
    add_rewrite_rule('^'.$home_url.'collection/([^/]*)/?', 'index.php?post_type=drs&drstk_template_type=collection&pid=$matches[1]', 'top');
 }

/*add something like this later to override manual paths to the original wp search */
// function fb_change_search_url_rewrite() {
// 	if ( is_search() && ! empty( $_GET['s'] ) ) {
// 		wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) );
// 		exit();
// 	}
// }
// add_action( 'template_redirect', 'fb_change_search_url_rewrite' );

 function drstk_install() {
     // Clear the permalinks after the post type has been registered
     drstk_rewrite_rule();
     flush_rewrite_rules();
 }

 function drstk_deactivation() {
     // Clear the permalinks to remove our post type's rules
     flush_rewrite_rules();
 }

//This function creates the settings page for entering the pid
 add_action('admin_menu', 'drs_admin_add_page');
 function drs_admin_add_page() {
   $hook = add_options_page('Settings for CERES: Exhibit Toolkit Plugin', 'CERES: Exhibit Toolkit', 'manage_options', 'drstk_admin_menu', 'drstk_display_settings');
   add_action('load-'.$hook,'drstk_plugin_settings_save');
 }

//This registers the settings
function register_drs_settings() {
  global $facet_options, $niec_facet_options;

  add_settings_section('drstk_project', "Project Info", null, 'drstk_options');
  add_settings_field('drstk_collection', 'Project Collection or Set URL', 'drstk_collection_callback', 'drstk_options', 'drstk_project');
  register_setting( 'drstk_options', 'drstk_collection' );
  add_settings_field('drstk_home_url', 'Permalink/URL Base', 'drstk_home_url_callback', 'drstk_options', 'drstk_project');
  register_setting( 'drstk_options', 'drstk_home_url', 'drstk_home_url_validation' );

    //Adding Map Leaflet API Field
  add_settings_field('leaflet_api_key', 'Leaflet API Key', 'leaflet_api_key_callback', 'drstk_options', 'drstk_project');
  register_setting( 'drstk_options', 'leaflet_api_key' );

   //Adding Map Leaflet Project Field
  add_settings_field('leaflet_project_key', 'Leaflet Project Key', 'leaflet_project_key_callback', 'drstk_options', 'drstk_project');
  register_setting( 'drstk_options', 'leaflet_project_key' );

  add_settings_section('drstk_search_settings', 'Search Settings', null, 'drstk_options');
  add_settings_field('drstk_search_page_title', 'Search Page Title', 'drstk_search_page_title_callback', 'drstk_options', 'drstk_search_settings');
  register_setting( 'drstk_options', 'drstk_search_page_title' );
  add_settings_field('drstk_search_metadata', 'Metadata to Display', 'drstk_search_metadata_callback', 'drstk_options', 'drstk_search_settings');
  register_setting( 'drstk_options', 'drstk_search_metadata' );
  add_settings_field('drstk_search_related_content_title', 'Related Content Title', 'drstk_search_related_content_title_callback', 'drstk_options', 'drstk_search_settings');
  register_setting( 'drstk_options', 'drstk_search_related_content_title' );

  add_settings_section('drstk_browse_settings', 'Browse Settings', null, 'drstk_options');
  add_settings_field('drstk_browse_page_title', 'Browse Page Title', 'drstk_browse_page_title_callback', 'drstk_options', 'drstk_browse_settings');
  register_setting( 'drstk_options', 'drstk_browse_page_title' );
  add_settings_field('drstk_browse_metadata', 'Metadata to Display', 'drstk_browse_metadata_callback', 'drstk_options', 'drstk_browse_settings');
  register_setting( 'drstk_options', 'drstk_browse_metadata' );
  add_settings_field('drstk_default_sort', 'Default Sort', 'drstk_default_sort_callback', 'drstk_options', 'drstk_browse_settings');
  register_setting( 'drstk_options', 'drstk_default_sort' );

  add_settings_section('drstk_facet_settings', 'Facets', null, 'drstk_options');
  add_settings_field('drstk_facets', 'Facets to Display<br/><small>Select which facets you would like to display on the search and browse pages. Once selected, you may enter custom names for these facets. Drag and drop the order of the facets to change the order of display.</small>', 'drstk_facets_callback', 'drstk_options', 'drstk_facet_settings');
  register_setting( 'drstk_options', 'drstk_facets' );
  foreach($facet_options as $option){
    add_settings_field('drstk_'.$option.'_title', null, 'drstk_facet_title_callback', 'drstk_options', 'drstk_facet_settings', array('class'=>'hidden'));
    register_setting( 'drstk_options', 'drstk_'.$option.'_title');
  }
  add_settings_field('drstk_facet_sort_order', 'Default Facet Sort', 'drstk_facet_sort_callback', 'drstk_options', 'drstk_facet_settings');
  register_setting('drstk_options', 'drstk_facet_sort_order');
  add_settings_field('drstk_niec', 'Does your project include NIEC metadata?', 'drstk_niec_callback', 'drstk_options', 'drstk_facet_settings');
  register_setting('drstk_options', 'drstk_niec');
  add_settings_field('drstk_niec_metadata', 'Facets and Metadata to Display', 'drstk_niec_metadata_callback', 'drstk_options', 'drstk_facet_settings', array('class'=>'niec'));
  register_setting( 'drstk_options', 'drstk_niec_metadata' );
  foreach($niec_facet_options as $option){
    add_settings_field('drstk_niec_'.$option.'_title', null, 'drstk_niec_metadata_title_callback', 'drstk_options', 'drstk_facet_settings', array('class'=>'hidden'));
    register_setting( 'drstk_options', 'drstk_niec_'.$option.'_title');
  }

  add_settings_section('drstk_collections_settings', 'Collections Page Settings', null, 'drstk_options');
  add_settings_field('drstk_collections_page_title', 'Collections Page Title', 'drstk_collections_page_title_callback', 'drstk_options', 'drstk_collections_settings');
  register_setting( 'drstk_options', 'drstk_collections_page_title' );

  add_settings_section('drstk_collection_settings', 'Collection Page Settings', null, 'drstk_options');
  add_settings_field('drstk_collection_page_title', 'Collection Page Title', 'drstk_collection_page_title_callback', 'drstk_options', 'drstk_collection_settings');
  register_setting( 'drstk_options', 'drstk_collection_page_title' );

  add_settings_section('drstk_single_settings', 'Single Item Page Settings', null, 'drstk_options');
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
}
add_action( 'admin_init', 'register_drs_settings' );
add_action( 'admin_init', 'add_tinymce_plugin');

function add_tinymce_plugin(){
  add_filter("mce_external_plugins", 'mce_plugin');
}

function mce_plugin($plugin_array){
  global $DRS_PLUGIN_URL;
  $plugin_array['drstkshortcodes'] = $DRS_PLUGIN_URL.'/assets/js/mce-button.js';
  return $plugin_array;
}

/*helper functions for getting default values and cleaning up stored options*/
function drstk_get_pid(){
  $collection_pid = get_option('drstk_collection');
  $collection_pid = explode("/", $collection_pid);
  $collection_pid = end($collection_pid);
  return $collection_pid;
}

function drstk_get_assoc_meta_options(){
  $meta_options = get_option('drstk_assoc_file_metadata');
  if ($meta_options == NULL){
    $meta_options = array("full_title_ssi","creator_tesim","abstract_tesim");
  }
  return $meta_options;
}

function drstk_get_facets_to_display(){
  $facet_options = get_option('drstk_facets');
  if ($facet_options == NULL){
    if (get_option('drstk_niec_metadata') == NULL) {
      $facet_options = array("creator_sim","creation_year_sim","subject_sim","type_sim");
    } else {
      $facet_options = array();
    }
  }
  return $facet_options;
}

function drstk_get_facet_name($facet, $niec=false){
  if ($niec){
    $name = get_option('drstk_niec_'.$facet.'_title');
  } else {
    $name = get_option('drstk_'.$facet.'_title');
  }
  if ($name == NULL){
    $name = titleize($facet);
  }
  return $name;
}

function drstk_get_errors(){
  global $errors;
  return $errors;
}

function drstk_get_map_api_key(){
  $api_key = get_option('leaflet_api_key');
  return $api_key;
}

function drstk_get_map_project_key(){
  $project_key = get_option('leaflet_project_key');
  return $project_key;
}

/*callback functions for display fields on settings page*/
function drstk_collection_callback(){
  $collection_pid = (get_option('drstk_collection') != '') ? get_option('drstk_collection') : 'https://repository.library.northeastern.edu/collections/neu:1';
  echo '<input name="drstk_collection" type="text" value="'.$collection_pid.'" style="width:100%;"></input><br/>
     <small>Ie. <a href="https://repository.library.northeastern.edu/collections/neu:6012">https://repository.library.northeastern.edu/collections/neu:6012</a></small>';
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
  $sort_options = array("title_ssi%20asc"=>"Title A-Z","title_ssi%20desc"=>"Title Z-A", "score+desc%2C+system_create_dtsi+desc"=>"Relevance", "creator_tesim%20asc"=>"Creator A-Z","creator_tesim%20desc"=>"Creator Z-A","system_modified_dtsi%20asc"=>"Date (earliest to latest)","system_modified_dtsi%20desc"=>"Date (latest to earliest)");
  $default_sort = get_option('drstk_default_sort');
  echo '<select name="drstk_default_sort">';
  foreach($sort_options as $val=>$option){
    echo '<option value="'.$val.'"';
    if ($default_sort == $val){ echo 'selected="true"';}
    echo '/> '.$option.'</option>';
  }
  echo '</select><br/>';
}

function drstk_facets_callback(){
  global $facet_options;
  $facets_to_display = drstk_get_facets_to_display();
  echo "<table><tbody id='facets_sortable'>";
  foreach($facets_to_display as $option){
    echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_facets[]" value="'.$option.'" checked="checked"/> <label> <span class="dashicons dashicons-move"></span> '.titleize($option).'</label></td>';
    echo '<td style="padding:0;" class="title"><input type="text" name="drstk_'.$option.'_title" value="'.get_option('drstk_'.$option.'_title').'"></td></tr>';
  }
  foreach($facet_options as $option){
    if (!in_array($option, $facets_to_display)){
      echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_facets[]" value="'.$option.'"/> <label> <span class="dashicons dashicons-move"></span> '.titleize($option).'</label></td>';
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
  global $niec_facet_options;
  $niec_facets_to_display = get_option('drstk_niec_metadata');
  echo "<table><tbody id='niec_facets_sortable'>";
  if (is_array($niec_facets_to_display)){
    foreach($niec_facets_to_display as $option){
      echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_niec_metadata[]" value="'.$option.'" checked="checked"/> <label> <span class="dashicons dashicons-move"></span> '.titleize($option).'</label></td>';
      echo '<td style="padding:0;" class="title"><input type="text" name="drstk_niec_'.$option.'_title" value="'.get_option('drstk_niec_'.$option.'_title').'"></td></tr>';
    }
  }
  foreach($niec_facet_options as $option){
    if (!is_array($niec_facets_to_display) || (is_array($niec_facets_to_display) && !in_array($option, $niec_facets_to_display))){
      echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_niec_metadata[]" value="'.$option.'"/> <label> <span class="dashicons dashicons-move"></span> '.titleize($option).'</label></td>';
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
  if (get_option('drstk_collections_page_title') == ''){ echo 'Collections';} else { echo get_option('drstk_collections_page_title'); }
  echo '" />';
}

function drstk_collection_page_title_callback(){
  echo '<input type="text" name="drstk_collection_page_title" value="';
  if (get_option('drstk_collection_page_title') == ''){ echo 'Browse';} else { echo get_option('drstk_collection_page_title'); }
  echo '" />';
}

function drstk_item_page_metadata_callback(){
  global $all_meta_options;
  $item_options = get_option('drstk_item_page_metadata') != "" ? get_option('drstk_item_page_metadata') : array();
  echo '<table><tbody id="item_metadata_sortable">';
  foreach($item_options as $option){
    echo'<tr><td style="padding:0"><label><input type="checkbox" name="drstk_item_page_metadata[]" value="'.$option.'" ';
    if (is_array($item_options) && in_array($option, $item_options)){echo'checked="checked"';}
    echo'/> <span class="dashicons dashicons-move"></span> '.$option.' </label></td></tr>';
  }
  foreach($all_meta_options as $option){
    if (!in_array($option, $item_options)){
      echo'<tr><td style="padding:0"><label><input type="checkbox" name="drstk_item_page_metadata[]" value="'.$option.'" ';
      if (is_array($item_options) && in_array($option, $item_options)){echo'checked="checked"';}
      echo'/> <span class="dashicons dashicons-move"></span> '.$option.' </label></td></tr>';
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


//this creates the form for the drstk settings page
function drstk_display_settings(){
  ?>
    <div class="wrap">
    <h1>DRS Settings</h1>
    <form method="post" action="options.php" name="options">
        <?php
            settings_fields("drstk_options");
            do_settings_sections("drstk_options");
            submit_button();
        ?>
    </form>
	</div>
   <?php
}

function drstk_plugin_settings_save(){
  if(isset($_GET['settings-updated']) && $_GET['settings-updated'])
   {
      drstk_install();
      //plugin settings have been saved.
      // $collection_pid = drstk_get_pid();
   }
}

function drstk_admin_enqueue() {
    if (get_current_screen()->base == 'settings_page_drstk_admin_menu') {
      // we are on the settings page
      wp_enqueue_script('jquery-ui-sortable');
      wp_register_script('drstk_meta_helper_js',
          plugins_url('/assets/js/item_meta_helper.js', __FILE__),
          array('jquery'));
      wp_enqueue_script( 'drstk_meta_helper_js');
    }
}

add_action('admin_enqueue_scripts', 'drstk_admin_enqueue');

 /**
  * Register an additional query variable so we can differentiate between
  * the types of custom queries that are generated
  */
 add_filter('query_vars', 'drstk_add_query_var');
 function drstk_add_query_var($public_query_vars){
     $public_query_vars[] = 'drstk_template_type';
     $public_query_vars[] = 'pid';
     $public_query_vars[] = 'js';
     return $public_query_vars;
 }

/**
 * This is the hook that will filter our template calls; it searches for the
 * drstk_template_type variable (which we set above) and then makes a
 * decision accordingly.
 */
add_filter('template_include', 'drstk_content_template', 1, 1);
function drstk_content_template( $template ) {
    global $wp_query;
    global $TEMPLATE;
    global $TEMPLATE_THEME;

    if ( isset($wp_query->query_vars['drstk_template_type']) ) {

        $template_type = $wp_query->query_vars['drstk_template_type'];

        if ($template_type == 'browse' || $template_type == 'search' || $template_type == 'collections' || $template_type == 'collection') {
            global $sub_collection_pid;
            $sub_collection_pid = get_query_var( 'pid' );
            add_action('wp_enqueue_scripts', 'drstk_browse_script');
            if ($template_type == 'collection') {
              add_action('wp_enqueue_scripts', 'drstk_breadcrumb_script');
            }

            // look for theme template first, load plugin template as fallback
            $theme_template = locate_template( array( $TEMPLATE_THEME['browse_template'] ) );
            return ($theme_template ? $theme_template : $TEMPLATE['browse_template']);
        }

        if ($template_type == 'item') {
            global $item_pid;
            $item_pid = get_query_var('pid');
            add_action('wp_enqueue_scripts', 'drstk_item_script');

            // look for theme template first, load plugin template as fallback
            $theme_template = locate_template( array( $TEMPLATE_THEME['item_template'] ) );
            return ($theme_template ? $theme_template : $TEMPLATE['item_template']);
        }

        if ($template_type == 'download') {
          global $item_pid;
          $item_pid = get_query_var('pid');

          // look for theme template first, load plugin template as fallback
          $theme_template = locate_template( array( $TEMPLATE_THEME['download_template'] ) );
          return ($theme_template ? $theme_template : $TEMPLATE['download_template']);
        }

    } else {
        return $template;
    }
} // end drstk_content_template

/**
 * Load scripts for the browse/search page
 *
 */
function drstk_browse_script() {
    global $wp_query;
    global $VERSION;
    global $sub_collection_pid;
    global $errors;
    //this enqueues the JS file
    wp_register_script( 'drstk_browse',
        plugins_url( '/assets/js/browse.js', __FILE__ ),
        array( 'jquery' )
    );
    wp_enqueue_script('drstk_browse');
    $search_options = get_option('drstk_search_metadata');
    $browse_options = get_option('drstk_browse_metadata');
    $default_sort = get_option('drstk_default_sort');
    $default_facet_sort = get_option('drstk_facet_sort_order');
    $related_content_title = get_option('drstk_search_related_content_title');
    //this creates a unique nonce to pass back and forth from js/php to protect
    $browse_nonce = wp_create_nonce( 'browse_drs' );
    $facets = drstk_get_facets_to_display();
    $facets_to_display = array();
    foreach($facets as $facet){
      $facets_to_display[$facet] = drstk_get_facet_name($facet);
    }
    $niec_facets = get_option('drstk_niec_metadata');
    $niec_facets_to_display = array();
    if (is_array($niec_facets)){
      foreach($niec_facets as $facet){
        $niec_facets_to_display[$facet] = drstk_get_facet_name($facet, true);
      }
    }
    //this allows an ajax call from browse.js
    $browse_obj = array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce'    => $browse_nonce,
      'template' => $wp_query->query_vars['drstk_template_type'],
      'home_url' => drstk_home_url(),
      'sub_collection_pid' => $sub_collection_pid,
      'search_options' => json_encode($search_options),
      'related_content_title' => $related_content_title,
      'browse_options' => json_encode($browse_options),
      'errors' => json_encode($errors),
      'facets_to_display' => $facets_to_display,
      'default_sort' => $default_sort,
      'default_facet_sort' => $default_facet_sort
    );
    if (get_option('drstk_niec') == 'on' && count($niec_facets_to_display) > 0){
      $browse_obj['niec_facets_to_display'] = $niec_facets_to_display;
    }

    wp_localize_script( 'drstk_browse', 'browse_obj', $browse_obj );
}


/**
 * Load scripts for the doc/page views
 */
function drstk_item_script() {
    global $VERSION;
    global $wp_query;
    global $item_pid;
    global $errors;

    $item_nonce = wp_create_nonce( 'item_drs' );

    //this enqueues the JS file
    wp_register_script('drstk_jwplayer', plugins_url('/assets/js/jwplayer/jwplayer.js', __FILE__), array(), $VERSION, false );
    wp_enqueue_script('drstk_jwplayer');
    wp_register_script('drstk_elevatezoom',plugins_url('/assets/js/elevatezoom/jquery.elevateZoom-3.0.8.min.js', __FILE__), array());
    wp_enqueue_script('drstk_elevatezoom');
    wp_register_script('swfobject', '');
    wp_enqueue_script('swfobject');
    wp_register_script('drstk_item_gallery', plugins_url('/assets/js/item_gallery.js', __FILE__), array(), $VERSION, false );
    wp_enqueue_script('drstk_item_gallery');

    //this allows an ajax call from browse.js
    $assoc_obj = array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce'    => $item_nonce,
      'template' => $wp_query->query_vars['drstk_template_type'],
      'home_url' => drstk_home_url(),
    );

    wp_localize_script( 'drstk_item_gallery', 'assoc_obj', $assoc_obj );
}

function drstk_breadcrumb_script(){
  global $wp_query;
  global $VERSION;
  global $sub_collection_pid;
  global $item_pid;

  wp_register_script( 'drstk_breadcrumb',
      plugins_url( '/assets/js/breadcrumb.js', __FILE__ ),
      array( 'jquery' )
  );
  wp_enqueue_script('drstk_breadcrumb');
  $breadcrumb_nonce = wp_create_nonce( 'breadcrumb_drs' );

  wp_localize_script( 'drstk_breadcrumb', 'breadcrumb_obj', array(
     'ajax_url' => admin_url( 'admin-ajax.php' ),
     'nonce'    => $breadcrumb_nonce,
     'template' => $wp_query->query_vars['drstk_template_type'],
     'item_pid' => $item_pid,
     'sub_collection_pid' => $sub_collection_pid,
     'collection_pid' => drstk_get_pid(),
     'home_url' => drstk_home_url(),
  ) );
}

/*fix for weird jumpiness in wp admin menu*/
function fix_admin_head() {
	echo "<script type='text/javascript'>jQuery(window).load(function(){jQuery('#adminmenuwrap').hide().show(0);});</script>";
  echo "<style>#postimagediv, #start-pt-pb-tour{display:none !important;}";
}
add_action( 'admin_head', 'fix_admin_head' );


/**
* Basic curl response mechanism.
*/
function get_response( $url ) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

  // if it returns a 403 it will return no $output
  curl_setopt($ch, CURLOPT_FAILONERROR, 1);
  $output = curl_exec($ch);
  curl_close($ch);
  return $output;
}

function titleize($string){
  $string = str_replace("_tesim","",$string);
  $string = str_replace("_sim","",$string);
  $string = str_replace("_ssim","",$string);
  $string = str_replace("_ssi","",$string);
  $string = str_replace("full_","",$string);
  $string = str_replace("drs_","",$string);
  $string = str_replace("niec_","",$string);
  $string = str_replace("_"," ",$string);
  $string = ucfirst($string);
  return $string;
}

/**
 * Wraps home_url() to include the drstk_home_url after the home_url.
 *
 * If no $path is provided, will return the url with a trailing '/'
 * which is different from how the normal home_url() would function.
 */
function drstk_home_url($path = '', $scheme = null) {
  $drstk_url = get_option('drstk_home_url') ? get_option('drstk_home_url') : '/';
  $url = home_url($drstk_url, $scheme);
  if ($path) {
    $url .= ltrim( $path, '/' );
  } else {
    $url = rtrim($url, '/') . '/';
  }

  return $url;
}

/* This makes it so that the tinymce wysiwyg does not process shortcodes so the database saves the shortcode before it is processed - this allows the has_shortcode function to work as expected and thus enqueue javascript correctly*/
add_action( 'init', 'remove_bstw_widget_text_filters' );
function remove_bstw_widget_text_filters() {
    if ( function_exists( 'bstw' ) ) {
        remove_filter( 'widget_text', array( bstw()->text_filters(), 'do_shortcode' ), 10 );
    }
}

function drstk_image_attachment_fields_to_edit($form_fields, $post) {
    $form_fields["timeline_date"] = array(
        "label" => __("Timeline Date"),
        "input" => "text", // this is default if "input" is omitted
        "value" => get_post_meta($post->ID, "_timeline_date", true),
        "helps" => "Must be YYYY/MM/DD format"
    );
    $form_fields["map_coords"] = array(
        "label" => __("Map Coordinates"),
        "input" => "text", // this is default if "input" is omitted
        "value" => get_post_meta($post->ID, "_map_coords", true),
        "helps" => "Must be in Lat, Long or City name, State Initials format"
    );
    return $form_fields;
}
add_filter("attachment_fields_to_edit", "drstk_image_attachment_fields_to_edit", null, 2);

function drstk_image_attachment_fields_to_save($post, $attachment) {
    if( isset($attachment['timeline_date']) ){
      update_post_meta($post['ID'], '_timeline_date', $attachment['timeline_date']);
    }
    if( isset($attachment['map_coords']) ){
      update_post_meta($post['ID'], '_map_coords', $attachment['map_coords']);
    }
    return $post;
}
add_filter("attachment_fields_to_save", "drstk_image_attachment_fields_to_save", 10, 2);

/*helper method for getting repo type from pid valiues*/
function drstk_get_repo_from_pid($pid){
  $arr = explode(":", $pid);
  if ($arr[0] == "neu"){
    $repo = "drs";
  } else if ($arr[0] == "wp"){
    $repo = "wp";
  } else if ($arr[0] == "dpla"){
    $repo = "dpla";
  } else {
    $repo = NULL;
  }
  return $repo;
}


add_action('wp', 'drstk_add_hypothesis');
function drstk_add_hypothesis($param) {
  global $wp_query;
  $annotations = get_option('drstk_annotations');
  if ( isset($wp_query->query_vars['drstk_template_type']) ) {
    $template_type = $wp_query->query_vars['drstk_template_type'];
  } else {
    $template_type = "";
  }
	if (is_single() && !is_front_page() && !is_home() && $annotations == "on"):
		wp_enqueue_script( 'hypothesis', '//hypothes.is/embed.js', '', false, true );
	elseif (is_page() && !is_front_page() && !is_home() && $annotations == "on"):
		wp_enqueue_script( 'hypothesis', '//hypothes.is/embed.js', '', false, true );
  elseif ($template_type == 'item' && $annotations == "on"):
    wp_enqueue_script( 'hypothesis', '//hypothes.is/embed.js', '', false, true );
	endif;
}
