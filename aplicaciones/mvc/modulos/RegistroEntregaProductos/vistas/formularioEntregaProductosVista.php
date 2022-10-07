<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<!-- Despliegue de datos -->
<div id="datosEntrega">
		
	<fieldset>
		<legend>Datos del Beneficiario</legend>
		
		<div data-linea="1">
			<label>CI / RUC: </label><?php echo $this->modeloEntregaProductos->getIdentificadorBeneficiario(); ?>
		</div>
		
		<div data-linea="2">
			<label>Nombres: </label><?php echo $beneficiario['nombre']; ?>
			
		</div>
		
		<div data-linea="2">
			<label>Apellidos: </label><?php echo $beneficiario['apellido']; ?>
			
		</div>
		
		<div data-linea="3">
			<label>Dirección: </label><?php echo $beneficiario['direccion']; ?>
			
		</div>
		
		<div data-linea="4">
			<label>Teléfono: </label><?php echo $beneficiario['telefono']; ?>
			
		</div>
		
		<div data-linea="4">
			<label>Correo: </label><?php echo $beneficiario['correo']; ?>
			
		</div>	
		
		<div data-linea="5">
			<label id="l_certificado">Certificado: </label>
			<?php echo ($this->modeloEntregaProductos->getRutaArchivo()==''? '<span class="alerta">No ha generado ningún certificado</span>':'<a href="'.$this->modeloEntregaProductos->getRutaArchivo().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Clic aquí para ver el Certificado</a>')?>
		</div>	
	</fieldset>
	
	<fieldset>
		<legend>Productos y Lugar de Aplicación</legend>
		
		<div data-linea="6">
			<label for="id_producto">Producto: </label><?php echo $this->modeloEntregaProductos->getProducto(); ?>
		</div>
		
		<div data-linea="6">
			<label for="cantidad_entrega">Cantidad: </label><?php echo $this->modeloEntregaProductos->getCantidadEntrega(); ?>
		</div>				

		<div data-linea="7">
			<label for="id_provincia_uso">Provincia: </label><?php echo $this->modeloEntregaProductos->getProvinciaUso(); ?>
		</div>				

		<div data-linea="7">
			<label for="id_canton_uso">Cantón: </label><?php echo $this->modeloEntregaProductos->getCantonUso(); ?>
		</div>				

		<div data-linea="8">
			<label for="id_parroquia_uso">Parroquia: </label><?php echo $this->modeloEntregaProductos->getParroquiaUso(); ?>
		</div>				

		<div data-linea="9">
			<label for="lugar_uso">Lugar: </label><?php echo $this->modeloEntregaProductos->getLugarUso(); ?>
		</div>	
	</fieldset>
	
	<form id='formularioCertificado' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroEntregaProductos' data-opcion='EntregaProductos/agregarCertificado' data-destino="detalleItem" method="post">
		<input type="hidden" id="id_entrega" name="id_entrega" value="<?php echo $this->modeloEntregaProductos->getIdEntrega(); ?>"/>
		
    	<fieldset id="documentoCertificado">
    		<legend>Certificado firmado</legend>
    		
    		<div data-linea="38" class="nacional">
    			<label for="anexo">Certificado firmado: </label> <?php echo ($this->modeloEntregaProductos->getRutaCertificadoFirmado()=='0'? '<span class="alerta">No ha cargado ningún documento</span>':'<a href="'.$this->modeloEntregaProductos->getRutaCertificadoFirmado().'" target="_blank" class="archivo_cargado" id="archivo_cargado">Click aquí para ver el documento</a>')?>
    
    			<input type="file" id="anexo" class="archivo" accept="application/pdf" /> 
    			<input type="hidden" class="rutaArchivoAnexo" name="ruta_certificado_firmado" id="ruta_certificado_firmado" value="0" />
    				
        		<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
        		<button type="button" class="subirArchivoAnexo adjunto" data-rutaCarga="<?php echo ENT_PROD_CERT_FIRM_URL . $this->rutaFecha;?>">Subir archivo</button>
        	</div>
        </fieldset>
        
        <button type="submit" class="guardar" >Guardar</button>
    </form>
</div>


<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroEntregaProductos' data-opcion='EntregaProductos/agregarEntrega' data-destino="detalleItem" method="post">
	
	<fieldset>
		<legend>Datos del Beneficiario</legend>
		
		<div data-linea="1">
			<label id="l_identificador_beneficiario">CI / RUC: </label>
			<input type="text" id="identificador_beneficiario" name="identificador_beneficiario" required maxlength="13" data-er="^[0-9]+$"/>
		</div>
		
		<div data-linea="2">
			<label id="l_nombre_beneficiario">Nombres: </label>
			<input type="text" id="nombre_beneficiario" name="nombre_beneficiario" readonly="readonly" required maxlength="128" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>
		</div>
		
		<div data-linea="2">
			<label id="l_apellido_beneficiario">Apellidos: </label>
			<input type="text" id="apellido_beneficiario" name="apellido_beneficiario" readonly="readonly" required maxlength="128" data-er="^[A-Za-zñÑÁáÉéÍíÓóÚúÜü ]+$"/>
		</div>
		
		<div data-linea="3">
			<label id="l_direccion_beneficiario">Dirección: </label>
			<input type="text" id="direccion_beneficiario" name="direccion_beneficiario" readonly="readonly" required maxlength="1024" data-er="^[A-Za-z0-9ñÑÁáÉéÍíÓóÚúÜü -\/]+$"/>
		</div>
		
		<div data-linea="4">
			<label id="l_telefono_beneficiario">Teléfono celular: </label>
			<input type="text" id="telefono_beneficiario" name="telefono_beneficiario" readonly="readonly" maxlength="16" data-er="^\([0-9]{2}\) [0-9]{4}-[0-9]{4}" data-inputmask="'mask': '(99) 9999-9999'"/>
		</div>
		
		<div data-linea="4">
			<label id="l_correo_beneficiario">Correo: </label>
			<input type="text" id="correo_electronico_beneficiario" name="correo_electronico_beneficiario" readonly="readonly" maxlength="128" data-er="^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$"/>
		</div>		
	</fieldset>
	
	<fieldset id="TablaHistorico">
    	<legend>Histórico de Entregas</legend>    	
    	<div id="tablaSeguimientos" > </div>
	</fieldset>
	
	<fieldset id="documentoCertificado">
		<legend>Certificado firmado</legend>
	
		<div data-linea="35" class="equivalente">
			<label for="ruta">Certificado: </label>

			<input type="file" id="informe" class="archivo" accept="application/pdf" /> 
			<input type="hidden" class="rutaArchivo" name="ruta_certificado_firmado" id="ruta_certificado_firmado" value="0" />
				
    		<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
    		<button type="button" class="subirArchivo adjunto" data-rutaCarga="<?php echo ENT_PROD_CERT_FIRM_URL . $this->rutaFecha;?>">Subir archivo</button>
    	</div>
    	    	
	</fieldset>
	
	<fieldset>
		<legend>Productos y Lugar de Aplicación</legend>							

		<div data-linea="8">
			<label for="id_producto">Producto: </label>
			<select id="id_producto" name="id_producto" required>
				<option value="">Seleccionar....</option>
				<?php echo $this->comboProductosDistribucion($_SESSION['nombreProvincia'], $_SESSION['entidad']); ?>
			</select>
			
			<input type="hidden" id="producto" name="producto" />
		</div>	
		
		<div data-linea="8">
			<label for="cantidad">Cantidad disponible: </label> 			
			<input type="text" 	id="cantidad" name="cantidad" readonly="readonly"  />
		</div>						

		<div data-linea="10">
			<label for="cantidad_entrega">Cantidad: </label>
			<input type="number" id="cantidad_entrega" name="cantidad_entrega" step="1" value="<?php echo $this->modeloEntregaProductos->getCantidadEntrega(); ?>" required maxlength="4" />
		</div>				

		<div data-linea="11">
			<label for="id_provincia_uso">Provincia: </label>
			<select id="id_provincia_uso"  name="id_provincia_uso" required>
				<option value="">Seleccionar....</option>
				<?php echo $this->comboProvinciasEc(); ?>
			</select>
			<input type="hidden" id="provincia_uso" name="provincia_uso" />
		</div>				

		<div data-linea="13">
			<label for="id_canton_uso">Cantón: </label>
			<select id="id_canton_uso" name="id_canton_uso" required>
				<option value="">Seleccionar....</option>
			</select>
			
			<input type="hidden" id="canton_uso" name="canton_uso"  />
		</div>				

		<div data-linea="15">
			<label for="id_parroquia_uso">Parroquia: </label>
			<select id="id_parroquia_uso" name="id_parroquia_uso" required>
				<option value="">Seleccionar....</option>
			</select>
			
			<input type="hidden" id="parroquia_uso" name="parroquia_uso"  />
		</div>				

		<div data-linea="17">
			<label for="lugar_uso">Lugar: </label>
			<input type="text" id="lugar_uso" name="lugar_uso" value="<?php echo $this->modeloEntregaProductos->getLugarUso(); ?>" required maxlength="64" />
		</div>				

		<div data-linea="14">
    		<button type="submit" class="guardar">Agregar</button>
    	</div>
	</fieldset >		
</form >

<form id='certificado' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>RegistroEntregaProductos' data-opcion='EntregaProductos/generarCertificadoEntrega' data-destino="detalleItem" method="post">
	<input type="hidden" id="identificador_benef" name="identificador_benef" />
	<input type="hidden" id="certificado" name="certificado" />
	<input type="hidden" id="id" name="id" />

    <fieldset id="TablaFormulario">
    	<legend>Entregas</legend>
    	
    	<div data-linea="15">
    		<table id="tbItems" style="width:100%">
    			<thead>
    				<tr>
                        <th style="width: 50%;">Provincia</th>
                        <th style="width: 25%;">Producto</th>
    					<th style="width: 15%;">Cantidad</th>
                        <th style="width: 10%;"></th>
    				</tr>
    			</thead>
    			<tbody id="bodyTbl">
    			</tbody>
    		</table>
    	</div>		
    </fieldset>
    
    <div id="cargarMensajeTemporal"></div>
		
    <fieldset>
    	<legend>Uso del producto asignado</legend>
    		<div data-linea="18">
    		<label for="tipo_uso">Tipo de Uso: </label>
    		<select id="tipo_uso" name="tipo_uso" required>
    			<option value="">Seleccionar....</option>
    			<?php echo $this->comboTiposUsoEntrega(); ?>
    		</select>
    	</div>
    </fieldset>

	<div data-linea="20">
		<button type="submit" class="guardar">Guardar</button>
	</div>
</form>

<script type ="text/javascript">
var bandera = <?php echo json_encode($this->formulario); ?>;
var combo = "<option>Seleccionar....</option>";

	$(document).ready(function() {
		$("#estado").html("");

		if(bandera == 'Nuevo'){
			$("#formulario").show();
			$("#certificado").show();
			
			$("#datosEntrega").hide();
		}else{
			$("#formulario").hide();
			$("#certificado").hide();

			$("#datosEntrega").show();
		}
		
		ocultarBeneficiario();
			
		construirValidador();
		distribuirLineas();
	 });

	$("#identificador_beneficiario").change(function () {
		if ($("#identificador_beneficiario").val() !== ""){
			$("#identificador_benef").val($("#identificador_beneficiario").val());
			fn_cargarInformacionBeneficiario();
        }else{
        	limpiarBeneficiario();
        	ocultarBeneficiario();
    		$("#tablaSeguimientos").html("");
    		$("#identificador_benef").val("");
        }
    });

	$("#id_producto").change(function () {
        if ($("#id_producto option:selected").val() !== "") {
            $("#cantidad").val($("#id_producto option:selected").attr('data-cantidad'));
            $("#producto").val($("#id_producto option:selected").text());
        }else{
        	$("#cantidad").val("");
        	$("#producto").val("");
        }
    });

	$("#id_provincia_uso").change(function () {
		$("#id_canton_uso").html(combo);
		$("#canton_uso").val("");
    	$("#id_parroquia_uso").html(combo);
    	$("#parroquia_uso").val("");
    	
    	if ($("#id_provincia_uso option:selected").val() !== "") {
    		fn_cargarCantones();
            $("#provincia_uso").val($("#id_provincia_uso option:selected").text());
        }else{
        	$("#provincia_uso").val("");
        }
    });

    $("#id_canton_uso").change(function () {
    	$("#id_parroquia_uso").html(combo);
    	$("#parroquia_uso").val("");
    	
    	if ($("#id_canton_uso option:selected").val() !== "") {
    		fn_cargarParroquias();
            $("#canton_uso").val($("#id_canton_uso option:selected").text());
        }else{
        	$("#canton_uso").val("");
        }
    }); 

	$("#id_parroquia_uso").change(function () {
		if ($("#id_parroquia_uso option:selected").val() !== "") {
    		$("#parroquia_uso").val($("#id_parroquia_uso option:selected").text());
        }else{
        	$("#parroquia_uso").val("");
        }
    });

	$("#formulario").submit(function (event) {
		event.preventDefault();
		var error = false;

		if(!$.trim($("#identificador_beneficiario").val())  || !esCampoValido("#identificador_beneficiario") ){
			error = true;
			$("#identificador_beneficiario").addClass("alertaCombo");
		}

		if(!$.trim($("#nombre_beneficiario").val())  || !esCampoValido("#nombre_beneficiario") || $("#nombre_beneficiario").val().length > $("#nombre_beneficiario").attr("maxlength")){
			error = true;
			$("#nombre_beneficiario").addClass("alertaCombo");
		}

		if(!$.trim($("#apellido_beneficiario").val())  || !esCampoValido("#apellido_beneficiario") || $("#apellido_beneficiario").val().length > $("#apellido_beneficiario").attr("maxlength")){
			error = true;
			$("#apellido_beneficiario").addClass("alertaCombo");
		}

		if(!$.trim($("#direccion_beneficiario").val())  || !esCampoValido("#direccion_beneficiario") || $("#direccion_beneficiario").val().length > $("#direccion_beneficiario").attr("maxlength")){
			error = true;
			$("#direccion_beneficiario").addClass("alertaCombo");
		}

		if(!$.trim($("#telefono_beneficiario").val())  || !esCampoValido("#telefono_beneficiario") ){
			error = true;
			$("#telefono_beneficiario").addClass("alertaCombo");
		}

		if(!$.trim($("#correo_electronico_beneficiario").val())  || !esCampoValido("#correo_electronico_beneficiario") || $("#correo_electronico_beneficiario").val().length > $("#correo_electronico_beneficiario").attr("maxlength")){
			error = true;
			$("#correo_electronico_beneficiario").addClass("alertaCombo");
		}
		
		if(!$.trim($("#id_producto").val())){
			error = true;
			$("#id_producto").addClass("alertaCombo");
		}

		//validar que la cantidad disponible sea suficiente para crear un nuevo registro 
		if(!$.trim($("#cantidad_entrega").val()) || ($("#cantidad_entrega").val() <= 0) || (parseInt($("#cantidad_entrega").val()) > parseInt($("#cantidad").val()))){//  
			error = true;
			$("#cantidad_entrega").addClass("alertaCombo");
		}
		
		if(!$.trim($("#id_provincia_uso").val())){
			error = true;
			$("#id_provincia_uso").addClass("alertaCombo");
		}	

		if(!$.trim($("#id_canton_uso").val())){
			error = true;
			$("#id_canton_uso").addClass("alertaCombo");
		}

		if(!$.trim($("#id_parroquia_uso").val())){
			error = true;
			$("#id_parroquia_uso").addClass("alertaCombo");
		}

		if(!$.trim($("#lugar_uso").val())){
			error = true;
			$("#lugar_uso").addClass("alertaCombo");
		}

		if (!error) {
			var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);	
					
		    if (respuesta.estado == 'exito'){
		    	$("#identificador_beneficiario").attr('readonly', 'readonly');
		    	$("#bodyTbl").append(respuesta.contenido);
		    	$("#estado").html(respuesta.mensaje);
		    	limpiarDetalle();
		    	fn_limpiar();
		    	bloquearBeneficiario();
		    }else{
		    	$("#estado").html(respuesta.mensaje).addClass("alerta");
		    }			
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//Quitar registro
	function quitarProductos(fila){
		$("#tbItems tbody tr").eq($(fila).index()).remove();	  
		fn_eliminarEntrega(fila);
	}

	$("#certificado").submit(function (event) {
		event.preventDefault();
		var error = false;

		if(!$.trim($("#identificador_beneficiario").val())){
			error = true;
			$("#identificador_beneficiario").addClass("alertaCombo");
		}else{
			$("#identificador_benef").val($("#identificador_beneficiario").val())
		}
		
		if(!$.trim($("#tipo_uso").val())){
			error = true;
			$("#tipo_uso").addClass("alertaCombo");
		}

		//Información en tabla de detalle
		if (($("#iEntrega").length > 0)){
			error = false;
		}else{
			error = true;
			$("#estado").html("Por favor ingrese por lo menos un producto a entregar.").addClass("alerta");
		}

		if (!error) {
			$("#cargarMensajeTemporal").html("<div id='cargando' style='position :fixed'>Cargando...</div>").fadeIn();

			setTimeout(function(){ 
				var respuesta = JSON.parse(ejecutarJson($("#certificado")).responseText);			
		       	if (respuesta.estado == 'exito'){
		       		$("#id").val(respuesta.contenido);
		       		$("#certificado").attr('data-opcion', 'EntregaProductos/mostrarReporte');
					abrir($("#certificado"),event,false);
		        }

			}, 1000);
			
			
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//---------------FUNCIONES-----------------------
	
	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}
	
	function fn_limpiar() {
		$(".alertaCombo").removeClass("alertaCombo");
		$('#estado').html('');
	}
	
	//Para eliminar el registro de Entrega seleccionado
	function fn_eliminarEntrega(fila) {

		$.post("<?php echo URL ?>RegistroEntregaProductos/EntregaProductos/eliminarEntrega",
	    		{
	    			idEntrega : fila
	    		},
	    	    function (data) {
	    			limpiarDetalle();
	    			mostrarMensaje("Registro eliminado con éxito", "EXITO");
	        	}
	    );        
    } 
    
	//Lista de cantones por provincia
    function fn_cargarCantones() {
        var idProvincia = $("#id_provincia_uso option:selected").val();
        
        if (idProvincia !== "") {
        	$.post("<?php echo URL ?>RegistroEntregaProductos/EntregaProductos/comboCantones/" + idProvincia, function (data) {
                $("#id_canton_uso").removeAttr("disabled");
                $("#id_canton_uso").html(data);               
            });
        }
    }

    //Lista de parroquias por cantón
	function fn_cargarParroquias() {
        var idCanton = $("#id_canton_uso option:selected").val();
        
        if (idCanton !== "") {
        	$.post("<?php echo URL ?>RegistroEntregaProductos/EntregaProductos/comboParroquias/" + idCanton, function (data) {
                $("#id_parroquia_uso").removeAttr("disabled");
                $("#id_parroquia_uso").html(data);               
            });
        }
    }

	//Para cargar los datos del beneficiario
    function fn_cargarInformacionBeneficiario() {
        var identificador = $("#identificador_beneficiario").val();
        
        if (identificador !== "") {
        	mostrarMensaje("","EXITO");
        	$.post("<?php echo URL ?>RegistroEntregaProductos/Beneficiarios/obtenerNombreBeneficiario/" + identificador, function (data) {
				if(data.validacion == "Fallo"){
	        		mostrarMensaje(data.nombre,"FALLO");
	        		mostrarBeneficiario();
	        		limpiarBeneficiario();
	        		habilitarBeneficiario();
	        		$("#tablaSeguimientos").html("");
				}else{
					$("#nombre_beneficiario").val(data.nombre);
					$("#apellido_beneficiario").val(data.apellido);
					$("#direccion_beneficiario").val(data.direccion);
					$("#telefono_beneficiario").val(data.telefono);
					$("#correo_electronico_beneficiario").val(data.correo);
					mostrarBeneficiario();
					bloquearBeneficiario();
					fn_mostrarEntregas();
				}
            }, 'json');
        }
    } 

  //Para cargar las entregas realizadas al beneficiario
    function fn_mostrarEntregas() {
        var identificador = $("#identificador_beneficiario").val();
        
    	$.post("<?php echo URL ?>RegistroEntregaProductos/EntregaProductos/construirEntregas/" + identificador, function (data) {
            $("#tablaSeguimientos").html(data);
        });
    }

  //Lista de productos del catálogo de distribuciones
    function fn_cargarProductosDistribucion() {
    	$.post("<?php echo URL ?>RegistroEntregaProductos/EntregaProductos/comboProductosDistribucionActualizado/", function (data) {
            $("#id_producto").html(data);               
        });
    }

	function limpiarBeneficiario(){
		$("#nombre_beneficiario").val("");
		$("#apellido_beneficiario").val("");
		$("#direccion_beneficiario").val("");
		$("#telefono_beneficiario").val("");
    	$("#correo_electronico_beneficiario").val("");
	}

	function ocultarBeneficiario(){
		$("#l_nombre_beneficiario").hide();
		$("#l_apellido_beneficiario").hide();
		$("#l_direccion_beneficiario").hide();
		$("#l_telefono_beneficiario").hide();
    	$("#l_correo_beneficiario").hide();

    	$("#nombre_beneficiario").hide();
		$("#apellido_beneficiario").hide();
		$("#direccion_beneficiario").hide();
		$("#telefono_beneficiario").hide();
    	$("#correo_electronico_beneficiario").hide();

    	$("#TablaHistorico").hide();
	}

	function mostrarBeneficiario(){
		$("#l_nombre_beneficiario").show();
		$("#l_apellido_beneficiario").show();
		$("#l_direccion_beneficiario").show();
		$("#l_telefono_beneficiario").show();
    	$("#l_correo_beneficiario").show();

    	$("#nombre_beneficiario").show();
		$("#apellido_beneficiario").show();
		$("#direccion_beneficiario").show();
		$("#telefono_beneficiario").show();
    	$("#correo_electronico_beneficiario").show();

    	$("#TablaHistorico").show();
	}

	function habilitarBeneficiario(){
		$("#nombre_beneficiario").removeAttr('readonly');
		$("#apellido_beneficiario").removeAttr('readonly');
		$("#direccion_beneficiario").removeAttr('readonly');
		$("#telefono_beneficiario").removeAttr('readonly');
    	$("#correo_electronico_beneficiario").removeAttr('readonly');

    	$("#TablaHistorico").hide();
	}

	function bloquearBeneficiario(){
		$("#nombre_beneficiario").attr('readonly', 'readonly');
		$("#apellido_beneficiario").attr('readonly', 'readonly');
		$("#direccion_beneficiario").attr('readonly', 'readonly');
		$("#telefono_beneficiario").attr('readonly', 'readonly');
    	$("#correo_electronico_beneficiario").attr('readonly', 'readonly');
	}

	function limpiarDetalle(){
		//Volver a cargar el combo con los valores nuevos!
		fn_cargarProductosDistribucion();
		$("#id_producto").val("");
		$("#producto").val("");
		$("#cantidad").val("");
    	$("#cantidad_entrega").val("");
    	$("#id_provincia_uso").val("");
    	$("#provincia_uso").val("");
    	$("#id_canton_uso").val("");
    	$("#canton_uso").val("");
    	$("#id_parroquia_uso").val("");
    	$("#parroquia_uso").val("");
    	$("#lugar_uso").val("");
	}

	//Función para carga de archivo de certificado firmado
    $('button.subirArchivo').click(function (event) {
    	var nombre_archivo = "<?php echo 'certificado_' . time(); ?>";
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
			
            $("#certificado").val(<?php echo json_encode(ENT_PROD_CERT_FIRM_URL . $this->rutaFecha);?>+'/'+nombre_archivo+'.'+extension[extension.length - 1]);

        } else {
            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
            archivo.val("0");
        }
    });

  //Función para carga de archivo de certificado firmado
    $('button.subirArchivoAnexo').click(function (event) {
    	var nombre_archivo = "<?php echo 'certificado_' . time(); ?>";
        var boton = $(this);
        var archivo = boton.parent().find(".archivo");
        var rutaArchivo = boton.parent().find(".rutaArchivoAnexo");
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

    $("#formularioCertificado").submit(function (event) {
		event.preventDefault();
		var error = false;

		if(!$.trim($("#ruta_certificado_firmado").val()) && ($("#ruta_certificado_firmado").val() != '0')){
			error = true;
			$("#ruta_certificado_firmado").addClass("alertaCombo");
		}
		
		if (!error) {
	        var respuesta = JSON.parse(ejecutarJson($(this)).responseText);

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