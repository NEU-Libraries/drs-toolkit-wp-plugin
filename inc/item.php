<?php
/**
 * get single record from DRS
 *
 * Also includes a helper function for getting a video document or returning
 * a 404 message
 */
function get_or_create_solr_doc( $wp_query, $pid ){
    echo "WE MADE IT TO GET OR CREATE";
    global $piddoc;    // $solrdoc will be available to our theme templates

    $pid = 'neu:' . $pid;

    // try to retrieve an item based on pid
    // if we can't get one, then return a 404 and let's get out of here
    try {
        //get data from API
    } catch (Exception $e) {
        //error
        $wp_query->is_404 = true;
        return;
    }
}
