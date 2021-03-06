SirTrevor.Blocks.Feliximage = (function() {
  return SirTrevor.Block.extend({

    type: "feliximage",

    title: "Picture",

    icon_name: 'image',

    editorHTML: function() {
      var blockId = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5);

      var template = _.template([
          '<input name="image" class="st-input-string js-id-input st-required" id="'+blockId+'" type="hidden" />',
          '<ul id="tabs-'+blockId+'" class="nav nav-pills" role="tablist">',
          '<li role="presentation" class="active"><a href="#'+blockId+'-current" id="'+blockId+'-current-tab" onClick="$(this).tab(\'show\'); return false" role="tab" data-toggle="pill" aria-controls="'+blockId+'-current" aria-expanded="true">Current Image</a></li>',
          '<li role="presentation"><a href="#'+blockId+'-new" id="'+blockId+'-new-tab" onClick="$(this).tab(\'show\'); return false" role="tab" data-toggle="pill" aria-controls="'+blockId+'-new" aria-expanded="true">Upload</a></li>',
          '<li role="presentation"><a href="#'+blockId+'-pick" id="'+blockId+'-pick-tab" onClick="$(this).tab(\'show\'); return false" role="tab" data-toggle="pill" aria-controls="'+blockId+'-pick" aria-expanded="true">Pick Previously Uploaded Picture</a></li>',
          '</ul>',
          '<br><div id="tabs-'+blockId+'-content" class="tab-content">',
          '<div role="tabpanel" class="tab-pane fade in active" id="'+blockId+'-current" aria-labelledby="'+blockId+'-current-tab">',
          '<i>No image selected.</i>',
          '</div>',
          '<div role="tabpanel" class="tab-pane fade in" id="'+blockId+'-new" aria-labelledby="'+blockId+'-new-tab">',
          '<div id="dropzone-'+blockId+'" class="dropzone"></div>',
          '<script>',
          '  $(document).ready( function() {',
          '    Dropzone.autoDiscover = false;',
          '    $("#dropzone-'+blockId+'").dropzone({',
          '      url: getImageUploadEndpoint(),',
          '      uploadMultiple: false,',
          '      maxFiles: 1,',
          '      addRemoveLinks: true,',
          '      init: function() {',
          '        this.on("maxfilesexceeded", function(file) { this.removeFile(file); });',
          '        this.on("success", function(file, responseText) {',
          '          imageForm("'+blockId+'", responseText, "1", true);',
          '          this.removeFile(file);',
          '        });',
          '      }',
          '    });',
          '  });',
          '</script>',
          '</div>',
          '<div role="tabpanel" class="tab-pane fade in" id="'+blockId+'-pick" aria-labelledby="'+blockId+'-pick-tab">',
          '     <label for="'+blockId+'-pick">Search for a picture by filename or description</label>',
          '      <div class="input-group select2-bootstrap-append">',
          '        <select class="form-control select2" id="'+blockId+'-picker" style="width: 100%"></select>',
          '        <span class="input-group-btn">',
          '          <button class="btn btn-primary" onClick="pickLookupPic(\''+blockId+'\', true); return false;">',
          '            <span class="glyphicon glyphicon-ok"></span> Select this image',
          '          </button>',
          '        </span>',
          '      </div>',
          '    </div>',
          '    <script>',
          '      $(document).ready( function() {',
          '        $("#'+blockId+'-picker").select2({',
          '          theme: "bootstrap",',
          '          ajax: {',
          '            url: getAjaxEndpoint(),',
          '            dataType: "json",',
          '            delay: 250,',
          '            method: "POST",',
          '            data: function (params) {',
          '              return {',
          '                q: "imageLookup",',
          '                query: params.term, // search term',
          '                "00csrf": $("#csrf-key").attr("data-csrf")',
          '              };',
          '            },',
          '            error: function(data) {',
          '               if(data.responseJSON) {',
          '                 alert(data.responseJSON.message);',
          '               } else {',
          '                 alert(data.responseText);',
          '               }',
          '            },',
          '            processResults: function (data, page) {',
          '              return {',
          '                results: data',
          '              };',
          '            },',
          '            cache: false',
          '          },',
          '          templateResult: formatImagePicker,',
          '          minimumInputLength: 0',
          '        });',
          '      });',
          '    </script>'
      ].join("\n"));
      return template(this);
    },

    loadData: function(data){
      blockId = this.$('.js-id-input').attr('id');
      imageForm(blockId, data.image, "1", data);
    }
  });
}) ();