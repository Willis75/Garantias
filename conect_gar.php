<?php

$mysqli = new mysqli("172.30.0.218", "tickets", "595704", "garantias");
if ($mysqli->connect_errno) {
    echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$mysqli->set_charset("utf-8");

?>
