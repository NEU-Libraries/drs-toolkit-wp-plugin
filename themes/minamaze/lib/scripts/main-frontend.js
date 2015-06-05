/**
 * Wordpress Front End Enhancements.
 *
 * jQuery effects used in theme.
 */

/* ----------------------------------------------------------------------------------
	FORMAT FOOTER LAYOUT
---------------------------------------------------------------------------------- */
jQuery(document).ready(function(){

jQuery('#footer-core .widget-area:last-child').addClass("last");

/* Footer - Footer Widgets Layout (Options 1 - 6) */
jQuery('#footer-core.option2 .widget-area').addClass("one_half");
jQuery('#footer-core.option3 .widget-area').addClass("one_third");
jQuery('#footer-core.option4 .widget-area').addClass("one_fourth");
jQuery('#footer-core.option5 .widget-area').addClass("one_fifth");
jQuery('#footer-core.option6 .widget-area').addClass("one_sixth");

/* Footer - Footer Widgets Layout (Option 7) */
jQuery('#footer-core.option7 #footer-col1.widget-area').addClass("one_third");
jQuery('#footer-core.option7 #footer-col2.widget-area').addClass("two_third");

/* Footer - Footer Widgets Layout (Option 8) */
jQuery('#footer-core.option8 #footer-col1.widget-area').addClass("two_third");
jQuery('#footer-core.option8 #footer-col2.widget-area').addClass("one_third");

/* Footer - Footer Widgets Layout (Option 9) */
jQuery('#footer-core.option9 #footer-col1.widget-area').addClass("one_fourth");
jQuery('#footer-core.option9 #footer-col2.widget-area').addClass("three_fourth");

/* Footer - Footer Widgets Layout (Option 10) */
jQuery('#footer-core.option10 #footer-col1.widget-area').addClass("three_fourth");
jQuery('#footer-core.option10 #footer-col2.widget-area').addClass("one_fourth");

/* Footer - Footer Widgets Layout (Option 11) */
jQuery('#footer-core.option11 #footer-col1.widget-area').addClass("one_fifth");
jQuery('#footer-core.option11 #footer-col2.widget-area').addClass("four_fifth");

/* Footer - Footer Widgets Layout (Option 12) */
jQuery('#footer-core.option12 #footer-col1.widget-area').addClass("four_fifth");
jQuery('#footer-core.option12 #footer-col2.widget-area').addClass("one_fifth");

/* Footer - Footer Widgets Layout (Option 13) */
jQuery('#footer-core.option13 #footer-col1.widget-area').addClass("one_sixth");
jQuery('#footer-core.option13 #footer-col2.widget-area').addClass("one_sixth");
jQuery('#footer-core.option13 #footer-col3.widget-area').addClass("one_sixth");
jQuery('#footer-core.option13 #footer-col4.widget-area').addClass("one_half");

/* Footer - Footer Widgets Layout (Option 14) */
jQuery('#footer-core.option14 #footer-col1.widget-area').addClass("one_half");
jQuery('#footer-core.option14 #footer-col2.widget-area').addClass("one_sixth");
jQuery('#footer-core.option14 #footer-col3.widget-area').addClass("one_sixth");
jQuery('#footer-core.option14 #footer-col4.widget-area').addClass("one_sixth");

/* Footer - Footer Widgets Layout (Option 15) */
jQuery('#footer-core.option15 #footer-col1.widget-area').addClass("one_sixth");
jQuery('#footer-core.option15 #footer-col2.widget-area').addClass("one_third");
jQuery('#footer-core.option15 #footer-col3.widget-area').addClass("one_half");

/* Footer - Footer Widgets Layout (Option 16) */
jQuery('#footer-core.option16 #footer-col1.widget-area').addClass("one_half");
jQuery('#footer-core.option16 #footer-col2.widget-area').addClass("one_third");
jQuery('#footer-core.option16 #footer-col3.widget-area').addClass("one_sixth");

/* Footer - Footer Widgets Layout (Option 17) */
jQuery('#footer-core.option17 #footer-col1.widget-area').addClass("one_fourth");
jQuery('#footer-core.option17 #footer-col2.widget-area').addClass("one_fourth");
jQuery('#footer-core.option17 #footer-col3.widget-area').addClass("one_sixth");
jQuery('#footer-core.option17 #footer-col4.widget-area').addClass("one_sixth");
jQuery('#footer-core.option17 #footer-col5.widget-area').addClass("one_sixth");

/* Footer - Footer Widgets Layout (Option 18) */
jQuery('#footer-core.option18 #footer-col1.widget-area').addClass("one_sixth");
jQuery('#footer-core.option18 #footer-col2.widget-area').addClass("one_sixth");
jQuery('#footer-core.option18 #footer-col3.widget-area').addClass("one_sixth");
jQuery('#footer-core.option18 #footer-col4.widget-area').addClass("one_fourth");
jQuery('#footer-core.option18 #footer-col5.widget-area').addClass("one_fourth");
});


