<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .container {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            font-family: Roboto, "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
        }

        .logo {
            width: 60rem;
        }

        .main {
            padding-inline: 52rem;
            width: 100%;
        }

        .divider {
            border-bottom: 0.8rem solid #787878;
            width: 100%;
            position: relative;
            top: -12rem
        }
    </style>
</head>
<body>
<div class="main">
    <div class="container">
        <div>
            <p style="font-size: 17rem;">Secretaria Especial de Assuntos Parlamentares – SEPAR</p>
        </div>
        <div>
            <img alt="logo sri" class="logo"
                 src="{{'data:image/png;base64,'.base64_encode(file_get_contents(public_path('src/img/sri.png')))}}">
        </div>
    </div>
    <div class="divider"></div>
</div>
</body>
</html>


