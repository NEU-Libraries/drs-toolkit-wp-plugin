jQuery(document).ready(function ($) {
	$(".wp-block-drs-tk-gallery-carousel").each(function () {
		var $gallery = $(this);
		var slideCount = null;

		console.log($gallery);

		$gallery.on("init", function (event, slick) {
			slideCount = slick.slideCount;
			setSlideCount();
			setCurrentSlideNumber(slick.currentSlide);
		});

		$gallery.slick({
			fade: $gallery.data("effect") == "fade",
			autoplay: $gallery.data("autoplay"),
			dots: $gallery.data("dots"),
			arrows: $gallery.data("arrows"),
			speed: $gallery.data("speed"),
			adaptiveHeight: $gallery.data("adaptiveheight"),
			pauseOnHover: $gallery.data("pauseOnHover"),
			pauseOnFocus: false,
			cssEase: "linear",
			lazyLoad: "anticipated",
		});

		$gallery.on(
			"beforeChange",
			function (event, slick, currentSlide, nextSlide) {
				setCurrentSlideNumber(nextSlide);
			}
		);

		function setSlideCount() {
			var $el = $(".counter").find(".total");
			$el.text(slideCount);
		}

		function setCurrentSlideNumber(currentSlide) {
			var $el = $(".counter").find(".current");
			$el.text(currentSlide + 1);
		}
	});
});
