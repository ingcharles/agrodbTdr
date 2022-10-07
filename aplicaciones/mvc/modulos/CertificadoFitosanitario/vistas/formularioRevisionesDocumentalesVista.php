<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<div class="pestania">

	<fieldset>
		<legend>Datos Generales</legend>
		<?php echo $this->datosGeneralesCertificadoFitosanitario; ?>
	</fieldset>

	<fieldset>
		<legend>Puertos de Destino</legend>
    	<?php echo $this->paisPuertosDestino; ?>		
    </fieldset>

	<fieldset>
		<legend>Países, Puertos de Tránsito y Medios de Transporte</legend>  	
         <?php echo $this->paisesPuertosTransito; ?>
    </fieldset>

	<fieldset>
		<legend>Exportadores y Productos</legend>
         <?php echo $this->exportadoresProductosRevisiones; ?>
	</fieldset>

	<fieldset>
		<legend>Documentos Adjuntos</legend>
		<?php echo $this->documentosAdjuntos; ?>
	</fieldset>

	<fieldset>
		<legend>Forma de Pago</legend>

		<div data-linea="14">
			<label for="forma_pago">Forma de Pago: </label>
			<?php echo ($this->modeloCertificadoFitosanitario->getFormaPago() != "") ? $this->modeloCertificadoFitosanitario->getFormaPago() : 'N/A'; ?>
		</div>

		<div data-linea="14">
			<label for="descuento">Descuento: </label>
            <?php echo ($this->modeloCertificadoFitosanitario->getDescuento() != "") ? $this->modeloCertificadoFitosanitario->getDescuento() : 'N/A'; ?>
		</div>

		<div data-linea="15">
			<label for="motivo_descuento">Motivo del Descuento: </label>
			<?php echo ($this->modeloCertificadoFitosanitario->getMotivoDescuento() != "") ? $this->modeloCertificadoFitosanitario->getMotivoDescuento() : 'N/A'; ?>
		</div>

	</fieldset>

</div>

<div class="pestania">

    <?php echo (isset($this->listaDetallesInspecciones)) ? $this->listaDetallesInspecciones : null; ?>
	<?php echo (isset($this->detalleAnulaReemplaza)) ? $this->detalleAnulaReemplaza : null ?>

	<form id='formulario'
		data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario'
		data-opcion='RevisionesDocumentales/guardar'
		data-destino="detalleItem" data-accionEnExito="ACTUALIZAR"
		method="post">
		<input type="hidden" id="id_solicitud" name="id_solicitud"
			value="<?php echo $_POST['id']; ?>" /> <input type="hidden"
			id="tipo_certificado" name="tipo_certificado"
			value="<?php echo $this->modeloCertificadoFitosanitario->getTipoCertificado(); ?>" />
		<input type="hidden" id="forma_pago" name="forma_pago"
			value="<?php echo $this->modeloCertificadoFitosanitario->getFormaPago(); ?>" />
		<input type="hidden" id="es_reemplazo" name="es_reemplazo"
			value="<?php echo $this->modeloCertificadoFitosanitario->getEsReemplazo(); ?>" />
		<input type="hidden" id="id_certificado_reemplazo"
			name="id_certificado_reemplazo"
			value="<?php echo $this->modeloCertificadoFitosanitario->getIdCertificadoReemplazo(); ?>" />
    	
    	<?php echo $this->modeloRevisionesDocumentales->getIdSolicitud(); ?>
    	<fieldset>
			<legend>Revisión Documental</legend>

			<div data-linea="22">
				<label for="estado">Resultado: </label> <select id="estado_revision"
					name="estado_revision" required>
					<option value="">Seleccionar....</option>
                    <?php
                    echo ($this->modeloCertificadoFitosanitario->getTipoCertificado() === 'otros' ? ($this->modeloCertificadoFitosanitario->getEsReemplazo() != 'Si' ? $this->comboResultadosDocumentalOtros() : $this->comboResultadosDocumentalOrnamentalesMusaceasRenovacion()) : $this->comboResultadosDocumentalOrnamentalesMusaceasRenovacion());
                    ?>
                </select>
			</div>

			<div data-linea="23" class="ObservacionRevision">
				<label for="observacion_revision">Observación: </label> <input
					type="text" id="observacion_revision" name="observacion_revision"
					maxlength="2048" />
			</div>

		</fieldset>

		<fieldset class="OtrosSubsanacion">
			<legend>Inspecciones a Revisar</legend>

			<div data-linea="24">
				<label for="id_provincia">Provincia: </label> <select
					id="id_provincia" name="id_provincia">
					<option value="">Seleccionar....</option>
                    <?php
                    echo $this->comboProvinciaXSolicitud($_POST['id'], "'InspeccionAprobada'");
                    ?>
                </select>
			</div>

			<div data-linea="25">
				<label for="id_area_inspeccion">Centro de Acopio: </label> <select
					id="id_area_inspeccion" name="id_area_inspeccion">
					<option value="">Seleccionar....</option>
				</select>
			</div>

			<div data-linea="26" class="oculto">
				<label for="id_producto">Producto: </label> <select id="id_producto"
					name="id_producto">
					<option value="">Seleccionar....</option>
				</select>
			</div>

			<div data-linea="27">
				<label for="estado">Resultado: </label> <select
					id="estado_documental" name="estado_documental">
					<option value="">Seleccionar....</option>
                    <?php
                    echo ($this->modeloCertificadoFitosanitario->getTipoCertificado() === 'otros' ? $this->comboResultadosDocumentalOtros() : $this->comboResultadosDocumentalOrnamentalesMusaceasRenovacion());
                    ?>
                </select>
			</div>

			<div data-linea="28">
				<label for="observacion">Observación: </label> <input type="text"
					id="observacion" name="observacion" maxlength="2048" />
			</div>

			<div data-linea="29">
				<button type="button" class="mas" id="btnAgregarProductos">Agregar</button>
			</div>

			<hr />

			<div data-linea="30">
				<table id="tbItems" style="width: 100%">
					<thead>
						<tr>
							<th style="width: 5%;">#</th>
							<th style="width: 20%;">Provincia</th>
							<th style="width: 20%;">Centro Acopio</th>
							<th style="width: 20%;">Producto</th>
							<th style="width: 10%;">Resultado</th>
							<th style="width: 20%;">Observación</th>
							<th style="width: 5%;"></th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>

		</fieldset>

		<div data-linea="31">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</form>
