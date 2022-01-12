<?php
header('Content-Type: text/html; charset=UTF-8');
include 'conect.php';

$conn = oci_connect("apps", "apps", "NOVEM");
if (!$conn) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit;
}
else {
   print "Connected to Oracle!";

    $array = oci_parse($conn, "SELECT * FROM xxno_ra_customers_wl ORDER BY CUSTOMER_NUMBER");
    oci_execute($array);
    
    while ($row = oci_fetch_array($array, OCI_ASSOC+OCI_RETURN_NULLS)) {
       echo "<br>".$row['CUSTOMER_NUMBER'].", '".utf8_decode($row['CUSTOMER_NAME']);
       $mysqli->query("INSERT INTO clientes (CUSTOMER_ID, CUSTOMER_NUMBER, CUSTOMER_NAME) VALUES (".$row['CUSTOMER_ID'].", ".$row['CUSTOMER_NUMBER'].", '".  utf8_decode($row['CUSTOMER_NAME'])."')");
    }

    $array = oci_parse($conn, "SELECT * FROM xxno_articulos_proveedores_wl");
    oci_execute($array);

    while ($row = oci_fetch_array($array, OCI_ASSOC+OCI_RETURN_NULLS)) {
            $PRODUCTO= $row['PRODUCTO'];
            $DESCRIPCION= $row['DESCRIPCION'];
            $LINEA= $row['LINEA'];
            //echo "<br>".$row['PRODUCTO'];
            $result=$mysqli->query("INSERT INTO productos (PRODUCTO, DESCRIPCION, LINEA) VALUES ('$PRODUCTO', '$DESCRIPCION', '$LINEA')");       

    }

}

$mysqli->close();
oci_close($conn);

?>


<html> 
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" /> 
<script type ="text/javascript" src="../jquery-1.10.1.js"></script>

<script>

function closeMe()
{
var win = window.open("","_self"); 
win.close();
}

$(document).ready(function(){
  
  closeMe();
  
})

</script>

</head> 

<body>



</body> 
</html>
