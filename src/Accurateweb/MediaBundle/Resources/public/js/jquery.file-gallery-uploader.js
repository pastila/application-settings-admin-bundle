/* 
 * @author Denis N. Ragozin <ragozin at artsofte.ru>
 * @version SVN: $Id$
 * @revision SVN: $Revision$
 */
(function($){
  var defaults = {
    uploadUrl: null,
    onUploadComplete: function() {}
  }
  
  function FileGalleryUploader(element, options){
    this.options = $.extend({}, defaults, options);
    this.element = $(element);
  
    this.fn = FileGalleryUploader.fn;
    
    this.initialize();
  
  }
  
  FileGalleryUploader.fn = FileGalleryUploader.prototype
  
  $.extend(FileGalleryUploader.prototype, {
    initialize: function(){
      var self = this;
      
      this.element.dialog({
        autoOpen: false,
        resizable: false,
        width: 810,
        title: 'Добавление файлов',
        open: function(){
          if (self.capabilities == 'html5')
            self.element.parent().find('.ui-dialog-buttonset button:first').hide();          
          self.element.html('');
          self.runtime.embed(self.element, self.options.uploadUrl + "?type="+self.capabilities);
        },
        buttons: {
          'Загрузить файлы': function(){
            
            $(this).parent().find('.ui-dialog-buttonset button:first').hide();
            self.runtime.submit(function(){               
                self.element.dialog('close');
                self.options.onUploadComplete();
            });
          },
          'Закрыть': function(){
            self.element.dialog('close');
          }
        }
      });
      
      this.capabilities = this._getBrowserUploadCapabilities();
      switch (this.capabilities){
        case 'html5': {            
            this.runtime = new HTML5UploaderRuntime({
              onSelectFile: function(){
                self.element.parent().find('.ui-dialog-buttonset button:first').show();
              },
              onRemoveFile: function(){
                if (self.runtime.getQueueLength() == 0){
                  self.element.parent().find('.ui-dialog-buttonset button:first').hide();                  
                }
              }
            }); 
            break;
        }
        case 'legacy': this.runtime = new HTMLUploaderRuntime(); break;         
      }
      
      //this.runtime.embed(this.element);
    },
    _getBrowserUploadCapabilities: function(){
      if ($.support.fileSelecting && $.support.fileReading && $.support.fileSending)
        return 'html5';
      
      return 'legacy';
    },
    open: function(){
      this.element.dialog('open')
    }
  });
  
  $.fn.fileGalleryUploadDialog = function(method){
    var returnValue = this;
    var args = arguments;

    $.each(this, function(){
      var inst = $.data(this, 'fileGalleryUploadDialog');
      if ((typeof method === 'object' || ! method ) && !inst){
        $.data(this, 'fileGalleryUploadDialog', new FileGalleryUploader(this, method))
      } else if (typeof method === 'string' && method[0] != '_' && inst && inst.fn[method] ) {
        returnValue = inst.fn[method].apply(inst, Array.prototype.slice.call( args, 1 ))
        return false;
      } else {
        $.error( 'Method ' +  method + ' does not exist on jQuery.element' );
      }
    });
    
    return returnValue;
  }
  
})(jQuery)

