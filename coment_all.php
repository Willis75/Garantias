<?php
header('Content-Type: text/html; charset=utf-8');

/*** begin the session ***/
session_start();

$folio=$_GET["folio"];
/*** set a form token ***/
$form_token = md5( uniqid('auth', true) );

/*** set the session form token ***/
$_SESSION['form_token'] = $form_token;


if(!isset($_SESSION['user_id']))
{
    header("Location:logout.php");
}

$user=$_SESSION['user_id'];

include 'conect.php';

$sucursales = array();
$suc="SELECT Id, abrevia FROM sucursal";
$Qsuc= $mysqli->query($suc);
if (!$Qsuc) {
    printf("error: %s\n", $mysqli->error);
    exit();
}else{
    while ($row = $Qsuc->fetch_assoc()){
        $sucursales[$row['Id']] = $row['abrevia'];
    }
}

$linea=$mysqli->query("SELECT email, gar_master, gar_dictam, gar_admin, gar_jo FROM asoc WHERE usuario ='$user'");
if ( false==$linea) {
    printf("error: %s\n", mysqli_error($mysqli));
}else{
    $asoc= $linea->fetch_assoc();
}

$mysqli->close();

include 'conect_gar.php';

$datos="SELECT * FROM solic WHERE folio = $folio";
$Qdatos= $mysqli->query($datos);
if (!$Qdatos) {
    printf("error: %s\n", $mysqli->error);
    exit();
} else {
    $insert= $Qdatos->fetch_assoc();
}

$mysqli->close();

if ($asoc['gar_master']==1){
    $liga='intro_admin_todos.php';
}elseif ($asoc['gar_dictam']==1){
    $liga='intro_admin.php';;
} else {
    $liga='intro_admin_general.php';
}

?>

<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema de Garant&#237as</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/coment.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  
<script type="text/javascript">

