<?php
header ('Content-type: text/html; charset=utf-8');
/*** begin our session ***/
session_start();

$folio=$_POST['folio'];
$user=$_POST['usuario'];

/*** first check that both the username, password and form token have been sent ***/
if(!isset( $_POST['revision'], $_POST['form_token']))
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
    $revision = utf8_decode(filter_var($_POST['revision'], FILTER_SANITIZE_STRING));

    include 'conect_gar.php';

    $datos = $mysqli -> query("SELECT sucursal, usuario_dictaminador, dic_suc FROM solic WHERE folio = $folio");
    $suc = $datos -> fetch_assoc();
    $sucursal = $suc['sucursal'];
    $usuario_dictaminador = $suc['usuario_dictaminador'];

    $date= date('c') ;
    $date1= date('j/M/Y') ;

    $revision=$revision." ".$date1;

    $sql1="UPDATE solic SET comentario_revision='$revision', fecha_revision='$date' WHERE folio=$folio";
	$result = $mysqli->query($sql1);
        if (!$result) {
            printf("error: %s\n", $mysqli->error);
            exit();
        }
        else {

            include 'conect.php';

            if ($suc['dic_suc'] == 1){
                $dic_suc = 1;
                $sql = "SELECT usuario, nombre, email FROM asoc WHERE usuario='$user' OR (sucursal = $sucursal AND gar_jo = 1)";
            } else {
                $sql = "SELECT usuario, nombre, email FROM asoc WHERE usuario='$user' OR (sucursal = $sucursal AND gar_jo = 1) OR usuario = '".$usuario_dictaminador."'";
            }

            $datos=$mysqli->query($sql);
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
                $subject = utf8_decode('El Jefe de Operaciones ha realizado la Revisión para el folio: '.$folio); 
                //define the message to be sent. Each line should be separated with \n
                $message = utf8_decode('Revisi&#243;n para el folio '.$folio.': '.$revision); 
                //define the headers we want passed. Note that they are separated with \r\n
                $headers = "From: jsaenzr@novem.com.mx\r\nReply-To: NoResponder@novem.com.mx\r\nContent-type: text/html; charset=ISO-8859-1";
                //send the email
                
                include 'mail.php';

            }

            header("Location: coment.php?folio=".$folio.""); /* Redirect browser */
            exit();
        }
	
        /*** unset the token session and folio variables  ***/
        unset($_SESSION['form_token']);
        unset($_SESSION['folio']);
        unset($_SESSION['$correo_usuario']);
        unset($_SESSION['$correo_asignado']);
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