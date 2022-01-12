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
                "CUSTOMER_ID"    : Id
            }
            $.ajax({
                data:   datos,
                url:   'getClientesTop.php',
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
                $("#tabla tbody").append("<tr> <td> <input type='button' onclick='Remueve("+data[i].CUSTOMER_ID+")' class='boton_remover' value='Remover' > </td> <td>"+data[i].CUSTOMER_NUMBER+"</td> <td class='left'>"+data[i].CUSTOMER_NAME+"</td> <td>"+data[i].TOP+"</td> </tr>" )               
                i++;
            }
    };

    $(document).ready(function(){
    
        var i = 0;
        $.ajax({
          data : {"tarea" : "nada"},  
          url:   'getClientesTop.php',
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
                        "color": "black"
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
                    "color": "black"
                })
                }, "mouseleave": function(){
                    $(this).css({
                        "background-color":"#458B00",
                        "color" : "white"
                    })
                }, "click": function(){
                        $.ajax({
                              data : {"tarea" : "agregar", "CUSTOMER_NUMBER" : $("#CUSTOMER_NUMBER").val()},  
                              url:   'getClientesTop.php',
                              type:  'get',
                              dataType: 'json',
                              success:  function (data){
                                poblar_tabla(data);
                              }
                        })
                        location.reload();
                }
            })


        $( "#CUSTOMER_NUMBER" ).autocomplete({
                source: "source_descrip.php",
                autoFocus: true,
                select: function(event, ui) {
                    $('#CUSTOMER_NUMBER').val(ui.item.CUSTOMER_NUMBER);
                    $('#CUSTOMER_NAME').val(ui.item.CUSTOMER_NAME);
                    $('#TOP').val(ui.item.TOP);
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
                    <tr><td> <input id="boton_agregar" type="button" value ="Agregar"/> </td><td> <input id="CUSTOMER_NUMBER" placeholder= "Número de Cliente" type="text" size ="20"/> </td><td> <input readonly id="CUSTOMER_NAME" type="text" size = "60"/> </td><td> <input readonly id="TOP" type="text" size = "1"/> </td></tr>
                </tbody>       
            </table>   
             <br>  
            <table id="tabla">
                <thead><tr><th></th><th>Cliente #</h><th>Nombre del Cliente</th><th>TOP</th></tr></thead>
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

</body>
</html>
