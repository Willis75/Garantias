<?php
header ('Content-type: text/html; charset=utf-8');
/*** begin our session ***/
session_start();

$user=$_SESSION['user_id'];

/*** first check that both the username, password and form token have been sent ***/
if(!isset( $_SESSION['user_id']))
{
    $message = 'Usuario no registrado';
}
/*** check the form token is valid ***/
elseif( $_POST['form_token'] != $_SESSION['form_token'])
{
    $message = 'Carga de datos invalida';
}

else
{
    /*** if we are here the data is valid and we can insert it into database ***/
    $codigo = filter_var($_POST['codigo'], FILTER_SANITIZE_STRING);
    $descripcion = filter_var($_POST['descripcion'], FILTER_SANITIZE_STRING);
    $proveedor = filter_var($_POST['proveedor'],FILTER_SANITIZE_STRING);
    $linea =  filter_var($_POST['linea'],FILTER_SANITIZE_STRING);
    $periodo =  filter_var($_POST['periodo'],FILTER_SANITIZE_STRING);
    $recepcion =  filter_var($_POST['recepcion'],FILTER_SANITIZE_STRING);
    $canalizado =  filter_var($_POST['canalizado'],FILTER_SANITIZE_STRING);
    $dictaminador =  filter_var($_POST['dictaminador'],FILTER_SANITIZE_STRING);
    $observacion =  filter_var($_POST['observacion'],FILTER_SANITIZE_STRING);

    include 'conect_gar.php';
     
    $sql1="INSERT INTO canalizacion (codigo, descripcion, proveedor, linea, periodo, recepcion, canalizado, dictaminador, observacion) VALUES (?,?,?,?,?,?,?,?,?)";
    $result = $mysqli->prepare($sql1);
    $result->bind_param('sssssssss', $codigo, $descripcion, $proveedor, $linea, $periodo, $recepcion, $canalizado, $dictaminador, $observacion);
    $result -> execute();

    if ($result->errno) {
      echo "FAILURE!!! " . $mysqli->error;
    }

    $mysqli->close();
    
    header("Location: canalizacion.php"); /* Redirect browser */
    exit();
    
    /*** unset the form token session variable ***/
    unset( $_SESSION['form_token'] );

}

?>