console.log("We're browsing")
jQuery(document).ready(function($) {
  console.log("we're browsing and ready");
  var q = '';
  var per_page = 2;
  var page = 1;
  var params = {q:q, per_page:per_page, page:page};
  get_data(params);
  var template = browse_obj.template;
  console.log(template);
  if (template == 'search') {
    $("#drs-search").show();
  }
  //where will we get all of the varaibles from? the URL params? or from clicks??
  function get_data(params){
    $.post(browse_obj.ajax_url, {
       _ajax_nonce: browse_obj.nonce,
        action: "get_browse",
        query: params.q,
        per_page: params.per_page,
        page: params.page,

    }, function(data) {
        var data = $.parseJSON(data);
        //what happens when the API returns an error??
        if (data.response.response.numFound > 0) {
          paginate(data.pagination.table);//send to paginate function
          facetize(data.response.facet_counts);//send to facetize function
          resultize(data.response.response);//send to resultize function
          //handle sorting
          clickable(data);
        } else {
          $("#drs-content").html("Your query produced no results. Please go back and try a different query. Thanks!");
        }

    }).fail(function() {
      console.log("there was an error");
    });
  }


  //parses pagination data
  function paginate(data){
    $("#drs-pagination-header").html("Displaying " + data.start + " to " + data.end + " of " + data.total_count + " <br/>Show <select id='drs-per-page'><option val='2'>2</option><option val='5'>5</option><option val='10'>10</option></select> per page");
    $("#drs-per-page").val(params.per_page);
    if (data.num_pages > 1) {
      var pagination = "<li><a href='#'><<</a></li>";
      for (var i = 1; i <= data.num_pages; i++) {
        if (data.current_page == i){
          var pagination_class = 'active';
        } else {
          var pagination_class = '';
        }
        pagination += "<li class='"+pagination_class+"'>";
        if (data.current_page == i) {
          pagination += "<span>" + i + "</span>";
        } else {
          pagination += "<a href='#'>" + i + "</a>";
        }
        pagination += "</li>";
      }
      pagination += "<li><a href='#'>>></a></li>";
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
            this_facet = "<a href='#' class='list-group-item'>"+this_facet_name+"<span class='badge'>"+this_facet_count+"</span></a>";
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

  function clickable(data){
    $("#drs-pagination a").on("click", function(e){
      e.preventDefault();
      params.page = $(this).html();
      get_data(params);
    });
    $("#drs-per-page").on("change", function(){
      params.per_page = $(this).val();
      params.page = 1;
      get_data(params);
    });
    $("#drs-search input[type='submit']").on("click", function() {
      params.q = $("#drs-input").val();
      get_data(params);
    });
  }

});//end doc ready
