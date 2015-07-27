jQuery(document).ready(function($) {

	jQuery( '.rslides-drstk' ).each( function( i, element ) {

		var instanceID = 'rslides-drstk-' + i;
		console.log(instanceID);

		jQuery( element ).attr( 'id', instanceID );


		// Supported Platforms
		var slider = jQuery( element ).find( '.rslides-inner .slides' );
		console.log(slider);
		var slider_height = jQuery( element ).data( 'height' );
		console.log(slider_height);
		console.log(slider.width());
		console.log((slider_height * slider.width())/(jQuery( element ).data('width')));


		jQuery( element ).find( '.rslides' ).css( { 'height': slider_height, 'max-height': slider_height } );
		jQuery( element ).find( '.rslides li' ).css( { 'height': slider_height, 'max-height': slider_height } );
		jQuery( element ).find( '.rslides img' ).css( { 'max-height': slider_height } );

    slider_height = (slider_height * slider.width())/(jQuery( element ).data('width'));
    slider.height(slider_height);

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
