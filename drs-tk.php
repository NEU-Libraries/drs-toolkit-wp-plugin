<?php
/**
 * Plugin Name: DRS Toolkit Plugin
 * Plugin URI:
 * Version: 0.8
 * Author: Eli Zoller
 * Description: This plugin provides the core functionality of the DRS Project Toolkit and brings the content of a project from the DRS into Wordpress using the DRS API.
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

$VERSION = '0.5.0';
$SITE_URL = site_url();

// Set template names here so we don't have to go into the code.
$TEMPLATE = array(
    'browse_template' => dirname(__FILE__) . '/templates/browse.php',
    'item_template' => dirname(__FILE__) . '/templates/item.php',
);

$TEMPLATE_THEME = array(
    'browse_template' => 'drstk-browse.php',
    'item_template' => 'drstk-item.php',
);

 register_activation_hook( __FILE__, 'drstk_install' );
 register_deactivation_hook( __FILE__, 'drstk_deactivation' );

 $all_meta_options = array("Title","Creator","Contributor","Publisher","Type of Resource","Genre","Language","Physical Description","Abstract/Description","Table of contents","Notes","Subjects and keywords","Related item","Identifier","Access condition","Location","uri","Format","Permanent URL","Date created","Date issued","Copyright date","Biographical/Historical","Biográfica/histórica");
 $all_assoc_meta_options = array("Title","Creator","Abstract/Description");
 $facet_options = array("creator_sim", "creation_year_sim", "subject_sim", "type_sim", "community_name_ssim", "drs_department_ssim", "drs_degree_ssim", "drs_course_number_ssim", "drs_course_title_ssim");
 $niec_facet_options = array("niec_gender_ssim", "niec_age_ssim", "niec_race_ssim", "niec_sign_pace_ssim", "niec_fingerspelling_extent_ssim", "niec_fingerspelling_pace_ssim", "niec_numbers_pace_ssim", "niec_numbers_extent_ssim", "niec_classifiers_extent_ssim", "niec_use_of_space_extent_ssim", "niec_how_space_used_ssim", "niec_text_type_ssim", "niec_register_ssim", "niec_conversation_type_ssim", "niec_audience_ssim", "niec_signed_language_ssim");

 /**
  * Rewrite rules for the plugin.
  */
 add_action('init', 'drstk_rewrite_rule');
 function drstk_rewrite_rule() {

     add_rewrite_rule('^browse/?$',
         'index.php?post_type=drs&drstk_template_type=browse',
         'top');
     add_rewrite_rule('^search/?$', 'index.php?post_type=drs&drstk_template_type=search', 'top');
     add_rewrite_rule('^item/([^/]*)/?([^/]*)*', 'index.php?post_type=drs&drstk_template_type=item&pid=$matches[1]&js=$matches[2]', 'top');
     add_rewrite_rule('^collections/?$', 'index.php?post_type=drs&drstk_template_type=collections', 'top');
     add_rewrite_rule('^collection/([^/]*)/?', 'index.php?post_type=drs&drstk_template_type=collection&pid=$matches[1]', 'top');
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
   $hook = add_options_page('Settings for DRS Toolkit Plugin', 'DRS Toolkit', 'manage_options', 'drstk_admin_menu', 'drstk_display_settings');
   add_action('load-'.$hook,'drstk_plugin_settings_save');
 }

