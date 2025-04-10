<?php

namespace Drstk\AdminUI\Callbacks;

class ItemPage {
    
    static function itemPageMetadata() {
        global $all_meta_options;
        $item_options = get_option('drstk_item_page_metadata') != "" ? get_option('drstk_item_page_metadata') : array();
        echo '<table class="drstk_item_metadata"><tbody id="item_metadata_sortable">';
        foreach ($item_options as $option) {
            echo '<tr><td style="padding:0"><label><input type="checkbox" name="drstk_item_page_metadata[]" value="' . $option . '" ';
            if (is_array($item_options) && in_array($option, $item_options)) {
                echo 'checked="checked"';
            }
            echo '/> <span class="dashicons dashicons-sort"></span> ' . $option . ' </label></td></tr>';
        }
        foreach ($all_meta_options as $option) {
            if (!in_array($option, $item_options)) {
                echo '<tr><td style="padding:0"><label><input type="checkbox" name="drstk_item_page_metadata[]" value="' . $option . '" ';
                if (is_array($item_options) && in_array($option, $item_options)) {
                    echo 'checked="checked"';
                }
                echo '/> <span class="dashicons dashicons-sort"></span> ' . $option . ' </label></td></tr>';
            }
        }
        echo '</tbody></table>';
        echo '<a href="" class="add-item-meta button"><span class="dashicons dashicons-plus"></span>Add Metadata Field</a>';
    

    }

    static function appears(): void {
        echo '<input type="checkbox" name="drstk_appears" ';
        if (get_option('drstk_appears') == 'on') {
            echo 'checked="checked"';
        }
        echo '/>Display</label>';
    }

    static function appearsTitle(): void {
        echo '<input type="text" name="drstk_appears_title" value="';
        if (get_option('drstk_appears_title') == '') {
            echo 'Item Appears In';
        } else {
            echo get_option('drstk_appears_title');
        }
        echo '" />';

    }

    static function associatedFiles(): void {
        echo '<input type="checkbox" name="drstk_assoc" ';
        if (get_option('drstk_assoc') == 'on') {
            echo 'checked="checked"';
        }
        echo '/>Display</label>';
    }

    static function associatedTitle(): void {
        echo '<input type="text" name="drstk_assoc_title" value="';
        if (get_option('drstk_assoc_title') == '') {
            echo 'Associated Files';
        } else {
            echo get_option('drstk_assoc_title');
        }
        echo '" />';
    }

    static function associatedFileMetadata(): void {
        global $all_assoc_meta_options;
        $assoc_options = drstk_get_assoc_meta_options();
        foreach ($all_assoc_meta_options as $option) {
            echo '<label><input type="checkbox" name="drstk_assoc_file_metadata[]" value="' . $option . '" ';
            if (is_array($assoc_options) && in_array($option, $assoc_options)) {
                echo 'checked="checked"';
            }
            echo '/> ' . titleize($option) . '</label><br/>';
        }

    }

    static function annotations(): void {

        echo '<input type="checkbox" name="drstk_item_extensions" ';
        if (get_option('drstk_item_extensions') == 'on') {
            echo 'checked="checked"';
        }
        echo '/>Enable</label>';
    }

    static function facets(): void {
        $facet_options = drstk_facets_get_option('drstk', true);
        $facets_to_display = drstk_get_facets_to_display();
        echo "<table class='drstk_facets'><tbody id='facets_sortable'>";
        foreach ($facets_to_display as $option) {
            echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_facets[]" value="' . $option . '" checked="checked"/> <label> <span class="dashicons dashicons-sort"></span> ' . titleize($option) . '</label></td>';
            echo '<td style="padding:0;" class="title"><input type="text" name="drstk_' . $option . '_title" value="' . get_option('drstk_' . $option . '_title') . '"></td></tr>';
        }
        foreach ($facet_options as $option) {
            if (!in_array($option, $facets_to_display)) {
                echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_facets[]" value="' . $option . '"/> <label> <span class="dashicons dashicons-sort"></span> ' . titleize($option) . '</label></td>';
                echo '<td style="padding:0;display:none" class="title"><input type="text" name="drstk_' . $option . '_title" value="' . get_option('drstk_' . $option . '_title') . '"></td></tr>';
            }
        }
        echo "</tbody></table>";
    }

    static function facetSort(): void {
        $sort_options = array("fc_desc" => "Facet Count (Highest to Lowest)", "fc_asc" => "Facet Count (Lowest to Highest)", "abc_asc" => "Facet Title (A-Z)", "abc_desc" => "Facet Title (Z-A)");
        $default_sort = get_option('drstk_facet_sort_order');
        echo '<select name="drstk_facet_sort_order">';
        foreach ($sort_options as $val => $option) {
            echo '<option value="' . $val . '"';
            if ($default_sort == $val) {
                echo 'selected="true"';
            }
            echo '/> ' . $option . '</option>';
        }
        echo '</select><br/>';
    }

    // @todo figure out how and why this was in the old code
    static function facetTitle(): void {
        echo '';
    }

    static function itemExtensions(): void {
        echo '<input type="checkbox" name="drstk_item_extensions" ';
        if (get_option('drstk_item_extensions') == 'on') {
            echo 'checked="checked"';
        }
        echo '/>Enable</label>';
    }
}




