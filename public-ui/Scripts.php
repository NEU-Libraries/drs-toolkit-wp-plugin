<?php

namespace Drstk\PublicUi;



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
    wp_register_script('drstk_elevatezoom',plugins_url('/assets/js/elevatezoom/jquery.elevateZoom-3.0.8.min.js', __FILE__),
    array( 'jquery' )
);
    wp_enqueue_script('drstk_elevatezoom');
    wp_register_script('drstk_item_gallery', plugins_url('/assets/js/item_gallery.js', __FILE__), array(), $VERSION, false );
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

