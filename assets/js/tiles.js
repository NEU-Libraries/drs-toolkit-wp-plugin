jQuery(document).ready(function($) {
  $(".freewall").each(function(){
    var type = $(this).data("type");
    if (type == 'pinterest'){
      var wall = new freewall(this);
      wall.reset({
        selector: ".brick",
        animate: true,
        cellW: 200,
        cellH: "auto",
        onResize: function() {
          wall.fitWidth();
        }
      });
      wall.container.find(".brick img").load(function() {
        wall.fitWidth();
      });
    }
    if (type == 'even-row'){
      var w = 1, html = '', i = 0;
      $(this).find(".cell").each( function(){
        var temp = "<div class='cell' style='width:{width}px; height: {height}px; background-image: url("+$(this).data("thumbnail")+")'><div class='info'>"+$(this).children(".info").html()+"</div></div>";
        w = 200 + 200 * Math.random() << 0;
        html += temp.replace(/\{height\}/g, 200).replace(/\{width\}/g, w);
        i++;
      });
  		$(this).html(html);

  		var wall = new freewall(this);
  		wall.reset({
  			selector: '.cell',
  			animate: true,
  			cellW: 20,
  			cellH: 200,
  			onResize: function() {
  				wall.fitWidth();
  			}
  		});
  		wall.fitWidth();
  		// for scroll bar appear;
  		$(window).trigger("resize");
    }
    if (type == 'square'){
      var w = 200, h = 200, html = '', i = 0;
      $(this).find(".cell").each( function(){
        var temp = "<div class='cell' style='width:{width}px; height: {height}px; background-image: url("+$(this).data("thumbnail")+")'><div class='info'>"+$(this).children(".info").html()+"</div></div>";
        html += temp.replace(/\{height\}/g, h).replace(/\{width\}/g, w);
        i++;
      });
  		$(this).html(html);

  		var wall = new freewall(this);
  		wall.reset({
  			selector: '.cell',
  			animate: true,
  			cellW: 200,
  			cellH: 200,
  			onResize: function() {
  				wall.refresh();
  			}
  		});
  		wall.fitWidth();
  		// for scroll bar appear;
  		$(window).trigger("resize");
    }
  });
});//end doc ready
