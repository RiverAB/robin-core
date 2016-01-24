{% extends '@robin/layout.tpl' %}

{% block title %}
    Your account
{% endblock %}

{% block content %}

    <form method="post" action="{{ robin.route('robin.user.update') }}" class="edit-user-form" id="edit-user-form">
    
        <input type="hidden" name="csrf_token" value="{{ robin.csrf_token('user_update') }}" />


        <div class="field-group">

            <div class="field col1-2">

                <label>Username</label>
                <input type="text" name="user[username]" value="{{ user.username }}" />
            
            </div>

            <div class="field col1-2">

                <label>Email address</label>
                <input type="text" name="user[email]" value="{{ user.email }}" />
            
            </div>

        </div>

        <p class="info">If you don't want to change your password, just leave the following fields empty.</p>

        <div class="field-group">

            <div class="field col1-2">

                <label>Password</label>
                <input type="password" name="user[password]" value="" />
            
            </div>

            <div class="field col1-2">

                <label>Confirm password</label>
                <input type="password" name="user[confirm_password]" value="" />
            
            </div>
        
        </div>


        <div class="page-actions">
            <ul>
            <li><input type="submit" value="Save" class="button confirm-btn" id="save-user-button" /></li>
            </ul>
        </div>


    </form>


{% endblock %}