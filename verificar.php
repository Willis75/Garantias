<html> 
<head>
<link rel="shortcut icon" href="favicon.ico" >    
<meta http-equiv="X-UA-Compatible" content="IE=edge" />    
<title>Sistema de Tickets</title>
<link rel="stylesheet" type= "text/css" href="css/login.css">

<script type="text/javascript">
function FocusOnInput()
{
     document.getElementById("phpro_username").focus();
}
</script>

<script language="JavaScript">

  function validarForma(forma) {
   /* Valida el usuario */
   if(forma.phpro_username.value == "") {
     alert("Debe especificar su usuario");
     forma.phpro_username.focus();
     return false;
   }

   /* Valida la contraseña */
   if(forma.phpro_password.value == "") {
     alert("Debe ingresar su contraseña");
     forma.phpro_password.focus();
     return false;
   }
   
   if(forma.verificacion.value == "") {
     alert("Debe ingresar clave");
     forma.verificacion.focus();
     return false;
   }

    return true;       
  }
</script>

</head> 

<body onload="FocusOnInput()">

<?php
    include 'encabezado.php';
?>

<div id="navegacion">
  <div id="volver"><a href="login.php">Volver</a> </div>
</div>

	<div id="bienvenida"><h1>Bienvenidos al Sistema de Garantías</h1></div>
	<div id="encabezado"><h1>Verificar usuario</h1></div> 
	<div id="forma_v"><form action="verificar_submit.php" method="post" onsubmit="return validarForma(this);" target="_top"> 
	    <div id="izquierda">
	    <p> <label for="phpro_username">Usuario</label></p>
	    <p><label for="phpro_password">Contraseña</label></p>
            <p><label for="verificacion">Clave recibida</label></p>
	    </div>
	    <div id="derecha">
	    <p><input class=box type="text" id="phpro_username" name="phpro_username" value="" maxlength="20" /></p>
	    <p><input class=box type="password" id="phpro_password" name="phpro_password" value="" maxlength="20" /></p>
            <p><input class=box type="text" id="verificacion" name="verificacion" value="" maxlength="3" size="3" /></p>
	    </div>
	    <div id="abajo"><p> <input id="boton" type="submit" value="Verificar"/></p></div>
            <br>
	 </form>
	</div>

  <?php
      include_once 'contador.php';
  ?>

</body> 
 </html> 