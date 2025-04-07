<?php

namespace Drstk\AdminUI;

function drstk_image_attachment_fields_to_save($post, $attachment) {
    if( isset($attachment['timeline_date']) ){
      update_post_meta($post['ID'], '_timeline_date', $attachment['timeline_date']);
    }
    if( isset($attachment['map_coords']) ){
      update_post_meta($post['ID'], '_map_coords', $attachment['map_coords']);
    }
    return $post;
}

function drstk_podcast_page_template( $template ) {
    if (get_option('drstk_is_podcast') != 'on') {
      return $template;
    }
    //is_page takes the id, so this is set in the CERES settings for a podcast site.
    $podcast_page = get_option('drstk_podcast_page');
    if ( is_page( $podcast_page ) ) {
      $file_name = 'podcast-template.php';
      if ( locate_template( $file_name ) ) {
        $template = locate_template( $file_name );
      } else {
        // Template not found in theme's folder, use plugin's template as a fallback
        $template = dirname( __FILE__ ) . '/templates/' . $file_name;
      }
    }
  
    return $template;
  }
  

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
  * Register an additional query variable so we can differentiate between
  * the types of custom queries that are generated
  */
  
function drstk_add_query_var($public_query_vars){
      $public_query_vars[] = 'drstk_template_type';
      $public_query_vars[] = 'pid';
      $public_query_vars[] = 'js';
      return $public_query_vars;
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