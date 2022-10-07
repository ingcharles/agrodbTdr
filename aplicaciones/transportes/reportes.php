<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorUsuarios.php';

$conexion = new Conexion();
$cu = new ControladorUsuarios();

$res = $cu-> obtenerPermisoUsuario($conexion, $_SESSION['usuario']);
$permiso = pg_fetch_assoc($res);

//print_r($_SESSION);
?>
	
	<header>
		<h1>Reportes Vehículos</h1>
	</header>
	<article id="0" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteHistoricoVehiculos" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Histórico Vehículos</span>
		<span class="ordinal">1</span>
		<aside></aside>
	</article>
	
	
	<article id="1" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteMovilizacion" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte Movilización</span>
		<span class="ordinal">2</span>
		<aside></aside>
	</article>
	
	<article id="2" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteMantenimiento" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte Mantenimiento</span>
		<span class="ordinal">3</span>
		<aside></aside>
	</article>
	
	<article id="3" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteCombustible" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte Combustible</span>
		<span class="ordinal">4</span>
		<aside></aside>
	</article>
	
	<article id="4" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteGasolinera" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte Gasolineras</span>
		<span class="ordinal">5</span>
		<aside></aside>
	</article>

	<article id="5" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteTalleres" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte Talleres</span>
		<span class="ordinal">6</span>
		<aside></aside>
	</article>
	
	<!-- article id="6" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteSiniestro" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte Siniestros</span>
		<span class="ordinal">7</span>
		<aside></aside>
	</article-->
	
	<article id="7" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteConsolidado" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte consolidado</span>
		<span class="ordinal">8</span>
		<aside></aside>
	</article>
	
	<article id="8" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteVehiculoNacional" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reporte vehículos nivel nacional</span>
		<span class="ordinal">9</span>
		<aside></aside>
	</article>
	
	<article id="9" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteGeneralVehiculos" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reportes generales </span>
		<span class="ordinal">10</span>
		<aside></aside>
	</article>
	
	<article id="11" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteFuncionariosMovilizados" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reportes de Movilizaciones y Funcionarios Movilizados</span>
		<span class="ordinal">11</span>
		<aside></aside>
	</article>
	
	<article id="12" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteCombustiblesGenerados" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reportes de Combustibles Generados</span>
		<span class="ordinal">12</span>
		<aside></aside>
	</article>
	
	<article id="13" class="item" data-rutaAplicacion="transportes"	data-opcion="reporteMantenimientosGenerados" draggable="true" data-destino="listadoItems">
		<div></div>
		<span>Reportes de Mantenimientos Generados</span>
		<span class="ordinal">13</span>
		<aside></aside>
	</article>
	
	<script>
	$(document).ready(function(){
	
		$("#listadoItems").removeClass("programas");
		$("#listadoItems").addClass("comunes");
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un reporte para visualizar.</div>');
	});

	if (<?php echo $permiso['administrador'];?>!="2"){
		$("#8").hide();
		$("#9").hide();
	}

	$("#7").hide();
		
	
	</script>