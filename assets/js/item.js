jQuery(document).ready(function($) {
  $("#drs-loading").html("<h2>Loading...<br/><span class='fa fa-spinner fa-spin'></span></h2>");
  var meta_options = $.parseJSON(item_obj.meta_options);
  meta_options = meta_options.split(",");
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
      $("#title-container h3").html(data.mods.Title);
      $(".post-title").html(data.mods.Title);
    }
    if (data.thumbnails) {
      $("#drs-item-img").attr("src",data.thumbnails[data.thumbnails.length - 2]);
    }
    if (data.canonical_object[0][1] == 'Master Image'){
      var canonical_image = data.canonical_object[0][0];
      $("#drs-item-img").attr('data-zoom-image', data.thumbnails[data.thumbnails.length - 1]);
    } else {
      $("#drs-item-img").attr('data-zoom-image', data.thumbnails[data.thumbnails.length - 1]);
    }
    if (data.canonical_object[0][1] == 'PDF'){
      if (data.mods.Location && data.mods.Location[0].indexOf("issuu") > -1){
        var location_link = String(data.mods.Location[0]);
        var location_href = $(location_link).attr("href");
        var issuu_id = location_href.split('?')[1].split('=')[1];
        $("#drs-item-img").after('<div data-configid="'+issuu_id+'" style="width:100%; height:500px;" class="issuuembed"></div><script type="text/javascript" src="//e.issuu.com/embed.js" async="true"></script>');
        $("#drs-item-img").hide();
      } else {
        //no issuu link - do nothing
        $("#drs-item-img").elevateZoom();
      }
    } else if (data.canonical_object[0][1] == 'Video File' || data.canonical_object[0][1] == 'Audio File'){
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
        { file: "http://libwowza.neu.edu:1935/vod/_definst_/datastreamStore/cerberusData/newfedoradata/datastreamStore/"+dir+"/"+type+":" + "info%3Afedora%2F"+encoded+"%2Fcontent%2Fcontent.0" + "/playlist.m3u8", type:type}
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
          $("#drs-item-img").show();
          $("#drs-item-video").hide();
        };
       jwplayer().onError(errorMessage);
       jwplayer().onSetupError(errorMessage);
       jwplayer().onBuffer(function() {
         theTimeout = setTimeout(function() {
           errorMessage;
         }, 5000);
       });
    } else {
      $("#drs-item-img").elevateZoom();
    }
    var data_html = '';
    $.each(data.mods, function(key,value){
      if($.inArray(key, meta_options) >= 0){
        data_html += "<div class='drs-field-label'><b>"+key+"</b></div><div class='drs-field-value'>";
        if (value.length > 0){
          var i = 0;
          for (i; i<value.length; i++){
            if (value[i].indexOf('http://') == 0){
              data_html += '<a href="'+value[i]+'" target="_blank">'+value[i]+'</a>';
            } else {
              data_html += value[i];
            }
            if (i != value.length-1){
              data_html += ", ";
            }
          }
        } else {
          data_html += value;
        }
        data_html += "</div>";
      }
    });
    $("#drs-item-details").html(data_html);
    var download_links = '';
    $.each(data.content_objects, function(num,content_object) {
      if (content_object[1] != 'Thumbnail Image') {
        download_links += make_object_url(content_object);
      }
    });
    $("#drs-item-details").append("<br/><h4>Downloads</h4>"+download_links);
    function make_object_url(object_array){
      return " <a href='"+object_array[0]+"' target='_blank' class='themebutton button btn' data-label='download' data-pid='"+data.pid+"'>"+object_array[1]+"</a> ";
    }
    if (isFunction(add_google_tracking)){
      add_google_tracking();
    }
  }
  function isFunction(possibleFunction) {
    return typeof(possibleFunction) === typeof(Function);
   }
});
