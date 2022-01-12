<?php
header('Content-Type: text/html; charset=utf-8');
session_start();

define('DB_SERVER', '172.18.0.61');
define('DB_USER', 'tickets');
define('DB_PASSWORD', '595704');
define('DB_NAME', 'oracle');
 
if (isset($_GET['factura'])){
    $factura = $_GET['factura'];
    try {
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare("SELECT * FROM facturas WHERE factura LIKE '".$factura."' ORDER BY factura");
        $stmt->execute();
        
        $resultado= $stmt->fetch(PDO::FETCH_ASSOC);
        $fecha = $resultado['fecha_factura'];
        
    }catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
}
$fecha = strtotime($fecha);
$fecha = Date("d/M/Y",$fecha);
$conn = NULL;
$stmt->close();
echo $fecha;
?>
