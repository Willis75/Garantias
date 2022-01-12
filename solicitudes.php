<?php
header('Content-Type: text/html; charset=utf-8');

session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location:logout.php");
}

$return_arr = array();

include 'conect.php';
$sucursales =  array();
$suc = $mysqli->query("SELECT Id, abrevia FROM sucursal ");
if (!$suc) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else {
    while($row = $suc->fetch_assoc()){
    	$sucursales[$row['Id']]=$row['abrevia'];
	}
}
$suc->free();
$mysqli->close();

include 'conect_gar.php';

$estatus = $_GET['estatus'];
$linea = $_GET['linea'];

if ($estatus==1){
	$sql="SELECT * FROM solic where estatus = $estatus AND linea = '$linea' AND fecha_dictamen is null";
} else {
	$sql="SELECT * FROM solic where estatus = $estatus AND linea = '$linea'";
}

$resultado = $mysqli->query($sql);

while ($fila = $resultado->fetch_assoc()){
	$date= strtotime($fila['fecha']);
	$date=date('d/M/y g:ia',$date); 
	$revisar = "";
	if ($fila['lugar'] != 1) {$revisar="**";};
	$abrevia_suc =  $sucursales[$fila['sucursal']];
	$row_arr['folio'] =  $fila['folio'];
	$row_arr['revisar'] = $revisar;
	$row_arr['sucursal'] = $abrevia_suc;
	$row_arr['fecha'] = $date;
	$row_arr['linea'] = $fila['linea'];
	$row_arr['PRODUCTO'] = $fila['PRODUCTO'];
	$row_arr['DESCRIPCION'] = $fila['DESCRIPCION'];
	$row_arr['dictaminador'] = $fila['dictaminador'];
	if ($fila['TOP']==1){
		$TOP = "TOP";
	} else {
		$TOP ="";
	}
	$row_arr['TOP'] = $TOP;

	array_push($return_arr, $row_arr);
}

echo json_encode($return_arr);

$resultado->free();
$mysqli->close();
?>