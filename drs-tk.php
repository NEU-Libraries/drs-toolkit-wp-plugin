<?php

namespace Drstk;

/**
 * Plugin Name: CERES: Exhibit Toolkit Plugin
 * Plugin URI:
 * Version: 1.6.9
 * Author: Digital Scholarship Group, Northeastern University. Eli Zoller, Patrick Murray-John, Jeanine Rodriguez et al.
 * Description: This plugin provides the core functionality of the CERES: Exhibit Toolkit and brings the content of a project from the DRS into Wordpress using the DRS API.
 */

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
require_once( plugin_dir_path( __FILE__ ) . 'inc/metabox.php' );
require_once( plugin_dir_path( __FILE__ ) . 'config.php' );


/* Moving toward a Ceres namespace for podcasting */
// require_once( plugin_dir_path( __FILE__ ) . 'classes/Ceres_Abstract_Fetcher.php' );
// require_once( plugin_dir_path( __FILE__ ) . 'classes/Ceres_Abstract_Renderer.php' );
// require_once( plugin_dir_path( __FILE__ ) . 'classes/Ceres_Drs_Fetcher.php' );
// require_once( plugin_dir_path( __FILE__ ) . 'classes/Ceres_Podcast_Renderer.php' );
// require_once( plugin_dir_path( __FILE__ ) . 'classes/Ceres_Podcast_Rss_Renderer.php' );
// require_once( plugin_dir_path( __FILE__ ) . 'classes/Ceres_Jwplayer_Renderer.php' );


define( 'ALLOW_UNFILTERED_UPLOADS', true ); //this will allow files without extensions - aka from fedora
define('DRS_PLUGIN_PATH', plugin_dir_path( __FILE__ ));
define('DRS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('DPLA_FALLBACK_IMAGE_URL', DRS_PLUGIN_URL . 'assets/images/DPLA-square-logo-color.jpeg');
define('DRSTK_PODCAST_REGISTER_HTML',
"
<small>When you register your podcast with this service, it will tell you the URL to use here.</small>
<br /><small>Use this feed URL to tell the service where to look for your podcasts: <br />" . get_site_url() . "?feed=podcasts</small><br/>
");

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

 register_activation_hook( __FILE__, 'drstk_install' );
 register_deactivation_hook( __FILE__, 'drstk_deactivation' );
 $all_meta_options = array("Title","Alternative Title","Creator","Contributor","Publisher","Type of Resource","Genre","Language","Physical Description","Abstract/Description","Table of contents","Notes","Subjects and keywords","Related item","Identifier","Access condition","Location","uri","Format","Permanent URL","Date created","Date issued","Copyright date","Biographical/Historical","Biográfica/histórica", "Issuance","Frequency","Digital origin","Map data","Use and reproduction","Restriction on access");
 $all_assoc_meta_options = array("full_title_ssi","creator_tesim","abstract_tesim");



function mce_plugin($plugin_array){
  $plugin_array['drstkshortcodes'] = DRS_PLUGIN_URL.'/assets/js/mce-button.js';
  return $plugin_array;
}

function add_tinymce_plugin(){
  add_filter("mce_external_plugins", 'mce_plugin');
}

 /**
  * Rewrite rules for the plugin.
  */

 function drstk_rewrite_rule() {
    global $post;
    $home_url = get_option('drstk_home_url');
    add_rewrite_rule('^'.$home_url.'browse/?$', 'index.php?post_type=drs&drstk_template_type=browse', 'top');
    add_rewrite_rule('^'.$home_url.'search/?$', 'index.php?post_type=drs&drstk_template_type=search', 'top');
    add_rewrite_rule('^'.$home_url.'item/([^/]*)/?([^/]*)*', 'index.php?post_type=drs&drstk_template_type=item&pid=$matches[1]&js=$matches[2]', 'top');
    add_rewrite_rule('^'.$home_url.'download/([^/]*)/?', 'index.php?post_type=drs&drstk_template_type=download&pid=$matches[1]', 'top');
    add_rewrite_rule('^'.$home_url.'collections/?$', 'index.php?post_type=drs&drstk_template_type=collections', 'top');
    add_rewrite_rule('^'.$home_url.'collection/([^/]*)/?', 'index.php?post_type=drs&drstk_template_type=collection&pid=$matches[1]', 'top');
    $mirador_url = get_option('drstk_mirador_url') == '' ? 'mirador' : get_option('drstk_mirador_url');
    add_rewrite_rule('^'.$home_url.$mirador_url.'/?$', 'index.php?post_type=drs&drstk_template_type=mirador', 'top');
    if (get_option('drstk_item_extensions') == "on"){
      $args = array(
        'post_type' => 'drstk_item_extension',
        'posts_per_page' => 1,
        'post_status' => 'publish',
      );
      $meta_query = new WP_Query( $args );
      if ($meta_query->have_posts()){
        while ($meta_query->have_posts()){
          $meta_query->the_post();
          $post_id = $post->ID;
          $item_url = get_post_meta($post_id, 'item-url', true);
          $item_id = get_post_meta($post_id, 'item-id', true);
          if (isset($item_url) && isset($item_id)){
            add_rewrite_rule("^$home_url$item_url/?$", 'index.php?post_type=drs&drstk_template_type=item&pid='.$item_id, 'top');
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

//This function creates the settings page for entering the pid
add_action('admin_menu', 'drs_admin_add_page');
add_action( 'admin_init', 'register_drs_settings' );
add_action( 'admin_init', 'add_tinymce_plugin');

/*DRS API Auth Enabled helper method */

function drstk_api_auth_enabled(){
  $dau = constant("DRS_API_USER");
  $dap = constant("DRS_API_PASSWORD");
  // search config.php for username and password
  // if they're both not blank, use them and ask DRS API for a JWT token
  if (empty($dau) || empty($dap))
  {
    return false;
  }
  else
  {
    return true;
  }
}

/*DRS API Authenticate helper method*/
function drstk_drs_auth(){
  if(drstk_api_auth_enabled() == true){
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
  }
  else {
    // No user and/or password set
    return false;
  }
}


function drstk_plugin_settings_save(){
  if(isset($_GET['settings-updated']) && $_GET['settings-updated'])
   {
      drstk_install();
      //plugin settings have been saved.
      // $collection_pid = drstk_get_pid();
   }
}

/* ACTIONS */

add_action('admin_enqueue_scripts', 'drstk_admin_enqueue');
add_action('admin_head', 'fix_admin_head' );
add_action('wp', 'drstk_add_hypothesis');
add_action('init', 'drstk_add_podcast_feed');
add_action('init', 'drstk_rewrite_rule');
add_action( 'init', 'remove_bstw_widget_text_filters' );
add_action('init', 'create_post_type');

/* FILTERS */

add_filter('template_include', 'drstk_content_template', 1, 1);
add_filter("attachment_fields_to_edit", "drstk_image_attachment_fields_to_edit", null, 2);
add_filter("attachment_fields_to_save", "drstk_image_attachment_fields_to_save", 10, 2);
add_filter('query_vars', 'drstk_add_query_var');






/* Dev on Podcast site options */
add_filter( 'template_include', 'drstk_podcast_page_template', 100 );


/* End Dev on Podcast site */


require_once( plugin_dir_path( __FILE__ ) . 'ceres_adapters.php' );
