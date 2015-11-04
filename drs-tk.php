<?php
/**
 * Plugin Name: DRS Toolkit Plugin
 * Plugin URI:
 * Version: 0.8
 * Author: Eli Zoller
 * Description: This plugin provides the core functionality of the DRS Project Toolkit and brings the content of a project from the DRS into Wordpress using the DRS API.
 */

require_once( plugin_dir_path( __FILE__ ) . 'inc/item.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/import.php' );
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
    //'custom_template' => 'nusolr-template.php', //this attaches the plugin to the separate theme template
    'browse_template' => dirname(__FILE__) . '/templates/browse.php',
    'item_template' => dirname(__FILE__) . '/templates/item.php',
    'item_nojs_template' => dirname(__FILE__) . '/templates/item_nojs.php',
);

 register_activation_hook( __FILE__, 'drstk_install' );
 register_deactivation_hook( __FILE__, 'drstk_deactivation' );

 wp_register_script('drstk_jwplayer',
     plugins_url('/assets/js/jwplayer/jwplayer.js', __FILE__),
     array(), $VERSION, false );

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

  function drstk_plugin_settings_save()
  {
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

//this creates the form for entering the pid on the settings page
 function drstk_display_settings() {

     $collection_pid = (get_option('drstk_collection') != '') ? get_option('drstk_collection') : 'https://repository.library.northeastern.edu/collections/neu:1';
     $html = '</pre>
     <div class="wrap">
     <form action="options.php" method="post" name="options">
     <h2>Select Your Settings</h2>'. wp_nonce_field('update-options') . '
     <table class="form-table" width="100%" cellpadding="10">
     <tbody>
     <tr>
     <td scope="row" align="left">
      <label>Project Collection URL</label>
     <input name="drstk_collection" type="text" value="'.$collection_pid.'" style="width:100%;"></input>
     <br/>
     <small>Ie. <a href="https://repository.library.northeastern.edu/collections/neu:6012">https://repository.library.northeastern.edu/collections/neu:6012</a></small>
     </td>
     </tr>
     </tbody>
     </table>
     <table class="form-table" width="100%">
     <tbody>
     <tr>
     <td><h4>Search Settings</h4></td>
     </tr>
     <tr><td>Search Page Title<br/>
     <input type="text" name="drstk_search_page_title" value="';
     if (get_option('drstk_search_page_title') == ''){ $html .= 'Search';} else { $html .= get_option('drstk_search_page_title'); }
     $html .= '" /></td>
     </tr>
     <tr>
     <td>What metadata should be visible for each record by default?</td>
     </tr>
     <tr>
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
     <table class="form-table" width="100%">
     <tbody>
     <tr>
     <td><h4>Browse Settings</h4></td>
     </tr>
     <tr><td>Browse Page Title<br/>
     <input type="text" name="drstk_browse_page_title" value="';
     if (get_option('drstk_browse_page_title') == ''){ $html .= 'Browse';} else {$html .= get_option('drstk_browse_page_title');}
     $html .='" /></td>
     </tr>
     <tr>
     <td>What metadata should be visible for each record by default?</td>
     </tr>
     <tr>
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
     <table>
     <tbody>
     <tr>
     <td><h4>Collections Page Settings</h4></td>
     </tr>
     <tr><td>Collections Page Title<br/>
     <input type="text" name="drstk_collections_page_title" value="';
     if (get_option('drstk_collections_page_title') == ''){ $html .= 'Collections';} else { $html .= get_option('drstk_collections_page_title'); }
     $html .='" /></td>
     </tr>
     <tr>
     <td><h4>Single Collection Page Settings</h4></td>
     </tr>
     <tr><td>Single Collection Page Title<br/>
     <input type="text" name="drstk_collection_page_title" value="';
     if (get_option('drstk_collection_page_title') == ''){ $html .= 'Browse';} else { $html .= get_option('drstk_collection_page_title'); }
     $html .='" /></td>
     </tr>
     </tbody>
     </table>

      <input type="hidden" name="action" value="update" />

      <input type="hidden" name="page_options" value="drstk_collection, drstk_search_title, drstk_search_creator, drstk_search_date, drstk_search_abstract, drstk_browse_title, drstk_browse_creator, drstk_browse_abstract, drstk_browse_date, drstk_search_page_title, drstk_browse_page_title, drstk_collection_page_title, drstk_collections_page_title" />
      <br/><br/>
      <input type="submit" name="Submit" value="Update" class="button" style="font-size: 16px;padding: 10px 20px;height: auto;"/></form></div>
      <br/>
      <table>
      <tr>
      <td><a class="button" id="drstk-import" href="#" disabled="true">Import Items from the DRS</a></td>
      </tr>
      </table>
     <pre>
     ';

     echo $html;

 }

 function drstk_admin_enqueue() {
    if (get_current_screen()->base == 'settings_page_drstk_admin_menu') {
      //we are on the settings page
      wp_enqueue_script( 'drstk_import',
          plugins_url( '/assets/js/import.js', __FILE__ ),
          array( 'jquery' )
      );
      //this creates a unique nonce to pass back and forth from js/php to protect
      $import_nonce = wp_create_nonce( 'import_drs' );
      $import_data_nonce = wp_create_nonce( 'import_data_drs' );
      //this allows an ajax call from import.js
      wp_localize_script( 'drstk_import', 'import_obj', array(
         'ajax_url' => admin_url( 'admin-ajax.php' ),
         'import_nonce'    => $import_nonce,
         'import_data_nonce' => $import_data_nonce,
         'pid' => get_option('drstk_collection'),
      ) );

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

    if ( isset($wp_query->query_vars['drstk_template_type']) ) {

        $template_type = $wp_query->query_vars['drstk_template_type'];

        if ($template_type == 'browse' || $template_type == 'search' || $template_type == 'collections' || $template_type == 'collection') {
            global $sub_collection_pid;
            $sub_collection_pid = get_query_var( 'pid' );
            add_action('wp_enqueue_scripts', 'drstk_browse_script');
            if ($template_type == 'collection') {
              add_action('wp_enqueue_scripts', 'drstk_breadcrumb_script');
            }
            return $TEMPLATE['browse_template'];
        }

        if ($template_type == 'item') {
            global $item_pid;
            $item_pid = get_query_var( 'pid' );
          if (isset ($wp_query->query_vars['js']) && $wp_query->query_vars['js'] == 'false'){
            include( plugin_dir_path( __FILE__ ) . 'inc/item_nojs.php' );
            return $TEMPLATE['item_nojs_template'];
          } else {
            add_action('wp_enqueue_scripts', 'drstk_item_script');
            add_action('wp_enqueue_scripts', 'drstk_breadcrumb_script');
            return $TEMPLATE['item_template'];
          }
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
    //this enqueues the JS file
    wp_enqueue_script( 'drstk_browse',
        plugins_url( '/assets/js/browse.js', __FILE__ ),
        array( 'jquery' )
    );
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
    //wp_enqueue_style( 'drstk_browse_style', plugins_url('/assets/css/browse.css', __FILE__));
    //this creates a unique nonce to pass back and forth from js/php to protect
    $browse_nonce = wp_create_nonce( 'browse_drs' );
    //this allows an ajax call from browse.js
    wp_localize_script( 'drstk_browse', 'browse_obj', array(
       'ajax_url' => admin_url( 'admin-ajax.php' ),
       'nonce'    => $browse_nonce,
       'template' => $wp_query->query_vars['drstk_template_type'],
       'site_url' => $SITE_URL,
       'sub_collection_pid' => $sub_collection_pid,
       'search_options' => json_encode($search_options),
       'browse_options' => json_encode($browse_options),
    ) );
}

/**
 * Load scripts for the doc/page views
 */
function drstk_item_script() {
    global $VERSION;
    global $wp_query;
    global $item_pid;
    //this enqueues the JS file
    wp_register_script('drstk_elevatezoom',
        plugins_url('/assets/js/elevatezoom/jquery.elevateZoom-3.0.8.min.js', __FILE__),
        array());
    wp_enqueue_script('drstk_elevatezoom');
    wp_enqueue_script( 'drstk_item',
        plugins_url( '/assets/js/item.js', __FILE__ ),
        array( 'jquery' )
    );
    wp_enqueue_script('drstk_jwplayer');

    //this creates a unique nonce to pass back and forth from js/php to protect
    $item_nonce = wp_create_nonce( 'item_drs' );
    //this allows an ajax call from item.js
    wp_localize_script( 'drstk_item', 'item_obj', array(
       'ajax_url' => admin_url( 'admin-ajax.php' ),
       'nonce'    => $item_nonce,
       'template' => $wp_query->query_vars['drstk_template_type'],
       'pid' => $item_pid,
    ) );
}

function drstk_breadcrumb_script(){
  global $wp_query;
  global $VERSION;
  global $SITE_URL;
  global $sub_collection_pid;
  global $item_pid;

  wp_enqueue_script( 'drstk_breadcrumb',
      plugins_url( '/assets/js/breadcrumb.js', __FILE__ ),
      array( 'jquery' )
  );
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

/* Add custom field to attachment for DRS Metadata */
function drstk_image_attachment_add_custom_fields($form_fields, $post) {
  if (get_post_meta($post->ID, "drstk-creator")){
    $form_fields["drstk-creator"] = array();
    $form_fields["drstk-creator"]["label"] = __("Creator");
    $form_fields["drstk-creator"]["input"] = "html";
    $form_fields["drstk-creator"]["value"] = get_post_meta($post->ID, "drstk-creator", true);
    $form_fields["drstk-creator"]["html"] = "<p>This field is imported from the DRS. Any changes must be made directly in the DRS.</p><tr><td>".get_post_meta($post->ID, "drstk-creator", true)."</td></tr>";
  }

  if (get_post_meta($post->ID, "drstk-date-created")){
    $form_fields["drstk-date-created"] = array();
    $form_fields["drstk-date-created"]["label"] = __("Date Created");
    $form_fields["drstk-date-created"]["input"] = "html";
    $form_fields["drstk-date-created"]["value"] = get_post_meta($post->ID, "drstk-date-created", true);
    $form_fields["drstk-date-created"]["html"] = "<p>This field is imported from the DRS. Any changes must be made directly in the DRS.</p><tr><td></td><td>".get_post_meta($post->ID, "drstk-date-created", true)."</td></tr>";
  }
  if (get_post_meta($post->ID, "drstk-pid")){
    $form_fields["drstk-pid"] = array();
    $form_fields["drstk-pid"]["label"] = __("CoreFile Pid");
    $form_fields["drstk-pid"]["input"] = "html";//can change to just displaying the pid later since it shouldn't be editable
    $form_fields["drstk-pid"]["value"] = get_post_meta($post->ID, "drstk-pid", true);
    $form_fields["drstk-pid"]["html"] = "<p>This is a read-only field</p><tr><td></td><td>".get_post_meta($post->ID, "drstk-pid", true)."</td></tr>";
  }
  if (get_post_meta($post->ID, "drstk-drs-metadata")){
    $form_fields["drstk-drs-metadata"] = array();
    $form_fields["drstk-drs-metadata"]["label"] = __("DRS Metadata");
    $form_fields["drstk-drs-metadata"]["input"] = "html";
    $form_fields["drstk-drs-metadata"]["value"] = get_post_meta($post->ID, "drstk-drs-metadata", true);
    $metadata_html = '';
    foreach (get_post_meta($post->ID, "drstk-drs-metadata", true) as $key => $value){
      $metadata_html .= "<tr><td><i>" . $key . "</i></td><td>";
      if (count($value) > 1) {
        for($x=0; $x<=count($value)-1; $x++){
          $metadata_html .= $value[$x];
          if ($x != count($value)-1){
            $metadata_html .=", ";
          }
        }
        $metadata_html .= "</td></tr>";
      } else {
        $metadata_html .= $value[0] . "</td></tr>";
      }
    }
    $form_fields["drstk-drs-metadata"]["html"] = "<p>Metadata imported from the DRS. Note this data is read only. Any changes must be made directly in the DRS.</p>".$metadata_html;
  }

  return $form_fields;
}
add_filter("attachment_fields_to_edit", "drstk_image_attachment_add_custom_fields", null, 2);

/* Save custom field value for DRS Metadata */
function drstk_image_attachment_save_custom_fields($post, $attachment) {
  $new_creator_value = ( isset( $_POST['drstk-creator'] ) ? sanitize_html_class( $_POST['drstk-creator'] ) : '' );
  $creator_value = get_post_meta( $post, 'drstk-creator', true );
  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_creator_value && '' == $creator_value )
    add_post_meta( $post, 'drstk-creator', $new_creator_value, true );
  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_creator_value && $new_creator_value != $creator_value )
    update_post_meta( $post, 'drstk-creator', $new_creator_value );
  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_creator_value && $creator_value )
    delete_post_meta( $post, 'drstk-creator', $creator_value );

  $new_date_created_value = ( isset( $_POST['drstk-date-created'] ) ? sanitize_html_class( $_POST['drstk-date-created'] ) : '' );
  $date_created_value = get_post_meta( $post, 'drstk-date-created', true );
  if ( $new_date_created_value && '' == $date_created_value )
    add_post_meta( $post, 'drstk-date-created', $new_date_created_value, true );
  elseif ( $new_date_created_value && $new_date_created_value != $date_created_value )
    update_post_meta( $post, 'drstk-date-created', $new_date_created_value );
  elseif ( '' == $new_date_created_value && $date_created_value )
    delete_post_meta( $post, 'drstk-date-created', $date_created_value );

  return $post;
}
add_filter("attachment_fields_to_save", "drstk_image_attachment_save_custom_fields", 10 , 2);


/*permalinks for attachments -> item page*/
function drstk_attachment_link( $link, $post_id ){
    $post = get_post( $post_id );
    if (get_post_meta($post->ID, "drstk-pid", true)){
      return home_url( '/item/' . get_post_meta($post->ID, "drstk-pid", true) );
    } else {
      return home_url( '/?attachment_id=' . $post->ID );
    }
}
add_filter( 'attachment_link', 'drstk_attachment_link', 20, 2 );

/*fix for weird jumpiness in wp admin menu*/
function fix_admin_head() {
	echo "<script type='text/javascript'>jQuery(window).load(function(){jQuery('#adminmenuwrap').hide().show(0);});</script>";
  echo "<style>#postimagediv{display:none !important;}";
}
add_action( 'admin_head', 'fix_admin_head' );
