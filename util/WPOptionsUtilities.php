<?php

function drstk_get_assoc_meta_options(){
    $meta_options = get_option('drstk_assoc_file_metadata');
    if ($meta_options == NULL){
      $meta_options = array("full_title_ssi","creator_tesim","abstract_tesim");
    }
    return $meta_options;
}
  
function drstk_get_facets_to_display(){
    $facet_options = get_option('drstk_facets');
    if ($facet_options == NULL){
      if (get_option('drstk_niec_metadata') == NULL) {
        // this is a little weird, but it helps make the list of default facets more consistent -- PMJ
        // @TODO what happens here could probably be folded in to drstk_facets_get_option() eventually
        $facet_options = drstk_facets_get_option('drstk', true);
      } else {
        $facet_options = array();
      }
    }
    return $facet_options;
  }
  
function drstk_get_facet_name($facet, $niec=false){
    if ($niec){
      $name = get_option('drstk_niec_'.$facet.'_title');
    } else {
      $name = get_option('drstk_'.$facet.'_title');
    }
    if ($name == NULL){
      $name = titleize($facet);
    }
    return $name;
}
  
function drstk_get_map_api_key(){
    $api_key = get_option('leaflet_api_key');
    return $api_key;
}
  
function drstk_get_map_project_key(){
    $project_key = get_option('leaflet_project_key');
    return $project_key;
}

/**
 * Wrapper around get_option('drstk_facets') to return a consistent default array
 * without resorting to a global variable
 * @TODO see if this can do the work in drstk_get_facets_to_display
 * @TODO check if we really do need to strip out defaults in some cases
 *
 * @param string $facet_type drstk or niec
 * @param boolean $default true to return the defaults, false (default) to return the data from options table
 * @return array
 */

function drstk_facets_get_option($facet_type, $default = false)
{
   switch ($facet_type) {
     case 'drstk':
       $default_facet_options = array("creator_sim",
                                      "creation_year_sim",
                                      "subject_sim",
                                      "type_sim",
                                      "community_name_ssim",
                                      "drs_department_ssim",
                                      "drs_degree_ssim",
                                      "drs_course_number_ssim",
                                      "drs_course_title_ssim");
 
 
       if ($default) {
         return $default_facet_options;
       }
       return get_option('drstk_facets', $default_facet_options);
 
 
       break;
 
     case 'niec':
       $default_niec_facet_options = array("niec_gender_ssim",
                                           "niec_age_ssim",
                                           "niec_race_ssim",
                                           "niec_sign_pace_ssim",
                                           "niec_fingerspelling_extent_ssim",
                                           "niec_fingerspelling_pace_ssim",
                                           "niec_numbers_pace_ssim",
                                           "niec_numbers_extent_ssim",
                                           "niec_classifiers_extent_ssim",
                                           "niec_use_of_space_extent_ssim",
                                           "niec_how_space_used_ssim",
                                           "niec_text_type_ssim",
                                           "niec_register_ssim",
                                           "niec_conversation_type_ssim",
                                           "niec_audience_ssim",
                                           "niec_signed_language_ssim",
                                           "niec_spoken_language_ssim",
                                           "niec_lends_itself_to_classifiers_ssim",
                                           "niec_lends_itself_to_use_of_space_ssim");
 
       if ($default) {
         return $default_niec_facet_options;
       }
       return get_option('drstk_niec_metadata', $default_niec_facet_options);
 
       break;
 
     default:
       return array();
       break;
   }
}
