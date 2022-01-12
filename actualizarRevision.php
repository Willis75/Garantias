<?php

header('Content-Type: text/html; charset=utf-8');

$folio = $_POST['folio'];
$revision= utf8_encode(filter_var($_POST['revision'], FILTER_SANITIZE_STRING));

include 'conect_gar.php';

$datos = $mysqli -> query("SELECT sucursal, usuario_dictaminador, dic_suc FROM solic WHERE folio = $folio");
$suc = $datos -> fetch_assoc();
$sucursal = $suc['sucursal'];
$usuario_dictaminador = $suc['usuario_dictaminador'];



$date= date('j/M/Y') ;
$revision = $revision." ".$date;
$resultado = $mysqli->query("UPDATE solic SET comentario_revision = '$revision' WHERE folio=$folio");
if (!$resultado) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else{

    include 'conect.php';
    if ($suc['dic_suc'] == 1){
        $dic_suc = 1;
        $sql1 = "SELECT usuario, nombre, email, gar_admin FROM asoc WHERE sucursal = $sucursal AND (gar_jo = 1 OR gar_admin = 1)";
    } else {
        $sql1 = "SELECT usuario, nombre, email, gar_admin FROM asoc WHERE sucursal = ($sucursal AND (gar_jo = 1 OR gar_admin = 1)) OR usuario = '".$usuario_dictaminador."'";
    }

    $datos=$mysqli->query($sql1);
     if ( false==$datos) {
      printf("error: %s\n", $mysqli -> error);
    }else{
   
        $lista="jsaenzr@novem.com.mx, sistemas@novem.com.mx";
        while($fila = $datos->fetch_assoc()){
            if ($lista==""){
                $lista=$fila['email'];
            }else {
                $lista=$lista.", ".$fila['email'];
            }
        }

        $to = $lista;
        //define the subject of the email
        $subject = utf8_decode('El Jefe de Operaciones ha actualizado la Revisión para el folio: '.$folio); 
        //define the message to be sent. Each line should be separated with \n
        $message = utf8_decode('Revisi&#243;n para el folio '.$folio.': '.$revision); 
        //define the headers we want passed. Note that they are separated with \r\n
        $headers = "From: sistemas@novem.com.mx\r\nReply-To: NoResponder@novem.com.mx\r\nContent-type: text/html; charset=ISO-8859-1";
        //send the email
		
		include 'mail.php';
    }
}

$resultado->free();
$datos->free();
$mysqli->close();

?>

            