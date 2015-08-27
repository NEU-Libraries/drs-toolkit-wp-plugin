jQuery(document).ready(function($) {
  var item_pid = breadcrumb_obj.item_pid;
  var sub_collection_pid = breadcrumb_obj.sub_collection_pid;
  var collection_pid = breadcrumb_obj.collection_pid;
  var params = {};
  var template = breadcrumb_obj.template;
  var site_url = '';
  if (template == 'item') {
    params = {pid:item_pid};
  } else if (template == 'collection'){
    params = {pid:sub_collection_pid};
  }
  get_data(params);

  function get_data(params){
    $.post(breadcrumb_obj.ajax_url, {
       _ajax_nonce: breadcrumb_obj.nonce,
        action: "get_breadcrumb",
        pid: params.pid,

    }, function(data) {
        var data = $.parseJSON(data);
        site_url = data.site_url;
        if (jQuery.type(data) == 'object') {
          parse_breadcrumb(data.response.response);
        }
    }).fail(function() {
      $("#drs-content").html("<div class='alert error'>There was an error connecting to the external service. Please try a different request. Thanks!</div>");
    });
  }
  function parse_breadcrumb(data){
    var doc_vals = data.docs[0]
    var title = doc_vals.title_info_title_ssi;
    var parent = '';
    var object_type = doc_vals.active_fedora_model_ssi;
    if (object_type == 'CoreFile'){
      var object_url = '/item/'+doc_vals.id;
    }
    if (object_type == 'Collection'){
      var object_url = '/collection/'+doc_vals.id;
    }
    $("#drs-breadcrumbs").prepend(" > " + "<a href='"+site_url+object_url+"'>" + title + "</a>");

    if (doc_vals.fields_parent_id_tesim) {
      parent = doc_vals.fields_parent_id_tesim[0];
      if (parent != collection_pid) {
        params.pid = parent;
        get_data(params);
      } else {
        $("#drs-breadcrumbs").prepend("<a href='"+site_url+"/browse'>Browse</a>");
      }
    }
    if ((template == 'collection') && (doc_vals.id == sub_collection_pid)){
      $(".page-title").html(title + " <i>Collection</i>");
    }
  }
});
