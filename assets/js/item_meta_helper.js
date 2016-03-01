jQuery(document).ready(function(){
  jQuery("input[name='drstk_item_metadata']").on('change', function(){
    var item_meta = [];
    jQuery("input[name='drstk_item_metadata']").each(function(){
      if (jQuery(this).is(":checked")){
        item_meta.push(jQuery(this).val());
      }
    })
    jQuery("input[name='drstk_item_page_metadata']").val(item_meta);
  });
  jQuery("input[name='drstk_assoc_metadata']").on('change', function(){
    var assoc_meta = [];
    jQuery("input[name='drstk_assoc_metadata']").each(function(){
      if (jQuery(this).is(":checked")){
        assoc_meta.push(jQuery(this).val());
      }
    })
    jQuery("input[name='drstk_assoc_file_metadata']").val(assoc_meta);
  });
  jQuery("input[name='drstk_assoc']").on('change', function(){
    if (jQuery(this).is(":checked")){
      jQuery("tbody.assoc").css("display","table");
    } else {
      jQuery("tbody.assoc").css("display","none");
    }
  })
});
