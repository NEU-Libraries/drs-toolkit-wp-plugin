
jQuery(document).ready(function($) {
    //console.log(check_obj.data.response.facet_counts);
    var a = main_data.data.response.facet_counts
    console.log(a);

    create_left_pane();

    function create_left_pane(){
        //alert("Test Test");
        html = '<div id="drs-facets-1" class="one_fourth col-md-2 hidden-phone hidden-xs hidden-sm"';
        html += '></div';
        html += '>';

        jQuery("#content .row").prepend(html);
        jQuery("#primary").removeClass("col-md-9");
        jQuery("#primary").addClass("col-md-8");

        jQuery("#secondary").removeClass("col-md-3");
        jQuery("#secondary").addClass("col-md-2");

        // CREATOR
        creator_pane ='<div id="drs_creator_sim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Creator</b></div><div class="panel-body"></div></div></div>';
        jQuery("#drs-facets-1").append(creator_pane);

        var test = "";
        $.each(a.facet_fields.creator_sim, function(key, val){
            console.log(key+ " " +val);
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });

        jQuery("#drs_creator_sim .panel .panel-body").append(test);

        // CREATION YEAR
        creationYear_pane ='<div id="drs_creation_year_sim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Creation year</b></div><div class="panel-body"></div></div></div>';
        jQuery("#drs-facets-1").append(creationYear_pane);

        test = "";
        $.each(a.facet_fields.creation_year_sim, function(key, val){
            console.log(key+ " " +val);
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });

        jQuery("#drs_creation_year_sim .panel .panel-body").append(test);

        // COURSE NUMBER
        courseNumber_pane ='<div id="drs_course_number_ssim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Course Number</b></div><div class="panel-body"></div></div></div>';
        jQuery("#drs-facets-1").append(courseNumber_pane);

        test = "";
        $.each(a.facet_fields.drs_course_number_ssim, function(key, val){
            console.log(key+ " " +val);
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });

        jQuery("#drs_course_number_ssim .panel .panel-body").append(test);

        // COURSE TITLE
        courseTitle_pane ='<div id="drs_course_title_ssim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Course Title</b></div><div class="panel-body"></div></div></div>';
        jQuery("#drs-facets-1").append(courseTitle_pane);

        test = "";
        $.each(a.facet_fields.drs_course_title_ssim, function(key, val){
            console.log(key+ " " +val);
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });

        jQuery("#drs_course_title_ssim .panel .panel-body").append(test);

        // COURSE DEGREE
        courseDegree_pane ='<div id="drs_degree_ssim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Degree</b></div><div class="panel-body"></div></div></div>';
        jQuery("#drs-facets-1").append(courseDegree_pane);

        test = "";
        $.each(a.facet_fields.drs_degree_ssim, function(key, val){
            console.log(key+ " " +val);
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });

        jQuery("#drs_degree_ssim .panel .panel-body").append(test);

        // DEPARTMENT

        courseDepartment_pane ='<div id="drs_department_ssim" class="drs-facet"><div class="panel panel-default"><div class="panel-heading"><b class="drs-facet-name">Department</b></div><div class="panel-body"></div></div></div>';
        jQuery("#drs-facets-1").append(courseDepartment_pane);

        test = "";
        $.each(a.facet_fields.drs_department_ssim, function(key, val){
            console.log(key+ " " +val);
            test += '<a href="#" class="drs-facet-val row"><div class="three_fourth col-xs-8">'+key+'</div><div class="one_fourth col-xs-4 last">'+val+'</div>';
        });

        jQuery("#drs_department_ssim .panel .panel-body").append(test);
    }
});
