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
});
