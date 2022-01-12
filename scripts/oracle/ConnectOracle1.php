<?php
header('Content-Type: text/html; charset=UTF-8');
// Create connection to Oracle
$conn = oci_connect("apps", "apps", "NOVEM");
if (!$conn) {
   $m = oci_error();
   echo $m['message'], "\n";
   exit;
}
else {
   print "Connected to Oracle!";

}

oci_close($conn);
?>
