<?php

namespace Drstk\Callback\Podcasts;

function drstk_podcast_author_callback() {
    $author = get_option('drstk_podcast_author');
    echo "<input name='drstk_podcast_author' type='text'
                 class = 'drstk_podcast_options'
                 value='$author' class='drstk_podcast_author_setting'>
          </input><br/>";
}

function drstk_itunes_link_callback() {
    $link = get_option('drstk_itunes_link');
    echo "<input name='drstk_itunes_link' type='text'
                 class = 'drstk_podcast_options'
                 value='$link' class='drstk_podcast_link_setting'>
          </input><br/>";
    echo DRSTK_PODCAST_REGISTER_HTML;
}

function drstk_spotify_link_callback() {
    $link = get_option('drstk_spotify_link');
    echo "<input name='drstk_spotify_link' type='text'
                 class = 'drstk_podcast_options'
                 value='$link' class='drstk_podcast_link_setting'>
          </input><br/>";
    echo DRSTK_PODCAST_REGISTER_HTML;
}

function drstk_googleplay_link_callback() {
    $link = get_option('drstk_googleplay_link');
    echo "<input name='drstk_googleplay_link' type='text'
                 class = 'drstk_podcast_options'
                 value='$link' class='drstk_podcast_link_setting'>
          </input><br/>";
    echo DRSTK_PODCAST_REGISTER_HTML;
}

function drstk_overcast_link_callback() {
    $link = get_option('drstk_overcast_link');
    echo "<input name='drstk_overcast_link' type='text'
                 class = 'drstk_podcast_options'
                 value='$link' class='drstk_podcast_link_setting'>
          </input><br/>";
    echo DRSTK_PODCAST_REGISTER_HTML;
}

function drstk_stitcher_link_callback() {
    $link = get_option('drstk_stitcher_link');
    echo "<input name='drstk_stitcher_link' type='text'
                 class = 'drstk_podcast_options'
                 value='$link' class='drstk_podcast_link_setting'>
          </input><br/>";
    echo DRSTK_PODCAST_REGISTER_HTML;
}

function drstk_podcast_image_url_callback() {
    $url = get_option('drstk_podcast_image_url') ? get_option('drstk_podcast_image_url') : 'https://brand.northeastern.edu/wp-content/uploads/logotype-250x85.png';
    echo "<input name='drstk_podcast_image_url' type='text'
                 class = 'drstk_podcast_options drstk_podcast_link_setting'
                 value='$url'>
          </input><br/>
          <small>URL to an image to use in your podcast feed (usually something you upload to Media).</small>
          <br/>
          <img src='$url' />
          ";
} 


function drstk_is_podcast_callback() {
    $is_podcast = get_option('drstk_is_podcast');
    if (get_option('drstk_is_podcast')) {
        $checked_attribute = "checked='checked'";
    } else {
        $checked_attribute = '';
    }
    echo "<input name='drstk_is_podcast' type='checkbox' $checked_attribute></input>";
}

function drstk_podcast_poster_callback() {
    $is_podcast = get_option('drstk_podcast_poster');
    if (get_option('drstk_podcast_poster')) {
        $checked_attribute = "checked='checked'";
    } else {
        $checked_attribute = '';
    }
    echo "<input name='drstk_podcast_poster' type='checkbox' $checked_attribute></input>";
}


function drstk_podcast_page_callback() {
    $selected = get_option('drstk_podcast_page');
    wp_dropdown_pages(
        array(
            'selected' => $selected,
            'name' => 'drstk_podcast_page',
            'id' => 'drstk_podcast_page',
            'class' => 'drstk_podcast_options'
        )
    );
    echo "<p><a href='" . get_page_link($selected, false) . "'>" . get_the_title($selected) .   "</a></p>";
}







