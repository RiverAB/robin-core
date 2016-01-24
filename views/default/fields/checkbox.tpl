    
    {% if field.choices %}
    
        {% for choice in field.choices %}

            <label class="simple">
                <input type="checkbox" name="{{ name }}[]" value="{{ choice.value }}" {{ in_array(choice.value, value)? 'checked ' }}/> {{ choice.label }}
            </label>

        {% endfor %}

    {% else %}

        There are no choices defined for this checkbox

    {% endif %}