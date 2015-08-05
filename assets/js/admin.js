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
    tb_remove();
  });

  $("body").on("change", ".drstk-include-item", function(e){
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
    if ($("#drsitem-zoom").is(":checked")){
      shortcode += ' zoom="on"';
    }
    if ($("#drsitem-zoom-inner").is(":checked") && $("#drsitem-zoom-window").val() == 0){
      shortcode += ' zoom_position="inner"';
    }
    if ($("#drsitem-zoom-window").val() > 0){
      shortcode += ' zoom_position="'+$("#drsitem-zoom-window").val()+'"';
    }
    if (metadata.length > 0) {shortcode += ' metadata="'+metadata+'"';}
    shortcode +=']\n';
    $content.val(shortcode + $content.val());
    tb_remove();
  });

  //sortable tile list
  $("#sortable-tile-list").sortable();
  $("#sortable-gallery-list").sortable();
  //insert tile gallery button
  $("body").on("click", "#drstk_insert_tile_gallery", function(e){
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
    tb_remove();
  });

   $("#tabs").tabs().addClass('ui-tabs-vertical ui-helper-clearfix');
   $("#insert-drs").on('click', function(){
     $.post(tile_ajax_obj.ajax_url, {
        _ajax_nonce: tile_ajax_obj.tile_ajax_nonce,
         action: "get_tile_code",
     }, function(data) {
        // $("#TB_ajaxContent #tabs-1").html(data);
        // console.log(data);
        var data = $.parseJSON(data);
        var tile_html = '<h4>Tile Gallery</h4>';
        if (data.pagination.table.total_count > 0){
          console.log("there are more than ten");
          tile_html += '<a href="#" id="drstk_insert_tile_gallery" class="button" title="Insert shortcode">Insert shortcode</a><ol id="sortable-tile-list">';
          $.each(data.items, function(id, item){
            console.log(item.thumbnails);
            tile_html += '<li style="display:inline-block;padding:10px;">';
            tile_html += '<label for="drstile-' + id + '"><img src="' + item.thumbnails[0] + '" width="150" /><br/>';
            tile_html += '<input id="drstile-' + id + '" type="checkbox" class="drstk-include-tile" value="' + item.pid + '" />';
            tile_html += '<span style="width:100px;display:inline-block">' + item.mods.Title + '</span></label>';
            tile_html += '</li>';
          });
          tile_html += "</ol><p>Drag and drop the thumbnails in the order you want them to appear in the playlist. You can un-check the images you wish to exclude entirely.</p>";

          // if (data.pagination.table.num_pages > 1) {
          //   var pagination = "<div class='";
          //   if (data.pagination.table.current_page > 1){
          //     pagination += "'><a href='#' class='prev'><<</a>";
          //   } else {
          //     pagination += "disabled'><span><<</span>";
          //   }
          //   pagination += "</div>";
          //   for (var i = 1; i <= data.pagination.table.num_pages; i++) {
          //     if (data.pagination.table.current_page == i){
          //       var pagination_class = 'current';
          //     } else {
          //       var pagination_class = '';
          //     }
          //     pagination += "<div class='"+pagination_class+"'>";
          //     if (data.pagination.table.current_page == i) {
          //       pagination += "<span>" + i + "</span>";
          //     } else {
          //       pagination += "<a href='#'>" + i + "</a>";
          //     }
          //     pagination += "</div>";
          //   }
          //   pagination += "<div class='";
          //   if (data.pagination.table.current_page == data.pagination.table.num_pages){
          //     pagination += "disabled'><span>>></span>";
          //   } else {
          //     pagination += "'><a href='#' class='next'>>></a>";
          //   }
          //   pagination += "</div>";
          // }
          //   tile_html += '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">'+pagination+'</div></div>';
        } else {
          console.log("there are less than 10");
        }
        $("#TB_ajaxContent #tabs-1").html(tile_html);
      });
   });
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
     var shortcode = '[drstk_gallery id="'+slides+'"';
     if ($("#drstk-slider-caption").is(":checked")){
       shortcode += ' caption="on"';
     }
     if ($("#drstk-slider-auto").is(":checked")){
       shortcode += ' auto="on"';
     }
     if ($("#drstk-slider-nav").is(":checked")){
       shortcode += ' nav="on"';
     }
     if ($("#drstk-slider-speed").val()){
       shortcode += ' speed="'+$("#drstk-slider-speed").val()+'"';
     }
     if ($("#drstk-slider-timeout").val()){
       shortcode += ' timeout="'+$("#drstk-slider-timeout").val()+'"';
     }
     var metadata = [];
     $(".drstk-slider-metadata input[type='checkbox']:checked").each(function(){
       metadata.push($(this).attr('name'));
     });
     if (metadata.length > 0) {shortcode += ' metadata="'+metadata+'"';}
     shortcode += ']\n';
     $content.val(shortcode + $content.val());
     tb_remove();
   });

   $("body").on("click", "button.zoom-options", function(e){
     e.preventDefault();
     $("div.zoom-options").toggleClass('hidden');
   });
   $("body").on("click", "button.gallery-options", function(e){
     e.preventDefault();
     $("div.gallery-options").toggleClass('hidden');
   });

});
