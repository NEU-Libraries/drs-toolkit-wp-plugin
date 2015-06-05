jQuery(document).ready(function($) {
  var q = '';
  q = GetURLParameter('q');
  var per_page = 2;
  var page = 1;
  var f = {};
  var sort = "score+desc%2C+system_create_dtsi+desc";
  var params = {q:q, per_page:per_page, page:page, f:f, sort:sort};
  var template = browse_obj.template;
  if ((q) && (q != '')){
    $("#drs-selection a[data-type='q']").remove();
    $("#drs-selection").append("<a class='themebutton' href='#' data-type='q' data-val='"+params.q+"'>"+params.q+" X</a>");
  }
  get_data(params);

  function get_data(params){
    $("#drs-pagination-header").html("<h2>Loading...<br/><span class='fa fa-spinner fa-pulse'></span></h2>");
    $.post(browse_obj.ajax_url, {
       _ajax_nonce: browse_obj.nonce,
        action: "get_browse",
        params: params,

    }, function(data) {
        var data = $.parseJSON(data);
        if (data == null) {
          $("#drs-content").html("There seems to be an issue connecting with the place where the data is stored. Try again later. Thanks!");
        } else if (data.error) {
          $("#drs-content").html("Your query produced no results. The error received was '"+data.error+"'. Thanks!");
        } else if (data.response.response.numFound > 0) {
          paginate(data.pagination.table);//send to paginate function
          facetize(data.response.facet_counts);//send to facetize function
          resultize(data.response.response);//send to resultize function
          clickable(data);
          $("#drs-sort").show().css("visibility","visible");
        } else {
          $("#drs-content").html("Your query produced no results. Please go back and try a different query. Thanks!");
        }
    }).fail(function() {
      $("#drs-content").html("<div class='alert error'>There was an error connecting to the data. Please try a different query. Thanks!</div>");
    });
  }

  //parses pagination data
  function paginate(data){
    $("#drs-pagination-header").html("<div class='one_third'>Displaying " + data.start + " to " + data.end + " of " + data.total_count + "</div><div class='one_fourth'>Show <select id='drs-per-page'><option val='2'>2</option><option val='5'>5</option><option val='10'>10</option></select> per page</div>");
    $("#drs-per-page").val(params.per_page);
    if (data.num_pages > 1) {
      var pagination = "<li class='";
      if (data.current_page > 1){
        pagination += "'><a href='#' class='prev'><<</a>";
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
        pagination += "'><a href='#' class='next'>>></a>";
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
      var facet_name = titleize(facet); //need to prettize this
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
            this_facet = "<a href='#' class='drs-facet-val' ><div class='three_fourth'>"+this_facet_name+"</div><div class='one_fourth'>"+this_facet_count+"</div></a>";
            facet_values += this_facet;
          }
        });
        facet_html += "<div id='drs_"+facet+"' class='drs-facet'><b class='drs-facet-name'>" + facet_name + "</b>"+facet_values+"</div>";
      }
    });
    $("#drs-facets").html(facet_html);
  }//end facetize

  //parses actual results
  function resultize(data){
    //do grid or list depending on if template is search or browse
    var docs_html = '';
    $.each(data.docs, function(doc, doc_vals){
      var title, abstract = '';
      var thumbnail = [];
      doc_vals.title_ssi? title = doc_vals.title_ssi : "";
      doc_vals.abstract_tesim? abstract = doc_vals.abstract_tesim : "";
      if (String(abstract).length > 2){
        abstract = String(abstract).substring(0, 125) + "...";
      }
      doc_vals.thumbnail_list_tesim? thumbnail = doc_vals.thumbnail_list_tesim : "";
      //insert images in a responsive way based on thumbnails
      var this_doc = '';
      if (template == 'search'){
        this_doc += '<div class="drs-item">';
        if (thumbnail[0]) {
          this_doc += "<div class='one_fourth'><a href='"+browse_obj.site_url+"/item/"+doc_vals.id+"'><img src='http://cerberus.library.northeastern.edu"+thumbnail[1]+"' /></a></div>";
        }
        this_doc += "<div class='three_fourth'><h4><a href='"+browse_obj.site_url+"/item/"+doc_vals.id+"'>" + title + "</a></h4><p>" + abstract + "</p></div></div>";
      } else {
        this_doc += '<div class="drs-item one_third">';
        if (thumbnail[0]) {
          this_doc += "<div class=''><a href='"+browse_obj.site_url+"/item/"+doc_vals.id+"'><img src='http://cerberus.library.northeastern.edu"+thumbnail[1]+"' /></a></div>";
        }
        this_doc += "<div class=''><h5><a href='"+browse_obj.site_url+"/item/"+doc_vals.id+"'>" + title + "</a></h5></div></div>";
      }
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
      var facet_val = $(this).children(".drs-facet-val div:first-of-type").html();
      params.f[facet] = facet_val;
      $("#drs-selection").append("<a class='themebutton' href='#' data-type='f' data-facet='"+facet+"' data-val='"+facet_val+"'>"+titleize(facet)+" > "+facet_val+" X </a>");
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

  $("#drs-sort").html("<div class='one_fourth'>Sort By: <select id='drs-sort-option'><option value='score+desc%2C+system_create_dtsi+desc'>Relevance Desc</option><option value='title_info_title_ssi%20desc'>Title Desc</option><option value='title_info_title_ssi%20asc'>Title Asc</option><option value='creator_ssi%20desc'>Creator Desc</option><option value='creator_ssi%20asc'>Creator Asc</option><option value='system_create_dtsi%20desc'>Date Uploaded Desc</option><option value='system_create_dtsi%20asc'>Date Uploaded Asc</option><option value='system_modified_dtsi%20desc'>Date Created Desc</option><option value='system_modified_dtsi%20asc'>Date Created Asc</option></select></div>");

  $("#drs-sort-option").on("change", function() {
    params.sort = $(this).val();
    get_data(params);
    $(this).val(params.sort);
  });

  function titleize(str){
    str = str.replace("_tesim","").replace("_sim","");
    str = str.replace("_", " ");
    str = str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
    return str;
  }

  function GetURLParameter(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++){
      var sParameterName = sURLVariables[i].split('=');
      if (sParameterName[0] == sParam){
        return sParameterName[1];
      }
    }
  }

});//end doc ready
