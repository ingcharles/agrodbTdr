<header>
	<h1><?php echo $this->accion; ?></h1>
</header>
	
	<fieldset>
		<legend>Resumen Consulta</legend>				
		<div data-linea="1">
			<label for="fecha">Fecha:</label>
			<span><?php echo $this->fechaConsulta;?></span>
		</div>	
		<div data-linea="2">
			<label for="fecha">Síntomas:</label>
			<span><?php echo $this->modeloConsultaMedica->getSintomas();?></span>
		</div>	
			
		<div data-linea="4">
			<label for="fecha">Reposo médico:</label>
			<span><?php echo $this->modeloConsultaMedica->getReposoMedico();?></span>
		</div>	
		<div data-linea="5">
			<label for="fecha">Días de reposo:</label>
			<span><?php echo $this->modeloConsultaMedica->getDiasReposo();?></span>
		</div>		
		<div data-linea="6">
			<label for="fecha">Observaciones:</label>
			<span><?php echo $this->modeloConsultaMedica->getObservaciones();?></span>
		</div>		
		<?php echo $this->listarDiagnostico($this->idConsultaMedica,0);?>
		<?php echo $this->listarValoracionMedica($this->idConsultaMedica,0);?>
	</fieldset >

<script type ="text/javascript">

	$(document).ready(function() {
		mostrarMensaje("", "FALLO");
		construirValidador();
		distribuirLineas();
		$("#modalDetalle").hide();
		construirAnimacion($(".pestania"));
	 });

	
</script>