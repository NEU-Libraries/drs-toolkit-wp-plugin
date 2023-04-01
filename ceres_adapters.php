<?php

/**
 * Tools here for connecting the broader WP/drs-tk environment to the 
 * semi-independent CERES code under /libraries.
 * 
 * Future of this approach is uncertain as of 2023-03-07 10:59:40
 * 
 * 
 * 
 */

use Ceres\ViewPackage\ViewPackage;

/* LOAD CERES */

require_once( plugin_dir_path( __FILE__ ) . '/libraries/Ceres/config/ceresSetup.php' );

/* REGISTER SCRIPTS AND STYLES USED BY PARTICULAR VIEW PACKAGES */

/* Registering them will make them available everywhere, but it looks
*  like I can enqueue them from within a shortcode handler, so I'll do 
*  it there based on need. 2023-03-07 17:58:38
*/

wp_register_script('ceres_js_setup' , plugins_url('/drs-tk/libraries/Ceres/config/ceresJsSetup.js'));
wp_enqueue_script('ceres_js_setup');
$ceresRootDir = CERES_ROOT_DIR;
wp_add_inline_script('ceres_js_setup',
	"var CERES_ROOT_URL = new URL(window.location.href).origin + '/Ceres';");


/* Leaflet and Leaflet plugins */
//@todo make enqueuing conditional
wp_register_script('ceres_leaflet', plugins_url('/libraries/Ceres/assets/js/leaflet/leaflet1.7.1.js', __FILE__));
wp_register_script('ceres_leaflet_wicket', plugins_url('/libraries/Ceres/assets/js/leaflet/brc/wicket-1.3.8.js', __FILE__));
wp_register_script('ceres_leaflet_markercluster', plugins_url('/libraries/Ceres/assets/js/leaflet/leaflet-js-markercluster/leaflet.markercluster.js', __FILE__));
wp_register_script('ceres_leaflet_bostonboundaries', plugins_url('/libraries/Ceres/assets/js/leaflet/brc/bostonboundaries.js', __FILE__));
wp_register_script('ceres_leaflet_mask', plugins_url('/libraries/Ceres/assets/js/leaflet/leafet-plugin-mask.js', __FILE__));
wp_register_script('ceres_leaflet_boundary-canvas', plugins_url('/libraries/Ceres/assets/js/leaflet/brc/leaflet-boundary-canvas.js', __FILE__));
wp_register_script('ceres_leaflet_geolet', plugins_url('/libraries/Ceres/assets/js/leaflet/leaflet-plugin-geolet.js', __FILE__));
wp_register_script('ceres_leaflet_fuse', plugins_url('/libraries/Ceres/assets/js/leaflet/fuse-leaflet-plugin-6-6-2.js', __FILE__));
wp_register_style('ceres_leaflet', plugins_url('/libraries/Ceres/assets/css/leaflet/leaflet.css', __FILE__));
wp_register_style('ceres_leaflet_brc-project', plugins_url('/libraries/Ceres/assets/css/leaflet/leaflet-brc-project.css', __FILE__));
wp_register_style('ceres_leaflet_markercluster', plugins_url('/libraries/Ceres/assets/css/leaflet/leaflet-js-markercluster/MarkerCluster.css', __FILE__));
wp_register_style('ceres_leaflet_markercluster_default', plugins_url('/libraries/Ceres/assets/css/leaflet/leaflet-js-markercluster/MarkerCluster.Default.css', __FILE__));


//@todo  make enqueueing conditional upon the need
wp_enqueue_script('ceres_leaflet');
wp_enqueue_script('ceres_leaflet_wicket');
wp_enqueue_script('ceres_leaflet_markercluster');
wp_enqueue_script('ceres_leaflet_bostonboundaries');
wp_enqueue_script('ceres_leaflet_mask');
wp_enqueue_script('ceres_leaflet_boundary-canvas');
wp_enqueue_script('ceres_leaflet_geolet');
wp_enqueue_script('ceres_leaflet_fuse');

wp_enqueue_style('ceres_leaflet');
wp_enqueue_style('ceres_leaflet_brc-project');
wp_enqueue_style('ceres_leaflet_markercluster');
wp_enqueue_style('ceres_leaflet_markercluster_default');





// /* SETUP SHORTCODES USED BY CERES */

add_shortcode('ceres_vp', 'ceres_vp_handler');
add_shortcode('ceres_renderer', 'ceres_renderer_handler');
// add_shortcode('ceres_test', 'ceres_test_handler');

/* DEFINE THE HANDLERS USED BY THE SHORTCODES */


function ceres_renderer_handler($atts) {
	$atts = shortcode_atts(
		array(
			'renderer' => '',
		),
		$atts,
		'ceres_renderer'		
	);
	$rendererName = $atts['renderer'];
	return "<h2>hi! I'll render from a $rendererName someday!</h2>";
}

function ceres_vp_handler($atts) {

    $atts = shortcode_atts(
		array(
			'vp_name' => '',
			//'renderer_tableClass' => '',
		),
		$atts,
		'ceres_vp'
	);

	//wp_enqueue_script('jquery-ui-sortable');
	$vp = new ViewPackage($atts['vp_name']);
	$vp->build();


    if ($atts['vp_name'] == 'tabular_wikibase_for_chinatown_people') {
        $vp->gatherData(null, CERES_ROOT_DIR . '/data/staticQueryResponses/wbPeopleResponse.json');
    } else {
        $vp->gatherData();
    }
	return $vp->render();

}

function ceres_test_handler($atts) {

    $atts = shortcode_atts(
		array(
			'test_val' => 'Just testing!',
		),
		$atts,
		'ceres_test'
    );

    return "<h3>" . $atts['test_val'] . "</h3>";
	


}