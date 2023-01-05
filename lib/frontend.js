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

function convertToTimelineJSON(files) {
	const timelineJSON = files.map((file) => {
		console.log(file);
		return {
			media: {
				url: file.fileUrl,
				caption: "",
				credit: file.creator,
			},
			start_date: {
				year: file.date,
			},
			text: {
				headline: "",
				text: file.description,
			},
		};
	});

	return {
		events: timelineJSON,
	};
}

function createTimeline(files = [], options = {}) {
	new TL.Timeline("timeline-embed", convertToTimelineJSON(files), options);
}

// jquery code that checks for wp-block-drs-tk-timelinev2 class and create a timeline using the data from children with data-timeline-item data attributes
// wait untill the page is loaded
jQuery(document).ready(function ($) {
	$(".wp-block-drs-tk-timelinev2").each(function () {
		var $timeline = $(this);
		var files = [];

		// get all divs with data-timeline-item class
		$timeline.find(".data-timeline-item").each(function () {
			var $item = $(this);
			// get data from data attributes
			var file = {
				fileUrl: $item.data("fileurl"),
				creator: $item.data("creator"),
				date: $item.data("date"),
				description: $item.data("description"),
			};
			// push data to files array
			files.push(file);
		});

		//options
		var options = {
			width: "100%",
			height: "100%",
			// source: convertToTimelineJSON(files),
		};
		// check if timeline-embed div exists
		const timelineEmbed = $timeline.find("#timeline-embed");
		console.log(timelineEmbed);
		const timelineFiles = convertToTimelineJSON(files);
		console.log(timelineFiles);
		// createTimeline
		new TL.Timeline(timelineEmbed[0], timelineFiles, options);
	});
});
