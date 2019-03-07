<?php

add_settings_field('drstk_assoc',
    'Allow Mirador Page Viewer<br/><small>This requires a manifest file and modifications to a javascript file. Please contact the Toolkit team if you would like to enable this feature.</small>',
    'drstk_mirador_callback', 'drstk_options',
    'drstk_advanced');
register_setting( 'drstk_options', 'drstk_mirador' );

add_settings_field('drstk_mirador_page_title',
    'Mirador Page Title',
    'drstk_mirador_page_title_callback',
    'drstk_options',
    'drstk_advanced',
    array('class'=>'mirador'));
register_setting( 'drstk_options', 'drstk_mirador_page_title' );

add_settings_field('drstk_mirador_url',
    'Mirador URL',
    'drstk_mirador_url_callback',
    'drstk_options',
    'drstk_advanced',
    array('class'=>'mirador'));
register_setting('drstk_options', 'drstk_mirador_url');

