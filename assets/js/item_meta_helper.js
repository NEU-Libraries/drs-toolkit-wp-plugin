jQuery(document).ready(function(){
  function assoc_checked(){
    if (jQuery("input[name='drstk_assoc']").is(":checked")){
      jQuery(".assoc").css("display","table-row");
    } else {
      jQuery(".assoc").css("display","none");
    }
  }

  assoc_checked();

  jQuery("input[name='drstk_assoc']").on('change', function(){
    assoc_checked();
  });

  jQuery("input[name='drstk_facets[]']").on('change', function(){
    if (jQuery(this).is(":checked")){
      jQuery(this).parents("td").next(".title").css("display","table-cell");
    } else {
      jQuery(this).parents("td").next(".title").css("display","none");
    }
  });

  jQuery("#facets_sortable").sortable();
});
