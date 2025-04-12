<?php

namespace Drstk\AdminUI;

class Actions {

    static function addHypothesis(): void {
        global $wp_query;
        $annotations = get_option('drstk_annotations');
        if (isset($wp_query->query_vars['drstk_template_type'])) {
            $template_type = $wp_query->query_vars['drstk_template_type'];
        } else {
            $template_type = "";
        }
        if (is_single() && !is_front_page() && !is_home() && $annotations == "on"):
            wp_enqueue_script('hypothesis', '//hypothes.is/embed.js', '', false, true);
        elseif (is_page() && !is_front_page() && !is_home() && $annotations == "on"):
            wp_enqueue_script('hypothesis', '//hypothes.is/embed.js', '', false, true);
        elseif ($template_type == 'item' && $annotations == "on"):
            wp_enqueue_script('hypothesis', '//hypothes.is/embed.js', '', false, true);
        endif;
    }

    static function addPodcastFeed(): void {
        add_feed('podcasts', 'drstk_render_podcast_feed');
    }

    static function removeBstwWidgetTextFilters(): void {
        if (function_exists('bstw')) {
            remove_filter('widget_text', array(bstw()->text_filters(), 'do_shortcode'), 10);
        }
    }

    static function registerDrsSettings(): void {

        drstk_register_project_settings();
        drstk_register_search_settings();
        drstk_register_item_page_settings();
        drstk_register_browse_settings();
        drstk_register_podcast_settings();
        drstk_register_itunes_settings();
        drstk_register_googleplay_settings();
        drstk_register_spotify_settings();
        drstk_register_stitcher_settings();
        drstk_register_overcast_settings();
        drstk_register_niec_settings();
        drstk_register_mirador_settings();
        add_settings_section('drstk_advanced', "Advanced", null, 'drstk_options');
    }

    static function registerBrowseSettings(): void {
        add_settings_section('drstk_browse_settings', 'Browse', null, 'drstk_options');
        add_settings_field('drstk_browse_page_title', 'Browse Page Title', 'drstk_browse_page_title_callback', 'drstk_options', 'drstk_browse_settings');
        register_setting('drstk_options', 'drstk_browse_page_title');
        add_settings_field('drstk_browse_metadata', 'Metadata to Display', 'drstk_browse_metadata_callback', 'drstk_options', 'drstk_browse_settings');
        register_setting('drstk_options', 'drstk_browse_metadata');
        add_settings_field('drstk_default_sort', 'Default Sort', 'drstk_default_sort_callback', 'drstk_options', 'drstk_browse_settings');
        register_setting('drstk_options', 'drstk_default_sort');
        add_settings_field('drstk_default_browse_per_page', 'Default Per Page', 'drstk_default_browse_per_page_callback', 'drstk_options', 'drstk_browse_settings');
        register_setting('drstk_options', 'drstk_default_browse_per_page');
        add_settings_field('drstk_browse_show_facets', 'Show Facets', 'drstk_browse_show_facets_callback', 'drstk_options', 'drstk_browse_settings');
        register_setting('drstk_options', 'drstk_browse_show_facets');
    
        add_settings_section('drstk_facet_settings', 'Facets', null, 'drstk_options');
        add_settings_field('drstk_facets', 'Facets to Display<br/><small>Select which facets you would like to display on the search and browse pages. Once selected, you may enter custom names for these facets. Drag and drop the order of the facets to change the order of display.</small>', 'drstk_facets_callback', 'drstk_options', 'drstk_facet_settings');
        register_setting('drstk_options', 'drstk_facets');
    
        $facet_options = drstk_facets_get_option('drstk', true);
        foreach ($facet_options as $option) {
            add_settings_field('drstk_' . $option . '_title', null, 'drstk_facet_title_callback', 'drstk_options', 'drstk_facet_settings', array('class' => 'hidden'));
            register_setting('drstk_options', 'drstk_' . $option . '_title');
        }
        add_settings_field('drstk_facet_sort_order', 'Default Facet Sort', 'drstk_facet_sort_callback', 'drstk_options', 'drstk_facet_settings');
        register_setting('drstk_options', 'drstk_facet_sort_order');
    
        add_settings_section('drstk_collections_settings', 'Collections Browse Page', null, 'drstk_options');
        add_settings_field('drstk_collections_page_title', 'Collections Browse Title', 'drstk_collections_page_title_callback', 'drstk_options', 'drstk_collections_settings');
        register_setting('drstk_options', 'drstk_collections_page_title');
    
        add_settings_section('drstk_collection_settings', 'Collection Page', null, 'drstk_options');
        add_settings_field('drstk_collection_page_title', 'Collection Page Title', 'drstk_collection_page_title_callback', 'drstk_options', 'drstk_collection_settings');
        register_setting('drstk_options', 'drstk_collection_page_title');
    
    }

