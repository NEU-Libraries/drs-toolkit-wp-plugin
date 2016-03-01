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
	register_setting( 'drs_options', 'drstk_collection' );
  register_setting( 'drs_options', 'drstk_item_page_metadata' );
  register_setting( 'drs_options', 'drstk_assoc_file_metadata' );
  register_setting( 'drs_options', 'drstk_search_page_title' );
  register_setting( 'drs_options', 'drstk_search_title' );
  register_setting( 'drs_options', 'drstk_search_creator' );
  register_setting( 'drs_options', 'drstk_search_abstract' );
  register_setting( 'drs_options', 'drstk_search_date' );
  register_setting( 'drs_options', 'drstk_browse_page_title' );
  register_setting( 'drs_options', 'drstk_browse_title' );
  register_setting( 'drs_options', 'drstk_browse_creator' );
  register_setting( 'drs_options', 'drstk_browse_abstract' );
  register_setting( 'drs_options', 'drstk_browse_date' );
  register_setting( 'drs_options', 'drstk_collections_page_title' );
  register_setting( 'drs_options', 'drstk_collection_page_title' );
  register_setting( 'drs_options', 'drstk_assoc' );
  register_setting( 'drs_options', 'drstk_assoc_title' );
  register_setting( 'drs_options', 'drstk_facets' );
  foreach($facet_options as $option){
    register_setting( 'drs_options', 'drstk_'.$option.'_title');
  }
}
add_action( 'admin_init', 'register_drs_settings' );

