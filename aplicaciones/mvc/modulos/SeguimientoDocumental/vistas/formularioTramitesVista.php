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
		
		<div data-linea="00">
			<label for="fecha_creacion">Fecha de Creación: </label> <?php echo ($this->modeloTramites->getFechaCreacion()!=null?date('Y-m-d',strtotime($this->modeloTramites->getFechaCreacion())):''); ?>
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

		<div data-linea="11">
			<label for="quipux_agr">Quipux Agrocalidad: </label> 
			<input type="text"value="<?php echo $this->modeloTramites->getQuipuxAgr(); ?>"
				disabled="disabled" />
		</div>
		
		<div data-linea="11">
			<label for="origen_tramiter">Origen trámite: </label> 
			<input type="text"value="<?php echo $this->modeloTramites->getOrigenTramite(); ?>"
				disabled="disabled" />
		</div>

	</fieldset>


<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>SeguimientoDocumental' data-opcion='tramites/guardar' data-destino="detalleItem" method="post">
	<input type="hidden" id="id_tramite" name="id_tramite" value="<?php echo $this->modeloTramites->getIdTramite(); ?>" /> 
	<!-- input type="hidden" id="id_unidad_destino" name="id_unidad_destino" value="< ?php echo $datosUsuario['idUnidadDestino']; ?>" /--> 
	<input type="hidden" id="codigo_ventanilla" name="codigo_ventanilla" value="<?php echo $datosUsuario['codigoVentanilla']; ?>" />
	<input type="hidden" id="estado_tramite" name="estado_tramite" value="<?php echo $this->modeloTramites->getEstadoTramite(); ?>" />
	
	<fieldset>
		<legend>Trámites</legend>

		<div data-linea="1">
			<label for="numero_tramite">Registro del Trámite: </label> 
			<input type="text" id="numero_tramite" name="numero_tramite" value="<?php echo $this->modeloTramites->getNumeroTramite(); ?>" readonly="readonly" />
		</div>

		<div data-linea="1">
			<label for="id_ventanilla">Ventanilla: </label> 
			<input type="hidden" id="id_ventanilla" name="id_ventanilla" readonly="readonly" value="<?php echo $this->modeloTramites->getIdVentanilla(); ?>" /> 
			<input type="text" readonly="readonly" 
				value="<?php echo $this->modeloTramites->getNombreVentanilla(); ?>" />
		</div>

		<div data-linea="2">
			<label for="identificador">Responsable: </label> <input type="hidden" id="identificador" name="identificador" readonly="readonly" 
				value="<?php echo $this->modeloTramites->getIdentificador(); ?>" />

			<input type="text" readonly="readonly"
				value="<?php echo $this->modeloTramites->getNombreEmpleado(); ?>" />
		</div>

		<div data-linea="0" class="formularioEditar">
			<label for="fecha_creacion">Fecha de Creación: </label> <?php echo ($this->modeloTramites->getFechaCreacion()!=null?date('Y-m-d',strtotime($this->modeloTramites->getFechaCreacion())):''); ?>
		</div>
		
		<hr />

		<div data-linea="3">
			<label for="remitente">Remitente: </label> 
			<input type="text" id="remitente" name="remitente" value="<?php echo $this->modeloTramites->getRemitente(); ?>" 
				placeholder="Nombre de la persona que envía el trámite" required="required" maxlength="128" />
		</div>

		<div data-linea="4">
			<label for="oficio_memo">Oficio/Memo: </label> 
			<input type="text" id="oficio_memo" name="oficio_memo" value="<?php echo $this->modeloTramites->getOficioMemo(); ?>"
				placeholder="Número de referencia del trámite" required="required" maxlength="128" />
		</div>

		<div data-linea="4">
			<label for="factura">Factura: </label> 
			<input type="text" id="factura" name="factura" value="<?php echo $this->modeloTramites->getFactura(); ?>"
				placeholder="Número de factura remitida en el trámite" maxlength="32" required="required" />
		</div>

		<div data-linea="5">
			<label for="guia_quipux">Guía - Correo: </label> 
			<input type="text"
				id="guia_quipux" name="guia_quipux" value="<?php echo $this->modeloTramites->getGuiaQuipux(); ?>"
				placeholder="Número de guía de correo que referencia al trámite recibido"
				maxlength="128" required="required" />
		</div>

		<div data-linea="6">
			<label for="asunto">Asunto: </label> 
			<input type="text" id="asunto" name="asunto" value="<?php echo $this->modeloTramites->getAsunto(); ?>"
				placeholder="Asunto del trámite detallado por el usuario" required="required" maxlength="1024" />
		</div>

		<div data-linea="7">
			<label for="anexos">Anexos: </label> 
			<input type="text" id="anexos" name="anexos" value="<?php echo $this->modeloTramites->getAnexos(); ?>"
				placeholder="Detalle de anexos remitidos con el trámite" maxlength="1024" required="required" />
		</div>

		<hr />

		<div data-linea="8">
			<label for="destinatario">Destinatario: </label> 
			<input type="text" id="destinatario" name="destinatario" value="<?php echo $this->modeloTramites->getDestinatario(); ?>" 
				placeholder="Nombre de la persona a la que está dirigido el trámite" required="required" maxlength="512" />
		</div>

		<div data-linea="13">
			<label for="id_unidad_destino">Unidad de Destino: </label> 
			<select id="id_unidad_destino" name="id_unidad_destino" required>
				<option value="">Seleccionar....</option>
                <?php
                    echo $this->comboAreasCategoriaNacional($this->modeloTramites->getIdUnidadDestino());
                ?>
            </select>
            
            <input type="hidden" id="unidad_destino_actual" name="unidad_destino_actual" value="<?php echo $this->modeloTramites->getUnidadDestinoActual(); ?>" />
		</div>

		<div data-linea="15">
			<label for="derivado">Trámite derivado: </label> 
			<select id="derivado" name="derivado" required <?php echo $this->formulario=="abrir"?'disabled':""; ?> >
                <?php
                    echo $this->comboSiNo($this->modeloTramites->getDerivado());
                ?>
            </select>
		</div>

		<div data-linea="16">
			<label for="quipux_agr">Quipux Agrocalidad: </label> 
            <input type="text" id="quipux_agr" name="quipux_agr" value="<?php echo $this->modeloTramites->getQuipuxAgr(); ?>"
				placeholder="Número de oficio con el que se registra el trámite externo para su atención" maxlength="128" 
				required="required"/>
		</div>
		
		<div data-linea="17">
			<label for="origen_tramite">Origen trámite: </label> 
			<select id="origen_tramite" name="origen_tramite" required>
				<option value="">Seleccionar....</option>
                <?php
                    echo $this->comboOrigenTramite($this->modeloTramites->getOrigenTramite());
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
		$("#formulario").hide();
		$("#datosTramite").show();

		if(($("#estado_tramite").val() == 'Ingresado') || ($("#estado_tramite").val() == '')){
			$("#formulario").show();
			$("#datosTramite").hide();
		}else{
			$("#formulario").hide();
			$("#datosTramite").show();
		}

		if($("#estado_tramite").val() != ''){
			$(".formularioEditar").show();
		}else{
			$(".formularioEditar").hide();
		}

		construirValidador();
		distribuirLineas();
	 });

	$("#derivado").change(function () {
		$("#quipux_agr").val('');
		$("#estado").text("");
    	if ($("#derivado option:selected").val() == "Si") {
    		fn_cargarQuipuxDerivados();
    	}else{
    		$("#quipux_agr").autocomplete({
    			disabled: true
    		});
    		$("#quipux_agr").removeAttr('disabled');
    		$(".guardar").removeAttr('disabled');
    		$("#quipux_agr").val('No Aplica');
        }
    });

	//Para cargar los trámites en estado derivado mostrando su número de quipux_agr
    function fn_cargarQuipuxDerivados() {
        var estado = "'Seguimiento'";
        
    	if ($("#derivado option:selected").val() == "Si") {
        	$.post("<?php echo URL ?>SeguimientoDocumental/Tramites/comboTramites/" + estado, function (data) {
            	if(data.mensaje.length != 0){
            		$("#quipux_agr").autocomplete({
						disabled: false,
            			source: data.mensaje,
            			change:function(event, ui){
                    		if (ui.item == null || ui.item == undefined) {
                    			$("#quipux_agr").val("");
                    		}
            			}
            		});
                }else{
                	$(".guardar").attr('disabled','disabled');
                	$("#quipux_agr").attr('disabled','disabled');
                	mostrarMensaje("No existe trámites derivados.", "FALLO");
				}
        		
            }, 'json');
        }
    } 

    $("#id_unidad_destino").change(function () {
		$("#unidad_destino_actual").text('');
		
    	if ($("#id_unidad_destino option:selected").val() != "") {
    		$("#unidad_destino_actual").val($("#id_unidad_destino option:selected").text());
    	}else{
    		$("#unidad_destino_actual").val('');
        }
    });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);
	       	if (respuesta.estado == 'exito'){
	       		fn_filtrar_default();
	       		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
	        }
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	$("#quipux_agr").change(function(event){
		if ($("#derivado option:selected").val() == "No") {
			numeroQuipux = $(this).val().replace("/","¬");
			$.post("<?php echo URL ?>SeguimientoDocumental/Tramites/buscarQuipux/" + numeroQuipux, function (data) {
	            if(data.mensaje == 'SI'){
	            	mostrarMensaje("El número de Quipux ya ha sido ingresado previamente", "FALLO");
	            	$(".guardar").attr('disabled','disabled');
	            }else{
	            	$(".guardar").removeAttr('disabled');
	            }
	        }, 'json');
		}
	});
</script>