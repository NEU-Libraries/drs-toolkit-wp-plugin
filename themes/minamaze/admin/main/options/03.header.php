<?php
/**
 * Social media functions.
 *
 * @package ThinkUpThemes
 */

/* ----------------------------------------------------------------------------------
	HEADER STYLE
---------------------------------------------------------------------------------- */
function thinkup_input_headerstyle($classes) {

	$classes[] = 'header-style1';
	return $classes;
}
add_action( 'body_class', 'thinkup_input_headerstyle');


/* ----------------------------------------------------------------------------------
	SEARCH - DISABLE SEARCH
---------------------------------------------------------------------------------- */
function thinkup_input_headersearch() {
global $thinkup_header_searchswitch;

	if ( $thinkup_header_searchswitch == '1' ) {
		echo '<div id="pre-header-search">',
		     get_search_form(),
		     '</div>';
	}
}

/* ----------------------------------------------------------------------------------
	SOCIAL MEDIA - DISPLAY MESSAGE
---------------------------------------------------------------------------------- */

/* Message Settings */
function thinkup_input_socialmessage(){
global $thinkup_header_socialmessage;
global $thinkup_header_facebookswitch;
global $thinkup_header_twitterswitch;
global $thinkup_header_googleswitch;
global $thinkup_header_linkedinswitch;
global $thinkup_header_flickrswitch;
global $thinkup_header_youtubeswitch;
global $thinkup_header_rssswitch;
global $thinkup_header_diggswitch;

	if ( empty( $thinkup_header_facebookswitch ) and empty( $thinkup_header_twitterswitch ) and empty( $thinkup_header_googleswitch ) and empty( $thinkup_header_linkedinswitch ) and empty( $thinkup_header_flickrswitch ) and empty( $thinkup_header_youtubeswitch ) and empty( $thinkup_header_rssswitch ) and empty( $thinkup_header_diggswitch ) ) {	
		return '';
	} else if ( ! empty( $thinkup_header_socialmessage ) ) {	
		return esc_html( $thinkup_header_socialmessage );
	} else if ( empty( $thinkup_header_socialmessage ) ) {
		return '';
	}
}


/* ----------------------------------------------------------------------------------
	SOCIAL MEDIA - CUSTOM ICONS
---------------------------------------------------------------------------------- */

/* Facebook - Custom Icon */
function thinkup_input_facebookicon(){
global $thinkup_header_facebookiconswitch;
global $thinkup_header_facebookcustomicon;

	if ( $thinkup_header_facebookiconswitch == '1' and ! empty( $thinkup_header_facebookcustomicon ) ) {
		return '#pre-header-social .facebook a { background: url("' . $thinkup_header_facebookcustomicon . '") no-repeat center; background-size: 20px; }' . "\n";
	}
}

/* Twitter - Custom Icon */
function thinkup_input_twittericon(){
global $thinkup_header_twittericonswitch;
global $thinkup_header_twittercustomicon;

	if ( $thinkup_header_twittericonswitch == '1' and ! empty( $thinkup_header_twittercustomicon ) ) {
		return '#pre-header-social .twitter a { background: url("' . $thinkup_header_twittercustomicon . '") no-repeat center; background-size: 20px; }' . "\n";
	}
}

/* Google+ - Custom Icon */
function thinkup_input_googleicon(){
global $thinkup_header_googleiconswitch;
global $thinkup_header_googlecustomicon;

	if ( $thinkup_header_googleiconswitch == '1' and ! empty( $thinkup_header_googlecustomicon ) ) {
		return '#pre-header-social .google a { background: url("' . $thinkup_header_googlecustomicon . '") no-repeat center; background-size: 20px; }' . "\n";
	}
}

/* LinkedIn - Custom Icon */
function thinkup_input_linkedinicon(){
global $thinkup_header_linkediniconswitch;
global $thinkup_header_linkedincustomicon;

	if ( $thinkup_header_linkediniconswitch == '1' and ! empty( $thinkup_header_linkedincustomicon ) ) {
		return '#pre-header-social .linkedin a { background: url("' . $thinkup_header_linkedincustomicon . '") no-repeat center; background-size: 20px; }' . "\n";
	}
}

/* Flickr - Custom Icon */
function thinkup_input_flickricon(){
global $thinkup_header_flickriconswitch;
global $thinkup_header_flickrcustomicon;

	if ( $thinkup_header_flickriconswitch == '1' and ! empty( $thinkup_header_flickrcustomicon ) ) {
		return '#pre-header-social .flickr a { background: url("' . $thinkup_header_flickrcustomicon . '") no-repeat center; background-size: 20px; }' . "\n";
	}
}

/* YouTube - Custom Icon */
function thinkup_input_youtubeicon(){
global $thinkup_header_youtubeiconswitch;
global $thinkup_header_youtubecustomicon;

	if ( $thinkup_header_youtubeiconswitch == '1' and ! empty( $thinkup_header_youtubecustomicon ) ) {
		return '#pre-header-social .youtube a { background: url("' . $thinkup_header_youtubecustomicon . '") no-repeat center; background-size: 20px; }' . "\n";
	}
}

