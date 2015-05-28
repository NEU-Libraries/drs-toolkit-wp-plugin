console.log("We're browsing")
jQuery(document).ready(function($) {
  console.log("we're browsing and ready");
  //where will we get all of the varaibles from? the URL params? or from clicks??
  var q = 'lorem';
  var per_page = 2;
  var page = 3;
  $.post(browse_obj.ajax_url, {
     _ajax_nonce: browse_obj.nonce,
      action: "get_browse",
      query: q,
      per_page: per_page,
      page: page,

  }, function(data) {
      var data = $.parseJSON(data);
      //what happens when the API returns an error??
      if (data.response.response.numFound > 0) {
        paginate(data.pagination.table);//send to paginate function
        facetize(data.response.facet_counts);//send to facetize function
        resultize(data.response.response);//send to resultize function
        //handle sorting
      } else {
        $("#drs-content").html("Your query produced no results. Please go back and try a different query. Thanks!");
      }

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
      //add handling disabling of prev and next based on if first or last page
      $("#drs-pagination").html(pagination);
    }
  }//end paginate

  //parses facet data
  function facetize(data){
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

  //parses actual results
  function resultize(data){
    var docs_html = '';
    $.each(data.docs, function(doc, doc_vals){
      var title, abstract = '';
      var thumbnail = [];
      doc_vals.title_ssi? title = doc_vals.title_ssi : "";
      doc_vals.abstract_tesim? abstract = doc_vals.abstract_tesim : "";
      doc_vals.thumbnail_list_tesim? thumbnail = doc_vals.thumbnail_list_tesim : "";
      //insert images in a responsive way based on thumbnails
      var this_doc = "<div class='media'><h4>" + title + "</h4><p>" + abstract + "</p>";
      if (thumbnail[0]) {
        this_doc += "<img src='http://cerberus.library.northeastern.edu"+thumbnail[0]+"' />";
      }
      this_doc += "</div>";
      docs_html += this_doc;
    });
    $("#drs-docs").html(docs_html);
  }//end resultize

});//end doc ready
