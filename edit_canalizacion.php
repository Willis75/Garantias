<?php

header('Content-Type: text/html; charset=UTF-8');
/*** begin the session ***/
session_start();

$user=$_SESSION['user_id'];
$gar_master=$_SESSION['gar_master'];
$codigo= $_GET['codigo'];
$form_token = md5( uniqid('auth', true) );
$_SESSION['form_token'] = $form_token;

if(!isset($_SESSION['user_id'])||$gar_master!=1)
{
    header("Location:logout.php");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" type= "text/css" href="css/styles_intro_admin.css">
    <script type ="text/javascript" src="../scripts/jquery-1.10.1.js"></script>
    <title>Sistema de Garantías Novem</title>
    <link rel="shortcut icon" href="favicon.ico" >
</head>
<body>

    <div id="envoltorio" align="center">
        <div id="envoltorio_interno">
            
            <div id="encabezado">
                <img src="images/novem.jpg" alt="logo Novem" heigh="250" width="250"><br><br>                
                <div id="franja_superior"></div>
                
                <div class="clear"></div>
                
            </div>

        <div id="navegacion">
            
            <div class="navegacion"><a href="canalizacion.php">Regresar</a></div>
            
            <div class="navegacion"><a href="logout.php">Cerrar sesión</a></div>

        </div>
	    
	    <div id="cuerpo">
		
		<div id="tickets_abiertos">

            <?PHP

                include 'conect_gar.php';
                            
                $sql="SELECT *  FROM canalizacion WHERE codigo = '$codigo'";
                
                $resultado = $mysqli->query($sql);
                if (!$resultado) {
                 printf("error: %s\n", $mysqli->error);
                     exit();
                 }else {
                    $fila = $resultado->fetch_assoc();  
                }
                    
                $resultado->free();
                $mysqli->close();

            ?>

		    <h2>Edición</h2>
            
            <form action="add_canalizacion.php" method="post" enctype="multipart/form-data" onsubmit="return validarForma(this);" target="_top">

                <div class="izq">Código: <input type="text" readonly name="codigo" value="<?PHP echo $fila['codigo']; ?>"></div> <br><br>
                <div class="izq">Descripción: <input type="text" size="100" name="descripcion" value="<?PHP echo $fila['descripcion']; ?>"></div> <br><br>
                <div class="izq">Proveedor: <input type="text" size="100" name="proveedor" value="<?PHP echo $fila['proveedor']; ?>"> </div><br><br>
                <div class="izq">Linea: <input type="text" name="linea" value="<?PHP echo $fila['linea']; ?>"> </div><br><br>
                <div class="izq">Período: <input type="text" name="periodo" value="<?PHP echo $fila['periodo']; ?>"> </div><br><br> 
                <div class="izq">Recepción: <input type="text" name="recepcion" value="<?PHP echo $fila['recepcion']; ?>"></div> <br><br>
                <div class="izq">Canalizado: <input type="text" size="100" name="canalizado" value="<?PHP echo $fila['canalizado']; ?>"> </div><br><br>
                <div class="izq">Dictaminador: <input type="text" name="dictaminador" value="<?PHP echo $fila['dictaminador']; ?>"></div> <br><br>
                <div class="izq">Observaciones: <input type="text" size="100" name="observacion" value="<?PHP echo $fila['observacion']; ?>"> </div><br><br>
                <input type='hidden' name='form_token' value="<?PHP echo $form_token; ?>" />

                <p class="boton"><input type="submit" value="Modificar" /></p>

           </form> 

		</div>
		
	    </div>
	    
            <div id="franja_inferior"></div>
            
            <div id="pie">
                Desarrollado por Sistemas y Procesos | Grupo Novem Sistemas de Agua
            </div>
            
        </div>
        
    </div>
</body>
</html>
