<?php
include 'db.php'; // Conexión a la base de datos

// Verificar que se pase el ID de la propiedad
if (!isset($_GET['property_id'])) {
    echo "Propiedad no especificada.";
    exit;
}

$property_id = $_GET['property_id'];

// Obtener información de la propiedad para mostrarla
$query = "SELECT * FROM properties WHERE id = :id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $property_id);
$stmt->execute();
$property = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$property) {
    echo "Propiedad no encontrada.";
    exit;
}

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_name = $_POST['client_name'] ?? '';
    $client_email = $_POST['client_email'] ?? '';
    $client_phone = $_POST['client_phone'] ?? '';

    if (!empty($client_name) && !empty($client_email) && !empty($client_phone)) {
        $query = "INSERT INTO client_contacts (property_id, client_name, client_email, client_phone) 
                  VALUES (:property_id, :client_name, :client_email, :client_phone)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':property_id', $property_id);
        $stmt->bindParam(':client_name', $client_name);
        $stmt->bindParam(':client_email', $client_email);
        $stmt->bindParam(':client_phone', $client_phone);

        if ($stmt->execute()) {
            $success_message = "Gracias por su interés. Nos pondremos en contacto pronto.";
        } else {
            $error_message = "Hubo un error al enviar su información. Por favor, inténtelo de nuevo.";
        }
    } else {
        $error_message = "Por favor, complete todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Formulario de Contacto</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Contacto por Propiedad</h1>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <!-- Información de la propiedad -->
        <div class="card mb-4">
            <img src="data:image/jpeg;base64,<?= base64_encode($property['image']) ?>" class="card-img-top" alt="Imagen de la propiedad">
            <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars(ucfirst($property['type'])) ?></h5>
                <p class="card-text">
                    <strong>Ubicación:</strong> <?= htmlspecialchars($property['location']) ?><br>
                    <strong>Ciudad:</strong> <?= htmlspecialchars($property['city']) ?><br>
                    <strong>Colonia:</strong> <?= htmlspecialchars($property['neighborhood']) ?><br>
                    <strong>Precio:</strong> $<?= number_format($property['price'], 2) ?><br>
                    <strong>Tamaño:</strong> <?= htmlspecialchars($property['size'] ?? 'N/A') ?> m²<br>
                    <strong>Contacto:</strong> <?= htmlspecialchars($property['contact']) ?>
                </p>
            </div>
        </div>

        <!-- Formulario de contacto -->
        <form method="POST">
            <div class="mb-3">
                <label for="client_name" class="form-label">Nombre</label>
                <input type="text" id="client_name" name="client_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="client_email" class="form-label">Correo Electrónico</label>
                <input type="email" id="client_email" name="client_email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="client_phone" class="form-label">Teléfono</label>
                <input type="text" id="client_phone" name="client_phone" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
            <a href="index.php" class="btn btn-secondary">Volver a la Página Principal</a>
        </form>
    </div>
</body>
</html>
