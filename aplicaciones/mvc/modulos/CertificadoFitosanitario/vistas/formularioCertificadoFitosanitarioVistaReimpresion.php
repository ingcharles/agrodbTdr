<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario' data-opcion='certificadoFitosanitario/guardar' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	
	<input type="hidden" id="id_certificado_fitosanitario_reemplazo" name="id_certificado_fitosanitario_reemplazo" value="<?php echo $this->modeloCertificadoFitosanitario->getIdCertificadoFitosanitario(); ?>" readonly="readonly" />
    <input type="hidden" id="identificador_solicitante" name="identificador_solicitante" value="<?php echo $this->identificador; ?>" readonly="readonly" />
	<input type="hidden" id="estado_certificado" name="estado_certificado" value="DocumentalReimpresion" readonly="readonly" />				
		
	<input type="hidden" id="tipo_certificado" name="tipo_certificado" value="<?php echo $this->modeloCertificadoFitosanitario->getTipoCertificado(); ?>" readonly="readonly" />
	<input type="hidden" id="id_idioma" name="id_idioma" value="<?php echo $this->modeloCertificadoFitosanitario->getIdIdioma(); ?>" readonly="readonly" />
	<input type="hidden" id="nombre_idioma" name="nombre_idioma" value="<?php echo $this->modeloCertificadoFitosanitario->getNombreIdioma(); ?>" readonly="readonly" />
	<input type="hidden" id="producto_organico" name="producto_organico" value="<?php echo $this->modeloCertificadoFitosanitario->getProductoOrganico(); ?>" readonly="readonly" />
	<input type="hidden" id="id_pais_origen" name="id_pais_origen" value="<?php echo $this->modeloCertificadoFitosanitario->getIdPaisOrigen(); ?>" readonly="readonly" />
	<input type="hidden" id="nombre_pais_origen" name="nombre_pais_origen" value="<?php echo $this->modeloCertificadoFitosanitario->getNombrePaisOrigen(); ?>" readonly="readonly" />
	<input type="hidden" id="nombre_pais_destino" name="nombre_pais_destino" value="<?php echo $this->modeloCertificadoFitosanitario->getNombrePaisDestino(); ?>" readonly="readonly" />
	<input type="hidden" id="id_provincia_origen" name="id_provincia_origen" value="<?php echo $this->modeloCertificadoFitosanitario->getIdProvinciaOrigen(); ?>" readonly="readonly" />
	<input type="hidden" id="nombre_provincia_origen" name="nombre_provincia_origen" value="<?php echo $this->modeloCertificadoFitosanitario->getNombreProvinciaOrigen(); ?>" readonly="readonly" />
	<input type="hidden" id="fecha_embarque" name="fecha_embarque" value="<?php echo $this->modeloCertificadoFitosanitario->getFechaEmbarque(); ?>" readonly="readonly" />
	<input type="hidden" id="id_medio_transporte" name="id_medio_transporte" value="<?php echo $this->modeloCertificadoFitosanitario->getIdMedioTransporte(); ?>" readonly="readonly" />
	<input type="hidden" id="nombre_medio_transporte" name="nombre_medio_transporte" value="<?php echo $this->modeloCertificadoFitosanitario->getNombreMedioTransporte(); ?>" readonly="readonly" />
	<input type="hidden" id="id_puerto_embarque" name="id_puerto_embarque" value="<?php echo $this->modeloCertificadoFitosanitario->getIdPuertoEmbarque(); ?>" readonly="readonly" />
	<input type="hidden" id="nombre_puerto_embarque" name="nombre_puerto_embarque" value="<?php echo $this->modeloCertificadoFitosanitario->getNombrePuertoEmbarque(); ?>" readonly="readonly" />
	<input type="hidden" id="nombre_marca" name="nombre_marca" value="<?php echo $this->modeloCertificadoFitosanitario->getNombreMarca(); ?>" readonly="readonly" />
	<input type="hidden" id="numero_viaje" name="numero_viaje" value="<?php echo $this->modeloCertificadoFitosanitario->getNumeroViaje(); ?>" readonly="readonly" />
	<input type="hidden" id="proceso_solicitud" name="proceso_solicitud" value="<?php echo $this->procesoSolicitud; ?>" readonly="readonly" />
	
	<!-- Inputs de arrays  -->	
	<input type="hidden" id="array_pais_puertos_destino" name="array_pais_puertos_destino" value="" readonly="readonly" />
	<input type="hidden" id="array_pais_puertos_transito" name="array_pais_puertos_transito" value="" readonly="readonly" />
	<input type="hidden" id="array_exportadores_productos" name="array_exportadores_productos" value="" readonly="readonly" />
	
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
    
    <fieldset>
    	<legend>Puertos de Destino</legend>				

    	<div data-linea="1">
    		<label for="id_pais_destino">País de Destino: </label>
    		<select id="id_pais_destino" name="id_pais_destino" class="validacion">
    			<option value="">Seleccionar....</option>
                <?php 
                    echo $this->comboPaises($this->modeloCertificadoFitosanitario->getIdPaisDestino());
                ?>
            </select>
    	</div>	
    
    	<div data-linea="1">
    		<label for="id_puerto_pais_destino">Puerto de Destino: </label>
    		<select id="id_puerto_pais_destino" name="id_puerto_pais_destino" class="validacion">
    			<option value="">Seleccionar....</option>
            </select>
    	</div>
    
    	<hr/>
    
    	<div data-linea="5">
    		<button type="button" class="mas" id="agregarPuertoPaisDestino">Agregar</button>
    	</div>
    
    	<?php echo $this->paisPuertosDestinoReimpresion; ?>
    	
    </fieldset>
    
    <fieldset>
        <legend>Añadir Países, Puertos de Tránsito y Medios de Transporte</legend>
		
        <div data-linea="1">
        	<label for="id_pais_transito">País de Tránsito: </label>
        	<select id="id_pais_transito" name="id_pais_transito" class="validacion">
        		<option value="">Seleccionar....</option>
                <?php 
                echo $this->comboPaises();
            ?>
            </select>
        </div>
        		
        <div data-linea="1">
        	<label for="id_puerto_transito">Puerto de Tránsito: </label>
        	<select id="id_puerto_transito" name="id_puerto_transito" class="validacion" disabled="disabled">
        		<option value="">Seleccionar....</option>
            </select>
        </div>
        
        <div data-linea="2">
        	<label for="id_medio_transporte_transito">Medio de Transporte: </label>
        	<select id="id_medio_transporte_transito" name="id_medio_transporte_transito" class="validacion" disabled="disabled">
        		<option value="">Seleccionar....</option>
                <?php 
            echo $this->comboMediosTransporte();
            ?>
            </select>
        </div>					
        
        <hr/>
        
        <div data-linea="3">
        	<button type="button" class="mas" id="agregarPaisPuertoTransito">Agregar</button>
        </div>
        
        <?php echo $this->paisesPuertosTransitoReimpresion; ?>
        
    </fieldset>
    
    <fieldset>
    	<legend>Exportadores y Productos</legend>				
    		<?php echo $this->exportadoresProductosReimpresion; ?>

    </fieldset>
    
    <fieldset>
		<legend>Documentos Adjuntos</legend>	
		
		<div data-linea="1">
			<input type="hidden" id="ruta_adjunto" class="ruta_adjunto" name="ruta_adjunto" value="" />
			<input type="file"  id="estadoCarga" class="archivo" accept="application/msword | application/pdf | image/*"/>
            <div class="estadoCarga" >En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?></div>
           	<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo CERT_FIT_DOC_ADJ.$this->rutaFecha ?>">Subir archivo</button> 
		</div>
		
		<div data-linea="2">
			<label for="ruta_enlace_adjunto">Ruta a documentos de respaldo (mayor a 6Mbs): </label>
			<input type="text" id="ruta_enlace_adjunto" name="ruta_enlace_adjunto" value=""
			placeholder="Ingrese el enlace del archivo" maxlength="256" />
		</div>
    	
    </fieldset>
    
	<fieldset>
    <legend>Anula y Reemplaza</legend>
    <div data-linea="1">
    	<label for="motivo_reemplazo">Motivo de Anula y Reemplaza: </label>
        <input type="text" id="motivo_reemplazo" name="motivo_reemplazo" />
    </div>
    </fieldset>
   
	<input type="hidden" id="fecha_modificacion_certificado" name="fecha_modificacion_certificado" value="<?php echo $this->rutaFecha; ?>" />
	
	<button type="submit" class="guardar" id="guardar">Enviar solicitud</button>
	
