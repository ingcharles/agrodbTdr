<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
	<fieldset>
		<legend>Información del Acto Administrativo</legend>	
		<div data-linea="1" id="NumProceso">
			<label for="numero_proceso">No. de Proceso Administrativo: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getNumeroProceso(); ?></span>
		</div>	
		<div data-linea="2">
			<label for="area_tecnica">Área Técnica: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getAreaTecnica();?></span>
		</div>	
		<div data-linea="3">
			<label for="area_tecnica">Fecha de Ingreso: </label>
			<span><?php echo date('j/n/Y',strtotime($this->modeloProcesoAdministrativo->getFechaCreacion()));?></span>
		</div>	
       <div data-linea="4">
			<label for="provincia">Provincia: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getProvincia();?></span>
		</div>				
		<div data-linea="5">
			<label for="nombre_accionado">Nombre del Accionado: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getNombreAccionado(); ?></span>
		</div>		
		<div data-linea="6">
			<label for="nombre_establecimiento">Nombre del Establecimiento: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getNombreEstablecimiento(); ?></span>
		</div>		
		<div data-linea="7">
			<label for="nombre_abogado">Nombre de Abogado: </label>
			<span><?php echo $this->funcionario; ?></span>
		</div>
		<div data-linea="8" id="NumProceso">
			<label for="numero_proceso">Número de Resolución: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getNumeroProceso(); ?></span>
		</div>	
		<div data-linea="9">
			<label for="nombre_establecimiento">Detalle de Sanción: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getDetalleSancion(); ?></span>
		</div>	
		<div data-linea="10">
			<label for="nombre_establecimiento">Resultado del Trámite: </label>
			<span><?php echo ($this->modeloProcesoAdministrativo->getEstado()=='creado')?'':$this->modeloProcesoAdministrativo->getEstado(); ?></span>
		</div>
		<div data-linea="11">
			<label for="nombre_establecimiento">Observaciones: </label>
			<span><?php echo $this->modeloProcesoAdministrativo->getObservacion(); ?></span>
		</div>		
			

	</fieldset >
	<fieldset id="field1">
		<legend>Documentos generados en el Acto Administrativo</legend>				
      <?php echo  $this->consultaActos;?>
	</fieldset >
	<fieldset id="field1">
		<legend>Documentos anexados al Acto Administrativo</legend>	
		<?php echo  $this->consultaAnexo;?>			
      
	</fieldset >
	  
<script type ="text/javascript">
	$(document).ready(function() {
		mostrarMensaje("", "FALLO");
		construirValidador();
		distribuirLineas();
	 });

</script>
