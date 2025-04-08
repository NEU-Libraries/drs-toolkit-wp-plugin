<?php

//namespace Drstk\Query;




/**
 * Basic curl response mechanism.
 * Designed here to make it easy to output some message, even in the case of an error
 * For debugging, the fuller status info is passed along for inspection when needed
 *
 * Typical usage:
 * $response = get_response($url);
 * $output = $response['output'];
 * echo $output;
 *
 * Fancier:
 * $response = get_response($url);
 * if ($response['status'] == 404) {
 *   $output = 'No soup for you!';
 * }
 * echo $output;
 */
function get_response($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FAILONERROR, false);
    $raw_response = curl_exec($ch);
    $response_status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);

    switch ($response_status) {
        case 200:
            $output = $raw_response;
            $status_message = 'OK';
            break;
        case 404:
            $output = 'The resource was not found.';
            $status_message = 'Not Found';
            break;
        case 302:
            // check if there's json in it anyway
            $json = json_decode($raw_response);
            if (is_object($json)) {
                $output = $raw_response;
            } else {
                $output = 'An unknown error occured -- ' . $response_status;
            }
            $status_message = 'The resource has moved or is no longer available';
            break;
        default:
            $output = 'An unknown error occured.' . $response_status;
            $status_message = 'An unkown error occured. Please try again';
            break;
    }
    $response = array(
        'status' => $response_status,
        'status_message' => $status_message,
        'output' => $output,
    );
    curl_close($ch);
    return $response;
}
