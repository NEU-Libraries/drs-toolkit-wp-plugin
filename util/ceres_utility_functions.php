<?php


// Template 
// 'extractorMetadataFilterBy' => [
//     'label'   => '',
//     'desc'    => '',
//     'value'   => '',
//     'access' => '',
//     'type'    => '',
//     'default' => '',
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
//     'notes' =>'',
//     'appliesTo' => '', 
// ],


$ceres_options_data = [
    'altLabelProp' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => '',
        'type'    => '',
        'default' => '',
            //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
        'notes' => '',
        'appliesTo' => 'renderers', 
    ],
    'imageWrap' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => '',
        'type'    => '',
        'default' => '',
            //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
        'notes' => '',
        'appliesTo' => 'renderers', 
    ],

    'extractorMetadataFilterBy' => [
        'label'   => 'Filter Metadata By',
        'desc'    => 'A property in the metadata used to filter metadata results for display',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => 'varchar',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors',
    ],

    
    'extractorResourcesFilterBy' => [
        'label'   => 'Filter Resources By',
        'desc'    => 'A proper in the metadata used to filter search results',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => 'varchar',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
         //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'extractorMetadataSortBy' => [
        'label'   => 'Sort By Metadata',
        'desc'    => 'How to sort metadata fields for display',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => 'varchar',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'extractorResourcesSortBy' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => 'varchar',
        'default' => '',
        'notes' => '',
        'appliesTo' => 'extractors',
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'extractorMetadataSortOrder' => [
        'label'   => 'Extractor Metadata Sort Order',
        'desc'    => 'The order to sort metadata by, e.g. asc or desc',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => 'enum',
        'default' => 'asc',
        'notes' => '', 
        'appliesTo' => 'extractors',  
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'extractorResourcesSortOrder' => [
        'label'   => 'Resources Sort Order',
        'desc'    => 'The order to sort results by, e.g. asc or desc',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => 'enum',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],

    // @todo: remove this for extractor?
    'extractorMetadataSortByProperty' => [
        'label'   => 'Sort Metadata By Property',
        'desc'    => 'The metadata property to use for... ',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'extractorResourcesSortByProperty' => [
        'label'   => 'Sort Resources By Property',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    // @todo: do I need this?
    'extractorGroupBy' => [
        'label'   => 'Group By',
        'desc'    => 'Property to use for grouping ',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'extractorMetadataToShow' => [
        'label'   => 'Metadata To Show',
        'desc'    => 'The specific metadata properties to display',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => 'enum',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'fetcherMetadataToShow' => [
        'label'   => 'Metadata To Show',
        'desc'    => 'The specific metadata properties to display',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => 'enum',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'fetcherGroupBy' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
         //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'fetcherFilterBy' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
         //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'fetcherSortBy' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'fetcherSortOrder' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'fetcherSortByProperty' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'resourceLinkProp' => [
        'label'   => 'Resource Link Property',
        'desc'    => 'The property to use as the link back to the original resource',
        'value'   => '',
        'access' => 'owner',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'mediaLinkProp' => [
        'label'   => 'Media Link Property',
        'desc'    => 'The property to use as the link back to the original media',
        'value'   => '',
        'access' => 'owner',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'mediaUriProp' => [
        'label'   => 'Media URI Property',
        'desc'    => 'The URI to use for displaying media',
        'value'   => '',
        'access' => 'owner',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'thClassName' => [
        'label'   => 'Table Head Class Name',
        'desc'    => 'The CSS class to apply to &lt;th> elements',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'tdClassName' => [
        'label'   => 'Table Data Class Name',
        'desc'    => 'The CSS class to apply to &lt;td> elements',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],

    'getAll' => [
        'label'   => 'Get All',
        'desc'    => 'Whether to get all resources matching the query, or force pagination of the queries for rolling loads',
        'value'   => '',
        'access' => 'coder',
        'type'    => 'bool',
        'default' => false,
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'responseFormat' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'coder',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'perPage' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'coder',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'startPage' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'coder',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'resourceIds' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'query' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'coder',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],

    'endpoint' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'coder',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'searchType' => [
        'label'   => 'Search Type',
        'desc'    => 'The type of search to use for the specific API, e.g. search vs item in DRS',
        'value'   => '',
        'access' => 'coder',
        'type'    => '',
        'default' => '',
        'notes' => '',
        'appliesTo' => 'fetchers',
    ],
    'thumbnailSize' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => ['all' => 'a',
                      'drs' => 3,
                     ],
        'notes' => '', 
        'appliesTo' => 'renderers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'separator' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'keyClass' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'valueClass' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    'leafletCeres' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'content_creator',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
        //'default' => ['all' => [], 'drs' => [], 'wdqs' => []] //for very particular situations
    ],
    //passthroughs to Leaflet
    'leafletNative' =>[
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => 'owner',
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
    ],

];

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
    //$html .= "<th>Access</th>";
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
                case 'content_creator':
                    $contentCreators[$optionName] = $ceres_options_data[$optionName];
                break;
                case 'owner':
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
            if ($optionName == 'access') {
                continue;
            }
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
            if ($optionName == 'access') {
                continue;
            }
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

            if ($optionName == 'access') {
                continue;
            }
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


/**
 * 
 * for building up  view packages. template, not set values
 */

$extractorOptions = [
    'general' => [
        'extractorMetadataFilterBy' => '',
        'extractorResourcesFilterBy' => '',
        'extractorMetadataSortBy' => '',
        'extractorResourcesSortBy' => '',
        'extractorMetadataSortOrder' => '',
        'extractorResourcesSortOrder' => '',
        'extractorMetadataSortByProperty' => '',
        'extractorResourcesSortByProperty' => '',
        'extractorGroupBy' => '',
        'itemLinkProp' => '',
        'mediaLinkProp' => '',
        'mediaUriProp' => '',

    ],
    'tabular' => [
        'thClassName' => '',
        'tdClassName' => '',
    ]
];


/**
 * 
 * for building up view packages. template, not set values
 */


$fetcherOptions = [
    'general' => [
        'endpoint' => ['value' =>'',
                       'permissions' => 'contentCreator' // @todo or keep separate?
                      ],
        'fetcherMetadataToShow' => [],
        'getAll' => false,
        'responseFormat' => '',
        'perPage' => 10,
        'startPage' => 1,
        'resourceIds' => [],
        'query' => "",
        'fetcherGroupBy' => '', 
        'fetcherFilterBy' => '',
        'fetcherSortBy' => '',
        'fetcherSortOrder' => '',
        'fetcherSortByProperty' => '',
    ],
    'wdqs' => [
        'endpoint' => "https://wikidata",
        'responseFormat' => 'json',

    ],
    'drs' => [
        'endpoint' => "",
        'type' => "{search | item}",
        'thumbnailSize' => "{1, 2, 3, 4, 5}" // from DRS API

    ]


];


/**
 * 
 * for building up  view packages. template, not set values
 */


$rendererOptions = [
    'general' => [
        'imageWrap' => "",
        'containerClass' => "ceres-container", // @todo need to auto fill based on VP?
        'metadataToShow' => [],
        'altLabelProp' => "",
        'thClass' => "general",
    ],

    'tabular' => [
        'thClass' => "",
        'tdClass' => "",
        'trClass' => "",
    ],

    'keyValue' => [
        'separator' => ": ",
        'keyClass' => "",
        'valueClass' => "",
    ],
    //settings in the surrounding HTML
    'leafletCeres' => [

    ],
    //passthroughs to Leaflet
    'leafletNative' => [

    ],

    
];

/**
 * 
 * sets up the templates for various definitions of a
 * view package. No values are set here -- it is a template
 * for view packages to know what to display
 * permissions by ceresRole and default and set values
 * appear elsewhere
 * 
 */
$viewPackages = [
    "tabular_wikidata_for_short_metadata" =>
            [
              'humanName' => "Human Name",
              'description' => "Description",
              'parentViewPackage' => "",
              'projectName' => '',
              'rendererClassName' => "Ceres_Tabular_Renderer",
              'rendererOptions' => [
                    'options' => [  //redundant, yes. but helps keep the same patter with fetchers and extractors
                        array_merge(
                            $rendererOptions['general'],
                            $rendererOptions['tabular']
                        )
                    ]
                ],

              'fetchers' => 
                [
                    'WdqsFetcher' =>
                        ['options' => array_merge(
                            $fetcherOptions['general'],
                            $fetcherOptions['wdqs']),
                        ]
                ],
                
              'extractors' =>
                [
                    'WikiDataToTabular' =>
                        ['options' => array_merge(
                            $extractorOptions['general'], 
                            $extractorOptions['tabular']),
                        ],
                ],
            ],

        "another_view_package" => [],
    
];