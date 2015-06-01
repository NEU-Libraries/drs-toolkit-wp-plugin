<?php
/**
 * Plugin Name: DRS Toolkit Plugin
 * Plugin URI:
 * Version: 0.1
 * Author: Eli Zoller
 * Description: This plugin provides the core functionality of the DRS Project Toolkit and brings the content of a project from the DRS into Wordpress using the DRS API.
 */

require_once( plugin_dir_path( __FILE__ ) . 'inc/item.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/browse.php' );
define( 'ALLOW_UNFILTERED_UPLOADS', true ); //this will allow files without extensions - aka from fedora

$VERSION = '0.1.0';

// Set template names here so we don't have to go into the code.
$TEMPLATE = array(
    //'custom_template' => 'nusolr-template.php', //this attaches the plugin to the separate theme template
    'browse_template' => dirname(__FILE__) . '/templates/browse.php',
    'item_template' => dirname(__FILE__) . '/templates/item.php',
);

 function drstk_install() {
     // Clear the permalinks after the post type has been registered
     drstk_rewrite_rule();
     flush_rewrite_rules();
 }

 function get_media_images($collection_pid) {
   //echo $collection_pid;

   //first we get the existing images
   $query_images_args = array(
      'post_type' => 'attachment', 'post_mime_type' =>'image', 'post_status' => 'inherit', 'posts_per_page' => -1,
    );
   $query_images = new WP_Query( $query_images_args );
   $images = array();
   foreach ( $query_images->posts as $image) {
      $images[]= basename(wp_get_attachment_url( $image->ID ));
   }
    //print_r($images);
    //get all the core_files from the drs
    $drs_url = "http://cerberus.library.northeastern.edu/api/v1/search/".$collection_pid."?per_page=20";
    //will there be some kind of helper with the api to get them all back at once? don't want to waste time writing a function that would loop through the pages of results
    $json = get_response($drs_url);
    //print_r($json);
    $json = json_decode($json);
    if ($json->response->response->numFound > 0) {
      foreach($json->response->response->docs as $doc) {
        if ($doc->active_fedora_model_ssi == "CoreFile") {
          $title = $doc->title_ssi;
          $url = "http://cerberus.library.northeastern.edu" . end($doc->fields_thumbnail_list_tesim);
          //$url = str_replace("thumbnail_1","content", $url);
          echo $url;
          process_image($url, $images);
        }
      }
    }
  }

  function process_image($url, $images){
    //$url = "https://repository.library.northeastern.edu/downloads/neu:345593?datastream_id=content";
    $pid = explode("/", $url);
    $pid = explode("?", end($pid));
    $pid = str_replace(":","",$pid[0]);
    echo $pid;
    if (!in_array($pid, $images)){
      $tmp = download_url( $url );
      $post_id = 0;
      $desc = "The WordPress Logo";
      $file_array = array();

      // Set variables for storage
      $file_array['name'] = $pid;
      $file_array['type'] = 'image/jpeg';
      $file_array['error'] = 0;
      $file_array['tmp_name'] = $tmp;
      $file_array['size'] = filesize($tmp);
      //print_r($file_array);

      // If error storing temporarily, unlink
      if ( is_wp_error( $tmp ) ) {
        @unlink($file_array['tmp_name']);
        $file_array['tmp_name'] = '';
      }

      // do the validation and storage stuff
      $id = media_handle_sideload( $file_array, 0);
      echo $id . "<br/>";

      // If error storing permanently, unlink
      if ( is_wp_error($id) ) {
        @unlink($file_array['tmp_name']);
        return $id;
      }

      $src = wp_get_attachment_url( $id );
      $image_id = get_image_id($src);
      echo $src . "<br/>";
      echo $image_id . "<br/>";
      //permalink redirect from image url to item level page?
    }
  }

  function get_image_id($image_url) {
  	global $wpdb;
  	$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
          return $attachment[0];
  }


//This function creates the settings page for entering the pid
 add_action('admin_menu', 'drs_admin_add_page');
 function drs_admin_add_page() {
   $hook = add_options_page('Settings for DRS Toolkit Plugin', 'DRS Toolkit', 'manage_options', 'drstk_admin_menu', 'drstk_display_settings');
   add_action('load-'.$hook,'drstk_plugin_settings_save');
 }

  function drstk_plugin_settings_save()
  {
    if(isset($_GET['settings-updated']) && $_GET['settings-updated'])
     {
        //plugin settings have been saved.
        $collection_pid = get_option('drstk_collection');
        $sync_images = get_option('drstk_sync_images');
        if ($sync_images == true) {
          get_media_images($collection_pid);
        }
     }
  }

