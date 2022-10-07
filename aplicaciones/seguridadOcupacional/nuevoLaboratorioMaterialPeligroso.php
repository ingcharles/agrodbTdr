<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorSeguridadOcupacional.php';
	$conexion = new Conexion();
	$so = new ControladorSeguridadOcupacional();
	
?>


	<header>
		<h1>Nueva coordinación laboratorio</h1>
	</header>
	<div id="estado"></div>
	<form id="nuevoLaboratorioMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" data-opcion="guardarNuevoLaboratorioMaterialPeligroso" data-destino="detalleItem" >
			<fieldset>
				<legend>Datos coordinación laboratorio</legend>	
		
				<div data-linea="1">			
					<label>Nombre Coordinación Laboratorio:</label> 
					<input type="text" id="nombreLaboratorioUno" name="nombreLaboratorioUno" placeholder="Ej: DIRECCIÓN DE DIAGNOSTICO VEGETAL" maxlength="512" />	
				</div>
				
			</fieldset> 
		<button type="submit" class="guardar" > Guardar </button>	
	</form>	

<script>

	$('document').ready(function(){
		distribuirLineas();	
	});
	
	$("#nuevoLaboratorioMaterialPeligroso").submit(function(event){
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#nombreLaboratorioUno").val())){
			error = true;
			$("#nombreLaboratorioUno").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Ingresar información en campos obligatorios.").addClass('alerta');
		}else{
			abrir($(this),event,false);
		}
	});
</script>
