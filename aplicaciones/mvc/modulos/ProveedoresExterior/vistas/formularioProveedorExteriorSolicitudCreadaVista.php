<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<div class="pestania">


	<?php if(isset($this->observacionRevisionDocumental)){echo $this->observacionRevisionDocumental;} ?>	

    <form id='formularioProveedorExterior'
		data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
		method="post">

		<input type="hidden" id="id_proveedor_exterior_cabecera"
			name="id_proveedor_exterior_cabecera"
			value="<?php echo $this->modeloProveedorExterior->getIdProveedorExterior(); ?>"
			readonly="readonly" />

		<fieldset>
			<legend>Información del solicitante <?php if($this->modeloProveedorExterior->getCodigoCreacionSolicitud() != null){echo " - Solicitud N°" . $this->modeloProveedorExterior->getCodigoCreacionSolicitud();} ?></legend>
    		<?php echo $this->informacionSocilitante; ?>	
    	</fieldset>

		<fieldset>
			<legend>Información del proveedor en el exterior</legend>

			<div data-linea="1">
				<label for="nombre_fabricante">Nombre del fabricante: </label> <input
					type="text" id="nombre_fabricante" name="nombre_fabricante"
					value="<?php echo $this->modeloProveedorExterior->getNombreFabricante(); ?>"
					placeholder="Ejem: Carlos Alberto Pérez Castro" maxlength="128"
					class="validacion" />
			</div>

			<div data-linea="2">
				<label for="id_pais_fabricante">País del fabricante: </label> <select
					id="id_pais_fabricante" name="id_pais_fabricante"
					class="validacion">
					<option value="">Seleccionar....</option>
    				<?php
								echo $this->comboPaises($this->modeloProveedorExterior->getIdPaisFabricante());
								?>
                </select>
			</div>

			<div data-linea="3">
				<label for="direccion_fabricante">Dirección del fabricante: </label>
				<input type="text" id="direccion_fabricante"
					name="direccion_fabricante"
					value="<?php echo $this->modeloProveedorExterior->getDireccionFabricante(); ?>"
					placeholder="Ejem: Avenida de las Américas" maxlength="128"
					class="validacion" />
			</div>

			<div data-linea="4">
				<label for="servicio_oficial">Servicios oficiales que regulan los
					productos que fabrica la planta: </label>
			</div>
			<div data-linea="5">
				<textarea name="servicio_oficial" id="servicio_oficial"
					placeholder="Registre aquí los servicios oficiales" maxlength="128"
					class="validacion"><?php echo $this->modeloProveedorExterior->getServicioOficial(); ?></textarea>
			</div>
			<div data-linea="6" style="text-align: center;">
				<button type="submit" class="guardar">Guardar</button>
			</div>
		</fieldset>

	</form>

	<form id='formularioProductosProveedor'
		data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
		data-opcion='ProductosProveedor/guardar' data-destino="detalleItem"
		data-accionEnExito="ACTUALIZAR" method="post">

		<fieldset>
			<legend>Subtipos de productos veterinarios que desea exportar</legend>

			<div data-linea="3">
				<label for="id_subtipo_producto">Subtipo de producto: </label> <select
					id="id_subtipo_producto" name="id_subtipo_producto" class="validacion">
					<option value="">Seleccionar....</option>
        				<?php echo $this->comboSubtipoProductos; ?>
                    </select>
			</div>

			<div data-linea="6">
				<button type="submit" class="mas" id="agregarProductosProveedor">Agregar</button>
			</div>

			<table id="detalleProductosProveedor" style="width: 100%">
				<thead>
					<tr>
						<th>#</th>
						<th>Subtipos de productos agregados</th>
						<th>Opción</th>
					</tr>
				</thead>
				<tbody>
        				<?php echo $this->productosProveedorExterior; ?>
        			</tbody>
			</table>

		</fieldset>
	</form>
</div>

<div class="pestania">

	<form id='formularioDocumentosAdjuntos'
		data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'>

		<fieldset>
			<legend>Documentos anexos</legend>		
    		<?php echo $this->documentosAnexos; ?>		
    	</fieldset>

		<fieldset>
			<legend>Información de la planta</legend>		
    		<?php echo $this->documentosEmpresa; ?>		
    	</fieldset>

	</form>

</div>

