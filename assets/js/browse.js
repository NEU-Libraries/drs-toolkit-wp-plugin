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
      paginate(data.pagination.table);//send to paginate function
      facetize(data.response.facet_counts);//send to facetize function

  }).fail(function() {
    console.log("there was an error");
  });


  //parses pagination data
  function paginate(data){
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
  }//end paginate

  //parses facet data
  function facetize(data){
    console.log(data.facet_fields);
    var facet_html = '';
    $.each(data.facet_fields, function(facet, facet_vals){
      var facet_name = facet; //need to prettize this
      var facet_values = '';
      if (facet_vals.length > 0) {
        var this_facet, this_facet_name;
        $.each(facet_vals, function(index, val_q) {
          if (index % 2 != 0) { //odd index means it is a count for a specific facet value
            var this_facet_count = val_q;
          } else { //even or 0 index means it is a name of a facet value
            this_facet_name = val_q;
          }
          if (this_facet_count != undefined) {
            this_facet = "<a href='#' class='list-group-item'><span class='badge'>"+this_facet_count+"</span>"+this_facet_name+"</a>";
            facet_values += this_facet;
          }
        });
        facet_html += "<div class='panel-heading'>" + facet_name + "</div><div id='drs_"+facet_name+"' class='list_group'>"+facet_values+"</div>";
      }
    });
    $("#drs-facets").html(facet_html);
  }//end facetize

});//end doc ready
