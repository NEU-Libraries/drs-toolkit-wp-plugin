jQuery(document).ready(function($) {
  var q = '';
  var per_page = 2;
  var page = 1;
  var f = {};
  var sort = "score+desc%2C+system_create_dtsi+desc";
  var params = {q:q, per_page:per_page, page:page, f:f, sort:sort};
  get_data(params);
  var template = browse_obj.template;
  if (template == 'search') {
    $("#drs-search").show();
  }
  function get_data(params){
    $("#drs-pagination-header").html("<h2>Loading...<br/><span class='fa fa-spinner fa-pulse'></span></h2>");
    $.post(browse_obj.ajax_url, {
       _ajax_nonce: browse_obj.nonce,
        action: "get_browse",
        params: params,

    }, function(data) {
        var data = $.parseJSON(data);
        if (data == '') {
          $("#drs-content").html("Your query produced no results. The error received was '"+data.error+"'. Thanks!");
        } else if (data.error) {
          $("#drs-content").html("Your query produced no results. The error received was '"+data.error+"'. Thanks!");
        } else if (data.response.response.numFound > 0) {
          paginate(data.pagination.table);//send to paginate function
          facetize(data.response.facet_counts);//send to facetize function
          resultize(data.response.response);//send to resultize function
          clickable(data);
        } else {
          $("#drs-content").html("Your query produced no results. Please go back and try a different query. Thanks!");
        }
    }).fail(function() {
      $("#drs-content").html("<div class='alert error'>There was an error connecting to the data. Please try a different query. Thanks!</div>");
    });
  }

  //parses pagination data
  function paginate(data){
    $("#drs-pagination-header").html("<div class='grid-50'>Displaying " + data.start + " to " + data.end + " of " + data.total_count + "</div><div class='grid-50'>Show <select id='drs-per-page'><option val='2'>2</option><option val='5'>5</option><option val='10'>10</option></select> per page</div>");
    $("#drs-per-page").val(params.per_page);
    if (data.num_pages > 1) {
      var pagination = "<li class='";
      if (data.current_page > 1){
        pagination += "active'><a href='#' class='prev'><<</a>";
      } else {
        pagination += "disabled'><span><<</span>";
      }
      pagination += "</li>";
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
      pagination += "<li class='";
      if (data.current_page == data.num_pages){
        pagination += "disabled'><span>>></span>";
      } else {
        pagination += "active'><a href='#' class='next'>>></a>";
      }
      pagination += "</li>";
      $("#drs-pagination").html(pagination);
    } else {
      $("#drs-pagination").html("");
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
            this_facet = "<a href='#' class='list-group-item'><div class='facet_val'>"+this_facet_name+"</div><span class='badge'>"+this_facet_count+"</span></a>";
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
      var this_doc = '<div class="drs-item">';
      if (thumbnail[0]) {
        this_doc += "<div class='grid-25'><a href='"+browse_obj.site_url+"/item/"+doc_vals.id+"'><img src='http://cerberus.library.northeastern.edu"+thumbnail[0]+"' /></a></div>";
      }
      this_doc += "<div class='grid-75'><h3><a href='"+browse_obj.site_url+"/item/"+doc_vals.id+"'>" + title + "</a></h3><p>" + abstract + "</p></div></div>";
      docs_html += this_doc;
    });
    $("#drs-docs").html(docs_html);
  }//end resultize

  function clickable(data){
    $("#drs-pagination a").on("click", function(e){
      e.preventDefault();
      if ($(this).hasClass('next')){
        params.page = params.page +1;
      }else if ($(this).hasClass('prev')){
        params.page = params.page -1;
      } else {
        params.page = $(this).html();
      }
      get_data(params);
    });
    $("#drs-per-page").on("change", function(){
      params.per_page = $(this).val();
      params.page = 1;
      get_data(params);
    });
    $("#drs-facets a").on("click", function(e){
      e.preventDefault();
      var facet = $(this).parent().attr("id");
      facet = facet.substr(4);
      var facet_val = $(this).children(".facet_val").html();
      params.f[facet] = facet_val;
      $("#drs-selection").append("<a class='btn' href='#' data-type='f' data-facet='"+facet+"' data-val='"+facet_val+"'>"+facet+" > "+facet_val+" X </a>");
      get_data(params);
    });
    $("#drs-selection a").on("click", function(e){
      e.preventDefault();
      var type = $(this).data("type");
      if (type == 'f') {
        var facet = $(this).data("facet");
        delete params.f[facet];
      } else {
        params[type] = '';
      }
      $(this).remove();
      get_data(params);
    });

  }

  $("#drs-search input[type='submit']").on("click", function() {
    params.q = $("#drs-input").val();
    $("#drs-selection a[data-type='q']").remove();
    $("#drs-selection").append("<a class='btn' href='#' data-type='q' data-val='"+params.q+"'>"+params.q+" X</a>");
    get_data(params);
  });

  $("#drs-sort").html("<select id='drs-sort-option'><option value='score+desc%2C+system_create_dtsi+desc'>Relevance Desc</option><option value='title_info_title_ssi%20desc'>Title Desc</option><option value='title_info_title_ssi%20asc'>Title Asc</option><option value='creator_ssi%20desc'>Creator Desc</option><option value='creator_ssi%20asc'>Creator Asc</option><option value='system_create_dtsi%20desc'>Date Uploaded Desc</option><option value='system_create_dtsi%20asc'>Date Uploaded Asc</option><option value='system_modified_dtsi%20desc'>Date Created Desc</option><option value='system_modified_dtsi%20asc'>Date Created Asc</option></select>");

  $("#drs-sort-option").on("change", function() {
    params.sort = $(this).val();
    get_data(params);
    $(this).val(params.sort);
  });

});//end doc ready
