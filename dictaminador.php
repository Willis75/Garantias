<?php
header('Content-Type: text/html; charset=utf-8');

$return_arr = array();

include 'conect_gar.php';

$line = $_GET['linea'];
$dictamen = $_GET['dic_suc'];
$dictamen1 = $_GET['dic_suc1'];
$excep = $_GET['excep'];


$resultado = $mysqli->query("SELECT * FROM dictaminador where linea = '$line'");

$fila = $resultado->fetch_assoc();
if (($dictamen == 1 || $dictamen1 == 1) && $excep != 1){
	$row_arr['dictaminador'] =  "Sucursal";
	$row_arr['email_dictaminador'] = NULL;
	$row_arr['usuario'] = NULL;
} else {
	$row_arr['dictaminador'] =  $fila['dictaminador'];
	$row_arr['email_dictaminador'] = $fila['email'];
	$row_arr['usuario'] = $fila['usuario'];
}
array_push($return_arr, $row_arr);
echo json_encode($return_arr);

$resultado->free();
$mysqli->close();
?>