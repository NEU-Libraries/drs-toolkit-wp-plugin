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


/* LOAD CERES */

require_once( plugin_dir_path( __FILE__ ) . '/libraries/Ceres/config/ceresSetup.php' );

/* REGISTER SCRIPTS AND STYLES USED BY PARTICULAR VIEW PACKAGES */

/* Registering them will make them available everywhere, but it looks
*  like I can enqueue them from within a shortcode handler, so I'll do 
*  it there based on need. 2023-03-07 17:58:38
*/

$registeredScript = wp_register_script('ceres_test_js',
	plugin_dir_path( __FILE__ ) . 'libraries/Ceres/assets/js/test.js',
);

/* SETUP SHORTCODES USED BY CERES */

add_shortcode('ceres_vp', 'ceres_vp_handler');
add_shortcode('ceres_test', 'ceres_test_handler');

/* DEFINE THE HANDLERS USED BY THE SHORTCODES */


function ceres_vp_handler($atts) {

    $atts = shortcode_atts(
		array(
			'vp_name' => '',
			//'renderer_tableClass' => '',
		),
		$atts,
		'ceres_vp'
	);

	wp_enqueue_script('jquery-ui-sortable');
	$vp = new ViewPackage($atts['vp_name']);
	$vp->build();
	$vp->gatherData();
	return $vp->render();

}

function ceres_test_handler($atts) {
	wp_enqueue_script('ceres_test_js');
	wp_add_inline_script('ceres_test_js',
	"var testObject = {
		'a' : 'a hello',
		'b' : 'b bye'
		};"
	);
    $atts = shortcode_atts(
		array(
			'test_val' => 'Just testing!',
		),
		$atts,
		'ceres_test'
    );

    return "<h3>" . $atts['test_val'] . "</h3>";
	


}