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
        
        $stmt = $conn->prepare('SELECT * FROM clientes WHERE CUSTOMER_NUMBER LIKE :term ORDER BY CUSTOMER_NUMBER');
        $stmt->execute(array('term' => '%'.$_GET['term'].'%'));
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            
                $row_array['label'] = utf8_encode($row['CUSTOMER_NUMBER']);
                $row_array['value'] = utf8_encode($row['CUSTOMER_NUMBER']);
                $row_array['CUSTOMER_NAME'] = utf8_encode($row['CUSTOMER_NAME']);
                $row_array['TOP'] = $row['TOP'];
                if($row['TOP']==1){
                    $row_array['TOP_SI']="Si";
                } else {
                    $row_array['TOP_SI']=NULL;
                }
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