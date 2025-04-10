<?php

use Drstk\Query\Query as Query;

/**
 * 
 * Basic curl response mechanism.
 * Designed here to make it easy to output some message, even in the case of an error
 * For debugging, the fuller status info is passed along for inspection when needed
 *
 */
function get_response(string $url): array {
    return Query::apiQuery($url);
}

