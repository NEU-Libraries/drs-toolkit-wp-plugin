jQuery(document).ready(function($) {
  $("img[data-zoom='on']").each(function(){
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
  });
});//end doc ready