    static function registerItemPageSettings(): void {
        add_settings_section('drstk_single_settings', 'Single Item Page', null, 'drstk_options');
        add_settings_field('drstk_item_page_metadata', 'Metadata to Display<br/><small>If none are selected, all metadata will display in the default order. To reorder or limit the fields which display, select the desired fields and drag and drop to reorder. To add custom fields, click the add button and type in the label.</small>', 'drstk_item_page_metadata_callback', 'drstk_options', 'drstk_single_settings');
        register_setting('drstk_options', 'drstk_item_page_metadata');
        add_settings_field('drstk_appears', 'Display Item Appears In', 'drstk_appears_callback', 'drstk_options', 'drstk_single_settings');
        register_setting('drstk_options', 'drstk_appears');
        add_settings_field('drstk_appears_title', 'Item Appears In Block Title', 'drstk_appears_title_callback', 'drstk_options', 'drstk_single_settings', array('class' => 'appears'));
        register_setting('drstk_options', 'drstk_appears_title');
        add_settings_field('drstk_assoc', 'Display Associated Files', 'drstk_assoc_callback', 'drstk_options', 'drstk_single_settings');
        register_setting('drstk_options', 'drstk_assoc');
        add_settings_field('drstk_assoc_title', 'Associated Files Block Title', 'drstk_assoc_title_callback', 'drstk_options', 'drstk_single_settings', array('class' => 'assoc'));
        register_setting('drstk_options', 'drstk_assoc_title');
        add_settings_field('drstk_assoc_file_metadata', 'Metadata to Display', 'drstk_assoc_file_metadata_callback', 'drstk_options', 'drstk_single_settings', array('class' => 'assoc'));
        register_setting('drstk_options', 'drstk_assoc_file_metadata');
        add_settings_field('drstk_annotations', 'Display Annotations', 'drstk_annotations_callback', 'drstk_options', 'drstk_single_settings');
        register_setting('drstk_options', 'drstk_annotations');
        add_settings_field('drstk_item_extensions', 'Enable Item Page Custom Text', 'drstk_item_extensions_callback', 'drstk_options', 'drstk_single_settings');
        register_setting('drstk_options', 'drstk_item_extensions');
    }

    static function registerSearchSettings(): void {
        add_settings_section('drstk_search_settings', 'Search', null, 'drstk_options');
        add_settings_field('drstk_search_page_title', 'Search Page Title', 'drstk_search_page_title_callback', 'drstk_options', 'drstk_search_settings');
        register_setting('drstk_options', 'drstk_search_page_title');
        add_settings_field('drstk_search_placeholder', 'Search Box Placeholder Text', 'drstk_search_placeholder_callback', 'drstk_options', 'drstk_search_settings');
        register_setting('drstk_options', 'drstk_search_placeholder');
        add_settings_field('drstk_search_metadata', 'Metadata to Display', 'drstk_search_metadata_callback', 'drstk_options', 'drstk_search_settings');
        register_setting('drstk_options', 'drstk_search_metadata');
        add_settings_field('drstk_search_related_content_title', 'Related Content Title', 'drstk_search_related_content_title_callback', 'drstk_options', 'drstk_search_settings');
        register_setting('drstk_options', 'drstk_search_related_content_title');
        add_settings_field('drstk_default_search_per_page', 'Default Per Page', 'drstk_default_search_per_page_callback', 'drstk_options', 'drstk_search_settings');
        register_setting('drstk_options', 'drstk_default_search_per_page');
        add_settings_field('drstk_search_show_facets', 'Show Facets', 'drstk_search_show_facets_callback', 'drstk_options', 'drstk_search_settings');
        register_setting('drstk_options', 'drstk_search_show_facets');
    }

    static function registerProjectSettings(): void {
        add_settings_section('drstk_project', "Project", null, 'drstk_options');
        add_settings_field('drstk_collection', 'Project Collection or Set URL', 'drstk_collection_callback', 'drstk_options', 'drstk_project');
        register_setting('drstk_options', 'drstk_collection');
        add_settings_field('drstk_home_url', 'Permalink/URL Base', 'drstk_home_url_callback', 'drstk_options', 'drstk_project');
        register_setting('drstk_options', 'drstk_home_url', 'drstk_home_url_validation');
    }

    static function registerPodaseSettings(): void {
        add_settings_field(
            'drstk_is_podcast',
            'Is this a podcast site?',
            'drstk_is_podcast_callback',
            'drstk_options',
            'drstk_advanced'
        );
        register_setting('drstk_options', 'drstk_is_podcast');
    
        add_settings_field(
            'drstk_podcast_poster',
            'Show an image for each podcast episode?',
            'drstk_podcast_poster_callback',
            'drstk_options',
            'drstk_advanced'
        );
        register_setting('drstk_options', 'drstk_podcast_poster');
    
        add_settings_field(
            'drstk_podcast_page',
            'Select page to contain your podcast list',
            'drstk_podcast_page_callback',
            'drstk_options',
            'drstk_advanced',
            array('class' => 'drstk_podcast_options')
        );
        register_setting('drstk_options', 'drstk_podcast_page');
    
    
        add_settings_field(
            'drstk_podcast_author',
            'Name to use as the podcast author (usually the instructor of record)',
            'drstk_podcast_author_callback',
            'drstk_options',
            'drstk_advanced',
            array('class' => 'drstk_podcast_options')
        );
        register_setting('drstk_options', 'drstk_podcast_author');
    
        add_settings_field(
            'drstk_podcast_image_url',
            'Image for podcast feed',
            'drstk_podcast_image_url_callback',
            'drstk_options',
            'drstk_advanced',
            array('class' => 'drstk_podcast_options')
        );
        register_setting('drstk_options', 'drstk_podcast_image_url');        
    }

