<?php

header('Content-Type: text/html; charset=UTF-8');
/*** begin the session ***/
session_start();

if(!isset($_SESSION['user_id']))
{
    header("Location:logout.php");
}

$user=$_SESSION['user_id'];
$gar_master=$_SESSION['gar_master'];

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
        
    function validar(codigo){
        var r = confirm("¿Desea borrar el registro?");
        if (r == true) {
            window.location.href = "delete_canalizacion.php?codigo="+codigo;
        } else {
            return false;
        }
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
            <?PHP 
                if ($gar_master==1){
                    echo "<div class='navegacion'><a href='intro_admin_todos.php'>Regresar</a></div> <div class='navegacion'><a href='new_canalizacion.php'>Agregar</a></div>";
                } else {
                    echo "<div class='navegacion'><a href='index.php'>Regresar</a></div>";
                }
            ?>
            
            <div class="navegacion"><a href="logout.php">Cerrar sesión</a></div>
            

        </div>
	    
	    <div id="cuerpo">
		
		<div id="tickets_abiertos">
		    <h2>Canalización</h2>
		    <?PHP

		    include 'conect_gar.php';
                        
                $sql="SELECT *  FROM canalizacion ORDER BY codigo ASC";
                
                $resultado = $mysqli->query($sql);
                if (!$resultado) {
                 printf("error: %s\n", $mysqli->error);
                     exit();
                 }else {
                    echo "<table border='1'><tr><th>Código</h><th>Descripción</th><th>Línea</th><th>Período</th><th>Recepción</th><th>Canalización</th><th>Dictaminador</th><th>Observaciones</th></tr>";
    			    while($fila = $resultado->fetch_assoc()){
                        if ($gar_master ==1) {
                            echo "<tr><td><a href='edit_canalizacion.php?codigo=".$fila['codigo']."'>".$fila['codigo']."</td> <td>".$fila['descripcion']."</td> <td>".$fila['linea']."</td> <td>".$fila['periodo']."</td> <td>".$fila['recepcion']."</td> <td>".$fila['canalizado']."</td> <td>".$fila['dictaminador']."</td> <td>".$fila['observacion']."</td> <td><input type='button' value='Borrar' onclick='validar(".$fila['codigo'].")'</td></div> <tr>";
                        } else {
                            echo "<tr><td>".$fila['codigo']."</td> <td>".$fila['descripcion']."</td> <td>".$fila['linea']."</td> <td>".$fila['periodo']."</td> <td>".$fila['recepcion']."</td> <td>".$fila['canalizado']."</td> <td>".$fila['dictaminador']."</td> <td>".$fila['observacion']."</td> <tr>";
                        }
                    }
                }
                    echo "</table>";
			$resultado->free();
			$mysqli->close();

		    ?>
		    <div></div>

		</div>
		
	    </div>
	    
        </div>
        
    </div>
</body>
</html>
