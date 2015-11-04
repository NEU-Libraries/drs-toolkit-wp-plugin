jQuery(document).ready(function($) {
	$(".carousel").find(".item:first-of-type").addClass("active");
	$(".carousel").each(function(){
		if ($(this).data("max-height") > 0){
			$(this).css("max-height", $(this).data("max-height"));
			$(this).find(".carousel-inner, .item").css("max-height", $(this).data("max-height"));
			if ($(this).find(".carousel-caption").css("position") == 'relative'){
				var img_height = $(this).data("max-height")-$(this).find(".carousel-caption").height();
				$(this).find("img").css("max-height", img_height);
			} else {
				$(this).find("img").css("max-height", $(this).data("max-height"));
			}
		} else {
			var height = 0;
			$(this).find("img").each(function(){
				var this_height = $(this).height();
				if (this_height > height) {
					height = this_height;
				}
			});
			if ($(this).find(".carousel-caption").css("position") == 'relative'){
				var img_height = height-$(this).find(".carousel-caption").height();
				$(this).find("img").css("max-height", img_height);
			} else {
				$(this).find("img").css("max-height", height);
			}
		}
		if ($(this).data("max-width") > 0){
			$(this).css("max-width", $(this).data("max-width"));
			$(this).find(".carousel-inner, img").css("max-width", $(this).data("max-width"));
		}
	})
});
