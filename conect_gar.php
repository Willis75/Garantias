<?php

$mysqli = new mysqli("0.0.0.0", "user", "password", "db");
if ($mysqli->connect_errno) {
    echo "Fallo al contenctar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
$mysqli->set_charset("utf-8");

?>
