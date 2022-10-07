<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAreas.php';
	require_once '../../clases/ControladorProgramacionPresupuestaria.php';
	
	$conexion = new Conexion();
	$ca = new ControladorAreas();
	$cpp = new ControladorProgramacionPresupuestaria();
	
	$fecha = getdate();
	$anio = $fecha['year'];
	
	$idObjetivoEstrategico = $_POST['idObjetivoEstrategico'];
	$nombreObjetivoEstrategico = $_POST['nombreObjetivoEstrategico'];
	
	$idAreaN2 = $_POST['areaN2'];
	$nombreAreaN2 = $_POST['nombreAreaN2'];
	
	$idObjetivoEspecifico = $_POST['idObjetivoEspecifico'];
	$nombreObjetivoEspecifico = $_POST['nombreObjetivoEspecifico'];
	
	$idAreaN4 = $_POST['areaN4'];
	$nombreAreaN4 = $_POST['nombreAreaN4'];
	
	$idObjetivoOperativo = $_POST['idObjetivoOperativo'];
	$nombreObjetivoOperativo = $_POST['nombreObjetivoOperativo'];
	
	$idGestion = $_POST['idGestion'];
	$nombreGestion = $_POST['nombreGestion'];
	
	$tipo = $_POST['tipo'];
	
	$idProcesoProyecto = $_POST['idProcesoProyecto'];
	$nombreProcesoProyecto = $_POST['nombreProcesoProyecto'];
	
	$productoFinal = $_POST['productoFinal'];
	
	$idComponente = $_POST['idComponente'];
	$nombreComponente = $_POST['nombreComponente'];
	
	$idActividad = $_POST['idActividad'];
	$nombreActividad = $_POST['nombreActividad'];
	
	$idProvincia = $_POST['idProvincia'];
	$nombreProvincia = $_POST['nombreProvincia'];
	
	$cantidadUsuarios = $_POST['cantidadUsuarios'];
	$poblacionObjetivo = $_POST['poblacionObjetivo'];
	$medioVerificacion = $_POST['medioVerificacion'];
	
	$idResponsable = $_POST['idResponsable'];
	$nombreResponsable = $_POST['nombreResponsable'];
		
	$identificador = $_SESSION['usuario'];
	$idAreaFuncionario = $_SESSION['idArea'];
	
	//Área de usuario para revisión del jefe inmediato
	$areaFuncionario = pg_fetch_assoc($ca->areaUsuario($conexion, $identificador));
	$idAreaFuncionario = $areaFuncionario['id_area'];
	
	$idAreaRevisor = $areaFuncionario['id_area_padre'];
	$idRevisor = pg_fetch_result($ca->buscarResponsableSubproceso($conexion,$idAreaRevisor ), 0, 'identificador');
		

	if(($identificador != null) || ($identificador != '')){
		$conexion->ejecutarConsulta("begin;");
		$idPlanificacionAnual = $cpp->nuevaProgramacionAnual($conexion, $identificador, $idAreaFuncionario, $anio, 
				$idObjetivoEstrategico, $idAreaN2, $idObjetivoEspecifico, $idAreaN4, $idObjetivoOperativo, $idGestion, $tipo,
				$idProcesoProyecto, $idComponente, $idActividad, $productoFinal, $idProvincia, $nombreProvincia, $cantidadUsuarios, 
				$poblacionObjetivo, $medioVerificacion, $idResponsable, $nombreResponsable, $idRevisor, $idAreaRevisor);
		$conexion->ejecutarConsulta("commit;");
	
		echo '<input type="hidden" id="' . pg_fetch_result($idPlanificacionAnual, 0, 'id_planificacion_anual') . '" data-rutaAplicacion="programacionAnualPresupuestaria" data-opcion="abrirPlanificacionAnual" data-destino="detalleItem"/>';
	
	}else{
		echo '<label>Su sesión ha expirado, por favor ingrese nuevamente al sistema para continuar.</label>';
	}
?>

<script type="text/javascript">
	$("document").ready(function(){
		abrir($("#ventanaAplicacion #opcionesAplicacion a.abierto"),null,true);
		abrir($("#detalleItem input"),null,true);
	});	
</script>