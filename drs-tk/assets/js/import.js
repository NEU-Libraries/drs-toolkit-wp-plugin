jQuery(document).ready(function($) {
  $("#drstk-import").on("click", function(e){
    e.preventDefault();
    $(this).after("<div class='spinner is-active'></div>");
    $(".spinner").css("float","none");
    $.post(import_obj.ajax_url, {
       _ajax_nonce: import_obj.nonce,
        action: "get_import",
        pid: import_obj.pid,
    }, function(data) {
        var data = $.parseJSON(data);
        $(".spinner").removeClass('is-active');
        console.log(data);
        $("#drstk-import").after("<div class='updated notice'><p>Import completed of "+data.count+" objects. "+data.existing_count+" were already present in the Media library.</p></div>");
    }).fail(function() {
      $(".spinner").removeClass('is-active');
      $("#drstk-import").after("<div class='error notice'><p>There was an error connecting to the external service. Please try again later. Thanks!</p></div>");
    });
  });
});
