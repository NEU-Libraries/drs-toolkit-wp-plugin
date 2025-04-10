<?php

//namespace Drstk\Callbacks\Collections;




/*callback functions for display fields on settings page*/
function drstk_collection_callback() {
    $collection_pid = (get_option('drstk_collection') != '') ? get_option('drstk_collection') : 'https://repository.library.northeastern.edu/collections/neu:1';
    echo '<input name="drstk_collection" type="text" value="' . $collection_pid . '" style="width:100%;"></input><br/>
       <small>Ie. <a href="https://repository.library.northeastern.edu/collections/neu:6012">https://repository.library.northeastern.edu/collections/neu:6012</a></small>';

    if (WP_DEBUG) {
        $commonPidsHtml = "
        <p>Reference PIDs for dev and testing:</p>
        <ul>
            <li>ETD: https://repository.library.northeastern.edu/sets/neu:cj82r3884</li>
            <li>What's New: https://repository.library.northeastern.edu/collections/neu:cj82q862v</li>
            <li>Class podcasts: https://repository.library.northeastern.edu/collections/neu:f1881z41n</li>
            <li>LGBTAQ+ : https://repository.library.northeastern.edu/sets/neu:f1882114b</li>
        </ul>
        ";
        echo $commonPidsHtml;
    }
}

function drstk_collections_page_title_callback() {
    echo '<input type="text" name="drstk_collections_page_title" value="';
    if (get_option('drstk_collections_page_title') == '') {
        echo 'Browse Collections';
    } else {
        echo get_option('drstk_collections_page_title');
    }
    echo '" />';
}

function drstk_collection_page_title_callback() {
    echo '<input type="text" name="drstk_collection_page_title" value="';
    if (get_option('drstk_collection_page_title') == '') {
        echo 'Collection';
    } else {
        echo get_option('drstk_collection_page_title');
    }
    echo '" />';
}





