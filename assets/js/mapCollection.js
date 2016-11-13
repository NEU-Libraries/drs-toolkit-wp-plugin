/**
 * Created by Abhi on 11/10/2016.
 */

jQuery(document).ready(function($) {
    var a = facets_info_data_obj.data.response.facet_counts
    var atts = facets_info_data_obj.atts;
    var map_obj = facets_info_data_obj.map_obj;

    var f = {};
    var params1 = {f: f};
    var selectedItem =[];

    create_left_pane(a);

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
        params1.f[facet] = facet_val;
        selectedItem.push(facet_val);
        reload_Facets(facets_info_data_obj, atts, params1);
        jQuery("#content").prepend("<div id='drs-selection' class='col-md-10'><a class='themebutton btn btn-more' href='#' data-type='f' data-facet='"+facet+"' data-val='"+facet_val+"'>"+facet+" > "+facet_val+" <span class='fa fa-close'></span></a></div>");
    });

    function reload_Facets (facets_info_data_obj, atts, params1){
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

    function reloadFacetInfo(facets_info_data_obj, atts, params1)
    {
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


    function create_left_pane(a){

        jQuery("#drs-facets").remove();
        html = '<div id="drs-facets" class="one_fourth col-md-2 hidden-phone hidden-xs hidden-sm"';
        html += '></div';
        html += '>';

        jQuery("#content .row").prepend(html);
        jQuery("#primary").removeClass("col-md-9");
        jQuery("#primary").addClass("col-md-8");

        jQuery("#secondary").removeClass("col-md-3");
        jQuery("#secondary").addClass("col-md-2");

        // CREATOR
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
            reload_Facets(facets_info_data_obj, atts, params1);
            jQuery("#drs-selection").append("<a class='themebutton btn btn-more' href='#' data-type='f' data-facet='"+facet+"' data-val='"+facet_val+"'>"+facet+" > "+facet_val+" <span class='fa fa-close'></span></a>");
        });
    }

    $("#drs-selection a").on("click", function(e){
        e.preventDefault();
        var facet = $(this).data("facet");
        delete params.f[facet];
        $(this).remove();
        get_data(params);
        get_wp_data(params.q);
    });
});

