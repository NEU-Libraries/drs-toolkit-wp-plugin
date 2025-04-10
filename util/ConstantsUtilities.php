<?php

namespace Drstk\Util;

class ConstantsUtilities {
    static function getErrorMessages(): array {

        $errors = array(
            "admin" => array(
                "api_fail" => "Sorry, DRS files and metadata are currently unavailable. Please refresh the page or try again later. If problem persists please contact dsg@neu.edu.",
            ),
            "search" => array(
                "no_results" => "Your query produced no results. Please refine your search and try again.",
                "fail_null" => "Sorry, these project materials are currently unavailable. Please try again later.",
                "no_sub_collections" => "This project has no sub-collections.",
                "missing_collection" => "No collections are available at this time. Please contact the site administrator.",
            ),
            "item" => array(
                "no_results" => "This file is currently unavailable. Please check the URL and try again.",
                "fail" => "Sorry, project materials are currently unavailable. Please refresh the page or try again later. If problem persists please contact the site administrator.",
                "jwplayer_fail" => "There was an issue playing this file. Please contact the site administrator.",
            ),
            "shortcodes" => array(
                "fail" => "Sorry, project materials are currently unavailable. Please refresh the page or try again later. If problem persists please contact the site administrator.",
            ),
        );
        return $errors;
    }

    static function getFacetOptions(string $facetType, ?bool $useDefault = false): array {
        switch ($facetType) {
            case 'drstk':
                $default_facet_options = array(
                    "creator_sim",
                    "creation_year_sim",
                    "subject_sim",
                    "type_sim",
                    "community_name_ssim",
                    "drs_department_ssim",
                    "drs_degree_ssim",
                    "drs_course_number_ssim",
                    "drs_course_title_ssim"
                );
    
    
                if ($useDefault) {
                    return $default_facet_options;
                }
                return get_option('drstk_facets', $default_facet_options);
    
    
                break;
    
            case 'niec':
                $default_niec_facet_options = array(
                    "niec_gender_ssim",
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
                    "niec_lends_itself_to_use_of_space_ssim"
                );
    
                if ($useDefault) {
                    return $default_niec_facet_options;
                }
                return get_option('drstk_niec_metadata', $default_niec_facet_options);
    
                break;
    
            default:
                return array();
                break;
        }
    }
}


  
