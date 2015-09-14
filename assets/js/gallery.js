jQuery(document).ready(function($) {
	$(".carousel").find(".item:first-of-type").addClass("active");
	$(".carousel").each(function(){
		if ($(this).data("max-height") > 0){
			$(this).css("max-height", $(this).data("max-height"));
			$(this).find(".carousel-inner, img").css("max-height", $(this).data("max-height"));
		}
		if ($(this).data("max-width") > 0){
			$(this).css("max-width", $(this).data("max-width"));
			$(this).find(".carousel-inner, img").css("max-width", $(this).data("max-width"));
		}
	})
});