    static function registerItunesSettings(): void {
            
        add_settings_field(
            'drstk_itunes_link',
            'Link to iTunes',
            'drstk_itunes_link_callback',
            'drstk_options',
            'drstk_advanced',
            array('class' => 'drstk_podcast_options')
        );
        register_setting('drstk_options', 'drstk_itunes_link');
    }

    static function registerSpotifySettings(): void {
        add_settings_field(
            'drstk_spotify_link',
            'Link to Spotify',
            'drstk_spotify_link_callback',
            'drstk_options',
            'drstk_advanced',
            array('class' => 'drstk_podcast_options')
        );
        register_setting('drstk_options', 'drstk_spotify_link');
    }

    static function registerGooglePlaySettings(): void {

        add_settings_field(
            'drstk_googleplay_link',
            'Link to Google Play',
            'drstk_googleplay_link_callback',
            'drstk_options',
            'drstk_advanced',
            array('class' => 'drstk_podcast_options')
        );
        register_setting('drstk_options', 'drstk_googleplay_link');

    }


    static function registerStitcherSettings(): void {
        add_settings_field(
            'drstk_stitcher_link',
            'Link to Stitcher',
            'drstk_stitcher_link_callback',
            'drstk_options',
            'drstk_advanced',
            array('class' => 'drstk_podcast_options')
        );
        register_setting('drstk_options', 'drstk_stitcher_link');
    }

    static function registerOvercastSettings(): void {
        add_settings_field(
            'drstk_overcast_link',
            'Link to Overcast',
            'drstk_overcast_link_callback',
            'drstk_options',
            'drstk_advanced',
            array('class' => 'drstk_podcast_options')
        );
        register_setting('drstk_options', 'drstk_overcast_link');
    }


    static function registerLeafletSettings(): void {
        add_settings_field(
            'leaflet_api_key',
            'Leaflet API Key',
            'leaflet_api_key_callback',
            'drstk_options',
            'drstk_advanced'
        );
        register_setting('drstk_options', 'leaflet_api_key');
    
        add_settings_field(
            'leaflet_project_key',
            'Leaflet Project Key',
            'leaflet_project_key_callback',
            'drstk_options',
            'drstk_advanced'
        );
        register_setting('drstk_options', 'leaflet_project_key');
    }

    static function registerMiradorSettings(): void {

        add_settings_field(
            'drstk_assoc',
            'Allow Mirador Page Viewer<br/><small>This requires a manifest file and modifications to a javascript file. Please contact the Toolkit team if you would like to enable this feature.</small>',
            'drstk_mirador_callback',
            'drstk_options',
            'drstk_advanced'
        );
        register_setting('drstk_options', 'drstk_mirador');

        add_settings_field(
            'drstk_mirador_page_title',
            'Mirador Page Title',
            'drstk_mirador_page_title_callback',
            'drstk_options',
            'drstk_advanced',
            array('class' => 'mirador')
        );
        register_setting('drstk_options', 'drstk_mirador_page_title');

        add_settings_field(
            'drstk_mirador_url',
            'Mirador URL',
            'drstk_mirador_url_callback',
            'drstk_options',
            'drstk_advanced',
            array('class' => 'mirador')
        );
        register_setting('drstk_options', 'drstk_mirador_url');
    }

    static function adminAddPage(): void {
        $hook = add_options_page(
            'Settings for CERES: Exhibit Toolkit Plugin',
            'CERES: Exhibit Toolkit',
            'manage_options',
            'drstk_admin_menu',
            'drstk_display_settings'
        );
        add_action('load-' . $hook, 'drstk_plugin_settings_save');
    }

    static function registerNiecSettings(): void {
        add_settings_field(
            'drstk_niec',
            'Does your project include NIEC metadata?',
            'drstk_niec_callback',
            'drstk_options',
            'drstk_advanced'
        );
        register_setting('drstk_options', 'drstk_niec');
    
        add_settings_field(
            'drstk_niec_metadata',
            'NIEC Facets and Metadata to Display',
            'drstk_niec_metadata_callback',
            'drstk_options',
            'drstk_advanced',
            array('class' => 'niec')
        );
        register_setting('drstk_options', 'drstk_niec_metadata');
    
        $niec_facet_options = drstk_facets_get_option('niec', true);
        foreach ($niec_facet_options as $option) {
            add_settings_field(
                'drstk_niec_' . $option . '_title',
                null,
                'drstk_niec_metadata_title_callback',
                'drstk_options',
                'drstk_advanced',
                array('class' => 'hidden')
            );
            register_setting('drstk_options', 'drstk_niec_' . $option . '_title');
        }        
    }
}

