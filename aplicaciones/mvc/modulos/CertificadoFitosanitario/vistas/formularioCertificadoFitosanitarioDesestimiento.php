<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<form id='formulario' data-rutaAplicacion='<?php echo URL_MVC_FOLDER; ?>CertificadoFitosanitario' data-opcion='certificadoFitosanitario/guardarDesestimiento' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR" method="post">
	
	<input type="hidden" id="id_certificado_fitosanitario" name="id_certificado_fitosanitario" value="<?php echo $this->modeloCertificadoFitosanitario->getIdCertificadoFitosanitario(); ?>" readonly="readonly" />
   	
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
    	<?php echo $this->paisPuertosDestino; ?>    		
    </fieldset>
    
    <fieldset>
        <legend>Países, Puertos de Tránsito y Medios de Transporte</legend>        
        <?php echo $this->paisesPuertosTransito; ?>    	
    </fieldset>
    
    <fieldset>
		<legend>Exportadores y Productos</legend>			   
         <?php echo $this->exportadoresProductos; ?>    	
	</fieldset>
	
	<fieldset>
		<legend>Documentos Adjuntos</legend>
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
	
	<fieldset>
	<legend>Desestimiento</legend>
        <div data-linea="1">
        	<label for="motivo_desestimiento">Motivo de Desestimiento: </label>
            <input type="text" id="motivo_desestimiento" name="motivo_desestimiento" />
        </div>
    </fieldset>
	
	<button type="submit" class="guardar">Desistir solicitud</button>

</form>
    
<script type ="text/javascript">

	var estadoCertificado = "<?php echo $this->modeloCertificadoFitosanitario->getEstadoCertificado(); ?>";

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();

		if(estadoCertificado == "Anulado"){    		
    		$("#detalleItem").html('<div class="mensajeInicial">No se puede desistir una solicitud en estado "' + estadoCertificado + '".</div>');
        }
		
	 });

	$("#formulario").submit(function (event) {
		event.preventDefault();

		var respuesta = JSON.parse(ejecutarJson($("#formulario")).responseText);

			if (respuesta.estado == 'exito'){
	       		$("#estado").html(respuesta.mensaje);
	       		$("#_actualizar").click();
				$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para revisarlo.</div>');
	        }
		
	});
	
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
