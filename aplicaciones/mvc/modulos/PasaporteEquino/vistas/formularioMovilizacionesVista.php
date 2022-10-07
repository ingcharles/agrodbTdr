<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<div class="abrirFormulario">	
	<fieldset>
		<legend>Datos Generales</legend>	
		
		<div data-linea="1">
			<label for="nombre_asociacion">Identificador <?php echo ($this->tipoUsuario == 'Asociacion' ? 'Organización Ecuestre':'Centro de Concentración de Animales');?>: </label>
			<?php echo $this->modeloMovilizaciones->getIdentificador(); ?>
		</div>
		
		<div data-linea="2">
			<label><?php echo ($this->tipoUsuario == 'Asociacion' ? 'Organización Ecuestre emisor':'Centro de Concentración de Animales emisor');?>: </label> 
			<?php echo $this->modeloMovilizaciones->getNombreEmisor(); ?>
		</div>	
		
		<div data-linea="3">
			<label>Provincia Emisión: </label>
			<?php echo $this->modeloMovilizaciones->getProvinciaSolicitud(); ?>
		</div>	
		
		<hr />
		
		<div data-linea="4">
			<label for="pasaporte_equino">Número de pasaporte equino: </label>
			<?php echo $this->modeloMovilizaciones->getPasaporteEquino(); ?>
		</div>
		
		<div data-linea="5">
			<label for="identificador_miembro">Identificador Propietario equino: </label>
			<?php echo $this->modeloMovilizaciones->getIdentificadorMiembro(); ?>
		</div>				

		<div data-linea="6">
			<label for="nombre_miembro">Nombre Propietario equino: </label>
			<?php echo $this->modeloMovilizaciones->getNombreMiembro(); ?>
		</div>
		
		<hr />
		
		<div data-linea="7">
			<label for="identificador_solicitante">Identificador Solicitante: </label>
			<?php echo $this->modeloMovilizaciones->getIdentificadorSolicitante(); ?>
		</div>				

		<div data-linea="8">
			<label for="nombre_solicitante">Nombre Solicitante: </label>
			<?php echo $this->modeloMovilizaciones->getNombreSolicitante(); ?>
		</div>	
		
		<hr />
		
		<div data-linea="9">
			<label for="fecha_creacion">Fecha creación: </label>
			<?php echo date('Y-m-d H:i', strtotime($this->modeloMovilizaciones->getFechaCreacion())); ?>
		</div>				

		<div data-linea="10">
			<label for="nombre_solicitante">Fecha inicio vigencia: </label>
			<?php echo $this->modeloMovilizaciones->getFechaInicioMovilizacion(); ?>
		</div>
		
		<div data-linea="10">
			<label for="nombre_solicitante">Fecha fin vigencia: </label>
			<?php echo $this->modeloMovilizaciones->getFechaFinMovilizacion(); ?>
		</div>		
		
		<hr />
		
		<div data-linea="11">
			<label>Guía de movilización: </label>
			<?php echo 
			($this->modeloMovilizaciones->getRutaCertificado() != '' ? '<a href="'.URL_GUIA_PROYECTO . '/' .$this->modeloMovilizaciones->getRutaCertificado().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click para descargar documento</a>' : 'No hay un archivo adjunto'); ?>
		</div>	
				

	</fieldset>				

	<fieldset>
		<legend>Datos Origen</legend>		
		
		<div data-linea="1">
			<label for="nombre_asociacion">Identificador Sitio origen: </label>
			<?php echo $this->modeloMovilizaciones->getIdentificadorPropietarioOrigen(); ?>
		</div>
		
		<div data-linea="2">
			<label for="nombre_asociacion">Propietario Sitio origen: </label>
			<?php echo $this->modeloMovilizaciones->getNombrePropietarioOrigen(); ?>
		</div>

		
		<hr/>
		
		<div data-linea="3">
			<label>Predio origen: </label>
			<?php echo $this->modeloMovilizaciones->getNombreUbicacionOrigen(); ?>
		</div>
		
		<div data-linea="4">
			<label for="codigo_ubicacion_origen">Código origen: </label>
			<?php echo $this->modeloMovilizaciones->getCodigoUbicacionOrigen(); ?>
		</div>				

		<div data-linea="5">
			<label for="provincia_origen">Provincia origen: </label>
			<?php echo $this->modeloMovilizaciones->getProvinciaOrigen(); ?>
		</div>
		
		<div data-linea="5">
			<label for="canton_origen">Cantón origen: </label>
			<?php echo $this->modeloMovilizaciones->getCantonOrigen(); ?>
		</div>
		
		<div data-linea="6">
			<label for="parroquia_origen">Parroquia origen: </label>
			<?php echo $this->modeloMovilizaciones->getParroquiaOrigen(); ?>
		</div>				

		<div data-linea="7">
			<label for="direccion_origen">Dirección origen: </label>
			<?php echo $this->modeloMovilizaciones->getDireccionOrigen(); ?>
		</div>
		
    </fieldset>
    
    <fieldset>
		<legend>Datos Destino</legend>

		<div data-linea="1">
			<label for="tipo_destino">Tipo de Destino: </label>
			<?php echo $this->modeloMovilizaciones->getTipoDestino();?>
		</div>	
		
		<hr />
				
		<div data-linea="2">
			<label for="nombre_asociacion">Identificador Sitio destino: </label>
			<?php echo $this->modeloMovilizaciones->getIdentificadorPropietarioDestino(); ?>
		</div>
		
		<div data-linea="3">
			<label for="nombre_asociacion">Propietario Sitio destino: </label>
			<?php echo $this->modeloMovilizaciones->getNombrePropietarioDestino(); ?>
		</div>

		
		<hr/>			
		
		<div data-linea="4">
			<label>Predio destino: </label>
			<?php echo $this->modeloMovilizaciones->getNombreUbicacionDestino(); ?>
		</div>

		<div data-linea="5">
			<label for="codigo_ubicacion_destino">Código destino: </label>
			<?php echo $this->modeloMovilizaciones->getCodigoUbicacionDestino(); ?>
		</div>				
				

		<div data-linea="6">
			<label for="provincia_destino">Provincia destino: </label>
			<?php echo $this->modeloMovilizaciones->getProvinciaDestino(); ?>
		</div>				

		<div data-linea="6">
			<label for="canton_destino">Cantón destino: </label>
			<?php echo $this->modeloMovilizaciones->getCantonDestino(); ?>
		</div>				

		<div data-linea="7">
			<label for="parroquia_destino">Parroquia destino: </label>
			<?php echo $this->modeloMovilizaciones->getParroquiaDestino(); ?>
		</div>				

		<div data-linea="8">
			<label for="direccion_destino">Dirección destino: </label>
			<?php echo $this->modeloMovilizaciones->getDireccionDestino(); ?>
		</div>							
    </fieldset>
    
    <fieldset>
    	<legend>Información de Movilización</legend>
		<div data-linea="1">
			<label for="medio_transporte">Medio de Transporte </label>
			<?php echo $this->modeloMovilizaciones->getMedioTransporte(); ?>
			
		</div>				

		<div data-linea="1">
			<label for="placa_transporte">Placa: </label>
			<?php echo ($this->modeloMovilizaciones->getPlacaTransporte()!=''?$this->modeloMovilizaciones->getPlacaTransporte():'NA'); ?>
		</div>				

		<div data-linea="2">
			<label for="nombre_propietario_transporte">Propietario del transporte: </label>
			<?php echo ($this->modeloMovilizaciones->getNombrePropietarioTransporte()!=''?$this->modeloMovilizaciones->getNombrePropietarioTransporte():'NA'); ?>
		</div>				

		<hr />
		
		<div data-linea="3">
			<label for="identificador_conductor">Identificador conductor: </label>
			<?php echo ($this->modeloMovilizaciones->getIdentificadorConductor()!=''?$this->modeloMovilizaciones->getIdentificadorConductor():'NA'); ?>
		</div>				

		<div data-linea="3">
			<label for="nombre_conductor">Nombre conductor: </label>
			<?php echo ($this->modeloMovilizaciones->getNombreConductor()!=''?$this->modeloMovilizaciones->getNombreConductor():'NA'); ?>
		</div>		
		
		<hr />
		
		<div data-linea="4">
			<label for="observacion_transporte">Observaciones: </label>
			<?php echo ($this->modeloMovilizaciones->getObservacionTransporte()!=''?$this->modeloMovilizaciones->getObservacionTransporte():'NA'); ?>
		</div>	

	</fieldset >	
</div >

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PasaporteEquino' data-opcion='Movilizaciones/guardarMovilizacionEquino' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post" class="editarFormulario">
	<input type="hidden" id="id" name="id" />
	
	<fieldset>
		<legend>Datos Generales</legend>				

		<div data-linea="1">
			<label><?php echo ($this->tipoUsuario == 'Asociacion' ? 'Organización Ecuestre solicitante':'Centro de Concentración de Animales solicitante');?>: </label> 
			<input type="text" id="nombre_emisor" name="nombre_emisor" value="<?php echo ($this->tipoUsuario == 'Asociacion' ? $this->modeloOrganizacionEcuestre->current()->nombre_asociacion : $this->razonSocialCC ); ?>" readonly="readonly" />
			<input type="hidden" id="tipo_usuario" name="tipo_usuario" value="<?php echo $this->tipoUsuario; ?>" readonly="readonly" />
		</div>	
		
		<div data-linea="2">
			<label>Provincia Emisión: </label>
			<input type="text" id="provincia_solicitud" name="provincia_solicitud" value="<?php echo ($this->tipoUsuario == 'Asociacion' ? $this->modeloOrganizacionEcuestre->current()->provincia : $this->provinciaCC ); ?>" readonly="readonly" />
		</div>	
		
		<hr />
		
		<div data-linea="3">
			<label for="identificador_solicitante">Identificador Solicitante: </label>
			<input type="text" id="identificador_solicitante" name="identificador_solicitante" value="<?php echo $this->modeloMovilizaciones->getIdentificadorSolicitante(); ?>" required maxlength="13" />
		</div>				

		<div data-linea="3">
			<label for="nombre_solicitante">Nombre Solicitante: </label>
			<input type="text" id="nombre_solicitante" name="nombre_solicitante" value="<?php echo $this->modeloMovilizaciones->getNombreSolicitante(); ?>" required maxlength="128" />
		</div>				

	</fieldset>				

	<fieldset>
		<legend>Datos Origen</legend>				

		<div data-linea="1">
			<label for="pasaporte_equino">Número de pasaporte equino: </label>
			<input type="text" id="pasaporte_equino" name="pasaporte_equino" value="<?php echo $this->modeloMovilizaciones->getPasaporteEquino(); ?>" maxlength="13" />
		</div>
		
		<hr class="datosOrigen"/>
		
		<div data-linea="2" class="datosOrigen">
			<label for="nombre_asociacion">Asociación: </label>
			<input type="text" id="nombre_asociacion" name="nombre_asociacion" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getNombreAsociacion(); ?>"  />
		</div>	
		
		<div data-linea="3" class="datosOrigen">
			<label for="identificador_miembro">Identificador propietario: </label>
			<input type="text" id="identificador_miembro" name="identificador_miembro" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdentificadorMiembro(); ?>" />
		</div>				

		<div data-linea="3" class="datosOrigen">
			<label for="nombre_miembro">Nombre Propietario: </label>
			<input type="text" id="nombre_miembro" name="nombre_miembro" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getNombreMiembro(); ?>" />
		</div>			
		
		<div data-linea="4" class="datosOrigen">
			<label for="codigo_ubicacion_origen">Ubicación actual: </label>
			<input type="text" id="codigo_ubicacion_origen" name="codigo_ubicacion_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getCodigoUbicacionOrigen(); ?>" />
		</div>				

		<div data-linea="5" class="datosOrigen">
			<label for="nombre_ubicacion_origen">Predio actual: </label>
			<input type="text" id="nombre_ubicacion_origen" name="nombre_ubicacion_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getNombreUbicacionOrigen(); ?>" />
		</div>	
		
		<div data-linea="6" class="datosOrigen">
			<label for="provincia_origen">Provincia actual: </label>
			<input type="text" id="provincia_origen" name="provincia_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getProvinciaOrigen(); ?>" />
		</div>
		
		<div data-linea="6" class="datosOrigen">
			<label for="canton_origen">Cantón actual: </label>
			<input type="text" id="canton_origen" name="canton_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getCantonOrigen(); ?>" />
		</div>
		
		<div data-linea="7" class="datosOrigen">
			<label for="parroquia_origen">Parroquia actual: </label>
			<input type="text" id="parroquia_origen" name="parroquia_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getParroquiaOrigen(); ?>" />
		</div>				

		<div data-linea="7" class="datosOrigen">
			<label for="direccion_origen">Dirección actual: </label>
			<input type="text" id="direccion_origen" name="direccion_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getDireccionOrigen(); ?>" />
		</div>

		<div data-linea="8">
			<input type="hidden" id="id_asociacion" name="id_asociacion" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdAsociacion(); ?>" />
			<input type="hidden" id="id_equino" name="id_equino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdEquino(); ?>" />
			<input type="hidden" id="id_miembro" name="id_miembro" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdMiembro(); ?>" />
			<input type="hidden" id="id_ubicacion_actual" name="id_ubicacion_actual" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdUbicacionActual(); ?>"/>			
			<input type="hidden" id="identificador_propietario_origen" name="identificador_propietario_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdentificadorPropietarioOrigen(); ?>"/>
			<input type="hidden" id="nombre_propietario_origen" name="nombre_propietario_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getNombrePropietarioOrigen(); ?>"/>			
			<input type="hidden" id="id_provincia_origen" name="id_provincia_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdProvinciaOrigen(); ?>" />
			<input type="hidden" id="id_canton_origen" name="id_canton_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdCantonOrigen(); ?>"/>
			<input type="hidden" id="id_parroquia_origen" name="id_parroquia_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdParroquiaOrigen(); ?>" />
			<input type="hidden" id="id_sitio_origen" name="id_sitio_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdSitioOrigen(); ?>"/>
			<input type="hidden" id="id_area_origen" name="id_area_origen" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdAreaOrigen(); ?>" />
			<input type="hidden" id="id_especie" name="id_especie" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdEspecie(); ?>" />
			<input type="hidden" id="id_raza" name="id_raza" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdRaza(); ?>" />
			<input type="hidden" id="id_categoria" name="id_categoria" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdCategoria(); ?>" />
		</div>				

						

</fieldset>

<fieldset class="datosDestino">
	<legend>Datos Destino</legend>

		<div data-linea="1" class="datosDestino">
			<label for="tipo_destino">Tipo de Destino: </label>
			<select id="tipo_destino" name="tipo_destino">
				<?php echo $this->comboTiposDestinoMovilizacion($this->modeloMovilizaciones->getTipoDestino());?>
			</select>
		</div>	
		
		<div data-linea="1" class="datosDestino">
			<label for="provincia_busqueda">Provincia: </label>
			<select id="provincia_busqueda" name="provincia_busqueda">
			</select>
		</div>
		
		<div data-linea="2" class="datosDestinoUbicacion">
			<label for="codigo_busqueda">Código de Sitio: </label>
			<input type="text" id="codigo_busqueda" name="codigo_busqueda" />
		</div>				

		<div data-linea="2" class="datosDestinoUbicacion">
			<label for="nombre_busqueda">Nombre del Sitio: </label>
			<input type="text" id="nombre_busqueda" name="nombre_busqueda" />
		</div>
		
		<div data-linea="0" class="datosDestinoUbicacionSitio">
			<label for="sitio_destino">Sitio Destino: </label>
			<select id="sitio_destino" name="sitio_destino">			
			</select>
		</div>
		
		<div data-linea="0" class="datosDestinoUbicacionSitio">
			<label for="area_destino">Área Destino: </label>
			<select id="area_destino" name="area_destino">			
			</select>
		</div>
		
		<div data-linea="30" class="datosDestinoUbicacionPredio">
			<label for="predio_destino">Predio Destino: </label>
			<select id="predio_destino" name="predio_destino">			
			</select>
		</div>
		
		<hr class="datosDestinoDetalle"/>	
		
		<div data-linea="31" class="datosDestinoDetalle">
			<label for="identificador_propietario_destino">Identificador propietario: </label>
			<input type="text" id="identificador_propietario_destino" name="identificador_propietario_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdentificadorPropietarioDestino(); ?>"/>
		</div>
		
		<div data-linea="31" class="datosDestinoDetalle">
			<label for="nombre_propietario_destino">Nombre propietario: </label>
			<input type="text" id="nombre_propietario_destino" name="nombre_propietario_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getNombrePropietarioDestino(); ?>"/>
		</div>		

		<div data-linea="3" class="datosDestinoDetalle">
			<label for="codigo_ubicacion_destino">Ubicación destino: </label>
			<input type="text" id="codigo_ubicacion_destino" name="codigo_ubicacion_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getCodigoUbicacionDestino(); ?>" />
		</div>				

		<div data-linea="3" class="datosDestinoDetalle">
			<label for="nombre_ubicacion_destino">Predio destino: </label>
			<input type="text" id="nombre_ubicacion_destino" name="nombre_ubicacion_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getNombreUbicacionDestino(); ?>" />
		</div>				

		<div data-linea="4" class="datosDestinoDetalle">
			<label for="provincia_destino">Provincia destino: </label>
			<input type="text" id="provincia_destino" name="provincia_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getProvinciaDestino(); ?>" />
		</div>				

		<div data-linea="4" class="datosDestinoDetalle">
			<label for="canton_destino">Cantón destino: </label>
			<input type="text" id="canton_destino" name="canton_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getCantonDestino(); ?>"
			placeholder="" required maxlength="64" />
		</div>				

		<div data-linea="5" class="datosDestinoDetalle">
			<label for="parroquia_destino">Parroquia destino: </label>
			<input type="text" id="parroquia_destino" name="parroquia_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getParroquiaDestino(); ?>" />
		</div>				

		<div data-linea="5" class="datosDestinoDetalle">
			<label for="direccion_destino">Dirección destino: </label>
			<input type="text" id="direccion_destino" name="direccion_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getDireccionDestino(); ?>" />
		</div>							

		<div data-linea="6">
			<input type="hidden" id="id_ubicacion_destino" name="id_ubicacion_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdUbicacionDestino(); ?>" />
			<input type="hidden" id="id_provincia_destino" name="id_provincia_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdProvinciaDestino(); ?>" />
			<input type="hidden" id="id_canton_destino" name="id_canton_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdCantonDestino(); ?>" />
			<input type="hidden" id="id_parroquia_destino" name="id_parroquia_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdParroquiaDestino(); ?>" />
			<input type="hidden" id="id_sitio_destino" name="id_sitio_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdSitioDestino(); ?>" />
    		<input type="hidden" id="id_area_destino" name="id_area_destino" readonly="readonly" value="<?php echo $this->modeloMovilizaciones->getIdAreaDestino(); ?>" />
		</div>
</fieldset>

<fieldset class="datosMovilizacion">
	<legend>Información de Movilización</legend>
		<div data-linea="1">
			<label for="medio_transporte">Medio de Transporte </label>
			<select id="medio_transporte" name="medio_transporte" required>
				<option value>Seleccione....</option>
				<?php echo $this->comboMediosTransporteMovilizaciones($this->modeloMovilizaciones->getMedioTransporte()); ?>
			</select>
			
		</div>				

		<div data-linea="1">
			<label for="placa_transporte">Placa: </label>
			<input type="text" id="placa_transporte" name="placa_transporte" value="<?php echo $this->modeloMovilizaciones->getPlacaTransporte(); ?>" placeholder="Ej: AAA-0000" 
				data-er="[A-Z]{3}-[0-9]{3,4}" data-inputmask="'mask': 'aaa-9999'" maxlength="8" onblur="this.value=this.value.toUpperCase()" />
		</div>				

		<div data-linea="2">
			<label for="nombre_propietario_transporte">Propietario del transporte: </label>
			<input type="text" id="nombre_propietario_transporte" name="nombre_propietario_transporte" value="<?php echo $this->modeloMovilizaciones->getNombrePropietarioTransporte(); ?>" maxlength="128" />
		</div>				

		<hr />
		
		<div data-linea="3">
			<label for="identificador_conductor">Identificador conductor: </label>
			<input type="text" id="identificador_conductor" name="identificador_conductor" value="<?php echo $this->modeloMovilizaciones->getIdentificadorConductor(); ?>" maxlength="13" />
		</div>				

		<div data-linea="3">
			<label for="nombre_conductor">Nombre conductor: </label>
			<input type="text" id="nombre_conductor" name="nombre_conductor" value="<?php echo $this->modeloMovilizaciones->getNombreConductor(); ?>" maxlength="128" />
		</div>		
		
		<hr />
		
		<div data-linea="4">
			<label for="fecha_inicio_movilizacion">Fecha movilización: </label>
			<input type="datetime-local" id="fecha_inicio_movilizacion" name="fecha_inicio_movilizacion" value="<?php echo $this->modeloMovilizaciones->getFechaInicioMovilizacion(); ?>" required maxlength="8" />
		</div>				

		<div data-linea="5">
			<label for="observacion_transporte">Observaciones: </label>
			<input type="text" id="observacion_transporte" name="observacion_transporte" value="<?php echo $this->modeloMovilizaciones->getObservacionTransporte(); ?>" maxlength="1024" />
		</div>				

	</fieldset >
	
	
	
	<div id="cargarMensajeTemporal"></div>
	
	<div data-linea="48">
		<button type="submit" class="guardar">Guardar</button>
	</div>		
</form >

<script type ="text/javascript">
var formulario = <?php echo json_encode($this->formulario); ?>;
var tipoUsuario = <?php echo json_encode($this->tipoUsuario); ?>;
var combo = "<option>Seleccione....</option>";

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
/*
		//alert(new Date().toISOString().split(".")[0]);
		var fechaAhora = new Date();//.toISOString().split(".")[0];
		var fecha = fechaAhora.toISOString().split(".")[0];
		var nueva = fecha.split(":")[0] + ":" + fecha.split(":")[1];
		alert(new Date());
		$("#fecha_inicio_movilizacion").attr('min', nueva);
alert(nueva);
		//fecha_inicio_movilizacion
*/

	function toIsoString(date) {
	  var tzo = -date.getTimezoneOffset(),
	      dif = tzo >= 0 ? '+' : '-',
	      pad = function(num) {
	          return (num < 10 ? '0' : '') + num;
	      };

	  return date.getFullYear() +
	      '-' + pad(date.getMonth() + 1) +
	      '-' + pad(date.getDate()) +
	      'T' + pad(date.getHours()) +
	      ':' + pad(date.getMinutes()) +
	      ':' + pad(date.getSeconds()) +
	      dif + pad(Math.floor(Math.abs(tzo) / 60)) +
	      ':' + pad(Math.abs(tzo) % 60);
	}

	var dt = new Date();
	//alert(toIsoString(dt));
	var fecha = toIsoString(dt).split(".")[0];
	var nueva = fecha.split(":")[0] + ":" + fecha.split(":")[1];
	//alert(new Date());
	$("#fecha_inicio_movilizacion").attr('min', nueva);
	
	
		if(formulario == 'nuevo'){
			$(".editarFormulario").show();
			$(".abrirFormulario").hide();
		}else{
			$(".editarFormulario").hide();
			$(".abrirFormulario").show();
		}

		$(".datosOrigen").hide();
		$(".datosDestino").hide();
		$(".datosDestinoUbicacion").hide();
		$(".datosDestinoUbicacionSitio").hide();
		$(".datosDestinoUbicacionPredio").hide();
		$(".datosDestinoDetalle").hide();
		$(".datosMovilizacion").hide();
	 });

	//ORIGEN
	$("#pasaporte_equino").change(function () {
		$(".datosOrigen").hide();
		$(".datosDestino").hide();
		$(".datosDestinoUbicacion").hide();
		$(".datosDestinoUbicacionSitio").hide();
		$(".datosDestinoUbicacionPredio").hide();
		$(".datosDestinoDetalle").hide();
		$(".datosMovilizacion").hide();
		fn_limpiarDatosEquino();
		
		if ($("#pasaporte_equino").val() != '' ) {
			fn_buscarEquinoXPasaporte();		
        }else{
			alert('Debe ingresar un número de cédula o RUC válido');
			fn_limpiarDatosEquino();
			$(".datosOrigen").hide();
			$(".datosDestino").hide();
			$(".datosDestinoUbicacion").hide();
			$(".datosDestinoUbicacionSitio").hide();
			$(".datosDestinoUbicacionPredio").hide();
			$(".datosDestinoDetalle").hide();
			$(".datosMovilizacion").hide();
        }
    });

	//Función para mostrar las provincias donde el operador tiene predios en el módulo de programas de control oficial
    function fn_buscarEquinoXPasaporte() {
    	var pasaporteEquino = $("#pasaporte_equino").val();
        
        if (pasaporteEquino != "" ){
        	$.post("<?php echo URL ?>PasaporteEquino/Equinos/buscarEquinoXPasaporte",
               {
                tipoUsuario : tipoUsuario,
                pasaporteEquino : pasaporteEquino
               }, function (data) {
                   if(data.resultado == 'Fallo'){
                	   fn_limpiarDatosEquino();
                	   mostrarMensaje(data.mensaje,"FALLO");
                	   $(".datosOrigen").hide();
                	   $(".datosDestino").hide();
                	   $(".datosDestinoUbicacion").hide();
                	   $(".datosDestinoUbicacionSitio").hide();
                	   $(".datosDestinoUbicacionPredio").hide();
               		   $(".datosDestinoDetalle").hide();
               		$(".datosMovilizacion").hide();
                   }else{
                	   fn_cargarDatosEquino(data);
                	   mostrarMensaje(data.mensaje,"EXITO");
                	   $(".datosOrigen").show();
                	   $(".datosDestino").show();
                	   $(".datosDestinoUbicacion").hide();
                	   $(".datosDestinoUbicacionSitio").hide();
                	   $(".datosDestinoUbicacionPredio").hide();
                	   $(".datosDestinoDetalle").hide();
                	   $(".datosMovilizacion").hide();
                   }
            }, 'json');
        }else{
            if(!$.trim($("#pasaporte_equino").val())){
    			$("#pasaporte_equino").addClass("alertaCombo");
    		}

        	fn_limpiarDatosEquino()

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    function fn_cargarDatosEquino(data) {
    	$("#id_asociacion").val(data.idOrganizacionEcuestre);
    	$("#nombre_asociacion").val(data.nombreAsociacion);
    	$("#id_miembro").val(data.idMiembro);
    	$("#identificador_miembro").val(data.identificadorMiembro);
    	$("#nombre_miembro").val(data.nombreMiembro);
    	$("#id_equino").val(data.idEquino);
    	$("#id_ubicacion_actual").val(data.ubicacionActual);
    	$("#id_especie").val(data.idEspecie);
    	$("#id_raza").val(data.idRaza);    	
    	$("#id_categoria").val(data.idCategoria);
    	$("#codigo_ubicacion_origen").val(data.numSolicitud);
    	$("#nombre_ubicacion_origen").val(data.nombrePredio);
    	$("#identificador_propietario_origen").val(data.cedulaPropietario);
    	$("#nombre_propietario_origen").val(data.nombrePropietario);    	
    	$("#id_provincia_origen").val(data.idProvincia);
    	$("#provincia_origen").val(data.provincia);
    	$("#id_canton_origen").val(data.idCanton);
    	$("#canton_origen").val(data.canton);
    	$("#id_parroquia_origen").val(data.idParroquia);
    	$("#parroquia_origen").val(data.parroquia);
    	$("#direccion_origen ").val(data.direccionPredio);

    	$("#id_sitio_origen").val(data.idSitio);
    	$("#id_area_origen ").val(data.idArea);
    } 

    function fn_limpiarDatosEquino() {
    	$("#id_asociacion").val('');
    	$("#nombre_asociacion").val('');
    	$("#id_miembro").val('');
    	$("#identificador_miembro").val('');
    	$("#nombre_miembro").val('');
    	$("#id_equino").val('');
    	$("#id_ubicacion_actual").val('');
    	$("#id_especie").val('');
    	$("#id_raza").val('');    	
    	$("#id_categoria").val('');
    	$("#codigo_ubicacion_origen").val('');
    	$("#nombre_ubicacion_origen").val('');
    	$("#identificador_propietario_origen").val('');
    	$("#nombre_propietario_origen").val('');
    	$("#id_provincia_origen").val('');
    	$("#provincia_origen").val('');
    	$("#id_canton_origen").val('');
    	$("#canton_origen").val('');
    	$("#id_parroquia_origen").val('');
    	$("#parroquia_origen").val('');
    	$("#direccion_origen ").val('');

    	$("#id_sitio_origen").val('');
    	$("#id_area_origen ").val('');
    }

    //DESTINO
    $("#tipo_destino").change(function () {
    	$(".datosDestinoUbicacion").hide();
    	$(".datosDestinoUbicacionSitio").hide();
    	$(".datosDestinoUbicacionPredio").hide();
		$(".datosDestinoDetalle").hide();
		$(".datosMovilizacion").hide();
		fn_limpiarDatosDestino();
		
		if ($("#tipo_destino option:selected").val() != '' ) {	
			fn_buscarProvinciaXPrediosOperacionesRegistradas();					
        }else{
			alert('Debe ingresar un tipo de destino válido');
			fn_limpiarDatosDestino();
			$(".datosDestinoUbicacion").hide();
			$(".datosDestinoUbicacionSitio").hide();
			$(".datosDestinoUbicacionPredio").hide();
			$(".datosDestinoDetalle").hide();
			$(".datosMovilizacion").hide();
        }
    });

  	//Función para mostrar las provincias donde se tiene predios en el módulo de programas de control oficial
  	//u operaciones de centro de concentración en el módulo de registro de operador
    function fn_buscarProvinciaXPrediosOperacionesRegistradas() {
    	var pasaporte = $("#pasaporte_equino").val();
    	var tipoDestino = $("#tipo_destino option:selected").val();
        
        if (pasaporte != "" && tipoDestino != ""){
        	$.post("<?php echo URL ?>PasaporteEquino/Movilizaciones/comboProvinciaXPrediosOperacionesRegistradas",
               {
                tipoDestino : tipoDestino
               }, function (data) {
            	   $("#provincia_busqueda").html(data);
            });
        }else{
            $("#provincia_busqueda").html(combo);
        	
        	if(!$.trim($("#pasaporte_equino").val())){
    			$("#pasaporte_equino").addClass("alertaCombo");
    		}

        	if(!$.trim($("#tipo_destino option:selected").val())){
    			$("#tipo_destino").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#provincia_busqueda").change(function () {
    	$(".datosDestinoUbicacion").hide();
    	$(".datosDestinoUbicacionSitio").hide();
    	$(".datosDestinoUbicacionPredio").hide();
    	$(".datosDestinoDetalle").hide();
    	$(".datosMovilizacion").hide();
    	fn_limpiarParametrosBusqueda();
		fn_limpiarDatosDestino();
		
		if ($("#provincia_busqueda option:selected").val() != '' ) {	
			$(".datosDestinoUbicacion").show();				
        }else{
			alert('Debe ingresar una provincia válida');
			
			fn_limpiarParametrosBusqueda();
			fn_limpiarDatosDestino();
			$(".datosDestinoUbicacion").hide();
			$(".datosDestinoUbicacionSitio").hide();
			$(".datosDestinoUbicacionPredio").hide();
			$(".datosDestinoDetalle").hide();
			$(".datosMovilizacion").hide();
        }
    });

    function fn_limpiarParametrosBusqueda() {
    	$("#codigo_busqueda").val('');
    	$("#nombre_busqueda").val('');
    	$("#sitio_destino").html(combo);
    	$("#area_destino").html(combo);
    	fn_limpiarDatosDestino();
    }

    $("#codigo_busqueda").change(function () {
    	$("#nombre_busqueda").val('');
    	$(".datosDestinoUbicacionSitio").hide();
    	$(".datosDestinoUbicacionPredio").hide();
    	$(".datosDestinoDetalle").hide();
    	$(".datosMovilizacion").hide();
    	$("#sitio_destino").html(combo);
    	$("#area_destino").html(combo);
		fn_limpiarDatosDestino();
		
		if ($("#codigo_busqueda").val() != '' ) {
			if($("#tipo_destino option:selected").val()=='Predio'){	
				$(".datosDestinoUbicacionSitio").hide();
				fn_buscarPredioDestinoXCodigoNombre();	
			}else if($("#tipo_destino option:selected").val() == 'CentroConcentracion'){
				$(".datosDestinoUbicacionPredio").hide();
				fn_buscarSitioDestinoXCodigoNombre();
			}			
        }else{
			alert('Debe ingresar una provincia, código o nombre válidos');
			fn_limpiarDatosDestino();
			$(".datosDestinoDetalle").hide();
			$(".datosMovilizacion").hide();
        }
    });

    $("#nombre_busqueda").change(function () {
    	$("#codigo_busqueda").val('');
    	$(".datosDestinoUbicacionSitio").hide();
    	$(".datosDestinoUbicacionPredio").hide();
    	$(".datosDestinoDetalle").hide();
    	$(".datosMovilizacion").hide();
    	$("#sitio_destino").html(combo);
    	$("#area_destino").html(combo);
		fn_limpiarDatosDestino();
		
		if ($("#nombre_busqueda").val() != '' ) {	
			if($("#tipo_destino option:selected").val()=='Predio'){	
				fn_buscarPredioDestinoXCodigoNombre();	
			}else if($("#tipo_destino option:selected").val() == 'CentroConcentracion'){
				fn_buscarSitioDestinoXCodigoNombre();
			}				
        }else{
			alert('Debe ingresar una provincia, código o nombre válidos');
			fn_limpiarDatosDestino();
			$(".datosDestinoDetalle").hide();
			$(".datosMovilizacion").hide();
        }
    });

  	//Función para mostrar los datos del sitio de destino del módulo de programas de control oficial ------------------------------------aqui modificar para crear un nuevo combo para seleccionar un predio de una lista 
    function fn_buscarPredioDestinoXCodigoNombre() {
    	var pasaporte = $("#pasaporte_equino").val();
    	var tipoDestino = $("#tipo_destino option:selected").val();
    	var idProvincia = $("#provincia_busqueda option:selected").val();
    	var provincia = $("#provincia_busqueda option:selected").text();
    	var ubicacionActual = $("#id_ubicacion_actual").val();
    	var codigoSitio = $("#codigo_busqueda").val();
    	var nombreSitio = $("#nombre_busqueda").val();
    	var idSitio = $("#nombre_busqueda").val();
        
        if (pasaporte != "" && tipoDestino != "" && idProvincia != "" && (codigoSitio != "" || nombreSitio != "")){
        	$.post("<?php echo URL ?>PasaporteEquino/Movilizaciones/buscarSitiosDestinoPredio",
               {
        		pasaporte : pasaporte,
        		tipoDestino : tipoDestino,
        		idProvincia : idProvincia,
                provincia : provincia,
                ubicacionActual : ubicacionActual,
                codigoSitio : codigoSitio,
                nombreSitio : nombreSitio
               }, function (data) {
            	   $(".datosDestinoUbicacionPredio").show();
            	   $("#predio_destino").html(data);
            });
        }else{
            if(!$.trim($("#pasaporte_equino").val())){
    			$("#pasaporte_equino").addClass("alertaCombo");
    		}

        	fn_limpiarDatosEquino();

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#predio_destino").change(function () {
    	$(".datosDestinoDetalle").hide();
    	$(".datosMovilizacion").hide();
		fn_limpiarDatosDestino();
		
		if ($("#predio_destino option:selected").val() != '' ) {	
			if($("#tipo_destino option:selected").val() == 'Predio'){
				fn_buscarPredioDestino();
			}				
        }else{
			alert('Debe ingresar un predio válido');
			fn_limpiarDatosDestino();
			$(".datosDestinoDetalle").hide();
			$(".datosMovilizacion").hide();
        }
    });

    //Función para mostrar los datos del sitio de destino del módulo de catastro de predio de équidos
    function fn_buscarPredioDestino() {
    	var pasaporte = $("#pasaporte_equino").val();
    	var tipoDestino = $("#tipo_destino option:selected").val();
    	var idProvincia = $("#provincia_busqueda option:selected").val();
    	var provincia = $("#provincia_busqueda option:selected").text();
    	var ubicacionActual = $("#id_ubicacion_actual").val();
    	var codigoSitio = $("#codigo_busqueda").val();
    	var nombreSitio = $("#nombre_busqueda").val();
    	var idPredioDestino = $("#predio_destino option:selected").val();
        
        if (pasaporte != "" && tipoDestino != "" && idProvincia != "" && (codigoSitio != "" || nombreSitio != "") && idPredioDestino != ""){
        	$.post("<?php echo URL ?>PasaporteEquino/Movilizaciones/buscarSitiosDestinoPredioEquidos",
               {
        		idProvincia : idProvincia,
        		ubicacionActual : ubicacionActual,
        		idPredioDestino : idPredioDestino
               }, function (data) {
                   if(data.resultado == 'Fallo'){
                	   fn_limpiarParametrosBusqueda();
                	   fn_limpiarDatosDestino();
                	   mostrarMensaje(data.mensaje,"FALLO");
               		   $(".datosDestinoDetalle").hide();
               		   $(".datosMovilizacion").hide();
                   }else{
                	   fn_cargarDatosDestino(data);
                	   mostrarMensaje(data.mensaje,"EXITO");
                	   $(".datosDestinoDetalle").show();
                	   $(".datosMovilizacion").show();
                   }
            }, 'json');
        }else{
            if(!$.trim($("#pasaporte_equino").val())){
    			$("#pasaporte_equino").addClass("alertaCombo");
    		}

        	fn_limpiarDatosEquino();

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }


    //----------------------

  	//Función para mostrar los datos del sitio de destino del módulo de registro de operador
    function fn_buscarSitioDestinoXCodigoNombre() {
    	var pasaporte = $("#pasaporte_equino").val();
    	var tipoDestino = $("#tipo_destino option:selected").val();
    	var idProvincia = $("#provincia_busqueda option:selected").val();
    	var provincia = $("#provincia_busqueda option:selected").text();
    	var ubicacionActual = $("#id_ubicacion_actual").val();
    	var codigoSitio = $("#codigo_busqueda").val();
    	var nombreSitio = $("#nombre_busqueda").val();
    	var idSitio = $("#id_sitio_origen").val();
        
        if (pasaporte != "" && tipoDestino != "" && idProvincia != "" && (codigoSitio != "" || nombreSitio != "")){//////////////////////////////////////
        	$.post("<?php echo URL ?>PasaporteEquino/Movilizaciones/comboSitiosDestinoRegistroOperador",
               {
        		pasaporte : pasaporte,
        		tipoDestino : tipoDestino,
        		idProvincia : idProvincia,
                provincia : provincia,
                ubicacionActual : ubicacionActual,
                codigoSitio : codigoSitio,
                nombreSitio : nombreSitio,
                idSitio : idSitio
               }, function (data) {
            	   $(".datosDestinoUbicacionSitio").show();
            	   $("#sitio_destino").html(data);
            });
        }else{
            if(!$.trim($("#pasaporte_equino").val())){
    			$("#pasaporte_equino").addClass("alertaCombo");
    		}

            if(!$.trim($("#tipo_destino option:selected").val())){
    			$("#tipo_destino").addClass("alertaCombo");
    		}

            if(!$.trim($("#provincia_busqueda option:selected").val())){
    			$("#provincia_busqueda").addClass("alertaCombo");
    		}

            if(!$.trim($("#id_ubicacion_actual").val())){
    			$("#id_ubicacion_actual").addClass("alertaCombo");
    		}

            if(!$.trim($("#codigo_busqueda").val())){
    			$("#codigo_busqueda").addClass("alertaCombo");
    		}

            if(!$.trim($("#nombre_busqueda").val())){
    			$("#nombre_busqueda").addClass("alertaCombo");
    		}

            $(".datosDestinoUbicacionSitio").hide();

        	fn_limpiarDatosEquino();

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#sitio_destino").change(function () {
    	$("#area_destino").val(combo);
    	$(".datosDestinoDetalle").hide();
    	$(".datosMovilizacion").hide();
		fn_limpiarDatosDestino();
		
		if ($("#sitio_destino option:selected").val() != '' ) {	
			if($("#tipo_destino option:selected").val() == 'CentroConcentracion'){
				fn_buscarAreaDestinoXSitio();
			}				
        }else{
			alert('Debe ingresar un sitio válido');
			$("#area_destino").val(combo);
			fn_limpiarDatosDestino();
			$(".datosDestinoDetalle").hide();
			$(".datosMovilizacion").hide();
        }
    });

  	//Función para mostrar los datos del área por sitio de destino seleccionado del módulo de registro de operador
    function fn_buscarAreaDestinoXSitio() {
    	var pasaporte = $("#pasaporte_equino").val();
    	var tipoDestino = $("#tipo_destino option:selected").val();
    	var idProvincia = $("#provincia_busqueda option:selected").val();
    	var provincia = $("#provincia_busqueda option:selected").text();
    	var ubicacionActual = $("#id_ubicacion_actual").val();
    	var codigoSitio = $("#codigo_busqueda").val();
    	var nombreSitio = $("#nombre_busqueda").val();
    	var idSitio = $("#id_sitio_origen").val();
    	var idSitioDestino = $("#sitio_destino option:selected").val();
        
        if (pasaporte != "" && tipoDestino != "" && idProvincia != "" && (codigoSitio != "" || nombreSitio != "") && idSitioDestino != ""){//////////////////////////////////////
        	$.post("<?php echo URL ?>PasaporteEquino/Movilizaciones/comboAreasDestinoRegistroOperador",
               {
        		pasaporte : pasaporte,
        		tipoDestino : tipoDestino,
        		idProvincia : idProvincia,
                provincia : provincia,
                ubicacionActual : ubicacionActual,
                codigoSitio : codigoSitio,
                nombreSitio : nombreSitio,
                idSitio : idSitio,
                idSitioDestino : idSitioDestino
               }, function (data) {
            	   $("#area_destino").html(data);
                   /*if(data.resultado == 'Fallo'){
                	   fn_limpiarParametrosBusqueda();
                	   fn_limpiarDatosDestino();
                	   mostrarMensaje(data.mensaje,"FALLO");
               		   $(".datosDestinoDetalle").hide();
                   }else{
                	   fn_cargarDatosDestino(data);
                	   mostrarMensaje(data.mensaje,"EXITO");
                	   $(".datosDestinoDetalle").show();
                   }*/
            });
        }else{
            if(!$.trim($("#pasaporte_equino").val())){
    			$("#pasaporte_equino").addClass("alertaCombo");
    		}

            if(!$.trim($("#tipo_destino option:selected").val())){
    			$("#tipo_destino").addClass("alertaCombo");
    		}

            if(!$.trim($("#provincia_busqueda option:selected").val())){
    			$("#provincia_busqueda").addClass("alertaCombo");
    		}

            if(!$.trim($("#id_ubicacion_actual").val())){
    			$("#id_ubicacion_actual").addClass("alertaCombo");
    		}

            if(!$.trim($("#codigo_busqueda").val())){
    			$("#codigo_busqueda").addClass("alertaCombo");
    		}

            if(!$.trim($("#nombre_busqueda").val())){
    			$("#nombre_busqueda").addClass("alertaCombo");
    		}

            if(!$.trim($("#sitio_destino").val())){
    			$("#sitio_destino").addClass("alertaCombo");
    		}

        	fn_limpiarDatosEquino();

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#area_destino").change(function () {
    	$(".datosDestinoDetalle").hide();
    	$(".datosMovilizacion").hide();
		fn_limpiarDatosDestino();
		
		if ($("#area_destino option:selected").val() != '' ) {	
			if($("#tipo_destino option:selected").val() == 'CentroConcentracion'){
				fn_buscarSitioDestino();
			}				
        }else{
			alert('Debe ingresar un sitio válido');
			fn_limpiarDatosDestino();
			$(".datosDestinoDetalle").hide();
			$(".datosMovilizacion").hide();
        }
    });

    //Función para mostrar los datos del sitio de destino del módulo de registro de operador
    function fn_buscarSitioDestino() {
    	var pasaporte = $("#pasaporte_equino").val();
    	var tipoDestino = $("#tipo_destino option:selected").val();
    	var idProvincia = $("#provincia_busqueda option:selected").val();
    	var provincia = $("#provincia_busqueda option:selected").text();
    	var ubicacionActual = $("#id_ubicacion_actual").val();
    	var codigoSitio = $("#codigo_busqueda").val();
    	var nombreSitio = $("#nombre_busqueda").val();
    	var idSitio = $("#nombre_busqueda").val();
    	var idSitioDestino = $("#sitio_destino option:selected").val();
    	var idAreaDestino = $("#area_destino option:selected").val();
        
        if (pasaporte != "" && tipoDestino != "" && idProvincia != "" && (codigoSitio != "" || nombreSitio != "") && idSitioDestino != "" && idAreaDestino != ""){
        	$.post("<?php echo URL ?>PasaporteEquino/Movilizaciones/buscarSitiosDestinoRegistroOperadorPredioEquidos",
               {
        		idProvincia : idProvincia,
        		ubicacionActual : ubicacionActual,
        		idSitioDestino : idSitioDestino,
        		idAreaDestino : idAreaDestino
               }, function (data) {
                   if(data.resultado == 'Fallo'){
                	   fn_limpiarParametrosBusqueda();
                	   fn_limpiarDatosDestino();
                	   mostrarMensaje(data.mensaje,"FALLO");
               		   $(".datosDestinoDetalle").hide();
               		   $(".datosMovilizacion").hide();
                   }else{
                	   fn_cargarDatosDestino(data);
                	   mostrarMensaje(data.mensaje,"EXITO");
                	   $(".datosDestinoDetalle").show();
                	   $(".datosMovilizacion").show();
                   }
            }, 'json');
        }else{
            if(!$.trim($("#pasaporte_equino").val())){
    			$("#pasaporte_equino").addClass("alertaCombo");
    		}

        	fn_limpiarDatosEquino();

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }
    function fn_cargarDatosDestino(data) {
    	$("#id_ubicacion_destino").val(data.idCatastroPredioEquidos);
    	$("#codigo_ubicacion_destino").val(data.numSolicitud);
    	$("#nombre_ubicacion_destino").val(data.nombrePredio);
    	$("#identificador_propietario_destino").val(data.cedulaPropietario);
    	$("#nombre_propietario_destino").val(data.nombrePropietario);    	
    	$("#id_provincia_destino").val(data.idProvincia);
    	$("#provincia_destino").val(data.provincia);
    	$("#id_canton_destino").val(data.idCanton);
    	$("#canton_destino").val(data.canton);
    	$("#id_parroquia_destino").val(data.idParroquia);
    	$("#parroquia_destino").val(data.parroquia);
    	$("#direccion_destino").val(data.direccionPredio);
    	$("#id_sitio_destino").val(data.idSitio);
    	$("#id_area_destino").val(data.idArea);
    } 

    function fn_limpiarDatosDestino() {
    	$("#id_ubicacion_destino").val('');
    	$("#codigo_ubicacion_destino").val('');
    	$("#nombre_ubicacion_destino").val('');
    	$("#identificador_propietario_destino").val('');
    	$("#nombre_propietario_destino").val('');
    	$("#id_provincia_destino").val('');
    	$("#provincia_destino").val('');
    	$("#id_canton_destino").val('');
    	$("#canton_destino").val('');
    	$("#id_parroquia_destino").val('');
    	$("#parroquia_destino").val('');
    	$("#direccion_destino").val('');
    	$("#id_sitio_destino").val('');
    	$("#id_area_destino").val('');
    }

    //MOVILIZACION
	$("#medio_transporte").change(function () {
		if ($("#medio_transporte option:selected").val() != 'Caminando' ) {
			fn_asignarCamposObligatorios();		
        }else{
        	fn_quitarCamposObligatorios();	
        }
    });

	function fn_asignarCamposObligatorios() {
		$("#nombre_propietario_transporte").attr('required', 'required');
		$("#placa_transporte").attr('required', 'required');
    	$("#identificador_conductor").attr('required', 'required');
    	$("#nombre_conductor").attr('required', 'required');

    	$("#nombre_propietario_transporte").removeAttr('disabled');
    	$("#placa_transporte").removeAttr('disabled');
    	$("#identificador_conductor").removeAttr('disabled');
    	$("#nombre_conductor").removeAttr('disabled');
    }

	function fn_quitarCamposObligatorios() {
		$("#nombre_propietario_transporte").removeAttr('required');
		$("#placa_transporte").removeAttr('required');
    	$("#identificador_conductor").removeAttr('required');
    	$("#nombre_conductor").removeAttr('required');

    	$("#nombre_propietario_transporte").attr('disabled', 'disabled');
    	$("#placa_transporte").attr('disabled', 'disabled');
    	$("#identificador_conductor").attr('disabled', 'disabled');
    	$("#nombre_conductor").attr('disabled', 'disabled');
    }

    /*$("#fecha_inicio_movilizacion").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    minDate: '0',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fecha_inicio_movilizacion').datepicker('getDate')); 
        	fecha.setDate(fecha.getDate()+1);	 
      		$('#fecha_fin_movilizacion').datepicker('setDate',fecha);
	    }
	 });*/

    /*$("#fecha_fin_movilizacion").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd'
	 });*/

	 /*$("#fecha_inicio_movilizacion").change(function (event) {
		 $.post("< ?php echo URL ?>PasaporteEquino/Movilizaciones/tiempo",
	               {
						fechaInicio : $("#fecha_inicio_movilizacion").val()
	               }, function (data) {
	                	   $("#fecha_fin_movilizacion").html(data);
	                   }
	            );
	 });*/
    
    $("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		
		//Sección Datos Generales
		if (!$.trim($("#identificador_solicitante").val())) {
        	error = true;
        	$("#identificador_solicitante").addClass("alertaCombo");
        }

        if(!$.trim($("#nombre_solicitante").val())){
        	error = true;
			$("#nombre_solicitante").addClass("alertaCombo");
		}
		
		//Sección Datos Origen
        if(!$.trim($("#id_equino").val())){
        	error = true;
			$("#pasaporte_equino").addClass("alertaCombo");
		}

        if(!$.trim($("#pasaporte_equino").val())){
        	error = true;
			$("#pasaporte_equino").addClass("alertaCombo");
		}

        if (!$.trim($("#id_asociacion").val())) {
        	error = true;
        	$("#nombre_asociacion").addClass("alertaCombo");
        }
				
        if(!$.trim($("#nombre_asociacion").val())){
        	error = true;
        	$("#nombre_asociacion").addClass("alertaCombo");
		}

        if (!$.trim($("#id_miembro").val())) {
        	error = true;
        	$("#identificador_miembro").addClass("alertaCombo");
        	$("#nombre_miembro").addClass("alertaCombo");
        }

        if (!$.trim($("#identificador_miembro").val())) {
        	error = true;
        	$("#identificador_miembro").addClass("alertaCombo");
        }
				
        if(!$.trim($("#nombre_miembro").val())){
        	error = true;
        	$("#nombre_miembro").addClass("alertaCombo");
		}

        if(!$.trim($("#id_ubicacion_actual").val())){
        	error = true;
        	$("#codigo_ubicacion_origen").addClass("alertaCombo");
        	$("#nombre_ubicacion_origen").addClass("alertaCombo");
		}

        if(!$.trim($("#codigo_ubicacion_origen").val())){
        	error = true;
			$("#codigo_ubicacion_origen").addClass("alertaCombo");
		}

        if(!$.trim($("#nombre_ubicacion_origen").val())){
        	error = true;
			$("#nombre_ubicacion_origen").addClass("alertaCombo");
		}

        if (!$.trim($("#id_provincia_origen").val())) {
        	error = true;
        	$("#provincia_origen").addClass("alertaCombo");
        }
				
        if(!$.trim($("#provincia_origen").val())){
        	error = true;
        	$("#provincia_origen").addClass("alertaCombo");
		}

        if (!$.trim($("#id_canton_origen").val())) {
        	error = true;
        	$("#canton_origen").addClass("alertaCombo");
        }
				
        if(!$.trim($("#canton_origen").val())){
        	error = true;
        	$("#canton_origen").addClass("alertaCombo");
		}

        if (!$.trim($("#id_parroquia_origen").val())) {
        	error = true;
        	$("#parroquia_origen").addClass("alertaCombo");
        }
				
        if(!$.trim($("#parroquia_origen").val())){
        	error = true;
        	$("#parroquia_origen").addClass("alertaCombo");
		}

        if(!$.trim($("#direccion_origen").val())){
        	error = true;
        	$("#direccion_origen").addClass("alertaCombo");
		}

        /*if (!$.trim($("#id_sitio_origen").val())) {
        	error = true;
        	$("#id_sitio_origen").addClass("alertaCombo");
        }

        if (!$.trim($("#id_area_origen").val())) {
        	error = true;
        	$("#id_area_origen").addClass("alertaCombo");
        }*/


      //Sección Datos Destino
        if(!$.trim($("#tipo_destino option:selected").val())){
        	error = true;
			$("#tipo_destino").addClass("alertaCombo");
		}

        if(!$.trim($("#id_ubicacion_destino").val())){
        	error = true;
			$("#codigo_ubicacion_destino").addClass("alertaCombo");
			$("#nombre_ubicacion_destino").addClass("alertaCombo");
		}

        if (!$.trim($("#codigo_ubicacion_destino").val())) {
        	error = true;
        	$("#codigo_ubicacion_destino").addClass("alertaCombo");
        }
				
        if(!$.trim($("#nombre_ubicacion_destino").val())){
        	error = true;
        	$("#nombre_ubicacion_destino").addClass("alertaCombo");
		}

        if (!$.trim($("#id_provincia_destino").val())) {
        	error = true;
        	$("#provincia_destino").addClass("alertaCombo");
        }

        if (!$.trim($("#provincia_destino").val())) {
        	error = true;
        	$("#provincia_destino").addClass("alertaCombo");
        }
				
        if(!$.trim($("#id_canton_destino").val())){
        	error = true;
        	$("#canton_destino").addClass("alertaCombo");
		}

        if(!$.trim($("#canton_destino").val())){
        	error = true;
			$("#canton_destino").addClass("alertaCombo");
		}

        if(!$.trim($("#id_parroquia_destino").val())){
        	error = true;
			$("#parroquia_destino").addClass("alertaCombo");
		}

        if(!$.trim($("#parroquia_destino").val())){
        	error = true;
			$("#parroquia_destino").addClass("alertaCombo");
		}

        if (!$.trim($("#direccion_destino").val())) {
        	error = true;
        	$("#direccion_destino").addClass("alertaCombo");
        }
				
        /*if(!$.trim($("#id_sitio_destino").val())){
        	error = true;
        	$("#id_sitio_destino").addClass("alertaCombo");
		}

        if (!$.trim($("#id_area_destino").val())) {
        	error = true;
        	$("#id_area_destino").addClass("alertaCombo");
        }*/

		//Sección Datos Movilización		
        if(!$.trim($("#medio_transporte option:selected").val())){
        	error = true;
        	$("#medio_transporte").addClass("alertaCombo");
		}

		if($("#medio_transporte option:selected").val() != 'Caminando'){

            if (!$.trim($("#placa_transporte").val())) {
            	error = true;
            	$("#placa_transporte").addClass("alertaCombo");
            }
    				
            if(!$.trim($("#nombre_propietario_transporte").val())){
            	error = true;
            	$("#nombre_propietario_transporte").addClass("alertaCombo");
    		}
    
            if(!$.trim($("#identificador_conductor").val())){
            	error = true;
            	$("#identificador_conductor").addClass("alertaCombo");
    		}
    
            if (!$.trim($("#nombre_conductor").val())) {
            	error = true;
            	$("#nombre_conductor").addClass("alertaCombo");
            }
		}

        if (!$.trim($("#fecha_inicio_movilizacion").val())) {
        	error = true;
        	$("#fecha_inicio_movilizacion").addClass("alertaCombo");
        }

        /*if (!$.trim($("#hora_inicio_movilizacion").val())) {
        	error = true;
        	$("#hora_inicio_movilizacion").addClass("alertaCombo");
        }

        if (!$.trim($("#observacion_transporte").val())) {
        	error = true;
        	$("#observacion_transporte").addClass("alertaCombo");
        }*/

        if (!error) {
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();

			setTimeout(function(){ 
				var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);			
		       	if (respuesta.estado == 'exito'){
		       		$("#id").val(respuesta.contenido);
		       		$("#formulario").attr('data-opcion', 'Movilizaciones/mostrarReporte');
					abrir($("#formulario"),event,false);
		        }else{
		        	mostrarMensaje(data.mensaje,"FALLO");
		        }
			}, 1000);
			
			
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>