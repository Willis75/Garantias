<?PHP

function makeRandomString($max) {
$i = 0; //Reset the counter.
$possible_keys = "23456789abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ";
$keys_length = strlen($possible_keys);
$str = ""; //Let's declare the string, to add later.
while($i<$max) {
    $rand = mt_rand(1,$keys_length-1);
    $str.= $possible_keys[$rand];
    $i++;
}
return $str;
}

session_start();

include 'conect.php';


$result = $mysqli->query("SELECT Id, sucursal FROM sucursal ORDER BY sucursal ASC");

$verificador=makeRandomString(3);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge; charset=UTF-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type= "text/css" href="css/registro.css">
        <link rel="shortcut icon" href="favicon.ico" >
        <title>Registro</title>
        
        <script type="text/javascript">
        function FocusOnInput()
        {
            document.getElementById("nombre").focus();
        }
        </script>

<script language="JavaScript">

  function fill(){
    document.getElementById('correo1').innerHTML=document.getElementById('usuario').value.toLowerCase();
    document.getElementById('usuario').value=document.getElementById('usuario').value.toLowerCase();
    }
  
  function validarForma(forma) {
   /* Valida la clasificacion */
   if(forma.usuario.value =="") {
     alert("Debe especificar usuario");
     forma.usuario.focus();
     return false;
   }

   /* Valida la descripcion */
   if(forma.pass.value =="") {
     alert("Debe escribir una contraseña");
     forma.pass.focus();
     return false;
   }

  if(forma.validar.value =="") {
     alert("Debe confirmar la contraseña");
     forma.validar.focus();
     return false;
   }
         
   if(forma.nombre.value =="") {
     alert("Debe escribir su nombre");
     forma.nombre.focus();
     return false;
   }
    
   if(forma.dominio.value==99){
     alert("Debe seleccionar dominio");
     forma.dominio.focus();
     return false;
   }
       
   if(forma.validar.value =="") {
     alert("Debe confirmar la contraseña");
     forma.validar.focus();
     return false;
   }
        
   if(forma.puesto.value =="") {
     alert("Debe ingresar su puesto");
     forma.puesto.focus();
     return false;
   }
   
   if(forma.sucursal.value ==99) {
     alert("Debe seleccionar sucursal");
     forma.sucursal.focus();
     return false;
   }
   
   if(isNaN(forma.lada.value)==true) {
     alert("La lada debe ser un número");
     forma.lada.focus();
     return false;
   }
   
   if(isNaN(forma.telefono.value)==true) {
     alert("Teléfono solo acepta números");
     forma.telefono.focus();
     return false;      
  }
  
   if(isNaN(forma.ext.value)==true) {
     alert("La extensión solo acepta números");
     forma.ext.focus();
     return false;      
  }
   
   if(forma.telefono.value ==""&&forma.ext.value =="") {
     alert("Debe ingresar teléfono o extensión");
     forma.telefono.focus();
     return false;
   }
   
   if(forma.ip.value =="") {
     alert("Debe escribir dirección IP");
     forma.ip.focus();
     return false;
   }
   
   if(forma.pass.value != forma.validar.value) {
     alert("La contraseña no coincide, favor de volver a intentar");
     forma.pass.value="";
     forma.validar.value="";
     forma.pass.focus();
     return false;
   }
   
    return true;       
  }
  
</script>

</head> 
    
<body onload="FocusOnInput()">
  
      <div id="navegacion">
        <div id="volver"><a href="login.php">Volver</a> </div>
      </div>

        <div id="titulo"><h2>Formato de registro</h2></div>
        <div id="formato"
                <div id="datos">
                    <form action="add_user.php" method="post" enctype="multipart/form-data" onsubmit="return validarForma(this);" target="_top">
                        <p> <div class="etiqueta">Nombre:</div><div class="campo"> <input type="text" id="nombre" name="nombre" value="" maxlength="30" size="20" /> </div><p>
                        <p><div class="etiqueta">Usuario:</div><div class="campo"> <input type="text" id="usuario" name="usuario" value="" maxlength="30" size="20" onblur="fill()" /> Utiliza tu usuario de red</div></p>
                        <p><div class="etiqueta">Contraseña:</div><div class="campo"> <input type="password" id="pass" name="pass" value="" maxlength="30" size="20" /> </div></p>
                        <p><div class="etiqueta">Repetir contraseña:</div><div class="campo"> <input type="password" id="validar" name="validar" value="" maxlength="30" size="20" /> </div></p>
                        <p><div class="etiqueta">Email:</div>
                            <div class="campo"> 
                                <label for="correo1" id="correo1"></label>
                                <select name="dominio">
                                    <option value="99">Seleccionar</option>
                                    <option value="@industriasvertex.com.mx">@industriasvertex.com.mx</option>
                                    <option value="@ivertex.com.mx">@ivertex.com.mx</option>
                                    <option value="@brunnen.com.mx">@brunnen.com.mx</option>
                                    <option value="@cresco.com.mx">@cresco.com.mx</option>
                                    <option value="@emmsa.com.mx">@emmsa.com.mx</option>
                                    <option value="@nascor.com.mx">@nascor.com.mx</option>
                                    <option value="@novem.com.mx">@novem.com.mx</option>
                                    <option value="@padmont.com.mx">@padmont.com.mx</option>
                                    <option value="@salcodrip.com">@salcodrip.com</option>
                                    <option value="@soporteg.com.mx">@soporteg.com.mx</option>
                                </select>
                        </div></p>
                        <p><div class="etiqueta">Puesto:</div><div class="campo"> <input type="text" id="puesto" name="puesto" value="" maxlength="30" size="20" /> </div></p>        
                        <p><div class="etiqueta">Sucursal:</div>
                        <div class="campo"><?PHP
                        echo "<select name='sucursal' >";
                        echo "<option value='99'>Seleccionar</option>";
                        while ($row = $result->fetch_assoc())
                        {
                            echo "<option value='" . $row['Id'] . "'>" . $row['sucursal'] . "</option>";
                        }
                        echo "</select>";
                        $result->free();
                        $mysqli->close();
                        ?> </div> </a>
                         <p><div class="etiqueta">Telefono:</div><div class="campo"> <div class="campo"> (<input type="text" id="lada" name="lada" value="" maxlength="3" size="3" />)</div><input type="text" id="telefono" name="telefono" value="" maxlength="8" size="8" /> </div></p>
                         <p><div class="etiqueta">Extensión:</div><div class="campo"> <input type="text" id="ext" name="ext" value="" maxlength="4" size="4" /> </div></p>
                         <p><div class="etiqueta">Dirección IP:</div><div class="campo"> <input type="text" id="ip" name="ip" value="" maxlength="20"  size="20"/> </div></p>
                         
                         <input type="hidden" name="verificador" value="<?PHP echo $verificador; ?>">
                         <p><div class="submit"><input type="submit" value="Enviar" /></div></p>
                    </form>
                </div> 
        </div>
    </body>
</html>