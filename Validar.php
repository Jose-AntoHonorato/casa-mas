<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error de inicio de sesión</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
			background-image: url('inc.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        .error-message {
            background-color: #fff;
            border: 1px solid #000;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px #000;
            text-align: center;
        }
        .error-message h2 {
            color: red;
        }
        .error-message form {
            margin-top: 20px;
        }
        .error-message form input[type="submit"] {
            cursor: pointer;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="error-message">
        <h2>Nombre de usuario o contraseña incorrectos.</h2>
        <form action="iniciosesion.html" method="POST">
            <input type="submit" value="Regresar al inicio">
        </form>
    </div>
</body>
</html>
<?php
// Aquí es donde verificarías las credenciales del usuario.
// Por ahora, solo vamos a verificar contra los usuarios registrados en el archivo 'users.txt'.

$username = $_POST['usuario'];
$password = $_POST['pass'];

// Lee el archivo de usuarios.
$users = file('users.txt');

foreach ($users as $user) {
    // Divide cada línea por la coma para obtener el nombre de usuario y la contraseña.
    list($valid_username, $valid_password) = explode(',', trim($user));

    if ($username == $valid_username && $password == $valid_password) {
        // Si las credenciales son correctas, redirige al usuario a Menu.html
        header('Location: inicio.html');
        exit;
    }
}
?>
