<?php

use Drstk\Query\Query;

function get_response(string $url): array {
    return Query::apiQuery($url);
}