<div class="pestania">

	<form id='formularioFinalizarSolicitud'
		data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
		data-opcion='ProveedorExterior/finalizarSolicitud'
		data-destino="detalleItem" data-accionEnExito="ACTUALIZAR"
		method="post">
		<input type="hidden" id="id_proveedor_exterior"
			name="id_proveedor_exterior"
			value="<?php echo $this->modeloProveedorExterior->getIdProveedorExterior(); ?>"
			readonly="readonly" /> <input type="hidden"
			id="array_documentos_anexos" name="array_documentos_anexos" value=""
			readonly="readonly" /> <input type="hidden"
			id="array_documentos_anexos_nombre"
			name="array_documentos_anexos_nombre" value="" readonly="readonly" />

		<fieldset>
			<legend>Finalizar solicitud</legend>
			<div data-linea="1" style="text-align: center;">
				<label>TÉRMINOS Y CONDICIONES GENERALES DE USO</label>
			</div>
			<div data-linea="2" style="text-align: justify;">
				<p>La utilización del sistema le atribuye la condición de Usuario e implica la aceptación plena de todas y cada una de las disposiciones, reglamentos y/o normativas emitidas por la AUTORIDAD NACIONAL COMPETENTE (ANC) en el momento mismo en que el Usuario acceda al sistema. En consecuencia, el Usuario debe leer atentamente el presente Aviso en cada una de las ocasiones en que se proponga utilizar el sistema, ya que los módulos automatizados pueden sufrir modificaciones.</p>
				<p>1.- El usuario garantiza la autenticidad y veracidad de todos aquellos datos que ingrese al completar el/los formulario/s de registro y/o modificación de registro.
                    <br>2.- El usuario se compromete y se responsabiliza de que toda la información ingresada sea actualizada y verídica.
                    <br>3.- Se prohíbe el uso de cualquier tipo de programa que pretenda extraer información de sistema de forma automatizada y no autorizada.
                    <br>4.- El Usuario no podrá utilizar la información contenida en el sistema con propósitos diferentes a los autorizados o permitidos por la ANC.
                    <br>5.- El usuario dispone de 5 días hábiles para finalizar y enviar una solicitud a través del sistema. Caso contrario la solicitud será cancelada.
                    <br>6.- El usuario dispone de 60 días hábiles para subsanar las observaciones emitidas por la Agencia. Caso contrario la solicitud será cancelada.
                    <br>7.- Al usar el presente módulo, usted acepta y está de acuerdo con estos términos y condiciones en lo que se refiere al uso del mismo, al manejo, almacenamiento y uso de datos previamente ingresados.
				</p>
			</div>			
			<div data-linea="3" style="text-align: center;">
				<label><input type="checkbox" id="aceptar_condiciones"
					name="aceptar_condiciones" value=""> Acepto las condiciones</label><br>
			</div>
		</fieldset>
		<div>
			<button type="submit" id="enviarSolicitud" name="enviarSolicitud"
				class="guardar" disabled="disabled">Finalizar</button>
		</div>

	</form>

</div>



