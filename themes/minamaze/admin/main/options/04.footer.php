<?php
/**
 * Footer functions.
 *
 * @package ThinkUpThemes
 */

/* ----------------------------------------------------------------------------------
	FOOTER WIDGETS LAYOUT
---------------------------------------------------------------------------------- */

/* Assign function for widget area 1 */
function thinkup_input_footerw1() {
	echo	'<div id="footer-col1" class="widget-area">';
	if ( ! dynamic_sidebar( 'footer-w1' ) ) {
	echo	'<h3 class="widget-title">' . __( 'Please Add Widgets', 'lan-thinkupthemes') . '</h3>',
			'<div class="error-icon">',
			'<p>' . __( 'Remove this message by adding widgets to Footer Widget Area 1.', 'lan-thinkupthemes') . '</p>',
			'<a href="/wp-admin/widgets.php" title="No Widgets Selected">' . __( 'Click here to go to Widget area.', 'lan-thinkupthemes') . '</a>',
			'</div>';
	};
	echo	'</div>';
}

/* Assign function for widget area 2 */
function thinkup_input_footerw2() {
	echo	'<div id="footer-col2" class="widget-area">';
	if ( ! dynamic_sidebar( 'footer-w2' ) ) {
	echo	'<h3 class="widget-title">' . __( 'Please Add Widgets', 'lan-thinkupthemes') . '</h3>',
			'<div class="error-icon">',
			'<p>' . __( 'Remove this message by adding widgets to Footer Widget Area 2.', 'lan-thinkupthemes') . '</p>',
			'<a href="/wp-admin/widgets.php" title="No Widgets Selected">' . __( 'Click here to go to Widget area.', 'lan-thinkupthemes') . '</a>',
			'</div>';
	};
	echo	'</div>';
}

/* Assign function for widget area 3 */
function thinkup_input_footerw3() {
	echo	'<div id="footer-col3" class="widget-area">';
	if ( ! dynamic_sidebar( 'footer-w3' ) ) {
	echo	'<h3 class="widget-title">' . __( 'Please Add Widgets', 'lan-thinkupthemes') . '</h3>',
			'<div class="error-icon">',
			'<p>' . __( 'Remove this message by adding widgets to Footer Widget Area 3.', 'lan-thinkupthemes') . '</p>',
			'<a href="/wp-admin/widgets.php" title="No Widgets Selected">' . __( 'Click here to go to Widget area.', 'lan-thinkupthemes') . '</a>',
			'</div>';
	};	
	echo	'</div>';
}

/* Assign function for widget area 4 */
function thinkup_input_footerw4() {
	echo	'<div id="footer-col4" class="widget-area">';
	if ( ! dynamic_sidebar( 'footer-w4' ) ) {
	echo	'<h3 class="widget-title">' . __( 'Please Add Widgets', 'lan-thinkupthemes') . '</h3>',
			'<div class="error-icon">',
			'<p>' . __( 'Remove this message by adding widgets to Footer Widget Area 4.', 'lan-thinkupthemes') . '</p>',
			'<a href="/wp-admin/widgets.php" title="No Widgets Selected">' . __( 'Click here to go to Widget area.', 'lan-thinkupthemes') . '</a>',
			'</div>';
	};	
	echo	'</div>';
}

/* Assign function for widget area 5 */
function thinkup_input_footerw5() {
	echo	'<div id="footer-col5" class="widget-area">';
	if ( ! dynamic_sidebar( 'footer-w5' ) ) {
	echo	'<h3 class="widget-title">' . __( 'Please Add Widgets', 'lan-thinkupthemes') . '</h3>',
			'<div class="error-icon">',
			'<p>' . __( 'Remove this message by adding widgets to Footer Widget Area 5.', 'lan-thinkupthemes') . '</p>',
			'<a href="/wp-admin/widgets.php" title="No Widgets Selected">' . __( 'Click here to go to Widget area.', 'lan-thinkupthemes') . '</a>',
			'</div>';
	};	
	echo	'</div>';
}

