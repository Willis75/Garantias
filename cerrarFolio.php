<?php

header('Content-Type: text/html; charset=utf-8');

$folio = $_POST['folio'];
$info= $_POST['info'];

include 'conect_gar.php';

$solic = $mysqli -> query("SELECT sucursal, usuario_dictaminador, dic_suc FROM solic WHERE folio = $folio");
$suc = $solic -> fetch_assoc();
$sucursal = $suc['sucursal'];
$usuario_dictaminador = $suc['usuario_dictaminador'];

$date= date('c') ;
$resultado = $mysqli->query("UPDATE solic SET estatus = 0, fecha_cierre= '$date', info = $info WHERE folio=$folio");
if (!$resultado) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else{
        
        $mysqli->close();
        
        include 'conect.php';

        if ($suc['dic_suc'] == 1){
            $dic_suc = 1;
            $sql = "SELECT usuario, nombre, email FROM asoc WHERE sucursal = $sucursal AND (gar_jo = 1 || gar_admin = 1)";
        } else {
            $sql = "SELECT usuario, nombre, email FROM asoc WHERE (sucursal = $sucursal AND (gar_jo = 1 || gar_admin = 1)) OR usuario = '".$usuario_dictaminador."'";
        }

        $datos=$mysqli->query($sql);
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
            $subject = utf8_decode('El Jefe de Garantías ha cerrado el folio: '.$folio.'.'); 
            //define the message to be sent. Each line should be separated with \n
            $message = utf8_decode('El Jefe de Garantías ha cerrado el folio: '.$folio.'.') ;
            //define the headers we want passed. Note that they are separated with \r\n
            $headers = "From: jsaenzr@novem.com.mx\r\nReply-To: NoResponder@novem.com.mx\r\nContent-type: text/html; charset=ISO-8859-1";
            //send the email
            
            include 'mail.php';
      
            if($gar_dictam ==1 || $gar_master ==1 ){
                header("Location: coment_all.php?folio=".$folio.""); /* Redirect browser */
                exit();
            } else {
                header("Location: coment.php?folio=".$folio.""); /* Redirect browser */
                exit();
            }
            $datos->free();                
        }

}
$resultado->free();
$solic->free();
$mysqli->close();


?>

            