function drstk_plugin_settings_save(){
  if(isset($_GET['settings-updated']) && $_GET['settings-updated'])
   {
      //plugin settings have been saved.
      $collection_pid = drstk_get_pid();
   }
}

  function drstk_get_pid(){
    $collection_pid = get_option('drstk_collection');
    $collection_pid = explode("/", $collection_pid);
    $collection_pid = end($collection_pid);
    return $collection_pid;
  }

  function drstk_get_meta_options(){
    $meta_options = get_option('drstk_item_page_metadata');
    if ($meta_options != NULL){
      $meta_options = explode(",", $meta_options);
    } else {
      $meta_options = NULL;
    }
    return $meta_options;
  }

  function drstk_get_assoc_meta_options(){
    $meta_options = get_option('drstk_assoc_file_metadata');
    if ($meta_options != NULL){
      $meta_options = explode(",", $meta_options);
    } else {
      $meta_options = array("Title","Creator","Abstract/Description");
    }
    return $meta_options;
  }

  function drstk_get_facets_to_display(){
    $facet_options = get_option('drstk_facets');
    if ($facet_options != NULL){
      $facet_options = explode(",", $facet_options);
    } else {
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
//this creates the form for entering the pid on the settings page
 function drstk_display_settings() {
  global $facet_options, $all_meta_options, $all_assoc_meta_options, $niec_facet_options;
     $collection_pid = (get_option('drstk_collection') != '') ? get_option('drstk_collection') : 'https://repository.library.northeastern.edu/collections/neu:1';
     $item_options = drstk_get_meta_options();
     $assoc_options = drstk_get_assoc_meta_options();
     $facets_to_display = drstk_get_facets_to_display();


     $html = '
</pre>
     <div class="wrap">
     <form action="options.php" method="post" name="options">
     <h1>DRS Settings</h1>'. wp_nonce_field('update-options') . '
     <table class="form-table" width="100%" cellpadding="10">
     <tbody>
     <tr>
     <th>
      <label>Project Collection or Set URL</label></th>
     <td><input name="drstk_collection" type="text" value="'.$collection_pid.'" style="width:100%;"></input><br/>
     <small>Ie. <a href="https://repository.library.northeastern.edu/collections/neu:6012">https://repository.library.northeastern.edu/collections/neu:6012</a></small>
     </td>
     </tr>
     </tbody>
     </table>
     <h2>Search Settings</h2>
     <table class="form-table" width="100%">
     <tbody>
     <tr><th>Search Page Title</th>
     <td><input type="text" name="drstk_search_page_title" value="';
     if (get_option('drstk_search_page_title') == ''){ $html .= 'Search';} else { $html .= get_option('drstk_search_page_title'); }
     $html .= '" /></td>
     </tr>
     <tr>
     <th>What metadata should be visible for each record by default?</th>
     <td><label><input type="checkbox" name="drstk_search_title" ';
     if (get_option('drstk_search_title') == 'on'){ $html .= 'checked="checked"';}
     $html .= '/>Title</label><br/>
     <label><input type="checkbox" name="drstk_search_creator" ';
     if (get_option('drstk_search_creator') == 'on'){ $html .= 'checked="checked"';}
     $html .= '/>Creator</label><br/>
     <label><input type="checkbox" name="drstk_search_abstract" ';
     if (get_option('drstk_search_abstract') == 'on'){ $html .= 'checked="checked"';}
     $html .= '/>Abstract/Description</label><br/>
     <label><input type="checkbox" name="drstk_search_date" ';
     if (get_option('drstk_search_date') == 'on'){ $html .= 'checked="checked"';}
     $html .= '/>Date Created</label></td>
     </tr>
     </tbody>
     </table>
     <h2>Browse Settings</h2>
     <table class="form-table" width="100%">
     <tbody>
     <tr><th>Browse Page Title</th>
     <td><input type="text" name="drstk_browse_page_title" value="';
     if (get_option('drstk_browse_page_title') == ''){ $html .= 'Browse';} else {$html .= get_option('drstk_browse_page_title');}
     $html .='" /></td>
     </tr>
     <tr>
     <th>What metadata should be visible for each record by default?</th>
     <td><label><input type="checkbox" name="drstk_browse_title" ';
     if (get_option('drstk_browse_title') == 'on'){ $html .= 'checked="checked"';}
     $html .= '/>Title</label><br/>
     <label><input type="checkbox" name="drstk_browse_creator" ';
     if (get_option('drstk_browse_creator') == 'on'){ $html .= 'checked="checked"';}
     $html .= '/>Creator</label><br/>
     <label><input type="checkbox" name="drstk_browse_abstract" ';
     if (get_option('drstk_browse_abstract') == 'on'){ $html .= 'checked="checked"';}
     $html .= '/>Abstract/Description</label><br/>
     <label><input type="checkbox" name="drstk_browse_date" ';
     if (get_option('drstk_browse_date') == 'on'){ $html .= 'checked="checked"';}
     $html .= '/>Date Created</label></td>
     </tr>
     </tbody>
     </table>
     <h2>Facets</h2>
     <table class="form-table">
     <tbody>
     <tr><th>Facets to Display<br/><small>Select which facets you would like to display on the search and browse pages. Once selected, you may enter custom names for these facets. Drag and drop the order of the facets to change the order of display.</small></th><td><table><tbody id="facets_sortable">';
     foreach($facets_to_display as $option){
       $html .= '<tr><td style="padding:0;"><input type="checkbox" name="drstk_facet" value="'.$option.'" checked="checked"/> <label for="drstk_facet">'.titleize($option).'</label></td><td class="title" style="padding:0;"><input type="text" name="drstk_'.$option.'_title" value="'.get_option('drstk_'.$option.'_title').'"></td></tr>';
     }
     foreach($facet_options as $option){
       if (!in_array($option, $facets_to_display)){
         $html .= '<tr><td style="padding:0;"><input type="checkbox" name="drstk_facet" value="'.$option.'"/> <label for="drstk_facet">'.titleize($option).'</label></td><td class="title" style="padding:0;display:none"><input type="text" name="drstk_'.$option.'_title" value="'.get_option('drstk_'.$option.'_title').'"></td></tr>';
       }
     }
     $html .='<input type="hidden" name="drstk_facets" value="'.get_option('drstk_facets').'"/>
     </tbody></table></td></tr>
     </tbody>
     </table>
     <h2>Collections Page Settings</h2>
     <table class="form-table">
     <tbody>
     <tr><th>Collections Page Title</th>
     <td><input type="text" name="drstk_collections_page_title" value="';
     if (get_option('drstk_collections_page_title') == ''){ $html .= 'Collections';} else { $html .= get_option('drstk_collections_page_title'); }
     $html .='" /></td>
     </tr>
     </tbody>
     </table>
     <h2>Single Collection Page Settings</h2>
     <table class="form-table">
     <tbody>
     <tr><th>Single Collection Page Title</th>
     <td><input type="text" name="drstk_collection_page_title" value="';
     if (get_option('drstk_collection_page_title') == ''){ $html .= 'Browse';} else { $html .= get_option('drstk_collection_page_title'); }
     $html .='" /></td>
     </tr>
     </tbody>
     </table>
     <h2>Single Item Page Settings</h2>
     <table class="form-table">
     <tbody>
     <tr><th>Metadata to display<br/><small>If none are selected, all metadata will display.</small></th><td>';
     foreach($all_meta_options as $option){
       $html .='<label for="drstk_item_metadata"><input type="checkbox" name="drstk_item_metadata" value="'.$option.'" ';
       if (is_array($item_options) && in_array($option, $item_options)){$html.='checked="checked"';}
       $html.='/> '.$option.'</label><br/>';
     }
    $html .= '
     <input type="hidden" name="drstk_item_page_metadata" value="'.get_option('drstk_item_page_metadata').'"/>
     </td>
     </tr>
     <tr><th>Display Associated Files?</th>
     <td><label><input type="checkbox" name="drstk_assoc" ';
     if (get_option('drstk_assoc') == 'on'){ $html .= 'checked="checked"';}
     $html .= '/>Display</label></td></tr>
     <tr class="assoc" ';
     if (get_option('drstk_assoc') != 'on'){$html .= 'style="display:none"';}
     $html .= '><th>Associated Files Title</th><td>
     <input type="text" name="drstk_assoc_title" value="';
     if (get_option('drstk_assoc_title') == ''){ $html .= 'Associated Files';} else { $html .= get_option('drstk_assoc_title'); }
     $html .= '" /></td></tr>
     <tr class="assoc" ';
     if (get_option('drstk_assoc') != 'on'){$html .= 'style="display:none"';}
     $html .= '><th>Associated Files Metadata to Display</th><td>';
     foreach($all_assoc_meta_options as $option){
       $html .='<label for="drstk_assoc_metadata"><input type="checkbox" name="drstk_assoc_metadata" value="'.$option.'" ';
       if (is_array($assoc_options) && in_array($option, $assoc_options)){$html.='checked="checked"';}
       $html.='/> '.$option.'</label><br/>';
     }
    $html .= '<input type="hidden" name="drstk_assoc_file_metadata" value="'.get_option('drstk_assoc_file_metadata').'"/>
     </td></tr>
     </tbody>
     </table>

      <input type="hidden" name="action" value="update" />

      <input type="hidden" name="page_options" value="drstk_collection, drstk_search_title, drstk_search_creator, drstk_search_date, drstk_search_abstract, drstk_browse_title, drstk_browse_creator, drstk_browse_abstract, drstk_browse_date, drstk_search_page_title, drstk_browse_page_title, drstk_collection_page_title, drstk_collections_page_title, drstk_item_page_metadata, drstk_assoc_file_metadata, drstk_assoc_title, drstk_assoc, drstk_facets,';
      foreach($facet_options as $facet){
        $html.='drstk_'.$facet.'_title,';
      }
      $html.='" />
      <br/><br/>
      <input type="submit" name="Submit" value="Update" class="button" style="font-size: 16px;padding: 10px 20px;height: auto;"/></form></div>
     ';

     echo $html;

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
    $search_options = array();
    if (get_option('drstk_search_title') == 'on'){
      $search_options[] = 'title';
    }
    if (get_option('drstk_search_creator') == 'on'){
      $search_options[] = 'creator';
    }
    if (get_option('drstk_search_date') == 'on'){
      $search_options[] = 'date';
    }
    if (get_option('drstk_search_abstract') == 'on'){
      $search_options[] = 'abstract';
    }
    $browse_options = array();
    if (get_option('drstk_browse_title') == 'on'){
      $browse_options[] = 'title';
    }
    if (get_option('drstk_browse_creator') == 'on'){
      $browse_options[] = 'creator';
    }
    if (get_option('drstk_browse_date') == 'on'){
      $browse_options[] = 'date';
    }
    if (get_option('drstk_browse_abstract') == 'on'){
      $browse_options[] = 'abstract';
    }
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
