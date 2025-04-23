<?php

/**
 * Plugin Name: CERES: Exhibit Toolkit Plugin
 * Plugin URI:
 * Version: 1.6.9
 * Author: Digital Scholarship Group, Northeastern University. Eli Zoller, Patrick Murray-John, Jeanine Rodriguez et al.
 * Description: This plugin provides the core functionality of the CERES: Exhibit Toolkit and brings the content of a project from the DRS into Wordpress using the DRS API.
 */


// rsearch from https://stackoverflow.com/questions/17160696/php-glob-scan-in-subfolders-for-a-file
// a pseudo-autoloader, just rolling through everything to get the file list
function rsearch($folder, $regPattern) {
    $folder = plugin_dir_path(__FILE__) . $folder;
    $dir = new RecursiveDirectoryIterator($folder);
    $ite = new RecursiveIteratorIterator($dir);
    $files = new RegexIterator($ite, $regPattern, RegexIterator::GET_MATCH);
    $fileList = array();
    foreach($files as $file) {
        $fileList = array_merge($fileList, $file);
    }
    return $fileList;
}

$dirs = [
    
    'admin-ui',
    'debug',
    'public-ui',
    'query',
    'util',
    'inc'
];
$allFiles = [];

foreach($dirs as $dir) {
    $fileList = rsearch($dir, '/.*\.php/');
    $allFiles = array_merge($allFiles, $fileList);
}

foreach($allFiles as $file) {
    try {
        require_once($file);
    } catch (\Exception $e) {
        throw $e;
    }
}

require_once(plugin_dir_path(__FILE__) . 'config.php');
require_once(plugin_dir_path(__FILE__) . 'ceres_adapters.php');

define('ALLOW_UNFILTERED_UPLOADS', true); //this will allow files without extensions - aka from fedora
define('DRS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('DRS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DPLA_FALLBACK_IMAGE_URL', DRS_PLUGIN_URL . 'assets/images/DPLA-square-logo-color.jpeg');
define(
    'DRSTK_PODCAST_REGISTER_HTML',
    "
<small>When you register your podcast with this service, it will tell you the URL to use here.</small>
<br /><small>Use this feed URL to tell the service where to look for your podcasts: <br />" . get_site_url() . "?feed=podcasts</small><br/>
"
);

// Set template names here so we don't have to go into the code.
$TEMPLATE = array(
    'browse_template' => dirname(__FILE__) . '/templates/browse.php',
    'item_template' => dirname(__FILE__) . '/templates/item.php',
    'download_template' => dirname(__FILE__) . '/templates/download.php',
    'mirador_template' => dirname(__FILE__) . '/templates/mirador.php',
);

$TEMPLATE_THEME = array(
    'browse_template' => 'overrides/drstk-browse.php',
    'item_template' => 'overrides/drstk-item.php',
    'download_template' => 'overrides/drstk-download.php',
    'mirador_template' => 'overrides/drstk-mirador.php',
);

register_activation_hook(__FILE__, 'drstk_install');
register_deactivation_hook(__FILE__, 'drstk_deactivation');
$all_meta_options = array("Title", "Alternative Title", "Creator", "Contributor", "Publisher", "Type of Resource", "Genre", "Language", "Physical Description", "Abstract/Description", "Table of contents", "Notes", "Subjects and keywords", "Related item", "Identifier", "Access condition", "Location", "uri", "Format", "Permanent URL", "Date created", "Date issued", "Copyright date", "Biographical/Historical", "Biográfica/histórica", "Issuance", "Frequency", "Digital origin", "Map data", "Use and reproduction", "Restriction on access");
$all_assoc_meta_options = array("full_title_ssi", "creator_tesim", "abstract_tesim");



function mce_plugin($plugin_array) {
    $plugin_array['drstkshortcodes'] = DRS_PLUGIN_URL . '/assets/js/mce-button.js';
    return $plugin_array;
}

function add_tinymce_plugin() {
    add_filter("mce_external_plugins", 'mce_plugin');
}

/**
 * Rewrite rules for the plugin.
 */

function drstk_rewrite_rule() {
    global $post;
    $home_url = get_option('drstk_home_url');
    add_rewrite_rule('^' . $home_url . 'browse/?$', 'index.php?post_type=drs&drstk_template_type=browse', 'top');
    add_rewrite_rule('^' . $home_url . 'search/?$', 'index.php?post_type=drs&drstk_template_type=search', 'top');
    add_rewrite_rule('^' . $home_url . 'item/([^/]*)/?([^/]*)*', 'index.php?post_type=drs&drstk_template_type=item&pid=$matches[1]&js=$matches[2]', 'top');
    add_rewrite_rule('^' . $home_url . 'download/([^/]*)/?', 'index.php?post_type=drs&drstk_template_type=download&pid=$matches[1]', 'top');
    add_rewrite_rule('^' . $home_url . 'collections/?$', 'index.php?post_type=drs&drstk_template_type=collections', 'top');
    add_rewrite_rule('^' . $home_url . 'collection/([^/]*)/?', 'index.php?post_type=drs&drstk_template_type=collection&pid=$matches[1]', 'top');
    $mirador_url = get_option('drstk_mirador_url') == '' ? 'mirador' : get_option('drstk_mirador_url');
    add_rewrite_rule('^' . $home_url . $mirador_url . '/?$', 'index.php?post_type=drs&drstk_template_type=mirador', 'top');
    if (get_option('drstk_item_extensions') == "on") {
        $args = array(
            'post_type' => 'drstk_item_extension',
            'posts_per_page' => 1,
            'post_status' => 'publish',
        );
        $meta_query = new WP_Query($args);
        if ($meta_query->have_posts()) {
            while ($meta_query->have_posts()) {
                $meta_query->the_post();
                $post_id = $post->ID;
                $item_url = get_post_meta($post_id, 'item-url', true);
                $item_id = get_post_meta($post_id, 'item-id', true);
                if (isset($item_url) && isset($item_id)) {
                    add_rewrite_rule("^$home_url$item_url/?$", 'index.php?post_type=drs&drstk_template_type=item&pid=' . $item_id, 'top');
                }
            }
        }
    }
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



/*DRS API Auth Enabled helper method */

function drstk_api_auth_enabled() {
    $dau = constant("DRS_API_USER");
    $dap = constant("DRS_API_PASSWORD");
    // search config.php for username and password
    // if they're both not blank, use them and ask DRS API for a JWT token
    if (empty($dau) || empty($dap)) {
        return false;
    } else {
        return true;
    }
}

/*DRS API Authenticate helper method*/
function drstk_drs_auth() {
    if (drstk_api_auth_enabled() == true) {
        // Token is only good for one hour

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://repository.library.northeastern.edu/api/v1/auth_user");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "email=" . DRS_API_USER . "&password=" . DRS_API_PASSWORD);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $headers = array();
        $headers[] = "Content-Type: application/x-www-form-urlencoded";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);

        // result should be json
        $data = json_decode($result, true);

        $token = $data["auth_token"];

        if (!empty($token)) {
            return $token;
        } else {
            return false;
        }
    } else {
        // No user and/or password set
        return false;
    }
}


function drstk_plugin_settings_save() {
    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
        drstk_install();
        //plugin settings have been saved.
        // $collection_pid = drstk_get_pid();
    }
}

