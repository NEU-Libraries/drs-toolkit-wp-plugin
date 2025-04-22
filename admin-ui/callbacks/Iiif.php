<?php

namespace Drstk\AdminUI\Callbacks;

class Iiif {

    static function mirador(): void {
        echo '<input type="checkbox" name="drstk_mirador" ';
        if (get_option('drstk_mirador') == 'on') {
            echo 'checked="checked"';
        }
        echo '/>Display</label>';
    }

    static function miradorPageTitle(): void {
        echo '<input type="text" name="drstk_mirador_page_title" value="';
        if (get_option('drstk_mirador_page_title') == '') {
            echo 'Book View';
        } else {
            echo get_option('drstk_mirador_page_title');
        }
        echo '" />';
    }

    static function miradorUrl(): void {
        $mirador_url = get_option('drstk_mirador_url') == '' ? 'mirador' : get_option('drstk_mirador_url');
        echo '<input name="drstk_mirador_url" type="text" value="' . $mirador_url . '"></input><br/>
           <small>This sets the URL path for the mirador viewer<br/>
           Currently, yours will look like: <strong>' . drstk_home_url() . 'mirador/</strong></small>';
    
    }
}




