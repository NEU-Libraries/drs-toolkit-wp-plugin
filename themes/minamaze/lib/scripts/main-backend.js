/**
 * Wordpress Admin Area Enhancements.
 *
 * Theme options are hidden / shown so the user only see's what is required.
 */

/* ----------------------------------------------------------------------------------
	ADD CLASSES TO MAIN THEME OPTIONS
---------------------------------------------------------------------------------- */
jQuery(document).ready(function(){
	jQuery( 'td fieldset' ).each(function() {
		var mainclass = jQuery(this).attr("id");
		jQuery('fieldset[id='+mainclass+']').closest("tr").attr('id', 'section-' + mainclass );
	});

	// Specifically to add id to homepage slider options.
	jQuery( '#redux-slides-accordion' ).closest("tr").attr('id', 'section-thinkup_homepage_sliderpreset' );
	jQuery( '#redux-slides-accordion' ).closest("tr").attr('id', 'section-thinkup_homepage_sliderpreset' );
	jQuery( '#section-thinkup_homepage_sliderpresetwidth' ).next('tr').attr( 'id', 'section-thinkup_homepage_sliderpresetheight' );
});


/* ----------------------------------------------------------------------------------
	ADD CLASSES TO META THEME OPTIONS
---------------------------------------------------------------------------------- */
jQuery(document).ready(function($){
	$( 'th label' ).each(function() {
		var label = $(this),
		metaclass = label.attr( 'for' );
		if ( metaclass !== '' && metaclass !== undefined ) {
			label.closest( 'tr' ).addClass( metaclass );
		}
	});
});


/* ----------------------------------------------------------------------------------
	HIDE / SHOW PORTFOLIO OPTIONS PANEL (PAGE POST TYPE)
---------------------------------------------------------------------------------- */

jQuery(document).ready(function(){

	// Hide / show portfolio options panel on page load
	if ( jQuery( '#page_template option:selected' ).attr( 'value' ) == 'template-portfolio.php' ) {
		jQuery( '#thinkup_portfolioinfo' ).slideDown();
	} else if ( jQuery( '#page_template option:selected' ).attr( 'value' ) != 'template-portfolio.php' ) {
		jQuery( '#thinkup_portfolioinfo' ).slideUp();
	}

	jQuery( '#page_template' ).change( function() {

		// Hide / show portfolio options panel when template option is changed
		if ( jQuery( '#page_template option:selected' ).attr( 'value' ) == 'template-portfolio.php' ) {
			jQuery( '#thinkup_portfolioinfo' ).slideDown();
		} else if ( jQuery( '#page_template option:selected' ).attr( 'value' ) != 'template-portfolio.php' ) {
			jQuery( '#thinkup_portfolioinfo' ).slideUp();
		}
	});
});


