<?php
include '../includes/auth.php'; // Verifica la sesión
include '../db.php'; // Conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $city = $_POST['city'];
    $neighborhood = $_POST['neighborhood'];
    $contact = $_POST['contact'];
    $agent_id = $_SESSION['user_id'];
    
    // Manejo del archivo de imagen
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        $error = "Error al cargar la imagen.";
    }

    if (!isset($error)) {
        $query = "INSERT INTO properties (type, location, price, size, city, neighborhood, contact, image, agent_id) 
                  VALUES (:type, :location, :price, :size, :city, :neighborhood, :contact, :image, :agent_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':city', $city);
        $stmt->bindParam(':neighborhood', $neighborhood);
        $stmt->bindParam(':contact', $contact);
        $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
        $stmt->bindParam(':agent_id', $agent_id);

        if ($stmt->execute()) {
            $success = "Propiedad agregada exitosamente.";
        } else {
            $error = "Error al guardar la propiedad.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Agregar Propiedad</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Agregar Propiedad</h1>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="type" class="form-label">Tipo de Propiedad</label>
                <select id="type" name="type" class="form-select" required>
                    <option value="house">Casa</option>
                    <option value="land">Terreno</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Ubicación</label>
                <textarea id="location" name="location" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Precio</label>
                <input type="number" step="0.01" id="price" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="size" class="form-label">Tamaño (m²)</label>
                <input type="number" step="0.01" id="size" name="size" class="form-control">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">Ciudad</label>
                <input type="text" id="city" name="city" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="neighborhood" class="form-label">Colonia</label>
                <input type="text" id="neighborhood" name="neighborhood" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contacto</label>
                <input type="text" id="contact" name="contact" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Imagen</label>
                <input type="file" id="image" name="image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Propiedad</button>
            <a href="dashboard.php" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</body>
</html>
