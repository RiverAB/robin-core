
    {% if value is null %}
    {%      set value = field.default is null? 0 : field.default %}
    {% endif %}

    <label class="simple">
        <input type="hidden" name="{{ name }}" id="{{ id }}" value="{{ value }}" />
        <input type="checkbox" {{ value == 1? 'checked="checked"' }} onclick="document.getElementById('{{ id }}').value = this.checked? 1 : 0" />{{ field.label }}
    </label>
