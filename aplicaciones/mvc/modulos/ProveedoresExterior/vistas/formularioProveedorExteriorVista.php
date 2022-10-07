<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<?php if(isset($this->informacionOperador)){ ?>

<form id='formularioProveedorExterior'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
	data-opcion='ProveedorExterior/guardar' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">

	<fieldset>
		<legend>Información del solicitante</legend>
		<?php echo $this->informacionOperador; ?>		
	
	</fieldset>

	<fieldset>
		<legend>Información del proveedor en el exterior</legend>

		<div data-linea="1">
			<label for="nombre_fabricante">Nombre del fabricante: </label> <input
				type="text" id="nombre_fabricante" name="nombre_fabricante" value=""
				placeholder="Ejem: Carlos Alberto Pérez Castro" required
				maxlength="128" />
		</div>

		<div data-linea="2">
			<label for="id_pais_fabricante">País del fabricante: </label> <select
				id="id_pais_fabricante" name="id_pais_fabricante" class="validacion">
				<option value="">Seleccionar....</option>
				<?php
	echo $this->comboPaises();
	?>
            </select>
		</div>

		<div data-linea="3">
			<label for="direccion_fabricante">Dirección del fabricante: </label>
			<input type="text" id="direccion_fabricante"
				name="direccion_fabricante" value=""
				placeholder="Ejem: Avenida de las Américas" required maxlength="128" />
		</div>

		<div data-linea="4">
			<label for="servicio_oficial">Servicios oficiales que regulan los
				productos que fabrica la planta: </label>
		</div>
		<div data-linea="5">
			<textarea name="servicio_oficial" id="servicio_oficial"
				placeholder="Registre aquí los servicios oficiales" required
				maxlength="128"></textarea>
		</div>
	</fieldset>
	<input type="hidden" id="id" name="id" />
	<div data-linea="6">
		<button type="submit" class="guardar">Guardar</button>
	</div>

</form>

<?php }else{ ?>

<fieldset>
	<legend>Información del proveedor en el exterior</legend>
	<br />
	<label>Nota: </label><span>Para poder realizar una solicitud, usted
		debe poseer habilitado un registro con cualquiera de estas
		operaciones: Fabricante, Formulador, Distribuidor.</span>' ;
</fieldset>

<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
		construirValidador();
        distribuirLineas();
    });

	$("#formularioProveedorExterior").submit(function (event) {
		event.preventDefault();
		var error = false;

		var identificadorOperador = $("#identificador_operador").val();
		var idProvinciaOperador = $("#id_provincia_operador").val();
		var nombreProvinciaOperador = $("#nombre_provincia_operador").val();
		var nombreFabricante = $("#nombre_fabricante").val();
		var idPaisFabricante = $("#id_pais_fabricante").val();
		var nombrePaisFabricante = $("#id_pais_fabricante option:selected").text();
		var direccionFabricante = $("#direccion_fabricante").val();
		var servicioOficial = $("#servicio_oficial").val();
		
		
		if (!error) {
            $.post("<?php echo URL ?>ProveedoresExterior/ProveedorExterior/guardar", {
            	identificadorOperador: identificadorOperador,
            	idProvinciaOperador: idProvinciaOperador,
                nombreProvinciaOperador: nombreProvinciaOperador,
                nombreFabricante: nombreFabricante,
                idPaisFabricante: idPaisFabricante,
                nombrePaisFabricante: nombrePaisFabricante,
                direccionFabricante: direccionFabricante,
                servicioOficial: servicioOficial
            }, function(data) {
                if (data.estado == 'Fallo') {
                    mostrarMensaje(data.mensaje, "FALLO");
                }else{
                	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
                    $("#id").val(data.contenido);
                    $("#formularioProveedorExterior").attr('data-opcion', 'ProveedorExterior/abrirSolicitudCreada');
                    abrir($("#formularioProveedorExterior"), event, false);
                }
            }, 'json');
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
	
</script>

