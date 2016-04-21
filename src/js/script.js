// Define globals for JSHint validation:
/* global console */

// Theme Specific Code Here

(function () {

  var template = Handlebars.compile($('#result-template').html()),
      $results = $('#results'),
      $search = $('#search');

  function doSearch() {
    $results.html('');

    $.ajax({
      url: 'wp-json/wp/v2/media?filter[s]=' + $search.val(),
      success: function(data) {
        if (data.length) {
          for (var i = 0; i < data.length; i++) {
            html = template(data[i]);
            $results.append(html);
          }
        }
      }
    });
  }

  // Update search results with WP API data.
  $search.on('keyup', doSearch);

})();
