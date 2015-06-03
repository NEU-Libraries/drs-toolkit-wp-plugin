jQuery(document).ready(function($) {
  $("#drs-item-title").html("<h2>Loading...<br/><span class='fa fa-spinner fa-pulse'></span></h2>");
  $.post(item_obj.ajax_url, {
     _ajax_nonce: item_obj.nonce,
      action: "get_item",
      pid: item_obj.pid,

  }, function(data) {
      var data = $.parseJSON(data);
      if (data == '') {
        $("#drs-content").html("Your request produced no results. The error received was '"+data.error+"'. Thanks!");
      } else if (data.error) {
        $("#drs-content").html("Your request produced no results. The error received was '"+data.error+"'. Thanks!");
      } else if (jQuery.type(data) == 'object') {
        parse_item(data);
      } else {
        $("#drs-content").html("Your request produced no results. Please go back and try a different request. Thanks!");
      }
  }).fail(function() {
    $("#drs-content").html("<div class='alert error'>There was an error connecting to the external service. Please try a different request. Thanks!</div>");
  });

  function parse_item(data){
    if (data.mods.Title) {
      $("#drs-item-title").html(data.mods.Title);
    }
    if (data.thumbnails) {
      $("#drs-item-img").attr("src",data.thumbnails[data.thumbnails.length - 1]);
      //append responsive sized images here
    }
    var data_html = '';
    $.each(data.mods, function(key,value){
      data_html += "<div><b>"+key+"</b></div><div>"+value+"</div>";
    });
    $("#drs-item-details").html(data_html);
  }
});
