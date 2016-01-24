var FileBrowser = (function(){

    var fileContainer,
        filesLoaded = false,
        modalStatus = 0,
        filesInQueue = 0,
        currentFile  = 0,
        uploader;

    function init(container)
    {
        fileContainer = container;
        showPreviews();

        if ($(fileContainer).length > 0) {
            check();
            $(".selected-file", fileContainer).on("change", function(){
                check();
            });
        }

        $("body").on("click", ".file-item-container .select-button", function(e) {
            e.preventDefault();
            var uri = $(this).data('uri'),
                id  = $(this).data('id');

            if (!uri || !id) {
                return;
            }

            $("#" + id).val(uri);
            check();
            Modal.hide();
        });

        $("body").on("click", ".file-item-container .delete-button", function(e) {
            e.preventDefault();
            var data = {
                file: $(this).data('file'),
                csrf_token: envvars.delete_csrf_token
            };

            if (!data.file) {
                return;
            }

            Ajax.send(envvars.paths.file_browser_delete, "post", data, function(){
                loadFiles();
            });
        });

    }

    function registerUploader($container)
    {
        if (uploader) {
            return;
        }

        var $container;

        uploader = new ss.SimpleUpload({
            button: 'file-browse-button',
            url: envvars.paths.upload,
            responseType: 'json',
            name: 'uploadfile',
            multiple: true,
            queue: true,
            maxUploads: 1,
            hoverClass: 'ui-state-hover',
            focusClass: 'ui-state-focus',
            disabledClass: 'ui-state-disabled',   
            onSubmit: function(filename, extension) 
            {
                var $wrapper = $("#progress-template .uploading-wrapper").clone(),
                    $bar      = $(".bar", $wrapper),
                    $info     = $(".info", $wrapper);

                currentFile++;
                var info = '';
                if (filesInQueue == 1) {
                    info = '<strong>Uploading: </strong>' + filename + "</strong>";
                } else {
                    info = '<strong>Uploading ' + currentFile + ' of ' + filesInQueue + ': </strong>' + filename + "</strong>";
                }
                $info.html(info);
                $("#progress-box").append($wrapper);

                this.setProgressBar($bar);
                this.setProgressContainer($wrapper);
            },
            onComplete:   function(filename, r) 
            {
                if (!r) {
                    Messages.error("An unspecified error occurred uploading " + filename);
                    return false;
                }

                if (!r.success) {
                    Messages.error(r.errors);
                    return;
                }

                Messages.success('File ' + filename + ' is uploaded');
                if (currentFile == filesInQueue) {
                    currentFile  = 0;
                    filesInQueue = 0;
                }

                loadFiles();
            },
            onError: function(filename, status, statusText) 
            {
                Messages.error("An unspecified error occurred uploading " + filename);
                console.log(status, statusText);
            },
            onChange: function(filename, extension, uploadBtn, fileSize, file) 
            {
                filesInQueue++;
            }
        });    
    }

    function showFileBrowser(status, id)
    {
        var data = {
            'status': status? status : '',
            'id': id? id : ''
        };
        
        Modal.show("File browser");

        Ajax.send(envvars.paths.file_browser, "get", data,  function(r) 
        {
            if (!r.data) {
                return;
            }

            Modal.setContent('<div id="file-browser-container" class="file-browser-modal"></div>');
            var $container = $("#file-browser-container");
            $container.append(r.data);
            registerUploader($container);

        }, true);
    }

    function check()
    {
        $(fileContainer).each(function(){
            var $field   = $(this),
                filename = $(".selected-file", $field).val(),
                $preview = $(".selected-file-preview", $field);

            if (isImage(filename)) {
                $preview.html('<img src="' + filename + '" />');
            } else {
                $preview.html('');
            }
        });
    }

    function loadFiles(callback)
    {
        $.ajax({
            url: envvars.paths.files,
            type: 'get',
            dataType: 'json',
            success: function(r) {
                if (r && r.data) {
                    $("#file-list .file-item-container.item").remove();
                    for(var i in r.data) {
                        addItem(r.data[i]);
                    }
    
                    if (typeof callback == "function") {
                        callback();
                    }

                    if ($("#file-list").hasClass("preview-view")) {
                        showPreviews();
                    }

                    return;
                }
            },
            error: function() {}
        });
    }

    function showPreviews()
    {
        $(".file-item-container.image .preview img").each(function(){
            var file = $(this).data("file");
            $(this).attr('src', file);
        });
    }

    function addItem(data)
    {
        var template = $("#file-item-template").html();
        template = placeholders(template, data);
        $("#file-list").append(template);
    }

    function placeholders(tpl, data)
    {
        data.icon = data.type == 'executable' || data.type == 'misc'
                ? 'file-o'
                : 'file-' + data.type + '-o';

        for(var i in data) {
            tpl = tpl.replace(new RegExp(RegExp.escape("[["+i+"]]"), "g"), data[i]);
        }

        return tpl;
    }

    return {
        init: init,
        check: check,
        showFileBrowser: showFileBrowser,
        registerUploader: registerUploader,
        loadFiles: loadFiles
    }

})();
