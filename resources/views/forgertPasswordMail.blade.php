<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{$data['title']}}</title>
</head>
<body> 
  <p>{{$data['body']}}</p>
  <a href="{{$data['url']}}">Click Here</a>
  <p> thank You</p>
</body>
</html> -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$data['title']}}</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
      }

      .container {
        max-width: 600px;
        margin: 0 auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
      }

      h1 {
        text-align: center;
      }

      p {
        line-height: 1.5;
      }
      .escudo {
        display: flex;
        justify-content: space-between;
      }
      .escudo img {
        text-align: center;
        width: 20%;
      }
    </style>
  </head>
  <body>
    <div class="container">

      <div class="escudo">
         <h1>{{$data['title']}}</h1>
        <img src="https://unsm.edu.pe/wp-content/uploads/2022/03/escudo_unsm_2021_colores_PNG-370x426.png" alt="Logo" />
      </div>
      <p>
        Recibimos una solicitud de recuperación de contraseña para tu cuenta.
      </p>
      <p>Si no realizaste esta solicitud, puedes ignorar este correo.</p>
      <p>Para restablecer tu contraseña, haz clic en el siguiente enlace:</p>
      <p>
        <a href="{{$data['url']}}">Restablecer Contraseña</a>
      </p>
      <p>Si tienes alguna pregunta o necesitas ayuda, contáctanos.</p>
      <p>¡Gracias!</p>
    </div>
  </body>
</html>
