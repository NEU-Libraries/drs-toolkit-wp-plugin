<?php

use Drstk\Util\AdminConfig;
use Drstk\Util\ConstantsUtilities;
use Drstk\Util\StringUtilities as DrstkStringUtil;
use Drstk\Util\WPOptionsUtilities;

// AdminConfig
function create_post_type() {
    AdminConfig::createPostType();
}


/**
 * Basic validation and standardization of the $url_base entry;
 * should return a safe string that ends with a forward slash
 * (and does not begin with one).
 */
function drstk_home_url_validation($input) {
    return AdminConfig::homeUrlValidation($input);
}

// ConstantsUtilities

function drstk_get_errors() {
    return ConstantsUtilities::getErrorMessages();
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

function drstk_facets_get_option($facet_type, $default = false) {
    return ConstantsUtilities::getFacetOptions($facet_type, $default);
}
 

// StringUtilities

function drstk_api_url($source, $pid, $action, ?string $sub_action = NULL, ?array $url_arguments = NULL) {
    return DrstkStringUtil::buildApiUrl($source, $pid, $action, $sub_action = NULL, $url_arguments = NULL);
}

function drstk_get_pid() {
    return DrstkStringUtil::parsePid();
}

function titleize($string) {
    return DrstkStringUtil::titleize($string);
}

function drstk_home_url($path = '', $scheme = null) { 
    return DrstkStringUtil::buildHomeUrl($path, $scheme);
}

function drstk_get_repo_from_pid($pid) {
    return DrstkStringUtil::parseRepoFromPid($pid);
}

// WPOptionsUtilities

function drstk_get_assoc_meta_options() {
    return WPOptionsUtilities::getAssociatedMetadataOption();
}

function drstk_get_facets_to_display() {
    return WPOptionsUtilities::getFacetsToDisplay();
}

function drstk_get_facet_name($facet, $niec = false) {
    return WPOptionsUtilities::getFacetName($facet, $niec);
}

function drstk_get_map_api_key() {
    return WPOptionsUtilities::getLeafletApiKey();
}

function drstk_get_map_project_key() {
    return WPOptionsUtilities::getLeafletProjectKey();
}



