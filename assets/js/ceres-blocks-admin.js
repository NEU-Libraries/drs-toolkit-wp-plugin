jQuery(document).ready( function() {
   jQuery('body').on('click', '.ceres.drs-search', function(e) {
       const testHtml = 
           '<ul>' +
                '<li>' +
                  '<label for="tile-neu:1033">' + 
                  '<img src="https://repository.library.northeastern.edu/downloads/neu:3917?datastream_id=thumbnail_1">' +
                  '<br>' +
                  '<input id="tile-neu:1033" type="checkbox" class="tile drs" value="neu:1033">' +
                  '<span class="title">Understanding contemporary maritime piracy</span>' +
                  '</label>' +
                '</li>' +
                '<li>' +
                '<label for="tile-neu:1033">' + 
                '<img src="https://repository.library.northeastern.edu/downloads/neu:3917?datastream_id=thumbnail_1">' +
                '<br>' +
                '<input id="tile-neu:1033" type="checkbox" class="tile drs" value="neu:1033">' +
                '<span class="title">Understanding contemporary maritime piracy</span>' +
                '</label>' +
              '</li>' +
              '<li>' +
              '<label for="tile-neu:1033">' + 
              '<img src="https://repository.library.northeastern.edu/downloads/neu:3917?datastream_id=thumbnail_1">' +
              '<br>' +
              '<input id="tile-neu:1033" type="checkbox" class="tile drs" value="neu:1033">' +
              '<span class="title">Understanding contemporary maritime piracy</span>' +
              '</label>' +
            '</li>' +
            '</ul>';
       jQuery('#ceres-search-results').html(testHtml);
   })
});
