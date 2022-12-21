<?php


//print_r($optionsData);
// die();
function optionsDataReport($ceres_options_data) {
    $html = "<html><head>";
    $html .= "<style>table, td, th {border: 2px solid black; border-collapse: collapse; ";
    $html .= "margin: 0px; padding: 3px; } ";
    $html .= "td.notes {height: 150px;} ";
    $html .= "td.desc {width: 200px;} ";
    $html .= "td.label {width: 100px;} ";
    $html .= "</style>";
    $html .= "</head><body><table><tbody><tr>";
    $html .= "<th>Name</th>";
    $html .= "<th>Label</th>";
    $html .= "<th>Description</th>";
    $html .= "<th>Value</th>";
    $html .= "<th>Access</th>";
    $html .= "<th>Type</th>";
    $html .= "<th style='width: 100px';>CERES-wide Default</th>";
    $html .= "<th style='width: 250px; '>Notes</th>";
    $html .= "<th>Applies to</th>";
    $html .= "</tr>";

    $contentCreators =[];
    $owners = [];
    $coders = [];
   // print_r($optionsData);
        foreach($ceres_options_data as $optionName => $optionsArray) {
            switch ($ceres_options_data[$optionName]['access']) {
                case 'contentCreator':
                    $contentCreators[$optionName] = $ceres_options_data[$optionName];
                break;
                case 'projectOwner':
                    $owners[$optionName] = $ceres_options_data[$optionName];
                break;
                case 'coder':
                    $coders[$optionName] = $ceres_options_data[$optionName];
                break;
                default:
      
            }

        }
        //$optionsData = array_merge($contentCreators, $owners, $coders);

    $html .= "<tr><td colspan=7 style='text-align: center; '>Content Creators</td></tr>";
    $html .= "<tr>";
    foreach ($contentCreators as $codeName => $optionData) {
        $html .= "<td>$codeName</td>";
        foreach($optionData as $optionName => $optionValue) {
            if (is_array($optionValue)) {
               $optionValue = "<pre>" .  print_r($optionValue, true) . "</pre>";
            }
            if (empty($optionValue) && $optionName != 'notes') {
                $optionValue = '()';
            }
            
            $html .= "<td class='$optionName'>$optionValue</td>";
            
        }
        $html .= "</tr>";
    }
    $html .= "<tr><td colspan=7 style='text-align: center; '>Owners -- all of the above plus</td></tr>";
    $html .= "<tr>";

    foreach ($owners as $codeName => $optionData) {
        $html .= "<td>$codeName</td>";
        foreach($optionData as $optionName => $optionValue) {
            if (is_array($optionValue)) {
                $optionValue = "<pre>" .  print_r($optionValue, true) . "</pre>";
            }
            if (empty($optionValue)) {
                $optionValue = '()';
            }
            $html .= "<td>$optionValue</td>";
            
        }
        $html .= "</tr>";
    }
    $html .= "<tr><td colspan=7 style='text-align: center; '>Coders -- all of the above plus these, and whatever we build</td></tr>";
    $html .= "<tr>";
    foreach ($coders as $codeName => $optionData) {
        $html .= "<td>$codeName</td>";
        foreach($optionData as $optionName => $optionValue) {

            if (is_array($optionValue)) {
                $optionValue = "<pre>" .  print_r($optionValue, true) . "</pre>";
            }
            if (empty($optionValue)) {
                $optionValue = '()';
            }
            $html .= "<td>$optionValue</td>";
 
        }
        $html .= "</tr>";
    }

    $html .= "</tbody></table></body></html>";

    return $html;
}

