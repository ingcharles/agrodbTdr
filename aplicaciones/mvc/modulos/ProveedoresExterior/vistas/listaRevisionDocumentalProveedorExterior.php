<header>
	<nav><?php echo $this->panelBusquedaProveedoresExteriorReporte;?></nav>
	<nav><?php echo $this->crearAccionBotones();?></nav>
</header>
<div id="article"></div>

<style>
select {
	width: 100%;
	box-sizing: border-box;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
}
</style>

<script type="text/javascript">

    var tipoSolicitud = "proveedorExterior";   
    var tipoInspector = "Documental";      

    $(document).ready(function () {
    	$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí una solicitud para revisarla.</div>');	

		$("#_asignar").click(function(){
			if($("#cantidadItemsSeleccionados").text() == 0){
				$("#detalleItem").html('<div class="mensajeInicial">Seleccione una solicitud y presione el botón Asignar.</div>');	
				return false;
			}
		});		

	});

    $("#inspector").change(function (event) {    
        if($("#inspector").val() != ""){
            if($('#condicion').val() == 'Documental'){
            	if($("#inspector").val() == 'asignar'){
        			$("#estadoSolicitud").val('RevisionDocumental');
        		}else{
        			$("#estadoSolicitud").val('AsignadoDocumental');
        		}
            }
        }
        fn_cargarOperadoresSolicitudesProveedorExterior();
    });

    //Funcion para listar los operadores con solicitudes de proveedores en el exterior en estao de revisión documental
    function fn_cargarOperadoresSolicitudesProveedorExterior() {    

    	var condicion = $("#condicion").val();   
        var estadoSolicitud = $("#estadoSolicitud").val();   
        var inspector = $("#inspector").val();             
    
    	if (inspector !== ""){    
            $.post("<?php echo URL ?>ProveedoresExterior/AdministracionRevisionFormularios/buscarSolicitudesOperadoresProveedorExterior",
                {
                	condicion : condicion,
                    estadoSolicitud : estadoSolicitud,
                    inspector : inspector,
                    tipoSolicitud : tipoSolicitud,
                    tipoInspector : tipoInspector
                }, function (data) {
                	$("#identificadorOperador").html(data);               
            });
        } 
    
    }

    $("#btnFiltrar").click(function (event) {
		event.preventDefault();
		fn_filtrar();
	});

    //Funcion para listar las solicitudes en estado de revion documental
	function fn_filtrar() {
		event.preventDefault();
		var error = false;
		        
		if (!error) {
			$("#article").html("<div id='cargando'>Cargando...</div>");
			var condicion = $("#condicion").val();   
            var estadoSolicitud = $("#estadoSolicitud").val();   
            var inspector = $("#inspector").val();
            var identificadorOperador = $("#identificadorOperador").val();
			
			  $.post("<?php echo URL ?>ProveedoresExterior/AdministracionRevisionFormularios/buscarSolicitudesProveedorExteriorPorOperador",
		    	{
                    condicion : condicion,
                    estadoSolicitud : estadoSolicitud,
                    inspector : inspector,
                    identificadorOperador : identificadorOperador,
                    tipoSolicitud : tipoSolicitud,
                    tipoInspector : tipoInspector
		        }, function (data) {
                	$("#article").html(data);               
	            });
		} else {
			$("#estado").html();
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}		
	}	
   
</script>