<?php
header('Content-Type: text/html; charset=utf-8');

/*** begin the session ***/
session_start();

$folio=$_GET["folio"];
/*** set a form token ***/

include 'conect_gar.php';

$datos="SELECT * FROM solic WHERE folio = $folio";
$Qdatos= $mysqli->query($datos);
if (!$Qdatos) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else {
    $insert= $Qdatos->fetch_assoc();
}
$mysqli->close();

$abrevia=array();
include 'conect.php';
$suc="SELECT * FROM sucursal";
$Sucursal= $mysqli->query($suc);
if (!$Sucursal) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else {
    while ($sucursales= $Sucursal->fetch_assoc()){
        $abrevia[$sucursales['Id']]=$sucursales['abrevia'];
    }
}
$mysqli->close();

$date= date('d/M/y',strtotime('-6 hours',strtotime($insert['fecha'])));

$fecha_factura= strtotime($insert['fecha_factura']);
$fecha_factura=date('d/M/y',$fecha_factura);

if ($insert['recibe']==1){
    $recibe="Si";
} else {
    $recibe="No";
}

$Qdatos->free();
$Sucursal->free();
?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge charset=utf-8" />
    <meta charset="utf-8">
    <link rel="stylesheet" type= "text/css" href="css/styles_imprimir.css">
    <script type ="text/javascript" src="../scripts/jquery-1.10.1.js"></script>
    <title>Sistema de Garantías Novem</title>
    <link rel="shortcut icon" href="favicon.ico" >
    
<script type="text/javascript">

</script>

</head>

<!--<body onload="window.print()">!-->
<body onload="window.print()">

<div id="logo"><img src="http://sistemas/garantias/images/novem.jpg" height="80"></div>
<div id="titulo"> <h2> Solicitud de Garantía</h2></div>

<div id="linea_divisoria"></div>

<div id="datos">
    <b>Folio: </b>     <?php echo $folio;  ?><br>
    <b>Fecha: </b>    <?php echo $date;  ?><br>
    <b>Sucursal: </b>  <?php echo $abrevia[$insert['sucursal']];  ?><br>
    <b>Formato: </b>     FO018<br>

</div>

<div id="cuerpo"> 
    <b>Factura #:</b> <div class="sub"><?php echo $insert['factura'];  ?> </div> <div class="inline" id="fecha_factura"><b>Fecha factura:</b></div> <div class="sub"><?php echo $fecha_factura;  ?> </div>
    <br>
    <br>
    <b>Número de Cliente:</b> <div class="sub"><?php echo $insert['CUSTOMER_NUMBER'];  ?> </div>
    <br>
    <br>
    <b>Nombre del Cliente:</b> <div id="nombre_cliente" class="sub"><?php echo $insert['CUSTOMER_NAME'];  ?> </div> 
    <br>
    <br>
    <b>Producto:</b> <div class="sub"><?php echo $insert['PRODUCTO'];  ?> </div> <div class="inline" id="descripcion"><b>Descripción:</b></div> <div class="sub"><?php echo $insert['DESCRIPCION'];  ?> </div>
    <br>
    <br>
    <b>Número de serie o Date Code:</b> <div class="sub"><?php echo $insert['serie'];  ?> </div> 
    <br>
    <br>
    <b>Descripción de la falla:</b> 
    <br>
    <TEXTAREA readonly id="falla" cols="110" rows="6" > <?php echo utf8_encode($insert['falla']);  ?></TEXTAREA>
    <br>
    <b>Se recibe el producto:</b> <div id="nombre_cliente" class="sub"><?php echo $recibe;  ?> </div> <div class="inline" id="aplica"><b>Si procede garantía aplica:</b></div> <div class="inline">______________________</div>
    <br>
    <br>
    <b>Notas:</b><br> <TEXTAREA readonly id="notas" cols="110" rows="8" >Si Grupo Novem recibe el producto con esta Solicitud de Garantía es con el fin de realizar un diagnóstico.                                                            
En caso de proceder la garantía Grupo Novem brindará refacciones, reparará, repondrá o cualquier otra acción que aplique de acuerdo a las políticas de garantía establecidas por el fabricante del producto.                                                            
Recibir el producto no obliga a Grupo Novem a ninguna de las acciones antes mencionadas en caso de no proceder la garantía.                                                         
En algunos casos debido a la naturaleza del producto, el diagnóstico deberá realizarse en Taller Autorizado; de ser así y  en caso de no proceder la garantía, es responsaiblidad del Cliente asumir los gastos derivados del diagnóstico del producto.    </TEXTAREA>
    <br>
    <br> 
    <br>    
    <div id="recibe" class="inline"><b>Recibe:</b></div>
    <div id="acepta" class="inline"><b>Acepto términos y condiciones:</b></div>
    <br>
    <br>
    <br>
    <br>
    <div id="recibe_linea" class="inline">________________________________</div>
    <div id="acepta_linea" class="inline">________________________________</div>
    <br>
    <div id="linea_nombre1" class="inline"><b>Nombre y firma</b></div>
    <div id="linea_nombre2" class="inline"><b>Nombre y firma del cliente</b></div>
    <br>
    <br>
  
    <TEXTAREA readonly id="terminos" cols="110" rows="22" >Términos y Condiciones:
En ningún momento Grupo Novem será responsable por el costo de mano de obra o de cualquier otro costo en el que incurra el  Comprador del producto al remover, reinstalar o alterar cualquier parte del equipo que sea enviado a Grupo Novem para su diagnóstico.                                                           
En algunos casos debido a la naturaleza del producto, el diagnóstico deberá realizarse en Taller Autorizado; de ser así y  en caso de no proceder la garantía, es responsaiblidad del Comprador asumir los gastos derivados del diagnóstico del producto en Taller.                                                         
Grupo Novem, otorgará las garantías de los productos que comercializa sujeto al cumplimiento de las políticas de garantía establecidas por el fabricante y que los productos hayan sido correctamente instalados, operados y utilizados durante el periodo de garantía establecido por este último.                                                             
En caso de proceder la garantía del producto por defecto de fábrica o material, la responsabilidad de Grupo Novem se limita expresamente a reparar, brindar refacciones o suministrar reemplazo, a su discreción y libre de cargos al Comprador de acuerdo a las políticas de garantía establecidas por el fabricante.                                                          
El Comprador deberá informar por escrito a Grupo Novem en un plazo no mayor a los (30) días de ocurrida la falla para trámite de Solicitud de Garantía. Fuera de lo anteriormente estipulado, Grupo Novem no será responsable ante el Comprador ni ante ninguna otra tercera parte, en ningún caso, por razón de daños consecuenciales, incidentales o especiales, provocados por o en cualquier manera relacionados con el producto, su diseño, su uso o su imposibilidad de uso, incluyendo, sin estar limitado a, remover reinstalar o alterar el producto, la transportación del producto desde y hasta el centro de servicio y/o daños consecuenciales o incidentales.                                                         
En caso de no proceder la garantía Grupo Novem devolverá el producto en un plazo no mayor a 14 días hábiles después de haberse emitido el dictamen.         </TEXTAREA>
</div>
<?php include_once 'contador.php'; ?>
</body>
</html>
