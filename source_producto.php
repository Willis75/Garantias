<?php
header('Content-Type: text/html; charset=utf-8');
define('DB_SERVER', '172.30.0.218');
define('DB_USER', 'tickets');
define('DB_PASSWORD', '595704');
define('DB_NAME', 'oracle');
 
$return_arr = array();

if (isset($_GET['term'])){
    $return_arr = array();
 
    try {
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare('SELECT * FROM productos WHERE PRODUCTO LIKE :term ORDER BY PRODUCTO');
        $stmt->execute(array('term' => '%'.$_GET['term'].'%'));
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
                $row_array['label'] = utf8_encode($row['PRODUCTO']);
                $row_array['value'] = utf8_encode($row['PRODUCTO']);
                $row_array['DESCRIPCION'] = utf8_encode($row['DESCRIPCION']);
                $row_array['LINEA'] = utf8_encode($row['LINEA']);
                $row_array['dic_suc'] = utf8_encode($row['dic_suc']);
                $row_array['excep'] = utf8_encode($row['excep']);
                
                array_push($return_arr,$row_array);
            
        }
 
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);

}
     $conn = NULL;

?>