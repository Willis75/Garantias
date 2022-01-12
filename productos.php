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
<html lang="es">
<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" type= "text/css" href="css/styles_intro_admin.css">
    <link href="../scripts/css/ui-lightness/jquery-ui-1.10.3.custom.css" rel="stylesheet">
    <script type ="text/javascript" src="../scripts/jquery-1.10.1.js"></script>
    <script  src="../scripts/js/jquery-1.9.1.js"></script>
    <script  src="../scripts/js/jquery-ui-1.10.3.custom.min.js"></script>
    <title>Sistema de Garantías Novem</title>
    <link rel="shortcut icon" href="favicon.ico" >

    <script>
    function Remueve(Id){
        var r = confirm("¿Esta seguro de remover de la lista?");
        if (r){
            var i = 0;
            var datos = {
                "tarea" : "remover",
                "Id"    : Id,
            }
            $.ajax({
                data:   datos,
                url:   'getProductos.php',
                type:  'get',
                dataType: 'json',
                success:  function (data){
                $("#tabla tbody tr").remove()
                poblar_tabla(data);
              }
            });
        }
    };

    function poblar_tabla(data){
        var i = 0;
        while (data[i]) {
                $("#tabla tbody").append("<tr> <td> <input type='button' onclick='Remueve("+data[i].Id+")' class='boton_remover' value='Remover' > </td> <td>"+data[i].PRODUCTO+"</td> <td class='left'>"+data[i].DESCRIPCION+"</td> <td>"+data[i].LINEA+"</td> <td>"+data[i].dic_suc+"</td> <td>"+data[i].excep+"</td> </tr>" )               
                i++;
            }
    };

    $(document).ready(function(){
    
        var i = 0;
        $.ajax({
          data : {"tarea" : "nada"},  
          url:   'getProductos.php',
          type:  'get',
          dataType: 'json',
          success:  function (data){
            poblar_tabla(data);
          }
        });

        $("#tabla tbody")

            .on("mouseover", "tr td .boton_remover", function(){
                    $(this).css({
                        "background-color": "#FF7F24",
                        "color": "black",
                        "cursor": "pointer"
                    })
                })

            .on("mouseleave","tr td .boton_remover", function(){
                    $  (this).css({
                        "background-color":"#8B2323",
                        "color": "white"
                    })
                })

        $("#boton_agregar")

            .on({
                "mouseover": function(){
                $(this).css({
                    "background-color": "#CAFF70", 
                    "color": "black",
                    "cursor": "pointer"
                })
                }, "mouseleave": function(){
                    $(this).css({
                        "background-color":"#458B00",
                        "color" : "white"
                    })
                }, "click": function(){
                        if ($("input[name=tipo]:checked").length == 0){
                            alert("Debe seleccionar si es dictamen en sucursal o si es excepción");
                        } else {

                            $.ajax({
                                  data : {
                                    "tarea" : "agregar", 
                                    "producto" : $("#producto").val(),
                                    "tipo" :  $("input[name=tipo]:checked").val()
                                    }, 
                                  url:   'getProductos.php',
                                  type:  'get',
                                  dataType: 'json',
                                  success:  function (data){
                                    $("#tabla tbody tr").remove()
                                    poblar_tabla(data);
                                  }
                            })
                        }
                }
            })


        $( "#producto" ).autocomplete({
                source: "source_producto.php",
                autoFocus: true,
                select: function(event, ui) {
                    $('#producto').val(ui.item.DESCRIPCION);
                    $('#descripcion').val(ui.item.DESCRIPCION);
                    $('#linea').val(ui.item.LINEA);
                }
        })  

    });

    </script>

</head>

<body>

<div id="envoltorio" align="center">
    <div id="envoltorio_interno">
        
        <div id="encabezado">
            <img src="images/novem.jpg" alt="logo Novem" width="250"><br><br>                
            <div id="franja_superior"></div>
            <div class="clear"></div>
        </div>
    
    <div id="navegacion">
            <div id="cerrar_sesion"><a href="intro_admin_todos.php">Inicio</a></div>
    </div>
    
    <div id="cuerpo">
        <div id="envoltorio_tabla">
            <table id = "agregar">
                <tbody>
                    <tr><td> <input id="boton_agregar" type="button" value ="Agregar"/> </td><td> <input id="producto" placeholder= "Código del producto" type="text"/> </td> <td> <input readonly id="descripcion" type="text" size = "100"/> </td><td> <input readonly id="linea" type="text" size = "3"/> </td> <td><input type="radio" name="tipo" value="dic_suc">Sucursal<input type="radio" name="tipo" value="excep">Excepción</td></tr>
                </tbody>       
            </table>   
             <br>  
            <table id="tabla">
                <thead><tr><th></th><th>Producto</h><th>Descripci&#243;n</th><th>L&#237;nea</th><th>Dictamen Sucursal</th><th>Excepción</th></tr></thead>
                <tbody></tbody>
            </table>
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