/* ACTIONS */

add_action('admin_enqueue_scripts', 'drstk_admin_enqueue');
add_action('admin_head', 'fix_admin_head');
add_action('wp', 'drstk_add_hypothesis');
add_action('init', 'drstk_add_podcast_feed');
add_action('init', 'drstk_rewrite_rule');
add_action('init', 'remove_bstw_widget_text_filters');
add_action('init', 'create_post_type');
add_action('wp_feed_options', 'drstk_turn_off_feed_caching');
add_action('admin_menu', 'drs_admin_add_page');
add_action('admin_init', 'register_drs_settings');
add_action('admin_init', 'add_tinymce_plugin');

/* FILTERS */

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
        } elseif ($template_type == 'item') {
            global $item_pid;
            $item_pid = get_query_var('pid');
            add_action('wp_enqueue_scripts', 'drstk_item_script');

            // look for theme template first, load plugin template as fallback
            $theme_template = locate_template( array( $TEMPLATE_THEME['item_template'] ) );
            return ($theme_template ? $theme_template : $TEMPLATE['item_template']);
        } elseif ($template_type == 'download') {
          global $item_pid;
          $item_pid = get_query_var('pid');

          // look for theme template first, load plugin template as fallback
          $theme_template = locate_template( array( $TEMPLATE_THEME['download_template'] ) );
          return ($theme_template ? $theme_template : $TEMPLATE['download_template']);
        } elseif ($template_type == 'mirador') {
          add_action('wp_enqueue_scripts', 'drstk_mirador_script');

          // look for theme template first, load plugin template as fallback
          $theme_template = locate_template( array( $TEMPLATE_THEME['mirador_template'] ) );
          return ($theme_template ? $theme_template : $TEMPLATE['mirador_template']);
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
    global $sub_collection_pid;
    $errors = drstk_get_errors();
    //this enqueues the JS file
    wp_register_script( 'drstk_browse',
        plugins_url( '/assets/js/browse.js', __FILE__ ),
        array( 'jquery' )
    );
    wp_enqueue_script('drstk_browse');
    $search_options = get_option('drstk_search_metadata');
    $browse_options = get_option('drstk_browse_metadata');
    $default_sort = get_option('drstk_default_sort');
    $default_browse_per_page = get_option('drstk_default_browse_per_page');
    $default_search_per_page = get_option('drstk_default_search_per_page');
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
      'default_facet_sort' => $default_facet_sort,
      'default_browse_per_page' => $default_browse_per_page,
      'default_search_per_page' => $default_search_per_page,
      'search_show_facets' => get_option('drstk_search_show_facets'),
      'browse_show_facets' => get_option('drstk_browse_show_facets'),
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
    global $wp_query;
    global $item_pid;
    
    $errors = drstk_get_errors();
    $item_nonce = wp_create_nonce( 'item_drs' );

    //this enqueues the JS file
    wp_register_script('drstk_cdn_jwplayer', 'https://content.jwplatform.com/libraries/dTFl0VEe.js');
    wp_enqueue_script('drstk_cdn_jwplayer');
    wp_register_script('drstk_elevatezoom',plugins_url('/assets/js/elevatezoom/jquery.elevateZoom-3.0.8.min.js', __FILE__), array());
    wp_enqueue_script('drstk_elevatezoom');
    wp_register_script('drstk_item_gallery', plugins_url('/assets/js/item_gallery.js', __FILE__));
    wp_enqueue_script('drstk_item_gallery');

    //this allows an ajax call from browse.js
    $item_obj = array(
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce'    => $item_nonce,
      'template' => $wp_query->query_vars['drstk_template_type'],
      'home_url' => drstk_home_url(),
    );

    wp_localize_script( 'drstk_item_gallery', 'item_obj', $item_obj );
}

