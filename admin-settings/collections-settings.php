<?php


add_settings_section('drstk_collections_settings', 'Collections Browse Page', null, 'drstk_options');
add_settings_field('drstk_collections_page_title', 'Collections Browse Title', 'drstk_collections_page_title_callback', 'drstk_options', 'drstk_collections_settings');
register_setting( 'drstk_options', 'drstk_collections_page_title' );

add_settings_section('drstk_collection_settings', 'Collection Page', null, 'drstk_options');
add_settings_field('drstk_collection_page_title', 'Collection Page Title', 'drstk_collection_page_title_callback', 'drstk_options', 'drstk_collection_settings');
register_setting( 'drstk_options', 'drstk_collection_page_title' );



function drstk_collections_page_title_callback(){
  echo '<input type="text" name="drstk_collections_page_title" value="';
  if (get_option('drstk_collections_page_title') == ''){ echo 'Browse Collections';} else { echo get_option('drstk_collections_page_title'); }
  echo '" />';
}

function drstk_collection_page_title_callback(){
  echo '<input type="text" name="drstk_collection_page_title" value="';
  if (get_option('drstk_collection_page_title') == ''){ echo 'Collection';} else { echo get_option('drstk_collection_page_title'); }
  echo '" />';
}