//This registers the settings
function register_drs_settings() {
  global $facet_options;

  add_settings_section('drstk_project', "Project Info", null, 'drstk_options');
  add_settings_field('drstk_collection', 'Project Collection or Set URL', 'drstk_collection_callback', 'drstk_options', 'drstk_project');
  register_setting( 'drstk_options', 'drstk_collection' );
  
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

  add_settings_section('drstk_browse_settings', 'Browse Settings', null, 'drstk_options');
  add_settings_field('drstk_browse_page_title', 'Browse Page Title', 'drstk_browse_page_title_callback', 'drstk_options', 'drstk_browse_settings');
  register_setting( 'drstk_options', 'drstk_browse_page_title' );
  add_settings_field('drstk_browse_metadata', 'Metadata to Display', 'drstk_browse_metadata_callback', 'drstk_options', 'drstk_browse_settings');
  register_setting( 'drstk_options', 'drstk_browse_metadata' );

  add_settings_section('drstk_facet_settings', 'Facets', null, 'drstk_options');
  add_settings_field('drstk_facets', 'Facets to Display<br/><small>Select which facets you would like to display on the search and browse pages. Once selected, you may enter custom names for these facets. Drag and drop the order of the facets to change the order of display.</small>', 'drstk_facets_callback', 'drstk_options', 'drstk_facet_settings');
  register_setting( 'drstk_options', 'drstk_facets' );
  foreach($facet_options as $option){
    add_settings_field('drstk_'.$option.'_title', null, 'drstk_facet_title_callback', 'drstk_options', 'drstk_facet_settings', array('class'=>'hidden'));
    register_setting( 'drstk_options', 'drstk_'.$option.'_title');
  }

  add_settings_section('drstk_collections_settings', 'Collections Page Settings', null, 'drstk_options');
  add_settings_field('drstk_collections_page_title', 'Collections Page Title', 'drstk_collections_page_title_callback', 'drstk_options', 'drstk_collections_settings');
  register_setting( 'drstk_options', 'drstk_collections_page_title' );

  add_settings_section('drstk_collection_settings', 'Collection Page Settings', null, 'drstk_options');
  add_settings_field('drstk_collection_page_title', 'Collection Page Title', 'drstk_collection_page_title_callback', 'drstk_options', 'drstk_collection_settings');
  register_setting( 'drstk_options', 'drstk_collection_page_title' );

  add_settings_section('drstk_single_settings', 'Single Item Page Settings', null, 'drstk_options');
  add_settings_field('drstk_item_page_metadata', 'Metadata to Display<br/><small>If none are selected, all metadata will display.</small>', 'drstk_item_page_metadata_callback', 'drstk_options', 'drstk_single_settings');
  register_setting( 'drstk_options', 'drstk_item_page_metadata' );
  add_settings_field('drstk_assoc', 'Display Associated Files', 'drstk_assoc_callback', 'drstk_options', 'drstk_single_settings');
  register_setting( 'drstk_options', 'drstk_assoc' );
  add_settings_field('drstk_assoc_title', 'Associated Files Block Title', 'drstk_assoc_title_callback', 'drstk_options', 'drstk_single_settings', array('class'=>'assoc'));
  register_setting( 'drstk_options', 'drstk_assoc_title' );
  add_settings_field('drstk_assoc_file_metadata', 'Metadata to Display', 'drstk_assoc_file_metadata_callback', 'drstk_options', 'drstk_single_settings', array('class'=>'assoc'));
  register_setting( 'drstk_options', 'drstk_assoc_file_metadata' );
}
add_action( 'admin_init', 'register_drs_settings' );

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
    $meta_options = array("Title","Creator","Abstract/Description");
  }
  return $meta_options;
}

function drstk_get_facets_to_display(){
  $facet_options = get_option('drstk_facets');
  if ($facet_options == NULL){
    $facet_options = array("creator_sim","creation_year_sim","subject_sim","type_sim");
  }
  return $facet_options;
}

function drstk_get_facet_name($facet){
  $name = get_option('drstk_'.$facet.'_title');
  if ($name == NULL){
    $name = titleize($facet);
  }
  return $name;
}

function drstk_get_errors(){
  global $errors;
  return $errors;
}

