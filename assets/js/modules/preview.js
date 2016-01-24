var Preview = (function(){

    function show()
    {
        Modal.show("Preview", '<script id="preview-body"></script>');
    }

    function hide()
    {
        Modal.hide();
    }

    return {
        show: show,
        hide: hide
    }

})();