$(document).ready(function(){

    $("input, TEXTAREA")
    .focus(function(){
        $(this).addClass("shadow");
    })

    .blur(function(){
        $(this).removeClass("shadow");
    })

    $("#l_aplica").hide();
    $("#div_otro").hide();
    $("#div_reparacion").hide();
    $("#div_refaccion").hide();

        $("#enviar_comentario").click(function(event){
            console.log("click");
            console.log($("#coment").val());
            if($("#coment").val()==""){
                alert("Hay que escribir un comentario");
                $("#coment").focus();
                return false;
            }
                return true;
        });

        $("#enviar_dictamen").click(function(event){
            
            if($('input[name=procede]:checked').length == 0){
                alert("Se debe especificar si procede o no garant\u00cda");
                return false;
            }

            if($('input[name=procede]:checked').val() == 1){
                if($("select[name=aplica]").val()==99){
                    alert("Debe especificar lo que aplica");
                    $("select[name=aplica]").focus();
                    return false;
                }
            }        

            if($("#causa_falla").val()==""){
                alert("Hay que escribir la causa de la falla");
                $("#causa_falla").focus();
                return false;
            }    

            if($("#comentarios_dictamen").val()==""){
                alert("Hay que escribir un comentario");
                $("#comentarios_dictamen").focus();
                return false;
            }
                return true;
        });  

	   $(".lista_coments").mouseenter(function(){
	       $(this).css("background-color","#D0D0D0");
	   });

	   $(".lista_coments").mouseleave(function(){
	       $(this).css("background-color","transparent");
	   });

        $("#entregaDictamen").click(function(){
            var datos = {
                "folio" : $("#folio2").val(),
            };
            $.ajax({
              data:  datos,
              url:   'entregaDictamen.php',
              type:  'get',
                success:  function (response) {
                    $("#entregaDictamen").remove();
                }
              });
        });

        $("#cerrar_folio").click(function(){
            var datos = {
                "folio" : $("#folio2").val(),
                "info"  : $("input:radio[name='info']:checked").val()
            };
            $.ajax({
              data:  datos,
              url:   'cerrarFolio.php',
              type:  'post',
                success:  function (response) {
                    location.reload();
                }
              });
        });

	$("#cerrar").mouseenter(function(){
	    $(this).css("background-color","#D0D0D0");
	});
	$("#cerrar").mouseleave(function(){
	    $(this).css("background-color","transparent");
	});

    $("select[name='aplica']").change(function(){

        $("#div_otro").hide();
        $("#div_reparacion").hide();
        $("#div_refaccion").hide();

        if($("select[name='aplica']").val()=="Reparacion"){
            $("#div_reparacion").show();
        }

        if($("select[name='aplica']").val()=="Refaccion"){
            $("#div_refaccion").show();
        }

        if($("select[name='aplica']").val()=="Otro"){
            $("#div_otro").show();
        }
    });

    $("input:radio[name='procede']").change(function(){
      if($("input:radio[name='procede']:checked").val()==0){
        $("#l_aplica").hide();
        $("select[name='aplica'] option[value=99]").attr('selected', true); 
        $("#div_otro").hide();
        $("#div_reparacion").hide();
        $("#div_refaccion").hide();
        } else {
        $("#l_aplica").show();
        } 
    });

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
          <li><a href="<?php echo $liga; ?>">Inicio</a></li>
          <li class="active"><a href="#">Detalles</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </div>
</nav>

<div class=".container-fluid">
    <div class="row">
        <div class="col-sm-4 columna">
	    
            <div id="nvo_coment">
                    
                <div id="comentario">

                   <?PHP
                    if($asoc['gar_dictam']==1 && $insert['fecha_dictamen'] == NULL && $insert['TOP'] != 1 && $insert['dic_suc'] != 1 ){
                        echo "<br><div id='dictamen'>
                                <form action='add_dictamen.php' method='post' enctype='multipart/form-data'>
                                    <h2>Información del dictamen: </h2> <br>
                                    <div>
                                        <label>Procede garantía:  </label>
                                        <input type='radio' name='procede' value=1>Si 
                                        <input type='radio' name='procede' value=0>No
                                    </div>

                                    <div id='l_aplica'>
                                    <br>
                                        <label>Aplica:  </label>                         
                                        <select name='aplica'>
                                            <option value=99>Seleccionar</option>
                                            <option value='Refaccion'>Se entrega refacción</option>
                                            <option value='Reparacion'>Reparación</option>
                                            <option value='Cambio'>Cambio por nuevo</option>
                                            <option value='Otro'>Otro</option>
                                        </select>
                                        <br>
                                    </div>

                                    <div id='div_refaccion'>
                                        <label>Comentario refacción: </label>
                                        <input type='text' id='comentario_refaccion' name='comentario_refaccion' title='Comentario de refacción' size='60' />
                                    </div>

                                    <div id='div_reparacion'>
                                        <label>Comentario reparación: </label>
                                        <input type='text' id='comentario_reparacion' name='comentario_reparacion' title='Comentario de reparación' size='60' />
                                    </div>

                                    <div id='div_otro'>
                                        <label>Comentar otro: </label>
                                        <input type='text' id='otro' name='otro' title='Comenta otro' size='60' />
                                    </div>
                                        <br>
                                    <div>
                                        <label>Causa de la falla: </label>
                                        <br><TEXTAREA id='causa_falla' name='causa_falla' cols=60 rows=5 title='Ingresar la causa de la falla'></TEXTAREA>
                                    </div>                                        
                                    <div>
                                        <label>Comentarios del dictamen y recomendaciones: </label>
                                        <br><TEXTAREA id='comentarios_dictamen' name='comentarios_dictamen' cols=60 rows=5 title='Ingresar comentarios del dictamen'></TEXTAREA>
                                    </div>
                                    <br>

                                    <input type='hidden' id='usuario' name='usuario' value='".$user."' maxlength='20' />
                                    <input type='hidden' name='form_token' value='".$form_token."'' />
                                    <input type='hidden' name='folio' value='".$folio."'/>
                                    <input class='btn btn-info' id='enviar_dictamen' type='submit' value='Enviar' />
                                </form>
                            </div>";
                    }

                    if($insert['estatus']==1){
                        echo "<center><h2>Ingresar nuevo comentario</h2></center>
                        <form action='add_coment.php' method='post' enctype='multipart/form-data'>
                            <p>
                            <TEXTAREA id='coment' NAME='coment' COLS=60 ROWS=6></TEXTAREA>
                            </p>
                            <input type='hidden' id='usuario' name='usuario' value='".$user."' maxlength='20' />
                            <input type='hidden' name='form_token' value='".$form_token."' />
                            <input type='hidden' name='folio' value='".$folio."'/>
                            <input class='btn btn-info' id='enviar_comentario' type='submit' value='Enviar' />
                        </form>";
                    }

                    ?>
                </div>


                <div id="datos">
                    <?PHP
                        if ($insert['fecha_dictamen'] != NULL){

                            echo "<h2>Información del dictamen</h2>";

                            if ($insert['dictamen']==1){
                                $procede = "Si";
                            }else{
                                $procede = "No";
                            }

                            echo "<label>Procede garantía: </label>
                                <input readonly type='text' id='procede' name='procede' size='2' value='".$procede."' />
                                <br><br>
                                
                                <label>Causa de la falla: </label><br>
                                <TEXTAREA readonly id='causa_falla2' NAME='causa_falla2' COLS=60 ROWS=5>".utf8_encode($insert['causa_falla'])."</TEXTAREA>
                                <br><br>

                                <label>Comentarios del dictamen y recomendaciones: </label><br>
                                <TEXTAREA readonly id='comentarios_dictamen2' NAME='comentarios_dictamen2' COLS=60 ROWS=5>".utf8_encode($insert['comentario_dictamen'])."</TEXTAREA>
                                <br><br>

                                ";

                            if($insert['dictamen']==1){

                                echo "<label>Aplica: </label>
                                    <input readonly type='text' id='aplica' name='aplica' size='25' value='".$insert['aplica']."' />
                                    <br><br>

                                    <label>Comentarios de lo que aplica: </label><br>
                                    <TEXTAREA readonly id='comentarios_aplica2' NAME='comentarios_aplica2' COLS=60 ROWS=5>".utf8_encode($insert['comentario_aplica'])."</TEXTAREA>
                                    <br><br>";
                            }
                        }

                        if($insert['fecha_revision'] != NULL){
                            echo "<br><div id='comentario_revision'>
                                    <h2>Comentario revision</h2>(Suc / Taller): <br>
                                    <TEXTAREA readonly id='revision' NAME='revision' COLS=60 ROWS=5>".utf8_encode($insert['comentario_revision'])."</TEXTAREA>
                                    </div>";
                        }

                        if ($insert['entrega_dictamen'] != NULL){
                            $date= strtotime($insert['entrega_dictamen']);
                            $date=date('d/M/y g:ia',$date);                                
                            echo "<br><div> 
                                <div> Entrega dictamen:  <input readonly='readonly' class='texto' type='text' id='entrega_dictamen' name='entrega_dictamen' value='".$date."'  /> </div>
                                </div>";
                        }


                        if ($insert['entrega_producto'] != NULL){
                            $date= strtotime($insert['entrega_producto']);
                            $date=date('d/M/y g:ia',$date);
                            echo "<br><div> 
                                <div> Entrega producto:  <input readonly='readonly' class='texto' type='text' id='entrega_dictamen' name='entrega_dictamen' value='".$date."'  /> </div>
                                </div>";
                        }  
                    ?>

                    <center><h2>Datos del folio</h2></center>
                        
                        <?PHP

                        if($insert['estatus']==1){
                            $estatus="Abierto";
                        }else{
                            $estatus="Cerrado";
                        }

                        if ($insert['recibe'] == 1){
                            $recibe = "Si";
                        } else {
                            $recibe = "No";
                        }

                        if ($insert['lugar'] == 1){
                            $diag_suc = "Si";
                        } else {
                            $diag_suc = "No";
                        }

                        if ($insert['info'] == 1){
                            $falta_info = "Si";
                        } elseif ($insert['info'] == 2) {
                            $falta_info = "No";
                        } elseif ($insert['info'] == NULL){
                            $falta_info = "";
                        }

                        if ($insert['TOP']==1){
                            $top2="Si";
                        } else {
                            $top2=NULL;
                        }
                                                                                            
                        echo "<div> 
                            <label> Folio: </label> <div> <input readonly='readonly' class='texto' type='text' id='folio2' name='folio2' value='".$insert['folio']."' size='4' /> </div>
                        </div>
                        <div>
                            <label> Sucursal: </label>  
                            <div> <input readonly='readonly' class='texto' type='text' id='sucursal' name='sucursal' value='".$sucursales[$insert['sucursal']]."'size='4' /></div>
                        </div>
                        <div>
                            <label> No. Cliente: </label>  
                            <div><input readonly='readonly' class='texto' type='text' id='No_cliente' name='No_cliente' value='".$insert['CUSTOMER_NUMBER']."'size='60' /></div>
                        </div>
                        <div>
                            <label> Cliente: </label>
                            <div>
                                <TEXTAREA readonly='readonly' class='texto' id='cliente' name='cliente' cols=60 rows=2 >".utf8_encode($insert['CUSTOMER_NAME'])." </TEXTAREA>
                            </div>
                        </div>
                        <div>
                            <label>TOP: </label>
                            <div>
                                <input readonly='readonly' type='text' id='top2' name='top2' value='".$top2."'size='2' />
                            </div>
                        </div>                            
                        <div>
                            <label> Contacto: </label>
                            <div> <input readonly='readonly' class='texto' type='text' id='contacto' name='contacto' value='".$insert['contacto']."'size='60' /></div>
                        </div>
                        <div>
                            <label> Email: </label>
                            <div> <input readonly='readonly' class='texto' type='text' id='email' name='email' value='".utf8_encode($insert['email'])."'size='60' /></div>
                        </div>
                        <br>
                        <div>
                            <label> Factura: </label>
                            <div>  <input readonly='readonly' class='texto' type='text' id='factura' name='factura' value='".$insert['factura']."'size='10' /></div>
                        </div>
                        <div>
                            <label> Fecha factura: </label>
                            <div><input readonly='readonly' class='texto' type='text' id='fecha_factura' name='fecha_factura' value='".$insert['fecha_factura']."'size='10' /></div>
                        </div>
                        <div>
                            <label> Linea: </label>
                            <div> <input readonly='readonly' class='texto' type='text' id='linea' name='linea' value='".$insert['linea']."'size='3' /></div>
                        </div>
                        <div>    
                            <label> Producto: </label>
                            <div> <input readonly='readonly' class='texto' type='text' id='producto' name='producto' value='".$insert['PRODUCTO']."'size='60' /></div>
                        </div>
                        <div>
                            <label> Descripción: </label>
                            <div> <TEXTAREA readonly='readonly' class='texto' id='descripcion' name='descripcion' cols=60 rows=3 >".utf8_encode($insert['DESCRIPCION'])." </TEXTAREA></div>
                        </div> 
                        <div>
                            <label> Serie: </label>
                            <div>  <input readonly='readonly' class='texto' type='text' id='serie' name='serie' value='".$insert['serie']."'size='60' /></div>
                        </div> 
                        <div>
                            <label> Falla: </label>
                            <div>
                                <TEXTAREA readonly='readonly' class='texto' id='falla' name='falla' cols=60 rows=5 >".utf8_encode($insert['falla'])." </TEXTAREA>
                            </div>
                        </div>
                        <div>
                            <label> Se recibe producto: </label>
                            <div> <input readonly='readonly' class='texto' type='text' id='sucursal' name='sucursal' value='".$recibe."'size='2' /></div>
                        </div>
                        <div>
                            <label> Diag en Sucursal: </label>
                            <div> <input readonly='readonly' class='texto' type='text' id='diag_suc' name='diag_suc' value='".$diag_suc."'size='2' /></div>
                        </div>
                        <div>
                            <label> Se canaliza a: </label>
                            <div> <input readonly='readonly' class='texto' type='text' id='canalizacion' name='canalizacion' value='".utf8_encode($insert['canalizacion'])."'size='60' /></div>
                        </div>
                        <div id='comentarios_canalizacion'>
                            <label> Detalles: </label>
                            <div> <input readonly='readonly' class='texto' type='text' id='canalizacion_otro' name='canalizacion_otro' value='".utf8_encode($insert['canalizacion_otro'])."'size='60' /></div>
                        </div>
                        <div>
                            <label> Dictaminador: </label>
                            <div> <input readonly='readonly' class='texto' type='text' id='dictaminador' name='dictaminador' value='".utf8_encode($insert['dictaminador'])."'size='60' /></div>
                        </div>

                        <div>
                            <label> Estatus: </label>
                            <div> <input readonly='readonly' class='texto' type='text' id='estatus2' name='estatus2' value='".$estatus."'size='60' /></div>
                        </div>

                        <div>
                            <label> Cerrado por falta de información: </label>
                            <div> <input readonly='readonly' class='texto' type='text' id='info2' name='info2' value='".$falta_info."'size='60' /></div>
                        </div>

                        "      ;
                         
                        if(strlen($insert['file_ruta'])>0){
                            echo "<br><br><div class='inline'><input type='button'class='btn btn-info' id='ver_adjunto' onclick=\"Javascript:window.open('".$insert['file_ruta']."')\" value='Ver adjunto' /></div>";
                        }

                        if($insert['entrega_dictamen'] == NULL && $insert['fecha_dictamen'] != NULL && $asoc['gar_admin'] == 1){
                          echo "<br><br><div class='inline'><div class='btn btn-info' id='entregaDictamen' >Dictamen entregado</div></div>";
                        }  

                        if($asoc['gar_master'] == 1 && $insert['estatus']==1){
                            echo "<br><br><div id = 'cierre_block'> <br><br><label for='info'>Falta de información:</label>
                                    <input type='radio' name='info' value=1>Si
                                    <input type='radio' name='info' value=2 checked>No";
                            echo "<br><br><div class='inline'><input type = 'button' class='btn btn-info' id='cerrar_folio' value='Cerrar folio' /></div></div>";
                        } 

                            
                        ?>
                    

                </div>
            </div>
        </div> <!-- Columna -->

    <div class="col-sm-1">
    </div>

    <div class="col-sm-5 columna">
    	<div id="comentarios">
    	    <center><h2>Comentarios</h2></center>
    	    <?PHP

            include 'conect_gar.php';

    		$resultado = $mysqli->query("SELECT c.Id, c.num_sol, c.comentario, c.de, c.fecha FROM  solic s, coment c WHERE c.num_sol = $folio AND s.folio = $folio ORDER BY c.Id DESC" );
    		if (!$resultado) {
    		    printf("error: %s\n", $mysqli->error);
    		    exit();
    		}
    		else {
    		    //echo 'done.';
    		    while($fila = $resultado->fetch_assoc()){
                    $date= date('d/M/y g:ia',strtotime('-6 hours',strtotime($fila['fecha'])));
    				echo "<div class='lista_coments'><b>Fecha</b> ".$date." <b>De:</b> ".$fila['de']." <b>Comentario:</b> ".utf8_encode($fila['comentario'])."</div>"; 
    		    }
    		} 
    		$resultado->free();
            $Qdatos->free();
            $Qsuc->free();
            $linea->free();
    		$mysqli->close();
                    ?>
    	    <div></div>
    	</div>
	</div> <!-- Columna -->

</div> <!-- Row -->
</div> <!-- Container fluid -->
</body>
</html>
