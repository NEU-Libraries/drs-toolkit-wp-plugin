jQuery(document).ready(function($) {
  $("#drs-loading").html("<h2>Loading...<br/><span class='fa fa-spinner fa-pulse'></span></h2>");
  $.post(item_obj.ajax_url, {
     _ajax_nonce: item_obj.nonce,
      action: "get_item",
      pid: item_obj.pid,

  }, function(data) {
      $("#drs-loading").hide();
      var data = $.parseJSON(data);
      if (data == null) {
        $("#drs-content").html("There seems to be an issue connecting with the place where the data is stored. Try again later. Thanks!");
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
      $(".page-title").html(data.mods.Title);
    }
    if (data.thumbnails) {
      $("#drs-item-img").attr("src",data.thumbnails[data.thumbnails.length - 1]);
    }
    if (data.canonical_object[0][1] == 'Video File' || data.canonical_object[0][1] == 'Audio File'){
      $("#drs-item-img").after("<div id='drs-item-video'></div>").hide();
        jwplayer.key="gi5wgpwDtAXG4xdj1uuW/NyMsECyiATOBxEO7A=="
        var provider = data.av_provider;
        var type = data.av_type;
        var encoded = data.encoded_av_pid;
        var dir = data.av_dir;
        var poster = data.av_poster;
        var primary = "flash"
        if (typeof swfobject == 'undefined' || swfobject.getFlashPlayerVersion().major == 0) {
          primary == "html5"
        }
        jwplayer("drs-item-video").setup({
        sources:
        [
        { file: "rtmp://libwowza.neu.edu:1935/vod/_definst_/"+type+":datastreamStore/cerberusData/newfedoradata/datastreamStore/"+dir+"/info%3Afedora%2F"+encoded+"%2Fcontent%2Fcontent.0"},
        { file: "http://libwowza.neu.edu:1935/vod/_definst_/datastreamStore/cerberusData/newfedoradata/datastreamStore/"+dir+"/"+type+":" + encodeURIComponent("info%3Afedora%2F"+encoded+"%2Fcontent%2Fcontent.0") + "/playlist.m3u8", type:type}
        ],
        image: poster,
        provider: provider,
        fallback: "true",
        androidhls: "true",
        primary: primary,
        width: "100%",
        height: 400,
        })
        var errorMessage = function() {
          $("#drs-item-img").show()
          $("#drs-item-video").hide()
        }
        jwplayer().onError(errorMessage);
        jwplayer().onSetupError(errorMessage);
        jwplayer().onBuffer(function() {
          theTimeout = setTimeout(function() {
            $("#drs-item-img").show()
            $("#drs-item-video").hide()
          }, 5000)
        })
    }
    var data_html = '';
    $.each(data.mods, function(key,value){
      data_html += "<div><b>"+key+"</b></div><div>"+value+"</div>";
    });
    $("#drs-item-details").html(data_html);
  }
});
