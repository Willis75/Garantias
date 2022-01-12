<?php
header('Content-Type: text/html; charset=utf-8');


?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Sistema de Garant&#237as</title>
  <link rel="stylesheet" type= "text/css" href="css/styles_index.css">
	<link href="../scripts/css/ui-lightness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
	<script  src="../scripts/js/jquery-1.9.1.js"></script>
	<script  src="../scripts/js/jquery-ui-1.10.3.custom.min.js"></script>
  <script type ="text/javascript" src="../scripts/jquery-1.10.1.js"></script>
        
<script type="text/javascript">


</script>

</head>

<body>
  <div id="envoltorio" align="center">

      <div id="encabezado">
        <img src="images/novem.jpg" alt="logo Novem" heigh="250" width="250"><br><br>                
        <div id="franja_superior"></div>
        <div class="clear"></div>
      </div>

      <div id="navegacion">
        <div id="logout"><a href="#"></a> </div>
      </div>

      <div id="cuerpo">
        <br>
        <div id = "formato">
          <form action="add_dictaminador.php" method="post" enctype="multipart/form-data" target="_top">
            <div class="label"><label for="file">Usuario</label></div> <div id="exito"></div>
            <div id='contenido_usuario'><input type="text" id="usaurio" name="usuario" placeholder = 'Ingresar el usuario' maxlength="30" /></div><br>
            <div class="label" id="adjunto"><label for="file">Archivo adjunto</label></div>
            <div class="content"><input type="file" id="file" name="file" value="" maxlength="20" /></div><br>
            <input type='submit' id='boton_enviar' value="Enviar" /></p>
          </form>   
        </div> 
      </div>
    
      <div id="franja_inferior"></div>

      <div id="pie"> Desarrollado por Sistemas y Procesos | Grupo Novem Sistemas de Agua</div>
  
  </div>
</body>
</html>
