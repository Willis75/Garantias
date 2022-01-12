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
   
    $array = oci_parse($conn, "SELECT rct.trx_number,
       rct.trx_date,
       hou.name

        FROM RA_CUSTOMER_TRX_all rct,
             HR_ORGANIZATION_UNITS_V hou

        WHERE 1=1
        AND ROWNUM <=10
        AND rct.trx_date >= '1-FEB-2014'
        AND rct.program_application_id = '222'
        AND rct.org_id = hou.organization_id

        ORDER BY rct.trx_date DESC");
    
    oci_execute($array);
    
    $ncols = oci_num_fields($array);
    echo "<table border='1'>";
    echo "<tr>";
    
    for ($i = 1; $i <= $ncols; $i++) {
        $column_name  = oci_field_name($array, $i);
        echo "<th>$column_name</th>";
    }
    echo "<th>FACTURA</th>";
    echo "</tr>";
    
    while ($row = oci_fetch_array($array, OCI_ASSOC+OCI_RETURN_NULLS)) {
    echo "<tr>\n";
    foreach ($row as $item) {
    echo " <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : " ") . "</td>\n";
    }
    echo "<td>".SUBSTR($row['NAME'],3)."A".$row['TRX_NUMBER']."</td>";
    echo "</tr>\n";
    }
    echo "</table>\n";
      
}

oci_free_statement($array);
// Close the Oracle connection
oci_close($conn);
?>
