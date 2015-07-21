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

  $("#drstk_get_item_meta").click(function(e){
    e.preventDefault();
    var pid = $("#drstk_item_url").val();
    pid = pid.split("/");
    pid = pid[pid.length-1];
    $.post(item_admin_obj.ajax_url, {
       _ajax_nonce: item_admin_obj.item_admin_nonce,
        action: "get_item_admin",
        pid: pid,
    }, function(data) {
        var data = $.parseJSON(data);
        if (data.error){
          $(".item-metadata").html("There was an error: "+data.error);
        } else {
          console.log(data);
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
  })

  $('#drstk_item_insert_shortcode').click(function(e) {
    e.preventDefault();
    var pid = $("#drstk_item_url").val();
    var zoom = $("#drstk_item_zoom").is(":checked");
    var metadata = {};
    $(".item-metadata input[type='checkbox']:checked").each(function(){
      metadata[$(this).attr('name')] = $(this).val();
    });
    pid = pid.split("/");
    pid = pid[pid.length-1];
    var shortcode = '[drstk_item id="'+pid+'"';
    if (zoom == true){
      shortcode += ' zoom="on"';
    }
    if (Object.keys(metadata).length > 0){
      for (var key in metadata){
        var pretty_key = key.toLowerCase().replace(/\W/g, '_');
        shortcode += ' '+pretty_key+'="'+metadata[key]+'"';
      }
    }
    shortcode += ']\n';
    $content.val(shortcode + $content.val());
  });

});
