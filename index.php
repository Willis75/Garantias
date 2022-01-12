<?php
header('Content-Type: text/html; charset=utf-8');

/*** begin the session ***/
session_start();

/*** set a form token ***/
$form_token = md5( uniqid('auth', true) );

/*** set the session form token ***/
$_SESSION['form_token'] = $form_token;

if(!isset($_SESSION['user_id']) || $_SESSION['sistema'] != 'garantias' || ($_SESSION['gar_master']==0 && $_SESSION['gar_dictam']==0 && $_SESSION['gar_admin']==0 && $_SESSION['gar_jo']==0)){
    header("Location:logout.php");
} 

$usuario=$_SESSION['user_id'];

include 'conect.php';

  $resultado = $mysqli->query("SELECT sucursal, email, gar_master, gar_dictam, gar_admin, gar_jo FROM asoc WHERE usuario = '$usuario' ");
  if (!$resultado) {
      printf("error: %s\n", $mysqli->error);
      exit();
  }
  else {
    $linea = $resultado->fetch_assoc();
    $sucursal = $linea['sucursal'];
    $_SESSION['sucursal']=$sucursal;
  }

$mysqli->close();

include 'conect_gar.php';
?>

<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sistema de Garant&#237as</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="scripts/jquery.maskedinput.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/index.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<script type="text/javascript">

