<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

	<fieldset>
		<legend>Datos del Trámite</legend>
		
		<div data-linea="1">
			<label for="numero_tramite">Registro del Trámite: </label> <?php echo $this->modeloTramites->getNumeroTramite(); ?>
		</div>

		<div data-linea="1">
			<label for="id_ventanilla">Ventanilla: </label> <?php echo $this->modeloTramites->getNombreVentanilla(); ?>
		</div>

		<div data-linea="2">
			<label for="identificador">Responsable: </label><?php echo $this->modeloTramites->getNombreEmpleado(); ?> 
		</div>

		<hr />

		<div data-linea="3">
			<label for="remitente">Remitente: </label> <?php echo $this->modeloTramites->getRemitente(); ?>
		</div>

		<div data-linea="4">
			<label for="oficio_memo">Oficio/Memo: </label> <?php echo $this->modeloTramites->getOficioMemo(); ?>
		</div>

		<div data-linea="4">
			<label for="factura">Factura: </label> <?php echo $this->modeloTramites->getFactura(); ?>
		</div>

		<div data-linea="5">
			<label for="guia_quipux">Guía - Correo: </label> <?php echo $this->modeloTramites->getGuiaQuipux(); ?>
		</div>

		<div data-linea="6">
			<label for="asunto">Asunto: </label> <?php echo $this->modeloTramites->getAsunto(); ?>
		</div>

		<div data-linea="7">
			<label for="anexos">Anexos: </label> <?php echo $this->modeloTramites->getAnexos(); ?>
		</div>

		<hr />

		<div data-linea="8">
			<label for="destinatario">Destinatario: </label> <?php echo $this->modeloTramites->getDestinatario(); ?>
		</div>

		<div data-linea="9">
			<label for="id_unidad_destino">Unidad de Destino: </label> 
			<select id="id_unidad_destino1" name="id_unidad_destino1" disabled>
				<?php
                    echo $this->comboAreasCategoriaNacional($this->modeloTramites->getIdUnidadDestino());
                ?>
            </select>
		</div>

		<div data-linea="10">
			<label for="derivado">Trámite derivado: </label> <?php echo $this->modeloTramites->getDerivado(); ?>
		</div>

		<div data-linea="11">
			<label for="quipux_agr">Quipux Agrocalidad: </label> <?php echo $this->modeloTramites->getQuipuxAgr(); ?>
		</div>

	</fieldset>

<form id='formularioSeguimiento' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='seguimientos/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $this->modeloTramites->getIdTramite(); ?>"/>
	<input type="hidden" id="id_ventanilla" name="id_ventanilla" value="<?php echo $datosUsuario['idVentanilla']; ?>" />
	<input type="hidden" id="estado_tramite" name="estado_tramite" value="<?php echo $this->modeloTramites->getEstadoTramite(); ?>" />
	
	<fieldset>
		<legend>Seguimiento del Trámite</legend>
		<div data-linea="11">
			<label for="fecha">Fecha: </label>
			<input type="date" id="fecha" name="fecha" value="<?php echo $this->modeloSeguimientos->getFecha(); ?>"
			placeholder="Fecha en que se realiza el seguimiento" required maxlength="8" max="<?php echo date('Y-m-d'); ?>" />
		</div>				

		<div data-linea="11">
			<label for="persona_recibe">Recibido/Entregado por: </label>
			<input type="text" id="persona_recibe" name="persona_recibe" value="<?php echo $this->modeloSeguimientos->getPersonaRecibe(); ?>"
			placeholder="Quién recibe el trámite" required maxlength="512" />
		</div>				

		<div data-linea="13">
			<label for="id_unidad_destino">Dirección de Destino: </label>
			<select id="id_unidad_destino" name="id_unidad_destino" required>
				<option value="">Seleccionar....</option>
                <?php
                    echo $this->comboAreasCategoriaNacional($this->modeloSeguimientos->getIdUnidadDestino());
                ?>
            </select>
            
            <input type="hidden" id="unidad_destino_actual" name="unidad_destino_actual" />
		</div>				

		<div data-linea="14">
			<label for="observaciones_seguimiento">Observaciones: </label>
			<input type="text" id="observaciones_seguimiento" name="observaciones_seguimiento" value="<?php echo $this->modeloSeguimientos->getObservacionesSeguimiento(); ?>"
			placeholder="Observaciones del seguimiento realizado" required maxlength="1024" />
		</div>				

		<div data-linea="15">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset>
</form >


<form id="filtrar" action="aplicaciones/mvc/SeguimientoDocumental/Seguimientos/exportarSeguimientoExcel" target="_blank" method="post">
	<fieldset id="seccionSeguimientos" name="seccionSeguimientos">
		<legend>Seguimientos registrados</legend>
		
		<input type="hidden" name="id_tramite_seguimiento" value="<?php echo $this->modeloTramites->getIdTramite(); ?>"/>
		
		<div data-linea="1">
				<button type="submit" class="guardar">Exportar a Excel</button>
			</div>
		
		<div id="tablaSeguimientos" name="tablaSeguimientos"> </div>
		
		<div data-linea="2">
			<label for="cierre">¿Cerrar Trámite? </label> 
			<select id="cierre" name="cierre">
	            <?php
	                echo $this->comboSiNo('No');
	            ?>
	        </select>
		</div>
	</fieldset>
