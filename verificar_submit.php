<?php
header('Content-Type: text/html; charset=UTF-8'); 
/*** begin our session ***/
session_start();

/*** check if the users is already logged in ***/
if(isset( $_SESSION['user_id'] ))
{
    $message = 'User is already logged in';
}
/*** check that both the username, password have been submitted ***/
if(!isset( $_POST['phpro_username'], $_POST['phpro_password']))
{
    $message = 'Please enter a valid username and password';
}
/*** check the username is the correct length ***/
elseif (strlen( $_POST['phpro_username']) > 20 || strlen($_POST['phpro_username']) < 0)
{
    $message = 'Incorrect Length for Username';
}
/*** check the password is the correct length ***/
elseif (strlen( $_POST['phpro_password']) > 20)
{
    $message = 'Incorrect Length for Password';
}
else
{
    /*** if we are here the data is valid and we can insert it into database ***/
    $phpro_username = filter_var($_POST['phpro_username'], FILTER_SANITIZE_STRING);
    $phpro_password = filter_var($_POST['phpro_password'], FILTER_SANITIZE_STRING);
    $verificador = filter_var($_POST['verificacion'], FILTER_SANITIZE_STRING);
    
    /*** now we can encrypt the password ***/
    $phpro_password = sha1($phpro_password);
    
    /*** connect to database ***/
    /*** mysql hostname ***/
    $mysql_hostname = '172.30.0.218';

    /*** mysql username ***/
    $mysql_username = 'tickets';

    /*** mysql password ***/
    $mysql_password = '595704';

    /*** database name ***/
    $mysql_dbname = 'tickets';

    try
    {
        $dbh = new PDO("mysql:host=$mysql_hostname;dbname=$mysql_dbname", $mysql_username, $mysql_password);
        /*** $message = a message saying we have connected ***/
	
        /*** set the error mode to excptions ***/
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        /*** prepare the select statement ***/
        $stmt = $dbh->prepare("SELECT usuario, pass, verificador, admin FROM asoc 
                    WHERE usuario = :phpro_username AND pass = :phpro_password");

        /*** bind the parameters ***/
        $stmt->bindParam(':phpro_username', $phpro_username, PDO::PARAM_STR);
        $stmt->bindParam(':phpro_password', $phpro_password, PDO::PARAM_STR, 40);

        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** check for a result ***/
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $user_id= $resultado['usuario'];
        $verifica=$resultado['verificador'];
        echo $verifica," ",$verificador;
        $administrador= $resultado['admin'];
        /*** if we have no result then fail boat ***/
        if($user_id == false){        
		$message = 'Login Failed';
		echo'<script type="text/javascript">'
		,'alert("Por favor valide su usuario y contraseña");'
		,'window.location.href = "verificar.php";'
		,'</script>';		
        }elseif($verificador != $verifica){
		$message = 'Login Failed';
		echo'<script type="text/javascript">'
		,'alert("Por favor valide la clave enviada a su correo");'
		,'window.location.href = "verificar.php";'
		,'</script>';
        }else{
                /*** set the session user_id variable ***/
                $_SESSION['user_id'] = $user_id;
                $_SESSION['admin']=$administrador;
                $_SESSION['verificado']=1;
                
                $stmt = $dbh->prepare("UPDATE asoc SET verificado=1 WHERE usuario='$user_id'");
                $stmt->execute();
                
                header("Location:login.php");
        }
    }
    catch(Exception $e)
    {
        /*** if we are here, something has gone wrong with the database ***/
	$message = 'Mensaje de error lanzado por login_submit.php"';
		//header("Location:login.php");
    }
}
$dbh = null;
$stmt->close();
?>

<html>
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
</body>
</html>