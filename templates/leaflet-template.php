<?php 

wp_register_script('drstk-leaflet', DRS_PLUGIN_URL . "assets/leaflet/js/leaflet1.7.1.js");
wp_enqueue_script('drstk-leaflet');

wp_register_script('drstk-leaflet-wicket', DRS_PLUGIN_URL . "assets/leaflet/js/wicket-1.3.8.js");
wp_enqueue_script('drstk-leaflet-wicket');


wp_register_script('drstk-leaflet-marker-cluster', DRS_PLUGIN_URL . "assets/leaflet/js/leaflet-js-markercluster/leaflet.markercluster.js");
wp_enqueue_script('drstk-leaflet-marker-cluster');


wp_register_script('drstk-leaflet-boston-boundaries', DRS_PLUGIN_URL . "assets/leaflet/js/bostonboundaries.js");
wp_enqueue_script('drstk-leaflet-boston-boundaries');


wp_register_script('drstk-leaflet-plugin-mask', DRS_PLUGIN_URL . "assets/leaflet/js/leafet-plugin-mask.js");
wp_enqueue_script('drstk-leaflet-plugin-mask');


wp_register_script('drstk-leaflet-boundary-canvas', DRS_PLUGIN_URL . "assets/leaflet/js/leaflet-boundary-canvas.js");
wp_enqueue_script('drstk-leaflet-boundary-canvas');


wp_register_script('drstk-leaflet-plugin-geolet', DRS_PLUGIN_URL . "assets/leaflet/js/leaflet-plugin-geolet.js");
wp_enqueue_script('drstk-leaflet-plugin-geolet');


wp_register_script('drstk-leaflet-fuse-plugin', DRS_PLUGIN_URL . "assets/leaflet/js/fuse-leaflet-plugin-6-6-2.js");
wp_enqueue_script('drstk-leaflet-fuse-plugin');

/* 
<script type="text/javascript" src="./res/libraries/leaflet1.7.1.js"></script>
<script type="text/javascript" src="./res/libraries/wicket-1.3.8.js"></script>
<script type="text/javascript" src="./res/libraries/leaflet-js-markercluster/leaflet.markercluster.js"></script>
<script type="text/javascript" src="./js/bostonboundaries.js"></script>
<script type="text/javascript" src="./res/libraries/leafet-plugin-mask.js"></script>
<script type="text/javascript" src="./res/libraries/leaflet-boundary-canvas.js"></script>
<script type="text/javascript" src="./res/libraries/leaflet-plugin-geolet.js"></script>
<script type="text/javascript" src="./res/libraries/fuse-leaflet-plugin-6-6-2.js"></script>

*/


wp_register_style('drstk-leaflet-css', DRS_PLUGIN_URL . "assets/leaflet/css/leaflet.css");
wp_enqueue_style('drstk-leaflet-css');


wp_register_style('drstk-leaflet-brc-project', DRS_PLUGIN_URL . "assets/leaflet/css/leaflet-brc-project.css");
wp_enqueue_style('drstk-leaflet-brc-project');


wp_register_style('drstk-leaflet-marker-cluster', DRS_PLUGIN_URL . "assets/leaflet/css/leaflet-js-markercluster/MarkerCluster.css");
wp_enqueue_style('drstk-leaflet-marker-cluster');


wp_register_style('drstk-leaflet-marker-cluster-default', DRS_PLUGIN_URL . "assets/leaflet/css/leaflet-js-markercluster/MarkerCluster.Default.css");
wp_enqueue_style('drstk-leaflet-marker-cluster-default');


/*
<link rel="stylesheet" type="text/css" href="./res/libraries/leaflet.css" />
<link rel="stylesheet" type="text/css" href="./css/leaflet-brc-project.css" />
<link rel="stylesheet" type="text/css" href="./res/libraries/leaflet-js-markercluster/MarkerCluster.css" />
<link rel="stylesheet" type="text/css" href="./res/libraries/leaflet-js-markercluster/MarkerCluster.Default.css" />
*/
get_header();