</form>

<fieldset id="datosCierre" name="datosCierre">
		<legend>Cierre del Trámite</legend>

		<div data-linea="16">
			<label for="documentos_entregados">Documentos entregados: </label> 
			<input type="text" value="<?php echo $this->modeloTramites->getDocumentosEntregados(); ?>" 
				disabled="disabled" />
		</div>

		<div data-linea="17">
			<label for="fecha_entrega">Fecha de entrega de documentos: </label> 
			<input type="text" value="<?php echo ($this->modeloTramites->getFechaEntrega()!=null?date('Y-m-d',strtotime($this->modeloTramites->getFechaEntrega())):''); ?>"
				disabled="disabled" />
		</div>

		<div data-linea="18">
			<label for="observaciones">Observaciones: </label> 
			<input type="text" value="<?php echo $this->modeloTramites->getObservaciones(); ?>"
				disabled="disabled" />
		</div>
	</fieldset>

<!-- Form para actualizar trámite en el cierre --> 
<form id='formularioCierre' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='tramites/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $this->modeloTramites->getIdTramite(); ?>" />
	<input type="hidden" id="quipux_agr" name="quipux_agr" value="<?php echo $this->modeloTramites->getQuipuxAgr(); ?>" />
	<input type="hidden" id="derivado" name="derivado" value="<?php echo $this->modeloTramites->getDerivado(); ?>" />
	<input type="hidden" id="estado_tramite" name="estado_tramite" value="Cerrado" />
	
	<fieldset>
		<legend>Cierre del Trámite</legend>

		<div data-linea="16">
			<label for="documentos_entregados">Documentos entregados: </label> 
			<input type="text" id="documentos_entregados" name="documentos_entregados" value="<?php //echo $this->modeloTramites->getDocumentosEntregados(); ?>" 
				placeholder="Nombre de la persona que envía el trámite" maxlength="1024" />
		</div>

		<div data-linea="17">
			<label for="fecha_entrega">Fecha de entrega de documentos: </label> 
			<input type="date" id="fecha_entrega" name="fecha_entrega" value="<?php //echo $this->modeloTramites->getFechaEntrega(); ?>"
				placeholder="Número de referencia del trámite" maxlength="8" max="<?php echo date('Y-m-d'); ?>"/>
		</div>

		<div data-linea="18">
			<label for="observaciones">Observaciones: </label> 
			<input type="text" id="observaciones" name="observaciones" value="<?php //echo $this->modeloTramites->getObservaciones(); ?>"
				placeholder="Número de factura remitida en el trámite" maxlength="1024" />
		</div>

		<div data-linea="19">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</fieldset>
</form>

<script type ="text/javascript">
	$(document).ready(function() {		
		fn_mostrarSeguimientos();
		$("#seccionSeguimientos").hide();
		$("#formularioCierre").hide();
		$("#datosCierre").hide();

		if($("#derivado").val() == 'Si'){
    		$("#formularioSeguimiento").hide();
    		$("#seccionSeguimientos").hide();
    		$("#formularioCierre").hide();
    		$("#cierre").attr('disabled','disabled');
    		$("#datosCierre").show();
		}else{
			if($("#estadoTramite").val() == 'Cerrado'){
    			$("#formularioSeguimiento").hide();
    			$("#seccionSeguimientos").show();
    			$("#cierre").attr('disabled','disabled');
    			$("#datosCierre").show();
    		} else{
    			$("#seccionSeguimientos").show();
    			$("#datosCierre").hide();
    		}
		}

		construirValidador();
		distribuirLineas();		
	 });

	//Para cargar los trámites en estado derivado mostrando su número de quipux_agr
    function fn_mostrarSeguimientos() {
        var idTramite = $("#id_tramite").val();
        
    	$.post("<?php echo URL ?>SeguimientoDocumental/Seguimientos/construirSeguimientos/" + idTramite, function (data) {
            $("#tablaSeguimientos").html(data);
        });
    } 

    function fn_limpiarSeguimientos() {
    	$("#fecha").val('');
		$("#persona_recibe").val('');
		$("#id_unidad_destino").val('');
		$("#observaciones_seguimiento").val('');
    } 

    $("#id_unidad_destino").change(function () {
		$("#unidad_destino_actual").text('');
		
    	if ($("#id_unidad_destino option:selected").val() != "") {
    		$("#unidad_destino_actual").val($("#id_unidad_destino option:selected").text());
    	}else{
    		$("#unidad_destino_actual").val('');
        }
    });

	$("#formularioSeguimiento").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
		       	fn_mostrarSeguimientos();
		       	fn_limpiarSeguimientos();
		       	//fn_filtrar();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#cierre").change(function () {
		if ($(this).val() == "Si") {
        	$("#formularioCierre").show();
        	construirValidador();
    		distribuirLineas();
        }else{
        	$("#formularioCierre").hide();
        	construirValidador();
    		distribuirLineas();
        } 
    });

	$("#formularioCierre").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
		       	fn_mostrarSeguimientos();
	            fn_filtrar();
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});
</script>