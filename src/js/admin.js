// Adds filter method to array objects
// https://developer.mozilla.org/en/JavaScript/Reference/Global_Objects/Array/filter

/* jshint ignore:start */
if (!Array.prototype.filter) {
  Array.prototype.filter = function(a) {
    "use strict";
    if (this === void 0 || this === null) throw new TypeError;
    var b = Object(this);
    var c = b.length >>> 0;
    if (typeof a !== "function") throw new TypeError;
    var d = [];
    var e = arguments[1];
    for (var f = 0; f < c; f++) {
      if (f in b) {
        var g = b[f];
        if (a.call(e, g, f, b)) d.push(g)
      }
    }
    return d
  }
}
/* jshint ignore:end */


var WebcomAdmin = {};


WebcomAdmin.__init__ = function($){
  // Allows forms with input fields of type file to upload files
  $('input[type="file"]').parents('form').attr('enctype','multipart/form-data');
  $('input[type="file"]').parents('form').attr('encoding', 'multipart/form-data');
  // Gravity Forms Overide
  $(('input[name=notification_resend]')).val('Send Notifications');
};


WebcomAdmin.utilityPageSections = function($){
  var cls      = this;
  cls.active   = null;
  cls.parent   = $('.i-am-a-fancy-admin');
  cls.sections = $('.i-am-a-fancy-admin .fields .section');
  cls.buttons  = $('.i-am-a-fancy-admin .sections .section a');
  cls.buttonWrap = $('.i-am-a-fancy-admin .sections');
  cls.sectionLinks = $('.i-am-a-fancy-admin .fields .section a[href^="#"]');

  this.showSection = function(){
    var button  = $(this);
    var href    = button.attr('href');
    var section = $(href);

    if (cls.buttonWrap.find('.section a[href="'+href+'"]') && section.is(cls.sections)) {
      // Switch active styles
      cls.buttons.removeClass('active');
      button.addClass('active');

      cls.active.hide();
      cls.active = section;
      cls.active.show();

      history.pushState({}, '', button.attr('href'));
      var http_referrer = cls.parent.find('input[name="_wp_http_referer"]');
      http_referrer.val(window.location);
      return false;
    }
  };

  this.__init__ = function(){
    cls.active = cls.sections.first();
    cls.sections.not(cls.active).hide();
    cls.buttons.first().addClass('active');
    cls.buttons.click(this.showSection);
    cls.sectionLinks.click(this.showSection);

    if (window.location.hash){
      cls.buttons.filter('[href="' + window.location.hash + '"]').click();
    }

    var fadeTimer = setInterval(function(){
      $('.updated').fadeOut(1000);
      clearInterval(fadeTimer);
    }, 2000);
  };

  if (cls.parent.length > 0){
    cls.__init__();
  }
};


/**
 * Adds file uploader functionality to File fields.
 * Mostly copied from https://codex.wordpress.org/Javascript_Reference/wp.media
 **/
WebcomAdmin.fileUploader = function($) {
  $('.meta-file-wrap').each(function() {
    var frame,
        $container = $(this),
        $field = $container.find('.meta-file-field'),
        $uploadBtn = $container.find('.meta-file-upload'),
        $deleteBtn = $container.find('.meta-file-delete'),
        $previewContainer = $container.find('.meta-file-preview');

    // Add new btn click
    $uploadBtn.on('click', function(e) {
      e.preventDefault();

      // If the media frame already exists, reopen it.
      if (frame) {
        frame.open();
        return;
      }

      // Create a new media frame
      frame = wp.media({
        title: 'Select or Upload a File',
        button: {
          text: 'Use this file'
        },
        multiple: false  // Set to true to allow multiple files to be selected
      });

      // When an image is selected in the media frame...
      frame.on('select', function() {

        // Get media attachment details from the frame state
        var attachment = frame.state().get('selection').first().toJSON();

        // Send the attachment URL to our custom image input field.
        $previewContainer.html( '<img src="' + attachment.iconOrThumb + '"><br>' + attachment.filename );

        // Send the attachment id to our hidden input
        $field.val(attachment.id);

        // Hide the add image link
        $uploadBtn.addClass('hidden');

        // Unhide the remove image link
        $deleteBtn.removeClass('hidden');
      });

      // Finally, open the modal on click
      frame.open();
    });

    // Delete selected btn click
    $deleteBtn.on('click', function(e) {
      e.preventDefault();

      // Clear out the preview image
      $previewContainer.html('No file selected.');

      // Un-hide the add image link
      $uploadBtn.removeClass('hidden');

      // Hide the delete image link
      $deleteBtn.addClass('hidden');

      // Delete the image id from the hidden input
      $field.val('');
    });
  });
};


