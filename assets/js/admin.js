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
  $('#drstk_insert_video').click(function(e) {
    e.preventDefault();
    $content.val( '[drstk_collection_playlist]\n' + $content.val());
    tb_remove();
  });

  $("body").on("change", ".drstk-include-item", function(e){
    var pid = $(this).val();
    if($(this).is(":checked")){
      $(this).parents("li").siblings("li").hide();
      $(".item-metadata").siblings(".drs-pagination").hide();
      $.post(item_admin_obj.ajax_url, {
         _ajax_nonce: item_admin_obj.item_admin_nonce,
          action: "get_item_admin",
          pid: pid,
      }, function(data) {
          var data = $.parseJSON(data);
          if (data.error){
            $(".item-metadata").html("There was an error: "+data.error);
          } else {
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
      $(".item-metadata").siblings(".drs-pagination").show();
      $(".item-metadata").html("");
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
  $("body").on("click", "#drstk_insert_tile", function(e){
    e.preventDefault();
    var tiles = [];
    $(".drstk-include-tile:checked").each(function(){
      tiles.push($(this).val());
    });
    tiles = tiles.join(", ");
    var shortcode = '[drstk_tiles id="'+tiles+'"]\n';
    $content.val(shortcode + $content.val());
    tb_remove();
  });


  var search_q = '';
  var search_page = 1;
  var search_params = {q:search_q, page:search_page};
   $("#tabs").tabs().addClass('ui-tabs-vertical ui-helper-clearfix');
   $("#insert-drs").on('click', function(){
     get_updated_items(search_params, 'tile');
   });

   $("body").on("click", "button[id^=search-button-]", function(){
     var id = jQuery(this).attr('id');
     id = id.split('-')[2];
     search_params.q = $("#TB_ajaxContent #search-"+id).val();
     get_updated_items(search_params, id);
   });

   $("body").on("click", ".tablenav-pages a", function(){
     val = $(this).html();
     if (val == '&lt;&lt;'){
       val = 1
     }
     if (val == '&gt;&gt;'){
       val = $(this).data('val');
     }
     if ($.isNumeric(val)){
       search_params.page = val;
       var type = $(this).parents(".drs-pagination").siblings(".themebutton").attr('id').split('-')[2]
       get_updated_items(search_params, type);
     }
   });

   function get_updated_items(search_params, name){
     var tile_html = '';
     var tab = 0;
     if(name == 'tile'){ tab = 1}
     if(name == 'gallery'){ tab = 2}
     if(name == 'item'){ tab = 3}
     if(name == 'video'){ tab = 4}
     $.post(tile_ajax_obj.ajax_url, {
        _ajax_nonce: tile_ajax_obj.tile_ajax_nonce,
         action: "get_tile_code",
         params: search_params,
     }, function(data) {
        var data = $.parseJSON(data);
        if (data.response.response.numFound > 0){
          tile_html += '<a href="#" id="drstk_insert_'+name+'" class="button" title="Insert shortcode">Insert shortcode</a><ol id="sortable-'+name+'-list">';
          $.each(data.response.response.docs, function(id, item){
            if (item.active_fedora_model_ssi == 'CoreFile'){
              tile_html += '<li style="display:inline-block;padding:10px;">';
              tile_html += '<label for="drstile-' + id + '"><img src="https://repository.library.northeastern.edu' + item.thumbnail_list_tesim[0] + '" width="150" /><br/>';
              tile_html += '<input id="drstile-' + id + '" type="checkbox" class="drstk-include-'+name+'" value="' + item.id + '" />';
              tile_html += '<span style="width:100px;display:inline-block">' + item.title_ssi + '</span></label>';
              tile_html += '</li>';
            }
          });
          tile_html += "</ol><p>Drag and drop the thumbnails in the order you want them to appear in the playlist. You can un-check the images you wish to exclude entirely.</p>";
          update_pagination(tab, data);
        } else {
          tile_html += "No results were retrieved for your query. Please try a different query.";
        }
        $("#TB_ajaxContent #tabs-"+tab+" .drs-items").html(tile_html);
      });
   }

   function update_pagination(tab, data){
     if (data.pagination.table.num_pages > 1){
         var pagination = "";
         if (data.pagination.table.current_page > 1){
           pagination += "<a href='#' class='prev-page'><<</a>";
         } else {
           pagination += "<a href='#' class='prev-page disabled'><<</a>";
         }
         for (var i = 1; i <= data.pagination.table.num_pages; i++) {
           if (data.pagination.table.current_page == i){
             var pagination_class = 'current-page disabled';
           } else {
             var pagination_class = '';
           }
             pagination += "<a href='#' class='"+pagination_class+"'>" + i + "</a>";
         }
         if (data.pagination.table.current_page == data.pagination.table.num_pages){
           pagination += "<a href='#' class='next-page' data-val='"+data.pagination.table.num_pages+"'>>></a>";
         } else {
           pagination += "<a href='#' class='next-page disabled' data-val='"+data.pagination.table.num_pages+"'>>></a>";
         }
         $("#TB_ajaxContent #tabs-"+tab+" .drs-pagination").html("<span class='tablenav'><span class='tablenav-pages'>" + pagination + "</span></span>");
     }
   }

   $("[id^=ui-id-]").on("click", function(e){
     var id = $(this).attr('id');
     id = id.substr(id.length - 1);
     search_params.q = '';
     search_params.page = 1;
     if (id == 4){
       $.post(video_ajax_obj.ajax_url, {
          _ajax_nonce: video_ajax_obj.video_ajax_nonce,
           action: "get_video_code",
       }, function(data) {
          $("#TB_ajaxContent #tabs-4").html(data);
        });
     }
     if (id == 3){
       get_updated_items(search_params, 'item');
     }
     if (id == 2){
       get_updated_items(search_params, 'gallery');
     }
   });

   //insert gallery button
  $("body").on("click", "#drstk_insert_gallery", function(e){
     e.preventDefault();
     var slides = [];
     $(".drstk-include-gallery:checked").each(function(){
       slides.push($(this).val());
     });
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
