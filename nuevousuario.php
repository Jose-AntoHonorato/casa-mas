<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro exitoso</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background-image: url('ven.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
        .success-message {
            background-color: #fff;
            border: 1px solid #000;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px #000;
            text-align: center;
        }
        .success-message h2 {
            color: green;
        }
        .success-message form {
            margin-top: 20px;
        }
        .success-message form input[type="submit"] {
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
    <div class="success-message">
        <h2>Usuario registrado con éxito.</h2>
        <form action="iniciosesion.html" method="POST">
            <input type="submit" value="Regresar al inicio">
        </form>
    </div>
</body>
</html>
<?php
$new_username = $_POST['newUser'];
$new_password = $_POST['newPass'];

file_put_contents('users.txt', "$new_username,$new_password\n", FILE_APPEND);
?>
