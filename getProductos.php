<?php
session_start();

include 'conect_oracle.php';

$tarea = $_GET['tarea'];
if (isset($_GET['tipo'])){
    $tipo = $_GET['tipo'];
}

if ($tarea == "remover") {
    $Id = $_GET['Id'];
    $remover = $mysqli->query("UPDATE productos SET dic_suc = NULL, excep = NULL WHERE Id = $Id");
} elseif ($tarea == "agregar") {
    $PRODUCTO = $_GET['producto'];
    if ($tipo == "excep"){
        if (!$mysqli->query("UPDATE productos SET excep = 1, dic_suc = null WHERE PRODUCTO = '$PRODUCTO'")) {
            printf("Errormessage: %s\n", $mysqli->error);
        }
    } elseif ($tipo == "dic_suc"){
        $agregar = $mysqli->query("UPDATE productos SET excep = null, dic_suc = 1 WHERE PRODUCTO = '$PRODUCTO'");
    }
}
 
$return_arr = array();

$sql = "SELECT * FROM productos WHERE dic_suc = 1 OR excep = 1";
$productos = $mysqli->query($sql);
if (!$productos) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else {
    while($row = $productos -> fetch_assoc()) {
        if ($row['dic_suc']==null) {
            $dic_suc = "";
            $excep = "Si"; 
        }
        if ($row['excep']==null) {
            $excep = "";
            $dic_suc = "Si";
        }
        $row_array['Id'] = $row['Id'];
        $row_array['PRODUCTO'] = utf8_encode($row['PRODUCTO']);
        $row_array['DESCRIPCION'] = utf8_encode($row['DESCRIPCION']);
        $row_array['LINEA'] = $row['LINEA'];
        $row_array['dic_suc'] = $dic_suc;
        $row_array['excep'] = $excep;
    
        array_push($return_arr,$row_array);        
    }       
}
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);

$productos->free();
$mysqli->close();
?>