jQuery(document).ready(function($) {
  $("img.drs-item-img").each(function(){
    if ($(this).data('align')){
      $(this).parent('a').css("display","block").css("text-align", $(this).data('align'));
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
  })
});//end doc ready
