<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Conference Manager</title>
        <style media="screen">
            body
            {
                font-family: Verdana, Geneva, sans-serif;
                margin: 0;
                padding: 0;
            }

            h1.login-header
            {
                margin: 0;
                padding: 20px;
                background-color: #222;
                color: #FFF;
                text-align: center;;
                font-size: 1.4em;
                font-weight: normal;
            }

            form
            {
                text-align: center;
                width: 500px;
                margin: 40px auto;
                background-color: #FFF;
                border: 1px solid #dedede;
                border-radius: 5px;
                padding: 5px 20px;
            }

            form label
            {
                font-size: 13px;
                display: block;
                margin: 10px 0;
            }

            input
            {
                padding: 5px;
                width: 200px;
            }

            input[type='submit']
            {
                margin: 20px auto;
                display: block;
            }

            div.badauth
            {
                margin-top: 20px;
                text-align: center;
                color: red;
            }
        </style>
    </head>
    <body>
        <h1 class='login-header'>Conference Manager</h1>

        @if(session('badauth'))
            <div class="badauth">{{ session('badauth') }}</div>
        @endif

        <form class="login" action="login" method="post">

            {{ csrf_field() }}
            <label for="username">Username</label>
            <input type="text" name="username">

            <label for="password">Password</label>
            <input type="password" name="password">

            <input type="submit" name="name" value="Login">
        </form>
    </body>
</html>