WebcomAdmin.shortcodeInterfaceTool = function($) {
  var $cls                   = WebcomAdmin.shortcodeInterfaceTool;
  $cls.shortcodeForm         = $('#select-shortcode-form');
  $cls.shortcodeButton       = $cls.shortcodeForm.find('button');
  $cls.shortcodeSelect       = $cls.shortcodeForm.find('#shortcode-select');
  $cls.shortcodeEditors      = $cls.shortcodeForm.find('#shortcode-editors');
  $cls.shortcodeDescriptions = $cls.shortcodeForm.find('#shortcode-descriptions');

  $cls.shortcodeInsert = function(shortcode, parameters, enclosingText) {
    var text = '[' + shortcode;

    if (parameters) {
      for (var key in parameters) {
        text += " " + key + "=\"" + parameters[key] + "\"";
      }
    }

    text += "]";

    if (enclosingText) {
      text += enclosingText;
    }
    text += "[/" + shortcode + "]";

    send_to_editor(text);
  };

  $cls.shortcodeAction = function() {
    var $selected = $cls.shortcodeSelect.find(':selected');
    if ($selected.length < 1 || $selected.val() === '') { return; }

    var $editor = $cls.shortcodeEditors.find('li.shortcode-' + $cls.shortcodeSelected),
        dummyText = $selected.attr('data-enclosing') || null,
        highlightedWysiwigText = tinymce.activeEditor ? tinymce.activeEditor.selection.getContent() : null,
        enclosingText = null;

    if (dummyText && highlightedWysiwigText) {
      enclosingText = highlightedWysiwigText;
    } else {
      enclosingText = dummyText;
    }

    var parameters = {};

    if ($editor.length === 1) {
      $editor.find('.shortcode-editor-input').each(function() {
        var $formElement = $(this);
        switch($formElement.prop('tagName')) {
          case 'INPUT':
          case 'TEXTAREA':
          case 'SELECT':
            if ($formElement.prop('type') === 'checkbox') {
              parameters[$formElement.attr('data-parameter')] = String($formElement.prop('checked'));
            } else {
              parameters[$formElement.attr('data-parameter')] = $formElement.val();
            }
            break;
        }
      });
    }

    $cls.shortcodeInsert($selected.val(), parameters, enclosingText);
  };

  $cls.shortcodeSelectAction = function() {
    $cls.shortcodeSelected = $cls.shortcodeSelect.val();
    $cls.shortcodeEditors.find('li').hide();
    $cls.shortcodeDescriptions.find('li').hide();
    $cls.shortcodeEditors.find('.shortcode-' + $cls.shortcodeSelected).show();
    $cls.shortcodeDescriptions.find('.shortcode-' + $cls.shortcodeSelected).show();
  };

  $cls.shortcodeSelectAction();

  $cls.shortcodeSelect.change($cls.shortcodeSelectAction);

  $cls.shortcodeButton.click($cls.shortcodeAction);

};

/**
 * AngularJS code to get, upload and delete files from AWS
 **/