$(document).ready(function() {

	function isEmail(email) {
	  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	  return regex.test(email);
	}

	function alertMail(email){
		if(isEmail(email)){
			$("#check").removeClass("glyphicon glyphicon-remove")
				.addClass("glyphicon glyphicon-ok")
				.css({"color":"green","padding-left":"10px"})
		} else {
			$("#check").removeClass("glyphicon glyphicon-ok")
				.addClass("glyphicon glyphicon-remove")
				.css({"color":"red","padding-left":"10px"})					
		} 		
	}

	$("#email").keydown(function(){
		alertMail($(this).val());
	})
		.blur(function(){
			alertMail($(this).val());	
		});

	$.mask.definitions['H'] = "[2]"
	$.mask.definitions['J'] = "[0]"
	$.mask.definitions['K'] = "[0-2]"
	$.mask.definitions['L'] = "[0-9]"
	$.mask.definitions['M'] = "[0-1]"
	$.mask.definitions['N'] = "[0-9]"
	$.mask.definitions['O'] = "[0-3]"
	$.mask.definitions['P'] = "[0-9]"
	$("#fecha_factura2").mask("HJKL-MN-OP");
  
  var gar_jo=0;
  <?php 
  if($_SESSION['gar_jo'] == 1){
    echo "var gar_jo=1;";
  }
  ?>;
  if (gar_jo==1){
  $("#nvo_ticket").remove();
  } else {
      document.getElementById("CUSTOMER_NUMBER").focus();
  }
  //$("#envoltorio_interno").show();
  
  $(".lista_tickets").on("mouseenter",function(){
      $(this).css("background-color","#D0D0D0")
      $(this).css('cursor', 'pointer')
  });
  
  $(".lista_tickets").mouseleave(function(){
      $(this).css("background-color","transparent");
  });

$('#div_otro').hide();

$( "#fecha_factura2" ).datepicker({
      changeMonth: true,
       changeYear: true
    });
        $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
        dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
        dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
        weekHeader: 'Sm',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);

  $("#fecha_factura2").change(function(){
      var minutes=1000*60;
      var hours=minutes*60;
      var days=hours*24;
      var d= new Date();
      d = Date.parse(d);
      var fecha= Date.parse($(this).val());
      var dias= (d - fecha)/days;
      dias=Math.ceil(dias);
      document.getElementById("dias_factura").value=dias;
      $("#PRODUCTO").focus() 
  })

  $("input, TEXTAREA, select")
      .focus(function(){
        $(this).addClass("shadow");
      })

      .blur(function(){
        $(this).removeClass("shadow");
      })
      
  $( "#CUSTOMER_NUMBER" ).focus()

    //$("#envoltorio_interno").hide();

    $("#file").bind('change',function(){ 
      if(this.files[0].size>1048576){
        alert("El archivo es mayor a 1 Mb favor usar uno más chico");
        $("#file").val("");
      }      
    });

    $( "#CUSTOMER_NAME" )
            .autocomplete({
            source: "source.php",
            autoFocus: true,
            minLength: 2,
            select: function(event, ui) {
                $('#CUSTOMER_NAME').val(ui.item.CUSTOMER_NAME);
                $('#CUSTOMER_NUMBER').val(ui.item.CUSTOMER_NUMBER);
                $('#top').val(ui.item.TOP);
                $('#top1').val(ui.item.TOP_SI);
            }
            })
            .blur(function(){
                $("#dic_suc").val(0)
                if($('#top').val()==1){
                  $("input:radio[name='lugar']").filter('[value=1]').prop('checked',true)
                  $("input:radio[name='lugar']").change()
                  $("#revision_sucursal").hide()
                  $("#dic_suc").val(1)
                } else if($('#dic_suc1').val() != 1){
                  $("input:radio[name='lugar']").filter('[value=1]').prop('checked',false)
                  $("input:radio[name='lugar']").change()
                  $("#revision_sucursal").show()
                } else {
                  $("#dic_suc").val(0)
                }
                getDictaminador();
            });

    $( "#CUSTOMER_NUMBER" ).autocomplete({
            source: "source_descrip.php",
            autoFocus: true,
            select: function(event, ui) {
            $('#CUSTOMER_NUMBER').val(ui.item.CUSTOMER_NUMBER);        
            $('#CUSTOMER_NAME').val(ui.item.CUSTOMER_NAME);
            $('#top').val(ui.item.TOP);
            $('#top1').val(ui.item.TOP_SI);
            }
            })
            .blur(function(){
                $("#dic_suc").val(0)
                if($('#top').val()==1){
                  $("input:radio[name='lugar']").filter('[value=1]').prop('checked',true)
                  $("input:radio[name='lugar']").change()
                  $("#revision_sucursal").hide()
                  $("#dic_suc").val(1)
                } else if($('#dic_suc1').val() != 1) {
                  $("input:radio[name='lugar']").filter('[value=0]').prop('checked',false)
                  $("input:radio[name='lugar']").change()
                  $("#revision_sucursal").show()
                } else {
                  $("#dic_suc").val(0)
                }
                getDictaminador();
            });
    
    $( "#PRODUCTO" ).autocomplete({
            source: "source_producto.php",
            autoFocus: true,
            select: function(event, ui) {
            $('#PRODUCTO').val(ui.item.PRODUCTO);        
            $('#DESCRIPCION').val(ui.item.DESCRIPCION);
            $('#linea').val(ui.item.LINEA);
            $('#dic_suc1').val(ui.item.dic_suc);
            $('#excep1').val(ui.item.excep);
            }
            })
            .blur(function(){
                if($('#dic_suc1').val()==1){
                  $("input:radio[name='lugar']").filter('[value=1]').prop('checked',true)
                  $("input:radio[name='lugar']").change()
                  $("#revision_sucursal").hide()
                } else if ($('#dic_suc').val()!=1 || $('#excep1').val()==1) {
                  $("input:radio[name='lugar']").filter('[value=0]').prop('checked',false)
                  $("input:radio[name='lugar']").filter('[value=1]').prop('checked',false)
                  $("input:radio[name='lugar']").change()
                  $("#revision_sucursal").show()
                }
            });
    
    $( "#DESCRIPCION" ).autocomplete({
            source: "source_prod_descrip.php",
            autoFocus: true,
            select: function(event, ui) {
            $('#PRODUCTO').val(ui.item.PRODUCTO);        
            $('#DESCRIPCION').val(ui.item.DESCRIPCION);
            $('#linea').val(ui.item.LINEA);
            $('#dic_suc1').val(ui.item.dic_suc);
            $('#excep1').val(ui.item.excep);
            }
            })       
            .blur(function(){
                if($('#dic_suc1').val()==1){
                  $("input:radio[name='lugar']").filter('[value=1]').prop('checked',true)
                  $("input:radio[name='lugar']").change()
                  $("#revision_sucursal").hide()
                } else if ($('#dic_suc').val()!=1 || $('#excep1').val()==1) {
                  $("input:radio[name='lugar']").filter('[value=0]').prop('checked',false)
                  $("input:radio[name='lugar']").filter('[value=1]').prop('checked',false)
                  $("input:radio[name='lugar']").change()
                  $("#revision_sucursal").show()
                }
            });

    $("input:radio[name='lugar']").change(function(){
      if($("input:radio[name='lugar']:checked").val()==1){
        $("#div_otro").hide();
        $("select[name='canalizacion'] option ").remove(); 
        $("select[name='canalizacion']").append($('<option>',{
            value: 99,
            text: 'Seleccionar'
        }));
        $("select[name='canalizacion']").append($('<option>',{
            value: 'Sucursal',
            text: 'Diagnostico en Sucursal'
        }));
        $("select[name='canalizacion']").append($('<option>',{
            value: 'Taller',
            text: 'Revision en taller'
        }));
        $("select[name='canalizacion']").append($('<option>',{
            value: 'T\u00e9cnico',
            text: 'Revision con técnico'
        }));
      $("select[name='canalizacion']").prop("disabled",false);  
      } else if ($("input:radio[name='lugar']:checked").val()==0){
        $("select[name='canalizacion'] option ").remove(); 
        $("select[name='canalizacion']").append($('<option>',{
            value: 99,
            text: 'Seleccionar'
        }));
        $("select[name='canalizacion']").append($('<option>',{
            value: 'Monterrey',
            text: 'Envio a Monterrey'
        }));
        $("select[name='canalizacion']").append($('<option>',{
            value: 'Otro',
            text: 'Otro'
        }));
      $("select[name='canalizacion']").prop("disabled",false);  
      }
    });
    
    $("select[name='canalizacion']").change(function(){
        if($("select[name='canalizacion']").val()=="Otro"){
            $("#div_otro").show();
        } else {
          $("#otro").val(null);
            $("#div_otro").hide();
        }
    });
});
</script>

