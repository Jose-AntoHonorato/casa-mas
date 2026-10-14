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

// Consulta para obtener la última casa registrada
$sql = "SELECT * FROM Casas ORDER BY fecha_publicacion DESC LIMIT 1"; // Esto obtiene la última casa registrada
$resultado = $conexion->query($sql);

// Verificar si hay resultados
if ($resultado && $resultado->num_rows > 0) {
    // Mostrar detalles de la casa
    $casa = $resultado->fetch_assoc();
    echo "<h2>Casa publicada:</h2>";
    echo "<p><strong>Descripción:</strong> " . htmlspecialchars($casa['Descasa']) . "</p>";
    echo "<p><strong>Precio:</strong> $" . number_format($casa['precio'], 2) . "</p>";
    echo "<p><strong>Municipio:</strong> " . htmlspecialchars($casa['municipio']) . "</p>";
    echo "<p><strong>Barrio:</strong> " . htmlspecialchars($casa['barrio']) . "</p>";
    echo "<p><strong>Tamaño:</strong> " . htmlspecialchars($casa['tamano']) . " m²</p>";

    // Mostrar la imagen si existe
    if (!empty($casa['imagen'])) {
        echo "<p><img src='images/" . htmlspecialchars($casa['imagen']) . "' alt='Imagen de la casa' style='width:100%; max-width:300px;'></p>";
    } else {
        echo "<p>No se ha proporcionado imagen para esta casa.</p>";
    }

    // Mostrar la ubicación si existe
    if (!empty($casa['ubicacion'])) {
        echo "<p><strong>Ubicación:</strong> " . htmlspecialchars($casa['ubicacion']) . "</p>";
    }

    // Mostrar las coordenadas si existen
    if (!empty($casa['lat']) && !empty($casa['lng'])) {
        echo "<p><strong>Coordenadas:</strong> Lat: " . $casa['lat'] . ", Lng: " . $casa['lng'] . "</p>";
    }

} else {
    echo "<p>No se encontró ninguna casa publicada.</p>";
}

// Cerrar la conexión
$conexion->close();
?>

