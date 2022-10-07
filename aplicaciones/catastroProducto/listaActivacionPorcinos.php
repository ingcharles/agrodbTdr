<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';
require_once '../../clases/ControladorEmpleadoEmpresa.php';

$conexion = new Conexion();
$va = new ControladorVacunacion();
$cee = new ControladorEmpleadoEmpresa();

$identificadorUsuario = $_SESSION['usuario'];

$filaTipoUsuario=pg_fetch_assoc($va->obtenerTipoUsuario($conexion, $identificadorUsuario));

$qOperadorEmpresa = $cee->obtenerOperadorEmpresa($conexion, $_SESSION['usuario'],"('OPTSA')" );
$qOperadorEmpleadoEmpresa = $cee->verificarEmpleadoEmpresa($conexion, $identificadorUsuario);


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Activación de porcinos</h1>
</header>
<?php

switch ($filaTipoUsuario['codificacion_perfil']){
	case 'PFL_USUAR_INT':
	?>
	<article style="height:140px;" id="1" class="item" data-rutaAplicacion="catastroProducto" data-opcion="abrirActivarPorcinoCambioDuenio" draggable="true" data-destino="detalleItem">
		<div></div>
		<span>Activación por cambio de dueño</span>
		<span class="ordinal">1</span>
		<aside></aside>
	</article>
	<article style="height:140px;" id="2" class="item" data-rutaAplicacion="catastroProducto" data-opcion="abrirActivarPorcinoTemporal" draggable="true" data-destino="detalleItem">
		<span >Activación temporal</span>
		<span class="ordinal">2</span>
		<aside></aside>
	</article>	
	<?php	
	break;	
}

if (pg_num_rows($qOperadorEmpresa) > 0 || pg_num_rows($qOperadorEmpleadoEmpresa) > 0) { ?>
    <article style="height:140px;" id="1" class="item" data-rutaAplicacion="catastroProducto" data-opcion="abrirActivarPorcinoCambioDuenio" draggable="true" data-destino="detalleItem">
		<div></div>
		<span>Activación por cambio de dueño</span>
		<span class="ordinal">1</span>
		<aside></aside>
	</article>
<?php 
}
?>
</body>
<script>

$(document).ready(function(){

	$("#listadoItems").removeClass("programas");
	$("#listadoItems").addClass("comunes");
	$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un item para visualizar.</div>');
	
});

</script>
</html>