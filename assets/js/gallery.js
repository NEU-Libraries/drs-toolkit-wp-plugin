jQuery(document).ready(function($) {

	$('.carousel').carousel({
		interval: false
	});

	$(window).on('load', function(){
		fix_dimensions($('.carousel'));
	});

	var cHeight = 0;
	$('.carousel').on('slide.bs.carousel', function(e) {
		var $nextImage = $(e.relatedTarget).find('img');
		$activeItem = $('.active.item', this);

		// prevents the slide decrease in height
		if (cHeight == 0) {
			cHeight = $(this).height();
			$activeItem.next('.item').find("img").height(cHeight);
		}

		// prevents the loaded image if it is already loaded
		var src = $nextImage.data('src');

		if (typeof src !== "undefined" && src != "") {
			$nextImage.attr('src', src)
			$nextImage.data('src', '');
		}
	});

	function fix_dimensions(carousel){
		if (carousel.data("max-height") > 0){
			carousel.css("max-height", carousel.data("max-height"));
			carousel.find(".carousel-inner, .item").css("max-height", carousel.data("max-height"));
			if (carousel.find(".carousel-caption").css("position") == 'relative'){
				var img_height = carousel.data("max-height")-carousel.find(".carousel-caption").outerHeight();
				carousel.find("img").css("max-height", img_height);
			} else {
				carousel.find("img").css("max-height", carousel.data("max-height"));
			}
		} else {//no max-height is set
			var height = 0;
				carousel.find("img").each(function(){
					var this_height = $(this).height();
					if (this_height > height) {
						height = this_height;
						if ($(this).parents(".carousel").find(".carousel-caption").css("position") == 'relative'){
							var img_height = height-$(this).parents(".carousel").find(".carousel-caption").height();
							if (img_height > 0) {$(this).parents(".carousel").find("img").css("max-height", img_height);}
						} else {
							if (height > 0) {$(this).parents(".carousel").find("img").css("max-height", height);}
						}
					}
				});
		}
		if (carousel.data("max-width") > 0){
			carousel.css("max-width", carousel.data("max-width"));
			carousel.find(".carousel-inner, img").css("max-width", carousel.data("max-width"));
		}
	}

	$(".hidden").each(function(){
		$(this).appendTo("body");
	});
});
