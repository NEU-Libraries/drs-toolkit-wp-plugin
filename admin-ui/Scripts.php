<?php

namespace Drstk\AdminUI;

class Scripts {

    /*fix for weird jumpiness in wp admin menu*/
    // @todo is this still needed?
    static function fixAdminHead(): void {
        echo "<script type='text/javascript'>jQuery(window).load(function(){jQuery('#adminmenuwrap').hide().show(0);});</script>";
        echo "<style>#start-pt-pb-tour{display:none !important;}</style>";
    }

    static function adminEnqueue(): void {
        if (get_current_screen()->base == 'settings_page_drstk_admin_menu') {
            // we are on the settings page
            wp_enqueue_script('jquery-ui-sortable');
            wp_register_script('drstk_meta_helper_js',
                plugins_url('/assets/js/item_meta_helper.js', __FILE__),
                array('jquery'));
            wp_enqueue_script( 'drstk_meta_helper_js');
        }
    }
}

