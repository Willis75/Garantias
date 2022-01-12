<?php

$mysqli = new mysqli("172.30.0.218", "tickets", "595704", "tickets");
if ($mysqli->connect_errno) {
    echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$mysqli->set_charset("utf-8");

?>
