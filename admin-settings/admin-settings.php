<?php

//This function creates the settings page for entering the pid
add_action('admin_menu', 'drs_admin_add_page');
function drs_admin_add_page() {
  $hook = add_options_page('Settings for CERES: Exhibit Toolkit Plugin', 'CERES: Exhibit Toolkit', 'manage_options', 'drstk_admin_menu', 'drstk_display_settings');
  add_action('load-'.$hook,'drstk_plugin_settings_save');
}

//This registers the settings
function register_drs_settings() {
  
  // roll through requiring all the files in /admin-settings
  // have to list manually to control the order
  $settings_files = array(
      'project-settings.php',
      'search-settings.php',
      'browse-settings.php',
      'facets-settings.php',
      'collections-settings.php',
      'single-item-settings.php',
      'podcast-settings.php',
      'mirador-settings.php'
  );
  
  foreach ($settings_files as $file) {
    require_once($file);   
  }
  //Advanced Options
  add_settings_section('drstk_advanced', "Advanced", null, 'drstk_options');
}

add_action( 'admin_init', 'register_drs_settings' );

function drstk_default_sort_callback() {
  $sort_options = array("title_ssi%20asc"=>"Title A-Z","title_ssi%20desc"=>"Title Z-A", "score+desc%2C+system_create_dtsi+desc"=>"Relevance", "creator_ssi%20asc"=>"Creator A-Z","creator_ssi%20desc"=>"Creator Z-A","system_modified_dtsi%20asc"=>"Date (earliest to latest)","system_modified_dtsi%20desc"=>"Date (latest to earliest)");
  $default_sort = get_option('drstk_default_sort');
  echo '<select name="drstk_default_sort">';
  foreach($sort_options as $val=>$option){
    echo '<option value="'.$val.'"';
    if ($default_sort == $val){ echo 'selected="true"';}
    echo '/> '.$option.'</option>';
  }
  echo '</select><br/>';
}

//this creates the form for the drstk settings page
function drstk_display_settings() {
  // I (PMJ) always prefer to build up all the HTML, but WP's settings_ stuff doesn't allow that. :(
  echo "
    <div class='wrap'>
      <h1>CERES Settings</h1>
      <form method='post' action='options.php' name='options'>";
            settings_fields('drstk_options');
            do_settings_sections('drstk_options');
            submit_button();
  echo "</form>
  	</div>
  ";
}

function drstk_plugin_settings_save() {
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
          DRS_PLUGIN_URL . '/assets/js/item_meta_helper.js',
          array('jquery'));
      wp_enqueue_script( 'drstk_meta_helper_js');
    }
}

add_action('admin_enqueue_scripts', 'drstk_admin_enqueue');

function drstk_dev_site_status_admin_notice() {
  include(DRS_PLUGIN_PATH . '/devMessage.php');
}

if(WP_DEBUG) {
  add_action( 'admin_notices', 'drstk_dev_site_status_admin_notice' );
}
