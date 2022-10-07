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
        <legend>Paises, Puertos de Tránsito y Medios de Transporte</legend>        
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

    <form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario' data-opcion='inspecciones/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
    	<input type="hidden" id="id_solicitud" name="id_solicitud" value="<?php echo $_POST['id']; ?>" />
    	
    	<fieldset>
    		<legend>Inspecciones</legend>		
    		
    		<div data-linea="1">
    			<label for="id_area_inspeccion">Centro de Acopio: </label>
     			<select id="id_area_inspeccion" name="id_area_inspeccion" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboCentrosAcopioPorProvinciaPorSolicitud($_POST['id'], $_SESSION['idProvincia'], "'FechaConfirmada', 'Subsanado', 'DevueltoTecnico'");
                    ?>
                </select>	
                
                <input type="hidden" id="nombre_area_inspeccion" name="nombre_area_inspeccion" />
                <input type="hidden" id="fecha_confirmacion_inspeccion" name="fecha_confirmacion_inspeccion" />
                <input type="hidden" id="hora_confirmacion_inspeccion" name="hora_confirmacion_inspeccion" />		
    		</div>
    
    		<div data-linea="2">
    			<label for="id_producto">Producto: </label>
    			<select id="id_producto" name="id_producto"  >
                    <option value="">Seleccionar....</option>
                </select>			
    			<input type="hidden" id="producto" name="producto" maxlength="128" />
    		</div>	
    		
    		<div data-linea="3">
        		<input type="radio" id="tipo_formulario_ispeccion_tablet" name="tipo_formulario_ispeccion" value="inspeccionPorTablet">
      			<label for="tipo_formulario_ispeccion">Inspección por tablet</label>
    		</div>
    		
    		<div data-linea="3">
    			<input type="radio" id="tipo_formulario_ispeccion_fisica" name="tipo_formulario_ispeccion" value="inspeccionFisica" checked>
      			<label for="tipo_formulario_ispeccion">Inspección física</label>
    		</div>
    		
    		<div data-linea="4">
    			<label for="formulario_inspeccion_tablet">N° de formulario de inspección tablet: </label>
    			<input type="text" id="formulario_inspeccion_tablet" name="formulario_inspeccion_tablet" maxlength="20" disabled="disabled"/>
    		</div>
    		
    		<div data-linea="5" id="observacionDocumentalDiv">
    			<div id="observacionDocumental" name="observacionDocumental" ></div>
    		</div>
    		
    		<div data-linea="6">
    			<label for="estado">Resultado: </label>
    			<select id="estado_inspeccion" name="estado_inspeccion" >
                    <option value="">Seleccionar....</option>
                    <?php
                        echo $this->comboResultadosInspeccion();
                    ?>
                </select>
    		</div>	
    
    		<div data-linea="7">
    			<label for="observacion">Observación: </label>
    			<input type="text" id="observacion" name="observacion" maxlength="2048" />
    		</div>	
    		
    		<div data-linea="8">
        		<button type="button" class="mas" id="btnAgregarProductos">Agregar</button>
        	</div>    		
    		
    		<hr/>

            <table id="tbItems" style="width:100%">
            	<thead>
            		<tr>
            			<th>#</th>
            			<th>Centro Acopio</th>
            			<th>Producto</th>
                        <th>Resultado</th>
                        <th>Observación</th>
                        <th></th>
            		</tr>
            	</thead>
            	<tbody>
            	</tbody>
            </table>

    	</fieldset >    	
    	
    	<fieldset>
    		<legend>Respaldo de Inspección</legend>
            	            	        		
        		<div data-linea="10">
        			<label for="ruta_archivo_inspeccion">Reporte de Inspección: </label>
        			
        			<input type="file" id="informe" class="archivo" accept="application/pdf" /> 
        			<input type="hidden" class="rutaArchivo" name="ruta_archivo_inspeccion" id="ruta_archivo_inspeccion" value="" />
        				
            		<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
            		<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo CERT_FITO_URL_INSP . $this->rutaFecha;?>">Subir archivo</button>
        		</div>
    		
        </fieldset>
        	
    	<div data-linea="29">
			<button type="submit" class="guardar">Guardar</button>
		</div>
    </form >

