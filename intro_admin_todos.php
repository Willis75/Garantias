<?php

header('Content-Type: text/html; charset=UTF-8');
/*** begin the session ***/
session_start();

if(!isset($_SESSION['user_id'])||$_SESSION['gar_master']==0)
{
    header("Location:logout.php");
}

$user=$_SESSION['user_id'];

?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="refresh" content="900" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Garant&#237as</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/intro.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


    <script type="text/javascript">

        $(document).ready(function() {
            $("td,th").css( "vertical-align", "middle" );
        });

    </script>

</head>

<body>

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href="#">Garantías</a>
          </div>
          <div>
            <ul class="nav navbar-nav">
              <li class="active"><a href="#">Resumen</a></li>
              <li><a href="excel.php">Excel</a></li>
              <li><a href="productos.php">Diagnóstico en Sucursal</a></li>
              <li><a href="clientesTop.php">Clientes TOP</a></li>
              <li><a href="canalizacion.php">Canalización</a></li>

            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
          </div>
        </div>
      </nav>

    <div class=".container-fluid">

        <div class="row">

            <div class="col-sm-12 columna">
            <div id="tickets_abiertos">
                <center><h2>Solicitudes de Garantía</h2></center>
                <?PHP

                    include 'conect.php';
                    $sucursales =  array();
                    $suc = $mysqli->query("SELECT Id, abrevia FROM sucursal ");
                    if (!$suc) {
                        printf("error: %s\n", $mysqli->error);
                        exit();
                    } else {
                        while($row = $suc->fetch_assoc()){
                            $sucursales[$row['Id']]=$row['abrevia'];
                        }
                    }

                    $suc->free();
                    $mysqli->close();

                include 'conect_gar.php';
                            
                            $sql="SELECT * , DATEDIFF(CURDATE(),s.fecha) difhoy, DATEDIFF(s.fecha_cierre, s.fecha) datedif ,s.file_ruta FROM solic s ORDER BY s.folio DESC";
                            
                            $resultado = $mysqli->query($sql);
                            if (!$resultado) {
                             printf("error: %s\n", $mysqli->error);
                                 exit();
                             }
                             else {
                                
                                echo "<form action='".$_SERVER['PHP_SELF']."' method='get' name='form_filter' >" ;
                                echo "<center><p><b>Estatus: </b><select name='estatus_folio'>";
                                echo "<option value=99> Todos </option>";
                                echo "<option value=1 selected> Abiertos </option>";
                                echo "<option value=0> Cerrados </option>";
                                echo "</select>";   
                                echo "<input class='btn btn-info' type='submit' value = 'Filtrar'></center>"
                                ,"</form>";

                                if (!isset($_GET['estatus_folio'])){
                                    $_GET['estatus_folio']=1;
                                }
                                
                                if (!isset($_GET['asignados'])){
                                    $_GET['asignados']=99;
                                }
                           
                                echo "<table class='table table-hover' border='1'>"
                                ,"<tr><th>Folio</h><th>Sucursal</th><th>Estatus</th><th>Inicio</th><th>Línea</th><th>Canalización</th><th>Revisión Sucursal</th><th>Fecha dictamen</th><th>Entrega dictamen</th><th>Entrega producto</th><th>Procede</th><th>Aplica</th><th>Cierre</th><th>Dias</th><th>Adjunto</th><th>Producto</th><th>Comentario</th><th>#_Serie</th><th>Causa falla</th></tr>";
                                    
                                 while($fila = $resultado->fetch_assoc()){
                                    
                                    if( $fila['file_ruta']!=""){
                                        $adjunto="Adjunto";
                                    }else{
                                        $adjunto="";
                                    }
                                    if ($fila['estatus']==1){
                                        $estatus_b="";
                                        $fecha_cierre="";
                                        $datedif=$fila['difhoy'];
                                    }else{
                                        $estatus_b="cerrado";
                                        $fecha_cierre= strtotime($fila['fecha_cierre']);
                                        $fecha_cierre=date('d/M/y',$fecha_cierre);
                                        $datedif=$fila['datedif'];
                                    }
                                    
                                    $fecha_inicio= date('d/M/y',strtotime('-6 hours',strtotime($fila['fecha'])));

                                    if ($fila['fecha_dictamen']!=null){
                                        $fecha_dictamen= strtotime($fila['fecha_dictamen']);
                                        $fecha_dictamen=date('d/M/y',$fecha_dictamen);
                                    } else {
                                        $fecha_dictamen="";
                                    }

                                    if ($fila['entrega_dictamen']!=null){
                                        $entrega_dictamen= strtotime($fila['entrega_dictamen']);
                                        $entrega_dictamen=date('d/M/y',$entrega_dictamen);
                                    } else {
                                        $entrega_dictamen="";
                                    }

                                    if ($fila['fecha_revision']!=null){
                                        $fecha_revision= strtotime($fila['fecha_revision']);
                                        $fecha_revision=date('d/M/y',$fecha_revision);
                                    } else {
                                        $fecha_revision="";
                                    }

                                    if ($fila['entrega_producto']!=null){
                                        $entrega_producto= strtotime($fila['entrega_producto']);
                                        $entrega_producto=date('d/M/y',$entrega_producto);
                                    } else {
                                        $entrega_producto="";
                                    }

                                    if ($fila['dictamen'] == 1){
                                        $procede="Si";
                                    }elseif ($fila['dictamen']==null){
                                        $procede="";
                                    } 
                                    elseif ($fila['dictamen']==0) {
                                        $procede="No";
                                    }

                                    if ($_GET['estatus_folio']==99) {
                                        echo "<tr><td><a href='coment_all.php?folio=".$fila['folio']."'>".$fila['folio']."</a></td> <td>".$sucursales[$fila['sucursal']]."</td> <td>".$estatus_b."</td> <td>".$fecha_inicio."</td> <td>".$fila['linea']."</td> <td>".utf8_encode($fila['canalizacion'])."</td> <td>".$fecha_revision."</td> <td>".$fecha_dictamen."</td> <td>".$entrega_dictamen."</td> <td>".$entrega_producto."</td> <td>".$procede."</td> <td>".$fila['aplica']."</td> <td>".$fecha_cierre."</td> <td>".$datedif."</td><td><a href='".$fila['file_ruta']."' target='_blank'>".$adjunto."</a></td><td>".$fila['PRODUCTO']."</td><td>".utf8_encode($fila['comentario_aplica'])."</td><td>".$fila['serie']."</td><td>".utf8_encode($fila['causa_falla'])."</td> <tr>";
                                    }
                                    elseif ($_GET['estatus_folio']==$fila['estatus']) {
                                        echo "<tr><td><a href='coment_all.php?folio=".$fila['folio']."'>".$fila['folio']."</a></td> <td>".$sucursales[$fila['sucursal']]."</td> <td>".$estatus_b."</td> <td>".$fecha_inicio."</td> <td>".$fila['linea']."</td> <td>".utf8_encode($fila['canalizacion'])."</td> <td>".$fecha_revision."</td> <td>".$fecha_dictamen."</td> <td>".$entrega_dictamen."</td> <td>".$entrega_producto."</td> <td>".$procede."</td> <td>".$fila['aplica']."</td> <td>".$fecha_cierre."</td> <td>".$datedif."</td><td><a href='".$fila['file_ruta']."' target='_blank'>".$adjunto."</a></td><td>".$fila['PRODUCTO']."</td><td>".utf8_encode($fila['comentario_aplica'])."</td><td>".$fila['serie']."</td><td>".utf8_encode($fila['causa_falla'])."</td> <tr>";
                                    }                                                                     
                    }
                                echo "</table>";
                } 
                $resultado->free();
                $mysqli->close();
                            unset($seleccion_asignados);
                            unset($seleccion_estatus);
                            unset($_GET['estatus_folio']);
                            unset($_GET['asignados']);
                ?>

            </div>

            </div>

        </div>

    </div>
<?php include_once 'contador.php'; ?>
</body>
</html>
