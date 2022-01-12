<?php

header('Content-Type: text/html; charset=utf-8');

session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location:logout.php");
} 

include 'conect_gar.php';

$folio = $_GET['folio'];

$dictamen = 0;
$dir ="";

$explore = $mysqli -> query("SELECT entrega_dictamen, sucursal, usuario_dictaminador, dic_suc FROM solic WHERE folio = $folio");
if (!$explore) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else {
    $row = $explore -> fetch_array();
    $entrega_dictamen = $row['entrega_dictamen'];
    $sucursal = $row['sucursal'];
    $usuario_dictaminador = $row['usuario_dictaminador'];

    if ($entrega_dictamen != null){
        $dictamen = 1;
    }
}

$date= date('c');
if($dictamen == 1){
    $sql = "UPDATE solic SET entrega_producto= '$date', fecha_cierre = '$date', estatus = 0 WHERE folio=$folio";
    $dir = "index.php";
    $enviar = 1;
} else {
    $sql = "UPDATE solic SET entrega_producto= '$date' WHERE folio=$folio";
    $dir = "coment.php?folio=".$folio;
    $enviar = 0;
}

$resultado = $mysqli->query($sql);
if (!$resultado) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else{
    if ($enviar == 1) {    
        include 'conect.php';

        if ($row['dic_suc'] == 1){
            $dic_suc = 1;
            $sql1 = "SELECT usuario, nombre, email, gar_admin FROM asoc WHERE sucursal = $sucursal AND (gar_jo = 1 OR gar_admin = 1)";
        } else {
            $sql1 = "SELECT usuario, nombre, email, gar_admin FROM asoc WHERE sucursal = ($sucursal AND (gar_jo = 1 OR gar_admin = 1)) OR usuario = '".$usuario_dictaminador."'";
        }

        $datos=$mysqli->query($sql1);
         if ( false==$datos) {
          printf("error: %s\n", $mysqli -> error);
        }else{
       
            $lista="jsaenzr@novem.com.mx";
            while($fila = $datos->fetch_assoc()){
                if ($lista==""){
                    $lista=$fila['email'];
                }else {
                    $lista=$lista.", ".$fila['email'];
                }
            }

            $to = $lista;
            //define the subject of the email
            $subject = utf8_decode('Se ha cerrado el folio: '.$folio.' con la entrega al cliente'); 
            //define the message to be sent. Each line should be separated with \n
            $message = 'Ya se entreg&#243; el dictamen y se realiz&#243; la entrega f&#237;sica por lo que se cierra el folio: '.$folio; 
            //define the headers we want passed. Note that they are separated with \r\n
            $headers = "From: jsaenzr@novem.com.mx\r\nReply-To: NoResponder@novem.com.mx\r\nContent-type: text/html; charset=ISO-8859-1";
            //send the email
            
            include 'mail.php';
        }    
    }
    echo $dir; 
}
$datos->free();       
$row = null;
$explore->free();
$mysqli->close();


?>

            