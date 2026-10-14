<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Casas Disponibles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            background-image: url('vermed.jpg');
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
            padding: 20px 0;
            min-height: 100vh;
        }
        .results-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .house-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            margin-bottom: 20px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .house-card:hover {
            transform: translateY(-5px);
        }
        .house-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .house-details {
            padding: 15px;
        }
        .price {
            font-size: 1.25rem;
            color: #2c5282;
            font-weight: bold;
        }
        .location {
            color: #666;
            font-size: 0.9rem;
        }
        .features {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }
        .view-details {
            display: block;
            width: 100%;
            padding: 8px;
            background-color: #007bff;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 10px;
        }
        .view-details:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>
<body>
    <div class="results-container">
        <div class="row">
        <?php
$host = "localhost";
$user = "root";
$pass = "";
$dbNombre = "CasaDB";

// Crear conexión
$conexion = mysqli_connect($host, $user, $pass, $dbNombre) or die('Error en la conexión');

// Obtener valores del formulario
$municipio = $_POST['municipio'] ?? '';  // Cambiar de $_GET a $_POST
$barrio = $_POST['barrio'] ?? '';        // Cambiar de $_GET a $_POST
$tamano = $_POST['tamano'] ?? '';        // Cambiar de $_GET a $_POST
$precio_min = $_POST['precio_min'] ?? ''; // Precio mínimo
$precio_max = $_POST['precio_max'] ?? ''; // Precio máximo
$habitaciones = $_POST['habitaciones'] ?? '';  // Número de habitaciones
$amueblada = $_POST['amueblada'] ?? '';  // Amueblada

// Preparar la consulta base
$sql = "SELECT * FROM Casas WHERE 1=1";
$params = array();
$types = "";

// Agregar filtros solo si los campos tienen valor
if (!empty($municipio)) {
    $sql .= " AND municipio = ?";
    $params[] = $municipio;
    $types .= "s";
}

if (!empty($barrio)) {
    $sql .= " AND barrio = ?";
    $params[] = $barrio;
    $types .= "s";
}

if (!empty($tamano)) {
    $sql .= " AND tamano = ?";
    $params[] = $tamano;
    $types .= "s";
}

if (!empty($precio_min)) {
    $sql .= " AND precio >= ?";
    $params[] = $precio_min;
    $types .= "d";
}

if (!empty($precio_max)) {
    $sql .= " AND precio <= ?";
    $params[] = $precio_max;
    $types .= "d";
}

if (!empty($habitaciones)) {
    $sql .= " AND habitaciones = ?";
    $params[] = $habitaciones;
    $types .= "i";
}

if (!empty($amueblada)) {
    $sql .= " AND amueblada = ?";
    $params[] = $amueblada;
    $types .= "s";
}

// Preparar y ejecutar la consulta
$stmt = $conexion->prepare($sql);

if (!empty($params)) {
    $bind_params = array($types);
    for($i = 0; $i < count($params); $i++) {
        $bind_params[] = &$params[$i];
    }
    call_user_func_array(array($stmt, 'bind_param'), $bind_params);
}

$stmt->execute();
$result = $stmt->get_result();

// Array para almacenar los datos de las casas para el mapa
$casas_data = array();

