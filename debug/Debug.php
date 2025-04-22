<?php

// namespace Drstk\Debug;


function drstk_turn_off_feed_caching($feed) {
    $feed->enable_cache(false);
}

function debug_change_feed_cache_transient_lifetime($seconds) {
    return 5;
}


function drstk_dev_site_status_admin_notice() {

    //include('devMessage.php');
}

if (WP_DEBUG) {
    add_action('admin_notices', 'drstk_dev_site_status_admin_notice');
}




