{% extends '@robin/layout.tpl' %}

{% block title %}
    Settings: {{ settings.name }}
{% endblock %}

{% block content %}

    <form method="post" action="{{ robin.route('robin.settings.update') }}" class="edit-settings-form" id="edit-settings-form">
    
        <input type="hidden" name="info[key]" value="{{ settings.key }}" />
        <input type="hidden" name="csrf_token" value="{{ robin.csrf_token('update-settings-content') }}" />


        {% for field, info in settings.fields %}

            <div class="field">
                
                <label>{{ info.name }}</label>
                
                {% if info.description %}
                    <div class="description"><span class="fa fa-info-circle icon"></span>{{ info.description }}</div>
                {% endif %}
                
                {%  
                    set data = { 
                        'name' : 'data['~field~']', 
                        'value': robin.setting(settings.key ~ '.' ~ field),
                        'id'   : 'field_' ~ field,
                        'field': info
                    } 
                %}

                {% include (info.field_template? info.field_template: '@robin/fields/' ~ info.type ~ '.tpl') with data only %}
            
            </div>

        {% endfor %}

    
        <div class="page-actions">
            <ul>
            <li><input type="submit" value="Save" class="button confirm-btn" id="save-settings-button" /></li>
            </ul>
        </div>

    </form>


{% endblock %}