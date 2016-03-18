jQuery(document).ready(function($) {
  $("img.drs-item-img").each(function(){
    if ($(this).data('align')){
      $(this).parent('a').css("text-align", $(this).data('align'));
    }
    if ($(this).data('zoom') == 'on'){
      if($(this).attr('data-zoom-position') == 'inner'){
        $(this).elevateZoom({
          zoomType	: "inner",
          cursor: "crosshair"
        });
      } else if ($.isNumeric($(this).attr('data-zoom-position'))){
        var position = parseInt($(this).attr('data-zoom-position'));
        $(this).elevateZoom({ zoomWindowPosition: position});
      } else {
        $(this).elevateZoom();
      }
    }
  });
  $(".drstk-caption").each(function(){
    if($(this).data('caption-align')){
      $(this).css("text-align", $(this).data('caption-align'));
    }
    if($(this).data('caption-position') == 'hover'){
      $(this).parents(".drs-item").addClass("hover");
      $(this).width($(this).siblings(".drs-item-img").innerWidth());
    }
  });
  $(".hidden").each(function(){
    $(this).appendTo("body");
  });
});//end doc ready