<script type="text/javascript">

function focusOnLoad(){  
  var gar_jo=0;
  <?php 
  if($_SESSION['gar_jo'] == 1){
    echo "var gar_jo=1;";
  }
  ?>;
  if (gar_jo==1){
  $("#nvo_ticket").remove();
  } else {
      document.getElementById("CUSTOMER_NUMBER").focus();
  }
};
  
  function getDictaminador() {
    var datos = {
      "linea" : $("#linea").val(),
      "dic_suc" : $("#dic_suc").val(),
      "dic_suc1": $("#dic_suc1").val(),
      "excep" : $("#excep1").val()
    };

    $.ajax({
      data:  datos,
      url:   'dictaminador.php',
      type:  'get',
      dataType: 'json',
      success:  function (data){
        $("#dictaminador").val(data[0].dictaminador)
        $("#usuario_dictaminador").val(data[0].usuario)
      }
    });
  }
    
  function validarForma(forma) {

    if(forma.CUSTOMER_NUMBER.value =="") {
     alert("Debe ingresar n\u00famero de cliente");
     forma.CUSTOMER_NUMBER.focus();
     return false;
    }

   if(forma.CUSTOMER_NAME.value =="") {
     alert("Se debe ingresar la raz\u00f3n social del cliente");
     forma.CUSTOMER_NAME.focus();
     return false;
   }
   
  if(forma.contacto.value =="") {
     alert("Se debe ingresar el nombre del contacto");
     forma.contacto.focus();
     return false;
   }

   var str=forma.email.value;
   var n=str.search("@");
  if(n==-1) {
     alert("El email no tiene el formato adecuado");
     forma.email.focus();
     return false;      
  }
  
  if(forma.factura.value =="") {
     alert("Se debe ingresar la factura");
     forma.factura.focus();
     return false;
  }

  if(forma.fecha_factura2.value =="") {
     alert("Se registrar la fecha de la factura");
     forma.fecha_factura2.focus();
     return false;
  }

  if(forma.PRODUCTO.value =="") {
     alert("Es necesario capturar el producto");
     forma.PRODUCTO.focus();
     return false;
  }

  if(forma.DESCRIPCION.value =="") {
     alert("Se requiere generar la descripci\u00f3n del producto");
     forma.DESCRIPCION.focus();
     return false;
  }

  if(forma.linea.value =="") {
     alert("El producto se debe seleccionar del listado");
     forma.PRODUCTO.focus();
     return false;
  }

  if($('input[name=lugar]:checked').length == 0){ 
     alert("Debe especificar si se revisar\u00e1 en sucursal");
     return false;
  }

  if($("select[name=canalizacion]").val() == 99 || $("select[name=canalizacion]").val() == null){ 
     alert("Seleccione canalizaci\u00f3n");
     forma.canalizacion.focus();
     return false;
  }

  if($("#file") =="") {
     alert("Debe describir con detalle la falla");
     forma.falla.focus();
     return false;
  }
      
  if(forma.falla.value =="") {
     alert("Debe describir con detalle la falla");
     forma.falla.focus();
     return false;
  }

  return true; 
  }