/* ----------------------------------------------------------------------------------
	HIDE / SHOW OPTIONS ON PAGE LOAD
---------------------------------------------------------------------------------- */
jQuery(document).ready(function(){

	// General - Logo Settings (Option 1) - DONE 
	if(jQuery('#section-thinkup_general_logoswitch input[value=option1]').is(":checked")){
		jQuery('#section-thinkup_general_logolink').show();
		jQuery('#section-thinkup_general_logolinkretina').show();
	}
	else if(jQuery('#section-thinkup_general_logoswitch input[value=option1]').not(":checked")){
		jQuery('#section-thinkup_general_logolink').hide();
		jQuery('#section-thinkup_general_logolinkretina').hide();
	}

	// General - Logo Settings (Option 2) - DONE
	if(jQuery('#section-thinkup_general_logoswitch input[value=option2]').is(":checked")){
		jQuery('#section-thinkup_general_sitetitle').show();
		jQuery('#section-thinkup_general_sitedescription').show();
	}
	else if(jQuery('#section-thinkup_general_logoswitch input[value=option2]').not(":checked")){
		jQuery('#section-thinkup_general_sitetitle').hide();
		jQuery('#section-thinkup_general_sitedescription').hide();
	}

	// === Select sidebar for Page Layout - DONE
	if( jQuery('#section-thinkup_general_layout input[value=option2]').is(":checked") || jQuery('#section-thinkup_general_layout input[value=option3]').is(":checked") ){
		jQuery('#section-thinkup_general_sidebars').show();
	}
	else if(jQuery('#section-thinkup_general_layout input[value=option2]').not(":checked") || jQuery('#section-thinkup_general_layout input[value=option3]').not(":checked") ){
		jQuery('#section-thinkup_general_sidebars').hide();
	}

	// Select sidebar for Homepage Layout  - DONE
	if( jQuery('#section-thinkup_homepage_layout input[value=option2]').is(":checked") || jQuery('#section-thinkup_homepage_layout input[value=option3]').is(":checked") ){
		jQuery('#section-thinkup_homepage_sidebars').show();
	}
	else if(jQuery('#section-thinkup_homepage_layout input[value=option2]').not(":checked") || jQuery('#section-thinkup_homepage_layout input[value=option3]').not(":checked") ){
		jQuery('#section-thinkup_homepage_sidebars').hide();
	}

	// Select sidebar for Blog Layout - DONE
	if( jQuery('#section-thinkup_blog_layout input[value=option2]').is(":checked") || jQuery('#section-thinkup_blog_layout input[value=option3]').is(":checked") ){
		jQuery('#section-thinkup_blog_sidebars').show();
	}
	else if(jQuery('#section-thinkup_blog_layout input[value=option2]').not(":checked") || jQuery('#section-thinkup_blog_layout input[value=option3]').not(":checked") ){
		jQuery('#section-thinkup_blog_sidebars').hide();
	}

	// Select sidebar for Post Layout - DONE
	if( jQuery('#section-thinkup_post_layout input[value=option2]').is(":checked") || jQuery('#section-thinkup_post_layout input[value=option3]').is(":checked") ){
		jQuery('#section-thinkup_post_sidebars').show();
	}
	else if(jQuery('#section-thinkup_post_layout input[value=option2]').not(":checked") || jQuery('#section-thinkup_post_layout input[value=option3]').not(":checked") ){
		jQuery('#section-thinkup_post_sidebars').hide();
	}

	// Select sidebar for Portfolio Layout DONE
	if( jQuery('#section-thinkup_portfolio_layout input[value=option5]').is(":checked") || jQuery('#section-thinkup_portfolio_layout input[value=option6]').is(":checked") || jQuery('#section-thinkup_portfolio_layout input[value=option7]').is(":checked") || jQuery('#section-thinkup_portfolio_layout input[value=option8]').is(":checked") ){
		jQuery('#section-thinkup_portfolio_sidebars').show();
	}
	else if(jQuery('#section-thinkup_portfolio_layout input[value=option5]').not(":checked") || jQuery('#section-thinkup_portfolio_layout input[value=option6]').not(":checked") || jQuery('#section-thinkup_portfolio_layout input[value=option7]').not(":checked") || jQuery('#section-thinkup_portfolio_layout input[value=option8]').not(":checked") ){
		jQuery('#section-thinkup_portfolio_sidebars').hide();
	}

	// Select sidebar for Project Layout - DONE
	if( jQuery('#section-thinkup_project_layout input[value=option2]').is(":checked") || jQuery('#section-thinkup_project_layout input[value=option3]').is(":checked") ){
		jQuery('#section-thinkup_project_sidebars').show();
	}
	else if(jQuery('#section-thinkup_project_layout input[value=option2]').not(":checked") || jQuery('#section-thinkup_project_layout input[value=option3]').not(":checked") ){
		jQuery('#section-thinkup_project_sidebars').hide();
	}

	// Homepage - Enable Homepage Blog - DONE
	if(jQuery('#section-thinkup_homepage_blog input').is(":checked")){
		jQuery('#section-thinkup_homepage_addtext').hide();
		jQuery('#section-thinkup_homepage_addtextparagraph').hide();
		jQuery('#section-thinkup_homepage_addpage').hide();
	}
	else if(jQuery('#section-thinkup_homepage_blog input').not(":checked")){
		jQuery('#section-thinkup_homepage_addtext').show();
		jQuery('#section-thinkup_homepage_addtextparagraph').show();
		jQuery('#section-thinkup_homepage_addpage').show();
	}

	// Homepage - Enable Slider
	if(jQuery('#thinkup_homepage_sliderswitch-buttonsetoption1').is(":checked")){
		jQuery('#section-thinkup_homepage_sliderpreset').show();
		jQuery('#section-thinkup_homepage_sliderpresetwidth').show();
		jQuery('#section-thinkup_homepage_sliderpresetheight').show();
	}
	else if(jQuery('#thinkup_homepage_sliderswitch-buttonsetoption1').not(":checked")){
		jQuery('#section-thinkup_homepage_sliderpreset').hide();
		jQuery('#section-thinkup_homepage_sliderpresetwidth').hide();
		jQuery('#section-thinkup_homepage_sliderpresetheight').hide();
	}
	if(jQuery('#thinkup_homepage_sliderswitch-buttonsetoption2').is(":checked")){
		jQuery('#section-thinkup_homepage_slidername').show();
	}
	else if(jQuery('#thinkup_homepage_sliderswitch-buttonsetoption2').not(":checked")){
		jQuery('#section-thinkup_homepage_slidername').hide();
	}	

	// Homepage - Call To Action Intro Link (Option 1) - DONE
	if(jQuery('#section-thinkup_homepage_introactionlink input[value=option1]').is(":checked")){
		jQuery('#section-thinkup_homepage_introactionpage').show();
	}
	else if(jQuery('#section-thinkup_homepage_introactionlink input[value=option1]').not(":checked")){
		jQuery('#section-thinkup_homepage_introactionpage').hide();
	}

	// Homepage - Call To Action Intro Link (Option 2) - DONE
	if(jQuery('#section-thinkup_homepage_introactionlink input[value=option2]').is(":checked")){
		jQuery('#section-thinkup_homepage_introactioncustom').show();
	}
	else if(jQuery('#section-thinkup_homepage_introactionlink input[value=option2]').not(":checked")){
		jQuery('#section-thinkup_homepage_introactioncustom').hide();
	}

	// Homepage - Call To Action Outro Link (Option 1) - DONE
	if(jQuery('#section-thinkup_homepage_outroactionlink input[value=option1]').is(":checked")){
		jQuery('#section-thinkup_homepage_outroactionpage').show();
	}
	else if(jQuery('#section-thinkup_homepage_outroactionlink input[value=option1]').not(":checked")){
		jQuery('#section-thinkup_homepage_outroactionpage').hide();
	}

	// Homepage - Call To Action Outro Link (Option 2) - DONE
	if(jQuery('#section-thinkup_homepage_outroactionlink input[value=option2]').is(":checked")){
		jQuery('#section-thinkup_homepage_outroactioncustom').show();
	}
	else if(jQuery('#section-thinkup_homepage_outroactionlink input[value=option2]').not(":checked")){
		jQuery('#section-thinkup_homepage_outroactioncustom').hide();
	}

	// Footer - Call To Action Outro Link (Option 1) - DONE
	if(jQuery('#section-thinkup_footer_outroactionlink input[value=option1]').is(":checked")){
		jQuery('#section-thinkup_footer_outroactionpage').show();
	}
	else if(jQuery('#section-thinkup_footer_outroactionlink input[value=option1]').not(":checked")){
		jQuery('#section-thinkup_footer_outroactionpage').hide();
	}

	// Footer - Call To Action Outro Link (Option 2) - DONE
	if(jQuery('#section-thinkup_footer_outroactionlink input[value=option2]').is(":checked")){
		jQuery('#section-thinkup_footer_outroactioncustom').show();
	}
	else if(jQuery('#section-thinkup_footer_outroactionlink input[value=option2]').not(":checked")){
		jQuery('#section-thinkup_footer_outroactioncustom').hide();
	}

	// Notification Bar - Add Button Link (Option 1) - DONE
	if(jQuery('#section-thinkup_notification_link input[value=option1]').is(":checked")){
		jQuery('#section-thinkup_notification_linkpage').show('slow');
	}
	else if(jQuery('#section-thinkup_notification_link input[value=option1]').not(":checked")){
		jQuery('#section-thinkup_notification_linkpage').hide('slow');
	}

	// Notification Bar - Add Button Link (Option 2) - DONE
	if(jQuery('#section-thinkup_notification_link input[value=option2]').is(":checked")){
		jQuery('#section-thinkup_notification_linkcustom').show('slow');
	}
	else if(jQuery('#section-thinkup_notification_link input[value=option2]').not(":checked")){
		jQuery('#section-thinkup_notification_linkcustom').hide('slow');
	}

	// Portfolio - Portfolio Style - DONE
	if(jQuery('#section-thinkup_portfolio_style input[value=option1]').not(":checked") && jQuery('#section-thinkup_portfolio_style input[value=option2]').not(":checked")){
		jQuery('#section-thinkup_portfolio_hovercheck').hide();
		jQuery('#section-thinkup_portfolio_textcheck').hide();
	}
	if(jQuery('#section-thinkup_portfolio_style input[value=option1]').is(":checked")){
		jQuery('#section-thinkup_portfolio_hovercheck').show();
	} else if(jQuery('#section-thinkup_portfolio_style input[value=option1]').not(":checked")){
		jQuery('#section-thinkup_portfolio_hovercheck').hide();
	}
	if(jQuery('#section-thinkup_portfolio_style input[value=option2]').is(":checked")){
		jQuery('#section-thinkup_portfolio_textcheck').hide();
	} else if(jQuery('#section-thinkup_portfolio_style input[value=option2]').not(":checked")){
		jQuery('#section-thinkup_portfolio_textcheck').hide();
	}

	// Typography - Body Font (Font Family) - DONE
	if(jQuery('#section-thinkup_font_bodyswitch input').is(":checked")){
		jQuery('#section-thinkup_font_bodystandard').hide();
		jQuery('#section-thinkup_font_bodygoogle').show();
	}
	else if(jQuery('#section-thinkup_font_bodyswitch input').not(":checked")){
		jQuery('#section-thinkup_font_bodystandard').show();
		jQuery('#section-thinkup_font_bodygoogle').hide();
	}

	// Typography - Body Headings (Font Family) - DONE
	if(jQuery('#section-thinkup_font_bodyheadingswitch input').is(":checked")){
		jQuery('#section-thinkup_font_bodyheadingstandard').hide();
		jQuery('#section-thinkup_font_bodyheadinggoogle').show();
	}
	else if(jQuery('#section-thinkup_font_bodyheadingswitch input').not(":checked")){
		jQuery('#section-thinkup_font_bodyheadingstandard').show();
		jQuery('#section-thinkup_font_bodyheadinggoogle').hide();
	}

	// Typography - Footer Headings (Font Family) - DONE
	if(jQuery('#section-thinkup_font_footerheadingswitch input').is(":checked")){
		jQuery('#section-thinkup_font_footerheadingstandard').hide();
		jQuery('#section-thinkup_font_footerheadinggoogle').show();
	}
	else if(jQuery('#section-thinkup_font_footerheadingswitch input').not(":checked")){
		jQuery('#section-thinkup_font_footerheadingstandard').show();
		jQuery('#section-thinkup_font_footerheadinggoogle').hide();
	}

	// Typography - Pre Header Menu (Font Family) - DONE
	if(jQuery('#section-thinkup_font_preheaderswitch input').is(":checked")){
		jQuery('#section-thinkup_font_preheaderstandard').hide();
		jQuery('#section-thinkup_font_preheadergoogle').show();
	}
	else if(jQuery('#section-thinkup_font_preheaderswitch input').not(":checked")){
		jQuery('#section-thinkup_font_preheaderstandard').show();
		jQuery('#section-thinkup_font_preheadergoogle').hide();
	}

	// Typography - Main Header Menu (Font Family) - DONE
	if(jQuery('#section-thinkup_font_mainheaderswitch input').is(":checked")){
		jQuery('#section-thinkup_font_mainheaderstandard').hide();
		jQuery('#section-thinkup_font_mainheadergoogle').show();
	}
	else if(jQuery('#section-thinkup_font_mainheaderswitch input').not(":checked")){
		jQuery('#section-thinkup_font_mainheaderstandard').show();
		jQuery('#section-thinkup_font_mainheadergoogle').hide();
	}

	// Typography - Main Footer Menu (Font Family) - DONE
	if(jQuery('#section-thinkup_font_mainfooterswitch input').is(":checked")){
		jQuery('#section-thinkup_font_mainfooterstandard').hide();
		jQuery('#section-thinkup_font_mainfootergoogle').show();
	}
	else if(jQuery('#section-thinkup_font_mainfooterswitch input').not(":checked")){
		jQuery('#section-thinkup_font_mainfooterstandard').show();
		jQuery('#section-thinkup_font_mainfootergoogle').hide();
	}

	// Typography - Post Footer Menu (Font Family) - DONE
	if(jQuery('#section-thinkup_font_postfooterswitch input').is(":checked")){
		jQuery('#section-thinkup_font_postfooterstandard').hide();
		jQuery('#section-thinkup_font_postfootergoogle').show();
	}
	else if(jQuery('#section-thinkup_font_postfooterswitch input').not(":checked")){
		jQuery('#section-thinkup_font_postfooterstandard').show();
		jQuery('#section-thinkup_font_postfootergoogle').hide();
	}

	// Meta General Page Options - Enable Slider
	if(jQuery('tr._thinkup_meta_slider input').is(":checked")){
		jQuery('tr._thinkup_meta_slidername').show();
	}
	else if(jQuery('tr._thinkup_meta_slider input').not(":checked")){
		jQuery('tr._thinkup_meta_slidername').hide();
	}

	// Meta General Page Options - Page Layout (Options 3 & 4)
	if(jQuery('tr._thinkup_meta_layout input[value=option3]').is(":checked") || jQuery('tr._thinkup_meta_layout input[value=option4]').is(":checked")){
		jQuery('tr._thinkup_meta_sidebars').show();
	}
	else if(jQuery('tr._thinkup_meta_layout input[value=option3]').not(":checked") || jQuery('tr._thinkup_meta_layout input[value=option4]').not(":checked")){
		jQuery('tr._thinkup_meta_sidebars').hide();
	}
});


