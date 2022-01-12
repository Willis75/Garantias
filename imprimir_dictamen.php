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

class Dictaminador {
    public $usuario;
    public $dictaminador;
    public $email;
    public $telefono;
    public $ext;
    public $file_route;

    public function __construct($usuario, $dictaminador, $email, $telefono, $ext, $file_route){
        $this->usuario = $usuario;
        $this->dictaminador = $dictaminador;
        $this->email = $email;
        $this->telefono = $telefono;
        $this->ext = $ext;
        $this->file_route =  $file_route;
    }
}

$dict = $mysqli->query("SELECT * FROM dictaminador");
if (!$dict) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else {
    $dictam = array();
    while ($row= $dict->fetch_assoc()){
        $dictam[$row['linea']] = new Dictaminador($row['usuario'], $row['dictaminador'], $row['email'], $row['telefono'], $row['ext'], $row['file_route']);   
    };
}

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

if ($insert['dic_suc'] == 1){
    $linea = $abrevia[$insert['sucursal']];
} else {
    $linea = $insert['linea'];
}

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

$dict->free();
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
<div id="titulo"> <h2> Dictamen de Garantía</h2></div>

<div id="linea_divisoria2"></div>

<div id="datos_dictamen">
    <b>Formato: </b>     FO020<br>
</div>

<div id="cuerpo">

    <div id="fecha_actual">Monterrey N.L. a <?PHP echo date('d')." de ".$meses[date('n')-1]. " del ".date('Y');?></div> 
    <br>
    <br>
    <br>
    <br>
    <b>Contacto: </b><div class="inline"><?php echo $insert['contacto'];?> </div>
    <br>
    <b>Cliente:</b><div class="inline"><?php echo $insert['CUSTOMER_NAME'];?> </div>
    <br>
    <br>
    <br>
    En respuesta a su solicitud de garantía:
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
    Le informamos que de acuerdo a la política de garantía establecida por el fabricante del producto se dictamina como sigue:
    <br>
    <br>
    <b>Procede garantía:</b> <div id="nombre_cliente" class="sub"><?php echo $dictamen;  ?> </div> <div class="inline" id="aplica"><b>Aplica:</b></div> <div class="inline" id="aplica_text"><?php echo $insert['aplica'];?></div>
    <br>
    <br>
    <b>Causa de la falla:</b><br> <TEXTAREA readonly id="causa_falla" cols="110" rows="3" > <?PHP echo utf8_encode($insert['causa_falla']) ?>    </TEXTAREA>
    <br>
    <br>
    <b>Comentarios del dictamen y/o recomendaciones:</b><br> <TEXTAREA readonly id="comentario_dictamen" cols="110" rows="8" > <?PHP echo utf8_encode($insert['comentario_dictamen']) ?>    </TEXTAREA>
    <br>
    <br>
    <TEXTAREA readonly id="contacto" cols="120" rows="2" >En caso de cualquier duda o requerir mayor información, favor de dirigirse en horarios de oficina con <?PHP print_r($dictam[$linea]->dictaminador); ?> al teléfono <?PHP print_r($dictam[$linea]->telefono) ?> ext <?PHP print_r($dictam[$linea]->ext) ?>.</TEXTAREA>
    <br> 
    <br>    
    Atentamente,
    <br>
    <br>
    <div id = "imagen" class ="inline"><img src=<?PHP print_r($dictam[$linea]->file_route) ?> height="150"></div>
    <div id ="firmas" class = "inline">
        <img src="images/firmas.png" width ="280">
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <TEXTAREA readonly id="direccion" cols="150" rows="2" >Oficinas Generales: Aarón Saenz No. 1896, Col. Santa María, Monterrey, N.L. 64650. Tel (81)8153-0020. Fax (81)8153-0027. www.novem.com.mx</TEXTAREA>
<?php include_once 'contador.php'; ?>
</body>
</html>
