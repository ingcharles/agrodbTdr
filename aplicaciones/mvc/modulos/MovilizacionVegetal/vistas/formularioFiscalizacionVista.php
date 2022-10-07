<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<!-- Despliegue de datos -->
<div id="datosMovilizacion">
		
	<fieldset>
		<legend>Datos Generales</legend>
		
		<div data-linea="1">
			<label for="tipo_solicitud">Tipo de Solicitud:</label> 
    			<?php echo $this->modeloMovilizacion->getTipoSolicitud(); ?>	
		</div>

		<div data-linea="2">
			<label for="id_provincia_emision">Provincia Emisión:</label>
     			<?php echo $this->modeloMovilizacion->getProvinciaEmision(); ?>	
		</div>

		<div data-linea="2">
			<label for="id_canton_emision">Cantón Emisión:</label>
    			<?php echo $this->modeloMovilizacion->getCantonEmision(); ?>
		</div>

		<div data-linea="3">
			<label for="id_oficina_emision">Oficina Emisión:</label> 
    			<?php echo $this->modeloMovilizacion->getOficinaEmision(); ?>
		</div>		
		
		<div data-linea="4">
			<label>Nº Permiso:</label>
			<?php echo $this->modeloMovilizacion->getNumeroPermiso(); ?>
		</div>
		
		<div data-linea="5">
			<label>Fecha Emisión:</label>
			<?php echo date('Y-m-d H:i',strtotime($this->modeloMovilizacion->getFechaCreacion())); ?>
		</div>
		
		<div data-linea="6">
			<label for="fecha_inicio_movilizacion">Fecha Inicio de Vigencia: </label>
				<?php echo date('Y-m-d',strtotime($this->modeloMovilizacion->getFechaInicioMovilizacion())) . ' ' . date('H:i',strtotime($this->modeloMovilizacion->getHoraInicioMovilizacion())); ?>
		</div>

		<div data-linea="7">
			<label for="fecha_inicio_movilizacion">Fecha Fin de Vigencia: </label>
				<?php echo date('Y-m-d',strtotime($this->modeloMovilizacion->getFechaFinMovilizacion())) . ' ' . date('H:i',strtotime($this->modeloMovilizacion->getHoraFinMovilizacion())); ?>
		</div>
		
		<div data-linea="0">
			<label>Ver Certificado:</label>
			<?php echo ($this->modeloMovilizacion->getRutaCertificado()==''? '<span class="alerta">No ha generado ningún certificado</span>':'<a href="'.$this->modeloMovilizacion->getRutaCertificado().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Clic aquí para ver el Certificado</a>')?>
		</div>
	</fieldset>

	<fieldset>
		<legend>Datos Origen</legend>
		
		<div data-linea="8">
			<label for="identificador_operador_origen">Identificador Operador:</label> 
    			<?php echo $this->modeloMovilizacion->getIdentificadorOperadorOrigen(); ?>
		</div>

		<div data-linea="9">
			<label for="nombre_operador_origen">Nombre Operador:</label> 
				<?php echo $this->modeloMovilizacion->getNombreOperadorOrigen(); ?>
		</div>

		<div data-linea="10">
			<label id="lid_sitio_origen">Sitio: </label> 
				<?php echo $this->modeloMovilizacion->getSitioOrigen(); ?>
		</div>
		
		<div data-linea="10">
			<label id="lid_sitio_origen">Código de Sitio: </label> 
				<?php echo $this->modeloMovilizacion->getCodigoSitioOrigen(); ?>
		</div>
		
		<div data-linea="11">
			<label for="id_provincia_origen">Provincia:</label>
    			<?php echo $this->modeloMovilizacion->getProvinciaOrigen(); ?>
		</div>
		
		<div data-linea="11">
			<label for="canton_origen">Cantón:</label>
    			<?php echo $this->modeloMovilizacion->getCantonOrigen(); ?>
		</div>
		
		<div data-linea="12">
			<label for="parroquia_origen">Parroquia:</label>
    			<?php echo $this->modeloMovilizacion->getParroquiaOrigen(); ?>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Datos Destino</legend>
		
		<div data-linea="13">
			<label for="identificador_operador_destino">Identificador Operador:	</label> 
				<?php echo $this->modeloMovilizacion->getIdentificadorOperadorDestino(); ?>
		</div>

		<div data-linea="14">
			<label for="nombre_operador_destino">Nombre Operador: </label>
				<?php echo $this->modeloMovilizacion->getNombreOperadorDestino(); ?>
		</div>
		
		<div data-linea="15">
			<label id="lid_sitio_destino">Sitio: </label>
				<?php echo $this->modeloMovilizacion->getSitioDestino(); ?>
		</div>
		
		<div data-linea="15">
			<label id="lid_sitio_destino">Código de Sitio: </label>
				<?php echo $this->modeloMovilizacion->getCodigoSitioDestino(); ?>
		</div>
		
		<div data-linea="16">
			<label for="id_provincia_destino">Provincia: </label> 				
				<?php echo $this->modeloMovilizacion->getProvinciaDestino(); ?>
		</div>

		<div data-linea="16">
			<label for="canton_destino">Cantón: </label> 				
				<?php echo $this->modeloMovilizacion->getCantonDestino(); ?>
		</div>
		
		<div data-linea="17">
			<label for="parroquia_destino">Parroquia: </label> 				
				<?php echo $this->modeloMovilizacion->getParroquiaDestino(); ?>
		</div>

	</fieldset>

	<fieldset>
		<legend>Datos de Movilización</legend>

		<div data-linea="18">
			<label for="medio_transporte">Medio Transporte: </label> 
    			<?php echo $this->modeloMovilizacion->getMedioTransporte(); ?>
		</div>

		<div data-linea="18">
			<label for="placa_transporte">Placa Transporte: </label> 
				<?php echo $this->modeloMovilizacion->getPlacaTransporte(); ?>
		</div>

		<div data-linea="19">
			<label for="identificador_conductor">Identificador Conductor: </label>
				<?php echo $this->modeloMovilizacion->getIdentificadorConductor(); ?>
		</div>

		<div data-linea="19">
			<label for="nombre_conductor">Nombre Conductor: </label> 
				<?php echo $this->modeloMovilizacion->getNombreConductor(); ?>
		</div>		

		<div data-linea="20">
			<label for="observacion_transporte">Observación: </label> 
				<?php echo $this->modeloMovilizacion->getObservacionTransporte(); ?>
		</div>

	</fieldset>
	
	<fieldset id="tablaMovilizacion">
		<legend>Detalle de Productos a Movilizar</legend>
    	
    	<div data-linea="21">
    		<div id="tablaMovilizaciones" name="tablaMovilizaciones"> </div>
		</div>
		
	</fieldset>
	
	<fieldset>
		<legend>Resultado de Fiscalizaciones</legend>
    	
    	<div data-linea="22">
    		<div id="tablaFiscalizaciones" name="tablaFiscalizaciones"> </div>
		</div>
		
	</fieldset>

</div>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>MovilizacionVegetal' data-opcion='fiscalizacion/guardar' data-destino="detalleItem" method="post"  data-accionEnExito ="ACTUALIZAR">
	<input type="hidden" id="id_movilizacion" name="id_movilizacion" value="<?php echo $this->modeloMovilizacion->getIdMovilizacion(); ?>"/>
	<input type="hidden" id="estado_movilizacion" name="estado_movilizacion" value="<?php echo $this->modeloMovilizacion->getEstadoMovilizacion(); ?>"/>
	
	<fieldset>
		<legend>Nueva Fiscalización</legend>				

		<div data-linea="23">
			<label for="fecha_fiscalizacion">Fecha de Fiscalización: </label>
			<input type="text" id="fecha_fiscalizacion" name="fecha_fiscalizacion" value="<?php echo date('Y-m-d') ?>" required  />
		</div>				

		<div data-linea="24">
			<label for="resultado_fiscalizacion">Resultado: </label>
			<select id="resultado_fiscalizacion" name="resultado_fiscalizacion" required >
                <option value="">Seleccionar....</option>
                <?php
                    echo $this->comboResultadoFiscalizacion();
                ?>
        	</select>
		</div>				

		<div data-linea="25">
			<label for="accion_correctiva">Acción Correctiva: </label>
			<select id="accion_correctiva" name="accion_correctiva" required >
                
        	</select>
		</div>	
		
		<div data-linea="26" id='fCausa'>
			<label for="causa">Causa: </label>
			<select id="causa" name="causa"  >
				<option value="">Seleccionar....</option>
				<option value="Cambio de fecha">Cambio de fecha</option>
				<option value="Problemas de digitación">Problemas de digitación</option>
				<option value="Otros">Otros</option>
			</select>
			<input type="hidden" id="causa_anulacion" name="causa_anulacion" value="<?php echo $this->modeloFiscalizacion->getCausaAnulacion(); ?>"/>
		</div>

		<div data-linea="27">
			<label for="observacion_fiscalizacion">Observación: </label>
			<input type="text" id="observacion_fiscalizacion" name="observacion_fiscalizacion" value="<?php echo $this->modeloFiscalizacion->getObservacionFiscalizacion(); ?>"
					required maxlength="1024" />
		</div>
				
	</fieldset >
	
	<fieldset id="fMovilizacionDetalleEdicion">
		<legend>Detalle de Productos Movilizados</legend>
    	
    	<table id="tablaMovilizacionesDetalle" style="width: 100%;">
            <thead>
                <tr id='cabecera'>
                    <th>NºReg</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Subtipo Producto</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                </tr>
            </thead>
			<tbody id="detalle">
				
			</tbody>
		</table>
	</fieldset>
	
	<div data-linea="27">
		<button onclick='actualizar()'>Limpiar</button>
		<button type="submit" class="guardar">Guardar</button>
	</div>
</form >

<script type ="text/javascript">
var bandera = <?php echo json_encode($this->modeloMovilizacion->getEstadoMovilizacion()); ?>;

	$(document).ready(function() {
		$('#estado').html('');
		$("#formulario").hide();
		$("#fMovilizacionDetalleEdicion").hide();
		$("#fCausa").hide();

		if(bandera == 'Vigente'){
			$("#formulario").show();
		}else{
			$("#formulario").hide();
		}
		
		construirValidador();
		distribuirLineas();

		fn_mostrarDetalleMovilizacion();
		fn_mostrarDetalleMovilizacionEditable();
		fn_mostrarDetalleFiscalizacion();		
	 });
	
	$("#fecha_fiscalizacion").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd',
		maxDate: '0',
	    onSelect: function(dateText, inst) {
        	var fecha=new Date($('#fecha_fiscalizacion').datepicker('getDate')); 
	    }
	 });

	$("#resultado_fiscalizacion").change(function () {
    	$("#accion_correctiva").html("");
    	
        if ($("#resultado_fiscalizacion option:selected").val() !== "") {
        	fn_cargarAccionesCorrectivas();
        }else{
        	$("#accion_correctiva").val("");
        }
    });

	$("#accion_correctiva").change(function () {
    	if ($("#accion_correctiva option:selected").val() === "Modificar permiso") {
        	$("#fMovilizacionDetalleEdicion").show();
        	$("#tablaMovilizacion").hide();
        	$("#causa").removeAttr("required");
        }else if ($("#accion_correctiva option:selected").val() === "Anulado") {
			$("#fCausa").show();
			$("#causa").attr("required", "required");
			distribuirLineas();
		} else{
        	$("#fMovilizacionDetalleEdicion").hide();
        	$("#tablaMovilizacion").show();
        	$("#causa").removeAttr("required");
        }
	});
	
	$("#causa").change(function () {
    	if ($("#causa option:selected").val() != "") {
			$("#causa_anulacion").val($("#causa option:selected").val());
		} else{
        	$("#causa_anulacion").val('');
        }
    });
		
	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		
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

    function fn_validarCantidad(id) {
    	if(!$.trim($("#cantidadN"+id).val()) || !esCampoValido("#cantidadN"+id)){
    		$("#cantidadN"+id).val($("#cantidadO"+id).val());
    	}
		
       if(Number.parseInt($("#cantidadN"+id).val()) > Number.parseInt($("#cantidadO"+id).val())){
			alert('El valor no puede ser mayor al original');
			$("#cantidadN"+id).val($("#cantidadO"+id).val());
       }

       if(Number.parseInt($("#cantidadN"+id).val()) <= Number.parseInt('0')){
			alert('El valor no puede ser menor a 1');
			$("#cantidadN"+id).val($("#cantidadO"+id).val());
      }
    } 

  //Para cargar el detalle de movilizaciones registradas
    function fn_mostrarDetalleMovilizacion() {
        var idMovilizacion = $("#id_movilizacion").val();
        
    	$.post("<?php echo URL ?>MovilizacionVegetal/DetalleMovilizacion/construirDetalleMovilizacion/" + idMovilizacion, function (data) {
            $("#tablaMovilizaciones").html(data);
        });
    } 

  //Para cargar el detalle de movilizaciones registradas
    function fn_mostrarDetalleMovilizacionEditable() {
        var idMovilizacion = $("#id_movilizacion").val();
        
    	$.post("<?php echo URL ?>MovilizacionVegetal/DetalleMovilizacion/construirDetalleMovilizacionEditable/" + idMovilizacion, function (data) {
            $("#detalle").html(data);
        });
    } 

    //Para cargar el detalle de fiscalizaciones registradas
    function fn_mostrarDetalleFiscalizacion() {
        var idMovilizacion = $("#id_movilizacion").val();
        
    	$.post("<?php echo URL ?>MovilizacionVegetal/Fiscalizacion/construirDetalleFiscalizacion/" + idMovilizacion, function (data) {
            $("#tablaFiscalizaciones").html(data);
        });
    }

  	//Lista de Acciones Correctivas por resultado de fiscalización
    function fn_cargarAccionesCorrectivas() {
        var resultado = $("#resultado_fiscalizacion option:selected").val();
        
        if (resultado !== "") {
        	$.post("<?php echo URL ?>MovilizacionVegetal/Fiscalizacion/comboAccionCorrectivaFiscalizacion/" + resultado, function (data) {
                $("#accion_correctiva").removeAttr("disabled");
                $("#accion_correctiva").html(data);               
            });
        }
    }

    function limpiarFiscalizacion(){
		$("#fecha_fiscalizacion").val("");
    	$("#resultado_fiscalizacion").val("");
    	$("#accion_correctiva").val("");
    	$("#observacion_fiscalizacion").val("");
    	$("#producto").text("");
    	$("#cantidad").val("");
    	$("#unidad").text("");
    	$("#agregar").attr('disabled','disabled');
	}

	function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}

	function esCampoValido(elemento){
    	var patron = new RegExp($(elemento).attr("data-er"),"g");
    	return patron.test($(elemento).val());
    }
</script>