<?php
header('Content-Type: text/html; charset=utf-8');
setlocale(LC_ALL,'es_ES');
$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");


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
$nom_suc=array();
$estado=array();

include 'conect.php';
$suc="SELECT * FROM sucursal";
$Sucursal= $mysqli->query($suc);
if (!$Sucursal) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else {
    while ($sucursales= $Sucursal->fetch_assoc()){
        $abrevia[$sucursales['Id']]=$sucursales['abrevia'];
        $nom_suc[$sucursales['Id']]=$sucursales['sucursal'];
        $estado[$sucursales['Id']]=$sucursales['estado'];
    }
}
$mysqli->close();

$date= date('d/M/y',strtotime('-6 hours',strtotime($insert['fecha'])));

$fecha_factura= strtotime($insert['fecha_factura']);
$fecha_factura=date('d/M/Y',$fecha_factura);

if ($insert['recibe']==1){
    $recibe="Si";
} else {
    $recibe="No";
}

if ($insert['dictamen']==1){
    $dictamen="Si";
} else {
    $dictamen="No";
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
<body onload="window.print()">  <!--onload="window.print()">!-->

<div id="logo"><img src="http://sistemas/garantias/images/novem.jpg" height="80"></div>
<div id="titulo"> <h2> Recibo del producto</h2></div>

<div id="linea_divisoria2"></div>

<div id="datos_dictamen">
    <b>Formato: </b>     FO021<br>
</div>

<div id="cuerpo">

    <div id="fecha_actual"><?PHP echo $nom_suc[$insert['sucursal']]." ".$estado[$insert['sucursal']] ?> a <?PHP echo date('d')." de ".$meses[date('n')-1]. " del ".date('Y');?></div> 
    <br>
    <br>
    <br>
    <br>
    <h2>Constancia de entrega relacionada a Solicitud de Garantía</h2>
    <br>
    <br>
    <br>
    Yo, ____________________________________ hago constar que recibí:
    <br>
    <br>
        <br>
    <br>
    <b>Folio:</b> <div class="sub"><?php echo $insert['folio'];  ?> </div> <div class="inline" id="fecha_solicitud"><b>Fecha solicitud:</b> </div><div class="sub"><?php echo $date;  ?> </div> 
    <br>
    <br>
    <b>Producto:</b> <div class="sub"><?php echo $insert['PRODUCTO'];  ?> </div> <div class="inline" id="descripcion"><b>Descripción:</b></div> <div class="sub"><?php echo $insert['DESCRIPCION'];  ?> </div>
    <br>
    <br>
    <b>Factura:</b> <div class="sub"><?php echo $insert['factura'];  ?> </div> <div class="inline" id="descripcion"><b>Fecha factura:</b></div> <div class="sub"><?php echo $insert['fecha_factura'];  ?> </div>
    <br>
    <br>
    <br>
    <br>
    Le informamos que de acuerdo a la política de garantía establecida por el fabricante del producto se dictamina como sigue:
    <br>
    <br>
    <b>Procede garantía:</b> <div id="nombre_cliente" class="sub"><?php echo $dictamen;  ?> </div> <div class="inline" id="aplica"><b>Aplica:</b></div> <div class="inline"><?php echo $insert['aplica'];?></div>
    <br>
    <br>
    <b>Comentarios del dictamen y/o recomendaciones:</b><br> <TEXTAREA readonly id="terminos" cols="110" rows="6" > <?PHP echo utf8_encode($insert['comentario_dictamen']) ?>    </TEXTAREA>
    <br>
    <br>
    <br>
    <br>
    <TEXTAREA readonly id="contacto" cols="110" rows="2" >En caso de cualqueir duda o requerir mayor información, favor de dirigirse con <?PHP echo $insert['dictaminador']; ?> al teléfono __________ ext _____ en horarios de oficina.</TEXTAREA>
    <br> 
    <br>    
    <br> 
    <br>    
    Atentamente,
    <br>
    <b>FIRMA ELECTRÓNICA</b>
    <br>
    <br>
    <br>
    <br>
    Título y nombre completo
    <br>
    Puesto desempeñado
    <br>
    <br>
    <br>
    <div id="recibido" class="inline">________________________________</div>
    <br>
    <div id="linea_recibido" class="inline"><b>Nombre y firma</b></div>
    <br>
    <br>
    <div id="fecha_recibido" class="inline">Fecha:_________________</div>
    <br>
    <br>
    <br>
    <br>
    <TEXTAREA readonly id="direccion" cols="150" rows="2" >Oficinas Generales: Aarón Saenz No. 1896, Col. Santa María, Monterrey, N.L. 64650. Tel (81)8153-0020. Fax (81)8153-0027. www.novem.com.mx</TEXTAREA>
<?php include_once 'contador.php'; ?>
</body>
</html>
