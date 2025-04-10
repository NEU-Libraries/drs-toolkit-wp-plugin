<?php

namespace Drstk\Util;

class WPOptionsUtilities {

    static function getAssociatedMetadataOption(): array {
        $meta_options = get_option('drstk_assoc_file_metadata');
        if ($meta_options == NULL) {
            $meta_options = array("full_title_ssi", "creator_tesim", "abstract_tesim");
        }
        return $meta_options;
    }

    static function getFacetsToDisplay(): array {
        $facet_options = get_option('drstk_facets');
        if ($facet_options == NULL) {
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

    static function getFacetName(string $facet, bool $isNiec = false): string {
        if ($isNiec) {
            $name = get_option('drstk_niec_' . $facet . '_title');
        } else {
            $name = get_option('drstk_' . $facet . '_title');
        }
        if ($name == NULL) {
            $name = titleize($facet);
        }
        return $name;
    }

    static function getLeafletApiKey(): string {
        $api_key = get_option('leaflet_api_key');
        return $api_key;
    }

    static function getLeafletProjectKey(): string {
        $project_key = get_option('leaflet_project_key');
        return $project_key;
    }
}

