
<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<fieldset>
	<legend>Revisión Técnica</legend>
	<div data-linea="1">
		<label for="observacion_revision">Observación:</label> 
		<?php echo $this->modeloCertificadoFitosanitario->getObservacionRevision(); ?>	
	</div>
</fieldset>

<fieldset>
	<legend>Datos Generales</legend>
	<?php echo $this->datosGeneralesCertificadoFitosanitario; ?>	
</fieldset>

<form id='formularioPuertosPaisDestino'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario'
	data-opcion='PuertosDestino/guardar' data-destino="detalleItem"
	method="post">

	<fieldset>
		<legend>Puertos de Destino</legend>

		<input type="hidden" id="id_certificado_fitosanitario_puertos_destino"
			name="id_certificado_fitosanitario_puertos_destino"
			value="<?php echo $this->modeloCertificadoFitosanitario->getIdCertificadoFitosanitario(); ?>"
			readonly="readonly" /> <input type="hidden"
			id="nombre_puerto_pais_destino" name="nombre_puerto_pais_destino"
			value="" readonly="readonly" />

		<div data-linea="1">
			<label for="id_pais_destino">País de Destino: </label> <select
				id="id_pais_destino" name="id_pais_destino" class="validacion">
                <?php
                echo $this->comboPaisesPorIdioma($this->modeloCertificadoFitosanitario->getIdIdioma(), $this->modeloCertificadoFitosanitario->getIdPaisDestino());
                ?>
            </select>
		</div>

		<div data-linea="1">
			<label for="id_puerto_pais_destino">Puerto de Destino: </label> <select
				id="id_puerto_pais_destino" name="id_puerto_pais_destino"
				class="validacion">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<hr />

		<div data-linea="5">
			<button type="submit" class="mas" id="agregarPuertoPaisDestino">Agregar</button>
		</div>

		<?php echo $this->paisPuertosDestino; ?>
		
	</fieldset>

</form>

<form id='formularioPaisesPuertosTransito'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario'
	data-opcion='PaisesPuertosTransito/guardar' data-destino="detalleItem"
	method="post">

	<fieldset>
		<legend>Añadir Países, Puertos de Tránsito y Medios de Transporte</legend>

		<input type="hidden"
			id="id_certificado_fitosanitario_pais_puerto_transito"
			name="id_certificado_fitosanitario_pais_puerto_transito"
			value="<?php echo $this->modeloCertificadoFitosanitario->getIdCertificadoFitosanitario(); ?>"
			readonly="readonly" />

		<div data-linea="1">
			<label for="id_pais_transito">País de Tránsito: </label> <select
				id="id_pais_transito" name="id_pais_transito" class="validacion">
				<option value="">Seleccionar....</option>
            <?php
            echo $this->comboPaisesPorIdioma($this->modeloCertificadoFitosanitario->getIdIdioma());
            ?>
        </select>
		</div>

		<div data-linea="1">
			<label for="id_puerto_transito">Puerto de Tránsito: </label> <select
				id="id_puerto_transito" name="id_puerto_transito" class="validacion"
				disabled="disabled">
				<option value="">Seleccionar....</option>
			</select>
		</div>

		<div data-linea="2">
			<label for="id_medio_transporte_transito">Medio de Transporte: </label>
			<select id="id_medio_transporte_transito"
				name="id_medio_transporte_transito" class="validacion"
				disabled="disabled">
				<option value="">Seleccionar....</option>
            <?php
            echo $this->comboMediosTransportePorIdioma($this->modeloCertificadoFitosanitario->getIdIdioma());
            ?>
        </select>
		</div>

		<hr />

		<div data-linea="3">
			<button type="submit" class="mas" id="agregarPaisPuertoTransito">Agregar</button>
		</div>
    
    <?php echo $this->paisesPuertosTransito; ?>
    
    </fieldset>

</form>

<form id='formularioExportadoresProductos'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario'
	data-opcion='ExportadoresProductos/guardar' data-destino="detalleItem"
	method="post">

	<fieldset>
		<legend>Exportadores y Productos</legend>				

		<?php echo (isset($this->ingresarExportadoresProductos)) ? $this->ingresarExportadoresProductos : null; ?>

		<?php echo $this->exportadoresProductos; ?>
	
	</fieldset>

</form>

