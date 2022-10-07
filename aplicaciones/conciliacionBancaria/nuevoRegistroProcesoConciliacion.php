<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();

?>

<header>
	<h1>Nueva Trama</h1>
</header>

	<div id="estado"></div>
	
	<form id="nuevoRegistroProcesoConciliacion" data-rutaAplicacion="conciliacionBancaria" data-opcion="guardarNuevoRegistroProcesoConciliacion" data-destino="detalleItem" data-accionExito = "ACTUALIZAR">

		<fieldset id="informacionProceso">
			<legend>Información de Proceso</legend>
			<div data-linea="1">
				<label>Nombre de proceso:</label>
				<input type="text" id="nombreRegistroProcesoConciliacion" name="nombreRegistroProcesoConciliacion"/>
			</div>
			<hr>
			<div data-linea="2">
				<label>Facturas GUIA:</label>
				<select id="facturaRegistroProcesoConciliacion" name="facturaRegistroProcesoConciliacion">
					<option value="">Seleccione...</option>
					<option value="interno">Servicion internos</option>
					<option value="comercioExterior">Comercio exterior</option>
				</select>
			</div>
			<div data-linea="3">
				<label>Tipo de revisión: </label>
				<select id="tipoRevisionRegistroProcesoConciliacion" name="tipoRevisionRegistroProcesoConciliacion">
					<option value="">Seleccione...</option>
					<option value="comparacionGUIA">Comparación de información de campos con GUIA</option>
				</select>
			</div>			
		</fieldset>
		<div>
			<button type="submit" class="guardar">Guardar</button>
		</div>
		
	</form>	
	

<script type="text/javascript">			

    $(document).ready(function(){	
    	distribuirLineas();
    });    

    $("#nuevoRegistroProcesoConciliacion").submit(function(){

  	 	event.preventDefault();
  	    $(".alertaCombo").removeClass("alertaCombo");
  	  	var error = false;

    	if($("#nombreRegistroProcesoConciliacion").val()==""){
			error = true;
			$("#nombreRegistroProcesoConciliacion").addClass("alertaCombo");
		}

    	if($("#facturaRegistroProcesoConciliacion").val()==""){
			error = true;
			$("#facturaRegistroProcesoConciliacion").addClass("alertaCombo");
		}

    	if($("#tipoRevisionRegistroProcesoConciliacion").val()==""){
			error = true;
			$("#tipoRevisionRegistroProcesoConciliacion").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			//ejecutarJson($(this));   
			abrir($(this), event, false);                          
		}
		
    });
</script>
	
	