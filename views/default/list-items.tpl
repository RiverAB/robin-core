{% extends '@robin/layout.tpl' %}

{% block title %}
    List: {{ list.name }}
{% endblock %}

{% block content %}
    
    <form method="post" action="{{ robin.route('robin.list.update-items-order', [list_key]) }}" id="list-order-form">

        <input type="hidden" name="csrf_token" value="{{ robin.csrf_token('update-list-items-order' ~ list_key) }}" />
        <input type="hidden" name="locale" value="{{ current_locale }}" />

        <div class="list-container" id="list-container">

            <div class="list-item-container">

                <div class="list-item title"></div>

                {% for field_key in list.show_in_admin %}
                <div class="list-item title">
                    {{ list.fields[field_key].name }}
                </div>
               {% endfor %}

               <div class="list-item title"></div>

            </div>


            {% for item_key, item in robin.list_items(list.key) %}
            <div class="list-item-container sortable">
            
                <div class="list-item item drag-handle">
                    <span class="fa fa-sort icon"></span>
                    <input type="hidden" name="order[]" value="{{ item_key }}" class="order-list" />
                </div>

                {% for field_key in list.show_in_admin %}
                <div class="list-item item">
                    {{ robin.list_item_content(list.key~'.'~item_key~'.'~field_key) }}
                    
                </div>
                {% endfor %}

                <div class="list-item item actions">
                    <a href="{{ robin.route('robin.list-item.edit', [list.key, item_key]) }}"><span class="fa fa-pencil icon"></span></a>
                </div>
            
            </div>
            {% endfor %}

        </div>

        <div class="page-actions">
            <ul>
            <li><a href="{{ robin.route('robin.list.add', [list.key]) }}" class="button">Add item</a></li>
            <li><a href="#" class="button confirm-btn" id="save-list-items-order-button" style="display: none;">Save new list order</a></li>
            </ul>
        </div>

    </form>

    <script>

        var sortElement = document.getElementById("list-container"),
            sortable = Sortable.create(sortElement, {
                handle: '.drag-handle',
                animation: 150,
                draggable: '.sortable',
                onEnd: function (e) {
                    sortOrderChanged();
                },
            });

        function sortOrderChanged()
        {
            $("#save-list-items-order-button").show();
        }

        $(function(){
            $("#save-list-items-order-button").on("click", function(e) {
                Ajax.sendForm($("#list-order-form"), function(r) {
                    $("#save-list-items-order-button").hide();
                });
            });
        });

    </script>

{% endblock %}