/* Assign function for widget area 6 */
function thinkup_input_footerw6() {
	echo	'<div id="footer-col6" class="widget-area">';
	if ( ! dynamic_sidebar( 'footer-w6' ) ) {
	echo	'<h3 class="widget-title">' . __( 'Please Add Widgets', 'lan-thinkupthemes') . '</h3>',
			'<div class="error-icon">',
			'<p>' . __( 'Remove this message by adding widgets to Footer Widget Area 6.', 'lan-thinkupthemes') . '</p>',
			'<a href="/wp-admin/widgets.php" title="No Widgets Selected">' . __( 'Click here to go to Widget area.', 'lan-thinkupthemes') . '</a>',
			'</div>';
	};	
	echo	'</div>';
}


/* Add Custom Footer Layout */
function thinkup_input_footerlayout() {	
global $thinkup_footer_layout;
global $thinkup_footer_widgetswitch;
global $footer_w1;
global $footer_w2;
global $footer_w3;
global $footer_w4;
global $footer_w5;
global $footer_w6;
					
	if ( $thinkup_footer_widgetswitch !== "1" and ! empty( $thinkup_footer_layout )  ) {
		echo	'<div id="footer">';
			if ( $thinkup_footer_layout == "option1" ) {
				echo	'<div id="footer-core" class="option1">';
						thinkup_input_footerw1();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option2" ) {
				echo	'<div id="footer-core" class="option2">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option3" ) {
				echo	'<div id="footer-core" class="option3">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
						thinkup_input_footerw3();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option4" ) {
				echo	'<div id="footer-core" class="option4">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
						thinkup_input_footerw3();
						thinkup_input_footerw4();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option5" ) {
				echo	'<div id="footer-core" class="option5">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
						thinkup_input_footerw3();
						thinkup_input_footerw4();
						thinkup_input_footerw5();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option6" ) {
				echo	'<div id="footer-core" class="option6">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
						thinkup_input_footerw3();
						thinkup_input_footerw4();
						thinkup_input_footerw5();
						thinkup_input_footerw6();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option7" ) {
				echo	'<div id="footer-core" class="option7">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option8" ) {
				echo	'<div id="footer-core" class="option8">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option9" ) {
				echo	'<div id="footer-core" class="option9">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option10" ) {
				echo	'<div id="footer-core" class="option10">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option11" ) {
				echo	'<div id="footer-core" class="option11">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option12" ) {
				echo	'<div id="footer-core" class="option12">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option13" ) {
				echo	'<div id="footer-core" class="option13">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
						thinkup_input_footerw3();
						thinkup_input_footerw4();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option14" ) {
				echo	'<div id="footer-core" class="option14">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
						thinkup_input_footerw3();
						thinkup_input_footerw4();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option15" ) {
				echo	'<div id="footer-core" class="option15">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
						thinkup_input_footerw3();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option16" ) {
				echo	'<div id="footer-core" class="option16">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
						thinkup_input_footerw3();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option17" ) {
				echo	'<div id="footer-core" class="option17">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
						thinkup_input_footerw3();
						thinkup_input_footerw4();
						thinkup_input_footerw5();
				echo	'</div>';
			} else if ( $thinkup_footer_layout == "option18" ) {
				echo	'<div id="footer-core" class="option18">';
						thinkup_input_footerw1();
						thinkup_input_footerw2();
						thinkup_input_footerw3();
						thinkup_input_footerw4();
						thinkup_input_footerw5();

				echo	'</div>';
			}
		echo	'</div>';
	}
}


/* ----------------------------------------------------------------------------------
	COPYRIGHT TEXT
---------------------------------------------------------------------------------- */

function thinkup_input_copyright() {
global $thinkup_footer_copyright;

	printf( __( 'Developed by %1$s. Powered by %2$s.', 'lan-thinkupthemes' ) , '<a href="//www.thinkupthemes.com/" target="_blank">Think Up Themes Ltd</a>', '<a href="//www.wordpress.org/" target="_blank">Wordpress</a>'); 
}


?>