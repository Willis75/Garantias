<?php
header ('Content-type: text/html; charset=utf-8');

$usuario = $_POST['usuario'];

include 'conect_gar.php';

$fileSize=$_FILES['file']['size'];
$fileType=$_FILES['file']['type'];
//$ext=pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION);
$fileName=$_FILES['file']['name'];
$fileTmpLoc=$_FILES['file']['tmp_name'];
$pathAndName="images/".$fileName;
$moveResult=move_uploaded_file($fileTmpLoc,$pathAndName);
$ruta=$pathAndName;

//echo $fileName." ".$fileSize." ".$fileType." ".$pathAndName." ".$moveResult." ".$ruta;

$sql="UPDATE dictaminador SET file_name = ?, file_type = ?, file_size = ?, file_route = ? WHERE usuario = ?";

$result = $mysqli->prepare($sql);
$result->bind_param('ssiss', $fileName , $fileType, $fileSize, $ruta, $usuario);
if (!$result) {
    printf("error: %s\n", $mysqli->error);
    exit();
}
$result -> execute();
$mysqli->close();

?>
