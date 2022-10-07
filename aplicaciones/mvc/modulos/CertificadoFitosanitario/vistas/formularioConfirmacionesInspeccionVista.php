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
			<?php echo $this->modeloCertificadoFitosanitario->getFormaPago(); ?>
		</div>

		<div data-linea="14">
			<label for="descuento">Descuento: </label>
            <?php echo $this->modeloCertificadoFitosanitario->getDescuento(); ?>
		</div>

		<div data-linea="15">
			<label for="motivo_descuento">Motivo del Descuento: </label>
			<?php echo $this->modeloCertificadoFitosanitario->getMotivoDescuento(); ?>
		</div>

	</fieldset>

</div>

<div class="pestania">

	<form id='formulario'
		data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario'
		data-opcion='confirmacionesInspeccion/guardar'
		data-destino="detalleItem" data-accionEnExito="ACTUALIZAR"
		method="post">
		<input type="hidden" id="id_solicitud" name="id_solicitud"
			value="<?php echo $_POST['id']; ?>" />

		<fieldset>
			<legend>Confirmación de Inspección</legend>

			<div data-linea="1">
				<label for="id_area_inspeccion">Centro de Acopio: </label> <select
					id="id_area_inspeccion" name="id_area_inspeccion">
					<option value="">Seleccionar....</option>
                    <?php
                    echo $this->comboCentrosAcopioPorProvinciaPorSolicitud($_POST['id'], $_SESSION['idProvincia'], "'Creado'");
                    ?>
                </select>
			</div>

			<div data-linea="2" id="fecha_inspeccion_solicitada">
				<div id="fecha_solicitada" name="fecha_solicitada"></div>
			</div>

			<div data-linea="3">
				<label for="fecha_confirmacion_inspeccion">Fecha de Inspección: </label>
				<input type="text" id="fecha_confirmacion_inspeccion"
					name="fecha_confirmacion_inspeccion" readonly="readonly" required />
			</div>

			<div data-linea="3">
				<label for="hora_confirmacion_inspeccion">Hora de Inspección: </label>
				<input type="time" id="hora_confirmacion_inspeccion"
					name="hora_confirmacion_inspeccion" maxlength="8" />
			</div>

			<div data-linea="6">
				<button type="button" class="mas" id="btnAgregarProductos">Agregar</button>
			</div>

			<hr />

			<table id="tbItems" style="width: 100%">
				<thead>
					<tr>
						<th>#</th>
						<th>Centro Acopio</th>
						<th>Fecha inspección</th>
						<th>Hora inspección</th>
						<th>Opción</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>

			<div id="cargarMensajeTemporal"></div>
		</fieldset>

		<div data-linea="8">
			<button type="submit" class="guardar">Guardar</button>
		</div>
	</form>

</div>

