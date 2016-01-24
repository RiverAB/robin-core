{% extends '@robin/layout.tpl' %}

{% block title %}
    Files
{% endblock %}

{% block content %}

    
    {% include '@robin/partials/file-browser.tpl' %}

    <script>
    $(function(){
        FileBrowser.registerUploader();
    });
    </script>


{% endblock %}