function drstk_breadcrumb_script(){
  global $wp_query;
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

function drstk_mirador_script() {
    global $wp_query;
    // this appears unused, but at least it isn't the global it used to be
    $errors = drstk_get_errors();
    
    //this enqueues the JS file
    wp_register_script('drstk_mirador', plugins_url('/assets/mirador/mirador.js', __FILE__));
    wp_enqueue_script('drstk_mirador');
    wp_register_script('drstk_mirador_manifest',plugins_url('/assets/mirador/mirador_manifest.js', __FILE__), array());
    wp_enqueue_script('drstk_mirador_manifest');
    wp_register_style('drstk_mirador_style', plugins_url('/assets/mirador/css/mirador-combined.min.css', __FILE__), array());
    wp_enqueue_style('drstk_mirador_style');
}

/*fix for weird jumpiness in wp admin menu*/
function fix_admin_head() {
	echo "<script type='text/javascript'>jQuery(window).load(function(){jQuery('#adminmenuwrap').hide().show(0);});</script>";
  echo "<style>#start-pt-pb-tour{display:none !important;}";
}
add_action( 'admin_head', 'fix_admin_head' );


/**
* Basic curl response mechanism.
* Designed here to make it easy to output some message, even in the case of an error
* For debugging, the fuller status info is passed along for inspection when needed
* 
* Typical usage:
* $response = get_response($url);
* $output = $response['output'];
* echo $output;
* 
* Fancier:
* $response = get_response($url);
* if ($response['status'] == 404) {
*   $output = 'No soup for you!';
* }
* echo $output;
*/
function get_response($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
  curl_setopt($ch, CURLOPT_FAILONERROR, false);
  $raw_response = curl_exec($ch);
  // @TODO:  when we're up to PHP > 5.5, CURLINFO_HTTP_CODE should be CURLINFO_RESPONSE_CODE
  $response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//$response_status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
  
  //fallback for PHP < 5.5
  // @TODO remove this once our servers are upgraded, so we can keep using modern(ish) PHP practices
  if (! $response_status) {
    $response_status_array = curl_getinfo($ch);
    $response_status = $response_status_array['http_code'];
  }
  
  switch ($response_status) {
    case 200:
      $output = $raw_response;
      $status_message = 'OK';
      break;
    case 404:
      $output = 'The resource was not found.';
      $status_message = 'Not Found';
      break;
    case 302:
      // check if there's json in it anyway
      $json = json_decode($raw_response);
      if (is_object($json)) {
        $output = $raw_response;
      } else {
        $output = 'An unknown error occured -- ' . $response_status;
      }
      $status_message = 'The resource has moved or is no longer available';
      break;
    default:
      $output = 'An unknown error occured.' . $response_status;
      $status_message = 'An unkown error occured. Please try again';
      break;
      
  }
  $response = array(
    'status' => $response_status,
    'status_message' => $status_message,
    'output' => $output,
  );
  curl_close($ch);
  return $response;
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
add_filter("attachment_fields_to_save", "drstk_image_attachment_fields_to_save", 10, 2);
add_filter('query_vars', 'drstk_add_query_var');
add_filter('wp_feed_cache_transient_lifetime', 'debug_change_feed_cache_transient_lifetime', 200000);
add_filter('template_include', 'drstk_podcast_page_template', 100);
