<?php

// Template 
// 'extractorMetadataFilterBy' => [
//     'label'   => '',
//     'desc'    => '',
//     'value'   => '',
//     'access' => [],
//     'type'    => '',
//     'default' => '',
//     'notes' =>'',
//     'appliesTo' => '', 
// ],

/**
 * The core, complete set of options that exist (or will/could exist)
 * in CERES. Values stored elsewhere, and merged for live needs like
 * displaying the page/components/etc or reporting on how they are
 * being used across view templates (and possibly projects)
 * 
 */

$ceresOptionsValueTemplate = [
    'currentValue' => '',
    'defaults' => [
        'ceres' => 'val',
        'project_name' => 'val',
        'view_package_name' => 'val',
        'immutable' => 'val',
    ]

    ];

$ceresAllOptions = [
    'altLabelProp' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => ['projectOwner'],
        'type'    => '',
        'default' => '',
        'notes' => '',
        'appliesTo' => 'renderers', 
    ],
    'imageWrap' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
            
        'notes' => '',
        'appliesTo' => 'renderers', 
    ],

    'extractorMetadataFilterBy' => [
        'label'   => 'Filter Metadata By',
        'desc'    => 'A property in the metadata used to filter metadata results for display',
        'value'   => '',
        'access' => [],
        'type'    => 'varchar',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors',
    ],

    
    'extractorResourcesFilterBy' => [
        'label'   => 'Filter Resources By',
        'desc'    => 'A proper in the metadata used to filter search results',
        'value'   => '',
        'access' => [],
        'type'    => 'varchar',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
         
    ],
    'extractorMetadataSortBy' => [
        'label'   => 'Sort By Metadata',
        'desc'    => 'How to sort metadata fields for display',
        'value'   => '',
        'access' => [],
        'type'    => 'varchar',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
        
    ],
    'extractorResourcesSortBy' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => 'varchar',
        'default' => '',
        'notes' => '',
        'appliesTo' => 'extractors',
        
    ],
    'extractorMetadataSortOrder' => [
        'label'   => 'Extractor Metadata Sort Order',
        'desc'    => 'The order to sort metadata by, e.g. asc or desc',
        'value'   => '',
        'access' => [],
        'type'    => 'enum',
        'default' => 'asc',
        'notes' => '', 
        'appliesTo' => 'extractors',  
        
    ],
    'extractorResourcesSortOrder' => [
        'label'   => 'Resources Sort Order',
        'desc'    => 'The order to sort results by, e.g. asc or desc',
        'value'   => '',
        'access' => [],
        'type'    => 'enum',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
        
    ],

    // @todo: remove this for extractor?
    'extractorMetadataSortByProperty' => [
        'label'   => 'Sort Metadata By Property',
        'desc'    => 'The metadata property to use for... ',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
        
    ],
    'extractorResourcesSortByProperty' => [
        'label'   => 'Sort Resources By Property',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
        
    ],
    // @todo: do I need this?
    'extractorGroupBy' => [
        'label'   => 'Group By',
        'desc'    => 'Property to use for grouping ',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
        
    ],
    'extractorMetadataToShow' => [
        'label'   => 'Metadata To Show',
        'desc'    => 'The specific metadata properties to display',
        'value'   => '',
        'access' => [],
        'type'    => 'enum',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'extractors', 
        
    ],
    'fetcherMetadataToShow' => [
        'label'   => 'Metadata To Show',
        'desc'    => 'The specific metadata properties to display',
        'value'   => '',
        'access' => [],
        'type'    => 'enum',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'fetcherGroupBy' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
         
    ],
    'fetcherFilterBy' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
         
    ],
    'fetcherSortBy' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'fetcherSortOrder' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'fetcherSortByProperty' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'resourceLinkProp' => [
        'label'   => 'Resource Link Property',
        'desc'    => 'The property to use as the link back to the original resource',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'mediaLinkProp' => [
        'label'   => 'Media Link Property',
        'desc'    => 'The property to use as the link back to the original media',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'mediaUriProp' => [
        'label'   => 'Media URI Property',
        'desc'    => 'The URI to use for displaying media',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'thClassName' => [
        'label'   => 'Table Head Class Name',
        'desc'    => 'The CSS class to apply to &lt;th> elements',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
        
    ],
    'tdClassName' => [
        'label'   => 'Table Data Class Name',
        'desc'    => 'The CSS class to apply to &lt;td> elements',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
        
    ],

    'getAll' => [
        'label'   => 'Get All',
        'desc'    => 'Whether to get all resources matching the query, or force pagination of the queries for rolling loads',
        'value'   => '',
        'access' => [],
        'type'    => 'bool',
        'default' => false,
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'responseFormat' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'perPage' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'startPage' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'resourceIds' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'query' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],

    'endpoint' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'fetchers', 
        
    ],
    'searchType' => [
        'label'   => 'Search Type',
        'desc'    => 'The type of search to use for the specific API, e.g. search vs item in DRS',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '',
        'appliesTo' => 'fetchers',
    ],
    'thumbnailSize' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => ['all' => 'a',
                      'drs' => 3,
                     ],
        'notes' => '', 
        'appliesTo' => 'renderers', 
        
    ],
    'separator' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
        
    ],
    'keyClass' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
        
    ],
    'valueClass' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
        
    ],
    'leafletCeres' => [
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
        
    ],
    //passthroughs to Leaflet
    'leafletNative' =>[
        'label'   => '',
        'desc'    => '',
        'value'   => '',
        'access' => [],
        'type'    => '',
        'default' => '',
        'notes' => '', 
        'appliesTo' => 'renderers', 
    ],

];


/**
 * 
 * for building up  view packages. template, not set values
 * 
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
                        //after deduping options in the merge,
                        // stuff in the current values
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



