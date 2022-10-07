<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorFitosanitarioExportacion.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cfe = new ControladorFitosanitarioExportacion();

?>

<header>
	<h1>Notificación de producto CFE</h1>
</header>
	<div id="estado"></div>
	<div id="mensajeCargando"></div>

<form id="nuevoNotificacionProductoCFE" data-rutaAplicacion="notificacionesCFE" data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">

	<input type="hidden" id="opcion" name="opcion"/>
	
<fieldset id="registrarDiagnostico">
<legend>Datos de Notificación</legend>
	<div data-linea="1">
		<label>Número:</label>
		<input type="text" id="numeroNotificacion" name="numeroNotificacion"/>
	</div>
	
	<div data-linea="1">
	<label>Fecha notificación:</label>
	<input type="text" id="fechaNotificacion" name="fechaNotificacion"/>
	</div>
	
	<div data-linea="2">
	<label>Motivo:</label>
	<input type="text" id="motivoNotificacion" name="motivoNotificacion"/>
	</div>
	
	<div data-linea="3">
	<label>Observaciones:</label>
	<input type="text" id="observacionNotificacion" name="observacionNotificacion"/>
	</div>
	
</fieldset>
	
	
<fieldset id="datosExportacion">
<legend>Datos de Exportación</legend>
	<div data-linea="4">
		<label>Número de CFE:</label>
		<input type="text" id="numeroCFE" name="numeroCFE"/>
		<hr/>
	</div>
	
	<div id="resultadoExportador" data-linea="5"></div>
	<div id="resultadoDatosExportador" data-linea="6"></div>
	<div id="resultadoTipoProducto" data-linea="7"></div>
	<div id="resultadoSubtipoProducto" data-linea="8"></div>
	
</fieldset>

 <button type="submit" class="guardar" id="guardar">Guardar notificación</button>
</form>

<script type="text/javascript">

$(document).ready(function(){	
	$("#guardar").hide();
	distribuirLineas();	
});

$("#fechaNotificacion").datepicker({
    changeMonth: true,
    changeYear: true
}).datepicker('setDate', 'today');


$("#numeroCFE").change(function(event){
	 $('#nuevoNotificacionProductoCFE').attr('data-opcion','accionesNotificacionProducto');
	 $('#nuevoNotificacionProductoCFE').attr('data-destino','resultadoExportador');
	 $('#opcion').val('numeroCFE');
	 abrir($("#nuevoNotificacionProductoCFE"),event,false);	
});
						

						
$("#nuevoNotificacionProductoCFE").submit(function(event){
	event.preventDefault();
	chequearCampos(this);
});

function chequearCampos(form){
	$(".alertaCombo").removeClass("alertaCombo");
	var error = false;

	if($("#numeroNotificacion").val()==""){	
		error = true;		
		$("#numeroNotificacion").addClass("alertaCombo");
	}

	if($("#motivoNotificacion").val()==""){	
		error = true;		
		$("#motivoNotificacion").addClass("alertaCombo");
	}

	if($("#observacionNotificacion").val()==""){	
		error = true;		
		$("#observacionNotificacion").addClass("alertaCombo");
	}

	if($("#numeroCFE").val()==""){	
		error = true;		
		$("#numeroCFE").addClass("alertaCombo");
	}

	if($("#productosExportador").val()==""){	
		error = true;		
		$("#productosExportador").addClass("alertaCombo");
	}

	if (error){
		$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
	}else{
		$("#nuevoNotificacionProductoCFE").attr('data-opcion', 'guardarNuevoNotificacionCFE');
	    $("#nuevoNotificacionProductoCFE").attr('data-destino', 'detalleItem');
	    
		ejecutarJson(form);
	}
};
</script>