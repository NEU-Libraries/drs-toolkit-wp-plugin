<?php
namespace Ceres\Util;

function getAllOptions() {
    $ceresAllOptions = [
        'altLabelProp' => [
            'label'   => '',
            'desc'    => '',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '',
            'appliesTo' => 'renderers', 
        ],
        'imageWrap' => [
            'label'   => 'Image Wrap',
            'desc'    => 'How to wrap text around images',
            'access' => ['contentCreator', 'projectOwner', 'coder'],
            'type'    => 'enum',
            'ceresWideDefault' => '',
            'notes' => '',
            'appliesTo' => 'renderers', 
        ],
    
        'extractorMetadataFilterBy' => [
            'label'   => 'Filter Metadata By',
            'desc'    => 'A property in the metadata used to filter metadata results for display',
            'access' => ['coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => 'API default',
            'notes' => '', 
            'appliesTo' => 'extractors',
        ],
    
        
        'extractorResourcesFilterBy' => [
            'label'   => 'Filter Resources By',
            'desc'    => 'A property in the metadata used to filter search results',
            'access' => ['coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => 'API default',
            'notes' => '', 
            'appliesTo' => 'extractors', 
             
        ],
        'extractorMetadataSortBy' => [
            'label'   => 'Sort By Metadata',
            'desc'    => 'How to sort metadata fields for display',
            'access' => ['coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => 'API default',
            'notes' => '', 
            'appliesTo' => 'extractors', 
            
        ],
        'extractorResourcesSortBy' => [
            'label'   => 'Sort resources by',
            'desc'    => 'The property to use for sorting results',
            'access' => ['coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => 'API default',
            'notes' => '',
            'appliesTo' => 'extractors',
            
        ],
        'extractorMetadataSortOrder' => [
            'label'   => 'Metadata sort order',
            'desc'    => 'The order to sort metadata by, e.g. asc or desc',
            'access' => ['coder'],
            'type'    => 'enum',
            'ceresWideDefault' => 'API default',
            'notes' => '', 
            'appliesTo' => 'extractors',  
            
        ],
        'extractorResourcesSortOrder' => [
            'label'   => 'Resources Sort Order',
            'desc'    => 'The order to sort results by, e.g. asc or desc',
            'access' => ['coder'],
            'type'    => 'enum',
            'ceresWideDefault' => 'API default',
            'notes' => '', 
            'appliesTo' => 'extractors', 
            
        ],
    
        // @todo: remove this for extractor?
        'extractorMetadataSortByProperty' => [
            'label'   => 'Sort Metadata By Property',
            'desc'    => 'The metadata property to use for... ',
            'access' => ['coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => 'API default',
            'notes' => 'Maybe remove this for Extractors', 
            'appliesTo' => 'extractors', 
            
        ],
        'extractorResourcesSortByProperty' => [
            'label'   => 'Sort Resources By Property',
            'desc'    => 'Property to use when sorting results',
            'access' => ['coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => 'API default',
            'notes' => '', 
            'appliesTo' => 'extractors', 
            
        ],
        // @todo: do I need this?
        'extractorGroupBy' => [
            'label'   => 'Group By',
            'desc'    => 'Property to use for grouping ',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => 'API default',
            'notes' => 'Needed?', 
            'appliesTo' => 'extractors', 
            
        ],
        'extractorMetadataToShow' => [
            'label'   => 'Metadata To Show',
            'desc'    => 'The specific metadata properties to display',
            'access' => ['contentCreator', 'projectOwner', 'coder'],
            'type'    => 'enum',
            'ceresWideDefault' => 'All',
            'notes' => '', 
            'appliesTo' => 'extractors', 
            
        ],
        'fetcherMetadataToShow' => [
            'label'   => 'Metadata To Show',
            'desc'    => 'The specific metadata properties to display',
            'access' => ['contentCreator', 'projectOwner', 'coder'],
            'type'    => 'enum',
            'ceresWideDefault' => 'All',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'fetcherGroupBy' => [
            'label'   => 'Group by',
            'desc'    => 'Property to use for grouping results',
            'access' => ['contentCreator', 'projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => 'API default',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
             
        ],
        'fetcherFilterBy' => [
            'label'   => 'Filter by',
            'desc'    => 'Property to use for filtering results',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
             
        ],
        'fetcherSortBy' => [
            'label'   => 'Sort by',
            'desc'    => 'Property to use for sorting results',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'fetcherSortOrder' => [
            'label'   => 'Sort order',
            'desc'    => 'The order with which to sort results',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => 'API default',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'fetcherSortByProperty' => [
            'label'   => 'Sort by property',
            'desc'    => 'The property to use for sorting results',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'resourceLinkProp' => [
            'label'   => 'Resource Link Property',
            'desc'    => 'The property to use as the link back to the original resource',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'mediaLinkProp' => [
            'label'   => 'Media Link Property',
            'desc'    => 'The property to use as the link back to the original media',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'mediaUriProp' => [
            'label'   => 'Media URI Property',
            'desc'    => 'The URI to use for displaying media',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'thClassName' => [
            'label'   => 'Table Head Class Name',
            'desc'    => 'The CSS class to apply to &lt;th> elements',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'renderers', 
            
        ],
        'tdClassName' => [
            'label'   => 'Table Data Class Name',
            'desc'    => 'The CSS class to apply to &lt;td> elements',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'renderers', 
            
        ],
    
        'getAll' => [
            'label'   => 'Get All',
            'desc'    => 'Whether to get all resources matching the query, or force pagination of the queries for rolling loads',
            'access' => ['coder'],
            'type'    => 'bool',
            'ceresWideDefault' => true,
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'responseFormat' => [
            'label'   => 'Response Format',
            'desc'    => 'The data format the API should return',
            'access' => ['coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => 'API default',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'perPage' => [
            'label'   => 'Per Page',
            'desc'    => 'The number of results for each page returned by the API',
            'access' => ['coder'],
            'type'    => 'int',
            'ceresWideDefault' => 'API default',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'startPage' => [
            'label'   => 'Start Page',
            'desc'    => 'The start page of results from the API',
            'access' => ['coder'],
            'type'    => 'int',
            'ceresWideDefault' => 'API default',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'resourceIds' => [
            'label'   => 'Resource Ids',
            'desc'    => 'The resource ids to return',
            'access' => ['contentCreator', 'projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'query' => [
            'label'   => 'Query',
            'desc'    => 'The full query to send to the API',
            'access' => ['coder'],
            'type'    => 'text',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
    
        'endpoint' => [
            'label'   => 'Endpoint',
            'desc'    => 'The API endpoint URI',
            'access' => ['coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'fetchers', 
            
        ],
        'searchType' => [
            'label'   => 'Search Type',
            'desc'    => 'The type of search to use for the specific API, e.g. search vs item in DRS',
            'access' => ['coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '',
            'appliesTo' => 'fetchers',
        ],
        'thumbnailSize' => [
            'label'   => 'Thumbnail Size',
            'desc'    => 'The size of the thumbnail to use',
            'access' => ['contentCreator', 'projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => 'API default',
            'notes' => 'This will vary based on API result structure',
            'appliesTo' => 'renderers', 
            
        ],
        'separator' => [
            'label'   => 'Separator',
            'desc'    => 'The separator character to use for key/value pairs',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => ': ',
            'notes' => '', 
            'appliesTo' => 'renderers', 
            
        ],
        'keyClass' => [
            'label'   => 'Key Class',
            'desc'    => 'A CSS class to apply to keys in rendering arrays',
            'access' => ['projectOwner', 'coder'],
            'type'    => 'varchar',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'renderers', 
            
        ],
        'valueClass' => [
            'label'   => 'Value Class',
            'desc'    => 'A CSS class to apply to values in rendering arrays',
            'access' => ['projectOwner', 'coder'],
            'type'    => '',
            'ceresWideDefault' => '',
            'notes' => '', 
            'appliesTo' => 'renderers', 
            
        ],
        'leafletCeres' => [
            'label'   => 'Leaflet CERES',
            'desc'    => '',
            'access' => ['contentCreator', 'projectOwner', 'coder'],
            'type'    => '',
            'ceresWideDefault' => '',
            'notes' => 'need to break this out', 
            'appliesTo' => 'renderers', 
            
        ],
        //passthroughs to Leaflet
        'leafletNative' =>[
            'label'   => '',
            'desc'    => '',
            'access' => ['coder'],
            'type'    => '',
            'ceresWideDefault' => 'Leaflet defaults',
            'notes' => 'need to break this out', 
            'appliesTo' => 'renderers', 
        ],
    
    ];
    return $ceresAllOptions;
}

function getOptionsValues() {
    $ceresOptionsValues = [


    ]; 

    return $ceresOptionsValues;
}

function getViewPackages() {
    /**
     * 
     * sets up the templates for various definitions of a
     * view package. No values are set here -- it is a template
     * for view packages to know what to display
     * permissions by ceresRole and default and set values
     * appear elsewhere
     * 
     */



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


    $ceresViewPackages = [
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

    return $ceresViewPackages;
}