<script type="text/javascript">
	$(document).ready(function() {
		construirAnimacion($(".pestania"));
		construirValidador();
		distribuirLineas();

		$("#fecha_inspeccion_solicitada").hide();

	 });

	$("#id_area_inspeccion").change(function () {
    	$("#fecha_confirmacion_inspeccion").val("");
    	$("#hora_confirmacion_inspeccion").val("");
    	
        if ($("#id_area_inspeccion option:selected").val() !== "") {
        	$("#nombre_area_inspeccion").val($("#id_area_inspeccion option:selected").attr('data-nombre'));
        	
        	$("#fecha_inspeccion_solicitada").show();
            $("#fecha_solicitada").html("<strong>Fecha de Inspección Solicitada: </strong>"+$("#id_area_inspeccion option:selected").attr('data-fecha')+ ' - ' +$("#id_area_inspeccion option:selected").attr('data-hora'));
            $("#fecha_confirmacion_inspeccion").val($("#id_area_inspeccion option:selected").attr('data-fecha'));
            
        }else{
        	$("#nombre_area_inspeccion").val('');
        	
        	$("#fecha_inspeccion_solicitada").hide();
        	$("#fecha_solicitada").html('');
        	$("#fecha_confirmacion_inspeccion").val('');
        	$("#hora_confirmacion_inspeccion").val('');
        }
    });

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
	}

  	//Función para agregar elementos
    $('#btnAgregarProductos').click(function(){
    	$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#id_area_inspeccion").val())){
			error = true;
			$("#id_area_inspeccion").addClass("alertaCombo");
		}
		
		if(!$.trim($("#fecha_confirmacion_inspeccion").val())){
			error = true;
			$("#fecha_confirmacion_inspeccion").addClass("alertaCombo");
		}

		if(!$.trim($("#hora_confirmacion_inspeccion").val())){
			error = true;
			$("#hora_confirmacion_inspeccion").addClass("alertaCombo");
		}
		
		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');

			if($("#id_area_inspeccion option:selected").val( )!= "" && $("#fecha_confirmacion_inspeccion option:selected").val() != "" && $("#hora_confirmacion_inspeccion option:selected").val() != ""){
				var codigo = 'r_' + $("#id_area_inspeccion option:selected").val();	
				var cadena = '';

				//revisar datos enviados y que se agregue al grid
				if($("#tbItems tbody #" + codigo.replace(/ /g,'')).length == 0){
					
					cadena = "<tr id='" + codigo.replace(/ /g,'')+"' data-idArea = '" + $("#id_area_inspeccion option:selected").val() +"'>" +
								"<td>" +
								"</td>" +
								"<td>" + $("#id_area_inspeccion option:selected").attr('data-nombre') +
								"<input id='iAreaInspeccion' name='iAreaInspeccion[]' value='" + $("#id_area_inspeccion option:selected").val() + "' type='hidden'>" +
								"<input id='nAreaInspeccion' name='nAreaInspeccion[]' value='" + $("#id_area_inspeccion option:selected").attr("data-nombre") + "' type='hidden'>" +							
								"</td>" +
								"<td>" + $("#fecha_confirmacion_inspeccion").val() +
								"<input id='nFechaConfirmacionInspeccion' name='nFechaConfirmacionInspeccion[]' value='" + $("#fecha_confirmacion_inspeccion").val() + "' type='hidden'>" +
								"</td>" +
								"<td>" + $("#hora_confirmacion_inspeccion").val() +
								"<input id='nHoraConfirmacionInspeccion' name='nHoraConfirmacionInspeccion[]' value='" + $("#hora_confirmacion_inspeccion").val() + "' type='hidden'>" +
								"</td>"+
								"<td>"+
								"<button type='button' onclick='quitarInspeccion("+codigo.replace(/ /g,'')+","+$("#id_area_inspeccion option:selected").val()+")' class='menos'>Quitar</button>"+
								"</td>"+
							"</tr>"

					$("#tbItems tbody").append(cadena);
					enumerar();
					habilitarCentroAcopio();
					limpiarDetalle();				
				}else{
					$("#estado").html("No puede ingresar la misma área.").addClass('alerta');
				}
			}
		}
    });

    function quitarInspeccion(fila, id){
		$("#tbItems tbody tr").eq($(fila).index()).remove();	  
		enumerar();
        $("#id_area_inspeccion option").each(function(){
            if ($(this).val() == id) {
            	$(this).removeAttr("disabled");
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
		$("#id_area_inspeccion").val("");
    	$("#fecha_confirmacion_inspeccion").val("");
    	$("#hora_confirmacion_inspeccion").val("");
	}

	function habilitarCentroAcopio(){
		$('#tbItems tbody tr').each(function (rows) {		
            var id = $(this).attr("data-idArea");
            $("#id_area_inspeccion option").each(function(){
                if ($(this).val() == id) {
                	$(this).attr("disabled", "disabled");
            	}
            });
        });
	}
	
	$("#formulario").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		var error = false;
		array1 = [];
		array2 = [];
		
		$('#tbItems tbody tr').each(function (rows) {
			var id = $(this).attr("data-idArea");
			array1.push(id);				
		});

		$("#id_area_inspeccion option").each(function(){
            var id2 = $(this).val();
			if(id2 != ""){            
            	array2.push(id2);
			}            
        });
		        
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