</div>

<script type ="text/javascript">
var combo = "<option>Seleccione....</option>";
var bandera = <?php echo json_encode($this->formulario); ?>;

	$(document).ready(function() {
		construirAnimacion($(".pestania"));
		construirValidador();
		distribuirLineas();

		$("#observacionDocumentalDiv").hide();
	});

	$("#id_area_inspeccion").change(function () {
		$("#nombre_area_inspeccion").val('');
		$("#observacionDocumentalDiv").hide();
    	$("#observacionDocumental").val('');
    	$("#estado_inspeccion").val("");
    	$("#observacion").val("");
    	
        if ($("#id_area_inspeccion option:selected").val() !== "") {
        	fn_cargarProductos();
        	$("#nombre_area_inspeccion").val($("#id_area_inspeccion option:selected").attr('data-nombre'));
        	
        	$("#fecha_confirmacion_inspeccion").val($("#id_area_inspeccion option:selected").attr('data-fecha'));
        	$("#hora_confirmacion_inspeccion").val($("#id_area_inspeccion option:selected").attr('data-hora'));
            
        }else{
        	$("#nombre_area_inspeccion").val('');
        	$("#id_producto").html(combo);
        	
        	$("#fecha_confirmacion_inspeccion").val('');
        	$("#hora_confirmacion_inspeccion").val('');
        	$("#observacionDocumentalDiv").hide();
        	$("#observacionDocumental").val('');
        }
    });

	$("#id_producto").change(function () {
		$("#estado_inspeccion").val("");
    	$("#observacion").val("");
		
        if ($("#id_producto option:selected").val() !== "" && $("#id_producto option:selected").attr('data-estado') == "DevueltoTecnico") {
        	$("#observacionDocumentalDiv").show();
        	fn_cargarObservacionInspectorDocumental();        	
        }else{
        	$("#observacionDocumentalDiv").hide();
        	$("#observacionDocumental").val('');
        }
    });

	$("input:radio[name=tipo_formulario_ispeccion]").click(function () {
    	if($("input:radio[name=tipo_formulario_ispeccion]:checked").val() == "inspeccionPorTablet"){
    		$('#formulario_inspeccion_tablet').attr('disabled', false);
    		$('#estado_inspeccion').attr('disabled', true);
    		$('#observacion').attr('disabled', true);
    		$('#btnAgregarProductos').attr('disabled', true);
        }else{
    		$('#formulario_inspeccion_tablet').attr('disabled', true);
    		$('#formulario_inspeccion_tablet').val("");
    		$('#estado_inspeccion').attr('disabled', false);
    		$('#observacion').attr('disabled', false);
    		$('#btnAgregarProductos').attr('disabled', false);
    	}
	});

	$("#formulario_inspeccion_tablet").change(function () {
		var identificadorExportador = $("#id_producto option:selected").attr("data-identificadorExportador");
		var numeroFormularioInspeccion = $('#formulario_inspeccion_tablet').val();
		var idProducto = $("#id_producto").val();
		var idPaisDestino = $("#id_producto option:selected").attr("data-idPaisDestino");
		fn_verificarnumeroFormularioInspeccion(identificadorExportador, numeroFormularioInspeccion, idProducto, idPaisDestino);
	});
	
	//---------------FUNCIONES-----------------------
	//Lista de productos por centro de acopio y provincia
    function fn_cargarProductos() {
    	fn_limpiar();
    	$("#id_producto").html(combo);
    	
        var idSolicitud = $("#id_solicitud").val();
        var idCentroAcopio = $("#id_area_inspeccion option:selected").val();
        var estado = '';
        
        /*if(bandera=='nuevo'){
            estado = 'FechaConfirmada';
        }else{
        	estado = 'Subsanado';
        }*/

        if ((idSolicitud !== "") && (idCentroAcopio !== "")) {
        	$.post("<?php echo URL ?>CertificadoFitosanitario/Inspecciones/comboProductosPorCentroAcopioInspeccion", 
    			{
        			id_certificado_fitosanitario : $("#id_solicitud").val(),
        			id_area : $("#id_area_inspeccion option:selected").val()/*,
        			estado_exportador_producto : estado*/
    			},
                function (data) {
                    $("#id_producto").html(data);
                });
        }else{
            $("#id_producto").html(combo);
        	
        	if(!$.trim($("#id_area_inspeccion").val())){
    			$("#id_area_inspeccion").addClass("alertaCombo");
    		}

            $("#estado").html("Por favor ingrese la información requerida para continuar").addClass('alerta');
        }
    }

	//Función para verificar si el numero de formulario de inspeccion pertenece al exportador
	function fn_verificarnumeroFormularioInspeccion(identificadorExportador, numeroFormularioInspeccion, idProducto, idPaisDestino) {
        $.post("<?php echo URL ?>CertificadoFitosanitario/Inspecciones/verificarFormularioInspeccion",
        {
        	identificadorExportador : identificadorExportador,
        	numeroFormularioInspeccion : numeroFormularioInspeccion,
        	idProducto : idProducto,
        	idPaisDestino : idPaisDestino
        }, function (data) {
        	if(data.validacion == "Fallo"){
        		$("#estado").html(data.resultado).addClass('alerta');        		     		
        	}else{
        		$("#estado").html("").removeClass('alerta');
        		$('#estado_inspeccion').attr('disabled', false);
        		$('#observacion').attr('disabled', false);
        		$('#btnAgregarProductos').attr('disabled', false);
            }
        }, 'json');
	}
	
  	//Muestra la observación del Inspector Documental para el centro de acopio y producto elegido
    function fn_cargarObservacionInspectorDocumental() {
    	var idSolicitud = $("#id_solicitud").val();
        var idCentroAcopio = $("#id_area_inspeccion option:selected").val();
        var idProducto = $("#id_producto option:selected").val();
        var estado = $("#id_producto option:selected").attr('data-estado');

        if ((idSolicitud !== "") && (idCentroAcopio !== "") && (idProducto !== "") && (estado == "DevueltoTecnico")) {
        	$.post("<?php echo URL ?>CertificadoFitosanitario/RevisionesDocumentales/consultarResultadoRevisionDocumental", 
    			{
        			id_certificado_fitosanitario : $("#id_solicitud").val(),
        			id_area : $("#id_area_inspeccion option:selected").val(),
        			id_producto : $("#id_producto option:selected").val(),
        			estado_exportador_producto : $("#id_producto option:selected").attr('data-estado')
    			},
                function (data) {
                    $("#observacionDocumental").html("Observación Revisión Documental: " + data.resultado).addClass('alerta');
                }, 'json');
        }else{
        	$("#observacionDocumental").html('');
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
		
		if(!$.trim($("#id_producto").val())){
			error = true;
			$("#id_producto").addClass("alertaCombo");
		}

		if(!$.trim($("#estado_inspeccion").val())){
			error = true;
			$("#estado_inspeccion").addClass("alertaCombo");
		}
		
		if(!$.trim($("#observacion").val())){
			error = true;
			$("#observacion").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');

			if($("#id_area_inspeccion option:selected").val()!="" && $("#id_producto option:selected").val()!="" && $("#estado_inspeccion option:selected").val()!="" && $("#observacion").val()!=""){
				var codigo = 'r_' + $("#id_area_inspeccion option:selected").val() + $("#id_producto option:selected").val();	
				var cadena = '';

				verificarRegistro($(this).val());

				//revisar datos enviados y que se agregue al grid
				if($("#tbItems tbody #"+codigo.replace(/ /g,'')).length==0){
					
					cadena = "<tr id='"+codigo.replace(/ /g,'')+"' data-idAreaProducto = '"+$("#id_area_inspeccion option:selected").val()+$("#id_producto option:selected").val()+"'>" +
								"<td>"+
								"</td>"+
								"<td>"+$("#id_area_inspeccion option:selected").attr('data-nombre')+
								"	<input id='iAreaInspeccion' name='iAreaInspeccion[]' value='"+$("#id_area_inspeccion option:selected").val()+"' type='hidden'>"+
								"	<input id='nAreaInspeccion' name='nAreaInspeccion[]' value='"+$("#nombre_area_inspeccion").val()+"' type='hidden'>"+
								"	<input id='nFechaInspeccion' name='nFechaInspeccion[]' value='"+$("#fecha_confirmacion_inspeccion").val()+"' type='hidden'>"+
								"	<input id='nHoraInspeccion' name='nHoraInspeccion[]' value='"+$("#hora_confirmacion_inspeccion").val()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#id_producto option:selected").text()+
								"	<input id='iProducto' name='iProducto[]' value='"+$("#id_producto option:selected").val()+"' type='hidden'>"+
								"	<input id='nProducto' name='nProducto[]' value='"+$("#id_producto option:selected").text()+"' type='hidden'>"+
								"   <input id='iFormularioInspeccion' name='iFormularioInspeccion[]' value='"+$("#formulario_inspeccion_tablet").val()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#estado_inspeccion option:selected").text()+
								"	<input id='iEstado' name='iEstado[]' value='"+$("#estado_inspeccion option:selected").val()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#observacion").val()+
								"	<input id='iObservacion' name='iObservacion[]' value='"+$("#observacion").val()+"' type='hidden'>"+
								"</td>"+
								"<td>"+
								"	<button type='button' onclick='quitarInspeccion("+codigo.replace(/ /g,'')+","+$("#id_area_inspeccion option:selected").val()+$("#id_producto option:selected").val()+")' class='menos'>Quitar</button>"+
								"</td>"+
							"</tr>"

					$("#tbItems tbody").append(cadena);
					enumerar();
					habilitarCentroAcopio();
					limpiarDetalle();
				}else{
					$("#estado").html("No puede ingresar dos registros iguales.").addClass('alerta');
				}
			}
		}
    });

    function quitarInspeccion(fila, id){
		$("#tbItems tbody tr").eq($(fila).index()).remove();	  
		enumerar();
		$("#id_area_inspeccion option").each(function(){
            if ($(this).attr("data-idAreaProducto") == id) {
            	$(this).removeAttr("disabled");
        	}
        });
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
		$("#id_area_inspeccion").val("");
    	$("#id_producto").val("");
    	$("#observacionDocumentalDiv").hide();
    	$("#observacionDocumental").val("");
    	$("#estado_inspeccion").val("");
    	$("#observacion").val("");
    	$('#formulario_inspeccion_tablet').val("");
    	$('#formulario_inspeccion_tablet').attr('disabled', true);
	}

  //Función para carga de archivo de certificado equivalente
    $('button.subirArchivo').click(function (event) {
    	var nombre_archivo = "<?php echo 'informe_inspeccion_' . $_POST['id'] . time(); ?>";
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivo");
        var extension = archivo.val().split('.');
        var estado = boton.parent().find(".estadoCarga");

        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

            subirArchivo(
                archivo
                , nombre_archivo
                , boton.attr("data-rutaCarga")
                , rutaArchivo
                , new carga(estado, archivo, boton)
            );

        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("0");
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

    function habilitarCentroAcopio(){
		$('#tbItems tbody tr').each(function (rows) {
            var id = $(this).attr("data-idAreaProducto");	
            $("#id_area_inspeccion option").each(function(){
                if ($(this).attr("data-idAreaProducto") == id) {
                	$(this).attr("disabled", "disabled");
            	}
            });
        });
	}
    
	$("#formulario").submit(function (event) {
		fn_limpiar();
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		//Información en tabla de detalle
		if (($("#iAreaInspeccion").length > 0)){
			error = false;
		}else{
			error = true;
			$("#tbItems").addClass("alertaCombo");
			$("#estado").html("Por favor ingrese por lo menos un producto por centro de acopio para evaluar.").addClass("alerta");
		}		

		if($("input:radio[name=tipo_formulario_ispeccion]:checked").val() == "inspeccionFisica"){
			if(!$.trim($("#ruta_archivo_inspeccion").val())){
				error = true;
				$("#informe").addClass("alertaCombo");
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