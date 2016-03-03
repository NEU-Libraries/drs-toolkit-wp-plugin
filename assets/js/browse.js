jQuery(document).ready(function($) {
  $(".breadcrumbs").children("li").remove();
  var q = '';
  q = GetURLParameter(window.location.search.substring(1), 'q');
  var per_page = 10;
  var page = 1;
  var f = {};
  var sort = "score+desc%2C+system_create_dtsi+desc";
  var params = {q:q, per_page:per_page, page:page, f:f, sort:sort};
  var template = browse_obj.template;
  var search_options = browse_obj.search_options;
  var browse_options = browse_obj.browse_options;
  var facets_to_display = browse_obj.facets_to_display;
  if ((q) && (q != '')){
    $("#drs-selection").show();
    $("#drs-selection a[data-type='q']").remove();
    $("#drs-selection .col-md-10").append("<a class='themebutton btn btn-more' href='#' data-type='q' data-val='"+params.q+"'>"+params.q+" <span class='fa fa-close'></span></a>");
  }
  if (template == 'collections'){
    params.f['type_sim'] = 'Collection';
    $("#drs-facets").hide();
    $("#drs-docs").css("width", "100%");
  }
  if (template == 'collection'){
    params.collection = browse_obj.sub_collection_pid;
  }
  if (template == 'search'){
    $("#primary").removeClass('col-md-12').addClass('col-md-9');
    $("#secondary").show();
  } else {
    params.sort = "title_ssi+asc";
    $("#primary").addClass('col-md-12').removeClass('col-md-9');
    $("#secondary").hide();
  }
  get_data(params);
  get_wp_data(params.q);


  function get_data(params){
    var errors = $.parseJSON(browse_obj.errors);
    $("#drs-loading").html("<h2>Loading...<br/><span class='fa fa-spinner fa-spin'></span></h2>").show();
    $.ajax({
        type: 'GET',
        url: browse_obj.ajax_url,
        data: {
          _ajax_nonce: browse_obj.nonce,
           action: "get_browse",
           params: params,
        },
    success: function(data)
     {
      // console.log(params)
      $("#drs-loading").hide();
      // console.log(data);
        var data = $.parseJSON(data);
        // console.log(data);
        if (data == null) {
          $("#drs-content").html(errors.search.fail_null);
        } else if (data == -1){
          $("#drs-content").html(errors.search.fail_null);
        } else if (data.error) {
          $("#drs-content").html(errors.search.no_results);
          if (template == 'collections'){
            $("#drs-content").html(errors.search.no_sub_collections);
          }
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
          $("#drs-content").html(errors.search.no_results);
        }
    }, error: function()
    {
      $("#drs-content").html("<div class='alert error alert-error'>"+errors.search.fail_null+"</div>");
    }
  });
  }

  //parses pagination data
  function paginate(data){
    $("#drs-item-count").html("<h6>Displaying " + data.start + " to " + data.end + " of " + data.total_count + "</h6>");
    $("#drs-per-page-div").html("<h6>Show <select id='drs-per-page'><option val='10'>10</option><option val='20'>20</option><option val='50'>50</option></select> per page</h6>");
    $("#drs-per-page").val(params.per_page);
    if (data.num_pages > 1) {
      var pagination = "<li class='";
      if (data.current_page > 1){
        pagination += "'><a href='#' class='prev'><<</a>";
      } else {
        pagination += "disabled'><span><<</span>";
      }
      pagination += "</li>";
      var ellipsis = 0;
      for (var i = 1; i <= data.num_pages; i++) {
        if (data.current_page == i){
          var pagination_class = 'active';
        } else {
          var pagination_class = '';
        }
        if (data.current_page == i) {
          pagination += "<li class='"+pagination_class+"'><span>" + i + "</span>";
        } else{
          if(i <=1 || (data.current_page && i >= data.current_page -2 && i <= data.current_page + 2) || i > data.num_pages -1){
            pagination += "<li class='"+pagination_class+"'><a href='#'>" + i + "</a>";
          } else {
            ellipsis++;
          }
        }
        pagination += "</li>";
        if (ellipsis > 0 && (i >= data.num_pages -1 || i <= 2 || (data.current_page && i <= data.num_pages -2) )){
          pagination += "<li class='disabled ellipsis'><span class='ellipsis'>...</span></li>";
          ellipsis = 0;
        }
      }
      pagination += "<li class='";
      if (data.current_page == data.num_pages){
        pagination += "disabled'><span>>></span>";
      } else {
        pagination += "'><a href='#' class='next'>>></a>";
      }
      pagination += "</li>";
      $("#drs-pagination ul.pag").html(pagination);
      $("#drs-pagination").show();
    } else {
      $("#drs-pagination ul.pag").html("");
      $("#drs-pagination").hide();
    }
  }//end paginate

  //parses facet data
  function facetize(data){
    var facet_html = '';
    $.each(facets_to_display, function(facet, title){
      var facet_name = title;
      var facet_values = '';
      if (Object.keys(data.facet_fields[facet]).length > 0) {
        var this_facet, this_facet_name;
        var facet_modal = facet_modal_vals = '';
        var i=1;
        var facet_array = [];
        $.each(data.facet_fields[facet],function(index, val_q){
          facet_array.push({v:index, k:val_q});
        });
        facet_array.sort(function(a,b){
           if(a.k > b.k){ return -1}
            if(a.k < b.k){ return 1}
              return 0;
        });
        $.each(facet_array, function(index, val_q) {
            var this_facet_count = val_q.k;
            this_facet_name = val_q.v;
          if (this_facet_count != undefined) {
            this_facet = "<a href='#' class='drs-facet-val row'><div class='three_fourth col-xs-8'>"+this_facet_name+"</div><div class='one_fourth col-xs-4 last'>"+this_facet_count+"</div></a>";
            if (i <= 5){
              facet_values += this_facet;
            }
              facet_modal_vals += this_facet;
          }
          i++;
        });
        facet_modal = '<button type="button" class="themebutton btn btn-more" data-toggle="modal" data-target="#drs_modal_'+facet+'">More '+facet_name+'s</button><div class="modal fade" id="drs_modal_'+facet+'"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">All '+facet_name+'s</h4></div><div class="modal-body">'+facet_modal_vals+'</div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div><!-- /.modal-content --></div><!-- /.modal-dialog --></div><!-- /.modal -->';
        facet_html += "<div id='drs_"+facet+"' class='drs-facet'><div class='panel panel-default'><div class='panel-heading'><b class='drs-facet-name'>" + facet_name + "</b></div><div class='panel-body'>"+facet_values;
        if (Object.keys(data.facet_fields[facet]).length > 5){
          facet_html += facet_modal;
        }
        facet_html += "</div></div></div>";
      }
    });
    $("#drs-facets").html(facet_html);
    $("#drs-facets").before("<button class='themebutton button btn visible-phone hidden-tablet hidden-desktop drs-facet-toggle hidden-md hidden-lg visible-sm visible-xs'>Show Facets</button>");
  }//end facetize

  //parses actual results
  function resultize(data){
    //do grid or list depending on if template is search or browse
    var docs_html = '';
    $.each(data.docs, function(doc, doc_vals){
      var title, abstract, creator, date, klass = '';
      var thumbnail = [];
      doc_vals.full_title_ssi? title = doc_vals.full_title_ssi : "";
      doc_vals.abstract_tesim? abstract = doc_vals.abstract_tesim : "";
      doc_vals.creator_tesim? creator = doc_vals.creator_tesim : "";
      doc_vals.thumbnail_list_tesim? thumbnail = doc_vals.thumbnail_list_tesim : "";
      doc_vals.origin_info_date_created_tesim? date = doc_vals.origin_info_date_created_tesim : "";
      doc_vals.active_fedora_model_ssi? klass = doc_vals.active_fedora_model_ssi : "";
      if (klass == 'CoreFile'){klass = get_short_name(doc_vals.canonical_class_tesim);}
      if (doc_vals.active_fedora_model_ssi == 'Collection') {
        this_doc_url = '/collection/' + doc_vals.id;
      } else if (doc_vals.active_fedora_model_ssi == 'CoreFile') {
        this_doc_url = '/item/' + doc_vals.id;
      }
      var this_doc = '';
      if (template == 'search'){
        //search = grid
        this_doc += "<div class='drs-item search panel panel-default'><div class='panel-body'><div class='one_fourth col-sm-3'><figure><a href='"+browse_obj.site_url+this_doc_url+"'>";
        if (thumbnail[1]) {
          this_doc += "<img src='https://repository.library.northeastern.edu"+thumbnail[1]+"' />";
        } else {
          this_doc += "<div class='fa fa-folder-open-o'></div>";
        }
        this_doc += "<figcaption><span class='label small'>"+klass+"</span></figcaption></a></figure></div><div class='three_fourth col-sm-9 last'>";
        if (search_options.indexOf('Title') > -1){
          this_doc += "<h4 class='drs-item-title'><a href='"+browse_obj.site_url+this_doc_url+"'>" + title + "</a></h4>";
        }
        if (creator && search_options.indexOf('Creator') > -1){
          this_doc += "<h6>"+ creator + "</h6>";
        }
        if (abstract  && search_options.indexOf('Abstract') > -1){
          this_doc += "<p class='drs-item-abstract'>" + abstract + "</p>";
        }
        if (date  && search_options.indexOf('Date') > -1){
          this_doc += "<p class='drs-item-date'>" + date + "</p>";
        }
        this_doc += "<div class=''><a href='"+browse_obj.site_url+this_doc_url+"' class='themebutton button btn'>View More</a></div></div></div></div>";
      } else {
        //browse = tile
        this_doc += "<div class='drs-item browse one_third ";
        if (template == 'collections'){
          this_doc += "col-lg-3 col-md-4 col-sm-5";
        } else {
          this_doc += "col-sm-4";
        }
        this_doc += "'><div class='thumbnail'><figure><a href='"+browse_obj.site_url+this_doc_url+"'>";
        if (thumbnail[1]) {
          this_doc += "<img src='https://repository.library.northeastern.edu"+thumbnail[1]+"' />";
        } else {
          this_doc += "<div class='fa fa-folder-open-o'></div>";
        }
        this_doc += "<figcaption><span class='label small'>"+klass+"</span></figcaption></a></figure><div class='caption text-center'><h5 class='drs-item-title'><a href='"+browse_obj.site_url+this_doc_url+"'>";
        if (browse_options.indexOf('Title') > -1){
          this_doc += title;
        }
        this_doc += "</a></h5><h6 class='drs-item-creator'>";
        if (creator && browse_options.indexOf('Creator') > -1){
          this_doc += creator;
        }
        this_doc += "</h6><p class='drs-item-abstract'>";
        if (abstract  && browse_options.indexOf('Abstract') > -1){
          this_doc += abstract;
        }
        this_doc += "</p><p class='drs-item-date'>";
        if (date  && browse_options.indexOf('Date') > -1){
          this_doc += date;
        }
        this_doc += "</p></div></div></div>";
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
      var facet = $(this).parents('.drs-facet').attr("id");
      if ($(this).parent().hasClass('modal-body')){
        facet = $(this).parents('.modal').attr('id').substr(10);
        $(this).parents('.modal').modal('hide');
      } else {
        facet = facet.substr(4);
      }
      var facet_val = $(this).children(".drs-facet-val div:first-of-type").html();
      params.f[facet] = facet_val;
      params.page = 1;
      $("#drs-selection").show();
      $("#drs-selection .col-md-10").append("<a class='themebutton btn btn-more' href='#' data-type='f' data-facet='"+facet+"' data-val='"+facet_val+"'>"+titleize(facet)+" > "+facet_val+" <span class='fa fa-close'></span></a>");
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
      params.page = 1;
      $(this).remove();
      get_data(params);
      get_wp_data(params.q);
    });

  }

  $("#drs-sort").html("<h6>Sort By: <select id='drs-sort-option'><option value='score+desc%2C+system_create_dtsi+desc'>Relevance</option><option value='full_title_ssi%20asc'>Title A-Z</option><option value='full_title_ssi%20desc'>Title Z-A</option><option value='creator_tesim%20asc'>Creator A-Z</option><option value='creator_tesim%20desc'>Creator Z-A</option><option value='system_modified_dtsi%20asc'>Date (earliest to latest)</option><option value='system_modified_dtsi%20desc'>Date (latest to earliest)</option></select></h6>");

  $("#drs-sort-option").on("change", function() {
    params.sort = $(this).val();
    get_data(params);
    $(this).val(params.sort);
  });

  $("#drs-content").on("click", ".drs-facet-toggle",  function() {
    $("#drs-facets").toggleClass("hidden-xs hidden-sm visible-sm visible-xs");
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
          $("#secondary").html("Looking for related content...");
  			},
  			success: function(data)
  			{
          // console.log(data);
          $("#secondary").html("<div class='panel panel-default'><div class='panel-heading'><b>Related Content</b></div><div class='panel-body'>"+data+"</div></div>");
          $("#secondary").addClass('drs-sidebar');
          $("#primary").addClass('drs-main');
          $("#secondary #title-container").hide();
          fix_wp_pagination();
  			},
  			error: function()
  			{
  				$("#secondary").hide();
          $("#primary").removeClass('col-md-9').addClass('col-md-12');
  			}
  		});
    } else {
      //console.log("we're in browse silly");
    }
  }

  function fix_wp_pagination() {
    $('#secondary .pagination li a').on("click", function(e) {
      e.preventDefault();
      var wp_page = GetURLParameter($(this).attr('href'), 'paged');
      get_wp_data(params.q, wp_page);
    });
  }

  function get_short_name(klass){
    if (klass == 'AudioFile'){
      klass = 'Audio';
    } else if (klass == 'ImageLargeFile' || klass == 'ImageMasterFile' || klass == 'ImageMediumFile' || klass == 'ImageSmallFile' || klass == 'ImageThumbnailFile'){
      klass = 'Image';
    } else if (klass == 'MsexcelFile'){
      klass = 'Dataset';
    } else if (klass == 'MspowerpointFile'){
      klass = 'Presentation';
    } else if (klass == 'MswordFile' || klass == 'PdfFile' || klass == 'TextFile'){
      klass = 'Document';
    } else if (klass == 'VideoFile'){
      klass = 'Video';
    } else if (klass == 'ZipFile'){
      klass = 'Zip';
    }
    return klass;
  }

});//end doc ready
