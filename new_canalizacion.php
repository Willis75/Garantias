<?php

header('Content-Type: text/html; charset=UTF-8');
/*** begin the session ***/
session_start();

$user=$_SESSION['user_id'];
$gar_master=$_SESSION['gar_master'];
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

<script>

    function validarForma(forma) {

    if(forma.codigo.value =="") {
     alert("Debe ingresar código");
     forma.codigo.focus();
     return false;
    }



  return true; 
  }

</script>

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

		    <h2>Nuevo registro de canalización</h2>
            
            <form action="add_new_canalizacion.php" method="post" enctype="multipart/form-data" onsubmit="return validarForma(this);" target="_top">

                <div class="izq">Código: <input type="text" name="codigo"></div> <br><br>
                <div class="izq">Descripción: <input type="text" size="100" name="descripcion" ></div> <br><br>
                <div class="izq">Proveedor: <input type="text" size="100" name="proveedor" > </div><br><br>
                <div class="izq">Linea: 
                    <select name="linea">
                        <option value="PIS">PIS</option> 
                        <option value="PQU">PQU</option>
                        <option value="SAC">SAC</option>
                        <option value="SBO">SBO</option>
                        <option value="TDA">TDA</option>
                    </select> <br><br><br>
                <div class="izq">Período: <input type="text" name="periodo" > </div><br><br> 
                <div class="izq">Recepción: <input type="text" name="recepcion" ></div> <br><br>
                <div class="izq">Canalizado: <input type="text" size="100" name="canalizado" > </div><br><br>
                <div class="izq">Dictaminador: <input type="text" name="dictaminador" ></div> <br><br>
                <div class="izq">Observaciones: <input type="text" size="100" name="observacion" > </div><br><br>
                <input type='hidden' name='form_token' value="<?PHP echo $form_token; ?>" />

                <div class="center"> <p class="boton"><input type="submit" value="Agregar" /></p> </div> <br><br><br>

           </form> 

		</div>
		
	    </div>
	    
            <div id="franja_inferior"></div>
            
            <div id="pie">
                Desarrollado por Sistemas y Procesos | Grupo Novem Sistemas de Agua
            </div>
            
        </div>
        
    </div>
<?php include_once 'contador.php'; ?>
</body>
</html>
