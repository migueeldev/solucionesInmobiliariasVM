<?php
include 'db.php'; // Conexión a la base de datos

// Variables para los filtros
$city_filter = $_GET['city'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';

// Construcción de la consulta con filtros dinámicos
$query = "SELECT * FROM properties WHERE 1=1";

if (!empty($city_filter)) {
    $query .= " AND city LIKE :city";
}

if (!empty($min_price)) {
    $query .= " AND price >= :min_price";
}

if (!empty($max_price)) {
    $query .= " AND price <= :max_price";
}

$stmt = $pdo->prepare($query);

if (!empty($city_filter)) {
    $city_filter = "%$city_filter%";
    $stmt->bindParam(':city', $city_filter);
}

if (!empty($min_price)) {
    $stmt->bindParam(':min_price', $min_price);
}

if (!empty($max_price)) {
    $stmt->bindParam(':max_price', $max_price);
}

$stmt->execute();
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Propiedades en Venta</title>
    <!-- Iconos-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Footer icono */
        .social-icons a {
            text-decoration: none;
            color: #ffffff;
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: #f1c40f; /* Cambia el color al pasar el ratón */
        }

        .social-icons i {
            margin: 0 10px;
        }

    </style>
</head>
<body>
    <!-- Header -->
    <header class="bg-white shadow-sm py-3 mb-5">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="img/victores-logo.png" alt="Logo de la empresa" style="height: 70px; margin-right: 30px;">
            <h1 class="h5 text-primary m-0">VM Soluciones Inmobiliarias</h1>
        </div>
        <div>
            <a href="pages/login.php" class="btn btn-outline-primary me-2">Iniciar Sesión</a>
            <a href="pages/register.php" class="btn btn-primary">Registro</a>
        </div>
    </div>
</header>


    <div class="container mt-5">
        <h1 class="text-center mb-4">Propiedades en Venta</h1>

        <!-- Filtros -->
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="city" class="form-label">Ciudad</label>
                <input type="text" id="city" name="city" class="form-control" placeholder="Ingrese ciudad" value="<?= htmlspecialchars($city_filter) ?>">
            </div>
            <div class="col-md-3">
                <label for="min_price" class="form-label">Precio Mínimo</label>
                <input type="number" id="min_price" name="min_price" class="form-control" placeholder="0" value="<?= htmlspecialchars($min_price) ?>">
            </div>
            <div class="col-md-3">
                <label for="max_price" class="form-label">Precio Máximo</label>
                <input type="number" id="max_price" name="max_price" class="form-control" placeholder="0" value="<?= htmlspecialchars($max_price) ?>">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filtrar</button>
            </div>
        </form>

        <!-- Listado de Propiedades -->
        <div class="row">
            <?php if (count($properties) > 0): ?>
                <?php foreach ($properties as $property): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="data:image/jpeg;base64,<?= base64_encode($property['image']) ?>" class="card-img-top" alt="Imagen de la propiedad">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars(ucfirst($property['type'])) ?></h5>
                                <p class="card-text">
                                <a href="<?= htmlspecialchars($property['location']) ?>" target="_blank" class="text-decoration-none">Ubicación</a><br>
                                    <strong>Ciudad:</strong> <?= htmlspecialchars($property['city']) ?><br>
                                    <strong>Colonia:</strong> <?= htmlspecialchars($property['neighborhood']) ?><br>
                                    <strong>Precio:</strong> $<?= number_format($property['price'], 2) ?><br>
                                    <strong>Tamaño:</strong> <?= htmlspecialchars($property['size'] ?? 'N/A') ?> m²<br>
                                    <strong>Contacto:</strong> <?= htmlspecialchars($property['contact']) ?>
                                </p>
                                <a href="contact_form.php?property_id=<?= $property['id'] ?>" class="btn btn-primary">Contactar</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">No se encontraron propiedades que coincidan con los filtros.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-primary text-white text-center py-4 mt-5">
    <div class="container">
        <p class="mb-2">&copy; <?= date('Y') ?> VM Soluciones Inmobiliarias. Todos los derechos reservados.</p>
        <div class="social-icons">
            <a href="https://github.com/migueeldev" target="_blank" class="text-white me-3"><i class="fab fa-github fa-2x"></i></a>
        </div>
    </div>
</footer>
</body>
</html>