/* RSS - Custom Icon */
function thinkup_input_rssicon(){
global $thinkup_header_rssiconswitch;
global $thinkup_header_rsscustomicon;

	if ( $thinkup_header_rssiconswitch == '1' and ! empty( $thinkup_header_rsscustomicon ) ) {
		return '#pre-header-social .rss a { background: url("' . $thinkup_header_rsscustomicon . '") no-repeat center; background-size: 20px; }' . "\n";
	}
}

/* Input Custom Social Media Icons */
function thinkup_input_socialicon(){

	$output = NULL;

	$output .= thinkup_input_facebookicon();
	$output .= thinkup_input_twittericon();
	$output .= thinkup_input_googleicon();
	$output .= thinkup_input_linkedinicon();
	$output .= thinkup_input_flickricon();
	$output .= thinkup_input_youtubeicon();
	$output .= thinkup_input_rssicon();

	if ( ! empty( $output ) ) {
		echo    '<style type="text/css">' . "\n" . $output . '</style>';
	}
}
add_action( 'wp_head', 'thinkup_input_socialicon', 13 );


/* ----------------------------------------------------------------------------------
	SOCIAL MEDIA - OUTPUT SOCIAL MEDIA SELECTIONS
---------------------------------------------------------------------------------- */

function thinkup_input_socialmedia() {
global $thinkup_header_socialswitch;
global $thinkup_header_socialmessage;
global $thinkup_header_facebookswitch;
global $thinkup_header_facebooklink;
global $thinkup_header_twitterswitch;
global $thinkup_header_twitterlink;
global $thinkup_header_googleswitch;
global $thinkup_header_googlelink;
global $thinkup_header_linkedinswitch;
global $thinkup_header_linkedinlink;
global $thinkup_header_flickrswitch;
global $thinkup_header_flickrlink;
global $thinkup_header_youtubeswitch;
global $thinkup_header_youtubelink;
global $thinkup_header_rssswitch;
global $thinkup_header_rsslink;

	if ( $thinkup_header_socialswitch == '1' ) {

		echo '<div id="pre-header-social"><ul>';

			if ( ! empty ( $thinkup_header_socialmessage ) ) {
				echo '<li class="social message">' . thinkup_input_socialmessage() . '</li>';
			}

			/* Facebook settings */
			if ( $thinkup_header_facebookswitch == '1' ) {
				echo '<li class="social facebook"><a href="' . esc_url( $thinkup_header_facebooklink ) . '" data-tip="bottom" data-original-title="Facebook">',
					 '<i class="icon-facebook"></i>',
					 '</a></li>';
			}

			/* Twitter settings */
			if ( $thinkup_header_twitterswitch == '1' ) {
				echo '<li class="social twitter"><a href="' . esc_url( $thinkup_header_twitterlink ) . '" data-tip="bottom" data-original-title="Twitter">',
					 '<i class="icon-twitter"></i>',
					 '</a></li>';
			}

			/* Google+ settings */
			if ( $thinkup_header_googleswitch == '1' ) {
				echo '<li class="social google"><a href="' . esc_url( $thinkup_header_googlelink ) . '" data-tip="bottom" data-original-title="Google+">',
					 '<i class="icon-google-plus"></i>',
					 '</a></li>';
			}

			/* LinkedIn settings */
			if ( $thinkup_header_linkedinswitch == '1' ) {
				echo '<li class="social linkedin"><a href="' . esc_url( $thinkup_header_linkedinlink ) . '" data-tip="bottom" data-original-title="LinkedIn">',
					 '<i class="icon-linkedin"></i>',
					 '</a></li>';
			}

			/* Flickr settings */
			if ( $thinkup_header_flickrswitch == '1' ) {
				echo '<li class="social flickr"><a href="' . esc_url( $thinkup_header_flickrlink ) . '" data-tip="bottom" data-original-title="Flickr">',
					 '<i class="icon-flickr"></i>',
					 '</a></li>';
			}

			/* YouTube settings */
			if ( $thinkup_header_youtubeswitch == '1' ) {
				echo '<li class="social youtube"><a href="' . esc_url( $thinkup_header_youtubelink ) . '" data-tip="bottom" data-original-title="YouTube">',
					 '<i class="icon-youtube-play"></i>',
					 '</a></li>';
			}

			/* RSS settings */
			if ( $thinkup_header_rssswitch == '1' ) {
				echo '<li class="social rss"><a href="' . esc_url( $thinkup_header_rsslink ) . '" data-tip="bottom" data-original-title="RSS">',
					 '<i class="icon-rss"></i>',
					 '</a></li>';
			}

		echo	'</ul></div>';

	}
}


?>