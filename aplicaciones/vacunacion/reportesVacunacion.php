<header>
	<h1>Reportes Vacunación</h1>
</header>
<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacunacion.php';
require_once '../../clases/ControladorEmpleadoEmpresa.php';

$conexion = new Conexion();
$va = new ControladorVacunacion();
$cee = new ControladorEmpleadoEmpresa();


$identificadorUsuario=$_SESSION['usuario'];
$filaTipoUsuario=pg_fetch_assoc($va->obtenerTipoUsuario($conexion, $identificadorUsuario));

$qOperadorEmpleadoEmpresa = $cee->verificarEmpleadoEmpresa($conexion, $identificadorUsuario);

?>

	<article id="0" class="item" data-rutaAplicacion="vacunacion" data-opcion="reporteVacunacionUsuarioExterno" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte certificados de vacunación usuarios externos</span>
		<span class="ordinal">1</span>
		<aside></aside>
	</article>
	
<?php

switch ($filaTipoUsuario['codificacion_perfil']){
	case 'PFL_USUAR_INT':
	?>
	
	<article id="1" class="item" data-rutaAplicacion="vacunacion" data-opcion="reporteVacunacionUsuarioInterno" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte certificados de vacunación usuarios internos</span>
		<span class="ordinal">2</span>
		<aside></aside>
	</article>	
	<article id="2" class="item" data-rutaAplicacion="vacunacion" data-opcion="reporteFiscalizacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte fiscalizaciones de vacunación</span>
		<span class="ordinal">3</span>
		<aside></aside>
	</article>	
	<article id="3" class="item" data-rutaAplicacion="vacunacion" data-opcion="reporteAretesVacunacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte de aretes utilizados en vacunación</span>
		<span class="ordinal">4</span>
		<aside></aside>
	</article>
	<?php	
	break;
	
}

if (pg_num_rows($qOperadorEmpleadoEmpresa) > 0 && $filaTipoUsuario['codificacion_perfil'] != 'PFL_USUAR_INT') { ?>
    <article id="3" class="item" data-rutaAplicacion="vacunacion" data-opcion="reporteAretesVacunacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte de aretes utilizados en vacunación</span>
		<span class="ordinal">4</span>
		<aside></aside>
	</article>    
<?php 

}

?>
<script>
	$(document).ready(function(){
		$("#listadoItems").removeClass("programas");
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un reporte para visualizar.</div>');
	});
	</script>