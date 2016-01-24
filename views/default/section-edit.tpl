{% extends '@robin/layout.tpl' %}

{% block title %}
    Section: {{ section.name }}
{% endblock %}

{% block content %}

    <form method="post" action="{{ robin.route('robin.section.update') }}" class="edit-content-form" id="edit-content-form" data-preview="{{ robin.route('robin.section.preview') }}">
    
        <input type="hidden" name="info[key]" value="{{ section.key }}" />
        <input type="hidden" name="locale" value="{{ current_locale }}" />
        <input type="hidden" name="csrf_token" value="{{ robin.csrf_token('update-section-content') }}" />


        {% for field, info in section.fields %}

            <div class="field">
                
                <label>{{ info.name }}</label>
                
                {% if info.description %}
                    <div class="description"><span class="fa fa-info-circle icon"></span>{{ info.description }}</div>
                {% endif %}
                
                {%  
                    set data = { 
                        'name' : 'data['~field~']', 
                        'value': robin.content(section.key ~ '.' ~ field),
                        'id'   : 'field_' ~ field,
                        'field': info
                    } 
                %}

                {% include (info.field_template? info.field_template: '@robin/fields/' ~ info.type ~ '.tpl') with data only %}
            
            </div>

        {% endfor %}

    
        <div class="page-actions">
            <ul>
            <li><input type="submit" value="Save" class="button confirm-btn" id="save-content-button" /></li>
            <li><input type="button" value="Preview" class="button" id="preview-button" /></li>
            </ul>
        </div>

    </form>

{% endblock %}