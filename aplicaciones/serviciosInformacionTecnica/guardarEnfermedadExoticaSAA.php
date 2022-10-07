<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorServiciosInformacionTecnica.php';

	$conexion = new Conexion ();
	$cc = new ControladorCatalogos();
	$csit = new ControladorServiciosInformacionTecnica();

	$idEnfermedad = $_POST['enfermedad'];
	$nombreEnfermedad = $_POST['nombreEnfermedad'];
	$inicioVigencia = $_POST['inicioVigencia'];
	$finVigencia = $_POST['finVigencia'];
	$observacion = $_POST['observacion'];
	$usuarioResponsable = $_POST['usuarioResponsable'];
	
	$zona=$_POST['hZona'];
	$zonaNombre=$_POST['hZonaNombre'];
	$pais=$_POST['hPais'];
	$paisNombre=$_POST['hPaisNombre'];
	
	$tipoRequerimiento=$_POST['hTipoRequerimiento'];
	$tipoRequerimientoNombre=$_POST['hTipoRequerimientoNombre'];
	$elementoRevision=$_POST['hElementoRevision'];
	$elementoRevisionNombre=$_POST['hElementoRevisionNombre'];
	
	$conexion->ejecutarConsulta("begin;");
	$idEnfermedadExotica= pg_fetch_row($csit->guardarEnfermedadesExoticas($conexion, $idEnfermedad, $nombreEnfermedad, $inicioVigencia, $finVigencia, $observacion,$usuarioResponsable));
	
	for($i = 0; $i < count($zona); $i++){
		$csit->guardarEnfermedadesLocalizacion($conexion, $zona[$i], $zonaNombre[$i], $pais[$i], $paisNombre[$i], $idEnfermedadExotica[0],$usuarioResponsable);
	}
	
	for($i = 0; $i < count($tipoRequerimiento); $i++){
		$csit->guardarEnfermedadesRequerimiento($conexion, $tipoRequerimiento[$i], $tipoRequerimientoNombre[$i], $elementoRevision[$i], $elementoRevisionNombre[$i], $idEnfermedadExotica[0],$usuarioResponsable);
	}

	$conexion->ejecutarConsulta("commit;");
	$conexion->ejecutarConsulta("rollback;");

	echo '<input type="hidden" id="' . $idEnfermedadExotica[0] . '" data-rutaAplicacion="serviciosInformacionTecnica" data-opcion="abrirEnfermedadExoticaSAA" data-destino="detalleItem"/>'
	?>
<script type="text/javascript">
	$("document").ready(function(){
		$('#_actualizarSubListadoItems').click();
		abrir($("#detalleItem input"),null,true);
	});	
</script>