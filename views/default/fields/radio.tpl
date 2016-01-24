
    {% if value is null %}
    {%      set value = field.default is null? null : field.default %}
    {% endif %}


    {% if field.choices %}
    
        {% for choice in field.choices %}

            <label class="simple">
                <input type="radio" name="{{ name }}" value="{{ choice.value }}" {{ value == choice.value? 'checked ' }}/> {{ choice.label }}
            </label>

        {% endfor %}

    {% else %}

        There are no choices defined for this radio button

    {% endif %}
