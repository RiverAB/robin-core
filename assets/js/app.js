$(function(){

    /*
     * Local switcher
     * -----------------------------------------------
     */
    $("#locale-switcher").on("change", function() {
        $.ajax({
            url: envvars.paths.locale_set,
            dataType: 'json',
            type: 'post',
            data: { locale: $(this).val() },
            success: function(r) {
                if (r && r.success) {
                    location.reload();
                    return;
                }
            }
        });
    });


    /*
     * Wysiwyg setup
     * -----------------------------------------------
     */
    $('textarea.field-wysiwyg').trumbowyg({
        fullscreenable: false
    });


    /*
     * Hot keys
     * -----------------------------------------------
     */
    $("body").on("keydown", function(e) {
        if (e.keyCode == 27) { // ESC
            if (Modal.isOpen()) {
                Modal.hide();
            }
        }
    });


    /*
     * Form button actions
     * -----------------------------------------------
     */
    $("#save-content-button").on("click", function(e) {
        e.preventDefault();
        Ajax.sendForm($("#edit-content-form"));
    });

    $("#save-settings-button").on("click", function(e) {
        e.preventDefault();
        Ajax.sendForm($("#edit-settings-form"));
    });

    $("#save-user-button").on("click", function(e) {
        e.preventDefault();
        Ajax.sendForm($("#edit-user-form"));
    });

    $("#save-list-item-button").on("click", function(e) {
        e.preventDefault();
        Ajax.sendForm($("#edit-list-item-form"), function() {
            if ($("#new-notice").length == 1) {
                $("#new-notice").remove();
            }
        });
    });

    $("#delete-list-item-button").on("click", function(e) {
        e.preventDefault();
        if (!confirm("Are you sure you want to delete this item?")) {
            return;
        }
        var url = $(this).data('url');
        Ajax.send(url, 'post', $("#edit-list-item-form").serialize(), function() {
            location.href = envvars.paths.list_items.replace('{LIST_KEY}', $("#list_key").val());
        });
    });

    
    /*
     * File browser
     * -----------------------------------------------
     */
    FileBrowser.init('.field-select-file');

    $(".field-select-file-button").on("click", function(e){
        e.preventDefault();
        var id = $(this).data('id');
        FileBrowser.showFileBrowser('select', id);
    });

    $(".clear-selected-file").on("click", function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (!id) {
            return;
        }

        $("#" + id).val("");
        FileBrowser.check();
    });


    /*
     * Preview
     * -----------------------------------------------
     */
    $("#preview-button").on("click", function(e){
        e.preventDefault();

        var $form  = $(this).parents("form"),
            action = $form.attr('action'),
            target = $form.attr('target'),
            url    = $form.data('preview');

        if (!url) {
            return;
        }

        Modal.show("Preview", '<iframe id="preview-body" name="preview-body"></iframe>');

        // Change the form to the preview action and target
        $form.attr('action', url)
            .attr('target', 'preview-body')
            .submit();

        // Change the form back to the original target and action
        $form.attr('action', action).attr('target', !target? '_self' : target);

    });


    /*
     * Modules setup
     * -----------------------------------------------
     */
    Modal.init();
    Messages.init($("#messages"));

    // Init tab indent
    tabIndent.config.tab = '    ';
    tabIndent.renderAll();    

});


/**
 * HELPERS
 * ----------------------------------------------------------------------------
 **/
if( typeof Array.isArray !== 'function' ) {
    Array.isArray = function( arr ) {
        return Object.prototype.toString.call( arr ) === '[object Array]';
    };
}

RegExp.escape = function(str) {
     return str.replace(/([.?*+^$[\]\\(){}|-])/g, "\\$1");
 };

String.prototype.hashCode = function() {
  var hash = 0, i, chr, len;
  if (this.length === 0) return hash;
  for (i = 0, len = this.length; i < len; i++) {
    chr   = this.charCodeAt(i);
    hash  = ((hash << 5) - hash) + chr;
    hash |= 0; // Convert to 32bit integer
  }
  return hash;
};

function isImage(filename)
{
    if (typeof filename == "string" && filename.indexOf('.') > 0) {
        var ext = filename.split('.').pop().toLowerCase();
        return ext == 'jpg' || ext == 'gif' || ext == 'tiff';
    }
    
    return false;
}
