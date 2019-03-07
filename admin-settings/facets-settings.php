<?php



function drstk_facets_callback(){
  $facet_options = drstk_facets_get_option('drstk', true);
  $facets_to_display = drstk_get_facets_to_display();
  echo "<table class='drstk_facets'><tbody id='facets_sortable'>";
  foreach($facets_to_display as $option){
    echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_facets[]" value="'.$option.'" checked="checked"/> <label> <span class="dashicons dashicons-sort"></span> '.titleize($option).'</label></td>';
    echo '<td style="padding:0;" class="title"><input type="text" name="drstk_'.$option.'_title" value="'.get_option('drstk_'.$option.'_title').'"></td></tr>';
  }
  foreach($facet_options as $option){
    if (!in_array($option, $facets_to_display)){
      echo '<tr><td style="padding:0;"><input type="checkbox" name="drstk_facets[]" value="'.$option.'"/> <label> <span class="dashicons dashicons-sort"></span> '.titleize($option).'</label></td>';
      echo '<td style="padding:0;display:none" class="title"><input type="text" name="drstk_'.$option.'_title" value="'.get_option('drstk_'.$option.'_title').'"></td></tr>';
    }
  }
  echo "</tbody></table>";
}

function drstk_facet_title_callback(){
  echo '';
}

function drstk_facet_sort_callback(){
  $sort_options = array("fc_desc"=>"Facet Count (Highest to Lowest)","fc_asc"=>"Facet Count (Lowest to Highest)","abc_asc"=>"Facet Title (A-Z)","abc_desc"=>"Facet Title (Z-A)");
  $default_sort = get_option('drstk_facet_sort_order');
  echo '<select name="drstk_facet_sort_order">';
  foreach($sort_options as $val=>$option){
    echo '<option value="'.$val.'"';
    if ($default_sort == $val){ echo 'selected="true"';}
    echo '/> '.$option.'</option>';
  }
  echo '</select><br/>';
}
