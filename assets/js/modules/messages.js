var Messages = (function(){

    var $container, 
        duration = 5000;

    function init($msgContainer)
    {
        $container = $msgContainer;
    }

    function error(msg)
    {
        add('error', msg);
    }

    function success(msg)
    {
        add('success', msg);
    }

    function add(type, message)
    {
        if (Array.isArray(message)) {
            message = "<ul><li>" + message.join("</li><li>") + "</li></ul>";
        } else if (typeof message == "object") {
            return;
        }

        $closeButton = $('<a href="#" class="close-button"><span class="fa fa-close icon"></span></a>');

        $message = $('<div class="message ' + type + '">' + message + '</div>');
        $message.append($closeButton);
        
        $container.append($message);
        $message.fadeIn(200);
        
        var $item = $message;

        $closeButton.on("click", function(e) {
            e.preventDefault();
            remove($item);
        });

        $message.on("click", function(e) {
            e.preventDefault();
            remove($(this));
        });

        window.setTimeout(function(){
            remove($item);
        }, duration);
    }

    function remove($message)
    {
        $message.slideUp(400, function(){
            $message.remove();
        });
    }

    return {
        init: init,
        error: error,
        success: success
    }

})();