</script>    

<script type="text/javascript">

function focusOnLoad(){  
  // var gar_jo=0;
  // <?php 
  // if($_SESSION['gar_jo'] == 1){
  //   echo "var gar_jo=1;";
  // }
  // ?>;
  // if (gar_jo==1){
  // $("#nvo_ticket").remove();
  // } else {
  //     document.getElementById("CUSTOMER_NUMBER").focus();
  // }
  // $("#envoltorio_interno").show();
};
  
  function getDictaminador() {
    var datos = {
      "linea" : $("#linea").val(),
      "dic_suc" : $("#dic_suc").val(),
      "dic_suc1": $("#dic_suc1").val(),
      "excep" : $("#excep1").val()
    };

    $.ajax({
      data:  datos,
      url:   'dictaminador.php',
      type:  'get',
      dataType: 'json',
      success:  function (data){
        $("#dictaminador").val(data[0].dictaminador)
        $("#usuario_dictaminador").val(data[0].usuario)
      }
    });
  }

  function validarForma(forma) {

    if(forma.CUSTOMER_NUMBER.value =="") {
     alert("Debe ingresar n\u00famero de cliente");
     forma.CUSTOMER_NUMBER.focus();
     return false;
    }

   if(forma.CUSTOMER_NAME.value =="") {
     alert("Se debe ingresar la raz\u00f3n social del cliente");
     forma.CUSTOMER_NAME.focus();
     return false;
   }
   
  if(forma.contacto.value =="") {
     alert("Se debe ingresar el nombre del contacto");
     forma.contacto.focus();
     return false;
   }

   var str=forma.email.value;
   var n=str.search("@");
  if(n==-1) {
     alert("El email no tiene el formato adecuado");
     forma.email.focus();
     return false;      
  }
  
  if(forma.factura.value =="") {
     alert("Se debe ingresar la factura");
     forma.factura.focus();
     return false;
  }

  if(forma.fecha_factura2.value =="") {
     alert("Se registrar la fecha de la factura");
     forma.fecha_factura2.focus();
     return false;
  }

  if(forma.PRODUCTO.value =="") {
     alert("Es necesario capturar el producto");
     forma.PRODUCTO.focus();
     return false;
  }

  if(forma.DESCRIPCION.value =="") {
     alert("Se requiere generar la descripci\u00f3n del producto");
     forma.DESCRIPCION.focus();
     return false;
  }

  if(forma.linea.value =="") {
     alert("El producto se debe seleccionar del listado");
     forma.PRODUCTO.focus();
     return false;
  }

  if($('input[name=lugar]:checked').length == 0){ 
     alert("Debe especificar si se revisar\u00e1 en sucursal");
     return false;
  }

  if($("select[name=canalizacion]").val() == 99 || $("select[name=canalizacion]").val() == null){ 
     alert("Seleccione canalizaci\u00f3n");
     forma.canalizacion.focus();
     return false;
  }

  if($("#file") =="") {
     alert("Debe describir con detalle la falla");
     forma.falla.focus();
     return false;
  }
      
  if(forma.falla.value =="") {
     alert("Debe describir con detalle la falla");
     forma.falla.focus();
     return false;
  }

  return true; 
  }

