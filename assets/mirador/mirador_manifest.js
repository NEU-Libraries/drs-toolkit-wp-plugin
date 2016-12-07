// this is a sample JS file for calling a manifest - it must be modified for each collection and correspond to a manifest.json file existing on this server
$(function() {
 Mirador({
   "id": "mirador_viewer",
   "layout": "1x1",
   "data": [
      { "manifestUri": "http://localhost/~beekerz/wordpress/manifest.json", "location":"Northeastern University Digital Repository Service (DRS)"},
   ],
   "windowObjects": [
          {
          "loadedManifest" : "http://localhost/~beekerz/wordpress/manifest.json",
          "viewType" : "BookView"}
   ],
   'buildPath' : '/~beekerz/wordpress/wp-content/plugins/drs-tk/assets/mirador/',
 });
});
