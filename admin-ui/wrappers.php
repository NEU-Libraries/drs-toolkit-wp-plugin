<?php

use Drstk\AdminUI\Actions;
use Drstk\AdminUI\Filters;
use Drstk\AdminUI\Scripts;
use Drstk\AdminUI\Callbacks\Collections;

// Actions


function drstk_add_hypothesis() {
    Actions::addHypothesis();
}

// INIT ACTIONS

/* This makes it so that the tinymce wysiwyg does not process shortcodes so the database saves the shortcode before it is processed - this allows the has_shortcode function to work as expected and thus enqueue javascript correctly*/

function remove_bstw_widget_text_filters() {
    Actions::removeBstwWidgetTextFilters();
}

function register_drs_settings() {
    Actions::registerDrsSettings();
}

function drstk_collection_page_title_callback(){
    Collections::collectionPageTitle();
}

function drstk_register_browse_settings(): void {
    Actions::registerBrowseSettings();
}

function drstk_register_item_page_settings(): void {
    Actions::registerItemPageSettings();
}

function drstk_register_search_settings(): void {
    Actions::registerSearchSettings();
}

function drstk_register_project_settings(): void {
    Actions::registerProjectSettings();
}

function drstk_register_podcast_settings(): void {
    Actions::registerPodcastSettings();
}

function drstk_register_itunes_settings(): void {
    Actions::registerItunesSettings();
}

function drstk_register_googleplay_settings() {
    Actions::registerGooglePlaySettings();
}

function drstk_register_spotify_settings(): void {
    Actions::registerSpotifySettings();
}

function drstk_register_stitcher_settings(): void {
    Actions::registerStitcherSettings();
}

function drstk_register_overcast_settings(): void {
    Actions::registerOvercastSettings();
}

function drstk_register_leaflet_settings(): void {
    Actions::registerLeafletSettings();
}

function drstk_register_mirador_settings(): void {
    Actions::registerMiradorSettings();
}


function drs_admin_add_page() {
    Actions::adminAddPage();
}
function drstk_register_niec_settings(): void {
    Actions::registerNiecSettings();
}

function drstk_add_podcast_feed() {
    Actions::addPodcastFeed();
}
// Filters


function drstk_image_attachment_fields_to_save($post, $attachment) {
    return Filters::imageAttachmentFieldsToSave($post, $attachment);
}


function drstk_image_attachment_fields_to_edit($formFields, $post) {
    return Filters::imageAttachmentFieldsToEdit($formFields, $post);
}

function drstk_podcast_page_template($template) {
    return Filters::podcastPageTemplate($template);
}


function drstk_content_template($template) {
    return Filters::contentTemplate($template);
} 

/**
 * Register an additional query variable so we can differentiate between
 * the types of custom queries that are generated
 */

function drstk_add_query_var($publicQueryVars) {
    return Filters::addQueryVar($publicQueryVars);
}

// Scripts


function fix_admin_head() {
    Scripts::fixAdminHead();
}


function drstk_admin_enqueue() {
    Scripts::adminEnqueue();
}


