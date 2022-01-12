<?php

$mysqli = new mysqli("172.18.0.61", "tickets", "595704", "oracle");
if ($mysqli->connect_errno) {
    echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$mysqli->set_charset("utf-8");
?>
