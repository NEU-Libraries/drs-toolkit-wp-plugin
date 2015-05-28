console.log("We're browsing")
jQuery(document).ready(function($) {
  console.log("we're browsing and ready");
  //where will we get all of the varaibles from? the URL params? or from clicks??
  var q = 'lorem';
  var per_page = 2;
  var page = 1;
  $.post(browse_obj.ajax_url, {         //POST request
     _ajax_nonce: browse_obj.nonce,     //nonce
      action: "get_browse",            //action
      query: q,
      per_page: per_page,
      page: page,
                       //data
  }, function(data) {                    //callback
      console.log(data);              //insert server response
      var data = $.parseJSON(data);
      pagination(data.pagination.table);


  }).fail(function() {
    console.log("there was an error");
  });


  //parses pagination data
  function pagination(data){
    $("#drs-pagination-header").html("Displaying " + data.start + " to " + data.end + " of " + data.total_count);
    if (data.num_pages > 1) {
      var pagination = "<li><span class='pager-prev'><<</span></li>";
      for (var i = 1; i <= data.num_pages; i++) {
        pagination += "<li>";
        if (data.current_page == i) {
          pagination += "<span class='pager-current'>" + i + "</span>";
        } else {
          pagination += "<a href='#'>" + i + "</a>";
        }
        pagination += "</li>";
      }
      pagination += "<li><span class='pager-next'><<</span></li>";
      $("#drs-pagination").html(pagination);
    }
  }
});
