var Ajax = (function(){

    function send(url, type, data, successCallback, noMessage)
    {
        $.ajax({ url: url, type: type, data: data, dataType: 'json',
            success: function(r) {
                if (r && r.success) {
                    if (typeof successCallback == "function") {
                        successCallback(r);
                    }
                    if (r.message) {
                        Messages.success(r.message);
                    }
                } else { 
                    if (r.errors && r.errors.length > 0) {
                        Messages.error(r.errors);
                    }
                }
            },
            error: function(xhr) {

                if (x.status == 401) {
                    Messages.error("Your session has timed out. You need to log in again.");
                } else {
                    Messages.error("An unspecified error occurred. Please try again.");    
                }
   
            }

        });
    }

    function sendForm($form, successCallback, errorCallback)
    {
        var url  = $form.attr('action'),
            type = $form.attr('method'),
            data = $form.serialize();

        $.ajax({ url: url, type: type, data: data, dataType: 'json',
            success: function(r) {
                if (r && r.success) {
                    if (r.message) {
                        Messages.success(r.message);
                    }
                    if (typeof successCallback == "function") {
                        successCallback(r);
                    }
                } else { 
                    if (r.errors && r.errors.length > 0) {
                        Messages.error(r.errors);
                    }
                    if (typeof errorCallback == "function") {
                        errorCallback(r);
                    }
                }
            },
            error: function(xhr) {

                if (xhr.status == 401) {
                    Messages.error("Your session has timed out. You need to log in again.");
                }

                if (typeof errorCallback == "function") {
                    errorCallback(r);
                } else if (xhr.status != 401) {
                    Messages.error("An unspecified error occurred. Please try again.");    
                }
            }

        });
    }

    return {
        sendForm: sendForm,
        send: send
    }

})();
