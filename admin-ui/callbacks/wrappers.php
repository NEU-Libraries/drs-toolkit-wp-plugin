<?php

use Drstk\AdminUI\Callbacks\Browse as Browse;
use Drstk\AdminUI\Callbacks\Form as Form;
use Drstk\AdminUI\Callbacks\Collections as Collections;
use Drstk\AdminUI\Callbacks\Iiif as Iiif;
use Drstk\AdminUI\Callbacks\ItemPage as ItemPage;
use Drstk\AdminUI\Callbacks\Leaflet;
use Drstk\AdminUI\Callbacks\Niec;
use Drstk\AdminUI\Callbacks\Podcasts;
use Drstk\AdminUI\Callbacks\Search;



// Browse

function drstk_browse_page_title_callback(): void {
    Browse::browsePageTitle();
}

function drstk_browse_metadata_callback(): void {
    Browse::browseMetadata();
}

function drstk_default_browse_per_page_callback(): void {
    Browse::defaultBrowsePerPage();

}

function drstk_browse_show_facets_callback(): void {
    Browse::browseShowFacets();
}

function drstk_default_sort_callback(): void {
    Browse::defaultSortCallback();
}

// Form

function drstk_display_settings() {
    Form::displaySettings();
}

function drstk_home_url_callback() {
    Form::homeUrl();
}

// Collections


/*callback functions for display fields on settings page*/
function drstk_collection_callback() {
    Collections::collection();
}

function drstk_collections_page_title_callback() {
    Collections::collectionPageTitle();
}

function drstk_collection_page_callback() {
    Collections::collectionsPage();
}
 // iiif


function drstk_mirador_callback() {
    Iiif::mirador();
}

function drstk_mirador_page_title_callback() {
    Iiif::miradorPageTitle();
}

function drstk_mirador_url_callback() {
    Iiif::miradorUrl();
}

// ItemPage


function drstk_item_page_metadata_callback() {
    ItemPage::itemPageMetadata();
}

function drstk_appears_callback() {
    ItemPage::appears();
}

function drstk_appears_title_callback() {
    ItemPage::appearsTitle();
}

function drstk_assoc_callback() {
    ItemPage::associatedFiles();
}

function drstk_assoc_title_callback() {
    ItemPage::associatedTitle();
}

function drstk_assoc_file_metadata_callback() {
    ItemPage::associatedFileMetadata();
}

function drstk_annotations_callback() {
    ItemPage::annotations();
}

function drstk_item_extensions_callback() {
    ItemPage::itemExtensions();
}


function drstk_facets_callback() {
    ItemPage::facets();
}

function drstk_facet_title_callback() {
    ItemPage::facetTitle();
}

function drstk_facet_sort_callback() {
    ItemPage::facetSort();
}

// Leaflet


function leaflet_api_key_callback() {
    Leaflet::apiKey();
}

function leaflet_project_key_callback() {
    Leaflet::projectKey();
}

// Niec


function drstk_niec_callback() {
    Niec::niec();
}


function drstk_niec_metadata_callback() {
    Niec::niecMetadata();
}

function drstk_niec_metadata_title_callback() {
    Niec::niecMetadataTitle();
}

// Podcasts

function drstk_podcast_author_callback() {
    Podcasts::podcastAuthor();

}

function drstk_itunes_link_callback() {
    Podcasts::itunesLink();
}

function drstk_spotify_link_callback() {
    Podcasts::spotifyLink();
}

function drstk_googleplay_link_callback() {
    Podcasts::googleplayLink();
}

function drstk_overcast_link_callback() {
    Podcasts::overcastLink();
}

function drstk_stitcher_link_callback() {
    Podcasts::stitcherLink();
}

function drstk_podcast_image_url_callback() {
    Podcasts::podcastImageUrl();
} 


function drstk_is_podcast_callback() {
    Podcasts::isPodcast();
}

function drstk_podcast_poster_callback() {
    Podcasts::podcastPoster();

}


function drstk_podcast_page_callback() {
    Podcasts::podcastPage();
}

// Search


function drstk_search_page_title_callback() {
    Search::searchPageTitle();
}

function drstk_search_placeholder_callback() {
    Search::searchPlaceholder();
}

function drstk_search_metadata_callback() {
    Search::searchMetadata();
}

function drstk_search_related_content_title_callback() {
    Search::searchRelatedTitle();
}


function drstk_default_search_per_page_callback() {
    Search::defaultSearchPerPage();
}

function drstk_search_show_facets_callback() {
    Search::searchShowFacets();
}



