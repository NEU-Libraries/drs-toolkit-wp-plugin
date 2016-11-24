/**
 * Created by Abhi on 11/10/2016.
 */

jQuery(document).ready(function($) {

    var searchBox = '<div id="search-and-facet" class="col-md-2"><input name="q1" type="text" placeholder="Search ..."><br><br></div>';
    jQuery("#content .row").prepend(searchBox);

    var facets_recieved = facets_info_data_obj.data.response.facet_counts
    var atts = facets_info_data_obj.atts;
    var map_obj = facets_info_data_obj.map_obj;
    var sort = "score+desc%2C+system_create_dtsi+desc";
    var f = {};
    var params1 = {f: f, sort:sort};
    var selectedItem =[];

    create_left_pane(facets_recieved);

    $("#drs-facets a").on("click", function(e){
        e.preventDefault();
        console.log("I am here when a facet is clicked");
        var facet = $(this).parents('.drs-facet').attr("id");
        if ($(this).parent().hasClass('modal-body')){
            facet = $(this).parents('.modal').attr('id').substr(10);
            $(this).parents('.modal').modal('hide');
        } else {
            facet = facet.substr(4);
        }
        var facet_val = $(this).children(".drs-facet-val div:first-of-type").html();
        params1.f[facet] = facet_val;
        selectedItem.push(facet_val);
        reload_Facets_with_Map(facets_info_data_obj, atts, params1);
        jQuery("#content").prepend("<div id='drs-selection' class='col-md-10'><a class='themebutton btn btn-more' href='#' data-type='f' data-facet='"+facet+"' data-val='"+facet_val+"'>"+facet+" > "+facet_val+" <span class='fa fa-close'></span></a></div>");
    });

    function reload_Facets_with_Map (facets_info_data_obj, atts, params1){
        console.log("I am here within ajax");
       $.ajax({
            type: 'POST',
            url: facets_info_data_obj.ajax_url,
            data: {
                _ajax_nonce: facets_info_data_obj.nonce,
                action: "reload_filtered_set",
                atts: atts,
                params: params1,
                reloadWhat: "mapReload"
            },
            success: function(data)
            {
                $('#map').remove();

                $(".entry-content").html(data);

                var home_url = map_obj.home_url;
                var apiKey = getApiKey($('#map'));
                var projectKey = getProjectKey($('#map'));

                var colorGroups = getColorGroups($('#map'));
                var colorDescriptions = getColorDescriptions($('#map'));

                var items = getItemsFromJqueryArray($('.coordinates'));

                var mymap = createMap('map');

                addTileLayerToMap(mymap, apiKey, projectKey);

                var markerCluster = addPopupsToItems(items, mymap, colorGroups, home_url);

                var customItems = getCustomItems($('.custom-coordinates'));

                var markerCluster = addCustomItemsToMap(customItems, markerCluster, home_url);

                fitToBounds(items, customItems, mymap);

                addLegendToMap(colorDescriptions, mymap, home_url);

                if (isStoryModeEnabled($('#map'))) {
                    addStoryModeToMap(items, mymap, markerCluster, customItems);
                }
                //console.log("Map Reload Done, now reload the facet");

                reloadFacetInfo(facets_info_data_obj, atts, params1)
            }, error: function()
            {
                alert("failure");
            }
        });
    }

    function reloadFacetInfo(facets_info_data_obj, atts, params1) {
        //alert("Reloading Facets");

        jQuery.ajax({
            type: 'POST',
            url: facets_info_data_obj.ajax_url,
            data: {
                _ajax_nonce: facets_info_data_obj.nonce,
                action: "reload_filtered_set",
                atts: atts,
                params: params1,
                reloadWhat: "facetReload"
            },
            success: function(data) {
                //alert("success");
                //.log(data);
                create_left_pane(data.response.facet_counts);
            },
            error: function(){
                alert("failure");
            }
        });
    }


    function create_left_pane(data){

        jQuery("#drs-facets").remove();
        html = '<div id="drs-facets" class="one_fourth hidden-phone hidden-xs hidden-sm"';
        html += '></div';
        html += '>';

        jQuery("#search-and-facet").append(html);
        jQuery("#primary").removeClass("col-md-9");
        jQuery("#primary").addClass("col-md-8");

        jQuery("#secondary").removeClass("col-md-3");
        jQuery("#secondary").addClass("col-md-2");


        var facet_html = '';
        var facet_title = {creator_sim: "Creator",creation_year_sim: "Creation year",  subject_sim: "Subject", drs_department_ssim: "Department", drs_degree_ssim: "Course Degree", drs_course_title_ssim: "Course Title"};

        facet_html = parse_facets(data, facet_title, facet_html);
        $("#drs-facets").html(facet_html);
        //$(".drs-facet-toggle").remove();
        //$("#drs-facets").before("<button class='themebutton button btn visible-phone hidden-tablet hidden-desktop drs-facet-toggle hidden-md hidden-lg visible-sm visible-xs'>Show Facets</button>");


        function parse_facets(data, object, facet_html){
            $.each(object, function(facet, title){
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
                        var sortBy = "fc_desc";
                        var sorts = sortBy.split("_");
                        var r1 = (sorts[1] === "desc" ? -1 : 1);
                        var type = (sorts[0] === "fc" ? 'k' : 'v');
                        if(a[type] > b[type]){ return r1; }
                        if(a[type] < b[type]){ return r1 *= -1; }
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
            return facet_html;
        }

       /* // CREATOR
        var test = "";
        var i = 0;
        jQuery.each(a.facet_fields.creator_sim, function(key, val){
            if(i == 0){
                creator_pane ='<div id="drs_creator_sim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Creator</b></div><div class="panel-body"></div></div></div>';
                jQuery("#drs-facets").append(creator_pane);
            }
            i++;
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });
        jQuery("#drs_creator_sim .panel .panel-body").append(test);

        // CREATION YEAR
        test = "";
        i=0;
        jQuery.each(a.facet_fields.creation_year_sim, function(key, val){
            if(i == 0){
                creationYear_pane ='<div id="drs_creation_year_sim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Creation Year</b></div><div class="panel-body"></div></div></div>';
                jQuery("#drs-facets").append(creationYear_pane);
            }
            i++;
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });
        jQuery("#drs_creation_year_sim .panel .panel-body").append(test);

        // SUBJECT
        test = "";
        i=0;
        jQuery.each(a.facet_fields.subject_sim, function(key, val){
            if(i == 0){
                subject_pane ='<div id="subject_sim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Subject</b></div><div class="panel-body"></div></div></div>';
                jQuery("#drs-facets").append(subject_pane);
            }
            i++;
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });
        jQuery("#subject_sim .panel .panel-body").append(test);

        // COURSE NUMBER
        test = "";
        i=0;
        jQuery.each(a.facet_fields.drs_course_number_ssim, function(key, val){
            if(i == 0){
                courseNumber_pane ='<div id="drs_course_number_ssim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Course Number</b></div><div class="panel-body"></div></div></div>';
                jQuery("#drs-facets").append(courseNumber_pane);
            }
            i++;
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });
        jQuery("#drs_course_number_ssim .panel .panel-body").append(test);

        // COURSE TITLE
        test = "";
        i=0;
        jQuery.each(a.facet_fields.drs_course_title_ssim, function(key, val){
            if(i == 0){
                courseTitle_pane ='<div id="drs_course_title_ssim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Course Title</b></div><div class="panel-body"></div></div></div>';
                jQuery("#drs-facets").append(courseTitle_pane);
            }
            i++;
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });

        jQuery("#drs_course_title_ssim .panel .panel-body").append(test);

        // COURSE DEGREE
        test = "";
        i=0;
        jQuery.each(a.facet_fields.drs_degree_ssim, function(key, val){
            if(i == 0){
                courseDegree_pane ='<div id="drs_degree_ssim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Degree</b></div><div class="panel-body"></div></div></div>';
                jQuery("#drs-facets").append(courseDegree_pane);
            }
            i++;
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });

        jQuery("#drs_degree_ssim .panel .panel-body").append(test);

        // DEPARTMENT
        test = "";
        i=0;
        jQuery.each(a.facet_fields.drs_department_ssim, function(key, val){
            if(i == 0){
                courseDepartment_pane ='<div id="drs_department_ssim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Department</b></div><div class="panel-body"></div></div></div>';
                jQuery("#drs-facets").append(courseDepartment_pane);
            }
            i++;
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });
        jQuery("#drs_department_ssim .panel .panel-body").append(test);

        $( "#drs-facets a" ).bind( "click", function(e) {
            e.preventDefault();
            var facet = $(this).parents('.drs-facet').attr("id");
            if ($(this).parent().hasClass('modal-body')){
                facet = $(this).parents('.modal').attr('id').substr(10);
                $(this).parents('.modal').modal('hide');
            } else {
                facet = facet.substr(4);
            }

            var facet_val = $(this).children(".drs-facet-val div:first-of-type").html();
            params1.f[facet] = facet_val;
            selectedItem.push(facet_val);
            reload_Facets_with_Map(facets_info_data_obj, atts, params1);
            jQuery("#drs-selection").append("<a class='themebutton btn btn-more' href='#' data-type='f' data-facet='"+facet+"' data-val='"+facet_val+"'>"+facet+" > "+facet_val+" <span class='fa fa-close'></span></a>");
        });*/
    }

    // $("#drs-selection a").on("click", function(e){
    //     e.preventDefault();
    //     var facet = $(this).data("facet");
    //     delete params.f[facet];
    //     $(this).remove();
    //     get_data(params);
    //     get_wp_data(params.q);
    // });

    function check(){
        alert("searched ...");
    }
});

