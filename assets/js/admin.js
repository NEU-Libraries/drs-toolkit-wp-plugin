/**
 * JavaScript for the registration page.
 **/

 jQuery( document ).ready(function( $ ) {

  var $the_list = $("#sortable-source-list");
  var $hidden_json = $('#drstk_collection_json');
  var jsonData = decodeURIComponent($hidden_json.val());

  var origHiddenData = '';

  if (jsonData !== 'undefined') {
    origHiddenData = JSON.parse(jsonData);
  }

  function updateHiddenJSON(  ) {
    var newHiddenData = [];
    var listData = $the_list.sortable('toArray');

    for (var i = 0; i < listData.length; i++) {
      $checkbox = $the_list.find('#' + listData[i] ).find('input');
      var key = listData[i].replace('drsvideokey-','');

      origHiddenData[key].include = $checkbox.prop('checked');
      newHiddenData.push(origHiddenData[key]);
    }

    $hidden_json.val( encodeURIComponent(JSON.stringify(newHiddenData) ) );

  }

  // list is sorted
  $the_list.sortable({
    update: updateHiddenJSON
  });

  // checkbox trigger
  $('.drstk-include-video').change(updateHiddenJSON);

  // insert shortcode into the text area
  var $content = $('#content');
  $('#drstk_insert_shortcode').click(function(e) {
    e.preventDefault();
    $content.val( '[drstk_collection_playlist]\n' + $content.val());
  });

  $("body").on("click", ".drstk-include-item", function(e){
    console.log($(this).val());
    var pid = $(this).val();
    if($(this).is(":checked")){
      $(this).parents("li").siblings("li").hide();
      $.post(item_admin_obj.ajax_url, {
         _ajax_nonce: item_admin_obj.item_admin_nonce,
          action: "get_item_admin",
          pid: pid,
      }, function(data) {
          var data = $.parseJSON(data);
          // console.log(data);
          if (data.error){
            $(".item-metadata").html("There was an error: "+data.error);
          } else {
            // console.log(data);
            var data_html = '';
            $.each(data.mods, function(key,value){
              data_html += "<div><input type='checkbox' name='"+key+"' value='"+value+"'/><b>"+key+"</b></div><div>";
                data_html += value;
              data_html += "</div>";
            });
            $(".item-metadata").html(data_html);
          }
      }).fail(function() {
        $(".item-metadata").html("There was an error getting metadata on this item. Please try a different url.");
      });
    } else {
      $(this).parents("li").siblings("li").show();
    }

  });

  //insert item shortcode button
  $('body').on("click", "#drstk_insert_item", function(e) {
    e.preventDefault();
    var pid = '';
    $(".drstk-include-item").each(function(){
      if ($(this).is(":visible")){
        pid = $(this).val();
      }
    });
    var metadata = [];
    $(".item-metadata input[type='checkbox']:checked").each(function(){
      metadata.push($(this).attr('name'));
    });
    var shortcode = '[drstk_item id="'+pid+'"';
    //add zoom back in
    shortcode += ' metadata="'+metadata;
    shortcode +='"]\n';
    $content.val(shortcode + $content.val());
  });

  //sortable tile list
  $("#sortable-tile-list").sortable();
  $("#sortable-gallery-list").sortable();
  //insert tile gallery button
  $("#drstk_insert_tile_gallery").click(function(e){
    e.preventDefault();
    var tiles = [];
    $(".drstk-include-tile:checked").each(function(){
      console.log($(this).val());
      tiles.push($(this).val());
    });
    console.log(tiles);
    tiles = tiles.join(", ");
    var shortcode = '[drstk_tiles id="'+tiles+'"]\n';
    $content.val(shortcode + $content.val());
  });

   $("#tabs").tabs().addClass('ui-tabs-vertical ui-helper-clearfix');
   $("[id^=ui-id-]").on("click", function(e){
     var id = $(this).attr('id');
     id = id.substr(id.length - 1);
     if (id == 4){
       $.post(video_ajax_obj.ajax_url, {
          _ajax_nonce: video_ajax_obj.video_ajax_nonce,
           action: "get_video_code",
       }, function(data) {
          $("#TB_ajaxContent #tabs-4").html(data);
        });
     }
     if (id == 3){
       $.post(item_ajax_obj.ajax_url, {
          _ajax_nonce: item_ajax_obj.item_ajax_nonce,
           action: "get_item_code",
       }, function(data) {
          $("#TB_ajaxContent #tabs-3").html(data);
        });
     }
     if (id == 2){
       $.post(gallery_ajax_obj.ajax_url, {
          _ajax_nonce: gallery_ajax_obj.gallery_ajax_nonce,
           action: "get_gallery_code",
       }, function(data) {
          $("#TB_ajaxContent #tabs-2").html(data);
        });
     }

   });

   //insert gallery button
  $("body").on("click", "#drstk_insert_gallery", function(e){
     e.preventDefault();
     console.log("we are inserting a gallery");
     var slides = [];
     $(".drstk-include-gallery:checked").each(function(){
       console.log($(this).val());
       slides.push($(this).val());
     });
     console.log(slides);
     slides = slides.join(", ");
     var shortcode = '[drstk_gallery id="'+slides+'"]\n';
     $content.val(shortcode + $content.val());
   });

});
