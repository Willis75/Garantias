<?php
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location:logout.php");
}

include 'conect_oracle.php';

$tarea = $_GET['tarea'];


if ($tarea == "remover") {
    $CUSTOMER_ID = $_GET['CUSTOMER_ID'];
    $remover = $mysqli->query("UPDATE clientes SET TOP = NULL WHERE CUSTOMER_ID = $CUSTOMER_ID");
} elseif ($tarea == "agregar") {
    $CUSTOMER_NUMBER = $_GET['CUSTOMER_NUMBER'];
    $agregar = $mysqli->query("UPDATE clientes SET TOP = 1 WHERE CUSTOMER_NUMBER = '$CUSTOMER_NUMBER'");
}
 
$return_arr = array();

$sql = "SELECT * FROM clientes WHERE TOP = 1";
$clientes = $mysqli->query($sql);
if (!$clientes) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else {
    while($row = $clientes -> fetch_assoc()) {
        
        $row_array['CUSTOMER_ID'] = $row['CUSTOMER_ID'];
        $row_array['CUSTOMER_NUMBER'] = $row['CUSTOMER_NUMBER'];
        $row_array['CUSTOMER_NAME'] = utf8_encode($row['CUSTOMER_NAME']);
        $row_array['TOP'] = $row['TOP'];
    
        array_push($return_arr,$row_array);        
    }       
}
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);

$clientes->free();
$mysqli->close();
?>