/* ----------------------------------------------------------------------------------
	FORMAT MAIN HEADER MENU
---------------------------------------------------------------------------------- */
function mainmenu(){

	/* Add menu-hover class */
	jQuery("header .header-links ul.menu > li").hover(function(){
		jQuery(this).find('ul.sub-menu:first').css({visibility: "visible",display: "none"}).parent().addClass('menu-hover');
	},function(){
		jQuery(this).find('ul.sub-menu:first').css({visibility: "hidden",display: "none"}).parent().removeClass('menu-hover');
	});

	/* Add menu-parent class */
	jQuery("header .header-links ul.menu > li").each(function(){
		jQuery(this).find('ul.sub-menu').css({visibility: "visible",display: "none"}).parent().addClass('menu-parent');
	});

	/* Add smooth dropdown effect */
	jQuery("header .header-links li").hover(function(){
		parentWidth = jQuery(this).width();
		jQuery(this).find('ul:first').css({visibility: "visible",display: "none","min-width": parentWidth}).slideToggle(400);
	},function(){
		jQuery(this).find('ul:first').css({visibility: "hidden"});
	});

	/* Add <span> to links in pre-header and header sub menus */
	jQuery('#pre-header .sub-menu a, #header .sub-menu a').each(function() {
		jQuery(this).wrapInner("<span></span>");
	});
}
jQuery(document).ready(function(){
	mainmenu();
});

/* ----------------------------------------------------------------------------------
	FLUID MEDIA SIZES (Modified from http://bavotasan.com/2012/better-way-to-resize-video-using-jquery)
---------------------------------------------------------------------------------- */

/* Videos */
jQuery(document).ready(function() {

	/* Supported Platforms */
	var all_videos = jQuery( 'iframe[src*="//player.vimeo.com"], iframe[src*="//www.youtube.com"], iframe[src*="//blip.tv"], iframe[src*="//www.slideshare.net"], iframe[src*="//www.scribd.com"], iframe[src*="//revision3.com"], iframe[src*="//www.hulu.com"], iframe[src*="//www.funnyordie.com"], iframe[src*="//www.dailymotion.com"], embed[src*="//v.wordpress.com"], object, embed' );

	all_videos.each(function() {
		var el = jQuery(this);
		el
			.attr( 'data-aspectRatio', 360 / 640 )
			.attr( 'data-oldWidth', el.width() );
	} );

	jQuery(document).ready(function() {
		all_videos.each( function() {
		var el = jQuery(this),
			newWidth = el.parents().width(),
			oldWidth = el.attr( 'data-oldWidth' );

			el
				.removeAttr( 'height' )
				.removeAttr( 'width' )
				.width( newWidth )
				.height( newWidth * el.attr( 'data-aspectRatio' ) );
		});
	}).resize();

	jQuery(window)
		.resize( function() {
			all_videos.each( function() {
			var el = jQuery(this),
				newWidth = el.parents().width(),
				oldWidth = el.attr( 'data-oldWidth' );

				el
					.removeAttr( 'height' )
					.removeAttr( 'width' )
					.width( newWidth )
		    		.height( newWidth * el.attr( 'data-aspectRatio' ) );
			});
		}).resize();
});