<script type="text/javascript">
    $(document).ready(function() {
    	$("#estado").html("").removeClass('alerta');
    	construirAnimacion($(".pestania"));
		construirValidador();
        distribuirLineas();
    });

	$('button.subirArchivo').click(function (event) {
		var boton = $(this);
		var tipo_archivo = boton.parent().find(".rutaArchivo").attr("id");
		var nombre_archivo = tipo_archivo+"<?php echo '_imf_' . (md5(time())); ?>";
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
    
	//Funcion que elimina una fila del detalle productos del proveedor
    function fn_eliminarDetalleProductosProveedor(idProductroProveedor) {
    	$("#estado").html("").removeClass('alerta');
        $.post("<?php echo URL ?>ProveedoresExterior/ProductosProveedor/borrar",
        {                
            elementos: idProductroProveedor
        },
        function (data) {
        	$("#fila" + idProductroProveedor).remove();
        	enumerarProductosProveedor();
        });
    }
    
  	//Funcion que enumera la tabla de detalle de productos del proveedor
    function enumerarProductosProveedor(){			    	    
	    con = 0;   
	    $("#detalleProductosProveedor tbody tr").each(function(row){        
	    	con += 1;    	
	    	$(this).find('td').eq(0).html(con);    	  	
	    });
	}

  	//Funcion para actualizar los datos de la informacion del solicitante
	$("#formularioProveedorExterior").submit(function (event) {
		event.preventDefault();
		var error = false;

		$('#formularioProveedorExterior .validacion').each(function(i, obj) {

 			if(!$.trim($(this).val())){
 				error = true;
 				$(this).addClass("alertaCombo");
 			}

 		});
		
		if (!error) {	

			var idProveedorExterior = $("#id_proveedor_exterior_cabecera").val();
			var nombreFabricante = $("#nombre_fabricante").val();
			var idPaisFabricante = $("#id_pais_fabricante").val();
			var nombrePaisFabricante = $("#id_pais_fabricante option:selected").text();
			var direccionFabricante = $("#direccion_fabricante").val();
			var servicioOficial = $("#servicio_oficial").val();

			//var idProveedorExterior = $("#id_proveedor_exterior").val();			         
            
            $.post("<?php echo URL ?>ProveedoresExterior/ProveedorExterior/actualizarInformacionProveedor",
            {
            	idProveedorExterior: idProveedorExterior,
                nombreFabricante: nombreFabricante,
                idPaisFabricante: idPaisFabricante,
                nombrePaisFabricante: nombrePaisFabricante,
                direccionFabricante: direccionFabricante,
                servicioOficial: servicioOficial
            	
            }, function (data) {
            	if (data.validacion === 'Fallo') {		        		
	        		mostrarMensaje(data.resultado,"FALLO");    	        		
                }else{
                	mostrarMensaje(data.resultado,"EXITO");                	
                	setTimeout(function(){
						$("#estado").html("").removeClass('alerta');
   	                },1500);
	            }
            }, 'json');
			
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//Funcion para agregar fila de tipos de producto
    $("#formularioProductosProveedor").submit(function (event) {
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		$('#formularioProductosProveedor .validacion').each(function(i, obj) {

 			if(!$.trim($(this).val())){
 				error = true;
 				$(this).addClass("alertaCombo");
 			}

 		});		
				
		if (!error) {

			$("#estado").html("").removeClass('alerta');
	        var filas = 0;
	        filas = $("#detalleProductosProveedor").find('tbody tr').length;
	        
			$.post("<?php echo URL ?>ProveedoresExterior/ProductosProveedor/guardar",
		    	{

				  	idProveedorExterior : $("#id_proveedor_exterior_cabecera").val(), 
				  	idSubtipoProducto : $("#id_subtipo_producto").val(),
				  	nombreSubtipoProducto : $("#id_subtipo_producto option:selected").text(),
				    filas :filas
				 
		        },
		      	function (data) {
		        	if (data.validacion == 'Fallo') {		        		
		        		mostrarMensaje(data.resultado,"FALLO");
		        		setTimeout(function(){
							$("#estado").html("").removeClass('alerta');
	   	                },1500); 	        		
	                }else{
	                	$("#detalleProductosProveedor tbody").append(data.filaProductosProveedor);
	                	limpiarDetalle("productosProveedor");
	                	mostrarMensaje(data.resultado,"EXITO");
		            }
		        }, 'json');
	        
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	});

	//Funcion que limpia detalles de las tablas
    function limpiarDetalle(valor){
        switch(valor){

            case "productosProveedor":
            	$("#id_subtipo_producto").val("");
            break;

        }        
	}

    $("#aceptar_condiciones").click(function (event) {
        if($("#aceptar_condiciones").is(':checked')){
        	$("#enviarSolicitud").removeAttr("disabled");
    	}else{
    		$("#enviarSolicitud").attr("disabled", "disabled");
        }
    });

    $("#formularioFinalizarSolicitud").submit(function (event) {
    	event.preventDefault();
		var error = false;
		$(".alertaCombo").removeClass("alertaCombo");
		var mensajeDetalle = "";
		
		$('#formularioDocumentosAdjuntos .validacion').each(function(i, obj) {

 			if(!$.trim($(this).val())){
 				error = true;
 				$(this).addClass("alertaCombo");
 			}

 		});	

		if($("#detalleProductosProveedor tbody tr").length == 0){
			mensajeDetalle += " Debe seleccionar un tipo de producto.";
			error = true;
			$("#id_subtipo_producto").addClass("alertaCombo");
		}
		
		if(!$("#aceptar_condiciones").is(':checked')){
 			error = true;
 			$("#aceptar_condiciones").addClass("alertaCombo");
 		}      

		if (!error) {
    		cargarDatosDetalle();
    		var respuesta = JSON.parse(ejecutarJson($("#formularioFinalizarSolicitud")).responseText);
    		if (respuesta.estado == 'exito'){
	       		$("#estado").html(respuesta.mensaje);
	        }			
		}else{
			$("#estado").html("Por favor revise los campos obligatorios." + mensajeDetalle).addClass("alerta");
		}

    });    

		
	//Funcion para cargar datos a un array
	function cargarDatosDetalle(){
        datosDocumentosAnexos = [];
        datosDocumentosAnexosNombre = [];
        $("input[name='ruta_archivo[]']").each(function(indice, elemento) {
        	agregarElementos(datosDocumentosAnexos, $(elemento).val(), $("#array_documentos_anexos"));
    	});

        $("input[name='tipo_archivo[]']").each(function(indice, elemento) {
        	agregarElementos(datosDocumentosAnexosNombre, $(elemento).val(), $("#array_documentos_anexos_nombre"));
    	});
    	
	}

	//Funcion que agrega elementos a un array//
    //Recibe array, datos del array y el objeto donde se almacena//
    function agregarElementos(array, datos, objeto){
    	array.push(datos);
    	objeto.val(JSON.stringify(array));
	}

	
	
</script>

