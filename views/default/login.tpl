<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="{{ robin.admin_theme_path('css/login.css') }}" />
</head>
<body>

    <div id="wrapper">
        
        <h1><span class="fa fa-key icon"></span>Login required</h1>

        {% if error %}<div class="message error">{{ error }}</div>{% endif %}
        {% if success %}<div class="message success">{{ success }}</div>{% endif %}

        <form method="post" action="{{ robin.route('robin.login.do') }}">
    
            <input type="hidden" name="csrf_token" value="{{ robin.csrf_token('login') }}" />

            <label>
                <span class="fa fa-user icon"></span>
                <input type="text" name="username" id="username" />
            </label>

            <label>
                <span class="fa fa-key icon"></span>
                <input type="password" name="password" />
            </label>

            <button id="login-button"><span class="fa fa-login"></span>Log in</button>

        </form>

    </div>

    <script>document.getElementById("username").focus();</script>

</body>
</html>