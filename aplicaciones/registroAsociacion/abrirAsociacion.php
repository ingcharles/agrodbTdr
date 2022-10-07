<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorAplicaciones.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

$identificador = $_POST['id'];

$qOperador = $cro->buscarOperador($conexion, $identificador);
$operador = pg_fetch_assoc($qOperador);

?>

<header>
	<h1>Asociación</h1>
</header>
<p>

<fieldset>
	<legend>Información de la asociación</legend>
	<div data-linea="1">
		<label>Identificación:</label>
		<?php echo $operador['identificador']; ?>
	</div>
	<div data-linea="1">
		<label>Fecha de registro:</label>
			<?php echo  date('j/n/Y',strtotime($operador['fecha_operador']));?>
	</div>
	<div data-linea="2">
		<label>Razón social:</label>
		<?php echo $operador['razon_social'];  ?>
	</div>
</fieldset>


<script type="text/javascript">

	$(document).ready(function(){
		distribuirLineas();	
	});

</script>
