<html> 
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />    
<title>Login Sistema de Garantías</title>
<link rel="stylesheet" type= "text/css" href="css/login.css">
<link href="../scripts/css/ui-lightness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
<script  src="../scripts/js/jquery-1.9.1.js"></script>
<script  src="../scripts/js/jquery-ui-1.10.3.custom.min.js"></script>
<script type ="text/javascript" src="../scripts/jquery-1.10.1.js"></script>
<link rel="shortcut icon" href="favicon.ico" >

<script language="JavaScript">

  // var isChrome = window.chrome;
  // var ua = navigator.userAgent;
  //     if(isChrome||/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini|Mobile|mobile/i.test(ua)) {
  //        // is chrome 
  //     } else {
  //       alert("Por favor utiliza navegador Chrome");
  //       window.location.replace("https://www.google.es/chrome/browser/desktop/index.html");
  //     }

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

    return true;       
  }
</script>

<script>

$(document).ready(function(){
  
  $("input")
    .focus(function(){
      $(this).addClass("shadow");
    })

    .blur(function(){
      $(this).removeClass("shadow");
    })

  $("#phpro_username").focus()

})

</script>
<script src='https://www.google.com/recaptcha/api.js'></script>
</head> 

<body>
           
<div id="navegacion">
  <!--<div id="logout"><a href="http://sistemas">Inicio</a> </div>-->
</div>
  <div id="bienvenida"><h1>Bienvenidos al Sistema de Garantías</h1></div>
	<div id="encabezado"><h1>Ingresar aquí</h1></div> 
	<div id="forma"><form action="login_submit.php" method="post" onsubmit="return validarForma(this);" target="_top"> 
	    <div id="izquierda">
	    <p> <label for="phpro_username">Usuario</label></p>
	    <p><label for="phpro_password">Contraseña</label></p>
	    </div>
	    <div id="derecha">
	    <p><input class=box type="text" id="phpro_username" name="phpro_username" value="" maxlength="20" /></p>
	    <p><input class=box type="password" id="phpro_password" name="phpro_password" value="" maxlength="20" /></p>
	    </div>
	    <br>    
      	<div class="center g-recaptcha" data-sitekey="6LdqhBoTAAAAAKA_eMca-bhbBDltimFUPhRO2TWQ"></div>
      	<br>
      	<br>
	    <br>
	    <br>
	    <div id="abajo"><p> <input id=boton type="submit" value="Ingresar"/></p></div>
      <br>
      <div id="ligas">
        <div class="inline center"><a href="/registro.php">Registro inicial</a></div> 
        <div class="inline center"> <a href="/verificar.php">Verificar usaurio</a></div>
      </div>            
	 </form>
	</div>
<?php include_once 'contador.php'; ?>
</body>
</html> 