/*callback functions for display fields on settings page*/
function drstk_collection_callback(){
  $collection_pid = (get_option('drstk_collection') != '') ? get_option('drstk_collection') : 'https://repository.library.northeastern.edu/collections/neu:1';
  echo '<input name="drstk_collection" type="text" value="'.$collection_pid.'" style="width:100%;"></input><br/>
     <small>Ie. <a href="https://repository.library.northeastern.edu/collections/neu:6012">https://repository.library.northeastern.edu/collections/neu:6012</a></small>';
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

function drstk_facets_callback(){
  global $facet_options;
  $facets_to_display = drstk_get_facets_to_display();
  echo "<table><tbody id='facets_sortable'>";
  foreach($facets_to_display as $option){
    echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_facets[]" value="'.$option.'" checked="checked"/> <label>'.titleize($option).'</label></td>';
    echo '<td style="padding:0;" class="title"><input type="text" name="drstk_'.$option.'_title" value="'.get_option('drstk_'.$option.'_title').'"></td></tr>';
  }
  foreach($facet_options as $option){
    if (!in_array($option, $facets_to_display)){
      echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_facets[]" value="'.$option.'"/> <label>'.titleize($option).'</label></td>';
      echo '<td style="padding:0;display:none" class="title"><input type="text" name="drstk_'.$option.'_title" value="'.get_option('drstk_'.$option.'_title').'"></td></tr>';
    }
  }
  echo "</tbody></table>";
}

function drstk_facet_title_callback(){
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
  $item_options = get_option('drstk_item_page_metadata');
  foreach($all_meta_options as $option){
    echo'<label><input type="checkbox" name="drstk_item_page_metadata[]" value="'.$option.'" ';
    if (is_array($item_options) && in_array($option, $item_options)){echo'checked="checked"';}
    echo'/> '.$option.'</label><br/>';
  }
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
    echo'/> '.$option.'</label><br/>';
  }
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
            add_action('wp_enqueue_scripts', 'get_leaflet_api_keys_script');
            if ($template_type == 'collection') {
              add_action('wp_enqueue_scripts', 'drstk_breadcrumb_script');
                add_action('wp_enqueue_scripts', 'get_leaflet_api_keys_script');
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
    global $SITE_URL;
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
    //this creates a unique nonce to pass back and forth from js/php to protect
    $browse_nonce = wp_create_nonce( 'browse_drs' );
    $facets = drstk_get_facets_to_display();
    $facets_to_display = array();
    foreach($facets as $facet){
      $facets_to_display[$facet] = drstk_get_facet_name($facet);
    }
    //this allows an ajax call from browse.js
    $browse_obj = array(
      'ajax_url' => admin_url( 'admin-ajax.php' ),
      'nonce'    => $browse_nonce,
      'template' => $wp_query->query_vars['drstk_template_type'],
      'site_url' => $SITE_URL,
      'sub_collection_pid' => $sub_collection_pid,
      'search_options' => json_encode($search_options),
      'browse_options' => json_encode($browse_options),
      'errors' => json_encode($errors),
      'facets_to_display' => $facets_to_display,
    );

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
    //this enqueues the JS file
    wp_register_script('drstk_elevatezoom',
        plugins_url('/assets/js/elevatezoom/jquery.elevateZoom-3.0.8.min.js', __FILE__),
        array());
    wp_enqueue_script('drstk_elevatezoom');
    wp_register_script('drstk_jwplayer',
        plugins_url('/assets/js/jwplayer/jwplayer.js', __FILE__),
        array(), $VERSION, false );
    wp_enqueue_script('drstk_jwplayer');
    wp_register_script('drstk_item_gallery',
        plugins_url('/assets/js/item_gallery.js', __FILE__),
        array(), $VERSION, false );
    wp_enqueue_script('drstk_item_gallery');
}

function drstk_breadcrumb_script(){
  global $wp_query;
  global $VERSION;
  global $SITE_URL;
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
  $string = str_replace("drs_","",$string);
  $string = str_replace("niec_","",$string);
  $string = str_replace("_"," ",$string);
  $string = ucfirst($string);
  return $string;
}