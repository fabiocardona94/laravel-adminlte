<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restablecer Contraseña</title>
</head>
<body>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            margin-top: 30px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .text-center {
            text-align: center;
        }
        .h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        a {
            color: #1a73e8;
            text-decoration: none;
        }
    </style>
    <div class="container">
        <div class="text-center">
            <p class="h2">Restablecer Contraseña</p>
        </div>
        <p>
            Hola
            <strong>
                {{ $data['name_user'] }}
            </strong>,
        </p>
        <p>
            Hemos recibido una solicitud para restablecer la contraseña de tu cuenta debido a un <strong>{{$data['tipo_solicitud']}}</strong>. 
            Para ayudarte a recuperar el acceso a tu cuenta, hemos generado una contraseña temporal para ti.
        </p>
        <p>
            <strong>Contraseña Temporal:</strong>{{ $data['password_tmp'] }}
        </p>
        <p>
            Por favor, utiliza esta contraseña para iniciar sesión y asegúrate de cambiarla a una nueva contraseña segura lo antes posible.
        </p>
        <p>
            Para más información, visítanos en <a target="_blank" href="http://127.0.0.1:8000/">nuestro sitio web</a>
        </p>
    </div>
</body>
</html>
