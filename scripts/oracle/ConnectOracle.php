<?php
header('Content-Type: text/html; charset=UTF-8');
// Create connection to Oracle

$conn = oci_connect("apps", "apps", "NCLON");
//$conn = oci_connect("apps", "apps", "NOVEM");
//$conn = oci_connect("wlongoria", "lponce", "NCLON");

if (!$conn) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit;
}
else {
   print "Connected to Oracle!";

    $array = oci_parse($conn, "SELECT * FROM APPS.XXNO_ARTICULOS_PROVEEDORES");

    oci_execute($array);
    
    echo "<table border=\"1\">";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>Type</th>";
    echo "<th>Length</th>";
    echo "</tr>";

    $ncols = oci_num_fields($array);

    for ($i = 1; $i <= $ncols; $i++) {
        $column_name  = oci_field_name($array, $i);
        $column_type  = oci_field_type($array, $i);
        $column_size  = oci_field_size($array, $i);

        echo "<tr>";
        echo "<td>$column_name</td>";
        echo "<td>$column_type</td>";
        echo "<td>$column_size</td>";
        echo "</tr>";
    }
    echo "</table>\n";
   
    $array = oci_parse($conn, "SELECT * FROM apps.ra_customers");

    oci_execute($array);
    
    echo "<table border=\"1\">";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>Type</th>";
    echo "<th>Length</th>";
    echo "</tr>";

    $ncols = oci_num_fields($array);

    for ($i = 1; $i <= $ncols; $i++) {
        $column_name  = oci_field_name($array, $i);
        $column_type  = oci_field_type($array, $i);
        $column_size  = oci_field_size($array, $i);

        echo "<tr>";
        echo "<td>$column_name</td>";
        echo "<td>$column_type</td>";
        echo "<td>$column_size</td>";
        echo "</tr>";
    }
    echo "</table>\n";
    
    //echo "<table border='1'>\n";
   // while ($row = oci_fetch_array($array, OCI_ASSOC+OCI_RETURN_NULLS)) {
    //echo "<tr>\n";
    //foreach ($row as $item) {
    //echo " <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : " ") . "</td>\n";
    //}
    //echo "</tr>\n";
    //}
    //echo "</table>\n";
    
    $array = oci_parse($conn, "SELECT * FROM inv.mtl_system_items_b");

    oci_execute($array);
    
    echo "<table border=\"1\">";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>Type</th>";
    echo "<th>Length</th>";
    echo "</tr>";

    $ncols = oci_num_fields($array);

    for ($i = 1; $i <= $ncols; $i++) {
        $column_name  = oci_field_name($array, $i);
        $column_type  = oci_field_type($array, $i);
        $column_size  = oci_field_size($array, $i);

        echo "<tr>";
        echo "<td>$column_name</td>";
        echo "<td>$column_type</td>";
        echo "<td>$column_size</td>";
        echo "</tr>";
    }
    echo "</table>\n";
    
    $array = oci_parse($conn, "SELECT * FROM apps.po_vendors");

    oci_execute($array);
    
    echo "<table border=\"1\">";
    echo "<tr>";
    echo "<th>Name</th>";
    echo "<th>Type</th>";
    echo "<th>Length</th>";
    echo "</tr>";

    $ncols = oci_num_fields($array);

    for ($i = 1; $i <= $ncols; $i++) {
        $column_name  = oci_field_name($array, $i);
        $column_type  = oci_field_type($array, $i);
        $column_size  = oci_field_size($array, $i);

        echo "<tr>";
        echo "<td>$column_name</td>";
        echo "<td>$column_type</td>";
        echo "<td>$column_size</td>";
        echo "</tr>";
    }
    echo "</table>\n";
    
    
    
    //echo "<table border='1'>\n";
    //while ($row = oci_fetch_array($array, OCI_ASSOC+OCI_RETURN_NULLS)) {
    //echo "<tr>\n";
 
    //foreach ($row as $item) {
    //echo " <td>" . $row['INVENTORY_ITEM_ID']."</td><td>".$row['DESCRIPTION']."</td>\n";
    
    //echo "</tr>\n";
    //}
    //echo "</table>\n";
    
    //echo "<table border='1'>\n";
    //while ($row = oci_fetch_array($array, OCI_ASSOC+OCI_RETURN_NULLS)) {
    //echo "<tr>\n";
    //foreach ($row as $item) {
    //echo " <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : " ") . "</td>\n";
    //}
    //echo "</tr>\n";
    //}
    //echo "</table>\n";
    //
    //while($row=oci_fetch_array($array, OCI_ASSOC)){

    //echo $row[0]." ".$row[1];

    //}
}
oci_free_statement($array);
// Close the Oracle connection
oci_close($conn);
?>