<form id='formulario'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario'
	data-opcion='CertificadoFitosanitario/guardarSubsanacion'
	data-destino="detalleItem" data-accionEnExito="ACTUALIZAR"
	method="post">

	<input type="hidden" id="id_certificado_fitosanitario"
		name="id_certificado_fitosanitario"
		value="<?php echo $this->modeloCertificadoFitosanitario->getIdCertificadoFitosanitario(); ?>"
		readonly="readonly" /> <input type="hidden" id="informacion_adicional"
		name="informacion_adicional" value="" readonly="readonly" maxlength="825" /> <input
		type="hidden" id="nombre_consignatario" name="nombre_consignatario"
		value="" readonly="readonly" /> <input type="hidden"
		id="direccion_consignatario" name="direccion_consignatario" value=""
		readonly="readonly" />

	<fieldset>
		<legend>Documentos Adjuntos</legend>

		<div data-linea="1">
			<input type="hidden" id="ruta_adjunto" class="ruta_adjunto"
				name="ruta_adjunto" value="" /> <input type="file" id="estadoCarga"
				class="archivo"
				accept="application/msword | application/pdf | image/*" />
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?></div>
			<button type="button" class="subirArchivo adjunto"
				data-rutaCarga="<?php echo CERT_FIT_DOC_ADJ.$this->rutaFecha ?>">Subir
				archivo</button>
		</div>

		<div data-linea="2">
			<label for="ruta_enlace_adjunto">Ruta a documentos de respaldo (mayor
				a 6Mbs): </label> <input type="text" id="ruta_enlace_adjunto"
				name="ruta_enlace_adjunto" value=""
				placeholder="Ingrese el enlace del archivo" maxlength="256" />
		</div>

		<hr />
	
		<?php echo $this->documentosAdjuntos; ?>
	</fieldset>

	<fieldset>
		<legend>Forma de Pago</legend>

		<div data-linea="1">
			<label for="forma_pago">Forma de Pago: </label>
			<?php echo ($this->modeloCertificadoFitosanitario->getFormaPago() != "") ? $this->modeloCertificadoFitosanitario->getFormaPago() : "N/A"?>
		</div>

		<div data-linea="1">
			<label for="descuento">Descuento: </label>
            <?php echo ($this->modeloCertificadoFitosanitario->getDescuento() != "") ? $this->modeloCertificadoFitosanitario->getDescuento() : "N/A" ?>
		</div>

		<div data-linea="2">
			<label for="motivo_descuento">Motivo del Descuento: </label>
			<?php echo ($this->modeloCertificadoFitosanitario->getMotivoDescuento() != "") ? $this->modeloCertificadoFitosanitario->getMotivoDescuento() : "N/A"; ?>
		</div>

	</fieldset>
	
	<?php echo (isset($this->detalleAnulaReemplaza)) ? $this->detalleAnulaReemplaza : null ?>
			
	<button type="submit" class="guardar">Enviar solicitud</button>

</form>


