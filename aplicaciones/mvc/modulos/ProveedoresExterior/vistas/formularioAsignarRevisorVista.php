<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formularioAsignarRevisor'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
	data-opcion='subsanacion/guardar' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="solicitudes" name="solicitudes"
		value="<?php echo $_POST['elementos']; ?>">

	<p>
		Las <b>solicitudes</b> a ser asignadas son:
	</p>
	
    <?php echo $this->solicitudesAsignadasRevision;?>
    
    <fieldset>
		<legend>Inspectores</legend>
		<div data-linea="1">
			<label for="revisorAsignado">Técnico: </label> <select
				id="revisorAsignado" name="revisorAsignado" class="validacion">
        		<?php echo $this->comboInspectoresRevisores; ?>
        	</select>
		</div>
		<div data-linea="2">
			<button type="submit" class="mas" id="agregarAsignarRevisor">Asignar técnico</button>
		</div>

		<table id="detalleRevisorAsignado" style="width: 100%">
			<thead>
				<tr>
					<th># Solicitud</th>
					<th>Tipos inspección</th>
					<th>Inspector asignado</th>
					<th>Opción</th>
				</tr>
			</thead>
			<tbody>
			<?php echo $this->generarFilaRevisorAsignado; ?>
			</tbody>
		</table>
	</fieldset>
</form>

<script type="text/javascript">

    var tipoSolicitud = "proveedorExterior";   
    var tipoInspector = "Documental";    

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	 });

	//Funcion para agregar fila de detalle de revisor asignado
    $("#formularioAsignarRevisor").submit(function (event) {
		event.preventDefault();
		mostrarMensaje("", "");
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		$('#formularioAsignarRevisor .validacion').each(function(i, obj) {

 			if(!$.trim($(this).val())){
 				error = true;
 				$(this).addClass("alertaCombo");
 			}

 		});		
				
		if (!error) {

			$("#estado").html("").removeClass('alerta');
	        var filas = 0;
	        
			$.post("<?php echo URL ?>ProveedoresExterior/AdministracionRevisionFormularios/guardarAsignacionRevisor",
		    	{

					revisorAsignado : $("#revisorAsignado").val(),
					nombreRevisorAsignado : $("#revisorAsignado option:selected").attr("data-nombreInspector"),					
				  	idSolicitud : $("#solicitudes").val(),
				  	tipoSolicitud : tipoSolicitud,
				  	tipoInspector : tipoInspector
				 
		        },
		      	function (data) {
		        	if (data.validacion == 'Fallo'){
		        		mostrarMensaje(data.resultado,"FALLO"); 
		        		setTimeout(function(){
							$("#estado").html("").removeClass('alerta');
	   	                },1500);	        		
	                }else{
	                	$("#detalleRevisorAsignado tbody").append(data.filaRevisorAsignado);
	                	mostrarMensaje(data.resultado,"EXITO");
	                	limpiarDetalle("revisorAsignado");
		            }
		        }, 'json');
	        
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//Funcion que limpia el valor de los elementos
	function limpiarDetalle(valor){
        switch(valor){

            case "revisorAsignado":
            	$("#revisorAsignado").val("");
            break;

        }        
	}

	//Funcion que elimina una fila del detalle de revisores asignados
    function fn_eliminarDetalleRevisorAsignado(idAsignacionCoordinador, idProveedorExterior ) {
    	$("#estado").html("").removeClass('alerta');
        $.post("<?php echo URL ?>ProveedoresExterior/AdministracionRevisionFormularios/eliminarAsignacionRevisor",
        {                
        	idAsignacionCoordinador: idAsignacionCoordinador,
        	idProveedorExterior : idProveedorExterior
        },
        function (data) {
        	$("#fila" + idAsignacionCoordinador).remove();
        });
    }
	
</script>