</div>

<script type="text/javascript">
var combo = "<option>Seleccione....</option>";

	$(document).ready(function() {
		construirAnimacion($(".pestania"));
		construirValidador();
		distribuirLineas();
		$(".OtrosSubsanacion").hide();
		$(".ObservacionRevision").hide();
		$(".ReimpresionReemplazo").hide();
	 });

    $("#estado_revision").change(function () {
    	if ($("#estado_revision option:selected").val() !== "") {
    		if ($("#estado_revision option:selected").val() === "DevueltoTecnico") {
    			$(".OtrosSubsanacion").show(); 
    			$(".ObservacionRevision").hide();           	
            }else{
            	$(".OtrosSubsanacion").hide();
            	$(".ObservacionRevision").show();
            }      	
        }else{
        	$("#estado_revision").val('');
        	$("#observacion_revision").val('');
        }
    });

    $("#id_provincia").change(function () {
    	$("#id_area_inspeccion").html(combo);
    	$("#id_producto").html(combo);
    	$("#estado_documental").val('');
    	$("#observacion_documental").val('');
    	
        if ($("#id_provincia option:selected").val() !== "") {
        	fn_cargarCentrosAcopio();
        	
        }else{
        	$("#id_area_inspeccion").html(combo);
        	$("#id_producto").html(combo);

        }
    });

    $("#id_area_inspeccion").change(function () {
    	$("#id_producto").html(combo);
    	
        if ($("#id_area_inspeccion option:selected").val() !== "") {
        	fn_cargarProductos();        	
        }else{
        	$("#id_producto").html(combo);
        }
    });

	//---------------FUNCIONES-----------------------	
	//Lista de centro de acopio por provincia
    function fn_cargarCentrosAcopio() {
    	fn_limpiar();
    	$("#id_area_inspeccion").html(combo);
    	$("#id_producto").html(combo);
    	
        var idSolicitud = $("#id_solicitud").val();
        var idProvincia = $("#id_provincia option:selected").val();
        var estado = '';

        if ((idSolicitud !== "") && (idProvincia !== "")) {// && (estado !== "")
        	$.post("<?php echo URL ?>CertificadoFitosanitario/RevisionesDocumentales/comboCentrosAcopioPorProvincia", 
    			{
        			id_certificado_fitosanitario : $("#id_solicitud").val(),
        			id_provincia : $("#id_provincia option:selected").val()
    			},
                function (data) {
                    $("#id_area_inspeccion").html(data);
                    $("#id_producto").html(combo);
                });
        }else{
        	$("#id_area_inspeccion").html(combo);
        	$("#id_producto").html(combo);
        	
        	if(!$.trim($("#id_provincia").val())){
    			$("#id_provincia").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
        }
    }
    
	//Lista de productos por centro de acopio y provincia
    function fn_cargarProductos() {
    	fn_limpiar();
    	$("#id_producto").html(combo);
    	
        var idSolicitud = $("#id_solicitud").val();
        var idProvincia = $("#id_provincia option:selected").val();
        var idCentroAcopio = $("#id_area_inspeccion option:selected").val();
        var estado = '';

        if ((idSolicitud !== "") && (idProvincia !== "") && (idCentroAcopio !== "")) {
        	$.post("<?php echo URL ?>CertificadoFitosanitario/RevisionesDocumentales/comboProductosPorCentroAcopioPorProvincia", 
    			{
        			id_certificado_fitosanitario : $("#id_solicitud").val(),
        			id_provincia : $("#id_provincia option:selected").val(),
        			id_area : $("#id_area_inspeccion option:selected").val()
    			},
                function (data) {
                    $("#id_producto").html(data);
                });
        }else{
            $("#id_producto").html(combo);

            if(!$.trim($("#id_provincia").val())){
    			$("#id_provincia").addClass("alertaCombo");
    		}
    		
        	if(!$.trim($("#id_area_inspeccion").val())){
    			$("#id_area_inspeccion").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
        }
    }

  //Función para agregar elementos
    $('#btnAgregarProductos').click(function(){
    	$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#id_provincia").val())){
			error = true;
			$("#id_provincia").addClass("alertaCombo");
		}

		if(!$.trim($("#id_area_inspeccion").val())){
			error = true;
			$("#id_area_inspeccion").addClass("alertaCombo");
		}
		
		if(!$.trim($("#id_producto").val())){
			error = true;
			$("#id_producto").addClass("alertaCombo");
		}

		if(!$.trim($("#estado_documental").val())){
			error = true;
			$("#estado_documental").addClass("alertaCombo");
		}
		
		if(!$.trim($("#observacion").val())){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');

			if($("#id_provincia option:selected").val()!="" && $("#id_area_inspeccion option:selected").val()!="" && $("#id_producto option:selected").val()!="" && $("#estado_documental option:selected").val()!="" && $("#observacion").val()!=""){
				var codigo = 'r_' + $("#id_provincia option:selected").val() + $("#id_area_inspeccion option:selected").val() + $("#id_producto option:selected").val();	
				var cadena = '';

				verificarRegistro($(this).val());

				if($("#tbItems tbody #"+codigo.replace(/ /g,'')).length==0){
					
					cadena = "<tr id='"+codigo.replace(/ /g,'')+"'>"+
								"<td>"+
								"</td>"+
								"<td>"+$("#id_provincia option:selected").text()+
								"	<input id='iProvincia' name='iProvincia[]' value='"+$("#id_provincia option:selected").val()+"' type='hidden'>"+
								"	<input id='nProvincia' name='nProvincia[]' value='"+$("#id_provincia option:selected").text()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#id_area_inspeccion option:selected").text()+
								"	<input id='iAreaInspeccion' name='iAreaInspeccion[]' value='"+$("#id_area_inspeccion option:selected").val()+"' type='hidden'>"+
								"	<input id='nAreaInspeccion' name='nAreaInspeccion[]' value='"+$("#id_area_inspeccion option:selected").text()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#id_producto option:selected").text()+
								"	<input id='iProducto' name='iProducto[]' value='"+$("#id_producto option:selected").val()+"' type='hidden'>"+
								"	<input id='nProducto' name='nProducto[]' value='"+$("#id_producto option:selected").text()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#estado_documental option:selected").text()+
								"	<input id='iEstado' name='iEstado[]' value='"+$("#estado_documental option:selected").val()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#observacion").val()+
								"	<input id='iObservacion' name='iObservacion[]' value='"+$("#observacion").val()+"' type='hidden'>"+
								"</td>"+
								"<td>"+
								"	<button type='button' onclick='quitarRevisionDocumental("+codigo.replace(/ /g,'')+")' class='menos'>Quitar</button>"+
								"</td>"+
							"</tr>"

					$("#tbItems tbody").append(cadena);
					enumerar();
					limpiarDetalle();
				}else{
					$("#estado").html("No puede ingresar dos registros iguales.").addClass('alerta');
				}
			}
		}
    });

    function quitarRevisionDocumental(fila){
		$("#tbItems tbody tr").eq($(fila).index()).remove();	  
		enumerar();
	}

    function verificarRegistro(produ){
		$('#tbItems tbody tr').each(function (rows) {		
			var rd= $(this).find('td').eq(1).find('input[id="idOperacion"]').val();
			filas=$('#tbItems tbody tr').length;
			if (filas>0){
				if(rd == produ){
					rDuplicado=true;
			    	return false;
			    } else{
			    	rDuplicado=false;		    			    		
			    }			        
			}	    
		});
	}

    function enumerar(){			    	    
	    var tabla = document.getElementById('tbItems');
	    con=0;   
	    $("#tbItems tbody tr").each(function(row){        
	    	con+=1;    	
	    	$(this).find('td').eq(0).html(con);    	  	
	    });
	}

    function limpiarDetalle(){
		$("#id_provincia").val("");
		$("#id_area_inspeccion").val("");
    	$("#id_producto").val("");
    	$("#estado_documental").val("");
    	$("#observacion").val("");
	}

    function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}

    function adicionalProducto(id){
    	event.preventDefault();
		visualizar = $("#resultadoInformacionProducto"+id).css("display");
        if(visualizar == "table-row") {
        	$("#resultadoInformacionProducto"+id).fadeOut('fast',function() {
            	$("#resultadoInformacionProducto"+id).css("display", "none");
            });
        }else{
        	$("#resultadoInformacionProducto"+id).fadeIn('fast',function() {
        		$("#resultadoInformacionProducto"+id).css("display", "table-row");
            });
        }
        
        visualizarSitio = $("#resultadoInformacionProductoSitio"+id).css("display");
        if(visualizarSitio == "table-row") {
        	$("#resultadoInformacionProductoSitio"+id).fadeOut('fast',function() {
            	$("#resultadoInformacionProductoSitio"+id).css("display", "none");
            });
        }else{
        	$("#resultadoInformacionProductoSitio"+id).fadeIn('fast',function() {
        		$("#resultadoInformacionProductoSitio"+id).css("display", "table-row");
            });
        }
	}
	
	$("#formulario").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;

		if(($("#tipo_certificado").val() === 'otros') && ($("#estado_revision option:selected").val() === 'DocumentalAprobada')){

			if(!$.trim($("#estado_revision").val())){
				error = true;
				$("#estado").addClass("alertaCombo");
			}
			
			if(!$.trim($("#observacion_revision").val())){
				error = true;
				$("#observacion_revision").addClass("alertaCombo");
			}
			
		}else if(($("#tipo_certificado").val() === 'otros') && ($("#estado_revision option:selected").val() === 'DevueltoTecnico')){

			if(!$.trim($("#estado_revision").val())){
				error = true;
				$("#estado").addClass("alertaCombo");
			}

			//Información en tabla de detalle
			if (($("#iAreaInspeccion").length > 0)){
				error = false;
			}else{
				error = true;
				$("#tbItems").addClass("alertaCombo");
				$("#estado").html("Por favor ingrese por lo menos una provincia/centro de acopio/producto para evaluar.").addClass("alerta");
			}
			
		}else {
			
			if(!$.trim($("#estado_revision").val())){
				error = true;
				$("#estado_revision").addClass("alertaCombo");
			}
			
			if(!$.trim($("#observacion_revision").val())){
				error = true;
				$("#observacion_revision").addClass("alertaCombo");
			}
			
		}
		
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