</script>

</head>

<body onload = "focusOnLoad();">
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="#">Garantías</a>
      </div>
      <div>
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">Inicio</a></li>
          <li><a href="intro_admin_general.php">Resumen</a></li>
          <li><a href="canalizacion.php">Canalización</a></li>
          <?php
                if ($_SESSION['territorial'] == 1){
                  echo '<li><a href="intro_admin_territorial.php">Territorio</a></li>';
                }
              ?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
<div class=".container-fluid">
	<div class="row">
		<div id="nvo_ticket" class="col-sm-4 columna">

			<center><h2>Ingresar solicitud</h2></center>
    
                <h3 id="datos_cliente_lbl">Datos del cliente:</h3>
	            <form action="add_ticket.php" method="post" enctype="multipart/form-data" onsubmit="return validarForma(this);" target="_top">
                    
                <div>   
                    <label>Num. de Cliente:</label>
                    <input id='CUSTOMER_NUMBER' name="CUSTOMER_NUMBER" title="Inserte n&#250mero del cliente" size='6' />

                    <label>TOP:</label> <input type="hidden" id="top" name="top"/>
                    <input id='top1' name="top1" readonly title="¿Es cliente top?" size='2' />
                </div>
                    
                <br>
                <div>
                    <input placeholder="Raz&#243n Social" id='CUSTOMER_NAME' name="CUSTOMER_NAME" title="Inserte la raz&#243n social del cliente" size='50' />
                </div>		

                <br>
                <div>
                    <input placeholder="Contacto" id='contacto' name="contacto" title="Nombre del contacto" size='50' />
                </div>

                <br>
                <div>
                    <input placeholder="email" id='email' name="email" title="Email del contacto" size='50' /><div id="check"></div>
                </div>
                
                <br>    
                		<h3 id="datos_cliente_lbl">Datos del producto y falla:</h3>

		<div>
			<input placeholder="Factura #" id='factura' name="factura" title="Folio de la factura" size='50' /><br><br>

			<input placeholder="Fecha factura" type="text" id='fecha_factura2' name="fecha_factura2" title="Fecha de la factura" size='20' />

			<input id='dias_factura' name="dias_factura" readonly title="Dias desde la facturaci&#243n" size='3' />

			<input type="hidden" id='fecha_factura' name="fecha_factura" title="Fecha de la factura" size='39' />

		</div>
		
		<br>
		<div>
			<input placeholder="Clave del producto" id='PRODUCTO' name="PRODUCTO" title="Clave del producto" size='20' onblur="getDictaminador()" />

			<input placeholder="L&#237;nea" id='linea' name="linea" readonly title="L&#237;nea del producto"  size='3' /><br><br>

			<input placeholder="Descripci&#243;n" id='DESCRIPCION' name="DESCRIPCION" title="Descripc&#243;n del producto" size='50' onblur="getDictaminador()" /><br><br>

			<input id='serie' name="serie" placeholder="N&#250;mero de serie" title="N&#250;mero de serie del producto" size='50' />
		</div>
								
		<div id="recibir">
		  <br>
			<label>Se recibe producto:</label>
				<input type="radio" name="recibe" value=1>Si 
				<input type="radio" name="recibe" checked value=0>No
		</div>

		<div id="info_dictaminador">
			<br>
			<input placeholder="Dictaminador" readonly id='dictaminador' name="dictaminador" title="Dictaminador" size='50' />
		</div>
		
		<div id="revision_sucursal">
			<br>
			<label>Revisi&#243;n en Sucursal:</label>
			<input type="radio" name="lugar" value = 1>Si 
			<input type="radio" name="lugar" value = 0>No
		</div>
		  <br>
		<div>
			<label>Canalizaci&#243;n:</label>
			<div class="content">
			  <select name="canalizacion" disabled>
			  </select>
			</div>
		</div>
		
		<div id="div_otro">
			<br>
		  <input placeholder="Comentar otro" type="text" id='otro' name="otro" title="Comenta otro" size='50' />
		</div><br>
		
		<p>
			<textarea placeholder="Descripci&#243;n detallada de la falla" id='falla' name="falla" title="Descripci&#243;n detallada de la falla" rows="4" cols='50'></textarea>
		</p>

		<div class="block">  
			<div class="label" id="adjunto"><label for="file">Archivo adjunto</label></div>
			<div class="content"><input type="file" id="file" name="file" value="" maxlength="20" /></div><br>
		</div>

		<input type="hidden" name="form_token" value="<?php echo $form_token; ?>" />
		<input type="hidden" id="usuario" name="usuario" value="<?php echo $usuario; ?>" />
		<input type="hidden" id="sucursal" name="sucursal" value="<?php echo $sucursal; ?>" />
		<input type="hidden" id="dic_suc" name="dic_suc"/>
		<input type="hidden" id="dic_suc1" name="dic_suc1"/>
		<input type="hidden" id="excep1" name="excep1"/>
		<input type="hidden" id="usuario_dictaminador" name="usuario_dictaminador" />
		<!-- <center><input class="btn btn-info" type="submit" value="Enviar" /></center> -->

	   </form>    
		</div>

    <div class="col-sm-1">
    </div>

		<div class="col-sm-5 columna">

					<center><h2>Solicitudes en proceso</h2></center>

					<?PHP

					  $resultado = $mysqli->query("SELECT folio, linea, PRODUCTO, fecha, CUSTOMER_NAME, lugar, dictaminador, TOP FROM solic WHERE sucursal = '$sucursal' and estatus=1");
						if (!$resultado) {
							printf("error: %s\n", $mysqli->error);
							exit();
						}
						else {
						while($fila = $resultado->fetch_assoc()){
							$revisar = "";
							if (($linea['gar_jo'] == 1 || $linea['gar_admin'] == 1) && $fila['lugar'] == 1){$revisar="**";};
							$date= strtotime($fila['fecha']);
							$date=date('d/M/y',$date);

			              $dictaminador = $fila['dictaminador'];
			              if ($fila['TOP']==1){
			                $TOP = "TOP";
			              } else {
			                $TOP = "";
			              }
							echo "<div class='lista_tickets' onclick=\"window.location = 'coment.php?folio=".$fila['folio']."';\"> ".$revisar." <strong>Folio:</strong> ".$fila['folio']." <strong>Fecha: </strong>".$date." <strong>Linea:</strong> ".$fila['linea']." <strong>Producto:</strong> ".$fila['PRODUCTO']."<br> <strong> Dictamina:</strong> ".$dictaminador." <strong>".$TOP."</strong> <strong>Cliente:</strong> ".$fila['CUSTOMER_NAME'].".</div>";
							}
						} 
						$resultado->free();
						$mysqli->close();
					?>
		</div>
	</div>
</div>
<?php include_once 'contador.php'; ?>
</body>
</html>
