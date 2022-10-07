<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<fieldset id="datosTramite" name="datosTramite">
		<legend>Datos del Trámite</legend>
		
		<div data-linea="1">
			<label for="numero_tramite">Registro del Trámite: </label> 
			<input type="text" value="<?php echo $this->modeloTramites->getNumeroTramite(); ?>" 
				disabled="disabled" />
		</div>

		<div data-linea="1">
			<label for="id_ventanilla">Ventanilla: </label> 
			<input type="text" value="<?php echo $this->modeloTramites->getNombreVentanilla(); ?>" 
				disabled="disabled" />
		</div>

		<div data-linea="2">
			<label for="identificador">Responsable: </label> 
			<input type="text" value="<?php echo $this->modeloTramites->getNombreEmpleado(); ?>" 
				disabled="disabled" />
		</div>

		<hr />

		<div data-linea="3">
			<label for="remitente">Remitente: </label> 
			<input type="text" value="<?php echo $this->modeloTramites->getRemitente(); ?>" 
				disabled="disabled"readonly="readonly" />
		</div>

		<div data-linea="4">
			<label for="oficio_memo">Oficio/Memo: </label> 
			<input type="text" value="<?php echo $this->modeloTramites->getOficioMemo(); ?>"
				disabled="disabled" />
		</div>

		<div data-linea="4">
			<label for="factura">Factura: </label> 
			<input type="text" value="<?php echo $this->modeloTramites->getFactura(); ?>"
				disabled="disabled" />
		</div>

		<div data-linea="5">
			<label for="guia_quipux">Guía - Correo: </label> 
			<input type="text" value="<?php echo $this->modeloTramites->getGuiaQuipux(); ?>"
				disabled="disabled" />
		</div>

		<div data-linea="6">
			<label for="asunto">Asunto: </label> 
			<input type="text" value="<?php echo $this->modeloTramites->getAsunto(); ?>"
				disabled="disabled" />
		</div>

		<div data-linea="7">
			<label for="anexos">Anexos: </label> 
			<input type="text" value="<?php echo $this->modeloTramites->getAnexos(); ?>"
				disabled="disabled" />
		</div>

		<hr />

		<div data-linea="8">
			<label for="destinatario">Destinatario: </label> 
			<input type="text" value="<?php echo $this->modeloTramites->getDestinatario(); ?>" 
				disabled="disabled" />
		</div>

		<div data-linea="9">
			<label for="id_unidad_destino">Unidad de Destino: </label> 
			<select disabled>
				<?php
                    echo $this->comboAreasCategoriaNacional($this->modeloTramites->getIdUnidadDestino());
                ?>
            </select>
		</div>

		<div data-linea="10">
			<label for="derivado">Trámite derivado: </label> 
			<select disabled>
                <?php
                    echo $this->comboSiNo($this->modeloTramites->getDerivado());
                ?>
            </select>
		</div>

		<div data-linea="10">
			<label for="quipux_agr">Quipux Agrocalidad: </label> 
			<input type="text"value="<?php echo $this->modeloTramites->getQuipuxAgr(); ?>"
				disabled="disabled" />
		</div>

	</fieldset>


<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='tramites/guardarAdministrador' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $this->modeloTramites->getIdTramite(); ?>" /> 

	<fieldset id="datosTramite" name="datosTramite">
		<legend>Estado del Trámite</legend>
		
		<div data-linea="13">
			<label for="estado_tramite">Estado: </label> 
			<select id="estado_tramite" name="estado_tramite" required>
				<?php
				echo $this->comboEstadosTramitesAdministrador($this->modeloTramites->getEstadoTramite());
                ?>
            </select>
		</div>

		<div data-linea="23">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset>
</form>
<script type="text/javascript">
	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	            fn_filtrar();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>