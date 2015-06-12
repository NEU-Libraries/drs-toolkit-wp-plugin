jQuery(document).ready(function($) {
  $("#drstk-import").on("click", function(e){
    e.preventDefault();
    $(this).after("<div class='spinner is-active'></div>");
    $(".spinner").css("float","none");
    $.post(import_obj.ajax_url, {
       _ajax_nonce: import_obj.import_nonce,
        action: "get_import",
        pid: import_obj.pid,
    }, function(data) {
        var data = $.parseJSON(data);
        console.log(data);
        $(".spinner").removeClass('is-active');
        var error_html = '';
        var error_count = 0;
        $.each(data.objects, function(pid, values){
          $.each(values, function(key, val){
            if (key == 'error'){
              error_html = val.errors.upload_error;
              error_count++;
            }
          });
        });
        if (error_count == 0){
          $("#drstk-import").after("<div class='updated notice'><p>Import completed of "+data.count+" objects. "+data.existing_count+" were already present in the Media library.</p></div>");
        } else {
          $("#drstk-import").after("<div class='error notice'><p>Import completed of "+(data.count - error_count)+" objects. "+data.existing_count+" were already present in the Media library.</p><p>"+error_count+" objects were not imported for the following reason:<br/>"+error_html+"</p></div>");
        }
        if (Object.prototype.toString.call( data.objects ) === '[object Object]'){
          show_updates(data);
        }
    }).fail(function() {
      $(".spinner").removeClass('is-active');
      $("#drstk-import").after("<div class='error notice'><p>There was an error connecting to the external service. Please try again later. Thanks!</p></div>");
    });
  });

  function show_updates(data){
    var data_table = "<div><br/><table class='wp-list-table widefat fixed striped media'><tr><thead><th class='manage-column'>PID</th><th class='manage-column'>Field</th><th class='manage-column'>Wordpress Value</th><th class='manage-column'>DRS Value</th><th></th></thead></tr>";
    $.each(data.objects, function(pid,values){
      $.each(values, function(key, val){
        if (key != 'error'){
          data_table += "<tr><td><a href='http://localhost/wordpress/item/"+pid+"' target='_blank'>"+pid+"</a></td><td>"+key.charAt(0).toUpperCase()+ key.slice(1)+"</td>";
          $.each(val, function(meta, value){
            console.log(meta + value);
            data_table += "<td>"+value+"</td>";
          });
          data_table +="<td><a class='button meta-override' href='#' data-pid='"+pid+"' data-field='"+key+"' data-value='"+val.drs+"'>Override Wordpress Value</a></td></tr>";
        }
      });
    });
    $(".notice").after(data_table);

    $(".meta-override").on("click", function(e){
      e.preventDefault();
      var pid = $(this).data('pid');
      var field = $(this).data('field');
      var value = $(this).data('value');
      var this_row = $(this).parent('td').parent('tr');
      console.log("pid is " + pid +" and field is " + field);
      $.post(import_obj.ajax_url, {
         _ajax_nonce: import_obj.import_data_nonce,
          action: "get_import_data",
          pid: pid,
          field: field,
          value: value,
      }, function(data) {
          var data = $.parseJSON(data);
          this_row.remove();
      }).fail(function() {

      });
    });
  }


});
