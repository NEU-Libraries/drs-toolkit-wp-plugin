/**
 * JavaScript for the registration page.
 **/

 jQuery( document ).ready(function( $ ) {
  //variables for generating the lists of items
  var search_q = '';
  var search_page = 1;
  var search_params = {q:search_q, page:search_page};


  //enables tabs
 $("#tabs").tabs().addClass('ui-tabs-vertical ui-helper-clearfix');
 $("#tabs-1").html('<h4>Tile Gallery</h4><br/><label for="search">Search for an item: </label><input type="text" name="search" id="search-tile" /><button class="themebutton" id="search-button-tile">Search</button><br/><button class="tile-options button"><span class="dashicons dashicons-admin-generic"></span></button><div class="hidden tile-options"><label for="tile-type">Type Layout Type</label><select name="tile-type" id="drstk-tile-type"><option value="pinterest-below">Pinterest style with caption below</option><option value="pinterest-hover">Pinterest style with caption on hover</option><option value="even-row">Even rows with caption on hover</option><option value="square">Even Squares with caption on hover</option></select><br/><label for="caption-align">Caption Text Alignment</label><select name="caption-align" id="drstk-tile-caption-align"><option value="center">Center</option><option value="left">Left</option><option value="right">Right</option></select><br/><label for="cell-height">Cell Height (auto for Pinterest style)</label><input type="number" value="200" name="cell-height"/></label><br/><label for="cell-width">Cell Width</label><input type="number" value="200" name="cell-width"/></label><p>Make the height and width the same for squares</p><br/><label for="drstk-tile-image-size">Image Size<select name="drstk-tile-image-size" id="drstk-tile-image-size"><option value="1">Largest side is 85px</option><option value="2">Largest side is 170px</option><option value="3">Largest side is 340px</option><option value="4" selected="selected">Largest side is 500px</option><option value="5">Largest side is 1000px</option></select></label><br/><div class="drstk-tile-metadata"><h5>Metadata for Captions</h5><label><input type="checkbox" name="Title" checked="checked"/>Title</label><br/><label><input type="checkbox" name="Creator,Contributor"/>Creator,Contributor</label><br/><label><input type="checkbox" name="Date created"/>Date Created</label><br/><label><input type="checkbox" name="Abstract/Description"/>Abstract/Description</label></div></div><div class="drs-items">Loading...</div><ol id="sortable-tile-list"></ol><div class="drs-pagination"></div><input type="hidden" class="selected-tile" />');


 //enables the tabs to get their content dynamically
 $("[id^=ui-id-]").on("click", function(e){ 
   var id = $(this).attr('id');
   id = id.substr(id.length - 1);
   search_params.q = '';
   search_params.page = 1;
   if (id == 6){
     $("#TB_ajaxContent #tabs-6").html('<h4>Timeline</h4><br/><label for="search">Search for timeline item: </label><input type="text" name="search" id="search-timeline" /><button class="themebutton" id="search-button-timeline">Search</button><br/><button class="zoom-options button"><span class="dashicons dashicons-admin-generic"></span></button><div class="hidden zoom-options"><label for="drstk-timeline-start-date-boundary">Start Date Boundary<input type="text" placeholder="year eg:1960" id="start-date-boundary"></label><br/><label for="drstk-timeline-end-date-boundary">End Date Boundary<input type="text" placeholder="year eg:2000" id="end-date-boundary"></label><br/><label for="drstk-timeline-increments">Scale Increments</label><select name="drstk-timeline-increments" id="drstk-timeline-increments"><option value="0.5">0.5</option><option value="2">2</option><option value="5">5</option><option value="8">8</option><option value="13">13</option></select><br/><i>Note : Higher the Increment, more granular the dates</i><br/><label for="drstk-timeline-legend">Legend Description </br> Red <input type="text" id="timeline_redlegend"></br>Blue <input type="text" id="timeline_bluelegend"></br>Green <input type="text" id="timeline_greenlegend"></br>Yellow <input type="text" id="timeline_yellowlegend"></br>Orange <input type="text" id="timeline_orangelegend"></label></br></div><hr/><div class="item-metadata"></div><div class="drs-items"></div><ol id="sortable-timeline-list"></ol><div class="drs-pagination"></div><input type="hidden" class="selected-timeline" />');
     get_updated_items(search_params, 'timeline');
   }
   if (id == 5){
     $("#TB_ajaxContent #tabs-5").html('<h4>Map</h4><br/><label for="search">Search for map item: </label><input type="text" name="search" id="search-map" /><button class="themebutton" id="search-button-map">Search</button><br/><button class="map-options button"><span class="dashicons dashicons-admin-generic"></span></button><div class="hidden map-options"><label for="drstk-map-legend">Legend Description </br> Red <input type="text" id="redlegend"></br>Blue <input type="text" id="bluelegend"></br>Green <input type="text" id="greenlegend"></br>Yellow <input type="text" id="yellowlegend"></br>Orange <input type="text" id="orangelegend"></label></br><div class="drstk-map-metadata"><h5>Metadata for Maps</h5><label><input type="checkbox" name="Creator,Contributor"/>Creator,Contributor</label><br/><label><input type="checkbox" name="Date created"/>Date Created</label><br/><label><input type="checkbox" name="Abstract/Description"/>Abstract/Description</label></div></div><div class="drs-items"></div><hr/><ol id="sortable-map-list"></ol><div class="drs-pagination"></div><input type="hidden" class="selected-map" />');
     get_updated_items(search_params, 'map');
   }
   if (id == 4){
     $("#TB_ajaxContent #tabs-4").html('<div class="drs-items"></div><button class="video-options button"><span class="dashicons dashicons-admin-generic"></span></button><div class="hidden video-options"><label for="drstk-video-height">Height: <input type="text" name="drstk-video-height" id="drstk-video-height" />(Enter in pixels or %, Default is 270)</label><br/><label for="drstk-video-width">Width: <input type="text" name="drstk-video-width" id="drstk-video-width" />(Enter in pixels or %, Default is 100%)</label><br/></div><ol id="sortable-video-list"></ol><div class="drs-pagination"></div>');
     $("#TB_ajaxContent #tabs-4").prepend('<h4>Media Playlist</h4><input type="hidden" class="selected-video" />');
     get_updated_items(search_params, 'video');
   }
   if (id == 3){
     $("#TB_ajaxContent #tabs-3").html('<h4>Item</h4><br/><label for="search">Search for an item: </label><input type="text" name="search" id="search-item" /><button class="themebutton" id="search-button-item">Search</button><br/><button class="zoom-options button"><span class="dashicons dashicons-admin-generic"></span></button><div class="hidden zoom-options"><label for="drstk-item-align">Image Alignment<select id="drstk-item-align" name="drstk-item-align"><option value="left">Left</option><option value="right">Right</option><option value="center">Center</option></select></label><br/><label for="drstk-item-caption-align">Caption Alignment<select id="drstk-item-caption-align" name="drstk-item-caption-align"><option value="left">Left</option><option value="right">Right</option><option value="center" selected="selected">Center</option></select></label><br/><label for="drstk-item-image-size">Image Size<select name="drstk-item-image-size" id="drstk-item-image-size"><option value="1">Largest side is 85px</option><option value="2">Largest side is 170px</option><option value="3">Largest side is 340px</option><option value="4" selected="selected">Largest side is 500px</option><option value="5">Largest side is 1000px</option></select></label><br/><label for="drstk-item-zoom"><input id="drstk-item-zoom" name="drstk-item-zoom" value="yes" type="checkbox" />Enable zoom</label><br/><label for="drstk-item-zoom-inner"><input id="drstk-item-zoom-inner" name="drstk-item-zoom-inner" value="yes" type="checkbox" />Zoom inside image</label><br/><label for="drstk-item-zoom-window">Zoom position (outside image)<select name="drstk-item-zoom-window" id="drstk-item-zoom-window"><option value="0">Select Position</option><option value="1">Top Right</option><option value="2">Middle Right</option><option value="3">Bottom Right</option><option value="4">Bottom Corner Right</option><option value="5">Under Right</option><option value="6">Under Middle</option><option value="7">Under Left</option><option value="8">Bottom Corner Left </option><option value="9">Bottom Left</option><option value="10">Middle Left</option><option value="11">Top Left</option><option value="12">Top Corner Left</option><option value="12">Above Left</option><option value="14">Above Middle</option><option value="15">Above Right</option><option value="16">Top Right Corner</option></select><br><i>Recommended and Default position:Top Right</i></div><hr/><div class="item-metadata"></div><div class="drs-items"></div><ol id="sortable-item-list"></ol><div class="drs-pagination"></div></div>');
     get_updated_items(search_params, 'item');
   }
   if (id == 2){
     $("#TB_ajaxContent #tabs-2").html('<h4>Gallery Slider</h4><br/><label for="search">Search for an item: </label><input type="text" name="search" id="search-gallery" /><button class="themebutton" id="search-button-gallery">Search</button><br/><button class="gallery-options button"><span class="dashicons dashicons-admin-generic"></span></button><div class="hidden gallery-options"><label for="drstk-slider-auto"><input type="checkbox" name="drstk-slider-auto" id="drstk-slider-auto" value="yes" checked="checked" />Auto rotate</label><br/><label for="drstk-slider-nav"><input type="checkbox" name="drstk-slider-nav" id="drstk-slider-nav" value="yes" checked="checked" />Next/Prev Buttons</label><br/><label for="drstk-slider-pager"><input type="checkbox" name="drstk-slider-pager" id="drstk-slider-pager" value="yes" checked="checked" />Dot Pager</label><br/><label for="drstk-slider-speed">Rotation Speed<input type="text" name="drstk-slider-speed" id="drstk-slider-speed" />(Speed is in milliseconds. 5000 milliseconds = 5 seconds)</label><br/><label for="drstk-slider-max-height">Max Height<input type="number" name="drstk-slider-max-height" id="drstk-slider-max-height" /></label><br/><label for="drstk-slider-max-width">Max Width<input type="text" name="drstk-slider-max-width" id="drstk-slider-max-width" /></label><br/><label for="drstk-slider-image-size">Image Size<select name="drstk-slider-image-size" id="drstk-slider-image-size"><option value="1">Largest side is 85px</option><option value="2">Largest side is 170px</option><option value="3">Largest side is 340px</option><option value="4" selected="selected">Largest side is 500px</option><option value="5">Largest side is 1000px</option></select></label><br/><label for="drstk-slider-caption"><input type="checkbox" name="drstk-slider-caption" id="drstk-slider-caption" value="yes" checked="checked"/>Enable captions</label><br/><div class="drstk-slider-metadata"><label for="drstk-slider-caption-align">Caption Alignment<select name="drstk-slider-caption-align" id="drstk-slider-caption-align"><option value="left">Left</option><option value="right">Right</option><option value="center" selected="selected">Center</option></select></label><br/><label for="drstk-slider-caption-position">Caption Position<select name="drstk-slider-caption-position" id="drstk-slider-caption-position"><option value="absolute">Over Image</option><option value="relative">Below Image</option></select></label><br/><h5>Metadata for Captions</h5><label><input type="checkbox" name="Title" checked="checked"/>Title</label><br/><label><input type="checkbox" name="Creator,Contributor" checked="checked"/>Creator</label><br/><label><input type="checkbox" name="Date created"/>Date Created</label><br/><label><input type="checkbox" name="Abstract/Description"/>Abstract/Description</label></div></div><div class="drs-items"></div><ol id="sortable-gallery-list"></ol><div class="drs-pagination"></div><input type="hidden" class="selected-gallery" />');
     get_updated_items(search_params, 'gallery');
   }
 });

 //click the main add drs button
 $("body").on('click', "#insert-drs",  function(){
  //  $("#TB_ajaxContent #tabs-1 .drs-items").html("Loading...");
   get_updated_items(search_params, 'tile');
 });

   //when an item is selected
  $("body").on("change", "[class^='drstk-include-']", function(e){
    var pid = $(this).val();
    var type = $(this).attr("class").split("-")[2];
      var divid = $(this).attr("id").split("-")[1];
      //alert(divid)
      //alert(divid)

    if (type == 'item'){
      if($(this).is(":checked")){
        $(this).parents("li").siblings("li").hide();
        $(".item-metadata").siblings(".drs-pagination").hide();
        var errors = $.parseJSON(item_admin_obj.errors);
        $.post(item_admin_obj.ajax_url, {
           _ajax_nonce: item_admin_obj.item_admin_nonce,
            action: "get_item_admin",
            pid: pid,
        }, function(data) {
            var data = $.parseJSON(data);
            if (data.error){
              $(".item-metadata").html(errors.admin.api_fail);
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
          $(".item-metadata").html(errors.admin.api_fail);
        });
      } else {
        $(this).parents("li").siblings("li").show();
        $(".item-metadata").siblings(".drs-pagination").show();
        $(".item-metadata").html("");
      }
    }
      if(type == 'gallery' || type == 'tile' || type == 'video' || type == 'map' || type == 'timeline'){
          var selected = $(".selected-"+type).val();
          if ( selected == ''){
              $(".selected-"+type).val(pid);
          } else {
              $(".selected-"+type).val(selected + ", " + pid);
          } if (type === 'map') {
              var map_color_options = "<p>Grouping:</p><select class='map_group_selection-"+divid+ "'><option value='please_select_option'>Please select a group</option><option value='red'>Red</option> <option value='blue'>Blue</option> <option value='green'>Green</option> <option value='yellow'>Yellow</option> <option value='orange'>Orange</option></select>"
              //alert(divid)
              //alert("Map is selected!")
              $("label[for='drstile-" + divid + "']").append(map_color_options);
          } if (type === 'timeline') {
				var timeline_color_options = "<div id='timeline_div-"+divid+"'><p>Grouping:</p><select class='timeline_group_selection-"+divid+ "'><option value='please_select_option'>Please select a group</option><option value='red'>Red</option> <option value='blue'>Blue</option> <option value='green'>Green</option> <option value='yellow'>Yellow</option> <option value='orange'>Orange</option></select></div>";
				if(!$(".timeline_group_selection-"+divid).is(':visible')){
					$("label[for='drstile-" + divid + "']").append(timeline_color_options);
				}
				
				if($(".timeline_group_selection-"+divid).is(':visible') && !$(this).is(":checked")){
					$("#timeline_div-"+divid).remove();
					$("#timeline_div-"+divid).remove();
				}
		  }
		  
		  $("body").on("change","[class^='timeline_group_selection-" + divid + "']", function() {
			  var timeline_dropdown_value = $(this).val();
			  var color_codes = ['red', 'blue', 'green', 'yellow', 'orange'];
			  color_codes.forEach(function(color_code){
					var current_color_group_attribute = color_code + "_group";
					var current_group_value = $(".selected-timeline").attr(current_color_group_attribute);
					if(current_group_value == undefined && timeline_dropdown_value == color_code){
						$(".selected-timeline").attr(current_color_group_attribute, pid);
						timeline_dropdown_value = '';
					}else if(current_group_value != undefined && timeline_dropdown_value == color_code){
						$(".selected-timeline").attr(current_color_group_attribute, current_group_value + ", " + pid);
						timeline_dropdown_value = '';
					}
			  });
		  })
		  
          //When a user changes map dropdown
          $("body").on("change","[class^='map_group_selection-" + divid + "']", function() {
              console.log("Change triggered!");
              var dropdown_value = $(this).val();
              console.log(dropdown_value);
              var red_group = $(".selected-map").attr("red_group");
              var blue_group = $(".selected-map").attr("blue_group");
              var green_group = $(".selected-map").attr("green_group");
              var yellow_group = $(".selected-map").attr("yellow_group");
              var orange_group = $(".selected-map").attr("orange_group");
              //Red cases
              if(red_group == undefined && dropdown_value == 'red') {
                  $(".selected-map").attr("red_group", pid);
                  dropdown_value = ''
              } if (red_group != undefined && dropdown_value == 'red' && dropdown_value != '') {
                  $(".selected-map").attr("red_group", red_group + ", " + pid);
                  dropdown_value = ''
              }

              //Blue cases
              if(blue_group == undefined && dropdown_value == 'blue') {
                  $(".selected-map").attr("blue_group", pid);
                  dropdown_value = ''
              } if (blue_group != undefined && dropdown_value == 'blue' && dropdown_value != '') {
                  $(".selected-map").attr("blue_group", blue_group + ", " + pid);
                  dropdown_value = ''
              }

              //Green cases
              if(green_group == undefined && dropdown_value == 'green') {
                  $(".selected-map").attr("green_group", pid);
                  dropdown_value = ''
              } if (green_group != undefined && dropdown_value == 'green' && dropdown_value != '') {
                  $(".selected-map").attr("green_group", green_group + ", " + pid);
                  dropdown_value = ''
              }

              //Yellow cases
              if(yellow_group == undefined && dropdown_value == 'yellow') {
                  $(".selected-map").attr("yellow_group", pid);
                  dropdown_value = ''
              } if (yellow_group != undefined && dropdown_value == 'yellow' && dropdown_value != '') {
                  $(".selected-map").attr("yellow_group", yellow_group + ", " + pid);
                  dropdown_value = ''
              }

              //Orange cases
              if(orange_group == undefined && dropdown_value == 'orange') {
                  $(".selected-map").attr("orange_group", pid);
                  dropdown_value = ''
              } if (orange_group != undefined && dropdown_value == 'orange' && dropdown_value != '') {
                  $(".selected-map").attr("orange_group", orange_group + ", " + pid);
                  dropdown_value = ''
              }


          })
      }

  });

  //enables the search button
   $("body").on("click", "button[id^=search-button-]", function(){
     var id = jQuery(this).attr('id');
     id = id.split('-')[2];
     search_params.q = $("#TB_ajaxContent #search-"+id).val();
     get_updated_items(search_params, id);
   });

   //enables the pagination
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
	 if(name == 'map'){ tab = 5}
	 if(name == 'timeline'){ tab = 6}
     $("#TB_ajaxContent #tabs-"+tab+" .drs-items").html("Loading...");
     $.post(tile_ajax_obj.ajax_url, {
        _ajax_nonce: tile_ajax_obj.tile_ajax_nonce,
         action: "get_tile_code",
         params: search_params,
     }, function(data) {
        var data = $.parseJSON(data);
        if (data.response.response.numFound > 0){
          $("#sortable-"+name+"-list").children("li").remove();
          var media_count = 0;
          $.each(data.response.response.docs, function(id, item){
            if (item.active_fedora_model_ssi == 'CoreFile'){
              if (tab == 4){
                if (item.canonical_class_tesim == 'AudioFile' || item.canonical_class_tesim == 'VideoFile'){
                  $("#sortable-"+name+"-list").append('<li style="display:inline-block;padding:10px;"><label for="drstile-' + id + '"><img src="https://repository.library.northeastern.edu' + item.thumbnail_list_tesim[0] + '" width="150" /><br/><input id="drstile-' + id + '" type="checkbox" class="drstk-include-'+name+'" value="' + item.id + '" /><span style="width:100px;display:inline-block">' + item.full_title_ssi + '</span></label></li>');
                  media_count++;
                  data.pagination.table.num_pages = Math.ceil(media_count / 10);
                }
              } if (tab == 5 || tab == 6){ //Maps and Timeline
                 if ((tab == 5 && get_item_geographic_or_date_handler(item.id, true, false)) || (tab == 6 && get_item_geographic_or_date_handler(item.id, false, true))){
                        $("#sortable-"+name+"-list").append('<li style="display:inline-block;padding:10px;"><label for="drstile-' + id + '"><img src="https://repository.library.northeastern.edu' + item.thumbnail_list_tesim[0] + '" width="150" /><br/><input id="drstile-' + id + '" type="checkbox" class="drstk-include-'+name+'" value="' + item.id + '" /><span style="width:100px;display:inline-block">' + item.full_title_ssi + '</span></label></li>');
                        media_count++;
                        data.pagination.table.num_pages = Math.ceil(media_count / 10);
                    } else {
                     //console.log("Was unable to find an item with geo!")
                 }
                } else {
                $("#sortable-"+name+"-list").append('<li style="display:inline-block;padding:10px;"><label for="drstile-' + id + '"><img src="https://repository.library.northeastern.edu' + item.thumbnail_list_tesim[0] + '" width="150" /><br/><input id="drstile-' + id + '" type="checkbox" class="drstk-include-'+name+'" value="' + item.id + '" /><span style="width:100px;display:inline-block">' + item.full_title_ssi + '</span></label></li>');
              }
            }
          });
          update_pagination(tab, data);
        } else {
          $("#TB_ajaxContent #tabs-"+tab+" .drs-items").html("No results were retrieved for your query. Please try a different query.");
        }
        $("#TB_ajaxContent #tabs-"+tab+" .drs-items").html('<a href="#" id="drstk_insert_'+name+'" class="button" title="Insert shortcode">Insert shortcode</a><p>Drag and drop the thumbnails in the order you want them to appear in the playlist. You can un-check the images you wish to exclude entirely.</p>');

      });
      $("#sortable-"+name+"-list").sortable();
   }

     function get_item_geographic_or_date_handler(itemid, mapsBool, timelineBool) {
         return get_item_geographic_or_date(itemid, mapsBool, timelineBool)
     }

     function get_item_geographic_or_date(item, mapsBool, timelineBool) {
         var genericBoolState = false;
         //AJAX call will be passed to internal WP AJAX
         $.ajax({
             async: false,
             url: ajaxurl,
             data: {
                 'action':'get_json_data_from_neu_item',
                 'item' : item
             },
             success:function(data) {
                 //Parsing data is no longer needed, as get_geolocation_from_item returns parsed data
                 //parseddata = JSON.parse(data)
                 //This console.log  is just for debugging.
                 //console.log(parseddata.pid + " " + parseddata.geographic)
                 //console.log(data.geographic)
                 if ((data && data.geographic && data.geographic.length && mapsBool) || (data && data.key_date && timelineBool) || data && data.coordinates && data.coordinates.length && mapsBool)  {
                     //console.log(data.geographic)
                     genericBoolState = true;
                 } else {
                     genericBoolState = false;
                 }
             },
             error: function(errorThrown){
                 console.log(errorThrown);
             }
         });

         return genericBoolState;
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

   //inserting the shortcodes
   $("body").on("click", "[id^=drstk_insert_]", function(e){
     e.preventDefault();
     var type = $(this).attr("id").split("_")[2];
     var shortcode = '';
     var timeline_bool = true;
     if(type == 'gallery'){
      var slides = $(".selected-"+type).val();
       shortcode = '[drstk_gallery id="'+slides+'"';
       if ($("#drstk-slider-caption").is(":checked")){
         shortcode += ' caption="on"';
       }
       shortcode += ' caption-align="'+$("#drstk-slider-caption-align").val()+'"';
       shortcode += ' caption-position="'+$("#drstk-slider-caption-position").val()+'"';
       if ($("#drstk-slider-auto").is(":checked")){
         shortcode += ' auto="on"';
       }
       if ($("#drstk-slider-nav").is(":checked")){
         shortcode += ' nav="on"';
       }
       if ($("#drstk-slider-speed").val()){
         shortcode += ' speed="'+$("#drstk-slider-speed").val()+'"';
       }
       if ($("#drstk-slider-max-height").val() > 0){
         shortcode += ' max-height="'+$("#drstk-slider-max-height").val()+'"';
       }
       if ($("#drstk-slider-max-width").val() > 0){
         shortcode += ' max-width="'+$("#drstk-slider-max-width").val()+'"';
       }
       if ($("#drstk-slider-image-size").val() > 0){
         shortcode += ' image-size="'+$("#drstk-slider-image-size").val()+'"';
       }
       var metadata = [];
       $(this).parent(".drs-items").siblings("div.gallery-options").find(".drstk-slider-metadata input[type='checkbox']:checked").each(function(){
         metadata.push($(this).attr('name'));
       });
       if (metadata.length > 0) {shortcode += ' metadata="'+metadata+'"';}
       shortcode += ']\n';
     }
     if(type == 'tile'){
       var tiles = $(".selected-"+type).val();
       shortcode = '[drstk_tiles id="'+tiles+'"';
       shortcode += ' type="'+$("#TB_ajaxContent #drstk-tile-type").val()+'"';
       var metadata = [];
       $(this).parent(".drs-items").siblings("div.tile-options").find(".drstk-tile-metadata input[type='checkbox']:checked").each(function(){
         metadata.push($(this).attr('name'));
       });
       if (metadata.length > 0) {shortcode += ' metadata="'+metadata+'"';}
       var cell_width = $(this).parent(".drs-items").siblings("div.tile-options").find("input[name='cell-width']").val();
       if ($.isNumeric(cell_width)){
         shortcode += ' cell-width="'+cell_width+'"';
       }
       var cell_height = $(this).parent(".drs-items").siblings("div.tile-options").find("input[name='cell-height']").val();
       if ($.isNumeric(cell_height)){
         shortcode += ' cell-height="'+cell_height+'"';
       }
       if ($("#drstk-tile-image-size").val() > 0){
         shortcode += ' image-size="'+$("#drstk-tile-image-size").val()+'"';
       }
       shortcode += ' text-align="'+$("#TB_ajaxContent #drstk-tile-caption-align").val()+'"';
       shortcode += ']\n';
     }
     if(type == 'item'){
       var pid = '';
       $(".drstk-include-item").each(function(){
         if ($(this).is(":visible")){
           pid = $(this).val();
         }
       });
       var metadata = [];
       $(this).parent(".drs-items").siblings("div.item-metadata").find("input[type='checkbox']:checked").each(function(){
         metadata.push($(this).attr('name'));
       });
       shortcode = '[drstk_item id="'+pid+'"';
       if ($("#drstk-item-zoom").is(":checked")){
         shortcode += ' zoom="on"';
       }
       if ($("#drstk-item-zoom-inner").is(":checked") && $("#drstk-item-zoom-window").val() == 0){
         shortcode += ' zoom_position="inner"';
       }
       if ($("#drstk-item-zoom-window").val() > 0){
         shortcode += ' zoom_position="'+$("#drstk-item-zoom-window").val()+'"';
       }
       shortcode += ' align="'+$("#drstk-item-align").val()+'"';
       shortcode += ' caption-align="'+$("#drstk-item-caption-align").val()+'"';
       if ($("#drstk-item-image-size").val() > 0){
         shortcode += ' image-size="'+$("#drstk-item-image-size").val()+'"';
       }
       if (metadata.length > 0) {shortcode += ' metadata="'+metadata+'"';}
       shortcode +=']\n';
     }
     if(type == 'video'){
        var videos = $(".selected-"+type).val();
        shortcode = '[drstk_collection_playlist id="'+videos+'"';
        var width = $(this).parent(".drs-items").siblings("div.video-options").find("input[name='drstk-video-width']").val();
        shortcode += ' width="'+width+'"';
        var height = $(this).parent(".drs-items").siblings("div.video-options").find("input[name='drstk-video-height']").val();
        shortcode += ' height="'+height+'"';
        shortcode += ']\n';
     }
	 if(type == 'map'){
        var mapValue = $(".selected-"+type).val();
        shortcode = '[drstk_map id="'+mapValue+'" ';
		shortcode += 'map_api_key="'+item_admin_obj.leaflet_api_key+'" ';
         shortcode += 'map_project_key="'+item_admin_obj.leaflet_project_key+'" ';
         shortcode += 'red_legend_desc="'+$("#redlegend").val()+'" ';
         shortcode += 'blue_legend_desc="'+$("#bluelegend").val()+'" ';
         shortcode += 'green_legend_desc="'+$("#greenlegend").val()+'" ';
         shortcode += 'yellow_legend_desc="'+$("#yellowlegend").val()+'" ';
         shortcode += 'orange_legend_desc="'+$("#orangelegend").val()+'" ';
         var metadata = [];
         $(".drstk-map-metadata input[type='checkbox']:checked").each(function(){
             metadata.push($(this).attr('name'));
         });
         if (metadata.length > 0) {shortcode += ' metadata="'+metadata+'" ';}
         var red_group = $(".selected-"+type).attr('red_group')
         var blue_group = $(".selected-"+type).attr('blue_group')
         var green_group = $(".selected-"+type).attr('green_group')
         var yellow_group = $(".selected-"+type).attr('yellow_group')
         var orange_group = $(".selected-"+type).attr('orange_group')
         shortcode += 'red="'+ red_group +'" ';
         shortcode += 'blue="'+ blue_group +'" ';
         shortcode += 'green="'+ green_group +'" ';
         shortcode += 'yellow="'+ yellow_group +'" ';
         shortcode += 'orange="'+ orange_group +'" ';
        shortcode += ']\n';
     }
     if(type == 'timeline'){
		 var start_date = $("#start-date-boundary").val();
		 var end_date = $("#end-date-boundary").val();		 
		 var timelineValue = $(".selected-"+type).val();
		 var color_codes = ['red', 'blue', 'green', 'yellow', 'orange'];
		 if((start_date != '') || (end_date != '')){
			 if((isNumeric(start_date) && isNumeric(end_date))){
				 
				var key_date_list = [];				
				var itemList = timelineValue.split(",").forEach(function(neuid){
					$.ajax({
					 async: false,
					 url: ajaxurl,
					 data: {
					 'action':'get_json_data_from_neu_item',
					 'item' : neuid.trim()
					 },
					 success:function(data) {
						 var itemName = Object.keys(data.breadcrumbs)[1];
						 var key_date_year = Object.keys(data.key_date)[0].split("/")[0];
						 key_date_list.push({year:key_date_year, name:data.breadcrumbs[itemName]});
					 },
					 error: function(errorThrown){
						 console.log(errorThrown);
					 }
					});
				});				
				key_date_list.forEach(function(each_key){			
					if(each_key.year < start_date || each_key.year > end_date){
						timeline_bool = false;
						alert("The corresponding item : '" + each_key.name + "' is out of the specified boundary dates");
					}			
				});
			}
			else{
				timeline_bool = false;
				alert("The Start Date or End Date is not numeric");
			}
		}
		shortcode = '[drstk_timeline id="'+timelineValue+'"';
		shortcode += ' increments="'+$("#drstk-timeline-increments").val()+'" ';
		var color_code_values = '';
		var color_code_description = '';
		color_codes.forEach(function(color_code){
			 var color_code_group_attribute = color_code + '_group';
			 var current_color_code_value = $(".selected-"+type).attr(color_code_group_attribute);
			 var color_code_legend_id = '#timeline_' + color_code + 'legend';
			 var current_color_code_description = $(color_code_legend_id).val();
			 
			 if(current_color_code_value != undefined){
				 color_code_values += color_code + '_id="' + current_color_code_value +'" ';
			 }
			 if(current_color_code_description != ''){
				 color_code_description += color_code + '_desc="' + current_color_code_description +'" ';
			 }
		 });
		 if(color_code_values != '' && color_code_description != ''){
			 shortcode += color_code_values;
			 shortcode += color_code_description;
		 }
		shortcode += ']\n';
     
	}
	 if(timeline_bool){
		 window.wp.media.editor.insert(shortcode);
	 }
   })
   
   function isNumeric(n) {
	  return !isNaN(parseFloat(n)) && isFinite(n);
	}

   //enables settings toggle
   $("body").on("click", "button[class*='-options']", function(e){
     e.preventDefault();
     var type = $(this).attr("class").split("-")[0];
     $("div."+type+"-options").toggleClass('hidden');
   });

});
