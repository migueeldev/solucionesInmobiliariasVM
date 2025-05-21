<?php
/* Aqui se configura la conexion, ya sea de forma local o en un hosting
manera de hacerlo con hostinger:
$host = 'localhost'; 
$dbname = 'u298556559_real_state';  
$user = 'u298556559_migueeldev';          
$password = 'Lmdev271';       
 */

$host = 'localhost'; 
$dbname = 'victores2';  
$user = 'root';          
$password = '';       

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
