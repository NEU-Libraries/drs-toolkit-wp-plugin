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

  $('#drstk_item_insert_shortcode').click(function(e) {
    e.preventDefault();
    var pid = $("#drstk_item_url").val();
    var zoom = $("#drstk_item_zoom").val();
    console.log(zoom);
    pid = pid.split("/");
    pid = pid[pid.length-1];
    var shortcode = '[drstk_item id="'+pid+'"';
    if (zoom == 'on'){
      shortcode += ' zoom="on"';
    }
    shortcode += ']\n';
    $content.val(shortcode + $content.val());
  });

});
