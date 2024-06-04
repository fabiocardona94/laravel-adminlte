<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Restablecer Contraseña</title>
    <link rel="stylesheet" href="/dist/css/email_design.css" />
</head>
<body>
    <div class="container">
        <div class="text-center">
            <p class="h2">Restablecer Contraseña</p>
        </div>
        <p>
            Hola
            <strong>
                {{ Auth::user()->name }}
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
            Para más información, visítanos en <a target="_blank" href="https://www.coytex.com.co/">nuestro sitio web</a>
        </p>
    </div>
</body>
</html>
