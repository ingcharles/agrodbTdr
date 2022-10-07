<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formularioProveedorExteriorModificarSolicitud'
	data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>ProveedoresExterior'
	data-opcion='ProveedorExterior/guardarModificarSolicitud'
	data-destino="detalleItem" data-accionEnExito="ACTUALIZAR"
	method="post">

	<input type="hidden" name="id_proveedor_exterior"
		id="id_proveedor_exterior"
		value="<?php echo $this->modeloProveedorExterior->getIdProveedorExterior(); ?>"
		readonly="readonly" />

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
	<input type="hidden" id="id" name="id" />
	<div>
		<button type="submit" class="guardar">Modificar solicitud</button>
	</div>

</form>

<script type="text/javascript">

    var estadoSolicitudSeleccionada = "<?php echo $this->estadoSolicitudSeleccionada ?>";
    var solicitudModificada = "<?php echo $this->solicitudModificada ?>";

    $(document).ready(function() {
    	$("#estado").html("").removeClass('alerta');    	
		construirValidador();
        distribuirLineas();

        if(!estadoSolicitudSeleccionada){
			$("#detalleItem").html('<div class="mensajeInicial">Seleccione una solicitud en estado aprobada y presione el botón Modificar.</div>');
		}  
        
        if(solicitudModificada){
			$("#detalleItem").html('<div class="mensajeInicial">La solicitud ya se encuentra en proceso de modificación.</div>');
		}      
    }); 


	$("#formularioProveedorExteriorModificarSolicitud").submit(function (event) {
		event.preventDefault();
		var error = false;

		var idProveedorExterior = $("#id_proveedor_exterior").val();		
		
		if (!error) {
            $.post("<?php echo URL ?>ProveedoresExterior/ProveedorExterior/guardarModificarSolicitud", {
            	idProveedorExterior: idProveedorExterior
            }, function(data) {
                if (data.estado == 'Fallo') {
                    mostrarMensaje(data.mensaje, "FALLO");
                }else{
                	abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),"#listadoItems",true);
                    $("#id").val(data.contenido);
                    $("#formularioProveedorExteriorModificarSolicitud").attr('data-opcion', 'ProveedorExterior/abrirSolicitudCreada');
                    abrir($("#formularioProveedorExteriorModificarSolicitud"), event, false);
                }
            }, 'json');
		} else {
			$("#estado").html("Por favor revise los campos obligatorios.").addClass("alerta");
		}
	}); 
	
</script>