WebcomAdmin.S3CRUD = function ($) {

  // Configure The S3 Object
  AWS.config.update({ accessKeyId: creds.access_key, secretAccessKey: creds.secret_key });
  AWS.config.region = 'us-east-1';

  var aws = new AWS.S3();

  angular.module('UIDFileUpload', []);
  angular.module('UIDFileUpload').factory('UIDFileUploadFactory', UIDFileUploadFactory);
  angular.module('UIDFileUpload').controller('UIDFileUploadController', UIDFileUploadController);
  angular.module('UIDFileUpload').directive('uidFileUploadDirective', UIDFileUploadDirective);
  angular.module('UIDFileUpload').filter('getfilename', getFilenameFilter);
  angular.module('UIDFileUpload').filter('exactmatchfilter', exactMatchFilter);

  // Factory

  UIDFileUploadFactory.$inject = ['$filter'];
  function UIDFileUploadFactory($filter) {
    var getFileList = function (callback) {
      var $postName = $('#editable-post-name-full');
      if ($postName.length) {
        var postNameStr = $postName.text(),
          params = {
          Bucket: creds.bucket,
          Prefix: creds.folder + '/' + postNameStr
        };

        aws.listObjectsV2(params, function (err, data) {
          if (err) {
            callback({ error: true });
            console.log(err, err.stack);
          } else {
            callback($filter('exactmatchfilter')(data.Contents,postNameStr));
          }
        });
      } else {
        callback([]);
      }
    };

    return {
      getFileList: getFileList
    };
  }

  // Controller

  UIDFileUploadController.$inject = ['$scope', 'UIDFileUploadFactory'];
  function UIDFileUploadController($scope, UIDFileUploadFactory) {
    var ctrl = this;
    ctrl.loading = false;
    ctrl.error = false;
    ctrl.bucket = creds.bucket;

    var displayError = function () {
      ctrl.loading = false;
      ctrl.error = true;
      $scope.$apply();
    };

    var updateView = function (fileList) {
      ctrl.loading = false;
      if (fileList && fileList.length) {
        if (fileList.error) {
          displayError();
        } else {
          ctrl.fileList = fileList;
          $scope.$apply();
        }
      } else {
        ctrl.fileList = [];
      }
    };

    ctrl.uploadFile = function () {
      if ($scope.file) {
        ctrl.loading = true;
        ctrl.error = false;
        var orgFilename = $scope.file.name,
          postName = $('#editable-post-name-full');

        if (postName.length) {
          if (orgFilename.indexOf('.zip') !== -1 || orgFilename.indexOf('.png') !== -1) {
            postName = postName.text();

            var extension = '.png';
            if (orgFilename.indexOf('.zip') !== -1) {
              extension = '.zip';
            }

            postName = creds.folder + '/' + postName + '/' + postName;
            var params = { Bucket: creds.bucket, Key: postName + extension, ContentType: $scope.file.type, Body: $scope.file, ServerSideEncryption: 'AES256' };

            aws.putObject(params, function (err, data) {
              if (err) {
                displayError();
                console.log(err.message);
              } else {
                UIDFileUploadFactory.getFileList(updateView);
              }
            });
          } else {
            // No File Selected
            alert('Please Select a File with a .png or .zip file extension.');
            UIDFileUploadFactory.getFileList(updateView);
          }
        } else {
          alert('Please enter a title above and wait for permalink to appear.');
          ctrl.loading = false;
        }
      } else {
        alert('Please Select a File.');
        UIDFileUploadFactory.getFileList(updateView);
      }
    };

    ctrl.deleteFile = function (index) {
      ctrl.loading = true;
      ctrl.error = false;

      var params = {
          Bucket: creds.bucket,
          Key: ctrl.fileList[index].Key
      };

      aws.deleteObject(params, function (err, data) {
        if (err) {
          displayError();
          console.log(err, err.stack);
        } else {
          UIDFileUploadFactory.getFileList(updateView);
        }
      });
    };

    // Get file list on page load
    UIDFileUploadFactory.getFileList(updateView);

  }

  // Directive

  function UIDFileUploadDirective() {
      return {
          restrict: 'AE',
          scope: {
              file: '@'
          },
          link: function (scope, el, attrs) {
              el.bind('change', function (event) {
                var files = event.target.files,
                    file = files[0];

                scope.file = file;
                scope.$parent.file = file;
                scope.$apply();
              });
          }
      };
  }

  // Filter

  function getFilenameFilter() {
    return function (item) {
      var itemArray = item.split('/');
      return itemArray[itemArray.length-1];
    };
  }

  function exactMatchFilter() {
    return function (array, search) {
      var matchArray = [];

      // find items that are an exact match
      array.forEach(function(element) {
        if (element.Key.indexOf('/' + search + '.png') !== -1 || element.Key.indexOf('/' + search + '.zip') !== -1) {
          matchArray.push(element);
        }
      }, this);

      return matchArray;
    };
  }

};

(function($){
  WebcomAdmin.__init__($);
  WebcomAdmin.utilityPageSections($);
  WebcomAdmin.fileUploader($);
  WebcomAdmin.shortcodeInterfaceTool($);
  if ($('.amazon-file-upload').length) {
    WebcomAdmin.S3CRUD($);
  }
})(jQuery);
