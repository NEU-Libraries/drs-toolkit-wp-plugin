jQuery(document).ready(function(){
  function assoc_checked(input, klass){
    if (jQuery(input).is(":checked")){
      jQuery(klass).css("display","table-row");
    } else {
      jQuery(klass).css("display","none");
    }
  }

  assoc_checked("input[name='drstk_assoc']", ".assoc");
  assoc_checked("input[name='drstk_niec']", ".niec");

  jQuery("input[name='drstk_assoc']").on('change', function(){
    assoc_checked("input[name='drstk_assoc']", ".assoc");
  });
  jQuery("input[name='drstk_niec']").on('change', function(){
    assoc_checked("input[name='drstk_niec']", ".niec");
  });

  jQuery("input[name='drstk_facets[]'], input[name='drstk_niec_metadata[]']").on('change', function(){
    if (jQuery(this).is(":checked")){
      jQuery(this).parents("td").next(".title").css("display","table-cell");
    } else {
      jQuery(this).parents("td").next(".title").css("display","none");
    }
  });

  jQuery("#facets_sortable").sortable();
  jQuery("#niec_facets_sortable").sortable();
});
