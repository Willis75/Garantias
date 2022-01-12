<?php
header ('Content-type: text/html; charset=utf-8');
/*** begin our session ***/
session_start();
$codigo=$_GET['codigo'];
$user=$_SESSION['user_id'];
$gar_master= $_SESSION['gar_master'];

/*** first check that both the username, password and form token have been sent ***/
if(!isset( $_SESSION['user_id'])||$gar_master!=1)
{
    header("Location:logout.php");

} else {
        
    include 'conect_gar.php';

    // sql to delete a record
    $sql = "DELETE FROM canalizacion WHERE codigo=$codigo";

    if ($mysqli->query($sql) === TRUE) {
        echo'<script type="text/javascript">'
        ,'window.location.href = "canalizacion.php";'
        ,'</script>';
    } else {
        echo "Error borrando registro: " . $mysqli->error;
    }

    $mysqli->close();

}

?>