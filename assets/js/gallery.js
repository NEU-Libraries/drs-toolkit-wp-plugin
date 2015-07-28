jQuery(document).ready(function($) {
	jQuery(".rslides-drstk .slides").each(function(){
		$(this).responsiveSlides({
			auto: $(this).attr("data-auto") || false,							// Boolean: Animate automatically, true or false
			speed: $(this).attr("data-speed") || 500,             // Integer: Speed of the transition, in milliseconds
			timeout: $(this).attr("data-timeout") || 6000,          // Integer: Time between slide transitions, in milliseconds
			pager: $(this).attr("data-pager") || false,            // Boolean: Show pager, true or false
			nav: $(this).attr("data-nav") || false,              // Boolean: Show navigation, true or false
			pause: true,            // Boolean: Pause on hover, true or false
			pauseControls: true,    // Boolean: Pause when hovering controls, true or false
			namespace: "rslides",   // String: Change the default namespace used
			prevText: " ",          // String: Text for the "previous" button
			nextText: " ",          // String: Text for the "next" button
			maxwidth: "",           // Integer: Max-width of the slideshow, in pixels
		});
	});
});
