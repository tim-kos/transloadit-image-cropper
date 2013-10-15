(function ($) {
  function TransloaditCropper() {
  }

  TransloaditCropper.prototype.init = function($container) {
    this.$form          = $container.find('form');
    this.step           = 0;
    this.$imgToCrop     = $container.find('.js-img-to-crop');
    this.cropSelection  = {};
    this.imageImportUrl = null;

    this._bindFormSubmit();
    this._bindTransloaditStep1();

    return this;
  };

  TransloaditCropper.prototype.reset = function() {
    this.cropSelection  = {};
    this.imageImportUrl = null;
    this.step           = 0;

    this.$form.find('input[type=text]').val('');

    this.$imgToCrop
      .hide()
      .attr('src', '')
      .removeAttr('width')
      .removeAttr('height');
  };

  TransloaditCropper.prototype._bindFormSubmit = function() {
    var self = this;

    this.$form.on('submit', function(e) {
      if (self.step === 0) {
        e.preventDefault();
        self._bindTransloaditStep2();
      }
      self.step = 1;
    });
  };

  TransloaditCropper.prototype._bindTransloaditStep1 = function(cb) {
    var self = this;

    this._fetchParamsAndSignatureStep1(function(params, signature) {
      self.$form.transloadit({
        wait: true,
        triggerUploadOnFileSelection: true,
        params: params,
        signature: signature,

        onError: function(assembly) {
          var err = 'There was a problem processing the files.';
          self._showError(err);
        },
        onSuccess: function(assembly) {
          var upload          = assembly.uploads[0];
          self.imageImportUrl = upload.url;

          self.$imgToCrop.attr('src', upload.url);
          self.$imgToCrop.attr('width', upload.meta.width);
          self.$imgToCrop.attr('height', upload.meta.height);
          self.$imgToCrop.show();

          // if the user does not crop, use the full image by default
          self.cropSelection = {
            x1: 0,
            y1: 0,
            x2: upload.meta.width,
            y2: upload.meta.height
          };

          self._bindImgAreaSelect();
        }
      });

      if (cb) {
        cb();
      }
    });
  };

  TransloaditCropper.prototype._bindImgAreaSelect = function() {
    var self = this;

    this.$imgToCrop.imgAreaSelect({
      onSelectEnd: function (img, selection) {
        self.cropSelection = {
          x1: selection.x1,
          y1: selection.y1,
          x2: selection.x2,
          y2: selection.y2
        };

        // rebind transloadit again to use the updated selection
        self._bindTransloaditStep2();
      }
    });
  };

  TransloaditCropper.prototype._bindTransloaditStep2 = function(cb) {
    var self = this;

    this._fetchParamsAndSignatureStep2(function(params, signature) {
      self._unbindtransloadit();
      self.$form.transloadit({
        wait: true,
        fields: true,
        params: params,
        signature: signature,
        onError: function(assembly) {
          var err = 'There was a problem processing the cropping.';
          self._showError(err);
        }
      });

      if (cb) {
        cb();
      }
    });
  };

  TransloaditCropper.prototype._showError = function(err) {
    alert(err);
  };

  TransloaditCropper.prototype._fetchParamsAndSignatureStep1 = function(cb) {
    $.post('params.php', function(response) {
      cb(response.params, response.signature);
    });
  };

  TransloaditCropper.prototype._fetchParamsAndSignatureStep2 = function(cb) {
    var data = {
      url: this.imageImportUrl,
      crop: this.cropSelection
    };
    $.post('params.php', data, function(response) {
      cb(response.params, response.signature);
    });
  };

  TransloaditCropper.prototype._unbindtransloadit = function(err) {
    this.$form.unbind('submit.transloadit');
    this.$form.find('textarea[name=transloadit]').remove();
  };

  $.fn.transloaditUpload = function() {
    var obj = (new TransloaditCropper()).init(this);
    return this.data('transloaditUploader', obj);
  };

  $(function() {
    $('.js-transloadit-upload').each(function() {
      $(this).transloaditUpload();
    });
  });
})(jQuery);
