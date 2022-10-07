<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<div class="abrirFormulario">
	<input type="hidden" id="id" name="id" />
	
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
	
	<fieldset id="tablaFiscalizaciones">
		<legend>Detalle de Fiscalizaciones</legend>
    	
    	<table id="tablaFiscalizacionesDetalle" style="width: 100%;">
            <thead>
                <tr id='cabecera'>
                    <th>Nº</th>
                    <th>Fecha</th>
                    <th>Fiscalizador</th>
                    <th>Resultado</th>
                    <th>Acción correctiva</th>
                    <th>Motivo</th>
                    <th>Observación</th>
                </tr>
            </thead>
			<tbody id="detalle">
				
			</tbody>
		</table>
	</fieldset>
</div >


<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>PasaporteEquino' data-opcion='fiscalizaciones/guardarFiscalizacion' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	<input type="hidden" id="id_movilizacion" name="id_movilizacion" value="<?php echo $this->modeloMovilizaciones->getIdMovilizacion(); ?>" />
	<input type="hidden" id="identificador_fiscalizador" name="identificador_fiscalizador" value="<?php echo $_SESSION['usuario']; ?>" />
	<input type="hidden" id="nombre_fiscalizador" name="nombre_fiscalizador" value="<?php echo ($this->tipoUsuario=='CentroConcentracion'?$this->razonSocialCC:$_SESSION['datosUsuario']); ?>" />
	<input type="hidden" id="tipo_fiscalizador" name="tipo_fiscalizador" value="<?php echo $this->tipoUsuario; ?>" />
	<input type="hidden" id="provincia_fiscalizador" name="provincia_fiscalizador" value="<?php echo ($this->tipoUsuario=='CentroConcentracion'?$this->provinciaCC:$_SESSION['nombreProvincia']); ?>" />
	<input type="hidden" id="id_equino" name="id_equino" value="<?php echo $this->modeloMovilizaciones->getIdEquino(); ?>" />
	
	<fieldset>
		<legend>Fiscalizaciones</legend>				

		<div data-linea="1">
			<label for="fecha_fiscalizacion">Fecha de fiscalización: </label>
			<input type="text" id="fecha_fiscalizacion" name="fecha_fiscalizacion" readonly="readonly" required maxlength="8" />
		</div>				

		<div data-linea="2">
			<label for="lugar_fiscalizacion">Lugar de fiscalización: </label>
			<select id="lugar_fiscalizacion" name="lugar_fiscalizacion" required>
				<option value>Seleccione....</option>
				<?php echo $this->comboLugarFiscalizacion();?>
			</select>
		</div>				

		<div data-linea="3">
			<label for="resultado_fiscalizacion">Resultado: </label>
			<select id="resultado_fiscalizacion" name="resultado_fiscalizacion" required>
				<option value>Seleccione....</option>
				<?php echo $this->comboResultadoFiscalizacion();?>
			</select>
		</div>				

		<div data-linea="4" class="detalleAccionCorrectiva">
			<label for="accion_correctiva">Acción correctiva: </label>
			<select id="accion_correctiva" name="accion_correctiva" required>
				<option value>Seleccione....</option>
			</select>
		</div>				

		<div data-linea="5" class="detalleMotivo">
			<label for="motivo">Motivo: </label>
			<select id="motivo" name="motivo">
				<option value>Seleccione....</option>
				<?php echo $this->comboMotivoFiscalizacion();?>
			</select>
		</div>				

		<div data-linea="6" class="detalleObservacion">
			<label for="observacion_fiscalizacion">Observación: </label>
			<input type="text" id="observacion_fiscalizacion" name="observacion_fiscalizacion"  maxlength="1024" />
		</div>
		
		<div data-linea="7" class="detalleEstado">
			<label for="estado_fiscalizacion">Estado: </label>
			<select id="estado_fiscalizacion" name="estado_fiscalizacion" required>
				<option value>Seleccione....</option>
				<?php echo $this->comboEstadoFiscalizacion();?>
			</select>
		</div>	

		<div data-linea="8">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset >
</form >

