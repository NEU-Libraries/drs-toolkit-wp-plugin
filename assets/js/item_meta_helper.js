jQuery(document).ready(function(){
  jQuery("input[name='drstk_item_metadata']").on('change', function(){
    compile_val("input[name='drstk_item_metadata']", "input[name='drstk_item_page_metadata']");
  });
  jQuery("input[name='drstk_assoc_metadata']").on('change', function(){
    compile_val("input[name='drstk_assoc_metadata']", "input[name='drstk_assoc_file_metadata']");
  });
  jQuery("input[name='drstk_assoc']").on('change', function(){
    if (jQuery(this).is(":checked")){
      jQuery("tr.assoc").css("display","table-row");
    } else {
      jQuery("tr.assoc").css("display","none");
    }
  });
  jQuery("input[name='drstk_facet']").on('change', function(){
    compile_val("input[name='drstk_facet']", "input[name='drstk_facets']");
    if (jQuery(this).is(":checked")){
      jQuery(this).parents("td").next(".title").css("display","table-cell");
    } else {
      jQuery(this).parents("td").next(".title").css("display","none");
    }
  });
  jQuery("#facets_sortable").sortable();
  jQuery("#facets_sortable").on( "sortstop", function( event, ui ) {
    compile_val("input[name='drstk_facet']", "input[name='drstk_facets']");
  });

  function compile_val(ind, agg){
    var array = [];
    jQuery(ind).each(function(){
      if (jQuery(this).is(":checked")){
        array.push(jQuery(this).val());
      }
    });
    jQuery(agg).val(array);
  }
});
