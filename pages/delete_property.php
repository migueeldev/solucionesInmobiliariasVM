<?php
include '../includes/auth.php'; // Verifica la sesión
include '../db.php'; // Conexión a la base de datos

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$property_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Verificar que la propiedad pertenece al agente logueado
$query = "SELECT id FROM properties WHERE id = :id AND agent_id = :agent_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $property_id, PDO::PARAM_INT);
$stmt->bindParam(':agent_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    echo "Propiedad no encontrada o no tienes permisos para eliminarla.";
    exit;
}

// Eliminar los registros relacionados en client_contacts
$query = "DELETE FROM client_contacts WHERE property_id = :property_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':property_id', $property_id, PDO::PARAM_INT);

if (!$stmt->execute()) {
    echo "Error al eliminar los datos de contacto relacionados.";
    exit;
}

// Eliminar la propiedad
$query = "DELETE FROM properties WHERE id = :id AND agent_id = :agent_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id', $property_id, PDO::PARAM_INT);
$stmt->bindParam(':agent_id', $user_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    header("Location: dashboard.php?success=deleted");
    exit;
} else {
    echo "Error al eliminar la propiedad.";
}
?>
