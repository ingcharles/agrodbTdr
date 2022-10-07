<header>
	<h1><?php echo $this->accion; ?></h1>
</header>

<?php echo $this->panelDatosFuncionarioContrato; ?>

	<fieldset>
		<legend>Horario de trabajo</legend>

		<div data-linea="3">
			<label for="grupo">Grupo: </label><?php echo $this->modeloJornadaLaboral->getGrupo(); ?>
		</div>
		
		<div data-linea="5">
			<label for="mes">Mes: </label> <?php echo $this->modeloJornadaLaboral->getMes(); ?>
		</div>

		<div data-linea="4">
			<label for="horario">Horario: </label> <?php echo $this->modeloJornadaLaboral->getHorario(); ?>
		</div>
		
	</fieldset>

<script type="text/javascript">
	$(document).ready(function() {
		distribuirLineas();
	 });
</script>
