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
        <h2>casa registrada con éxito.</h2>
        <form action="inicio.html" method="POST">
            <input type="submit" value="Regresar al inicio">
        </form>
        <!-- formulario para mostrar la casa publicada -->
        <form action="mostrarcasa.php" method="GET">
            <input type="submit" class="secondary" value="Mostrar casa publicada">
        </form>
    </div>
</body>
</html>
<?php
// Configuración de la base de datos
$host = "localhost";
$user = "root";
$pass = "";
$dbNombre = "CasaDB";

// Crear conexión
$conexion = new mysqli($host, $user, $pass, $dbNombre);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Función para sanitizar las entradas
function sanitizeInput($conexion, $data) {
    return htmlspecialchars(mysqli_real_escape_string($conexion, trim($data)));
}

// Variable para mostrar el mensaje de éxito o error
$mensaje = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recoger y sanitizar datos del formulario
    $descripcion = sanitizeInput($conexion, $_POST['Descasa']);
    $precio = floatval($_POST['precventa']);
    $municipio = sanitizeInput($conexion, $_POST['municipio']);
    $barrio = sanitizeInput($conexion, $_POST['barrio']);
    $tamano = sanitizeInput($conexion, $_POST['tamano']);
    $habitaciones = sanitizeInput($conexion, $_POST['habitaciones']);
    $amueblada = sanitizeInput($conexion, $_POST['amueblada']);
    $lat = isset($_POST['latitud']) ? floatval($_POST['latitud']) : null;
    $lng = isset($_POST['longitud']) ? floatval($_POST['longitud']) : null;

    // Procesar la imagen
    $imagen = '';
    $uploadOk = true;

    if (isset($_FILES['imagecasa']) && $_FILES['imagecasa']['error'] === 0) {
        $allowed = array('jpg', 'jpeg', 'png', 'gif');
        $filename = $_FILES['imagecasa']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        // Verificar extensión
        if (!in_array($ext, $allowed)) {
            $uploadOk = false;
            $mensaje = "Solo se permiten archivos JPG, JPEG, PNG y GIF.";
        }
        
        // Verificar tamaño (5MB máximo)
        if ($_FILES['imagecasa']['size'] > 5000000) {
            $uploadOk = false;
            $mensaje = "El archivo es demasiado grande. Máximo 5MB.";
        }
        
        if ($uploadOk) {
            // Generar nombre único para la imagen
            $imagen = uniqid() . '_' . $filename;
            $upload_dir = 'images/'; // Asegúrate de que este directorio existe y tiene permisos de escritura
            
            // Crear el directorio si no existe
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Mover la imagen
            if (move_uploaded_file($_FILES['imagecasa']['tmp_name'], $upload_dir . $imagen)) {
                // Imagen subida exitosamente
            } else {
                $uploadOk = false;
                $mensaje = "Error al subir la imagen.";
            }
        }
    } else {
        $uploadOk = false;
        $mensaje = "No se seleccionó ninguna imagen o hubo un error en la subida.";
    }

    // Si la imagen se subió correctamente, insertar en la base de datos
    if ($uploadOk) {
        // Inserción de los datos en la base de datos
        $sql = "INSERT INTO Casas (Descasa, precio, imagen, municipio, barrio, tamano, habitaciones, amueblada, lat, lng) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la consulta
        if ($stmt = $conexion->prepare($sql)) {
            // Enlazar parámetros
            $stmt->bind_param("sdssssssdd", $descripcion, $precio, $imagen, $municipio, $barrio, $tamano, $habitaciones, $amueblada, $lat, $lng);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Si la inserción es exitosa, mostrar el mensaje de éxito
                $mensaje = "Casa registrada con éxito.";
            } else {
                $mensaje = "Error al guardar los datos: " . $stmt->error;
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            $mensaje = "Error en la preparación de la consulta: " . $conexion->error;
        }
    }
}

// Cerrar la conexión
$conexion->close();
?>


