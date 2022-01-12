<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();

if(!isset($_SESSION['user_id']))
	//||$_SESSION['gar_master']==0
{
    header("Location:logout.php");
}

$gar_master     =   $_SESSION['gar_master'];
$territorial    =   $_SESSION['territorial'];
$terrotorio     =   $_SESSION['terrotorio'];
$sucursal       =   $_SESSION['sucursal'];

include 'conect.php';

$suc = array();
$sql="SELECT Id, abrevia FROM sucursal";
$resultado = $mysqli->query($sql);
if (!$resultado) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else {
    while($fila = $resultado->fetch_assoc()){
        $suc[$fila['Id']]=$fila['abrevia'];
    }
}

$mysqli->close();


include 'conect_gar.php';

if ($gar_master == 1) {
    $sql="SELECT *, DATEDIFF(CURDATE(),s.fecha) difhoy, DATEDIFF(s.fecha_cierre, s.fecha) datedif ,s.file_ruta FROM solic s ORDER BY s.folio DESC"; 
} elseif ($territorial == 1) {
    $sql="SELECT *, DATEDIFF(CURDATE(),s.fecha) difhoy, DATEDIFF(s.fecha_cierre, s.fecha) datedif ,s.file_ruta FROM solic s WHERE s.sucursal IN ($terrotorio) ORDER BY s.sucursal ASC,  s.folio DESC"; 
} else {
    $sql="SELECT *, DATEDIFF(CURDATE(),s.fecha) difhoy, DATEDIFF(s.fecha_cierre, s.fecha) datedif ,s.file_ruta FROM solic s WHERE s.sucursal = $sucursal ORDER BY s.folio DESC";
}                   

$resultado = $mysqli->query($sql);
if (!$resultado) {
    printf("error: %s\n", $mysqli->error);
    exit();
}

 function cleanData($str) { 
     $str = preg_replace("/\t/", "\\t", $str); 
     $str = preg_replace("/\r?\n/", "\\n", $str);
     return $str;
 }
 
header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=garantias.xls");

echo 'Folio'."\t".'Sucursal'."\t".'Producto'."\t".utf8_decode('Descripción')."\t".'Linea'."\t".'Cliente #'."\t".'Cliente'."\t".'TOP'."\t".'Factura'."\t".'Fecha factura'."\t".'Dictaminador'."\t".'Dictamen en Sucursal'."\t".utf8_decode('Canalización')."\t".'Estatus'."\t".utf8_decode('Falto información')."\t".'Inicio'."\t".utf8_decode('Revisión sucursal')."\t".'Fecha dictamen'."\t".utf8_decode('Fecha acción')."\t".'Entrega dictamen'."\t".'Entrega producto'."\t".'Procede'."\t".utf8_decode('Acción')."\t".'Aplica'."\t".'Cierre'."\t".'Dias'."\t".'Adjunto'."\t".'Producto'."\t".'Comentario'."\t".'#_Serie'."\t".'Causa falla'."\n";

while($fila = $resultado->fetch_assoc()){

    $sucursal=$suc[$fila['sucursal']];

    if( $fila['file_ruta']!=""){
        $adjunto="Adjunto";
    }else{
        $adjunto="";
    }
    if ($fila['estatus']==1){
        $fecha_cierre="";
        $datedif=$fila['difhoy'];
    }
    else{
        $fecha_cierre= strtotime($fila['fecha_cierre']);
        $fecha_cierre=date('d/m/y g:ia',$fecha_cierre);
        $datedif=$fila['datedif'];
    }

    $fecha_inicio= date('d/M/y H:i',strtotime('-6 hours',strtotime($fila['fecha'])));
    
    echo $fila['folio']."\t".$sucursal."\t".$fila['PRODUCTO']."\t".preg_replace("/\r|\n/","",$fila['DESCRIPCION'])."\t".$fila['linea']."\t".$fila['CUSTOMER_NUMBER']."\t".$fila['CUSTOMER_NAME']."\t".$fila['TOP']."\t".$fila['factura']."\t".$fila['fecha_factura']."\t".$fila['dictaminador']."\t".$fila['dic_suc']."\t".$fila['canalizacion']."\t".$fila['estatus']."\t".$fila['info']."\t".$fila['fecha']."\t".$fila['fecha_revision']."\t".$fila['fecha_dictamen']."\t".$fila['fecha_accion']."\t".$fila['entrega_dictamen']."\t".$fila['entrega_producto']."\t".$fila['dictamen']."\t".$fila['accion']."\t".preg_replace("/\r|\n/","",$fila['aplica'])."\t".$fila['fecha_cierre']."\t".$datedif."\t".$adjunto."\t".$fila['PRODUCTO']."\t".preg_replace("/\r|\n/","",$fila['comentario_aplica'])."\t".preg_replace("/\r|\n/","",$fila['serie'])."\t".preg_replace("/\r|\n/","",$fila['causa_falla'])."\t \n";
    }


$resultado->free();
$mysqli->close();

?>
