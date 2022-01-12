<?php
header('Content-Type: text/html; charset=UTF-8');
// Create connection to Oracle
//$conn = oci_connect("wlongoria", "lponce", "NCLON");
//$conn = oci_connect("apps", "apps", "NOVEM");
$conn = oci_connect("apps", "apps", "NOVEM");
if (!$conn) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit();
}
else {
   print "Connected to Oracle!";

   //apps.po_vendors
   //inv.mtl_system_items_b
   
    //$array = oci_parse($conn, "SELECT * FROM (SELECT * FROM xxno_ra_customers_wl) WHERE ROWNUM <=1000 ORDER BY CUSTOMER_NUMBER");
    $array = oci_parse($conn, "SELECT * FROM (SELECT * FROM xxno_ra_customers_wl WHERE CUSTOMER_NUMBER = 4477) ");
    oci_execute($array);
    
    $ncols = oci_num_fields($array);
    echo "<table border='1'>";
    echo "<tr>";
    
    for ($i = 1; $i <= $ncols; $i++) {
        $column_name  = oci_field_name($array, $i);
        echo "<th>$column_name</th>";
    }
    echo "</tr>";
    
    while ($row = oci_fetch_array($array, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
    echo " <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : " ") . "</td>\n";
    }
    echo "</tr>\n";
    }
    echo "</table>\n";
    
    $array = oci_parse($conn, "SELECT * FROM (SELECT * FROM xxno_articulos_proveedores_wl) WHERE ROWNUM <=100");
    oci_execute($array);
    
    $ncols = oci_num_fields($array);
    echo "<table border='1'>";
    echo "<tr>";
    
    for ($i = 1; $i <= $ncols; $i++) {
        $column_name  = oci_field_name($array, $i);
        echo "<th>$column_name</th>";
    }
    echo "</tr>";
    
    while ($row = oci_fetch_array($array, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
    echo " <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : " ") . "</td>\n";
    }
    echo "</tr>\n";
    }
    echo "</table>\n";
    


    $array = oci_parse($conn, "SELECT * FROM (SELECT * FROM inv.mtl_system_items_b) WHERE ROWNUM <=10");
    oci_execute($array);
    
    $ncols = oci_num_fields($array);
    echo "<table border='1'>";
    echo "<tr>";
    
    for ($i = 1; $i <= $ncols; $i++) {
        $column_name  = oci_field_name($array, $i);
        echo "<th>$column_name</th>";
    }
    echo "</tr>";
    
    while ($row = oci_fetch_array($array, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
    echo " <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : " ") . "</td>\n";
    }
    echo "</tr>\n";
    }
    echo "</table>\n";
       
}

oci_free_statement($array);
// Close the Oracle connection
oci_close($conn);
?>