//this creates the form for entering the pid on the settings page
 function drstk_display_settings() {

     $collection_pid = (get_option('drstk_collection') != '') ? get_option('drstk_collection') : 'neu:1';
     $sync_images = get_option('drstk_sync_images');

     $html = '</pre>
     <div class="wrap">
     <form action="options.php" method="post" name="options">
     <h2>Select Your Settings</h2>
     ' . wp_nonce_field('update-options') . '
     <table class="form-table" width="100%" cellpadding="10">
     <tbody>
     <tr>
     <td scope="row" align="left">
      <label>Project Collection ID</label>
     <input name="drstk_collection" type="text" value="'.$collection_pid.'"></input>
     <br/>
     <small>Ie. If the URL to your collection is <a href="https://repository.library.northeastern.edu/collections/neu:6012">https://repository.library.northeastern.edu/collections/neu:6012</a> then the ID is neu:6012</small>
     </td>
     </tr>
     <tr>
     <td scope="row" align="left">
     <label>Sync Images</label>
     <input type="checkbox" value="true" name="drstk_sync_images" ';
     if ($sync_images == true) {
       $html .= 'checked="checked"';
     }
     $html .= ' ></input>
     <br/>
     <small>If you would like to sync images from the DRS, check the box.</small>
     </td>
     </tr>
     </tbody>
     </table>
      <input type="hidden" name="action" value="update" />

      <input type="hidden" name="page_options" value="drstk_collection, drstk_sync_images" />

      <input type="submit" name="Submit" value="Update" /></form></div>
     <pre>
     ';

     echo $html;

 }

 function drstk_deactivation() {
     // Clear the permalinks to remove our post type's rules
     flush_rewrite_rules();
 }
 register_activation_hook( __FILE__, 'drstk_install' );
 register_deactivation_hook( __FILE__, 'drstk_deactivation' );

 /**
  * Rewrite rules for the plugin.
  */
 add_action('init', 'drstk_rewrite_rule');
 function drstk_rewrite_rule() {

     add_rewrite_rule('^browse/?$',
         'index.php?post_type=drs&drstk_template_type=browse',
         'top');
     add_rewrite_rule('^search/?$', 'index.php?post_type=drs&drstk_template_type=search', 'top');
     add_rewrite_rule('^item/?$', 'index.php?post_type=drs&drstk_template_type=item', 'top');

 }

 /**
  * Register an additional query variable so we can differentiate between
  * the types of custom queries that are generated
  */
 add_filter('query_vars', 'drstk_add_query_var');
 function drstk_add_query_var($public_query_vars){
     $public_query_vars[] = 'drstk_template_type';
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

    if ( isset($wp_query->query_vars['drstk_template_type']) ) {

        $template_type = $wp_query->query_vars['drstk_template_type'];

        if ($template_type == 'browse' || $template_type == 'search') {
            add_action('wp_enqueue_scripts', 'drstk_browse_script');
            //echo $template_type;
            #return locate_template( array( 'view.php' ) );
            //get_or_create_query($wp_query);
            return $TEMPLATE['browse_template'];

        }

        if ($template_type == 'item') {
            add_action('wp_enqueue_scripts', 'drstk_item_script');
            //echo "template is item";
            #return locate_template( array( 'view.php' ) );
            $pid = get_query_var( 'pid' );
            //echo "we are abotu to call get or create";
            get_or_create_doc( $wp_query, $pid );

            return $TEMPLATE['item_template'];
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
    //this enqueues the JS file
    wp_enqueue_script( 'drstk_browse',
        plugins_url( '/assets/js/browse.js', __FILE__ ),
        array( 'jquery' )
    );
    wp_enqueue_style( 'drstk_browse_style', plugins_url('/assets/css/browse.css', __FILE__));
    //this creates a unique nonce to pass back and forth from js/php to protect
    $browse_nonce = wp_create_nonce( 'browse_drs' );
    //this allows an ajax call from browse.js
    wp_localize_script( 'drstk_browse', 'browse_obj', array(
       'ajax_url' => admin_url( 'admin-ajax.php' ),
       'nonce'    => $browse_nonce,
       'template' => $wp_query->query_vars['drstk_template_type'],
    ) );
}

/**
 * Load scripts for the doc/page views
 */
function drstk_item_script() {
    global $VERSION;
    wp_register_script('drstk_item',plugins_url('/assets/js/item.js', __FILE__), array('jquery'), $VERSION, false );
    wp_enqueue_script('drstk_item');
}

function no_javascript_alternative(){
  //this is where an alternative function for not using javascript would go
  //it would basically create a form to populate the page and require manually clicking a submit button to reload the page with selected options
}
