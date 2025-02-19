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

        .divider {
            border-bottom: 0.8rem solid #787878;
            width: 100%;
        }

        .main {
            padding-inline: 52rem;
            width: 100%;
        }
    </style>
</head>
<body>
<div class="main">
    <div class="divider"></div>
    <div class="container">
        <div>
            <p style="font-size: 13rem">Sistema de Acompanhamento Legislativo – SIAL</p>
        </div>
        <div>
            <p style="font-size: 13rem">{{Carbon\Carbon::now()->format('d/m/Y')}}</p>
        </div>
    </div>
</div>
</body>
</html>



