// Define globals for JSHint validation:
/* global console */


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


if (typeof jQuery !== 'undefined'){
  (function(){
    $(document).ready(function() {
      Generic.addBodyClasses($);
      Generic.handleExternalLinks($);
      Generic.loadMoreSearchResults($);
    });
  })(jQuery);
}
