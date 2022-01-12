<?php
header ('Content-type: text/html; charset=utf-8');
include 'conect.php';

$password=$_POST['pass'];
$correo=$_POST['usuario'].$_POST['dominio'];

if($_POST['telefono']==""){
   $telefono=NULL;
}else{
   $telefono=$_POST['telefono']; 
}

if($_POST['ext']==0){
   $ext=NULL; 
}else{
   $ext=$_POST['ext'];
}

if($_POST['lada']==0){
  $lada=NULL;  
}else{
  $lada=$_POST['lada'];
}


$stmt = $mysqli->prepare("INSERT INTO asoc (usuario, nombre, email, puesto, sucursal, lada, telefono, ext, ip, verificador, pass) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param('ssssiisssss', $usuario, $nombre, $email, $puesto, $sucursal, $lada, $telefono, $ext, $ip, $verificador, $pass);

$usuario=strtolower($_POST['usuario']);
$nombre=utf8_decode(ucwords(strtolower($_POST['nombre'])));
$email= $correo;
$puesto=utf8_decode($_POST['puesto']);
$sucursal=$_POST['sucursal'];
$verificador=$_POST['verificador'];
$ip=$_POST['ip'];
$pass=sha1($password);


/* execute prepared statement */
if($stmt->execute()){
    echo'<script type="text/javascript">'
    ,'window.location.href = "verificar.php";'
    ,'alert("Usuario dado de alta");'
    ,'</script>';
    
    //define the receiver of the email
    $to = 'wlongoriap@soporteg.com.mx, jsaenzr@novem.com.mx, '.$correo.'';
    //define the subject of the email
    $subject = 'Nueva alta de usuario.'; 
    //define the message to be sent. Each line should be separated with \n
    $message = 'Se ha dado de alta al usuario '.$usuario.'. <br>Clave de verificacion: '.$verificador.'<br> Para verificar: http://garantiasnovem.com/verificar.php'; 
    //define the headers we want passed. Note that they are separated with \r\n
    $headers = "From: wlongoriap@soporteg.com.mx\r\nReply-To: NoResponder@novem.com.mx\r\nContent-type: text/html; charset=ISO-8859-1";
    //send the email
    include 'mail.php';
    
}else{
    printf("Error: %s.\n", $stmt->error);
    echo'<script type="text/javascript">'
    ,'window.location.href = "registro.php";'
    ,'alert("Al parecer el usuario o email ya han sido registrados, si continúa experimentando problemas llame a Sistemas.");'
    ,'</script>';
}

/* close statement and connection */
$stmt->close();

/* close connection */
$mysqli->close();

?> 

