/**
 * Function for ResponsiveSlides used on homepage slider.
 *
 * jQuery effects used in theme.
 */

//----------------------------------------------------------------------------------
//	RESPONSIVE SLIDER - HOMEPAGE
//----------------------------------------------------------------------------------

// Control ThinkUpSlider features
jQuery(document).ready(function() {
	jQuery(".rslides-inner .slides").responsiveSlides({
		auto: true,             // Boolean: Animate automatically, true or false
		speed: 500,             // Integer: Speed of the transition, in milliseconds
		timeout: 6000,          // Integer: Time between slide transitions, in milliseconds
		pager: true,            // Boolean: Show pager, true or false
		nav: true,              // Boolean: Show navigation, true or false
		random: false,          // Boolean: Randomize the order of the slides, true or false
		pause: true,            // Boolean: Pause on hover, true or false
		pauseControls: true,    // Boolean: Pause when hovering controls, true or false
		prevText: " ",          // String: Text for the "previous" button
		nextText: " ",          // String: Text for the "next" button
		maxwidth: "",           // Integer: Max-width of the slideshow, in pixels
		navContainer: "",       // Selector: Where controls should be appended to, default is after the 'ul'
		manualControls: "",     // Selector: Declare custom pager navigation
		namespace: "rslides",   // String: Change the default namespace used
		before: function(){},   // Function: Before callback
		after: function(){}     // Function: After callback
	});
});

// Homepage Responsive Slider
jQuery(document).ready(function() {

	// Supported Platforms
	var slider = jQuery( '#slider .rslides-inner .slides' );

	slider.each(function() {
		var el = jQuery(this);
		el
			.attr( 'data-aspectRatio', slider.height() / 1140 )
			.attr( 'data-oldWidth', el.width() );
		});

	jQuery(document).ready(function() {
		slider.each( function() {
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
			slider.each( function() {
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

// Homepage Responsive Slider (inner li)
jQuery(document).ready(function() {

	// Supported Platforms
	var slider_li = jQuery( '#slider .rslides-inner .slides li' );

	slider_li.each(function() {
		var el = jQuery(this);
		el
			.attr( 'data-aspectRatio', slider_li.height() / 1140 )
			.attr( 'data-oldWidth', el.width() );
		});

	jQuery(document).ready(function() {
		slider_li.each( function() {
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
			slider_li.each( function() {
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


//----------------------------------------------------------------------------------
//	RESPONSIVE SLIDER - SHORTCODE
//----------------------------------------------------------------------------------

// Homepage Responsive Slider
jQuery(document).ready(function() {

	jQuery( '.rslides-sc' ).each( function( i, element ) {

		var instanceID = 'rslides-sc-' + i;

		jQuery( element ).attr( 'id', instanceID );


		// Supported Platforms
		var slider = jQuery( element ).find( '.rslides-inner .slides' );

		var slider_height = jQuery( element ).data( 'height' );

		jQuery( element ).find( '.rslides' ).css( { 'height': slider_height, 'max-height': slider_height } );
		jQuery( element ).find( '.rslides li' ).css( { 'height': slider_height, 'max-height': slider_height } );
		jQuery( element ).find( '.rslides img' ).css( { 'max-height': slider_height } );

		slider.each(function() {
			var el = jQuery(this);
			el
				.attr( 'data-aspectRatio', slider.height() / slider.width() )
				.attr( 'data-oldWidth', el.width() );
			});

		jQuery(document).ready(function() {
			slider.each( function() {
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
				slider.each( function() {
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
});

// Homepage Responsive Slider (inner li)
jQuery(document).ready(function() {

	jQuery( '.rslides-sc' ).each( function( i, element ) {

		var instanceID = 'rslides-sc-' + i;

		jQuery( element ).attr( 'id', instanceID );
		
		// Supported Platforms
		var slider_li = jQuery( element ).find( '.rslides-inner .slides li' );

		slider_li.each(function() {
			var el = jQuery(this);
			el
				.attr( 'data-aspectRatio', slider_li.height() / slider_li.width() )
				.attr( 'data-oldWidth', el.width() );
			});

		jQuery(document).ready(function() {
			slider_li.each( function() {
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
				slider_li.each( function() {
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
});