/* ----------------------------------------------------------------------------------
	HIDE / SHOW OPTIONS ON RADIO CLICK
---------------------------------------------------------------------------------- */
jQuery(document).ready(function(){
	jQuery('input[type=radio]').change(function() {

		// General - Logo Settings (Option 1) - DONE
		if(jQuery('#section-thinkup_general_logoswitch input[value=option1]').is(":checked")){
			jQuery('#section-thinkup_general_logolink').fadeIn('slow');
			jQuery('#section-thinkup_general_logolinkretina').fadeIn('slow');
		}
		else if(jQuery('#section-thinkup_general_logoswitch input[value=option1]').not(":checked")){
			jQuery('#section-thinkup_general_logolink').fadeOut('slow');
			jQuery('#section-thinkup_general_logolinkretina').fadeOut('slow');
		}

		/* General - Logo Settings (Option 2) - DONE */
		if(jQuery('#section-thinkup_general_logoswitch input[value=option2]').is(":checked")){
			jQuery('#section-thinkup_general_sitetitle').fadeIn('slow');
			jQuery('#section-thinkup_general_sitedescription').fadeIn('slow');
		}
		else if(jQuery('#section-thinkup_general_logoswitch input[value=option2]').not(":checked")){
			jQuery('#section-thinkup_general_sitetitle').fadeOut('slow');
			jQuery('#section-thinkup_general_sitedescription').fadeOut('slow');
		}

		// Homepage - Enable Slider
		if(jQuery('#thinkup_homepage_sliderswitch-buttonsetoption1').is(":checked")){
			jQuery('#section-thinkup_homepage_sliderpreset').fadeIn('slow');
			jQuery('#section-thinkup_homepage_sliderpresetwidth').fadeIn('slow');
			jQuery('#section-thinkup_homepage_sliderpresetheight').fadeIn('slow');

		}
		else if(jQuery('#thinkup_homepage_sliderswitch-buttonsetoption1').not(":checked")){
			jQuery('#section-thinkup_homepage_sliderpreset').fadeOut('slow');
			jQuery('#section-thinkup_homepage_sliderpresetwidth').fadeOut('slow');
			jQuery('#section-thinkup_homepage_sliderpresetheight').fadeOut('slow');
		}
		if(jQuery('#thinkup_homepage_sliderswitch-buttonsetoption2').is(":checked")){
			jQuery('#section-thinkup_homepage_slidername').fadeIn('slow');
		}
		else if(jQuery('#thinkup_homepage_sliderswitch-buttonsetoption2').not(":checked")){
			jQuery('#section-thinkup_homepage_slidername').fadeOut('slow');
		}

		/* Homepage - Call To Action Intro Link (Option 1) - DONE */
		if(jQuery('#section-thinkup_homepage_introactionlink input[value=option1]').is(":checked")){
			jQuery('#section-thinkup_homepage_introactionpage').fadeIn('slow');
		}
		else if(jQuery('#section-thinkup_homepage_introactionlink input[value=option1]').not(":checked")){
			jQuery('#section-thinkup_homepage_introactionpage').fadeOut('slow');
		}

		/* Homepage - Call To Action Intro Link (Option 2) - DONE */
		if(jQuery('#section-thinkup_homepage_introactionlink input[value=option2]').is(":checked")){
			jQuery('#section-thinkup_homepage_introactioncustom').fadeIn('slow');
		}
		else if(jQuery('#section-thinkup_homepage_introactionlink input[value=option2]').not(":checked")){
			jQuery('#section-thinkup_homepage_introactioncustom').fadeOut('slow');
		}

		/* Homepage - Call To Action Outro Link (Option 1) - DONE */
		if(jQuery('#section-thinkup_homepage_outroactionlink input[value=option1]').is(":checked")){
			jQuery('#section-thinkup_homepage_outroactionpage').fadeIn('slow');
		}
		else if(jQuery('#section-thinkup_homepage_outroactionlink input[value=option1]').not(":checked")){
			jQuery('#section-thinkup_homepage_outroactionpage').fadeOut('slow');
		}

		/* Homepage - Call To Action Outro Link (Option 2) - DONE */
		if(jQuery('#section-thinkup_homepage_outroactionlink input[value=option2]').is(":checked")){
			jQuery('#section-thinkup_homepage_outroactioncustom').fadeIn('slow');
		}
		else if(jQuery('#section-thinkup_homepage_outroactionlink input[value=option2]').not(":checked")){
			jQuery('#section-thinkup_homepage_outroactioncustom').fadeOut('slow');
		}

		/* Footer - Call To Action Outro Link (Option 1) - DONE */
		if(jQuery('#section-thinkup_footer_outroactionlink input[value=option1]').is(":checked")){
			jQuery('#section-thinkup_footer_outroactionpage').fadeIn('slow');
		}
		else if(jQuery('#section-thinkup_footer_outroactionlink input[value=option1]').not(":checked")){
			jQuery('#section-thinkup_footer_outroactionpage').fadeOut('slow');
		}

		/* Footer - Call To Action Outro Link (Option 2) - DONE */
		if(jQuery('#section-thinkup_footer_outroactionlink input[value=option2]').is(":checked")){
			jQuery('#section-thinkup_footer_outroactioncustom').fadeIn('slow');
		}
		else if(jQuery('#section-thinkup_footer_outroactionlink input[value=option2]').not(":checked")){
			jQuery('#section-thinkup_footer_outroactioncustom').fadeOut('slow');
		}

		/* Notification Bar - Add Button Link (Option 1) - DONE */
		if(jQuery('#section-thinkup_notification_link input[value=option1]').is(":checked")){
			jQuery('#section-thinkup_notification_linkpage').fadeIn('slow');
		}
		else if(jQuery('#section-thinkup_notification_link input[value=option1]').not(":checked")){
			jQuery('#section-thinkup_notification_linkpage').fadeOut('slow');
		}

		/* Notification Bar - Add Button Link (Option 2) - DONE */
		if(jQuery('#section-thinkup_notification_link input[value=option2]').is(":checked")){
			jQuery('#section-thinkup_notification_linkcustom').fadeIn('slow');
		}
		else if(jQuery('#section-thinkup_notification_link input[value=option2]').not(":checked")){
			jQuery('#section-thinkup_notification_linkcustom').fadeOut('slow');
		}

		/* Portfolio - Portfolio Style - DONE */
		if(jQuery('#section-thinkup_portfolio_style input[value=option1]').is(":checked")){
			jQuery('#section-thinkup_portfolio_hovercheck').fadeIn('slow');
		} else if(jQuery('#section-thinkup_portfolio_style input[value=option1]').not(":checked")){
			jQuery('#section-thinkup_portfolio_hovercheck').fadeOut('slow');
		}
		if(jQuery('#section-thinkup_portfolio_style input[value=option2]').is(":checked")){
			jQuery('#section-thinkup_portfolio_textcheck').fadeIn('slow');
		} else if(jQuery('#section-thinkup_portfolio_style input[value=option2]').not(":checked")){
			jQuery('#section-thinkup_portfolio_textcheck').fadeOut('slow');
		}

		/* Meta General Page Options - Page Layout (Options 3 & 4) */
		if(jQuery('tr._thinkup_meta_layout input[value=option3]').is(":checked") || jQuery('tr._thinkup_meta_layout input[value=option4]').is(":checked")){
			jQuery('tr._thinkup_meta_sidebars').show();
		}
		else if(jQuery('tr._thinkup_meta_layout input[value=option3]').not(":checked") || jQuery('tr._thinkup_meta_layout input[value=option4]').not(":checked")){
			jQuery('tr._thinkup_meta_sidebars').hide();
		}
	});
});


