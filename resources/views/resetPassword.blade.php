
<!-- <form  method="post">
 @csrf
    <input type="text" name="id" value="{{ $user[0]['id'] }}">
    
    <input type="password" name="password" placeholder="Password" require>
    <input type="password" name="password_confirmation" placeholder="Confirm Password" require>
    <button type="submit">Reset Password</button>
</form>
 -->
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password</title>
    <link
      rel="stylesheet"
      href="https://necolas.github.io/normalize.css/8.0.1/normalize.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('css/resetpassword.css') }}" />
  </head>
  <body>
    <main>
      <h1 class="title">Reset Password</h1>
      <form class="formulario" id="formulario" method="post">
            <!-- @csrf
        <input type="hidden"  name="iduser" name= "iduser" value="{{ $user[0]['id'] }}"> -->
        <!-- Grupo: Contraseña -->
        <div class="formulario__grupo" id="grupo__password">
          <label for="password" class="formulario__label">Contraseña</label>
          <div class="formulario__grupo-input">
            <input
              type="password"
              name="password"
              class="formulario__input"
              name="password"
              id="password"
            />
            <i class="formulario__validacion-estado fas fa-times-circle"></i>
          </div>
            <div class="formulario__grupo-input">
            <input
              type="hidden"
              name="iduser"
              class="formulario__input"
              name="iduser"
              id="iduser"
              value="{{ $user[0]['id'] }}"
            />
            <i class="formulario__validacion-estado fas fa-times-circle"></i>
          </div>
          <p class="formulario__input-error">
            Por favor, elige una contraseña segura con al menos 8 caracteres,
            que incluya al menos un carácter especial, una letra minúscula, una
            letra mayúscula y un número.
          </p>
        </div>

        <!-- Grupo: Contraseña 2 -->
        <div class="formulario__grupo" id="grupo__password2">
          <label for="password_confirmation" class="formulario__label"
            >Repetir Contraseña</label
          >
          <div class="formulario__grupo-input">
            <input
              type="password"
              class="formulario__input"
              name="password_confirmation"
              id="password_confirmation"
            />
            <i class="formulario__validacion-estado fas fa-times-circle"></i>
          </div>
          <p class="formulario__input-error">
            Ambas contraseñas deben ser iguales.
          </p>
        </div>

        <div class="formulario__mensaje" id="formulario__mensaje">
          <p>
            <i class="fas fa-exclamation-triangle"></i> <b>Error:</b> Por favor
            rellena el formulario correctamente.
          </p>
        </div>

        <div class="formulario__grupo formulario__grupo-btn-enviar">
          <button type="submit" class="formulario__btn" id="btn-disable">Enviar</button>
          <p class="formulario__mensaje-exito" id="formulario__mensaje-exito">
            Formulario enviado exitosamente!
          </p>
        </div>
      </form>
    </main>


    <script
      src="https://kit.fontawesome.com/2c36e9b7b1.js"
      crossorigin="anonymous"
    ></script>  
      <script src="{{ asset('js/resetpassword.js') }}"></script>
  </body>
</html>
