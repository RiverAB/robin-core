{% extends '@robin/layout.tpl' %}

{% block title %}
    Edit list item: {{ list.name }}
{% endblock %}

{% block content %}

    <form method="post" action="{{ robin.route('robin.list-item.update', [list.key, item_key]) }}" class="edit-list-item-form" id="edit-list-item-form">
    
        <input type="hidden" name="list[key]" value="{{ list.key }}" id="list_key" />
        <input type="hidden" name="item_key" value="{{ item_key }}" />
        <input type="hidden" name="locale" value="{{ current_locale }}" />
        <input type="hidden" name="csrf_token" value="{{ robin.csrf_token('edit-list-item' ~ item_key) }}" />

        {% if is_new %}
            <div class="notice" id="new-notice">This item does not yet exist in this list. To add it, fill out the form and click "Save".</div>
        {% endif %}


        {% for field, info in list.fields %}

            <div class="field">
                
                <label>{{ info.name }}</label>
                
                {% if info.description %}
                    <div class="description"><span class="fa fa-info-circle icon"></span>{{ info.description | raw }}</div>
                {% endif %}
                
                {%  
                    set data = { 
                        'name' : 'data['~field~']', 
                        'value': robin.list_item_content(list.key ~ '.' ~ item_key ~ '.' ~ field),
                        'id'   : 'field_' ~ field,
                        'field': info
                    } 
                %}
                
                {% include (info.field_template? info.field_template: '@robin/fields/' ~ info.type ~ '.tpl') with data only %}

            </div>

        {% endfor %}

    
        <div class="page-actions">
            <ul>
            <li><input type="submit" value="Save" class="button confirm-btn" id="save-list-item-button" /></li>
            <li class="alt"><a href="¨#" class="button danger-btn" data-url="{{ robin.route('robin.list-item.delete', [list.key, item_key]) }}" id="delete-list-item-button">Delete this item</a></li>
            </ul>
        </div>

    </form>


{% endblock %}