<script type="text/javascript">

	var tipoCertificado = "<?php echo $this->modeloCertificadoFitosanitario->getTipoCertificado(); ?>";
	var productoOrganico = "<?php echo $this->modeloCertificadoFitosanitario->getProductoOrganico(); ?>";
	var idPaisDestino = "<?php echo $this->modeloCertificadoFitosanitario->getIdPaisDestino(); ?>";
	var fechaEmbarque = "<?php echo date('Y-m-d',strtotime($this->modeloCertificadoFitosanitario->getFechaEmbarque())); ?>";
	var arrayExportadoresProductosEditados = [];	

	$(document).ready(function() {

		fn_cargarPuertos("pais", $("#id_puerto_pais_destino"), $("#id_pais_destino option:selected").val());
		$("#id_pais_destino option:not(:selected)").attr("disabled",true);
		
		if(tipoCertificado == "otros"){
			fechaEmbarque = new Date(fechaEmbarque);
			$("#fecha_inspeccion").datepicker({ 
			    changeMonth: true,
			    changeYear: true,
			    dateFormat: 'yy-mm-dd',
			    minDate: fechaEmbarque
			});
		}

		construirValidador();
		fn_mostrarOcultarInformacion();
		fn_mostrarOcultarInformacion(tipoCertificado, false);
		$("#cantidad_comercial").numeric();
		$("#peso_neto").numeric();
		$("#peso_bruto").numeric();
		$("#duracion_tratamiento").numeric();
		$("#temperatura_tratamiento").numeric();
		$("#concentracion_tratamiento").numeric();
		if(productoOrganico == "Si"){	
			$("#iCentificacionOrganica").show();
		}else{
			$("#iCentificacionOrganica").hide();
		}
		$("#estado").html("").removeClass('alerta');
		distribuirLineas();
	 });

	//////////////////////////////////////
	////////PUERTOS DE DESTINO///////////
	
	$("#id_puerto_pais_destino").change(function () {
    	$("#nombre_puerto_pais_destino").val($("#id_puerto_pais_destino option:selected").attr('data-nombrepuerto'));
    });	
      	
  	//Funcion para agregar fila de detalle de puerto de destino
    $("#formularioPuertosPaisDestino").submit(function (event) {
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#id_puerto_pais_destino").val() == ""){
			error = true;
			$("#id_puerto_pais_destino").addClass("alertaCombo");
		}
				
		if (!error) {

			$("#estado").html("").removeClass('alerta');
	        
			$.post("<?php echo URL ?>CertificadoFitosanitario/PuertosDestino/guardar",
		    	{

				  	id_certificado_fitosanitario_puertos_destino : $("#id_certificado_fitosanitario_puertos_destino").val(), 
				  	nombre_pais_destino : $("#id_pais_destino option:selected").text(),
				    id_puerto_pais_destino : $("#id_puerto_pais_destino").val(),
				    nombre_puerto_pais_destino : $("#nombre_puerto_pais_destino").val()
				 
		        },
		      	function (data) {
		        	if (data.validacion === 'Fallo') {		        		
		        		mostrarMensaje(data.resultado,"FALLO");    	        		
	                }else{
	                	$("#detallePuertoPaisDestino tbody").append(data.filaPuertoPaisDestino);
	                	limpiarDetalle('paisPuertosDestino');
		            }
		        }, 'json');
	        
		} else {
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
	});

    //Funcion que elimina una fila del detalle de puertos de destino
    function fn_eliminarDetallePuertosDestino(idPuertoDestino) {
        
    	$("#estado").html("").removeClass('alerta');
    	
    	if($("#detallePuertoPaisDestino").find('tbody tr').length > 1){	
            $.post("<?php echo URL ?>CertificadoFitosanitario/PuertosDestino/borrar",
            {                
                elementos: idPuertoDestino
            },
            function (data) {
            	$("#" + idPuertoDestino).remove();
            });
		}else{
			mostrarMensaje("Agregue un muevo puerto de destino para eliminar el primero.", "FALLO");
			setTimeout(function(){
                $("#estado").html("").removeClass('alerta');
               },4000);
		}    	
        
    }

  	  	
		
	//////////////////////////////////////
	////////PUERTOS DE TRANSITO///////////
	
	$("#id_pais_transito").change(function () { 	
		$("#id_puerto_transito").attr("disabled",false);
		$("#id_medio_transporte_transito").attr("disabled",false);
        if ($(this).val !== "") {
        	fn_cargarPuertos("pais", $("#id_puerto_transito"), $("#id_pais_transito option:selected").val());
        }
	});

    
  	//Funcion para agregar fila de detalle de paises y puertos de transito
    $("#formularioPaisesPuertosTransito").submit(function (event) {
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if($("#id_pais_transito").val() == ""){
			error = true;
			$("#id_pais_transito").addClass("alertaCombo");
		}

		if($("#id_puerto_transito").val() == ""){
			error = true;
			$("#id_puerto_transito").addClass("alertaCombo");
		}

		if($("#id_medio_transporte_transito").val() == ""){
			error = true;
			$("#id_medio_transporte_transito").addClass("alertaCombo");
		}		
		
		if (!error) {

			$("#estado").html("").removeClass('alerta');
	       	        
			$.post("<?php echo URL ?>CertificadoFitosanitario/PaisesPuertosTransito/guardar",
		    	{

					id_certificado_fitosanitario_pais_puerto_transito : $("#id_certificado_fitosanitario_pais_puerto_transito").val(),
                    id_pais_transito : $("#id_pais_transito").val(),
                    nombre_pais_transito : $("#id_pais_transito option:selected").text(),
                    id_puerto_transito : $("#id_puerto_transito").val(),
                    nombre_puerto_transito : $("#id_puerto_transito option:selected").attr('data-nombrepuerto'),
                    id_medio_transporte_transito : $("#id_medio_transporte_transito").val(),
                    nombre_medio_transporte_transito : $("#id_medio_transporte_transito option:selected").text()
                    				 
		        },
		      	function (data) {
		        	if (data.validacion === 'Fallo') {		        		
		        		mostrarMensaje(data.resultado,"FALLO");    	        		
	                }else{
	                	$("#detallePaisPuertoTransito tbody").append(data.filaPaisPuertoTransito);
	                	limpiarDetalle('paisPuertosTransito');
		            }
		        }, 'json');
	        
		} else {
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
	});

	//Funcion que elimina una fila del detalle de paises y puertos de transito
    function fn_eliminarDetallePaisPuertosTransito(idPaisPuertoTransito) {
        
    	$("#estado").html("").removeClass('alerta');
    	
        $.post("<?php echo URL ?>CertificadoFitosanitario/PaisesPuertosTransito/borrar",
        {                
            elementos: idPaisPuertoTransito
        },
        function (data) {
        	$("#" + idPaisPuertoTransito).remove();
        });
    }

  	//Funcion que enumera la tabla de detalle de puertos paises destino
    function enumerarPaisPuertoTransito(){			    	    
	    con=0;   
	    $("#detallePaisPuertoTransito tbody tr").each(function(row){        
	    	con+=1;    	
	    	$(this).find('td').eq(0).html(con);    	  	
	    });
	}

  	//Funcion que elimina una fila de documento adjunto
    function fn_eliminarDocumentoAdjunto(idDocumentoAdjunto) {
        
    	$("#estado").html("").removeClass('alerta');
    	
        $.post("<?php echo URL ?>CertificadoFitosanitario/DocumentosAdjuntos/borrar",
        {                
            elementos: idDocumentoAdjunto
        },
        function (data) {
        	$("#" + idDocumentoAdjunto).remove();
        });
    }
	
	////////////////////////////////////////
	////////EXPOTADORES PRODUCTOS///////////	
		
    $("#identificador_exportador").change(function () {
		$("#id_tipo_producto").attr("disabled",false);				
		fn_obtenerDatosOperador($("#identificador_exportador").val());
	});

	$("#id_tipo_producto").change(function () {
		$("#id_subtipo_producto").attr("disabled",false);		
		fn_obtenerSubtiposProductoOperadorPorIdTipoProductoPorTipoSolicitud($("#identificador_exportador").val(), $("#id_tipo_producto").val(), tipoCertificado);
	});

	$("#id_subtipo_producto").change(function () {
		$("#id_producto").attr("disabled",false);	
		$("#partida_arancelaria_producto").val("");
		fn_obtenerProductosOperadorPorIdSubtipoProductoPorTipoSolicitudPorPais($("#identificador_exportador").val(), $("#id_subtipo_producto").val(), tipoCertificado, $("#id_pais_destino").val());
	});

	$("#id_producto").change(function () {
		fn_mostrarOcultarInformacion();
		if(tipoCertificado == "musaceas"){			
			fn_mostrarOcultarInformacion(tipoCertificado, true);			
		}else{
			fn_mostrarOcultarInformacion(tipoCertificado, false);	
		}
		$("#partida_arancelaria_producto").val($("#id_producto option:selected").attr("data-partidaArancelaria"));
	});

	$("#certificacion_organica").change(function () {
		var identificadorExportador = $("#identificador_exportador").val();
		var codigoPoa = $("#certificacion_organica").val();
		fn_verificarCodigoPoaExportador(identificadorExportador, codigoPoa);
	});

	$("#tipo_centro_acopio").change(function () {
		if(tipoCertificado == "otros" || tipoCertificado == "ornamentales"){    	
    		if($("#tipo_centro_acopio").val() == "propio")
    		{
    			$("#identificador_centro_acopio").val($("#identificador_exportador").val())
    			$('#identificador_centro_acopio').attr('readonly', true);
    			fn_obtenerCentroAcopioExportadorProducto();
    		}else{
    			$("#identificador_centro_acopio").val("")
    			$('#identificador_centro_acopio').attr('readonly', false);
    		}
		}	
    });
    
    $("#identificador_centro_acopio").change(function () {
    	if(tipoCertificado == "otros" || tipoCertificado == "ornamentales"){
    		if($("#identificador_centro_acopio").val() != ""){
    			fn_obtenerCentroAcopioExportadorProducto();
    		}
    	}	
	});
	
	  //Funcion para mostrar y ocultar informacion    
    function fn_mostrarOcultarInformacion(valor, estado){

    	$("#cantidad_comercial").val("");
    	$("#id_unidad_cantidad_comercial").val("");
    	$("#peso_neto").val("");
    	$("#peso_bruto").val("");
    	$("#tipo_centro_acopio").val("");
    	$("#identificador_centro_acopio").val("");
    	$("#codigo_centro_acopio").val("");
    	$("#fecha_inspeccion").val("");
    	$("#hora_inspeccion").val("");

		switch(valor){
			case "otros":				
				$("#cTipoCentroAcopio").show();
				$("#buscarCentroAcopio").show();
				$("#centroAcopio").show();
	    		$("#fechaInspeccion").show();
	    		$("#horaInspeccion").show();
	    		if(estado){
		    		$("#pesoBruto").show();
		    		$("#idUnidadPesoBruto").show();
	    		}else{
    				$("#pesoBruto").hide();
    				$("#idUnidadPesoBruto").hide();
	    		}
			break;
			case "ornamentales":
				$("#cTipoCentroAcopio").show();
				$("#buscarCentroAcopio").show();
				$("#hCentroAcopio").show();
				$("#centroAcopio").show();
	    		if(estado){
		    		$("#pesoBruto").show();
		    		$("#idUnidadPesoBruto").show();
	    		}else{
    				$("#pesoBruto").hide();
    				$("#idUnidadPesoBruto").hide();
	    		}
			break;
			case "musaceas":
				$("#pesoBruto").show();
				$("#idUnidadPesoBruto").show();
			break;
			default:
				$("#cTipoCentroAcopio").hide();
				$("#buscarCentroAcopio").hide();
				$("#centroAcopio").hide();
	    		$("#fechaInspeccion").hide();
	    		$("#horaInspeccion").hide();
	    		$("#pesoBruto").hide();
				$("#idUnidadPesoBruto").hide();
			break;
		}

		distribuirLineas();
		
    }

	//Funcion para validar el producto y area registrada//	
	function fn_obtenerCentroAcopioExportadorProducto() {
		
    	$("#estado").html("").removeClass('alerta');

    	var tipoCentroAcopio = $("#tipo_centro_acopio option:selected").val();
    	var identificadorExportador = $("#identificador_exportador").val();
		var identificadorProveedor = $("#identificador_centro_acopio").val();
 		var idProducto = $("#id_producto").val();
	 	var idPaisDestino = $("#id_pais_destino").val();

    	if (idProducto !== ""){    
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarCentroAcopioExportadorProducto",
    	                {
            			 	tipoCentroAcopio :tipoCentroAcopio,
        					identificadorExportador : identificadorExportador,
        					identificadorProveedor : identificadorProveedor,
        			 		idProducto : idProducto,
        			 		idLocalizacion : idPaisDestino,
        			 		tipoSolicitud : tipoSolicitud    			 		
    	                }, function (data) {    
    	                    if(data.validacion != 'Exito'){
    	                    	mostrarMensaje(data.mensaje, "FALLO");
    		                }
    	                    $("#codigo_centro_acopio").html(data.resultado);    		                       
    	                }, 'json');
    	}  

    } 

    //Funcion para obtener la informacion del operador por cedua/RUC
    function fn_obtenerDatosOperador(identificadorExportador) {  

    	$("#estado").html("").removeClass('alerta');
        
    	if (identificadorExportador !== ""){    
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarDatosOperadorPorIdentificador",
    	                {
    			 		identificadorExportador : identificadorExportador
    	                }, function (data) {
    	    				if(data.validacion == "Fallo"){
    	    	        		mostrarMensaje(data.resultado,"FALLO");
    	    	        		fn_cargarDatosOperador(data);	        		
    	    				}else{
    	    					fn_cargarDatosOperador(data);
    	    					fn_obtenerTiposProductoOperadorPorTipoSolicitud($("#identificador_exportador").val(), tipoCertificado);
    	    				}
    	                }, 'json');
    	}    

    }

  	//Función para mostrar los datos obtenidos del operador
    function fn_cargarDatosOperador(data) {
        
    	if(data.validacion == "Fallo"){
    		$("#identificador_exportador").val("");
			$("#razon_social_exportador").val("");
			$("#direccion_exportador").val("");
		}else{
			$("#razon_social_exportador").val(data.nombreOperador);
			$("#direccion_exportador").val(data.direccionOperador);
		}
		
    }

	//Funcion para obtener los tipos denproductos registrados del exportador
    function fn_obtenerTiposProductoOperadorPorTipoSolicitud(identificadorExportador, tipoSolicitud) {  

    	$("#estado").html("").removeClass('alerta');
    	
    	if (identificadorExportador !== "" && tipoSolicitud != ""){ //TODO:Descomentar esta seccion cuando se valide el tipo   
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarTipoProductoPorOperadorPorTipoSolicitud",
    	                {
        			 		identificadorExportador : identificadorExportador,
        			 		tipoSolicitud : tipoSolicitud
    	                }, function (data) {    
    	                	if(data.validacion != 'Exito'){
    	                		mostrarMensaje(data.mensaje, "FALLO");
    		                }
    		                $("#id_tipo_producto").html(data.resultado);
    			                  
    	                }, 'json');
    	}  

    }

	//Funcion para obtener los subtipos de productos registrados del exportador por tipo y tipo de solicitud
    function fn_obtenerSubtiposProductoOperadorPorIdTipoProductoPorTipoSolicitud(identificadorExportador, idTipoProducto, tipoSolicitud) {  

    	$("#estado").html("").removeClass('alerta');
    	
    	if (idTipoProducto !== "" && tipoSolicitud != ""){ //TODO:Descomentar esta seccion cuando se valide el tipo       
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarSubtiposProductoOperadorPorIdTipoProductoPorTipoSolicitud",
    	                {
        			 		identificadorExportador : identificadorExportador,
        			 		idTipoProducto : idTipoProducto,
        			 		tipoSolicitud : tipoSolicitud
    	                }, function (data) {
    	                    $("#id_subtipo_producto").html(data);               
    	                });
    	}  

    }

    //Funcion para obtener los productos registrados del exportador por subtipo y tipo de solicitud
    function fn_obtenerProductosOperadorPorIdSubtipoProductoPorTipoSolicitudPorPais(identificadorExportador, idSubtipoProducto, tipoSolicitud, idPaisDestino) {  
		
    	$("#estado").html("").removeClass('alerta');

    	if (idSubtipoProducto !== "" && tipoSolicitud != ""){ //TODO:Descomentar esta seccion cuando se valide el tipo     
    		 $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarProductosOperadorPorIdSubtipoProductoPorTipoSolicitudPorPais",
    	                {
        			 		identificadorExportador : identificadorExportador,
        			 		idSubtipoProducto : idSubtipoProducto,
        			 		tipoSolicitud : tipoSolicitud,
        			 		idLocalizacion : idPaisDestino
    	                }, function (data) {    
    	                    if(data.validacion != 'Exito'){
    	                    	mostrarMensaje(data.mensaje, "FALLO");
    		                }
    		                $("#id_producto").html(data.resultado);    		                       
    	                }, 'json');
    	}  

    }
    
    $("#fecha_inspeccion").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd'
	});

	$("#fecha_tratamiento").datepicker({ 
	    changeMonth: true,
	    changeYear: true,
	    dateFormat: 'yy-mm-dd'
	});

    //Funcion para editar los datos de los exportadores productos
    function fn_editarDetalleExportadoresProductos(idExportadorProducto) {
		
		var cantidadComercial = $("#cantidad_comercial" + idExportadorProducto).val();
		var pesoBruto = $("#peso_bruto" + idExportadorProducto).val();
		var pesoNeto = $("#peso_neto" + idExportadorProducto).val();
		var estadoExportadorProducto = $("#estado_exportador_producto" + idExportadorProducto).val();		

		$("#estado").html("").removeClass('alerta');
		$("#cantidad_comercial" + idExportadorProducto).removeClass("alertaCombo");
		$("#peso_bruto" + idExportadorProducto).removeClass("alertaCombo");
		$("#peso_neto" + idExportadorProducto).removeClass("alertaCombo");
 
        $.post("<?php echo URL ?>CertificadoFitosanitario/ExportadoresProductos/actualizarDatosExportadoresProductos",
                {
         			idExportadorProducto : idExportadorProducto,
        			cantidadComercial : cantidadComercial,
        			pesoBruto : pesoBruto,
        			pesoNeto : pesoNeto
                }, function (data) {
        			if(data.validacion == "Fallo"){
                		mostrarMensaje(data.resultado,"FALLO");
                		fn_mostrarErrorExportadoresProductos(data);	        		
        			}else{
        				mostrarMensaje(data.resultado,"EXITO");
        				fn_inhabilitarDetalleExportadoresProductos(idExportadorProducto);
        				
        				datos = {"idExportadorProducto" : idExportadorProducto};
        				
        				setTimeout(function(){
        	                 $("#estado").html("").removeClass('alerta');
        	                },1500);
        			}
        
                }, 'json');

	}
 
    function fn_habilitarDetalleExportadoresProductos(idExportadorProducto) {
    	$("#cantidad_comercial" + idExportadorProducto).removeAttr("disabled");
		$("#peso_bruto" + idExportadorProducto).removeAttr("disabled");
		$("#peso_neto" + idExportadorProducto).removeAttr("disabled");
    }
    
    function fn_mostrarErrorExportadoresProductos(data) {
    	event.preventDefault();
		error = true;
    	$("#"+data.elemento+data.idExportadorProducto).addClass("alertaCombo");

    }

    function fn_inhabilitarDetalleExportadoresProductos(idExportadorProducto) {
    	$("#cantidad_comercial" + idExportadorProducto).attr("disabled", true);
		$("#peso_bruto" + idExportadorProducto).attr("disabled", true);
		$("#peso_neto" + idExportadorProducto).attr("disabled", true);
    }
    
  	//Función para verificar que el código POA pertenece al operador exportador
	function fn_verificarCodigoPoaExportador(identificadorExportador, codigoPoa) {
        $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/verificarCodigoPoaPorExportador",
        {
        	identificadorExportador : identificadorExportador,
        	codigoPoa : codigoPoa
        }, function (data) {
        	if(data.validacion == "Fallo"){
        		$("#certificacion_organica").val("");
        		mostrarMensaje(data.mensaje, "FALLO");		     		
        	}
        }, 'json');
	}	
    
  	//Funcion para agregar fila de detalle de exportadores productos
    $("#formularioExportadoresProductos").submit(function (event) {
    	event.preventDefault();
       	mostrarMensaje("", "");
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;

    	if(!$.trim($("#identificador_exportador").val())){
			error = true;
			$("#identificador_exportador").addClass("alertaCombo");
		}
    
    	if(!$.trim($("#id_tipo_producto").val())){
			error = true;
			$("#id_tipo_producto").addClass("alertaCombo");
		}

    	if(!$.trim($("#id_subtipo_producto").val())){
			error = true;
			$("#id_subtipo_producto").addClass("alertaCombo");
		}
		
    	if(!$.trim($("#id_producto").val())){
			error = true;
			$("#id_producto").addClass("alertaCombo");
		}

    	if($("#producto_organico option:selected").val() == "Si"){
        	if(!$.trim($("#certificacion_organica").val())){
    			error = true;
    			$("#certificacion_organica").addClass("alertaCombo");
    		}
    	}

    	if(!$.trim($("#cantidad_comercial").val()) || $("#cantidad_comercial").val() <= 0){
			error = true;
			$("#cantidad_comercial").addClass("alertaCombo");
		}

    	if(tipoCertificado == "otros" || tipoCertificado == "musaceas"){
        	if($("#id_producto option:selected").attr("data-clasificacion") == "musaceas"){
        		if(!$.trim($("#peso_bruto").val()) || $("#peso_bruto").val() <= 0){
        			error = true;
        			$("#peso_bruto").addClass("alertaCombo");
        		}
            }
    	}

    	if(tipoCertificado == "otros"){
    		if(!$.trim($("#fecha_inspeccion").val())){
    			error = true;
    			$("#fecha_inspeccion").addClass("alertaCombo");
    		}
    		if(!$.trim($("#hora_inspeccion").val())){
    			error = true;
    			$("#hora_inspeccion").addClass("alertaCombo");
    		}
        }

    	if(!$.trim($("#peso_neto").val())){
			error = true;
			$("#peso_neto").addClass("alertaCombo");
		}

    	if(!$.trim($("#id_unidad_cantidad_comercial").val())){
			error = true;
			$("#id_unidad_cantidad_comercial").addClass("alertaCombo");
		}

		if(tipoCertificado == "otros" || tipoCertificado == "ornamentales"){
				
        	if(!$.trim($("#tipo_centro_acopio").val())){
    			error = true;
    			$("#tipo_centro_acopio").addClass("alertaCombo");
    		}
    
        	if(!$.trim($("#identificador_centro_acopio").val())){
    			error = true;
    			$("#identificador_centro_acopio").addClass("alertaCombo");
    		}
    
        	if(!$.trim($("#codigo_centro_acopio").val())){
    			error = true;
    			$("#codigo_centro_acopio").addClass("alertaCombo");
    		}

		}

    	if($.trim($("#duracion_tratamiento").val())){			
			if ($("#id_unidad_duracion").val() == ""){
				error = true;
				$("#id_unidad_duracion").addClass("alertaCombo");				
			}			
		}

    	if($.trim($("#id_tratamiento").val())){			
			if ($("#fecha_tratamiento").val() == ""){
				error = true;
				$("#fecha_tratamiento").addClass("alertaCombo");				
			}			
		}

    	if($.trim($("#temperatura_tratamiento").val())){			
			if ($("#id_unidad_temperatura").val() == ""){
				error = true;
				$("#id_unidad_temperatura").addClass("alertaCombo");				
			}			
		}

    	if($.trim($("#fecha_tratamiento").val())){			
			if ($("#id_tratamiento").val() == ""){
				error = true;
				$("#id_tratamiento").addClass("alertaCombo");				
			}			
		}

    	if($.trim($("#concentracion_tratamiento").val())){			
			if ($("#id_unidad_concentracion").val() == ""){
				error = true;
				$("#id_unidad_concentracion").addClass("alertaCombo");				
			}			
		}
		
		if (!error) {

			$("#estado").html("").removeClass('alerta');
	        
			$.post("<?php echo URL ?>CertificadoFitosanitario/ExportadoresProductos/guardar",
		    	{

                id_certificado_fitosanitario_exportadores_productos : $("#id_certificado_fitosanitario_exportadores_productos").val(),
				id_pais_destino : $("#id_pais_destino").val(),
                identificador_exportador : $("#identificador_exportador").val(),
                razon_social_exportador : $("#razon_social_exportador").val(),
                direccion_exportador : $("#direccion_exportador").val(),
                id_tipo_producto : $("#id_tipo_producto").val(),
                nombre_tipo_producto : $("#id_tipo_producto option:selected").text(),
                id_subtipo_producto : $("#id_subtipo_producto").val(),
                nombre_subtipo_producto : $("#id_subtipo_producto option:selected").text(),
                id_producto : $("#id_producto").val(),
                nombre_producto : $("#id_producto option:selected").attr("data-nombreProducto"),
                certificacion_organica : $("#certificacion_organica").val(),
                partida_arancelaria_producto : $("#partida_arancelaria_producto").val(),
                cantidad_comercial : $("#cantidad_comercial").val(),
                id_unidad_cantidad_comercial : $("#id_unidad_cantidad_comercial").val(),
                nombre_unidad_cantidad_comercial : $("#id_unidad_cantidad_comercial option:selected").text(),
                peso_bruto : $("#peso_bruto").val(),
                id_unidad_peso_bruto : $("#id_unidad_peso_bruto").val(),
                nombre_unidad_peso_bruto : $("#id_unidad_peso_bruto option:selected").text(),
                peso_neto : $("#peso_neto").val(),
                id_unidad_peso_neto : $("#id_unidad_peso_neto").val(),
                nombre_unidad_peso_neto : $("#id_unidad_peso_neto option:selected").text(),
                id_area : $("#codigo_centro_acopio option:selected").attr("data-idArea"),
                nombre_area : $("#codigo_centro_acopio option:selected").attr("data-nombreArea"),
                codigo_centro_acopio : $("#codigo_centro_acopio").val(),
                id_provincia_area : $("#codigo_centro_acopio option:selected").attr("data-idProvinciaArea"),
                nombre_provincia_area : $("#codigo_centro_acopio option:selected").attr("data-nombreProvinciaArea"),
                fecha_inspeccion : $("#fecha_inspeccion").val(),
                hora_inspeccion : $("#hora_inspeccion").val(),
                id_tipo_tratamiento : $("#id_tipo_tratamiento").val(),
                nombre_tipo_tratamiento : $("#id_tipo_tratamiento option:selected").text(),
                id_tratamiento : $("#id_tratamiento").val(),
                nombre_tratamiento : $("#id_tratamiento option:selected").text(),
                duracion_tratamiento : $("#duracion_tratamiento").val(),
                id_unidad_duracion : $("#id_unidad_duracion").val(),
                nombre_unidad_duracion : $("#id_unidad_duracion option:selected").text(),
                temperatura_tratamiento : $("#temperatura_tratamiento").val(),
                id_unidad_temperatura : $("#id_unidad_temperatura").val(),
                nombre_unidad_temperatura : $("#id_unidad_temperatura option:selected").text(),
                fecha_tratamiento : $("#fecha_tratamiento").val(),
                producto_quimico : $("#producto_quimico").val(),
                concentracion_tratamiento : $("#concentracion_tratamiento").val(),
                id_unidad_concentracion : $("#id_unidad_concentracion").val(),
                nombre_unidad_concentracion : $("#id_unidad_concentracion option:selected").text()              
                    				 
		        },
		      	function (data) {
		        	if (data.validacion === 'Fallo') {		        		
		        		mostrarMensaje(data.resultado,"FALLO");    	        		
	                }else{
	                	$("#detalleExportadoresProductos tbody").append(data.filaExportadorProducto);
	                	limpiarDetalle('exportadoresProductos');
	                	fn_mostrarOcultarInformacion();
		            }
		        }, 'json');
	        
		} else {
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
	});
	
	//Funcion que elimina una fila del detalle de exportadores productos
    function fn_eliminarDetalleExportadoresProductos(idExportadorProducto) {
        
    	$("#estado").html("").removeClass('alerta');
    	
		if($("#detalleExportadoresProductos").find('tbody tr').length > 3){	
            $.post("<?php echo URL ?>CertificadoFitosanitario/ExportadoresProductos/borrar",
            {                
                elementos: idExportadorProducto
            },
            function (data) {            	
            	$("#" + idExportadorProducto).remove();
            });

		}else{
			mostrarMensaje("No pueden eliminar todos los productos registrados.", "FALLO");
			setTimeout(function(){
                $("#estado").html("").removeClass('alerta');
               },4000);
		}
    }

    
	//////////////////////////////////////
	////////FUNCIONES ADICIONALES/////////

	//Funcion que limpia detalles de las tablas
    function limpiarDetalle(valor){

        switch(valor){

            case "paisPuertosDestino":
            	$("#id_puerto_pais_destino").val("");
            	$("#nombre_puerto_pais_destino").val("");
            break;
    
            case "paisPuertosTransito":
            	$("#id_pais_transito").val("");
            	$("#id_puerto_transito").val("");
            	$("#id_medio_transporte_transito").val("");
            break; 
    
            case "exportadoresProductos":
            	$("#identificador_exportador").val("");
            	$("#razon_social_exportador").val("");
            	$("#direccion_exportador").val("");
            	$("#id_tipo_producto").val("");
            	$("#id_subtipo_producto").val("");
            	$("#id_producto").val("");
            	$("#certificacion_organica").val("");
            	$("#partida_arancelaria_producto").val("");
            	$("#cantidad_comercial").val("");
            	$("#id_unidad_cantidad_comercial").val("");
            	$("#peso_bruto").val("");
            	$("#peso_neto").val("");
            	$("#codigo_centro_acopio").val("");
            	$("#fecha_inspeccion").val("");
            	$("#hora_inspeccion").val("");
            	$("#id_tipo_tratamiento").val("");
            	$("#id_tratamiento").val("");
            	$("#duracion_tratamiento").val("");
            	$("#id_unidad_duracion").val("");
            	$("#temperatura_tratamiento").val("");
            	$("#id_unidad_temperatura").val("");
            	$("#fecha_tratamiento").val("");
            	$("#producto_quimico").val("");
            	$("#id_concentracion_tratamiento").val("");
            	$("#tipo_centro_acopio").val("");
            	$("#identificador_centro_acopio").val("");
            	$("#concentracion_tratamiento").val("");
            	$("#id_unidad_concentracion").val("");
            break;
        
        }

	}

  	//Funcion para cargar puertos de provincia o de pais
    function fn_cargarPuertos(tipoValor, objeto, idLocalizacion) {                
    
    	if (idLocalizacion !== ""){    
            $.post("<?php echo URL ?>CertificadoFitosanitario/CertificadoFitosanitario/buscarPuertosPorIdPais",
                {
                 idLocalizacion : idLocalizacion,
                 tipoValor : tipoValor
                }, function (data) {                
                objeto.html(data);               
            });
        } 
    
    }

    function cargarDatosEnvio(){
    	$("#informacion_adicional").val($("#informacion_adicional_m").val());
    	$("#nombre_consignatario").val($("#nombre_consignatario_m").val());
    	$("#direccion_consignatario").val($("#direccion_consignatario_m").val());
    }

	//Accion para cargar archivo adjunto
	$("button.subirArchivo").click(function (event) {
	  	  
	  	var boton = $(this);
		var nombre_archivo = "<?php echo 'certificadofitosantario_' . (md5(time())); ?>";
	    var archivo = boton.parent().find(".archivo");
	    var rutaArchivo = boton.parent().find(".ruta_adjunto");
	    var extension = archivo.val().split('.');
	    var estado = boton.parent().find(".estadoCarga");
	
	    if (extension[extension.length - 1].toUpperCase() == 'PDF') {
	
	        subirArchivo(
	            archivo
	            , nombre_archivo
	            , boton.attr("data-rutaCarga")
	            , rutaArchivo
	            , new carga(estado, archivo, $("#no"))
	            
	        );
	    } else {
	        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
	        archivo.val("0");        
	        }
	});
    
    
	////////////////////////////////////////
	////////FUNCION DE GUARDADO///////////    
	
	$("#formulario").submit(function (event) {

		event.preventDefault();
		var error = false;
		var mensajeDetalle = "";
		$(".alertaCombo").removeClass("alertaCombo");

		cargarDatosEnvio();

		if(!$.trim($("#nombre_consignatario_m").val())){
			error = true;
			$("#nombre_consignatario_m").addClass("alertaCombo");
		}

		if(!$.trim($("#direccion_consignatario_m").val())){
			error = true;
			$("#direccion_consignatario_m").addClass("alertaCombo");
		}
		
		if($("#detallePuertoPaisDestino tbody tr").length == 0){
        	error = true;
        	mensajeDetalle += " Debe seleccionar un país de destino.";       	
        }

        if($("#detalleExportadoresProductos tbody tr").length == 0){
        	error = true;
        	mensajeDetalle += " Debe seleccionar un exportador y producto.";
        }

		if (!error) {
			
			var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);

				if (respuesta.estado == 'exito'){
		       		$("#estado").html(respuesta.mensaje);
		       		$("#_actualizar").click();
					$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
		        }
				
		} else {
			mostrarMensaje("Por favor revise los campos obligatorios." + mensajeDetalle, "FALLO");
		}
		
	});

	//Funcion que valida las cantidades//
    function verificarCantidades(idExportadorProducto, cantidad, idElemento, tipoCantidad) {
		
		$("#estado").html("").removeClass('alerta');
        
        $.post("<?php echo URL ?>CertificadoFitosanitario/ExportadoresProductos/verificarCantidades",
        {
        	idExportadorProducto : idExportadorProducto,
        	cantidad : cantidad,
        	tipoCantidad : tipoCantidad
        }, function (data) {
        	if(data.validacion == "Fallo"){
        		$("#"+idElemento+"").val(data.cantidad);	        		     		
        	}
        }, 'json');

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

</script>
