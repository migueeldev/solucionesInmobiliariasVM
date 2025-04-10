<?php
include '../includes/auth.php'; // Verifica la sesión
include '../db.php'; // Conexión a la base de datos

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$property_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Consulta para obtener los datos actuales de la propiedad
$query = "SELECT * FROM properties WHERE id = :id AND agent_id = :agent_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $property_id);
$stmt->bindParam(':agent_id', $user_id);
$stmt->execute();
$property = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$property) {
    echo "Propiedad no encontrada o no tienes permisos para editarla.";
    exit;
}

// Actualizar la propiedad
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $city = $_POST['city'];
    $neighborhood = $_POST['neighborhood'];
    $contact = $_POST['contact'];
    $image = null;

    if (!empty($_FILES['image']['tmp_name'])) {
        $image = file_get_contents($_FILES['image']['tmp_name']);
    }

    $query = "UPDATE properties 
              SET type = :type, location = :location, price = :price, size = :size, 
                  city = :city, neighborhood = :neighborhood, contact = :contact";

    if ($image) {
        $query .= ", image = :image";
    }

    $query .= " WHERE id = :id AND agent_id = :agent_id";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':size', $size);
    $stmt->bindParam(':city', $city);
    $stmt->bindParam(':neighborhood', $neighborhood);
    $stmt->bindParam(':contact', $contact);

    if ($image) {
        $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
    }

    $stmt->bindParam(':id', $property_id);
    $stmt->bindParam(':agent_id', $user_id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Error al actualizar la propiedad.";
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
    <title>Editar Propiedad</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Propiedad</h1>
        <form action="" method="POST" enctype="multipart/form-data" class="mt-4">
            <div class="mb-3">
                <label for="type" class="form-label">Tipo</label>
                <select id="type" name="type" class="form-select" required>
                    <option value="house" <?= $property['type'] === 'house' ? 'selected' : '' ?>>Casa</option>
                    <option value="land" <?= $property['type'] === 'land' ? 'selected' : '' ?>>Terreno</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Ubicación</label>
                <textarea id="location" name="location" class="form-control" required><?= htmlspecialchars($property['location']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Precio</label>
                <input type="number" id="price" name="price" class="form-control" value="<?= $property['price'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="size" class="form-label">Tamaño (m²)</label>
                <input type="number" id="size" name="size" class="form-control" value="<?= $property['size'] ?>">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">Ciudad</label>
                <input type="text" id="city" name="city" class="form-control" value="<?= htmlspecialchars($property['city']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="neighborhood" class="form-label">Colonia</label>
                <input type="text" id="neighborhood" name="neighborhood" class="form-control" value="<?= htmlspecialchars($property['neighborhood']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label">Contacto</label>
                <input type="text" id="contact" name="contact" class="form-control" value="<?= htmlspecialchars($property['contact']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Imagen (opcional)</label>
                <input type="file" id="image" name="image" class="form-control">
            </div>
            <button type="submit" class="btn btn-success">Actualizar Propiedad</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
