// this is a sample JS file for calling a manifest - it must be modified for each collection and correspond to a manifest.json file existing on this server
$(function() {
 Mirador({
   "id": "mirador_viewer",
   "layout": "1x1",
   "data": [
      { "manifestUri": "[insert manifest.json URI like http://localhost/~beekerz/manifest.json]", "location":"Northeastern University Digital Repository Service (DRS)"},
   ],
   "windowObjects": [
          {
          "loadedManifest" : "[insert manifest.json URI http://localhost/~beekerz/manifest.json]",
          "viewType" : "BookView"}
   ],
   'buildPath' : '[insert path to install like /~beekerz/wordpress/wp-content/plugins/drs-tk/assets/mirador/]',
 });
});
