<?php
include '../includes/auth.php'; // Verifica la sesión
include '../db.php'; // Conexión a la base de datos

$user_id = $_SESSION['user_id']; // ID del agente logueado

// Consulta para obtener las propiedades del agente
$query = "SELECT id, type, location, price, size, city, neighborhood, contact, created_at, image 
          FROM properties 
          WHERE agent_id = :agent_id 
          ORDER BY created_at DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':agent_id', $user_id);
$stmt->execute();
$properties = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Dashboard</title>
</head>
<body>
    <div class="container mt-5">
        <h1>Bienvenido, <?= htmlspecialchars($_SESSION['user_name']) ?></h1>
        <a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>
        <a href="add_property.php" class="btn btn-primary ms-3">Agregar Propiedad</a>

        <h2 class="mt-4">Mis Propiedades</h2>
        <?php if (count($properties) > 0): ?>
            <table class="table table-bordered table-hover mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Imagen</th>
                        <th>Tipo</th>
                        <th>Ubicación</th>
                        <th>Precio</th>
                        <th>Tamaño</th>
                        <th>Ciudad</th>
                        <th>Colonia</th>
                        <th>Contacto</th>
                        <th>Fecha de Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($properties as $index => $property): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <img src="data:image/jpeg;base64,<?= base64_encode($property['image']) ?>" 
                                     alt="Imagen de la Propiedad" class="img-thumbnail" style="width: 100px; height: auto;">
                            </td>
                            <td><?= htmlspecialchars(ucfirst($property['type'])) ?></td>
                            <td><?= htmlspecialchars($property['location']) ?></td>
                            <td>$<?= number_format($property['price'], 2) ?></td>
                            <td><?= $property['size'] ? htmlspecialchars($property['size'] . ' m²') : 'N/A' ?></td>
                            <td><?= htmlspecialchars($property['city']) ?></td>
                            <td><?= htmlspecialchars($property['neighborhood']) ?></td>
                            <td><?= htmlspecialchars($property['contact']) ?></td>
                            <td><?= htmlspecialchars($property['created_at']) ?></td>
                            <td>
                                <a href="edit_property.php?id=<?= $property['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                                <a href="delete_property.php?id=<?= $property['id'] ?>" class="btn btn-danger btn-sm" 
                                   onclick="return confirm('¿Estás seguro de eliminar esta propiedad?');">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info mt-3">No tienes propiedades registradas.</div>
        <?php endif; ?>
    </div>
</body>
</html>

