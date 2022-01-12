<?php
header('Content-Type: text/html; charset=UTF-8'); 
/*** begin our session ***/
session_start();

/*** check if the users is already logged in ***/
if(isset( $_SESSION['user_id'] ))
{
    $message = 'User is already logged in';
}

if (isset($_POST['g-recaptcha-response'])) {
    $captcha = $_POST['g-recaptcha-response'];
    if(!$captcha){
        echo'<script type="text/javascript">'
        ,'alert("Debes validar que no eres un robot");'
        ,'window.location.href = "https://www.google.com";'
        ,'</script>';
        exit;
    }
    $secretKey = "6LdqhBoTAAAAANFe3w8UnZxl4TknU33YxbscRJZC";
    $ip = $_SERVER['REMOTE_ADDR'];
    $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secretKey."&response=".$captcha."&remoteip=".$ip);
    $responseKeys = json_decode($response,true);
    if(intval($responseKeys["success"]) !== 1) {
        echo'<script type="text/javascript">'
        ,'alert("Fallo la prueba de captcha");'
        ,'window.location.href = "https://www.google.com";'
        ,'</script>';
        exit;
    } 
}

/*** check that both the username, password have been submitted ***/
if(!isset( $_POST['phpro_username'], $_POST['phpro_password']))
{
    $message = 'Please enter a valid username and password';
}

/*** check the username is the correct length ***/
elseif (strlen( $_POST['phpro_username']) > 20 || strlen($_POST['phpro_username']) < 4)
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
        $stmt = $dbh->prepare("SELECT usuario, pass, verificado, gar_master, gar_jo, gar_admin, gar_dictam, territorial, territorio, sucursal FROM asoc 
                    WHERE usuario = :phpro_username AND pass = :phpro_password");

        /*** bind the parameters ***/
        $stmt->bindParam(':phpro_username', $phpro_username, PDO::PARAM_STR);
        $stmt->bindParam(':phpro_password', $phpro_password, PDO::PARAM_STR, 40);

        /*** execute the prepared statement ***/
        $stmt->execute();

        /*** check for a result ***/
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $user_id = $resultado['usuario'];
        $verificado = $resultado['verificado'];
        $gar_master = $resultado['gar_master'];
        $gar_dictam = $resultado['gar_dictam'];
        $gar_jo = $resultado['gar_jo'];
        $gar_admin = $resultado['gar_admin'];
        $territorial = $resultado['territorial'];
        $territorio = $resultado['territorio'];
        $sucursal = $resultado['sucursal'];
        
        if ($gar_dictam==1){
            include 'conect_gar.php';
            $resultado = $mysqli->query("SELECT linea FROM dictaminador where usuario = '$user_id'");
            $fila = $resultado->fetch_assoc();
            $linea =  $fila['linea'];
            $_SESSION['linea']=$linea;
            $mysqli->close();
        }

        /*** if we have no result then fail boat ***/
        if($user_id == false)
        {
            $message = 'Login Failed';
            echo'<script type="text/javascript">'
                ,'alert("Por favor valide su usuario y contraseña");'
                ,'window.location.href = "login.php";'
                ,'</script>';
        }elseif($verificado!=1){
            echo'<script type="text/javascript">'
		      ,'alert("Por favor verifique su usuario con la clave que le llego a su email");'
		      ,'window.location.href = "../tickets/verificar.php";'
		      ,'</script>';
        }
        else{
                /*** set the session user_id variable ***/
                $_SESSION['user_id'] = $user_id;
                $_SESSION['verificado']=1;
                $_SESSION['gar_master']=$gar_master;
                $_SESSION['gar_dictam']=$gar_dictam;
                $_SESSION['gar_admin']=$gar_admin;
                $_SESSION['gar_jo']=$gar_jo;
                $_SESSION['sistema'] = 'garantias';
                $_SESSION['territorial'] = $territorial;
                $_SESSION['territorio']=$territorio;
                $_SESSION['sucursal']=$sucursal;

                /*** tell the user we are logged in ***/
                $message = 'You are now logged in';
                if($gar_dictam==1){
                   header("Location:intro_admin.php");
                } elseif ($gar_master==1){
                   header("Location:intro_admin_todos.php");
                }elseif ($gar_admin == 1 || $gar_jo == 1){
                   header("Location:index.php");
                } else{
                   header("Location:intro_admin_general.php");
                }             
        }
    }
    catch(Exception $e)
    {
        /*** if we are here, something has gone wrong with the database ***/
	$message = 'Mensaje de error lanzado por login_submit.php"';
		//header("Location:login.php");
    }
}
unset($resultado);
$dbh = null;
$stmt = null;
?>

<html>
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<!-- <p><?php echo $message; ?> -->
</body>
</html>