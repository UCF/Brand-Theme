// Define globals for JSHint validation:
/* global console */

// Theme Specific Code Here

(function () {

  var template = Handlebars.compile($('#js-result-template').html()),
      $results = $('#js-results'),
      $resultsWaiting = $('#js-results-waiting'),
      $search = $('#js-search'),
      timeout;

  function doSearch() {
    $.ajax({
      url: 'wp-json/wp/v2/media?filter[s]=' + $search.val(),
      beforeSend: function() {
        $resultsWaiting.removeClass('hidden');
      }
    })
      .done(function(data) {
        var html = '';

        if (data.length) {
          for (var i = 0; i < data.length; i++) {
            html += template(data[i]);
          }
        }
        else {
          html = 'No results found.';
        }

        $results.html(html);
      })
      .fail(function() {
        $results.html('Could not complete your request.  Please try again later.');
      })
      .always(function() {
        $resultsWaiting.addClass('hidden');
      });
  }

  function searchTypeHandler() {
    if (timeout) {
      clearTimeout(timeout);
    }
    timeout = setTimeout(function() { doSearch(); }, 100);
  }

  // Update search results with WP API data.
  $search.on('keyup', searchTypeHandler);

})();
