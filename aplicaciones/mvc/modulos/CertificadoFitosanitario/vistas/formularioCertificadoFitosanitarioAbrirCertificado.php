<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

    <fieldset>
    	<legend>Revisión Técnica</legend>
    	<div data-linea="1">
    		<label for="observacion_revision">Observación:</label> 
    			<?php echo ($this->modeloCertificadoFitosanitario->getObservacionRevision() == "") ? 'N/A' : $this->modeloCertificadoFitosanitario->getObservacionRevision(); ?>	
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
			<?php echo ($this->modeloCertificadoFitosanitario->getFormaPago() != "")?$this->modeloCertificadoFitosanitario->getFormaPago():"N/A"; ?>
		</div>				

		<div data-linea="1">
			<label for="descuento">Descuento: </label>
            <?php echo ($this->modeloCertificadoFitosanitario->getDescuento() != "")?$this->modeloCertificadoFitosanitario->getDescuento():"N/A"; ?>
		</div>							

		<div data-linea="2">
			<label for="motivo_descuento">Motivo del Descuento: </label>
			<?php echo ($this->modeloCertificadoFitosanitario->getMotivoDescuento() != "")?$this->modeloCertificadoFitosanitario->getMotivoDescuento():"N/A"; ?>
		</div>

		<input type="hidden" id="fecha_modificacion_certificado" name="fecha_modificacion_certificado" value="<?php echo $this->rutaFecha; ?>" />
	
	</fieldset>
	
	<?php echo (isset($this->detalleAnulaReemplaza)) ? $this->detalleAnulaReemplaza : null ?>
	<?php echo (isset($this->detalleDesestimiento)) ? $this->detalleDesestimiento : null ?>
    
<script type ="text/javascript">

	$(document).ready(function() {
		construirValidador();
		distribuirLineas();
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
    		