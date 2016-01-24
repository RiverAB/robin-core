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

    <div id="wrapper" style="min-width: 1000px">
        
        <h1>Before we continue, the following issues needs to be fixed:</h1>

        <ul>
        {% for error in errors %}

            <li style="color: #c00; padding: 5px 0;">{{ error }}</li>

        {% endfor %}
        </ul>

    </div>

</body>
</html>