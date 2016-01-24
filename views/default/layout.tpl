<!DOCTYPE html>
<html>
<head>
    <title>Admin | Robin CMS</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width" />
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,700italic,400italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="{{ robin.admin_theme_path('css/vendor/trumbowyg/trumbowyg.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ robin.admin_theme_path('css/main.css') }}" />
    <script>
    var envvars = {
            paths: {
                locale_set: '{{ robin.route('robin.locale.set') }}',
                list_items: '{{ robin.route('robin.list', ['{LIST_KEY}']) }}',
                file_browser: '{{ robin.route('robin.files.browser') }}',
                upload:     '{{ robin.route('robin.files.upload') }}',
                files:      '{{ robin.route('robin.files.get-files') }}',
                login:      '{{ robin.route('robin.login') }}',
                file_browser_delete: '{{ robin.route('robin.files.delete') }}'
            }
        }
    </script>
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="{{ robin.admin_theme_path('js/app.js') }}"></script>
</head>
{% set body_class = current_menu? ' class="body-' ~ current_menu ~ '"' %}
<body{{ body_class | raw }}>

    <header id="header">

        <div id="brand"><strong>Robin</strong>CMS<span class="dot">.</span></div>

        <nav id="main-nav">

            <ul class="nav-list">
            <li class="title">Sections</li>
            {% for key, item in robin.sections() %}
                
                <li{{ current_menu == 'section_' ~ key? ' class="current"' }}>
                    <a href="{{ robin.route('robin.section.edit', [key]) }}"><span class="fa fa-edit icon"></span>{{ item.name }}</a>
                </li>
            
            {% endfor %}
            </ul>
            
            <ul class="nav-list">
            <li class="title">Lists</li>
            {% for key, item in robin.lists() %}
            
                <li{{ current_menu == 'list_' ~ key? ' class="current"' }}>
                    <a href="{{ robin.route('robin.list', [key]) }}"><span class="fa fa-list-ul icon"></span>{{ item.name }}</a>
                </li>
            
            {% endfor %}
            </ul>

            <ul class="nav-list">
            <li class="title">Files</li>
            <li{{ current_menu == 'files'? ' class="current"' }}>
                <a href="{{ robin.route('robin.files') }}"><span class="fa fa-folder-open icon"></span>Manage files</a>
            </li>
            </ul>

            {% if robin.modules() %}
            <ul class="nav-list">
            <li class="title">Modules</li>
            {% for item in robin.modules() %}
                
                <li{{ current_menu == 'module_' ~ item.getKey()? ' class="current"' }}>
                    <a href="{{ robin.route(item.getMenuRoute()) }}"><span class="fa fa-plug icon"></span>{{ item.getMenuLabel() }}</a>
                </li>
            
            {% endfor %}
            </ul>
            {% endif %}

            <ul class="nav-list">
            <li class="title">Settings</li>
            {% for key, item in robin.setting_groups() %}
                
                <li{{ current_menu == 'settings_' ~ key? ' class="current"' }}>
                    <a href="{{ robin.route('robin.settings.edit', [key]) }}"><span class="fa fa-cog icon"></span>{{ item.name }}</a>
                </li>
            
            {% endfor %}
            <li{{ current_menu == 'user'? ' class="current"' }}>
                <a href="{{ robin.route('robin.user.edit') }}"><span class="fa fa-user icon"></span>Your account</a>
            </li>
            <li><a href="{{ robin.route('robin.logout') }}"><span class="fa fa-sign-out icon"></span>Log out</a></li>
            </ul>

        </nav>

    </header>

    <section id="main-content">

        <div id="top-bar">

            <h1>{% block title %}{% endblock %}</h1>

            <select id="locale-switcher">
            {% for key, item in robin.locales() %}
            <option value="{{ key }}"{{ key == current_locale? ' selected="selected"' }}>{{ item.name}}</option>
            {% endfor %}
            </select>

        </div>

        <div id="inner-content">
            
            {% if subnav %}
            <div class="subnav">{% block subnav %}{% endblock %}</div>
            {% endif %}

            {% block content %}{% endblock %}

        </div>

    </section>

    <div id="messages"></div>

    <div id="overlay"></div>

    <div id="modal-container">
        <div id="modal-title-bar">
            <h2 id="modal-title"></h2>
            <a href="#" id="modal-close"><span class="fa fa-close icon"></span></a>
        </div>

        <div id="modal-content" class="spinner"></div>
    </div>

    {% if got_message %}
    <script>
        $(function(){
            {% if error %} 
                Messages.error({{ error |json_encode|raw }});
            {% endif %}
            {% if success %} 
                Messages.success({{ success |json_encode|raw }});
            {% endif %}
        });
        
    </script>
    {% endif %}


</body>
</html>