/* ----------------------------------------------------------------------------------
	HIDE / SHOW OPTIONS ON CHECKBOX CLICK
---------------------------------------------------------------------------------- */
jQuery(document).ready(function(){
	jQuery('input[type=checkbox]').change(function() {

		/* Homepage - Enable Homepage Blog - DONE */
		if(jQuery('#section-thinkup_homepage_blog input').is(":checked")){
			jQuery('#section-thinkup_homepage_addtext').fadeOut();
			jQuery('#section-thinkup_homepage_addtextparagraph').fadeOut();
			jQuery('#section-thinkup_homepage_addpage').fadeOut();
		}
		else if(jQuery('#section-thinkup_homepage_blog input').not(":checked")){
			jQuery('#section-thinkup_homepage_addtext').fadeIn();
			jQuery('#section-thinkup_homepage_addtextparagraph').fadeIn();
			jQuery('#section-thinkup_homepage_addpage').fadeIn();
		}
	
		/* Typography - Body Font (Font Family) - DONE */
		if(jQuery('#section-thinkup_font_bodyswitch input').is(":checked")){
			jQuery('#section-thinkup_font_bodystandard').fadeOut();
			jQuery('#section-thinkup_font_bodygoogle').fadeIn();
		}
		else if(jQuery('#section-thinkup_font_bodyswitch input').not(":checked")){
			jQuery('#section-thinkup_font_bodystandard').fadeIn();
			jQuery('#section-thinkup_font_bodygoogle').fadeOut();
		}

		/* Typography - Body Headings (Font Family) - DONE */
		if(jQuery('#section-thinkup_font_bodyheadingswitch input').is(":checked")){
			jQuery('#section-thinkup_font_bodyheadingstandard').fadeOut();
			jQuery('#section-thinkup_font_bodyheadinggoogle').fadeIn();
		}
		else if(jQuery('#section-thinkup_font_bodyheadingswitch input').not(":checked")){
			jQuery('#section-thinkup_font_bodyheadingstandard').fadeIn();
			jQuery('#section-thinkup_font_bodyheadinggoogle').fadeOut();
		}

		/* Typography - Footer Headings (Font Family) - DONE */
		if(jQuery('#section-thinkup_font_footerheadingswitch input').is(":checked")){
			jQuery('#section-thinkup_font_footerheadingstandard').fadeOut();
			jQuery('#section-thinkup_font_footerheadinggoogle').fadeIn();
		}
		else if(jQuery('#section-thinkup_font_footerheadingswitch input').not(":checked")){
			jQuery('#section-thinkup_font_footerheadingstandard').fadeIn();
			jQuery('#section-thinkup_font_footerheadinggoogle').fadeOut();
		}

		/* Typography - Pre Header Menu (Font Family) - DONE */
		if(jQuery('#section-thinkup_font_preheaderswitch input').is(":checked")){
			jQuery('#section-thinkup_font_preheaderstandard').fadeOut();
			jQuery('#section-thinkup_font_preheadergoogle').fadeIn();
		}
		else if(jQuery('#section-thinkup_font_preheaderswitch input').not(":checked")){
			jQuery('#section-thinkup_font_preheaderstandard').fadeIn();
			jQuery('#section-thinkup_font_preheadergoogle').fadeOut();
		}

		/* Typography - Main Header Menu (Font Family) - DONE */
		if(jQuery('#section-thinkup_font_mainheaderswitch input').is(":checked")){
			jQuery('#section-thinkup_font_mainheaderstandard').fadeOut();
			jQuery('#section-thinkup_font_mainheadergoogle').fadeIn();
		}
		else if(jQuery('#section-thinkup_font_mainheaderswitch input').not(":checked")){
			jQuery('#section-thinkup_font_mainheaderstandard').fadeIn();
			jQuery('#section-thinkup_font_mainheadergoogle').fadeOut();
		}

		/* Typography - Main Footer Menu (Font Family) - DONE */
		if(jQuery('#section-thinkup_font_mainfooterswitch input').is(":checked")){
			jQuery('#section-thinkup_font_mainfooterstandard').fadeOut();
			jQuery('#section-thinkup_font_mainfootergoogle').fadeIn();
		}
		else if(jQuery('#section-thinkup_font_mainfooterswitch input').not(":checked")){
			jQuery('#section-thinkup_font_mainfooterstandard').fadeIn();
			jQuery('#section-thinkup_font_mainfootergoogle').fadeOut();
		}

		/* Typography - Post Footer Menu (Font Family) - DONE */
		if(jQuery('#section-thinkup_font_postfooterswitch input').is(":checked")){
			jQuery('#section-thinkup_font_postfooterstandard').fadeOut();
			jQuery('#section-thinkup_font_postfootergoogle').fadeIn();
		}
		else if(jQuery('#section-thinkup_font_postfooterswitch input').not(":checked")){
			jQuery('#section-thinkup_font_postfooterstandard').fadeIn();
			jQuery('#section-thinkup_font_postfootergoogle').fadeOut();
		}

		/* Meta General Page Options - Enable Slider */
		if(jQuery('tr._thinkup_meta_slider input').is(":checked")){
			jQuery('tr._thinkup_meta_slidername').show();
		}
		else if(jQuery('tr._thinkup_meta_slider input').not(":checked")){
			jQuery('tr._thinkup_meta_slidername').hide();
		}
	});
});


