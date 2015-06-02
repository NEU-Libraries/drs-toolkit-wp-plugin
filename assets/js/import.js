jQuery(document).ready(function($) {
  $("#drstk-import").on("click", function(e){
    e.preventDefault();
    $.post(import_obj.ajax_url, {
       _ajax_nonce: import_obj.nonce,
        action: "get_import",
        pid: import_obj.pid,

    }, function(data) {
        var data = $.parseJSON(data);
        console.log(data)
    }).fail(function() {
      $("#drs-content").html("<div class='alert error'>There was an error connecting to the external service. Please try a different request. Thanks!</div>");
    });
  });
});
