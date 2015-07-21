jQuery(document).ready(function($) {
  var wall = new freewall("#freewall");
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
});//end doc ready
