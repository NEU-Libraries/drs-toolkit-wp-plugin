<?php

// from https://gist.github.com/carousel/1aacbea013d230768b3dec1a14ce5751
function camelCaseToSnakeCase($input) {
    return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $input));
}

// from https://gist.github.com/carousel/1aacbea013d230768b3dec1a14ce5751
function snakeCaseToCamelCase($input) {
    return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $input))));
}

function languageToSnakeCase($input) {
    $output = strtolower($input);
    $output = preg_replace("/( )\\1+/", "$1", $output); // remove repeating spaces
    $output = preg_replace("#[[:punct:]]#", "", $output); // do this before _ because [[:punct:]] includes _   
    $output = str_replace(' ', '_', $output);
    return $output;
}
