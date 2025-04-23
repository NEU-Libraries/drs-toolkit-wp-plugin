<?php
wp_register_script(
    'drstk_admin_selectitem',
    plugins_url('assets/js/admin/selectItem.js', __FILE__)
);
wp_enqueue_script('drstk_admin_selectitem');
