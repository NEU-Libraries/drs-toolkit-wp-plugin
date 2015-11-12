jQuery(document).ready(function($) {

	$(".carousel").find(".item:first-of-type").addClass("active");
	$(".carousel").carousel();
	$(".carousel").each(function(){
		if ($(this).data("max-height") > 0){
			$(this).css("max-height", $(this).data("max-height"));
			$(this).find(".carousel-inner, .item").css("max-height", $(this).data("max-height"));
			if ($(this).find(".carousel-caption").css("position") == 'relative'){
				var img_height = $(this).data("max-height")-$(this).find(".carousel-caption").outerHeight();
				$(this).find("img").css("max-height", img_height);
			} else {
				$(this).find("img").css("max-height", $(this).data("max-height"));
			}
		} else {//no max-height is set
			var height = 0;
			$(this).find("img").first().one("load", function() {
				//wait until the first image is loaded and use it's height as the height of the gallery
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
			}).each(function() {
				if(this.complete) $(this).load();
			});
		}
		if ($(this).data("max-width") > 0){
			$(this).css("max-width", $(this).data("max-width"));
			$(this).find(".carousel-inner, img").css("max-width", $(this).data("max-width"));
		}
	});
	$(".hidden").each(function(){
		$(this).appendTo("body");
	});
});
