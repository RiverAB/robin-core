var Modal = (function(){

    var $overlay, $container, $content, $title, 
        opened = false;

    function init()
    {
        $overlay   = $("#overlay");
        $container = $("#modal-container");
        $content   = $("#modal-content");
        $title     = $("#modal-title");
    
        $("#overlay").on("click", function(e){
            e.preventDefault();
            Modal.hide();
        });

        $("#modal-close").on("click", function(e){
            e.preventDefault();
            Modal.hide();
        });
    }

    function show(title, content)
    {
        if (!opened) {
            $overlay.show();
            $container.show();
            $("body").addClass('no-scroll');
            opened = true;
        }

        if (title) {
            $title.html(title);
        }
        if (content) {
            hideSpinner();
            $content.html(content);
        }
    }

    function hide()
    {
        if (opened) {
            $overlay.hide();
            $container.hide();
            $content.html('');
            showSpinner();
            $title.html('');
            $("body").removeClass('no-scroll');
            opened = false;
        }
    }

    function setTitle(title)
    {
        $title.html(title);
    }

    function setContent(content)
    {
        hideSpinner();
        $content.html(content);
    }

    function showSpinner()
    {
        $content.addClass('spinner');
    }

    function hideSpinner()
    {
        $content.removeClass('spinner');        
    }

    function isOpen()
    {
        return opened;
    }

    return {
        init: init,
        show: show,
        hide: hide,
        isOpen: isOpen,
        setTitle: setTitle,
        setContent: setContent,
        showSpinner: showSpinner,
        hideSpinner: hideSpinner
    }

})();
