/* global tinymce */
( function() {
	tinymce.PluginManager.add( 'drstkshortcodes', function( editor ) {
    editor.on('DblClick',function(e) {
      shortcode = e.srcElement.innerText;
      shortcode = String(shortcode);
      old_shortcode = shortcode;
      if (shortcode.charAt(0) == "[" && shortcode.charAt(shortcode.length-1) == "]"){
        type = shortcode.split("id=")[0].trim();
        type = type.split("_")[1].trim();
				if (type == 'tiles'){type = 'tile'}
				if (type == 'item'){type = 'single'}
				if (type == 'gallery'){type = 'slider'}
				if (type == 'collection'){type = 'media'}
        ids = [];
        params = getShortcodeParams(shortcode);
        params.id = params.id.split(",");
        var items = [];
        jQuery.each(params.id, function(key, item){
          item = item.trim();
          this_item = new drstk.Item;
          repo = item.split(":")[0];
          if (repo == "wp"){ repo = "local";}
          if (repo == "neu"){ repo = "drs";} else { item = item.split(":")[1]; } //non drs pids don't need prefix
          this_item.set("pid", item).set("repo", repo); //TODO- set color here too if color_id or what not
          items.push(this_item);
        });
        delete params.id;
        if (params.metadata) {params.metadata = params.metadata.split(",");}
        editor.dom.remove(e.srcElement);
        drstk.backbone_modal.__instance = new drstk.backbone_modal.Application({current_tab:type, items: items, old_shortcode:old_shortcode, settings:params});
      }
    });
	});

  function getShortcodeParams(shortcode) {
    var re = /([a-z-_]{1,})="(.*?)"/g,
        match, params = {},
        decode = function (s) {return s};

    while (match = re.exec(shortcode)) {
      params[decode(match[1])] = decode(match[2]);
    }
    return params;
  }
})();