/* SoundCloud */
jQuery(document).ready(function() {

	/* Supported Platforms */
	var all_sounds = jQuery( 'iframe[src^="http://w.soundcloud.com"]' );

	all_sounds.each(function() {
		var el = jQuery(this);
		el
			.attr( 'data-aspectRatio', el.attr( 'height' ) / el.attr( 'width' )  )
			.attr( 'data-oldWidth', el.width() );
	});

	jQuery(document).ready(function() {
		all_sounds.each( function() {
		var el = jQuery(this),
			newWidth = el.parents().width(),
			oldWidth = el.attr( 'data-oldWidth' );

			el
				.removeAttr( 'width' )
				.width( newWidth )
		});
	}).resize();

	jQuery(window)
		.resize( function() {
			all_sounds.each( function() {
			var el = jQuery(this),
				newWidth = el.parents().width(),
				oldWidth = el.attr( 'data-oldWidth' );

				el
					.removeAttr( 'width' )
					.width( newWidth )
			});
		}).resize();
});


/* ----------------------------------------------------------------------------------
	ADD PRETTYPHOTO TO PORTFOLIO PAGE
---------------------------------------------------------------------------------- */

jQuery(document).ready(function() {
	jQuery('#container.portfolio-wrapper a').has('img.hover-zoom').addClass('prettyPhoto');
	jQuery('#container.portfolio-wrapper a').has('img.hover-zoom').attr('rel','portfolio[gallery]');
	jQuery('a.prettyPhoto').has('img').attr('rel','portfolio[gallery]');
	jQuery('a.prettyPhoto').prettyPhoto();
});



/* ----------------------------------------------------------------------------------
	ADD PRETTYPHOTO TO POST IMAGE PAGE
---------------------------------------------------------------------------------- */

jQuery(document).ready(function() {
	jQuery('body.attachment .entry-attachment .attachment a').has('img').addClass('prettyPhoto');
	jQuery('a.prettyPhoto').prettyPhoto();
});


/* ----------------------------------------------------------------------------------
	ADD STRUCTURE TO COMMENT FORM (BLOG PAGE)
---------------------------------------------------------------------------------- */

jQuery(document).ready(function() {
	jQuery('.comment-form-author').addClass('one_third');
	jQuery('.comment-form-email').addClass('one_third');
	jQuery('.comment-form-url').addClass('one_third last');
});


/* ----------------------------------------------------------------------------------
	WIDGETS
---------------------------------------------------------------------------------- */

/* Categories */
jQuery(document).ready(function(){
	jQuery('.widget.thinkup_widget_categories .cat-item a').prepend('<i class="icon-caret-right icon-large"></i>');
});


/* ----------------------------------------------------------------------------------
	ADD _BLANK TO OUTGOING LINKS
---------------------------------------------------------------------------------- */

jQuery(document).ready(function(){
	jQuery('.thinkup_widget_flickr a[href^="http://"], .thinkup_widget_flickr a[href^="https://"]').attr('target','_blank');
	jQuery('.thinkup_widget_socialshare a[href^="http://"], .thinkup_widget_socialshare a[href^="https://"]').attr('target','_blank');
	jQuery('.thinkup_widget_socialprofiles a[href^="http://"], .thinkup_widget_socialprofiles a[href^="https://"]').attr('target','_blank');
	jQuery('#pre-header-social a[href^="http://"], #pre-header-social a[href^="https://"]').attr('target','_blank');
	jQuery('#shareicons a[href^="http://"], #shareicons a[href^="https://"]').attr('target','_blank');
});


/* ----------------------------------------------------------------------------------
	SHORTCODES - PREMIUM FEATURE
---------------------------------------------------------------------------------- */

jQuery(document).ready(function() {

	/* Tooltip - Activate Bootstrap */
	jQuery('[data-tip]').each( function() {jQuery(this).tooltip({ placement: jQuery(this).data('tip') }); });
});


/* ----------------------------------------------------------------------------------
	SHORTCODES - WAYPOINTS ANIMATION - PREMIUM FEATURE
---------------------------------------------------------------------------------- */