<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbNombre = "CasaDB";

// Crear conexión
$conexion = mysqli_connect($host, $user, $pass, $dbNombre) or die('Error en la conexión');

// Obtener el ID de la casa de la URL
$id_casa = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Preparar la consulta para obtener los detalles de la casa específica
$sql = "SELECT * FROM Casas WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_casa);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $casa = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Casa - <?php echo htmlspecialchars($casa['municipio']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        .property-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
        }
        .details-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 20px;
        }
        .map-container {
            height: 300px;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
        }
        .price {
            font-size: 24px;
            color: #2c3e50;
            font-weight: bold;
        }
        .location {
            color: #7f8c8d;
            font-size: 18px;
            margin-bottom: 15px;
        }
        .description {
            line-height: 1.6;
            color: #34495e;
            margin: 20px 0;
        }
        .schedule-btn {
            background-color: #3498db;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .schedule-btn:hover {
            background-color: #2980b9;
        }
        .property-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .detail-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <img src="images/<?php echo htmlspecialchars($casa['imagen']); ?>" 
                     alt="Casa en <?php echo htmlspecialchars($casa['municipio']); ?>" 
                     class="property-image">
            </div>
            <div class="col-lg-4">
                <div class="details-container">
                    <div class="price">
                        $<?php echo number_format($casa['precio'], 2); ?>
                    </div>
                    <div class="location">
                        <?php echo htmlspecialchars($casa['municipio'] . ', ' . $casa['barrio']); ?>
                    </div>
                    <div class="property-details">
                        <div class="detail-item">
                            <strong>Tamaño</strong>
                            <div><?php echo htmlspecialchars($casa['tamano']); ?></div>
                        </div>
                        <div class="detail-item">
                            <strong>Fecha de Publicación</strong>
                            <div><?php echo date('d/m/Y', strtotime($casa['fecha_publicacion'])); ?></div>
                        </div>
                    </div>
                    <button class="schedule-btn w-100" onclick="programarCita()">
                        Programar Cita
                    </button>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="details-container">
                    <h3>Descripción</h3>
                    <div class="description">
                        <?php echo nl2br(htmlspecialchars($casa['Descasa'])); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="map-container" id="map"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Inicializar el mapa
        var map = L.map('map').setView([<?php echo $casa['lat']; ?>, <?php echo $casa['lng']; ?>], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        
        // Agregar marcador
        L.marker([<?php echo $casa['lat']; ?>, <?php echo $casa['lng']; ?>])
         .addTo(map)
         .bindPopup('<strong><?php echo htmlspecialchars($casa['municipio'] . ', ' . $casa['barrio']); ?></strong>');

        // Función para programar cita
        function programarCita() {
            window.location.href = 'cita.html';
        }
    </script>
</body>
</html>
<?php
} else {
    echo '<div class="alert alert-danger">Casa no encontrada</div>';
}

$stmt->close();
$conexion->close();
?>