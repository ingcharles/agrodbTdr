
<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='laboratorios'
	data-opcion='solicitudes/guardar' data-destino="detalleItem"
	data-accionEnExito="ACTUALIZAR" method="post">
	<fieldset>
		<legend>Solicitudes</legend>

		<div data-linea="3">
			<label for="codigo"> Código </label> <input type="text" id="codigo"
				name="codigo"
				value="<?php echo $this->modeloSolicitudes->getCodigo(); ?>"
				placeholder="Código que identifique la solicitud y el paquete de la(s) muestra(s)"
				required maxlength="512" />
		</div>

		<div data-linea="4">
			<label for="oficio_exoneracion"> No. Memorando </label> <input
				type="text" id="oficio_exoneracion" name="oficio_exoneracion"
				value="<?php echo $this->modeloSolicitudes->getOficioExoneracion(); ?>"
				placeholder="En caso de existir exoneración se debe registrar el número de oficio o memo donde se autoriza"
				required maxlength="512" />
		</div>

		<div data-linea="5">
			<label for="num_muestras_exoneradas"> Número de muestras exoneradas </label>
			<input type="text" id="num_muestras_exoneradas"
				name="num_muestras_exoneradas"
				value="<?php echo $this->modeloSolicitudes->getNumMuestrasExoneradas(); ?>"
				placeholder="Número de muestras exoneradas. Este dato es utilizado para el control cuando se realiza un análisis de forma parcial"
				required maxlength="512" />
		</div>

		<div data-linea="1">
			<label for="tipo_solicitud"> Tipo de solicitud </label> <select
				id="tipo_solicitud" name="tipo_solicitud" required="true">
				<option value="">Seleccionar....</option>
			<?php
// echo $this->combocombotipo_solicitud($this->modeloSolicitudes->getTipoSolicitud());
?>
			</select>
		</div>

		<div data-linea="7">
			<label for="fecha_envio"> Fecha de envío </label> <input type="date"
				id="fecha_envio" name="fecha_envio"
				value="<?php echo $this->modeloSolicitudes->getFechaEnvio(); ?>"
				placeholder="Fecha que el cliente envía la solicitud, esta fecha inicia el proceso."
				required maxlength="512" />
		</div>

		<div data-linea="8">
			<label for="fecha_recepcion"> Fecha recepción </label> <input
				type="date" id="fecha_recepcion" name="fecha_recepcion"
				value="<?php echo $this->modeloSolicitudes->getFechaRecepcion(); ?>"
				placeholder="Fecha que recaudador recepta la solicitud y muestra"
				required maxlength="512" />
		</div>

		<div data-linea="9">
			<label for="fecha_final_estimada"> Fecha de entrega </label> <input
				type="date" id="fecha_final_estimada" name="fecha_final_estimada"
				value="<?php echo $this->modeloSolicitudes->getFechaFinalEstimada(); ?>"
				placeholder="Fecha que se estima que puede ser entregado el informe del análisis."
				required maxlength="512" />
		</div>

		<div data-linea="10">
			<label for="fecha_final_real"> Fecha real de entrega </label> <input
				type="date" id="fecha_final_real" name="fecha_final_real"
				value="<?php echo $this->modeloSolicitudes->getFechaFinalReal(); ?>"
				placeholder="Fecha que el cliente finaliza el trámite, esta fecha finaliza el proceso y es actualizada cuando se envía el informe del resultado de análisis."
				required maxlength="512" />
		</div>

		<div data-linea="1">
			<label for="tipo_autorizacion"> Tipo de autorización </label> <select
				id="tipo_autorizacion" name="tipo_autorizacion" required="true">
				<option value="">Seleccionar....</option>
			<?php
// echo $this->combocombotipo_autorizacion($this->modeloSolicitudes->getTipoAutorizacion());
?>
			</select>
		</div>

		<div data-linea="13">
			<label for="ci_ruc_factura"> Ruc </label> <input type="text"
				id="ci_ruc_factura" name="ci_ruc_factura"
				value="<?php echo $this->modeloSolicitudes->getCiRucFactura(); ?>"
				placeholder="Número de cédula o ruc para la factura" required
				maxlength="512" />
		</div>

		<div data-linea="14">
			<label for="nombre_factura"> Representante legal </label> <input
				type="text" id="nombre_factura" name="nombre_factura"
				value="<?php echo $this->modeloSolicitudes->getNombreFactura(); ?>"
				placeholder="Nombre del cliente para la factura" required
				maxlength="512" />
		</div>

		<div data-linea="15">
			<label for="direccion_factura"> Dirección </label> <input type="text"
				id="direccion_factura" name="direccion_factura"
				value="<?php echo $this->modeloSolicitudes->getDireccionFactura(); ?>"
				placeholder="Dirección del cliente a facturar" required
				maxlength="512" />
		</div>

		<div data-linea="16">
			<label for="telefono_factura"> Teléfono </label> <input type="text"
				id="telefono_factura" name="telefono_factura"
				value="<?php echo $this->modeloSolicitudes->getTelefonoFactura(); ?>"
				placeholder="Teléfono del cliente a facturar" required
				maxlength="512" />
		</div>

		<div data-linea="17">
			<label for="email_factura"> E-mail </label> <input type="text"
				id="email_factura" name="email_factura"
				value="<?php echo $this->modeloSolicitudes->getEmailFactura(); ?>"
				placeholder="E-mail del cliente a facturar" required maxlength="512" />
		</div>

		<div data-linea="17">
			 <?php echo $this->crearRadioEstadoAI($this->modeloSolicitudes->getEstado()); ?>
			</div>

		<div data-linea="19">
			<label for="contacto_proforma"> Contacto </label> <input type="text"
				id="contacto_proforma" name="contacto_proforma"
				value="<?php echo $this->modeloSolicitudes->getContactoProforma(); ?>"
				placeholder="Nombre del contacto de la institución que solicita la proforma"
				required maxlength="512" />
		</div>

		<div data-linea="20">
			<label for="telefono_proforma"> Teléfono </label> <input type="text"
				id="telefono_proforma" name="telefono_proforma"
				value="<?php echo $this->modeloSolicitudes->getTelefonoProforma(); ?>"
				placeholder="Teléfono/Extensión del contacto de la institución que solicita la proforma"
				required maxlength="512" />
		</div>

		<div data-linea="21">
			<input type="hidden" name="id_solicitud" id="id_solicitud"
				value="<?php echo $this->modeloSolicitudes->getIdSolicitud() ?>"> <input
				type="hidden" name="identificador" id="identificador"
				value="<?php echo $this->modeloSolicitudes->getIdentificador() ?>">
			<input type="hidden" name="fecha_registro" id="fecha_registro"
				value="<?php echo $this->modeloSolicitudes->getFechaRegistro() ?>">
			<button type="submit" class="btnenviar">Guardar</button>
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
			  abrir($(this), event, false);
			  abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
			 } else {
			  $("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
			 }
			});
			</script>
