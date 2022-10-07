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

<?php if(isset($this->tecnicoRevisionDocumentalAsignado)){echo $this->tecnicoRevisionDocumentalAsignado;} ?>

<script type="text/javascript">
    $(document).ready(function() {
    	$("#estado").html("").removeClass('alerta');    	
		construirValidador();
        distribuirLineas();
    });
    
	
	
</script>

