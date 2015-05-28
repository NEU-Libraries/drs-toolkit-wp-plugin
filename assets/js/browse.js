console.log("We're browsing")
jQuery(document).ready(function($) {
  console.log("we're browsing and ready");
  var q = 'lorem';
  $.post(browse_obj.ajax_url, {         //POST request
     _ajax_nonce: browse_obj.nonce,     //nonce
      action: "get_browse",            //action
      query: q                 //data
  }, function(data) {                    //callback
      console.log(data);              //insert server response
  }).fail(function() {
    console.log("there was an error");
  });


});
