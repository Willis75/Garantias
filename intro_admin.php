<?php
header('Content-Type: text/html; charset=UTF-8');
include_once 'contador.php';
/*** begin the session ***/
session_start();

/*** set a form token ***/
$form_token = md5( uniqid('auth', true) );

/*** set the session form token ***/
$_SESSION['form_token'] = $form_token;


if(!isset($_SESSION['user_id'])||$_SESSION['gar_dictam']==0)
{
    header("Location:logout.php");
}

$user=$_SESSION['user_id'];
$linea=$_SESSION['linea'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="refresh" content="900" />
    <link rel="stylesheet" type= "text/css" href="css/styles_intro_admin.css">
    <title>Sistema de Garantías Novem</title>
    <link rel="shortcut icon" href="favicon.ico" >
</head>
<body>

<script type ="text/javascript" src="../scripts/jquery-1.10.1.js"></script>
   
<script>
    $(document).ready(function(){

	    $("input:radio[name='estatus']").change(function(){
	    	var datos = {
	      	"estatus" : $("input:radio[name='estatus']:checked").val(),
	      	"linea"	  : "<?PHP echo $linea ?>"
	    	};

	    	$.ajax({
	      		data:  datos,
	      		url:   'solicitudes.php',
	      		type:  'get',
	      		dataType: 'json',
	      		success:  function (data){
					console.log(data)
					$("#listado div").remove() 
					var i = 0;
					while (data[i]) {
						$("#listado").append("<div class='lista_tickets' onclick=\"window.location = 'coment_all.php?folio= "+data[i].folio+"';\">"+data[i].revisar+"<b>Folio: </b>"+data[i].folio+"<b> Sucursal:</b> "+data[i].sucursal+"<b> Fecha:</b> "+data[i].fecha+"<b> Línea:</b> "+data[i].linea+"<b> Producto:</b> "+data[i].PRODUCTO+" <b>"+data[i].TOP+"</b><br><b> Descripción:</b> "+data[i].DESCRIPCION+" <b> Dictaminador: </b>"+data[i].dictaminador+".</div>")
	      				i++;
	      			}
	      		}
	    	});
	    })

	    $("input:radio[name='estatus']").change()

		$("#listado")
			.on("mouseenter",".lista_tickets",function(){
		    	$(this).css("background-color","#D0D0D0");
		    })
			.on("mouseleave",".lista_tickets",function(){
		    	$(this).css("background-color","transparent");
			})
    });
</script>

    <div id="envoltorio" align="center">
        <div id="envoltorio_interno">
            
            <div id="encabezado">
                <img src="images/novem.jpg" alt="logo Novem" heigh="250" width="250"><br><br>                
                <div id="franja_superior"></div>
                
                <div class="clear"></div>
                
            </div>
	    
	    <div id="navegacion">
                <div id="cerrar_sesion"><a href="logout.php">Cerrar sesión</a></div> 
	    </div>
	    
	    <div id="cuerpo">
		
		<div id="tickets_abiertos">
		    <h2>Solicitudes de Garantías</h2>
		    <input type="radio" id="estatus" name ="estatus" checked value = 1>Abiertas
		    <input type="radio" id="estatus" name ="estatus" value = 0>Cerradas
		    <br>
		    <br>
		    <div id="listado"></div>
		    
			<div id="solicitudes_garantias"></div>
		    <div></div>
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
