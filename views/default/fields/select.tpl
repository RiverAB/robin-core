
    <select name="data[{{ field }}]">
    {% for choice in get_content(key ~ '._fields.' ~ field ~ '.choices', []) %}

        <option value="{{ choice.value }}"{{ value == choice.value? 'selected ' }}> {{ choice.label }}</option>

    {% endfor %}
    </select>