$page_id = 522;
if ($page_id == 522) {
$page_object = get_page( $page_id );
}


?>


<div id="content">
<div class="quest-row site-content">
<div class="<?php echo apply_filters( 'quest_content_container_cls', 'container' ); ?>">
<div id="drs-loading"></div>
<div class="row">
<div id="primary" class="content-area col-md-9">

<main id="main" class="site-main" role="main">

<h2><?php echo apply_filters('the_title', $page_object->post_title); ?></h2>

						
						<div id="drs-content" class="container">
						  <div class="row">
						    <?php echo apply_filters('the_content', $page_object->post_content);?>
						  </div>
						   <div class="body-container">
        <!--- The filter container which hold the filters -->
        <div class="filters-container">
            <div class="filters-section">
                <h1 class="filters-header">Filters</h1>
                <br>
                <div class="filters-body">
                    <div class="location-section">
                        <h3>Search</h3>
                        <!-- The search box container which has search option -->
                        <div class="search-container">
                            <form action="javascript:void(0);">
                                <input type="text" placeholder="Search.." name="search" id="search-box-input">
                                <button type="button" id="filters-search"><img src=<?php echo DRS_PLUGIN_URL . "assets/leaflet/images/search-icon.svg" ?> width='14' heigth='14'></button>
                            </form>
                        </div>
                    </div>
                    <!-- The location range selector dropdown -->
                    <div class="location-section">
                        <h3>Distance Radius</h3>
                        <select name="distance" id="distance-select">
                        <option value="0.25" deafult>0.25 miles</option>
                        <option value="0.50">0.50 miles</option>
                        <option value="0.75">0.75 miles</option>
                        <option value="1.0">1.0 miles</option>
                     </select>
                    </div>
                    <!--- Cards for filter data in the map decade wise, category of art and Neighbour hood-->
                    <div id="filter-cards">
                        <ul>
                            <!-- Installation Date filter section -->
                            <li>
                                <input type="checkbox" class="hidden-checkbox" checked>
                                <i></i>
                                <h2>Installation date</h2>
                                <div id="date-facet-section" class="inner-section">
                                    <div class="list-item">
                                        <input type="checkbox" id="date-selectall" name="date-selectall" checked="" />
                                        <label for="date-selectall">(Select All)</label>
                                    </div>
                                </div>
                            </li>
                            <!-- Type of artwork filter section -->
                            <li>
                                <input type="checkbox" class="hidden-checkbox" checked>
                                <i></i>
                                <h2>Type of artwork</h2>
                                <div id="art-category-section" class="inner-section">
                                    <div class="list-item">
                                        <input type="checkbox" id="category-selectall" name="category-selectall" checked="" />
                                        <label for="category-selectall">(Select All)</label>
                                    </div>
                                </div>
                            </li>
                            <!-- Neighborhood filter section -->
                            <li>
                                <input type="checkbox" class="hidden-checkbox" checked>
                                <i></i>
                                <h2>Neighborhood</h2>
                                <div id="neighbourhood-category-section" class="inner-section">
                                    <div class="list-item">
                                        <input type="checkbox" id="neighborhood-selectall" name="neighborhood-selectall" checked="" />
                                        <label for="neighborhood-selectall">(Select All)</label>
                                    </div>
                                </div>
                            </li>
                            <!-- Material filter section -->
                            <li>
                                <input type="checkbox" class="hidden-checkbox" checked>
                                <i></i>
                                <h2>Material</h2>
                                <div id="material-category-section" class="inner-section">
                                    <div class="list-item">
                                        <input type="checkbox" id="material-selectall" name="material-selectall" checked="" />
                                        <label for="material-selectall">(Select All)</label>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main map div container -->
        <div id="map"></div>
   
						  <?php echo "map page test"; ?>
						</div>

					</main>
					<!-- #main -->
				</div>
				<!-- #primary -->
			</div>
			<!-- .row -->
		</div>
		<!-- .container -->
	</div>
	<!-- .quest-row -->
</div><!-- #content -->

<?php get_footer(); ?>