<?php

add_settings_field('drstk_is_podcast',
    'Is this a podcast site?',
    'drstk_is_podcast_callback',
    'drstk_options',
    'drstk_advanced');
register_setting('drstk_options', 'drstk_is_podcast');

add_settings_field('drstk_podcast_poster',
    'Show an image for each podcast episode?',
    'drstk_podcast_poster_callback',
    'drstk_options',
    'drstk_advanced');
register_setting('drstk_options', 'drstk_podcast_poster');



add_settings_field('drstk_podcast_page',
    'Select page to contain your podcast list',
    'drstk_podcast_page_callback',
    'drstk_options',
    'drstk_advanced',
    array('class' => 'drstk_podcast_options'));
register_setting('drstk_options', 'drstk_podcast_page');


add_settings_field('drstk_podcast_image_url',
    'Image for podcast feed',
    'drstk_podcast_image_url_callback',
    'drstk_options',
    'drstk_advanced',
    array('class' => 'drstk_podcast_options'));
register_setting('drstk_options', 'drstk_podcast_image_url');

add_settings_field('drstk_itunes_link',
    'Link to iTunes',
    'drstk_itunes_link_callback',
    'drstk_options',
    'drstk_advanced',
    array('class' => 'drstk_podcast_options')
    );
register_setting('drstk_options', 'drstk_itunes_link');

add_settings_field('drstk_googleplay_link',
    'Link to Google Play',
    'drstk_googleplay_link_callback',
    'drstk_options',
    'drstk_advanced',
    array('class' => 'drstk_podcast_options'));
register_setting('drstk_options', 'drstk_googleplay_link');

add_settings_field('drstk_spotify_link',
    'Link to Spotify',
    'drstk_spotify_link_callback',
    'drstk_options',
    'drstk_advanced',
    array('class' => 'drstk_podcast_options'));
register_setting('drstk_options', 'drstk_spotify_link');

add_settings_field('drstk_stitcher_link',
    'Link to Stitcher',
    'drstk_stitcher_link_callback',
    'drstk_options',
    'drstk_advanced',
    array('class' => 'drstk_podcast_options'));
register_setting('drstk_options', 'drstk_stitcher_link');

add_settings_field('drstk_overcast_link',
    'Link to Overcast',
    'drstk_overcast_link_callback',
    'drstk_options',
    'drstk_advanced',
    array('class' => 'drstk_podcast_options'));
register_setting('drstk_options', 'drstk_overcast_link');



function drstk_is_podcast_callback() {
  if (get_option('drstk_is_podcast')) {
    $checked_attribute = "checked='checked'";
  } else {
    $checked_attribute = '';
  }
  echo "<input name='drstk_is_podcast' type='checkbox' $checked_attribute></input>";
}

function drstk_podcast_poster_callback() {
  if (get_option('drstk_podcast_poster')) {
    $checked_attribute = "checked='checked'";
  } else {
    $checked_attribute = '';
  }
  echo "<input name='drstk_podcast_poster' type='checkbox' $checked_attribute></input>";
}


function drstk_podcast_page_callback() {
  $selected = get_option('drstk_podcast_page');
  wp_dropdown_pages( array(
      'selected' => $selected,
      'name' => 'drstk_podcast_page',
      'id' => 'drstk_podcast_page',
      'class' => 'drstk_podcast_options'
  )
      );
}


function drstk_itunes_link_callback() {
  $link = get_option('drstk_itunes_link');
  echo "<input name='drstk_itunes_link' type='text'
               class = 'drstk_podcast_options'
               value='$link' class='drstk_podcast_link_setting'>
        </input><br/>
        <small>Register this feed URL: " . get_site_url() . "?feed=podcasts</small><br/>
        <small>When you register your podcast with this service, it will tell you the link to use.</small>";
}

function drstk_spotify_link_callback() {
  $link = get_option('drstk_spotify_link');
  echo "<input name='drstk_spotify_link' type='text'
               class = 'drstk_podcast_options'
               value='$link' class='drstk_podcast_link_setting'>
        </input><br/>
        <small>Register this feed URL: " . get_site_url() . "?feed=podcasts</small><br/>
        <small>When you register your podcast with this service, it will tell you the link to use.</small>";
}

function drstk_googleplay_link_callback() {
  $link = get_option('drstk_googleplay_link');
  echo "<input name='drstk_googleplay_link' type='text'
               class = 'drstk_podcast_options'
               value='$link' class='drstk_podcast_link_setting'>
        </input><br/>
        <small>Register this feed URL: " . get_site_url() . "?feed=podcasts</small><br/>
        <small>When you register your podcast with this service, it will tell you the link to use.</small>";
}

function drstk_overcast_link_callback() {
  $link = get_option('drstk_overcast_link');
  echo "<input name='drstk_overcast_link' type='text'
               class = 'drstk_podcast_options'
               value='$link' class='drstk_podcast_link_setting'>
        </input><br/>
        <small>Register this feed URL: " . get_site_url() . "?feed=podcasts</small><br/>
        <small>When you register your podcast with this service, it will tell you the link to use.</small>";
}

function drstk_stitcher_link_callback() {
  $link = get_option('drstk_stitcher_link');
  echo "<input name='drstk_stitcher_link' type='text'
               class = 'drstk_podcast_options'
               value='$link' class='drstk_podcast_link_setting'>
        </input><br/>
        <small>Register this feed URL: " . get_site_url() . "?feed=podcasts</small><br/>
        <small>When you register your podcast with this service, it will tell you the link to use.</small>";
}

function drstk_podcast_image_url_callback() {
  $url = get_option('drstk_podcast_image_url');
  echo "<input name='drstk_podcast_image_url' type='text'
               class = 'drstk_podcast_options'
               value='$url' class='drstk_podcast_link_setting'>
        </input><br/>
        <small>URL to an image to use in your podcast feed (usually something you upload to Media).</small>";
}

