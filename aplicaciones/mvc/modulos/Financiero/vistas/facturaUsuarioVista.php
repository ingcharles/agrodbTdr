
<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

	<fieldset>
		<legend>Comprobantes de facturación</legend>

		<table style="width:100%">
			<thead>
				<tr>					
					<th>N° factura</th>
					<th>Archivo factura PDF</th>
					<th>Archivo factura XML</th>
					<th>Fecha facturación</th>	
				</tr>
		</thead>
		
		<tr>					
			<td><?php echo $this->modeloOrdenPago->getNumeroEstablecimiento().'-'.$this->modeloOrdenPago->getPuntoEmision().'-'.$this->modeloOrdenPago->getNumeroFactura(); ?></td>
			<td><a href="<?php echo $this->modeloOrdenPago->getFactura(); ?>" target= "_blank">Archivo </a></td>
			<td><a download="<?php echo $this->modeloOrdenPago->getClaveAcceso().'.xml'; ?>" href="<?php echo $this->modeloOrdenPago->getRutaRecortadaXML(); ?>">Archivo </a></td>
			<td><?php echo $this->modeloOrdenPago->getFechaFacturacion(); ?></td>
		</tr>
		
		</table>
		
	</fieldset>
	
<script type="text/javascript">

	$(document).ready(function(){
		construirValidador();
		distribuirLineas();
	});
	
</script>
