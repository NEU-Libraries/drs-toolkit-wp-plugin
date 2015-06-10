<?php
/**
 * Plugin Name: DRS Toolkit Plugin
 * Plugin URI:
 * Version: 0.1
 * Author: Eli Zoller
 * Description: This plugin provides the core functionality of the DRS Project Toolkit and brings the content of a project from the DRS into Wordpress using the DRS API.
 */

require_once( plugin_dir_path( __FILE__ ) . 'inc/item.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/import.php' );
require_once( plugin_dir_path( __FILE__ ) . 'inc/browse.php' );

define( 'ALLOW_UNFILTERED_UPLOADS', true ); //this will allow files without extensions - aka from fedora

$VERSION = '0.1.0';
$SITE_URL = site_url();

// Set template names here so we don't have to go into the code.
$TEMPLATE = array(
    //'custom_template' => 'nusolr-template.php', //this attaches the plugin to the separate theme template
    'browse_template' => dirname(__FILE__) . '/templates/browse.php',
    'item_template' => dirname(__FILE__) . '/templates/item.php',
);

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
     add_rewrite_rule('^item/([^/]*)/?', 'index.php?post_type=drs&drstk_template_type=item&pid=$matches[1]', 'top');
     add_rewrite_rule('^collections/?$', 'index.php?post_type=drs&drstk_template_type=collections', 'top');
     add_rewrite_rule('^collection/([^/]*)/?', 'index.php?post_type=drs&drstk_template_type=collection&pid=$matches[1]', 'top');
 }

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
        $collection_pid = get_option('drstk_collection');
     }
  }

//this creates the form for entering the pid on the settings page
 function drstk_display_settings() {

     $collection_pid = (get_option('drstk_collection') != '') ? get_option('drstk_collection') : 'neu:1';
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
     </tbody>
     </table>
      <input type="hidden" name="action" value="update" />

      <input type="hidden" name="page_options" value="drstk_collection, drstk_sync_images" />

      <input type="submit" name="Submit" value="Update" /></form></div>
      <br/>
      <table>
      <tr>
      <td><a class="button" id="drstk-import" href="#">Import Items from the DRS</a></td>
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
      //this allows an ajax call from import.js
      wp_localize_script( 'drstk_import', 'import_obj', array(
         'ajax_url' => admin_url( 'admin-ajax.php' ),
         'nonce'    => $import_nonce,
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
            return $TEMPLATE['browse_template'];
        }

        if ($template_type == 'item') {
            global $item_pid;
            $item_pid = get_query_var( 'pid' );
            add_action('wp_enqueue_scripts', 'drstk_item_script');
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
    global $SITE_URL;
    global $sub_collection_pid;
    //this enqueues the JS file
    wp_enqueue_script( 'drstk_browse',
        plugins_url( '/assets/js/browse.js', __FILE__ ),
        array( 'jquery' )
    );
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
    wp_enqueue_script( 'drstk_item',
        plugins_url( '/assets/js/item.js', __FILE__ ),
        array( 'jquery' )
    );
    //wp_enqueue_style( 'drstk_item_style', plugins_url('/assets/css/item.css', __FILE__));
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

/* Add custom field to attachment for DRS Metadata */
function drstk_image_attachment_add_custom_fields($form_fields, $post) {
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

  $form_fields["drstk-pid"] = array();
  $form_fields["drstk-pid"]["label"] = __("CoreFile Pid");
  $form_fields["drstk-pid"]["input"] = "html";//can change to just displaying the pid later since it shouldn't be editable
  $form_fields["drstk-pid"]["value"] = get_post_meta($post->ID, "drstk-pid", true);
  $form_fields["drstk-pid"]["html"] = "<p>This is a read-only field</p><tr><td></td><td>".get_post_meta($post->ID, "drstk-pid", true)."</td></tr>";

  return $form_fields;
}
add_filter("attachment_fields_to_edit", "drstk_image_attachment_add_custom_fields", null, 2);

/* Save custom field value for DRS Metadata */
function drstk_image_attachment_save_custom_fields($post, $attachment) {
  if(isset($attachment['drstk-drs-metadata'])) {
    update_post_meta($post['ID'], 'drstk-drs-metadata', $attachment['drstk-drs-metadata']);
  } else {
    delete_post_meta($post['ID'], 'drstk-drs-metadata');
  }
  if(isset($attachment['drstk-creator'])) {
    update_post_meta($post['ID'], 'drstk-creator', $attachment['drstk-creator']);
  } else {
    delete_post_meta($post['ID'], 'drstk-creator');
  }
  if(isset($attachment['drstk-date-created'])) {
    update_post_meta($post['ID'], 'drstk-date-created', $attachment['drstk-date-created']);
  } else {
    delete_post_meta($post['ID'], 'drstk-date-created');
  }
  //can remove this later because it won't be editable
  if(isset($attachment['drstk-pid'])) {
    update_post_meta($post['ID'], 'drstk-pid', $attachment['drstk-pid']);
  } else {
    delete_post_meta($post['ID'], 'drstk-pid');
  }
  //end remove
  return $post;
}
add_filter("attachment_fields_to_save", "drstk_image_attachment_save_custom_fields", null , 2);


/*permalinks for attachments -> item page*/
function drstk_attachment_link( $link, $post_id ){
    $post = get_post( $post_id );
    return home_url( '/item/' . get_post_meta($post->ID, "drstk-pid", true) );
}
add_filter( 'attachment_link', 'drstk_attachment_link', 20, 2 );