/* ----------------------------------------------------------------------------------
	HIDE / SHOW OPTIONS ON SIDEBAR IMAGE CLICK - MAIN OPTIONS PANEL
---------------------------------------------------------------------------------- */
jQuery(document).ready(function(){
	jQuery('input[type=radio]').change(function() {

		/* Select sidebar for Page Layout - DONE */
		if( jQuery('#section-thinkup_general_layout input[value=option2]').is(":checked") || jQuery('#section-thinkup_general_layout input[value=option3]').is(":checked") ){
			jQuery('#section-thinkup_general_sidebars').fadeIn();
		}
		else if(jQuery('#section-thinkup_general_layout input[value=option2]').not(":checked") || jQuery('#section-thinkup_general_layout input[value=option3]').not(":checked") ){
			jQuery('#section-thinkup_general_sidebars').fadeOut();
		}

		/* Select sidebar for Homepage Layout - DONE */
		if( jQuery('#section-thinkup_homepage_layout input[value=option2]').is(":checked") || jQuery('#section-thinkup_homepage_layout input[value=option3]').is(":checked") ){
			jQuery('#section-thinkup_homepage_sidebars').fadeIn();
		}
		else if(jQuery('#section-thinkup_homepage_layout input[value=option2]').not(":checked") || jQuery('#section-thinkup_homepage_layout input[value=option3]').not(":checked") ){
			jQuery('#section-thinkup_homepage_sidebars').fadeOut();
		}

		/* Select sidebar for Blog Layout - DONE */
		if( jQuery('#section-thinkup_blog_layout input[value=option2]').is(":checked") || jQuery('#section-thinkup_blog_layout input[value=option3]').is(":checked") ){
			jQuery('#section-thinkup_blog_sidebars').fadeIn();
		}
		else if(jQuery('#section-thinkup_blog_layout input[value=option2]').not(":checked") || jQuery('#section-thinkup_blog_layout input[value=option3]').not(":checked") ){
			jQuery('#section-thinkup_blog_sidebars').fadeOut();
		}

		/* Select sidebar for Post Layout - DONE */
		if( jQuery('#section-thinkup_post_layout input[value=option2]').is(":checked") || jQuery('#section-thinkup_post_layout input[value=option3]').is(":checked") ){
			jQuery('#section-thinkup_post_sidebars').fadeIn();
		}
		else if(jQuery('#section-thinkup_post_layout input[value=option2]').not(":checked") || jQuery('#section-thinkup_post_layout input[value=option3]').not(":checked") ){
			jQuery('#section-thinkup_post_sidebars').fadeOut();
		}

		/* Select sidebar for Portfolio Layout - DONE */
		if( jQuery('#section-thinkup_portfolio_layout input[value=option5]').is(":checked") || jQuery('#section-thinkup_portfolio_layout input[value=option6]').is(":checked") || jQuery('#section-thinkup_portfolio_layout input[value=option7]').is(":checked") || jQuery('#section-thinkup_portfolio_layout input[value=option8]').is(":checked") ){
			jQuery('#section-thinkup_portfolio_sidebars').fadeIn();
		}
		else if(jQuery('#section-thinkup_portfolio_layout input[value=option5]').not(":checked") || jQuery('#section-thinkup_portfolio_layout input[value=option6]').not(":checked") || jQuery('#section-thinkup_portfolio_layout input[value=option7]').not(":checked") || jQuery('#section-thinkup_portfolio_layout input[value=option8]').not(":checked") ){
			jQuery('#section-thinkup_portfolio_sidebars').fadeOut();
		}

		/* Select sidebar for Project Layout - DONE */
		if( jQuery('#section-thinkup_project_layout input[value=option2]').is(":checked") || jQuery('#section-thinkup_project_layout input[value=option3]').is(":checked") ){
			jQuery('#section-thinkup_project_sidebars').fadeIn();
		}
		else if(jQuery('#section-thinkup_project_layout input[value=option2]').not(":checked") || jQuery('#section-thinkup_project_layout input[value=option3]').not(":checked") ){
			jQuery('#section-thinkup_project_sidebars').fadeOut();
		}	
	});
});