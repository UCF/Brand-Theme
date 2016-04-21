// Define globals for JSHint validation:
/* global console, PostTypeSearchDataManager */


var Generic = {};


Generic.addBodyClasses = function($) {
  // Assign browser-specific body classes on page load
    var bodyClass = '';
    // Old IE:
    if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) { //test for MSIE x.x;
            var ieversion = Number(RegExp.$1); // capture x.x portion and store as a number

            if (ieversion >= 10)     { bodyClass = 'ie ie10'; }
            else if (ieversion >= 9) { bodyClass = 'ie ie9'; }
            else if (ieversion >= 8) { bodyClass = 'ie ie8'; }
            else if (ieversion >= 7) { bodyClass = 'ie ie7'; }
    }
     // IE11+:
    else if (navigator.appName === 'Netscape' && !!navigator.userAgent.match(/Trident\/7.0/)) { bodyClass = 'ie ie11'; }
    // iOS:
    else if (navigator.userAgent.match(/iPhone/i)) { bodyClass = 'iphone'; }
    else if (navigator.userAgent.match(/iPad/i))   { bodyClass = 'ipad'; }
    else if (navigator.userAgent.match(/iPod/i))   { bodyClass = 'ipod'; }
    // Android:
    else if (navigator.userAgent.match(/Android/i)) { bodyClass = 'android'; }

    $('body').addClass(bodyClass);
};

Generic.handleExternalLinks = function($){
  $('a:not(.ignore-external)').each(function(){
    var url  = $(this).attr('href');
    var host = window.location.host.toLowerCase();

    if (url && url.search(host) < 0 && url.search('http') > -1){
      $(this).attr('target', '_blank');
      $(this).addClass('external');
    }
  });
};

Generic.loadMoreSearchResults = function($){
  var more        = '#search-results .more';
  var items       = '#search-results .result-list .item';
  var list        = '#search-results .result-list';
  var start_class = 'new-start';

  var next = null;
  var sema = null;

  var load = (function(){
    if (sema){
      setTimeout(function(){load();}, 100);
      return;
    }

    if (next === null){return;}

    // Grab results content and append to current results
    var results = $(next).find(items);

    // Add navigation class for scroll
    $('.' + start_class).removeClass(start_class);
    $(results[0]).addClass(start_class);

    $(list).append(results);

    // Grab new more link and replace current with new
    var anchor = $(next).find(more);
    if (anchor.length < 1){
      $(more).remove();
    }
    $(more).attr('href', anchor.attr('href'));

    next = null;
  });

  var prefetch = (function(){
    sema = true;
    // Fetch url for href via ajax
    var url = $(more).attr('href');
    if (url){
      $.ajax({
        'url'     : url,
        'success' : function(data){
          next = data;
        },
        'complete' : function(){
          sema = false;
        }
      });
    }
  });

  var load_and_prefetch = (function(){
    load();
    prefetch();
  });

  if ($(more).length > 0){
    load_and_prefetch();

    $(more).click(function(){
      load_and_prefetch();
      var scroll_to = $('.' + start_class).offset().top - 10;

      $('body, html').animate({'scrollTop' : scroll_to}, 1000);
      return false;
    });
  }
};

Generic.postTypeAutosuggest = function($) {
  var $autosuggests;

  function autosuggest($autosuggest) {
    var id = $autosuggest.attr('id'),
        resultsData = PostTypeAutosuggestManager.searches[id].results.data,
        resultsHeader = PostTypeAutosuggestManager.searches[id].results.header || false,
        resultsLimit = $autosuggest.attr('data-search-limit'),
        fallbacksData = PostTypeAutosuggestManager.searches[id].fallbacks ? PostTypeAutosuggestManager.searches[id].fallbacks.data || false : false,
        fallbacksHeader = PostTypeAutosuggestManager.searches[id].fallbacks ? PostTypeAutosuggestManager.searches[id].fallbacks.header || false : false,
        fallbacksLimit = $autosuggest.attr('data-fallbacks-limit'),
        labelText = $autosuggest.attr('data-search-label'),
        inputPlaceholder = $autosuggest.attr('data-search-placeholder'),
        showLabel = $autosuggest.attr('data-show-label'),
        inputClass = $autosuggest.attr('data-input-class'),
        $label = $('<label for="input-'+ id +'">'+ labelText +'</label>'),
        $input = $('<input id="input-'+ id +'" type="text" class="form-control" autocomplete="off" placeholder="'+ inputPlaceholder +'">'),
        $icon = $('<span class="glyphicon glyphicon-search"></span>');

    var typeaheadSource = function(query, syncResults, asyncResults) {
      // Match against keyterms in result.matches
      var matches = [];

      for (var i = 0; i < resultsData.length; i++) {
        var result = resultsData[i];
        for (var j = 0; j < result.matches.length; j++) {
          var term = result.matches[j];
          if (~term.toLowerCase().indexOf(query.toLowerCase())) {
            matches.push(result);
            break;
          }
        }
      }

      return syncResults(matches);
    };

    var typeaheadFallbackSource = function(query, syncResults, asyncResults) {
      // Always return all fallback results, if fallbacks are available.
      if (fallbacksData.length) {
        return syncResults(fallbacksData);
      }
      else {
        return;
      }
    };

    function typeaheadConfigs() {
      var retval = [];

      // General results config
      var results = {
        display: 'title',
        source: typeaheadSource
      };

      if (resultsLimit) {
        results.limit = parseInt(resultsLimit, 10);
      }

      if (resultsHeader) {
        results.templates = {
          header: ['<span class="dropdown-header">'+ resultsHeader +'</span>']
        };
      }

      retval.push(results);

      // Fallback results config
      if (fallbacksData.length) {
        var fallbacks = {
          display: 'title',
          source: typeaheadFallbackSource
        };

        if (fallbacksLimit) {
          fallbacks.limit = parseInt(fallbacksLimit, 10);
        }

        if (fallbacksHeader) {
          fallbacks.templates = {
            header: ['<span class="dropdown-header">'+ fallbacksHeader +'</span>']
          };
        }

        retval.push(fallbacks);
      }

      return retval;
    }

    function init() {
      if (showLabel === 'false' || showLabel === '') {
        $label.addClass('sr-only');
      }

      $label.appendTo($autosuggest);

      $input
        .addClass(inputClass)
        .appendTo($autosuggest)
        .typeahead(
          {
            minLength: 1,
            highlight: true
          },
          typeaheadConfigs()
        )
        .on('typeahead:select', function(e, suggestion) {
          document.location = suggestion.permalink;
        })
        .after($icon);
    }

    init();
  }


  $autosuggests = $('.post-type-autosuggest');

  if ($autosuggests.length) {
    for (var i = 0; i < $autosuggests.length; i++) {
      autosuggest($autosuggests.eq(i));
    }
  }
};


if (typeof jQuery !== 'undefined'){
  (function(){
    $(document).ready(function() {
      Generic.addBodyClasses($);
      Generic.handleExternalLinks($);
      Generic.loadMoreSearchResults($);
      Generic.postTypeAutosuggest($);
    });
  })(jQuery);
}
