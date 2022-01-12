<?php
header ('Content-type: text/html; charset=utf-8');
/*** begin our session ***/
session_start();

$fileSize=$_FILES['file']['size'];
$user=$_SESSION['user_id'];

/*** first check that both the username, password and form token have been sent ***/
if(!isset( $_SESSION['user_id']))
{
    $message = 'Usuario no registrado';
}
/***Revisar el tama�o del archivo max 1 Mb***/
elseif($fileSize>1048576)
{
    $message = 'El archivo es muy grande, max 1Mb';
    echo'<script type="text/javascript">'
    ,'alert("Los archivos deben ser maximo de 1Mb");'
    ,'window.history.back();'
    ,'</script>';
}
/*** check the form token is valid ***/
elseif( $_POST['form_token'] != $_SESSION['form_token'])
{
    $message = 'Carga de datos invalida';
}

else
{
    /*** if we are here the data is valid and we can insert it into database ***/
    $usuario = filter_var($_POST['usuario'], FILTER_SANITIZE_STRING);
    $sucursal = $_POST['sucursal'];
    $CUSTOMER_NUMBER = filter_var($_POST['CUSTOMER_NUMBER'], FILTER_SANITIZE_STRING);
    $CUSTOMER_NAME = filter_var($_POST['CUSTOMER_NAME'],FILTER_SANITIZE_STRING);
    $TOP =  $_POST['top'];
    $dictam = $_POST['dic_suc'];
    $dictam1 = $_POST['dic_suc1'];
    $excep = $_POST['excep1'];
    $contacto = filter_var($_POST['contacto'],FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
    $factura = filter_var($_POST['factura'],FILTER_SANITIZE_STRING);
    $fecha_factura = $_POST['fecha_factura2'];
    $PRODUCTO = filter_var($_POST['PRODUCTO'],FILTER_SANITIZE_STRING);
    $linea = filter_var($_POST['linea'],FILTER_SANITIZE_STRING);
    $DESCRIPCION = utf8_decode(filter_var($_POST['DESCRIPCION'], FILTER_SANITIZE_STRING));
    $serie = filter_var($_POST['serie'], FILTER_SANITIZE_STRING);
    $recibe = $_POST['recibe'];
    $dictaminador = filter_var($_POST['dictaminador'],FILTER_SANITIZE_STRING);
    $usuario_dictaminador = filter_var($_POST['usuario_dictaminador'],FILTER_SANITIZE_STRING);
    $lugar = $_POST['lugar'];
    $falla = utf8_decode(filter_var($_POST['falla'],FILTER_SANITIZE_STRING));
    $canalizacion = utf8_decode(filter_var($_POST['canalizacion'], FILTER_SANITIZE_STRING));

    if($canalizacion!= "Otro"){
        $otro=NULL;
    } else {
        $otro= utf8_decode(filter_var($_POST['otro'], FILTER_SANITIZE_STRING));
    }

    if (($dictam == 1 || $dictam1 == 1) && $excep != 1){
        $dic_suc = 1;
        $dictaminarse = "Sucursal";
        $sql = "SELECT usuario, nombre, email FROM asoc WHERE usuario='$user' OR (sucursal = $sucursal AND (gar_jo = 1 OR gar_admin = 1))";
    } else {
        $dictaminarse = utf8_encode("Soporte T&#233;cnico");
        $sql = "SELECT usuario, nombre, email FROM asoc WHERE usuario='$user' OR (sucursal = $sucursal AND (gar_jo = 1 OR gar_admin = 1)) OR usuario = '".$usuario_dictaminador."'";
    }
        
/*** para generar un nombre aleatorio***/    
    function makeRandomString($max) {
    $i = 0; //Reset the counter.
    $possible_keys = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $keys_length = strlen($possible_keys);
    $str = ""; //Let's declare the string, to add later.
    while($i<$max) {
    	$rand = mt_rand(1,$keys_length-1);
    	$str.= $possible_keys[$rand];
    	$i++;
    }
    return $str;
    }

    include 'conect_gar.php';

        if ($fileSize>0){
           $fileSize=$_FILES['file']['size'];
           $fileType=$_FILES['file']['type'];

           $ext=pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
           $fileName=date("Y-m-d_H-i-s",time())."_".makeRandomString(3).".".$ext;
           $fileTmpLoc=$_FILES['file']['tmp_name'];
           $pathAndName="documentos/".$fileName;
           $moveResult=move_uploaded_file($fileTmpLoc,$pathAndName);
           $ruta=$pathAndName;
        }

    $sql1="INSERT INTO solic (usuario, sucursal, CUSTOMER_NUMBER, CUSTOMER_NAME, TOP, contacto, email, factura, fecha_factura, PRODUCTO, linea, DESCRIPCION, serie, recibe, dictaminador, lugar, falla, canalizacion, canalizacion_otro, file_name, file_type, file_size, file_ruta, dic_suc, usuario_dictaminador) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    
    $result = $mysqli->prepare($sql1);
    $result->bind_param('sississssssssisisssssisis', $usuario , $sucursal, $CUSTOMER_NUMBER, $CUSTOMER_NAME, $TOP, $contacto, $email, $factura, $fecha_factura,$PRODUCTO, $linea, $DESCRIPCION,$serie, $recibe, $dictaminador, $lugar, $falla, $canalizacion, $otro, $fileName, $fileType, $fileSize, $ruta, $dic_suc, $usuario_dictaminador );
    $result -> execute();

    $folio = $mysqli -> insert_id;

    $mysqli->close();
	
    include 'conect.php';

    $abrevia = array ();
    $suc="SELECT Id, abrevia FROM sucursal";
    $Qsuc= $mysqli->query($suc);
    if (!$Qsuc) {
        printf("error: %s\n", $mysqli->error);
        exit();
    }else{
        while($fila = $Qsuc->fetch_assoc()){
            $abrevia[$fila['Id']] = $fila['abrevia'];
        }
    }

    $datos=$mysqli->query($sql);
         if ( false==$datos) {
          printf("error: %s\n", mysqli_error($mysqli));
        }else{      

            $lista="jsaenzr@novem.com.mx";
            while($fila = $datos->fetch_assoc()){
                if ($fila['usuario'] == $user){
                    $nombre = utf8_encode($fila['nombre']);
                }
                if ($lista==""){
                    $lista=$fila['email'];
                }else {
                    $lista=$lista.", ".$fila['email'];
                }
            }
        
            $to = $lista;
            //define the subject of the email
            $subject = utf8_decode('Nueva solicitud de garantía con el folio:  '.$folio.'.'); 
            //define the message to be sent. Each line should be separated with \n
            $message = utf8_decode('El usuario '.$nombre.' de la sucursal '.$abrevia[$sucursal].' ha dado de alta la solicitud de garant&#237;a con el folio: '.$folio.' a dictaminarse en '.$dictaminarse.' .') ;
            //define the headers we want passed. Note that they are separated with \r\n
            $headers = "From: jsaenzr@novem.com.mx\r\nReply-To: NoResponder@novem.com.mx\r\nContent-type: text/html; charset=ISO-8859-1";
            //send the email
            
            include 'mail.php';      
	   }

        header("Location: index.php"); /* Redirect browser */
        exit();
        
        $datos->free();
        $Qsuc->free();
        $mysqli->close();
          
        /*** unset the form token session variable ***/
        unset( $_SESSION['form_token'] );

}
?>

<html>
<head>
<title>Sistema de Tickets Novem</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
</body>
</html>