</form>

<script type ="text/javascript">

	var tipoCertificado = "<?php echo $this->modeloCertificadoFitosanitario->getTipoCertificado(); ?>";
	var estadoCertificado = "<?php echo $this->modeloCertificadoFitosanitario->getEstadoCertificado(); ?>";
	
	$(document).ready(function() {	
		$("#formulario").keypress(function(event) {
	        if (event.which == 13) {
	            return false;
	        }
		});
		
    	if(estadoCertificado == "Aprobado"){    		
    		fn_cargarPuertos("pais", $("#id_puerto_pais_destino"), $("#id_pais_destino option:selected").val());
    		$("#id_pais_destino option:not(:selected)").attr("disabled",true);
		}else{
    		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una solicitud en estado "Aprobado" y presione el botón Anula y Reemplaza.</div>');
        }
    	
		construirValidador();
		distribuirLineas();
		
	 });

	 $("#descuento").change(function () {
		if($("#descuento").val() == "Si"){
			$("#motivo_descuento").attr("disabled",false);		
		}else{
			$("#motivo_descuento").attr("disabled",true);	
			$("#motivo_descuento").val("");
		}
     });
	
     ///////////////////////////////////////
     ////////PAIS PUERTOS DESTINO///////////
	
	 //Accion para agregar puertos de pais destino
	 $("#agregarPuertoPaisDestino").click(function(event) {
        
    	event.preventDefault();
       	mostrarMensaje("", "");
    	$(".alertaCombo").removeClass("alertaCombo");
    	var error = false;

    	if($("#id_pais_destino").val() == ""){
			error = true;
			$("#id_pais_destino").addClass("alertaCombo");
		}
    	
    	if($("#id_puerto_pais_destino").val() == ""){
			error = true;
			$("#id_puerto_pais_destino").addClass("alertaCombo");
		}

        if(!error){
   
			$("#estado").html("").removeClass('alerta');

			if($("#id_pais_destino").val() != "" && $("#id_puerto_pais_destino").val() != ""){
				
				var codigoPuertoPaisDestino = 'r_' + $("#id_pais_destino").val() + $("#id_puerto_pais_destino").val();
				var cadena = '';

				//Valida que no exista en la tabla
				if($("#detallePuertoPaisDestino tbody #"+codigoPuertoPaisDestino.replace(/ /g,'')).length == 0){
										
					cadena = "<tr id='"+codigoPuertoPaisDestino.replace(/ /g,'')+"'>"+
								"<td>"+
								"</td>"+
								"<td>"+$("#id_pais_destino option:selected").text()+
								"<input name='iPaisDestino[]' value='"+$("#id_pais_destino option:selected").val()+"' type='hidden'>"+
								"<input name='nPaisDestino[]' value='"+$("#id_pais_destino option:selected").text()+"' type='hidden'>"+
								"</td>"+
								"<td>"+$("#id_puerto_pais_destino option:selected").text()+
								"<input name='iPuertoPaisDestino[]' value='"+$("#id_puerto_pais_destino option:selected").val()+"' type='hidden'>"+
								"<input name='nPuertoPaisDestino[]' value='"+$("#id_puerto_pais_destino option:selected").text()+"' type='hidden'>"+
								"</td>"+
								"<td class='borrar'>"+
								"<button type='button' onclick='quitarPuertoPaisDestino("+codigoPuertoPaisDestino.replace(/ /g,'')+")' class='icono'></button>"+
								"</td>"+
							"</tr>"

					$("#detallePuertoPaisDestino tbody").append(cadena);
					
					enumerarPuertoPaisDestino();
					limpiarDetalle('paisPuertosDestino');
				}else{
					$("#estado").html("No puede ingresar dos registros iguales.").addClass('alerta');
				}
			}
        	
		}else{
			mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
		}
    });

  	//Funcion que quita una fila de la tabla puertos de destino
    function quitarPuertoPaisDestino(fila){
		$("#detallePuertoPaisDestino tbody tr").eq($(fila).index()).remove();		 
		enumerarPuertoPaisDestino();
	}

    //Funcion que enumera fila de la tabla puertos de destino
    function enumerarPuertoPaisDestino(){			    	    
	    con=0;   
	    $("#detallePuertoPaisDestino tbody tr").each(function(row){        
	    	con+=1;    	
	    	$(this).find('td').eq(0).html(con);    	  	
	    });
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
    $("#agregarPaisPuertoTransito").click(function(event) {
    	event.preventDefault();
       	mostrarMensaje("", "");
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
    
        if(!error){
    
    		$("#estado").html("").removeClass('alerta');
    
    		if($("#id_pais_transito").val() != "" && $("#id_puerto_transito").val() != ""){
    			
    			var codigoPaisPuertoTransito = 'r_' + $("#id_pais_transito").val() + $("#id_puerto_transito").val();
    			var cadena = '';
    
    			//Valida que no exista en la tabla
    			if($("#detallePaisPuertoTransito tbody #"+codigoPaisPuertoTransito.replace(/ /g,'')).length == 0){
    				
    				//nombreObjetoPaisPuertoTransito = $("#array_pais_puertos_transito");
    				
    				cadena = "<tr id='"+codigoPaisPuertoTransito.replace(/ /g,'')+"'>"+
    							"<td>"+
    							"</td>"+
    							"<td>"+$("#id_pais_transito option:selected").text()+
    							"<input name='iPaisTransito[]' value='"+$("#id_pais_transito option:selected").val()+"' type='hidden'>"+
    							"<input name='nPaisTransito[]' value='"+$("#id_pais_transito option:selected").text()+"' type='hidden'>"+
    							"</td>"+    							
    							"<td>"+$("#id_puerto_transito option:selected").text()+
    							"<input name='iPuertoTransito[]' value='"+$("#id_puerto_transito option:selected").val()+"' type='hidden'>"+
    							"<input name='nPuertoTransito[]' value='"+$("#id_puerto_transito option:selected").text()+"' type='hidden'>"+
    							"</td>"+
    							"<td>"+$("#id_medio_transporte_transito option:selected").text()+
    							"<input name='iMedioTransporteTransito[]' value='"+$("#id_medio_transporte_transito option:selected").val()+"' type='hidden'>"+
    							"<input name='nMedioTransporteTransito[]' value='"+$("#id_medio_transporte_transito option:selected").text()+"' type='hidden'>"+
    							"</td>"+
    							"<td class='borrar'>"+
    							"<button type='button' onclick='quitarPaisPuertoTransito("+codigoPaisPuertoTransito.replace(/ /g,'')+")' class='icono'></button>"+
    							"</td>"+
    						"</tr>"
    
    				$("#detallePaisPuertoTransito tbody").append(cadena);
    				
    				enumerarPaisPuertoTransito();
    				limpiarDetalle('paisPuertosTransito');
    			}else{
    				$("#estado").html("No puede ingresar dos registros iguales.").addClass('alerta');
    			}
    		}
        	
    	}else{
    		mostrarMensaje("Por favor revise los campos obligatorios.", "FALLO");
    	}
    });

	//Funcion que quita una fila de la tabla paises puertos de transito
    function quitarPaisPuertoTransito(fila){
		$("#detallePaisPuertoTransito tbody tr").eq($(fila).index()).remove();		 
		enumerarPaisPuertoTransito();
	}
	
	//Funcion que enumera la tabla paises puertos de transito
    function enumerarPaisPuertoTransito(){			    	    
        con=0;   
        $("#detallePaisPuertoTransito tbody tr").each(function(row){        
        	con+=1;    	
        	$(this).find('td').eq(0).html(con);    	  	
        });
    }
    

	/////////////////////////////////////////
	////////EXPORTADORES PRODUCTOS///////////
	
    //Funcion que quita una fila de la tabla exportadores productos
    function quitarExportadoresProductos(fila){
		if($("#detalleExportadoresProductos tbody tr").length > 1){
			$("#detalleExportadoresProductos tbody tr").eq($(fila).index()).remove();		 
			enumerarExportadoresProductos();
		}
	}

   //Funcion que enumera la tabla de exportadores Productos
    function enumerarExportadoresProductos(){			    	    
	    con=0;   
	    $("#detalleExportadoresProductos tbody tr").each(function(row){        
	    	con+=1;    	
	    	$(this).find('td').eq(0).html(con);    	  	
	    });
	}
	
	
	////////////////////////////////////////
	////////FUNCIONES ADICIONALES///////////    

	//Funcion que limpia detalles de las tablas
    function limpiarDetalle(valor){

        switch(valor){

        case "paisPuertosDestino":
        	$("#id_puerto_pais_destino").val("");
        break;

        case "paisPuertosTransito":
        	$("#id_pais_transito").val("");
        	$("#id_puerto_transito").val("");
        	$("#id_medio_transporte_transito").val("");
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

	function cargarDatosDetalle(){

		var arrayPaisPuertosDestino = [];
		var arrayPaisPuertosTransito = [];
		var arrayExportadoresProductos = [];
    	
		datosPuertoPaisDestino = [];

		$('#detallePuertoPaisDestino tbody tr').each(function (rows) {				

			var idPaisDestino = $(this).find('td').find('input[name="iPaisDestino[]"]').val();			
			var nombrePaisDestino = $(this).find('td').find('input[name="nPaisDestino[]"]').val();			
			var idPuertoPaisDestino = $(this).find('td').find('input[name="iPuertoPaisDestino[]"]').val();
			var nombrePuertoPaisDestino = $(this).find('td').find('input[name="nPuertoPaisDestino[]"]').val();

			if ($('#detallePuertoPaisDestino tbody tr').length){		
				
				datosPuertoPaisDestino = {"idPuertoPaisDestino":idPuertoPaisDestino, "nombrePuertoPaisDestino":nombrePuertoPaisDestino};
				agregarElementos(arrayPaisPuertosDestino, datosPuertoPaisDestino, $("#array_pais_puertos_destino"));
				
			}

		});		

		datosPaisPuertoTransito = [];
	    
		$('#detallePaisPuertoTransito tbody tr').each(function (rows) {				

			var idPaisTransito = $(this).find('td').find('input[name="iPaisTransito[]"]').val();			
			var nombrePaisTransito = $(this).find('td').find('input[name="nPaisTransito[]"]').val();			
			var idMedioTransporteTransito = $(this).find('td').find('input[name="iMedioTransporteTransito[]"]').val();
			var nombreMedioTransporteTransito = $(this).find('td').find('input[name="nMedioTransporteTransito[]"]').val();			
			var idPuertoTransito = $(this).find('td').find('input[name="iPuertoTransito[]"]').val();
			var nombrePuertoTransito = $(this).find('td').find('input[name="nPuertoTransito[]"]').val();

			if ($('#detallePaisPuertoTransito tbody tr').length){		
				
				datosPaisPuertoTransito = {"idPaisTransito":idPaisTransito, "nombrePaisTransito":nombrePaisTransito,
				"idMedioTransporteTransito":idMedioTransporteTransito, "nombreMedioTransporteTransito":nombreMedioTransporteTransito,
				"idPuertoTransito":idPuertoTransito, "nombrePuertoTransito":nombrePuertoTransito};
				agregarElementos(arrayPaisPuertosTransito, datosPaisPuertoTransito, $("#array_pais_puertos_transito"));
				
			}

		});

		datosExportadoresProductos = [];
	    
		$('#detalleExportadoresProductos tbody tr').each(function (rows) {				
			
			var identificadorExportador = $(this).find('td').find('input[name="iIdentificadorExportador[]"]').val();			
			var razonSocialExportador = $(this).find('td').find('input[name="iRazonSocialExportador[]"]').val();			
			var direccionExportador = $(this).find('td').find('input[name="iDireccionExportador[]"]').val();
			var idTipoProducto = $(this).find('td').find('input[name="iTipoProducto[]"]').val();			
			var nombreTipoProducto = $(this).find('td').find('input[name="nTipoProducto[]"]').val();
			var idSubtipoProducto = $(this).find('td').find('input[name="iSubtipoProducto[]"]').val();
			var nombreSubtipoProducto = $(this).find('td').find('input[name="nSubtipoProducto[]"]').val();			
			var idProducto = $(this).find('td').find('input[name="iProducto[]"]').val();			
			var nombreProducto = $(this).find('td').find('input[name="nProducto[]"]').val();
			var partidaArancelariaProducto = $(this).find('td').find('input[name="iPartidaArancelariaProducto[]"]').val();
			var certificacionOrganica = $(this).find('td').find('input[name="iCertificacionOrganica[]"]').val();		
			var cantidadComercial = $(this).find('td').find('input[name="iCantidadComercial[]"]').val();
			var idUnidadCantidadComercial = $(this).find('td').find('input[name="iUnidadCantidadComercial[]"]').val();
			var nombreUnidadCantidadComercial = $(this).find('td').find('input[name="nUnidadCantidadComercial[]"]').val();
			var pesoBruto = $(this).find('td').find('input[name="iPesoBruto[]"]').val();
			var idUnidadPesoBruto = $(this).find('td').find('input[name="iUnidadPesoBruto[]"]').val();
			var nombreUnidadPesoBruto = $(this).find('td').find('input[name="nUnidadPesoBruto[]"]').val();			
			var pesoNeto = $(this).find('td').find('input[name="iPesoNeto[]"]').val();			
			var idUnidadPesoNeto = $(this).find('td').find('input[name="iUnidadPesoNeto[]"]').val();
			var nombreUnidadPesoNeto = $(this).find('td').find('input[name="nUnidadPesoNeto[]"]').val();	
			var codigoCentroAcopio = $(this).find('td').find('input[name="iCodigoCentroAcopio[]"]').val();			
			var fechaInspeccion = $(this).find('td').find('input[name="iFechaInspeccion[]"]').val();
			var horaInspeccion = $(this).find('td').find('input[name="iHoraInspeccion[]"]').val();
			var idTipoTratamiento = $(this).find('td').find('input[name="iTipoTratamiento[]"]').val();
			var nombreTipoTratamiento = $(this).find('td').find('input[name="nTipoTratamiento[]"]').val();			
			var idTratamiento = $(this).find('td').find('input[name="iTratamiento[]"]').val();
			var nombreTratamiento = $(this).find('td').find('input[name="nTratamiento[]"]').val();			
			var duracionTratamiento = $(this).find('td').find('input[name="iDuracionTratamiento[]"]').val();
			var idUnidadDuracion = $(this).find('td').find('input[name="iUnidadDuracion[]"]').val();
			var nombreUnidadDuracion = $(this).find('td').find('input[name="nUnidadDuracion[]"]').val();			
			var temperaturaTratamiento = $(this).find('td').find('input[name="iTemperaturaTratamiento[]"]').val();
			var idUnidadTemperatura = $(this).find('td').find('input[name="iUnidadTemperatura[]"]').val();
			var nombreUnidadTemperatura = $(this).find('td').find('input[name="nUnidadTemperatura[]"]').val();
			var fechaTratamiento = $(this).find('td').find('input[name="iFechaTratamiento[]"]').val();			
			var productoQuimico = $(this).find('td').find('input[name="iProductoQuimico[]"]').val();
			var concentracionTratamiento = $(this).find('td').find('input[name="iConcentracionTratamiento[]"]').val();
			var idUnidadConcentracion = $(this).find('td').find('input[name="iUnidadConcentracion[]"]').val();
			var nombreUnidadConcentracion = $(this).find('td').find('input[name="nUnidadConcentracion[]"]').val();
			var idArea = $(this).find('td').find('input[name="iArea[]"]').val();
			var nombreArea = $(this).find('td').find('input[name="nArea[]"]').val();
			var idProvinciaArea = $(this).find('td').find('input[name="iProvinciaArea[]"]').val();
			var nombreProvinciaArea = $(this).find('td').find('input[name="nProvinciaArea[]"]').val();
			
			if ($('#detalleExportadoresProductos tbody tr').length){		
				
				datosExportadoresProductos = {"identificadorExportador":identificadorExportador, "razonSocialExportador":razonSocialExportador,
						"direccionExportador":direccionExportador, "idTipoProducto":idTipoProducto, "nombreTipoProducto":nombreTipoProducto,
						"idSubtipoProducto":idSubtipoProducto, "nombreSubtipoProducto":nombreSubtipoProducto,
						"idProducto":idProducto, "nombreProducto":nombreProducto, "partidaArancelariaProducto":partidaArancelariaProducto,
						"certificacionOrganica":certificacionOrganica,
						"cantidadComercial":cantidadComercial, "idUnidadCantidadComercial":idUnidadCantidadComercial, "nombreUnidadCantidadComercial":nombreUnidadCantidadComercial,
						"pesoBruto":pesoBruto, "idUnidadPesoBruto":idUnidadPesoBruto, "nombreUnidadPesoBruto":nombreUnidadPesoBruto,
						"pesoNeto":pesoNeto, "idUnidadPesoNeto":idUnidadPesoNeto, "nombreUnidadPesoNeto":nombreUnidadPesoNeto,
						"codigoCentroAcopio":codigoCentroAcopio, "fechaInspeccion":fechaInspeccion, "horaInspeccion":horaInspeccion,
						"idTipoTratamiento":idTipoTratamiento, "nombreTipoTratamiento":nombreTipoTratamiento, 
						"idTratamiento":idTratamiento, "nombreTratamiento":nombreTratamiento,
						"duracionTratamiento":duracionTratamiento, "idUnidadDuracion":idUnidadDuracion, "nombreUnidadDuracion":nombreUnidadDuracion,
						"temperaturaTratamiento":temperaturaTratamiento, "idUnidadTemperatura":idUnidadTemperatura, "nombreUnidadTemperatura":nombreUnidadTemperatura,
						"fechaTratamiento":fechaTratamiento, "productoQuimico":productoQuimico, 
						"concentracionTratamiento":concentracionTratamiento, "idUnidadConcentracion":idUnidadConcentracion, "nombreUnidadConcentracion":nombreUnidadConcentracion,
						"idArea":idArea, "nombreArea":nombreArea, "idProvinciaArea":idProvinciaArea, "nombreProvinciaArea":nombreProvinciaArea
						};
				agregarElementos(arrayExportadoresProductos, datosExportadoresProductos, $("#array_exportadores_productos"));
				
			}

		});

	}

    //Funcion que agrega elementos a un array//
    //Recibe array, datos del array y el objeto donde se almacena//
    function agregarElementos(array, datos, objeto){
    	array.push(datos);
    	objeto.val(JSON.stringify(array));
	}

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
		$(".alertaCombo").removeClass("alertaCombo");

		$('#detalleExportadoresProductos tbody tr').each(function (rows) {				

			var vCantidadComercial = $(this).find('td').find('input[name="iCantidadComercial[]"]').val();			
			var vPesoNeto = $(this).find('td').find('input[name="iPesoNeto[]"]').val();			
			var vPesoBruto = $(this).find('td').find('input[name="iPesoBruto[]"]').val();

			if(vCantidadComercial <= 0){
				error = true;
				$(this).find('td').find('input[name="iCantidadComercial[]"]').addClass("alertaCombo");
			}

			if(vPesoNeto <= 0){
				error = true;
				$(this).find('td').find('input[name="iPesoNeto[]"]').addClass("alertaCombo");
			}

			if(vPesoBruto <= 0){
				error = true;
				$(this).find('td').find('input[name="iPesoBruto[]"]').addClass("alertaCombo");
			}
			
    		if($("#detallePuertoPaisDestino tbody tr").length == 0){
            	error = true;    	
            }
    
            if($("#detalleExportadoresProductos tbody tr").length == 0){
            	error = true;
            }
            
            if(!$.trim($("#nombre_consignatario").val())){
    			error = true;
    			$("#nombre_consignatario").addClass("alertaCombo");
    		}
    
    		if(!$.trim($("#direccion_consignatario").val())){
    			error = true;
    			$("#direccion_consignatario").addClass("alertaCombo");
    		}
                        
            if(!$.trim($("#motivo_reemplazo").val())){
    			error = true;
    			$("#motivo_reemplazo").addClass("alertaCombo");
			}

		});
		
		if (!error) {

			cargarDatosDetalle();
			
			var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);

				if (respuesta.estado == 'exito'){
		       		$("#estado").html(respuesta.mensaje);
		       		$("#_actualizar").click();
					$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
		        }
				
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
		
	});

</script>
