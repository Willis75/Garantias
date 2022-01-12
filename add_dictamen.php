<?php
header ('Content-type: text/html; charset=utf-8');
/*** begin our session ***/
session_start();

$folio=$_POST['folio'];
$user=$_POST['usuario'];

/*** check the form token is valid ***/
if( $_POST['form_token'] != $_SESSION['form_token'])
{
    $message = 'Invalid form submission';
}

else
{

    /*** if we are here the data is valid and we can insert it into database ***/
    $procede = $_POST['procede'];
    $aplica = utf8_decode(filter_var($_POST['aplica'], FILTER_SANITIZE_STRING));
    $otro = utf8_decode(filter_var($_POST['otro'], FILTER_SANITIZE_STRING));
    $reparacion = utf8_decode(filter_var($_POST['comentario_reparacion'], FILTER_SANITIZE_STRING));
    $comentarios_dictamen = utf8_decode(filter_var($_POST['comentarios_dictamen'], FILTER_SANITIZE_STRING));
    $causa_falla=utf8_decode(filter_var($_POST['causa_falla'], FILTER_SANITIZE_STRING));
    $refaccion = utf8_decode(filter_var($_POST['comentario_refaccion'], FILTER_SANITIZE_STRING));

    include 'conect_gar.php';

    if($procede == 1){
        $dictamen = "Procedente";
    } else {
        $dictamen = "No procedente";
    }

    $datos = $mysqli -> query("SELECT sucursal, usuario_dictaminador, dic_suc FROM solic WHERE folio = $folio");
    $suc = $datos -> fetch_assoc();
    $sucursal = $suc['sucursal'];
    $usuario_dictaminador = $suc['usuario_dictaminador'];

    if ($aplica == "Otro"){
        $comentario_aplica = $otro;
    } elseif ($aplica == "Reparacion"){
        $comentario_aplica = $reparacion;
    } else {
        $comentario_aplica = $refaccion;
    }

    $date= date('c') ;

    if ($aplica ==99){
    $sql1="UPDATE solic SET dictamen = $procede, comentario_dictamen = '$comentarios_dictamen', fecha_dictamen ='$date', causa_falla='$causa_falla' WHERE folio=$folio";
    }else{
    $sql1="UPDATE solic SET dictamen = $procede, aplica = '$aplica', comentario_aplica = '$comentario_aplica', comentario_dictamen = '$comentarios_dictamen', fecha_dictamen ='$date', causa_falla='$causa_falla' WHERE folio=$folio";
	}

    $result = $mysqli->query($sql1);
        if (!$result) {
            printf("error: %s\n", $mysqli->error);
            exit();
        }
        else {

            include 'conect.php';

            if ($suc['dic_suc'] == 1){
                $dic_suc = 1;
                $sql = "SELECT usuario, nombre, email FROM asoc WHERE usuario='$user' OR (sucursal = $sucursal AND (gar_jo = 1 OR gar_admin =1))";
            } else {
                $sql = "SELECT usuario, nombre, email FROM asoc WHERE usuario='$user' OR (sucursal = $sucursal AND (gar_jo = 1 OR gar_admin =1)) OR usuario = '".$usuario_dictaminador."'";
            }

            $datos=$mysqli->query($sql);
             if ( false==$datos) {
              printf("error: %s\n", $mysqli -> error);
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
                $subject = utf8_decode('Folio: '.$folio.': El usuario '.$nombre.' ha hecho el dictamen'); 
                //define the message to be sent. Each line should be separated with \n
                $message = utf8_decode('El usuario '.$nombre.' ha realizado el dictamen de la solicitud '.$folio.' como: '.$dictamen); 
                //define the headers we want passed. Note that they are separated with \r\n
                $headers = "From: jsaenzr@novem.com.mx\r\nReply-To: NoResponder@novem.com.mx\r\nContent-type: text/html; charset=ISO-8859-1";
                //send the email
                
                include 'mail.php';
        
                if($_SESSION['gar_dictam'] == 1){
                    header("Location: coment_all.php?folio=".$folio.""); /* Redirect browser */
                    exit();
                } elseif ($_SESSION['gar_admin'] ==1){
                    header("Location: coment.php?folio=".$folio.""); /* Redirect browser */
                    exit();
                }           
            }
        }
	
        /*** unset the token session and folio variables  ***/
        unset($_SESSION['form_token']);
        unset($_SESSION['folio']);

        $datos->free();
        $mysqli->close();
        /*** if all is done, say thanks ***/     
}
?>

<html>
<head>
<title>Sistema de Garantías Novem</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
</body>
</html>