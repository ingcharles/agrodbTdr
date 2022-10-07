<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProcesosAdministrativosJuridico' data-opcion='procesoAdministrativo/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>Creación de Proceso Administrativo</legend>				

		<div data-linea="1">
			<label for="provincia">Provincia: </label>
			<select id="provincia" name="provincia" >
				<?php echo $this->comboProvinciaTecnico($this->modeloProcesoAdministrativo->getProvincia());?>
			</select>
		</div>				

		<div data-linea="2">
			<label for="area_tecnica">Área Técnica: </label>
			<select id="area_tecnica" name="area_tecnica" >
				<option value="">Seleccione....</option>
			 	<?php echo $this->comboAreaTecnica($this->modeloProcesoAdministrativo->getAreaTecnica());?>
			</select>
		</div>				

	   <div data-linea="3" id="NumProceso">
			<label for="numero_proceso">Número del Expediente: </label>
			<input type="text" id="numero_procesod" disabled name="numero_procesod" value="<?php echo $this->modeloProcesoAdministrativo->getNumeroProceso(); ?>"
			 maxlength="32" />
		</div>	
		
		<div data-linea="4">
			<label for="nombre_accionado">Nombre del Accionado: </label>
			<input type="text" id="nombre_accionado" name="nombre_accionado" value="<?php echo $this->modeloProcesoAdministrativo->getNombreAccionado(); ?>"
			placeholder="Nombre del Accionado" required maxlength="64" />
		</div>				

		<div data-linea="5">
			<label for="nombre_establecimiento">Nombre del Establecimiento: </label>
			<input type="text" id="nombre_establecimiento" name="nombre_establecimiento" value="<?php echo $this->modeloProcesoAdministrativo->getNombreEstablecimiento(); ?>"
			placeholder="Nombre establecimiento" required maxlength="128" />
		</div>				

	</fieldset >
	<div data-linea="8" id="buttonGuardar">
			<button type="submit" class="guardar" >Guardar</button>
		</div>
</form >
<script type ="text/javascript">
var opcion = <?php echo json_encode($this->opcion); ?>;
	$(document).ready(function() {
		mostrarMensaje("", "FALLO");
		construirValidador();
		distribuirLineas();
		if(opcion=='editar'){
		  inactivarInputs();
		}else{
			$("#field1").hide();
			$("#NumProceso").hide();
			}
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
			abrir($(this), event, false);
			abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	function inactivarInputs(){
			$("#provincia").prop("disabled", true);
			$("#area_tecnica").prop("disabled", true); 
			$("#nombre_accionado").prop("disabled", true); 
			$("#nombre_establecimiento").prop("disabled", true); 
			$("#buttonGuardar").hide();
			$("#NumProceso").show();
		}
	
</script>
