jQuery(document).ready(function($) {
  var q = '';
  q = GetURLParameter(window.location.search.substring(1), 'q');
  var per_page = 10;
  var page = 1;
  var f = {};
  var sort = "score+desc%2C+system_create_dtsi+desc";
  var params = {q:q, per_page:per_page, page:page, f:f, sort:sort};
  var template = browse_obj.template;
  var search_options = $.parseJSON(browse_obj.search_options);
  var browse_options = $.parseJSON(browse_obj.browse_options);
  if ((q) && (q != '')){
    $("#drs-selection").show();
    $("#drs-selection a[data-type='q']").remove();
    $("#drs-selection").append("<a class='themebutton' href='#' data-type='q' data-val='"+params.q+"'>"+params.q+"</a>");
  }
  if (template == 'collections'){
    params.f['type_sim'] = 'Collection';
    $("#drs-facets").hide();
    $("#drs-docs").css("width", "100%");
  }
  if (template == 'collection'){
    params.f['fields_parent_id_tesim'] = browse_obj.sub_collection_pid;
  }
  get_data(params);
  get_wp_data(params.q);


  function get_data(params){
    $("#drs-loading").html("<h2>Loading...<br/><span class='fa fa-spinner fa-pulse'></span></h2>").show();
    $.post(browse_obj.ajax_url, {
       _ajax_nonce: browse_obj.nonce,
        action: "get_browse",
        params: params,

    }, function(data) {
      $("#drs-loading").hide();
        var data = $.parseJSON(data);
        console.log(data);
        if (data == null) {
          $("#drs-content").html("There seems to be an issue connecting with the place where the data is stored. Try again later. Thanks!");
        } else if (data.error) {
          $("#drs-content").html("Your query produced no results. The error received was '"+data.error+"'. Thanks!");
        } else if (data.response.response.numFound > 0) {
          paginate(data.pagination.table);//send to paginate function
          facetize(data.response.facet_counts);//send to facetize function
          resultize(data.response.response);//send to resultize function
          clickable(data);
          $("#drs-sort").show();
          if ($("#drs-selection").find("a").length < 1){
            $("#drs-selection").hide();
          }
        } else {
          $("#drs-content").html("Your query produced no results. Please go back and try a different query. Thanks!");
        }
    }).fail(function() {
      $("#drs-content").html("<div class='alert error'>There was an error connecting to the data. Please try a different query. Thanks!</div>");
    });
  }

  //parses pagination data
  function paginate(data){
    $("#drs-item-count").html("<div>Displaying " + data.start + " to " + data.end + " of " + data.total_count + "</div>");
    $("#drs-per-page-div").html("<div>Show <select id='drs-per-page'><option val='10'>10</option><option val='20'>20</option><option val='50'>50</option></select> per page</div>");
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
          var pagination_class = 'current';
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
      $("#drs-pagination ul.pag").html(pagination);
    } else {
      $("#drs-pagination ul.pag").html("");
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
        var facet_modal = facet_modal_vals = '';
        $.each(facet_vals, function(index, val_q) {
          if (index % 2 != 0) { //odd index means it is a count for a specific facet value
            var this_facet_count = val_q;
          } else { //even or 0 index means it is a name of a facet value
            this_facet_name = val_q;
          }
          if (this_facet_count != undefined) {
            this_facet = "<a href='#' class='drs-facet-val' ><div class='three_fourth'>"+this_facet_name+"</div><div class='one_fourth last'>"+this_facet_count+"</div></a>";
            if (index < 10){
              facet_values += this_facet;
              facet_modal_vals += this_facet;
            } else {
              facet_modal_vals += this_facet;
            }
          }
        });
        facet_modal = '<button type="button" class="themebutton" data-toggle="modal" data-target="#drs_modal_'+facet+'">More '+facet_name+'s</button><div class="modal fade hide" id="drs_modal_'+facet+'"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">All '+facet_name+'s</h4></div><div class="modal-body">'+facet_modal_vals+'</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div><!-- /.modal-content --></div><!-- /.modal-dialog --></div><!-- /.modal -->';
        facet_html += "<div id='drs_"+facet+"' class='drs-facet'><b class='drs-facet-name'>" + facet_name + "</b>"+facet_values;
        if (facet_vals.length > 10){
          facet_html += facet_modal;
        }
        facet_html += "</div>";
      }
    });
    $("#drs-facets").html(facet_html);
    $("#drs-facets").before("<button class='themebutton visible-phone hidden-tablet hidden-desktop drs-facet-toggle'>Show Facets</button>");
  }//end facetize

  //parses actual results
  function resultize(data){
    //do grid or list depending on if template is search or browse
    var docs_html = '';
    $.each(data.docs, function(doc, doc_vals){
      var title, abstract, creator, date = '';
      var thumbnail = [];
      doc_vals.title_ssi? title = doc_vals.title_ssi : "";
      doc_vals.abstract_tesim? abstract = doc_vals.abstract_tesim : "";
      doc_vals.creator_ssi? creator = doc_vals.creator_ssi : "";
      doc_vals.thumbnail_list_tesim? thumbnail = doc_vals.thumbnail_list_tesim : "";
      doc_vals.origin_info_date_created_tesim? date = doc_vals.origin_info_date_created_tesim : "";
      if (doc_vals.active_fedora_model_ssi == 'Collection') {
        this_doc_url = '/collection/' + doc_vals.id;
      } else if (doc_vals.active_fedora_model_ssi == 'CoreFile') {
        this_doc_url = '/item/' + doc_vals.id;
      }
      var this_doc = '';
      if (template == 'search'){
        //search = grid
        this_doc += "<div class='drs-item'><div class='one_fourth'><a href='"+browse_obj.site_url+this_doc_url+"'>";
        if (thumbnail[1]) {
          this_doc += "<img src='http://cerberus.library.northeastern.edu"+thumbnail[1]+"' />";
        } else {
          this_doc += "<div class='dashicons dashicons-portfolio'></div>";
        }
        this_doc += "</a></div><div class='three_fourth last'>";
        if (search_options.indexOf('title') > -1){
          this_doc += "<h4 class='drs-item-title'><a href='"+browse_obj.site_url+this_doc_url+"'>" + title + "</a></h4>";
        }
        if (creator && search_options.indexOf('creator') > -1){
          this_doc += "<h6>"+ creator + "</h6>";
        }
        if (abstract  && search_options.indexOf('abstract') > -1){
          this_doc += "<p class='drs-item-abstract'>" + abstract + "</p>";
        }
        if (date  && search_options.indexOf('date') > -1){
          this_doc += "<p class='drs-item-date'>" + date + "</p>";
        }
        this_doc += "</div><div class=''><a href='"+browse_obj.site_url+this_doc_url+"' class='themebutton'>View More</a></div></div>";
      } else {
        //browse = tile
        this_doc += "<div class='drs-item one_third'><div class=''><a href='"+browse_obj.site_url+this_doc_url+"'>";
        if (thumbnail[1]) {
          this_doc += "<img src='http://cerberus.library.northeastern.edu"+thumbnail[1]+"' />";
        } else {
          this_doc += "<div class='dashicons dashicons-portfolio'></div>";
        }
        this_doc += "</a></div><div class=''>";
        if (browse_options.indexOf('title') > -1){
          this_doc += "<h5 class='drs-item-title'><a href='"+browse_obj.site_url+this_doc_url+"'>" + title + "</a></h5>";
        }
        if (creator && browse_options.indexOf('creator') > -1){
          this_doc += "<h6>"+ creator + "</h6>";
        }
        if (abstract  && browse_options.indexOf('abstract') > -1){
          this_doc += "<p class='drs-item-abstract'>" + abstract + "</p>";
        }
        if (date  && browse_options.indexOf('date') > -1){
          this_doc += "<p class='drs-item-date'>" + date + "</p>";
        }
        this_doc += "</div></div>";
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
      if ($(this).parent().hasClass('modal-body')){
        facet = $(this).parents('.modal').attr('id').substr(10);
        $(this).parents('.modal').modal('hide');
      } else {
        facet = facet.substr(4);
      }
      var facet_val = $(this).children(".drs-facet-val div:first-of-type").html();
      params.f[facet] = facet_val;
      $("#drs-selection").show();
      $("#drs-selection").append("<a class='themebutton' href='#' data-type='f' data-facet='"+facet+"' data-val='"+facet_val+"'>"+titleize(facet)+" > "+facet_val+"</a>");
      get_data(params);
    });
    $("#drs-selection a").on("click", function(e){
      e.preventDefault();
      var type = $(this).data("type");
      if (type == 'f') {
        var facet = $(this).data("facet");
        delete params.f[facet];
      } else if (type == 'q') {
        params[type] = '';
        location.href=location.href.replace(/&?q=([^&]$|[^&]*)/i, "");
      } else {
        params[type] = '';
      }
      $(this).remove();
      get_data(params);
      get_wp_data(params.q);
    });

  }

  $("#drs-sort").html("<div>Sort By: <select id='drs-sort-option'><option value='score+desc%2C+system_create_dtsi+desc'>Relevance</option><option value='title_info_title_ssi%20asc'>Title A-Z</option><option value='title_info_title_ssi%20desc'>Title Z-A</option><option value='creator_ssi%20asc'>Creator A-Z</option><option value='creator_ssi%20desc'>Creator Z-A</option><option value='system_modified_dtsi%20asc'>Date (earliest to latest)</option><option value='system_modified_dtsi%20desc'>Date (latest to earliest)</option></select></div>");

  $("#drs-sort-option").on("change", function() {
    params.sort = $(this).val();
    get_data(params);
    $(this).val(params.sort);
  });

  $("#drs-content").on("click", ".drs-facet-toggle",  function() {
    $("#drs-facets").toggleClass("hidden-phone visible-phone");
    $(".drs-facet-toggle").html($('.drs-facet-toggle').text() == 'Hide Facets' ? 'Show Facets' : 'Hide Facets');
  });

  function titleize(str){
    str = str.replace("_tesim","").replace("_sim","").replace("_ssim","");
    str = str.replace("_", " ");
    str = str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
    return str;
  }

  function GetURLParameter(url, sParam){
    var sPageURL = url;
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++){
      var sParameterName = sURLVariables[i].split('=');
      if (sParameterName[0] == sParam){
        return sParameterName[1];
      }
    }
  }

  function get_wp_data(query, page){
    //console.log(query);
    if (template == 'search'){
      if (!page){
        page = 1;
      }
      $.ajax({
  			type: 'GET',
  			url: browse_obj.ajax_url,
  			data: {
  				action: 'wp_search',
  				query: query,
          page: page,
  			},
  			beforeSend: function ()
  			{
          $("#sidebar-core").html("Looking for related content...");
  			},
  			success: function(data)
  			{
          $("#sidebar-core").html("<h3 class='widget-title'>Related Content</h3>"+data);
          $("#sidebar").addClass('drs-sidebar');
          $("#main").addClass('drs-main');
          fix_wp_pagination();
  			},
  			error: function()
  			{
  				$("#sidebar-core").hide();
  			}
  		});
    } else {
      //console.log("we're in browse silly");
    }
  }

  function fix_wp_pagination() {
    $('#sidebar-core .pag li a').on("click", function(e) {
      e.preventDefault();
      var wp_page = GetURLParameter($(this).attr('href'), 'paged');
      get_wp_data(params.q, wp_page);
    });
  }

});//end doc ready