// Mostrar resultados y recopilar datos para el mapa
if ($result->num_rows > 0) {
    echo '<div class="row">';
    
    // Recoger los IDs de las casas encontradas
    $casas_encontradas = array();

    while($row = $result->fetch_assoc()) {
        $casas_encontradas[] = $row['id'];  // Guardamos el id de las casas encontradas
        // Almacenar datos para el mapa
        $casas_data[] = array(
            'lat' => $row['lat'],
            'lng' => $row['lng'],
            'titulo' => $row['municipio'] . ', ' . $row['barrio'],
            'precio' => $row['precio'],
            'imagen' => $row['imagen']
        );
        ?>
        <div class="col-md-4 mb-4">
            <div class="house-card">
                <img src="images/<?php echo htmlspecialchars($row['imagen']); ?>" 
                     alt="Casa en <?php echo htmlspecialchars($row['municipio']); ?>" 
                     class="house-image">
                <div class="house-details">
                    <div class="price">
                        $<?php echo number_format($row['precio'], 2); ?>
                    </div>
                    <div class="location">
                        <?php echo htmlspecialchars($row['municipio'] . ', ' . $row['barrio']); ?>
                    </div>
                    <div class="features">
                        <div><strong>Tamaño:</strong> <?php echo htmlspecialchars($row['tamano']); ?> m²</div>
                        <div><strong>Habitaciones:</strong> <?php echo htmlspecialchars($row['habitaciones']); ?></div>
                        <div><strong>Amueblada:</strong> <?php echo htmlspecialchars($row['amueblada'] == 'si' ? 'Sí' : 'No'); ?></div>
                        <div><strong>Descripción:</strong> <?php echo htmlspecialchars($row['Descasa']); ?></div>
                    </div>
                    <a href="detalles_casas.php?id=<?php echo $row['id']; ?>" 
                       class="view-details">Ver detalles</a>
                </div>
            </div>
        </div>
        <?php
    }
    echo '</div>';

    // Aquí buscamos casas similares (de acuerdo con el municipio y el barrio, por ejemplo)
    $casas_similares_sql = "SELECT * FROM Casas WHERE id NOT IN (" . implode(",", $casas_encontradas) . ") AND (municipio = ? OR barrio = ?)";

    // Si tienes más reglas para definir qué es similar, puedes agregar más condiciones aquí.

    // Preparamos la consulta de casas similares
    $stmt_similares = $conexion->prepare($casas_similares_sql);
    $stmt_similares->bind_param("ss", $municipio, $barrio);
    $stmt_similares->execute();
    $result_similares = $stmt_similares->get_result();

    // Mostrar casas similares
    if ($result_similares->num_rows > 0) {
        echo '<h3>Casas Similares</h3>';
        echo '<div class="row">';
        
        while($row_similar = $result_similares->fetch_assoc()) {
            // Almacenar datos para el mapa
            $casas_data[] = array(
                'lat' => $row_similar['lat'],
                'lng' => $row_similar['lng'],
                'titulo' => $row_similar['municipio'] . ', ' . $row_similar['barrio'],
                'precio' => $row_similar['precio'],
                'imagen' => $row_similar['imagen']
            );
            ?>
            <div class="col-md-4 mb-4">
                <div class="house-card">
                    <img src="images/<?php echo htmlspecialchars($row_similar['imagen']); ?>" 
                         alt="Casa en <?php echo htmlspecialchars($row_similar['municipio']); ?>" 
                         class="house-image">
                    <div class="house-details">
                        <div class="price">
                            $<?php echo number_format($row_similar['precio'], 2); ?>
                        </div>
                        <div class="location">
                            <?php echo htmlspecialchars($row_similar['municipio'] . ', ' . $row_similar['barrio']); ?>
                        </div>
                        <div class="features">
                            <div><strong>Tamaño:</strong> <?php echo htmlspecialchars($row_similar['tamano']); ?> m²</div>
                            <div><strong>Habitaciones:</strong> <?php echo htmlspecialchars($row_similar['habitaciones']); ?></div>
                            <div><strong>Amueblada:</strong> <?php echo htmlspecialchars($row_similar['amueblada'] == 'si' ? 'Sí' : 'No'); ?></div>
                        </div>
                        <a href="detalles_casas.php?id=<?php echo $row_similar['id']; ?>" 
                           class="view-details">Ver detalles</a>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    } else {
        echo '<div class="alert alert-info">No se encontraron casas similares</div>';
    }

    $stmt_similares->close();
    $stmt->close();
} else {
    echo '<div class="col-12 text-center">
            <div class="alert alert-info">No hay casas disponibles con los criterios seleccionados</div>
            <form action="busqueda.html" method="POST">
            <input type="submit" value="volver a buscar">
        </form>
          </div>';
}

$conexion->close();
?>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>