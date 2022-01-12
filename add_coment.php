<?php
header ('Content-type: text/html; charset=utf-8');
/*** begin our session ***/
session_start();

$folio=$_POST['folio'];
$user=$_POST['usuario'];
$gar_dictam=$_SESSION['gar_dictam'];
$gar_master=$_SESSION['gar_master'];


/*** first check that both the username, password and form token have been sent ***/
if(!isset( $_POST['coment'], $_POST['form_token']))
{
    $message = 'Please enter a valid username and password';
}

/*** check the form token is valid ***/
elseif( $_POST['form_token'] != $_SESSION['form_token'])
{
    $message = 'Invalid form submission';
}

else
{

    /*** if we are here the data is valid and we can insert it into database ***/
    $coment = utf8_decode(filter_var($_POST['coment'], FILTER_SANITIZE_STRING));

    include 'conect_gar.php';

    $solic = $mysqli -> query("SELECT sucursal, usuario_dictaminador, dic_suc FROM solic WHERE folio = $folio");
    $suc = $solic -> fetch_assoc();
    $sucursal = $suc['sucursal'];
    $usuario_dictaminador = $suc['usuario_dictaminador'];

    $sql1="INSERT INTO `coment` (num_sol, comentario, de) VALUES ($folio , '$coment', '$user')";
	$result = $mysqli->query($sql1);
    $mysqli->close();

        if (!$result) {
            printf("error: %s\n", $mysqli->error);
            exit();
        }
        else {
            
                include 'conect.php';

                if ($suc['dic_suc'] == 1){
                    $dic_suc = 1;
                    $sql = "SELECT usuario, nombre, email FROM asoc WHERE usuario='$user' OR (sucursal = $sucursal AND (gar_jo = 1 OR gar_admin = 1))";
                } else {
                    $sql = "SELECT usuario, nombre, email FROM asoc WHERE usuario='$user' OR (sucursal = $sucursal AND (gar_jo = 1 OR gar_admin = 1)) OR usuario = '".$usuario_dictaminador."'";
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
                    $subject = utf8_decode('Nuevo comentario para el folio:  '.$folio.'.'); 
                    //define the message to be sent. Each line should be separated with \n
                    $message = utf8_decode('El usuario '.$nombre.' ha realizado un nuevo comentario para la solicitud de garant&#237;a con el folio: '.$folio.'.') ;
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
                }
            }


    /*** unset the token session and folio variables  ***/
    unset( $_SESSION['form_token'] );
    unset($_SESSION['folio']);

    $solic->free();
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