<script type ="text/javascript">
var formulario = <?php echo json_encode($this->formulario); ?>;
var tipoUsuario = <?php echo json_encode($this->tipoUsuario); ?>;
var estadoMovilizacion = <?php echo json_encode($this->modeloMovilizaciones->getEstadoMovilizacion()); ?>;
var estadoFiscalizacion = <?php echo json_encode($this->modeloMovilizaciones->getEstadoFiscalizacion()); ?>;
var combo = "<option>Seleccione....</option>";

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();

		fn_mostrarDetalleFiscalizacion();

		$(".detalleAccionCorrectiva").hide();
		$(".detalleMotivo").hide();

		if(tipoUsuario == 'CentroConcentracion'){
			cargarValorDefecto("lugar_fiscalizacion","Centro de concentración animal");
		}

		if(estadoMovilizacion == 'Finalizado'){
			$("#formulario").hide();
		}
	 });

	//Para cargar el detalle de fiscalizaciones registradas
    function fn_mostrarDetalleFiscalizacion() {
        var idMovilizacion = $("#id_movilizacion").val();
        
    	$.post("<?php echo URL ?>PasaporteEquino/Fiscalizaciones/construirDetalleFiscalizacion/" + idMovilizacion, function (data) {
            $("#tablaFiscalizacionesDetalle").html(data);
        });
    }

	$("#lugar_fiscalizacion").change(function () {
    	
		if(tipoUsuario == 'CentroConcentracion'){
			cargarValorDefecto("lugar_fiscalizacion","Centro de concentración animal");
		}
    });

	$("#fecha_fiscalizacion").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
	    minDate: '0'
	 });

	$("#resultado_fiscalizacion").change(function () {
    	
		if ($("#resultado_fiscalizacion").val() != '' ) {	
			fn_comboAccionCorrectivaFiscalizacion();
        }else{
			alert('Debe ingresar un resultado de fiscalización válido');
        }
    });

  	//Función para mostrar las acciones correctivas por resultado de fiscalización
    function fn_comboAccionCorrectivaFiscalizacion() {
    	var resultadoFiscalizacion = $("#resultado_fiscalizacion option:selected").val();
        
        if (resultadoFiscalizacion != ""){
        	$.post("<?php echo URL ?>PasaporteEquino/Fiscalizaciones/comboAccionCorrectivaFiscalizacion/" + resultadoFiscalizacion, function (data) {
                $("#accion_correctiva").html(data);
            });
        }else{
        	$("#accion_correctiva").html(combo);
        	
            if(!$.trim($("#resultado_fiscalizacion").val())){
    			$("#resultado_fiscalizacion").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
    	}     
    }

    $("#resultado_fiscalizacion").change(function () {
    	$(".detalleAccionCorrectiva").hide();
		$(".detalleMotivo").hide();	
		$("#estado_fiscalizacion").val('');
		
		if ($("#resultado_fiscalizacion option:selected").val() != '' ) {
			$(".detalleAccionCorrectiva").show();	
        }else{
        	$(".detalleAccionCorrectiva").hide();	
        }
    });

    $("#accion_correctiva").change(function () {
    	$(".detalleMotivo").hide();
    	$("#estado_fiscalizacion").val('');
		
		if ($("#accion_correctiva option:selected").val() == 'Anular registro de movilización' ) {
			$(".detalleMotivo").show();	
			$("#motivo").attr("required","required");
        }else{
        	$(".detalleMotivo").hide();	
        	$("#motivo").removeAttr("required");
        }

		if ($("#resultado_fiscalizacion option:selected").val() == 'Negativo' &&  $("#accion_correctiva option:selected").val() == 'Inactivar registro de movilización') {
			cargarValorDefecto("estado_fiscalizacion","Finalizado");
        }
    });

    $("#estado_fiscalizacion").change(function () {
    	
		//if ($("#estado_fiscalizacion option:selected").val() != '' ) {
			if ($("#resultado_fiscalizacion option:selected").val() == 'Negativo' &&  $("#accion_correctiva option:selected").val() == 'Inactivar registro de movilización') {
				cargarValorDefecto("estado_fiscalizacion","Finalizado");
	        }
        //}
    });

    $("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		
		//Sección Datos Fiscalizacion
		if (!$.trim($("#fecha_fiscalizacion").val())) {
        	error = true;
        	$("#fecha_fiscalizacion").addClass("alertaCombo");
        }

        if(!$.trim($("#lugar_fiscalizacion option:selected").val())){
        	error = true;
			$("#lugar_fiscalizacion").addClass("alertaCombo");
		}		
        		
        if(!$.trim($("#resultado_fiscalizacion option:selected").val())){
        	error = true;
			$("#resultado_fiscalizacion").addClass("alertaCombo");
		}

        if(!$.trim($("#accion_correctiva option:selected").val())){
        	error = true;
			$("#accion_correctiva").addClass("alertaCombo");
		}
        
		if($("#resultado_fiscalizacion option:selected").val() == 'Negativo' && $("#accion_correctiva option:selected").val() == 'Anular registro de movilización'){

			if(!$.trim($("#motivo option:selected").val())){
	        	error = true;
				$("#motivo").addClass("alertaCombo");
			}
		}

		if(!$.trim($("#estado_fiscalizacion option:selected").val())){
        	error = true;
			$("#estado_fiscalizacion").addClass("alertaCombo");
		}
		
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);

	       	if (respuesta.estado == 'exito'){
		       	fn_filtrar();
		       	limpiarFiscalizacion();
		       	fn_mostrarDetalleFiscalizacion();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

    function limpiarFiscalizacion(){
		$("#fecha_fiscalizacion").val("");
    	$("#resultado_fiscalizacion").val("");
    	$("#accion_correctiva").val("");
    	$("#motivo").val("");
    	$("#observacion_fiscalizacion").val("");
	}
</script>