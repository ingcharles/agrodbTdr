<header>
	<h1><?php echo $this->accion; ?></h1>
</header>


<fieldset>
	<legend>Información del solicitante - Solicitud N° <?php echo $this->modeloProveedorExterior->getCodigoCreacionSolicitud(); ?></legend>
	<?php echo $this->informacionSocilitante; ?>	
</fieldset>

<fieldset>
	<legend>Información del proveedor en el exterior</legend>

	<div data-linea="1">
		<label for="nombre_fabricante">Nombre del fabricante: </label>
		<?php echo $this->modeloProveedorExterior->getNombreFabricante(); ?>
    </div>

	<div data-linea="2">
		<label for="id_pais_fabricante">País del fabricante: </label>
    	<?php echo $this->modeloProveedorExterior->getNombrePaisFabricante(); ?>
    </div>

	<div data-linea="3">
		<label for="direccion_fabricante">Dirección del fabricante: </label>
    	<?php echo $this->modeloProveedorExterior->getDireccionFabricante(); ?>
    </div>

	<div data-linea="4">
		<label for="servicio_oficial">Servicios oficiales que regulan los
			productos que fabrica la planta: </label>
        <?php echo $this->modeloProveedorExterior->getServicioOficial(); ?></div>
</fieldset>

<fieldset>
	<legend>Subtipos de productos veterinarios que desea exportar</legend>

	<table id="detalleProductosProveedor" style="width: 100%">
		<thead>
			<tr>
				<th>#</th>
				<th>Subtipos de productos agregados</th>
			</tr>
		</thead>
		<tbody>
				<?php echo $this->productosProveedorExterior; ?>
			</tbody>
	</table>
</fieldset>

<fieldset>
	<legend>Documentos anexos</legend>		
	<?php echo $this->documentos; ?>		
</fieldset>

<form id='formularioRevisionDocumental'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
	data-opcion='AdministracionRevisionFormularios/guardarRevisionDocumental'
	data-destino="detalleItem" data-accionEnExito="ACTUALIZAR"
	method="post">
	<input type="hidden" id="id_proveedor_exterior"
		name="id_proveedor_exterior"
		value="<?php echo $this->modeloProveedorExterior->getIdProveedorExterior(); ?>">
	<fieldset>
		<legend>Informe del análisis:</legend>
		<div id="subirInforme">
			<label>Cargar informe:</label> <input type="hidden" id="ruta_adjunto"
				class="ruta_adjunto" name="ruta_adjunto" value="" /> <input
				type="file" class="archivo"
				accept="application/msword | application/pdf | image/*" />
			<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?></div>
			<button type="button" id="subirArchivo"
				data-rutaCarga="<?php echo PROV_EXTE_DOC_INSP.$this->rutaFecha ?>">Subir
				archivo</button>
		</div>
	</fieldset>
	<?php if(isset($this->resultadoRevisionDocumental)){echo $this->resultadoRevisionDocumental;} ?>
</form>

<script type="text/javascript">

    $(document).ready(function() {
    	$("#estado").html("").removeClass('alerta');    	
		construirValidador();
        distribuirLineas();
    });

    $("#formularioRevisionDocumental").submit(function (event) {
		event.preventDefault();
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		$('#formularioRevisionDocumental .validacion').each(function(i, obj) {

 			if(!$.trim($(this).val())){
 				error = true;
 				$(this).addClass("alertaCombo");
 			}

 		});

 		if($("#resultadoDocumento").val() == "Subsanacion" || $("#resultadoDocumento").val() == "Inhabilitado"){
 	 		if($("#ruta_adjunto").val() == ""){
				$(".archivo").addClass("alertaCombo");
				error = true;
 			}
 	 	}		

		if (!error) {

			$("#guardarResultado").attr('disabled','disabled');
			
			var respuesta = JSON.parse(ejecutarJson($("#formularioRevisionDocumental")).responseText);

				if (respuesta.estado == 'exito'){
		       		$("#estado").html(respuesta.mensaje);
		       		$("#_actualizar").click();
					$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
		        }
				
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}

    });

    $("#subirArchivo").click(function (event) {
	  	  
	  	var boton = $(this);
		var nombre_archivo = "<?php echo 'proveedorExterior_' . (md5(time())); ?>";
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
	            , new carga(estado, archivo, boton)
	            
	        );
	    } else {
	        estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
	        archivo.val("0");        
	        }
	});	
	
</script>

