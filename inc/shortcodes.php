<?php
//allows modals in admin

function add_drs_button() {
    // Function to add a button in the media editor for DRS Toolkit Shortcodes
    echo '<a href="#" id="drs-backbone_modal" class="button" title="Add Toolkit Shortcodes">Add Toolkit Shortcodes</a>';
}

add_action('media_buttons', 'add_drs_button', 1000);

// Enques extra JS
function drstk_enqueue_page_scripts( $hook ) {
    // Enqueues necessary scripts and styles for the DRS Toolkit in the WordPress admin
    $errors = drstk_get_errors();
    wp_enqueue_style( 'drstk_admin_js', DRS_PLUGIN_URL . '/assets/css/admin.css' );
    if ($hook == 'post.php' || $hook == 'post-new.php') {
        // Include modal template and enqueue necessary scripts when editing or creating a post/page
        include DRS_PLUGIN_PATH . 'templates/modal.php';
        drstk_enqueue_scripts();
    }
 }

add_action('admin_enqueue_scripts', 'drstk_enqueue_page_scripts');
add_action('wp_ajax_get_drs_code', 'drstk_get_drs_items'); // For authenticated users
add_action('wp_ajax_get_dpla_code', 'drstk_get_dpla_items');
add_action('wp_ajax_get_custom_meta', 'drstk_get_custom_meta');
add_action('wp_ajax_get_post_meta', 'drstk_get_post_meta');

function drstk_enqueue_scripts() {
    // Enqueues necessary scripts and localizes data for JavaScript
    wp_enqueue_script('drstk_admin_js', DRS_PLUGIN_URL . '/assets/js/admin.js', array(
        'jquery',
        'jquery-ui-core',
        'backbone',
        'underscore',
        'wp-util',
        'jquery-ui-sortable'
    ));

    $localized_data = array(
        'replace_message' => __('Choose a method of embedding DRS and/or DPLA item(s).<br/><br/><table><tr><td><a class="button" href="#one">Single Item</a></td><td><a class="button" href="#four">Media Playlist</a></td></tr><tr><td><a class="button" href="#two">Tile Gallery</a></td><td><a class="button" href="#five">Map</a></td></tr><tr><td><a class="button" href="#three">Gallery Slider</a></td><td><a class="button" href="#six">Timeline</a></td></tr></table>', 'backbone_modal'),
        'collection_id' => drstk_get_pid(),
        'ajax_url' => admin_url('admin-ajax.php'),
        'item_admin_nonce' => wp_create_nonce('item_admin_nonce'),
        'drs_ajax_nonce' => wp_create_nonce('drs_ajax_nonce'),
        'dpla_ajax_nonce' => wp_create_nonce('dpla_ajax_nonce'),
        'drstk_jquery_ui' => 'https://code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css',
    );

    wp_localize_script('drstk_admin_js', 'drstk_backbone_modal_l10n', $localized_data);
}

function drstk_get_drs_items() {
    // Handles AJAX request to retrieve DRS items
    try {
        check_ajax_referer('drs_ajax_nonce');

        $col_pid = drstk_get_pid();
        $url = build_drs_url();

        $response = get_response($url);
        handle_response($response);
    } catch (Exception $e) {
        // Handle exceptions and return error message
        wp_send_json(json_encode("Error: " . $e->getMessage()));
        wp_die();
    }
}

function build_drs_url() {
    // Builds the DRS API URL based on selected filters
    $col_pid = drstk_get_pid();
    $url = "";

    $filters = array(
        'spatialfilter' => 'geo',
        'avfilter' => 'av',
        'timefilter' => 'date',
    );

    // Iterates through all the parameters of POST Request
    foreach ($filters as $filter => $value) {
        if (isset($_POST['params'][$filter])) {
            $url = drstk_api_url("drs", $col_pid, "search", $value, "per_page=20");
        }
    }

    return empty($url) ? drstk_api_url("drs", $col_pid, "search", null, "per_page=20") : $url;
}

function handle_response($response) {
    // Handles the response from the DRS API
    $jsonString = $response['output'];

    if ($response['status'] != 200) {
        // Throw an exception for non-200 responses
        throw new Exception("There was an error: " . $response['output']);
    }

    wp_send_json($jsonString);
    wp_die();
}

function drstk_get_dpla_items() {
    // Handles AJAX request to retrieve DPLA items
    try {
        check_ajax_referer('dpla_ajax_nonce');
        $url = build_dpla_url();
        $response = get_response($url);
        handle_response($response);
    } catch (Exception $e) {
        // Handle exceptions and return error message
        wp_send_json(json_encode("Error: " . $e->getMessage()));
        wp_die();
    }
}

function build_dpla_url() {
    // Builds the DPLA API URL based on selected filters
    $url = isset($_POST['params']['pid'])
        ? drstk_api_url("dpla", $_POST['params']['pid'], "items", NULL, "page_size=20")
        : drstk_api_url("dpla", "", "items", NULL, "page_size=20");

    $filters = array(
        'q' => 'q',
        'spatialfilter' => 'sourceResource.spatial=**',
        'timefilter' => 'sourceResource.date.displayDate=*',
    );

    foreach ($filters as $filter => $value) {
        if (isset($_POST['params'][$filter])) {
            $url .= '&' . $value . '=' . urlencode(sanitize_text_field($_POST['params'][$filter]));
        }
    }

    return $url . "&facets=sourceResource.contributor,sourceResource.date.begin,sourceResource.date.end,sourceResource.subject.name,sourceResource.type";
}

function drstk_get_custom_meta(){
    // Handles AJAX request to retrieve custom metadata
    try {
        check_ajax_referer('item_admin_nonce');
        $id = $_POST['pid'];
        $data = get_post_custom($id);
        wp_send_json($data);
        wp_die();
    } catch (Exception $e) {
        wp_send_json(json_encode("Error: " . $e->getMessage()));
        wp_die();
    }
}

function drstk_get_post_meta(){
    // Handles AJAX request to retrieve post metadata
    try {
        check_ajax_referer('item_admin_nonce');
        $id = $_POST['pid'];
        $data = get_post($id);
        wp_send_json($data);
        wp_die();
    } catch (Exception $e) {
        wp_send_json(json_encode("Error: " . $e->getMessage()));
        wp_die();
    }
}
