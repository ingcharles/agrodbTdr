<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProgramasControlOficial' data-opcion='catastropredioequidos/guardar' data-destino="detalleItem" data-accionEnExito ="ACTUALIZAR" method="post">
	<fieldset>
		<legend>CatastroPredioEquidos</legend>				

		<div data-linea="1">
			<label for="id_catastro_predio_equidos">id_catastro_predio_equidos </label>
			<input type="text" id="id_catastro_predio_equidos" name="id_catastro_predio_equidos" value="<?php echo $this->modeloCatastroPredioEquidos->getIdCatastroPredioEquidos(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="identificador">identificador </label>
			<input type="text" id="identificador" name="identificador" value="<?php echo $this->modeloCatastroPredioEquidos->getIdentificador(); ?>"
			placeholder="" required maxlength="13" />
		</div>				

		<div data-linea="3">
			<label for="fecha_creacion">fecha_creacion </label>
			<input type="text" id="fecha_creacion" name="fecha_creacion" value="<?php echo $this->modeloCatastroPredioEquidos->getFechaCreacion(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="4">
			<label for="num_solicitud">num_solicitud </label>
			<input type="text" id="num_solicitud" name="num_solicitud" value="<?php echo $this->modeloCatastroPredioEquidos->getNumSolicitud(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="5">
			<label for="fecha">fecha </label>
			<input type="text" id="fecha" name="fecha" value="<?php echo $this->modeloCatastroPredioEquidos->getFecha(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="6">
			<label for="nombre_predio">nombre_predio </label>
			<input type="text" id="nombre_predio" name="nombre_predio" value="<?php echo $this->modeloCatastroPredioEquidos->getNombrePredio(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="7">
			<label for="nombre_propietario">nombre_propietario </label>
			<input type="text" id="nombre_propietario" name="nombre_propietario" value="<?php echo $this->modeloCatastroPredioEquidos->getNombrePropietario(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="8">
			<label for="cedula_propietario">cedula_propietario </label>
			<input type="text" id="cedula_propietario" name="cedula_propietario" value="<?php echo $this->modeloCatastroPredioEquidos->getCedulaPropietario(); ?>"
			placeholder="" required maxlength="13" />
		</div>				

		<div data-linea="9">
			<label for="telefono_propietario">telefono_propietario </label>
			<input type="text" id="telefono_propietario" name="telefono_propietario" value="<?php echo $this->modeloCatastroPredioEquidos->getTelefonoPropietario(); ?>"
			placeholder="" required maxlength="16" />
		</div>				

		<div data-linea="10">
			<label for="correo_electronico_propietario">correo_electronico_propietario </label>
			<input type="text" id="correo_electronico_propietario" name="correo_electronico_propietario" value="<?php echo $this->modeloCatastroPredioEquidos->getCorreoElectronicoPropietario(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="11">
			<label for="nombre_administrador">nombre_administrador </label>
			<input type="text" id="nombre_administrador" name="nombre_administrador" value="<?php echo $this->modeloCatastroPredioEquidos->getNombreAdministrador(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="12">
			<label for="cedula_administrador">cedula_administrador </label>
			<input type="text" id="cedula_administrador" name="cedula_administrador" value="<?php echo $this->modeloCatastroPredioEquidos->getCedulaAdministrador(); ?>"
			placeholder="" required maxlength="13" />
		</div>				

		<div data-linea="13">
			<label for="telefono_administrador">telefono_administrador </label>
			<input type="text" id="telefono_administrador" name="telefono_administrador" value="<?php echo $this->modeloCatastroPredioEquidos->getTelefonoAdministrador(); ?>"
			placeholder="" required maxlength="16" />
		</div>				

		<div data-linea="14">
			<label for="correo_electronico_administrador">correo_electronico_administrador </label>
			<input type="text" id="correo_electronico_administrador" name="correo_electronico_administrador" value="<?php echo $this->modeloCatastroPredioEquidos->getCorreoElectronicoAdministrador(); ?>"
			placeholder="" required maxlength="32" />
		</div>				

		<div data-linea="15">
			<label for="id_provincia">id_provincia </label>
			<input type="text" id="id_provincia" name="id_provincia" value="<?php echo $this->modeloCatastroPredioEquidos->getIdProvincia(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="16">
			<label for="provincia">provincia </label>
			<input type="text" id="provincia" name="provincia" value="<?php echo $this->modeloCatastroPredioEquidos->getProvincia(); ?>"
			placeholder="" required maxlength="64" />
		</div>				

		<div data-linea="17">
			<label for="id_canton">id_canton </label>
			<input type="text" id="id_canton" name="id_canton" value="<?php echo $this->modeloCatastroPredioEquidos->getIdCanton(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="18">
			<label for="canton">canton </label>
			<input type="text" id="canton" name="canton" value="<?php echo $this->modeloCatastroPredioEquidos->getCanton(); ?>"
			placeholder="" required maxlength="64" />
		</div>				

		<div data-linea="19">
			<label for="id_parroquia">id_parroquia </label>
			<input type="text" id="id_parroquia" name="id_parroquia" value="<?php echo $this->modeloCatastroPredioEquidos->getIdParroquia(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="20">
			<label for="parroquia">parroquia </label>
			<input type="text" id="parroquia" name="parroquia" value="<?php echo $this->modeloCatastroPredioEquidos->getParroquia(); ?>"
			placeholder="" required maxlength="64" />
		</div>				

		<div data-linea="21">
			<label for="direccion_predio">direccion_predio </label>
			<input type="text" id="direccion_predio" name="direccion_predio" value="<?php echo $this->modeloCatastroPredioEquidos->getDireccionPredio(); ?>"
			placeholder="" required maxlength="128" />
		</div>				

		<div data-linea="22">
			<label for="utm_x">utm_x </label>
			<input type="text" id="utm_x" name="utm_x" value="<?php echo $this->modeloCatastroPredioEquidos->getUtmX(); ?>"
			placeholder="" required maxlength="16" />
		</div>				

		<div data-linea="23">
			<label for="utm_y">utm_y </label>
			<input type="text" id="utm_y" name="utm_y" value="<?php echo $this->modeloCatastroPredioEquidos->getUtmY(); ?>"
			placeholder="" required maxlength="16" />
		</div>				

		<div data-linea="24">
			<label for="utm_z">utm_z </label>
			<input type="text" id="utm_z" name="utm_z" value="<?php echo $this->modeloCatastroPredioEquidos->getUtmZ(); ?>"
			placeholder="" required maxlength="16" />
		</div>				

		<div data-linea="25">
			<label for="altitud">altitud </label>
			<input type="text" id="altitud" name="altitud" value="<?php echo $this->modeloCatastroPredioEquidos->getAltitud(); ?>"
			placeholder="" required maxlength="16" />
		</div>				

		<div data-linea="26">
			<label for="extension">extension </label>
			<input type="text" id="extension" name="extension" value="<?php echo $this->modeloCatastroPredioEquidos->getExtension(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="27">
			<label for="latitud">latitud </label>
			<input type="text" id="latitud" name="latitud" value="<?php echo $this->modeloCatastroPredioEquidos->getLatitud(); ?>"
			placeholder="" required maxlength="16" />
		</div>				

		<div data-linea="28">
			<label for="longitud">longitud </label>
			<input type="text" id="longitud" name="longitud" value="<?php echo $this->modeloCatastroPredioEquidos->getLongitud(); ?>"
			placeholder="" required maxlength="16" />
		</div>				

		<div data-linea="29">
			<label for="zona">zona </label>
			<input type="text" id="zona" name="zona" value="<?php echo $this->modeloCatastroPredioEquidos->getZona(); ?>"
			placeholder="" required maxlength="4" />
		</div>				

		<div data-linea="30">
			<label for="estado">estado </label>
			<input type="text" id="estado" name="estado" value="<?php echo $this->modeloCatastroPredioEquidos->getEstado(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="31">
			<label for="identificador_modificacion">identificador_modificacion </label>
			<input type="text" id="identificador_modificacion" name="identificador_modificacion" value="<?php echo $this->modeloCatastroPredioEquidos->getIdentificadorModificacion(); ?>"
			placeholder="" required maxlength="13" />
		</div>				

		<div data-linea="32">
			<label for="fecha_modificacion">fecha_modificacion </label>
			<input type="text" id="fecha_modificacion" name="fecha_modificacion" value="<?php echo $this->modeloCatastroPredioEquidos->getFechaModificacion(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="33">
			<label for="nueva_inspeccion">nueva_inspeccion </label>
			<input type="text" id="nueva_inspeccion" name="nueva_inspeccion" value="<?php echo $this->modeloCatastroPredioEquidos->getNuevaInspeccion(); ?>"
			placeholder="" required maxlength="2" />
		</div>				

		<div data-linea="34">
			<label for="fecha_nueva_inspeccion">fecha_nueva_inspeccion </label>
			<input type="text" id="fecha_nueva_inspeccion" name="fecha_nueva_inspeccion" value="<?php echo $this->modeloCatastroPredioEquidos->getFechaNuevaInspeccion(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="35">
			<label for="identificador_cierre">identificador_cierre </label>
			<input type="text" id="identificador_cierre" name="identificador_cierre" value="<?php echo $this->modeloCatastroPredioEquidos->getIdentificadorCierre(); ?>"
			placeholder="" required maxlength="13" />
		</div>				

		<div data-linea="36">
			<label for="fecha_cierre">fecha_cierre </label>
			<input type="text" id="fecha_cierre" name="fecha_cierre" value="<?php echo $this->modeloCatastroPredioEquidos->getFechaCierre(); ?>"
			placeholder="" required maxlength="8" />
		</div>				

		<div data-linea="37">
			<label for="observaciones">observaciones </label>
			<input type="text" id="observaciones" name="observaciones" value="<?php echo $this->modeloCatastroPredioEquidos->getObservaciones(); ?>"
			placeholder="" required maxlength="512" />
		</div>				

		<div data-linea="38">
			<label for="imagen_mapa">imagen_mapa </label>
			<input type="text" id="imagen_mapa" name="imagen_mapa" value="<?php echo $this->modeloCatastroPredioEquidos->getImagenMapa(); ?>"
			placeholder="" required maxlength="1024" />
		</div>				

		<div data-linea="39">
			<label for="ruta_informe">ruta_informe </label>
			<input type="text" id="ruta_informe" name="ruta_informe" value="<?php echo $this->modeloCatastroPredioEquidos->getRutaInforme(); ?>"
			placeholder="" required maxlength="1024" />
		</div>

		<div data-linea="40">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset >
</form >
<script type ="text/javascript">
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
