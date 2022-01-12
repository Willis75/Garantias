<?php
header ('Content-type: text/html; charset=utf-8');
/*** begin our session ***/
session_start();

$folio=$_POST['folio'];
$user=$_POST['usuario'];
$sucursal=$_POST['sucursal'];
$date= date('c') ;

if( $_POST['form_token'] != $_SESSION['form_token'])
{
    $message = 'Invalid form submission';
}

else
{

    /*** if we are here the data is valid and we can insert it into database ***/
    $accion = utf8_decode(filter_var($_POST['accion'], FILTER_SANITIZE_STRING));

    include 'conect_gar.php';

    $sql2="UPDATE solic SET accion='$accion', fecha_accion='$date' WHERE folio = $folio";
    $result2 = $mysqli->query($sql2);
    $mysqli->close();
    
    include 'conect.php';

    $sql = "SELECT usuario, nombre, email FROM asoc WHERE usuario='$user' OR (sucursal = '$sucursal' AND (gar_jo = 1 OR gar_admin = 1))";
    $datos=$mysqli->query($sql);
  
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
    $subject = utf8_decode('Actualización para el folio:  '.$folio.'.'); 
    //define the message to be sent. Each line should be separated with \n
    $message = utf8_decode('El usuario '.$nombre.' ha definido una acci&#243;n para la solicitud de garant&#237;a con el folio: '.$folio.'.') ;
    //define the headers we want passed. Note that they are separated with \r\n
    $headers = "From: sistemas@novem.com.mx\r\nReply-To: NoResponder@novem.com.mx\r\nContent-type: text/html; charset=ISO-8859-1";
    //send the email

    include 'mail.php';

    header("Location: coment.php?folio=".$folio.""); /* Redirect browser */
    exit();                     

    /*** unset the token session and folio variables  ***/
    unset( $_SESSION['form_token'] );

    $datos->free();
    $mysqli->close();
    /*** if all is done, say thanks ***/     
}
?>

