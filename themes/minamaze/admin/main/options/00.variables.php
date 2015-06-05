<?php
/**
 * Theme setup functions.
 *
 * @package ThinkUpThemes
 */


/* ----------------------------------------------------------------------------------
	ADD CUSTOM VARIABLES
---------------------------------------------------------------------------------- */

/* Add global variables used in Redux framework */
function thinkup_reduxvariables() { 

	// Fetch options stored in $data.
	global $redux;

	//  1.1.     General settings.
	$GLOBALS['thinkup_general_logoswitch']                  = thinkup_var ( 'thinkup_general_logoswitch' );
	$GLOBALS['thinkup_general_logolink']                    = thinkup_var ( 'thinkup_general_logolink', 'url' );
	$GLOBALS['thinkup_general_logolinkretina']              = thinkup_var ( 'thinkup_general_logolinkretina', 'url' );
	$GLOBALS['thinkup_general_sitetitle']                   = thinkup_var ( 'thinkup_general_sitetitle' );
	$GLOBALS['thinkup_general_sitedescription']             = thinkup_var ( 'thinkup_general_sitedescription' );
	$GLOBALS['thinkup_general_faviconlink']                 = thinkup_var ( 'thinkup_general_faviconlink', 'url' );
	$GLOBALS['thinkup_general_layout']                      = thinkup_var ( 'thinkup_general_layout' );
	$GLOBALS['thinkup_general_sidebars']                    = thinkup_var ( 'thinkup_general_sidebars' );
	$GLOBALS['thinkup_general_fixedlayoutswitch']           = thinkup_var ( 'thinkup_general_fixedlayoutswitch' );
	$GLOBALS['thinkup_general_breadcrumbswitch']            = thinkup_var ( 'thinkup_general_breadcrumbswitch' );
	$GLOBALS['thinkup_general_breadcrumbdelimeter']         = thinkup_var ( 'thinkup_general_breadcrumbdelimeter' );
	$GLOBALS['thinkup_general_customcss']                   = thinkup_var ( 'thinkup_general_customcss' );
	$GLOBALS['thinkup_general_customjavafront']             = thinkup_var ( 'thinkup_general_customjavafront' );

	//  1.2.     Homepage.
	$GLOBALS['thinkup_homepage_layout']                     = thinkup_var ( 'thinkup_homepage_layout' );
	$GLOBALS['thinkup_homepage_sidebars']                   = thinkup_var ( 'thinkup_homepage_sidebars' );
	$GLOBALS['thinkup_homepage_sliderswitch']               = thinkup_var ( 'thinkup_homepage_sliderswitch' );
	$GLOBALS['thinkup_homepage_slidername']                 = thinkup_var ( 'thinkup_homepage_slidername' );
	$GLOBALS['thinkup_homepage_sliderpreset']               = thinkup_var ( 'thinkup_homepage_sliderpreset' );
	$GLOBALS['thinkup_homepage_sliderpresetwidth']          = thinkup_var ( 'thinkup_homepage_sliderpresetwidth' );
	$GLOBALS['thinkup_homepage_sliderpresetheight']         = thinkup_var ( 'thinkup_homepage_sliderpresetheight' );
	$GLOBALS['thinkup_homepage_sectionswitch']              = thinkup_var ( 'thinkup_homepage_sectionswitch' );
	$GLOBALS['thinkup_homepage_section1_image']             = thinkup_var ( 'thinkup_homepage_section1_image', 'id' );
	$GLOBALS['thinkup_homepage_section1_title']             = thinkup_var ( 'thinkup_homepage_section1_title' );
	$GLOBALS['thinkup_homepage_section1_desc']              = thinkup_var ( 'thinkup_homepage_section1_desc' );
	$GLOBALS['thinkup_homepage_section1_link']              = thinkup_var ( 'thinkup_homepage_section1_link' );
	$GLOBALS['thinkup_homepage_section2_image']             = thinkup_var ( 'thinkup_homepage_section2_image', 'id' );
	$GLOBALS['thinkup_homepage_section2_title']             = thinkup_var ( 'thinkup_homepage_section2_title' );
	$GLOBALS['thinkup_homepage_section2_desc']              = thinkup_var ( 'thinkup_homepage_section2_desc' );	
	$GLOBALS['thinkup_homepage_section2_link']              = thinkup_var ( 'thinkup_homepage_section2_link' );
	$GLOBALS['thinkup_homepage_section3_image']             = thinkup_var ( 'thinkup_homepage_section3_image', 'id' );
	$GLOBALS['thinkup_homepage_section3_title']             = thinkup_var ( 'thinkup_homepage_section3_title' );
	$GLOBALS['thinkup_homepage_section3_desc']              = thinkup_var ( 'thinkup_homepage_section3_desc' );	
	$GLOBALS['thinkup_homepage_section3_link']              = thinkup_var ( 'thinkup_homepage_section3_link' );
	$GLOBALS['thinkup_homepage_introswitch']                = thinkup_var ( 'thinkup_homepage_introswitch' );
	$GLOBALS['thinkup_homepage_introaction']                = thinkup_var ( 'thinkup_homepage_introaction' );
	$GLOBALS['thinkup_homepage_introactionteaser']          = thinkup_var ( 'thinkup_homepage_introactionteaser' );
	$GLOBALS['thinkup_homepage_introactionbutton']          = thinkup_var ( 'thinkup_homepage_introactionbutton' );
	$GLOBALS['thinkup_homepage_introactionlink']            = thinkup_var ( 'thinkup_homepage_introactionlink' );
	$GLOBALS['thinkup_homepage_introactionpage']            = thinkup_var ( 'thinkup_homepage_introactionpage' );
	$GLOBALS['thinkup_homepage_introactioncustom']          = thinkup_var ( 'thinkup_homepage_introactioncustom' );

	//  1.3.     Header
	$GLOBALS['thinkup_header_searchswitch']                 = thinkup_var ( 'thinkup_header_searchswitch' );
	$GLOBALS['thinkup_header_socialswitch']                 = thinkup_var ( 'thinkup_header_socialswitch' );
	$GLOBALS['thinkup_header_socialmessage']                = thinkup_var ( 'thinkup_header_socialmessage' );
	$GLOBALS['thinkup_header_facebookswitch']               = thinkup_var ( 'thinkup_header_facebookswitch' );
	$GLOBALS['thinkup_header_facebooklink']                 = thinkup_var ( 'thinkup_header_facebooklink' );
	$GLOBALS['thinkup_header_facebookiconswitch']           = thinkup_var ( 'thinkup_header_facebookiconswitch' );
	$GLOBALS['thinkup_header_facebookcustomicon']           = thinkup_var ( 'thinkup_header_facebookcustomicon', 'url' );
	$GLOBALS['thinkup_header_twitterswitch']                = thinkup_var ( 'thinkup_header_twitterswitch' );
	$GLOBALS['thinkup_header_twitterlink']                  = thinkup_var ( 'thinkup_header_twitterlink' );
	$GLOBALS['thinkup_header_twittericonswitch']            = thinkup_var ( 'thinkup_header_twittericonswitch' );
	$GLOBALS['thinkup_header_twittercustomicon']            = thinkup_var ( 'thinkup_header_twittercustomicon', 'url' );
	$GLOBALS['thinkup_header_googleswitch']                 = thinkup_var ( 'thinkup_header_googleswitch' );
	$GLOBALS['thinkup_header_googlelink']                   = thinkup_var ( 'thinkup_header_googlelink' );
	$GLOBALS['thinkup_header_googleiconswitch']             = thinkup_var ( 'thinkup_header_googleiconswitch' );
	$GLOBALS['thinkup_header_googlecustomicon']             = thinkup_var ( 'thinkup_header_googlecustomicon', 'url' );
	$GLOBALS['thinkup_header_linkedinswitch']               = thinkup_var ( 'thinkup_header_linkedinswitch' );
	$GLOBALS['thinkup_header_linkedinlink']                 = thinkup_var ( 'thinkup_header_linkedinlink' );
	$GLOBALS['thinkup_header_linkediniconswitch']           = thinkup_var ( 'thinkup_header_linkediniconswitch' );
	$GLOBALS['thinkup_header_linkedincustomicon']           = thinkup_var ( 'thinkup_header_linkedincustomicon', 'url' );
	$GLOBALS['thinkup_header_flickrswitch']                 = thinkup_var ( 'thinkup_header_flickrswitch' );
	$GLOBALS['thinkup_header_flickrlink']                   = thinkup_var ( 'thinkup_header_flickrlink' );
	$GLOBALS['thinkup_header_flickriconswitch']             = thinkup_var ( 'thinkup_header_flickriconswitch' );
	$GLOBALS['thinkup_header_flickrcustomicon']             = thinkup_var ( 'thinkup_header_flickrcustomicon', 'url' );
	$GLOBALS['thinkup_header_youtubeswitch']                = thinkup_var ( 'thinkup_header_youtubeswitch' );
	$GLOBALS['thinkup_header_youtubelink']                  = thinkup_var ( 'thinkup_header_youtubelink' );
	$GLOBALS['thinkup_header_youtubeiconswitch']            = thinkup_var ( 'thinkup_header_youtubeiconswitch' );
	$GLOBALS['thinkup_header_youtubecustomicon']            = thinkup_var ( 'thinkup_header_youtubecustomicon', 'url' );
	$GLOBALS['thinkup_header_rssswitch']                    = thinkup_var ( 'thinkup_header_rssswitch' );
	$GLOBALS['thinkup_header_rsslink']                      = thinkup_var ( 'thinkup_header_rsslink' );
	$GLOBALS['thinkup_header_rssiconswitch']                = thinkup_var ( 'thinkup_header_rssiconswitch' );
	$GLOBALS['thinkup_header_rsscustomicon']                = thinkup_var ( 'thinkup_header_rsscustomicon', 'url' );

	//  1.4.     Footer.
	$GLOBALS['thinkup_footer_layout']                       = thinkup_var ( 'thinkup_footer_layout' );
	$GLOBALS['thinkup_footer_widgetswitch']                 = thinkup_var ( 'thinkup_footer_widgetswitch' );

	//  1.5.1.   Blog - Main page.
	$GLOBALS['thinkup_blog_layout']                         = thinkup_var ( 'thinkup_blog_layout' );
	$GLOBALS['thinkup_blog_sidebars']                       = thinkup_var ( 'thinkup_blog_sidebars' );
	$GLOBALS['thinkup_blog_postswitch']                     = thinkup_var ( 'thinkup_blog_postswitch' );

	//  1.5.2.   Blog - Single post.
	$GLOBALS['thinkup_post_layout']                         = thinkup_var ( 'thinkup_post_layout' );
	$GLOBALS['thinkup_post_sidebars']                       = thinkup_var ( 'thinkup_post_sidebars' );

	//  1.6.     Portfolio.

	//  1.7.     Contact Page.

	//  1.8.     Special pages.

	//  1.9.     Notification bar.

	//  1.10.     Search engine optimisation.

	//  1.11.     Typography.

	//  1.12.     Custom styling.

	//  1.13.     Support.
}
add_action( 'thinkup_hook_header', 'thinkup_reduxvariables' );

?>