<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    // Conectar a la base de datos
    $conn = new mysqli("localhost", "root", "", "CasaDB");

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO citas (nombre, email, fecha, hora) VALUES ('$nombre', '$email', '$fecha', '$hora')";
    if ($conn->query($sql) === TRUE) {
        // Mostrar mensaje de éxito con diseño
        echo '<!DOCTYPE html>
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
                    background-image: url(\'ven.jpg\');
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
                .success-message p {
                    margin: 10px 0;
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
                <h2>Registro exitoso</h2>
                <p>Nombre: ' . $nombre . '</p>
                <p>Email: ' . $email . '</p>
                <p>Fecha: ' . $fecha . '</p>
                <p>Hora: ' . $hora . '</p>
                <form action="inicio.html" method="POST">
                    <input type="submit" value="Regresar al inicio">
                </form>
            </div>
        </body>
        </html>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
}
?>
