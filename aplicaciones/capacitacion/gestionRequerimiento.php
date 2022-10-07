<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../../clases/ControladorCapacitacion.php';
require_once '../../clases/ControladorAreas.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try{

	$conexion = new Conexion();
	$cc = new ControladorCapacitacion();
	$ca = new ControladorAreas();
	
	$opcion=$_POST['opcion'];
	$tipoEvento = $_POST['tipoEvento'];
	$tipoCertificado = $_POST['tipoCertificado'];
	$nombreEvento= strtoupper ($_POST['nombreEvento']);
	$empresaCapacitadora= strtoupper ($_POST['empresaCapacitadora']);
	$fechaInicio= $_POST['fechaInicio'];
	$fechaFin= $_POST['fechaFin'];
	$eventoPagado= $_POST['eventoPagado'];
	
	if((strcmp ($_POST['costoUnitario'],'' ) == 0))
		$costoUnitaria=0;
	else 
		$costoUnitaria=$_POST['costoUnitario'];
	
	$horas=$_POST['horas'];
	$localizacion=$_POST['localizacion'];
	$pais= $_POST['pais']!=''?$_POST['pais']:'Ecuador';
	$provincia=$_POST['provincia'];
	$canton=$_POST['canton'];
	$ciudad=$_POST['ciudad'];
	$justificacion=$_POST['justificacion'];
	$idRequerimiento=$_POST['idRequerimiento'];
	$ocupante_id= ($_POST['ocupante_id']);
	$estado=$_POST['estadoAprobacion'];
	$observacion=$_POST['observacion'];
	$observacionTH=$_POST['observacionTH'];	
	$numeroCertificacion=$_POST['numeroCertificacion'];
	$nombrePartida=$_POST['nombreCertificacion'];
	$fechaPartida=$_POST['fechaPartida'];
	$archivo=$_POST['archivo'];
	$capacitacionProgramada=$_POST['capacitacionProgramada'];
	$capacitacionInterna=$_POST['capacitacionInterna'];
	$identificadorUsuario = $_SESSION['usuario'];
	$asignarDirector = $_POST['asignarDirector'];
	
	try {
		
		$conexion->ejecutarConsulta("begin;");
		$areaUsuario = pg_fetch_assoc($ca->areaUsuario($conexion, $identificadorUsuario));
		$areaRecursiva = pg_fetch_assoc($ca->buscarAreaResponsablePorUsuarioRecursivo($conexion, $areaUsuario['id_area']));
		
		$tipoArea = $areaRecursiva['clasificacion'];
		$arrayAreas = explode(',', $areaRecursiva['path']);
		
		if($tipoArea == 'Planta Central'){
			$areaRevisor = $arrayAreas[2];
		}else{
			$areaRevisor = $arrayAreas[3];			
		}
		
		$identificadorRevisor = pg_fetch_result($ca->buscarResponsableSubproceso($conexion, $areaRevisor), 0, 'identificador');
				
		if(strcmp($opcion,"Nuevo")==0){
			
			$fila=$cc->nuevoRequerimiento($conexion,$tipoEvento,$tipoCertificado,$nombreEvento,$empresaCapacitadora,$fechaInicio,$fechaFin,
					$eventoPagado,$costoUnitaria,$horas,$localizacion,$pais,$provincia,$canton,$ciudad,$justificacion,$identificadorUsuario,$capacitacionInterna);
			
			$requerimiento =  pg_fetch_assoc($fila);
			
			$idRequerimiento = $requerimiento['id_requerimiento'];
			
			for ($i = 0; $i < count ($ocupante_id); $i++) {
				$cc -> guardarParticipantesEvento($conexion, $idRequerimiento, $ocupante_id[$i],'1');
			}
			
			$cc->actualizarRevisorRequerimiento($conexion, $areaRevisor, $identificadorRevisor, $idRequerimiento, 'identificadorDistritoB');
			$cc->actualizarRevisorRequerimiento($conexion, $areaRevisor, $identificadorRevisor, $idRequerimiento, 'identificadorDistritoA');
			
		}
		
		if(strcmp($opcion,"Actualizar")==0){
			$cc->actualizarRequerimiento($conexion,$idRequerimiento,$tipoEvento,$tipoCertificado,$nombreEvento,$empresaCapacitadora,$fechaInicio,$fechaFin, $eventoPagado,
										$costoUnitaria,$horas,$localizacion,$pais,$provincia,$canton,$ciudad,$justificacion,$estado,$observacion,$observacionTH,
										$numeroCertificacion,$archivo,$nombrePartida,$fechaPartida,$capacitacionProgramada,$capacitacionInterna);
			
		
		}				
		
		if(strcmp($opcion,"actualizarEstado")==0){
			if($asignarDirector == 'SI'){
				$tipoAreaRevisor = pg_fetch_assoc($ca->buscarPadreSubprocesos($conexion, $areaRevisor));
					
				if($tipoAreaRevisor['clasificacion'] == 'Dirección Distrital B'){
					$zona = $arrayAreas[2];
			
					$areaRevisorDistrital = pg_fetch_assoc($ca->buscarAreaPadrePorClasificacion($conexion, $zona, 'Dirección Distrital A'));
			
					$identificadorRevisor = pg_fetch_result($ca->buscarResponsableSubproceso($conexion, $areaRevisorDistrital['id_area']), 0, 'identificador');
			
					$areaRevisor = $areaRevisorDistrital['id_area'];
			
					$cc->actualizarRevisorRequerimiento($conexion, $areaRevisor, $identificadorRevisor, $idRequerimiento,'identificadorDistritoA');
			
					$estado = 6;
				}
			}
			$cc->actualizarEstadoRequerimiento($conexion, $idRequerimiento, $estado ,$observacion);
		
		}
		if(strcmp($opcion,"actualizarAprobacionTH")==0){
			$cc->actualizarAprobacionTH($conexion, $idRequerimiento, $estado,$capacitacionProgramada,$observacionTH);
			
		}
		if(strcmp($opcion,"actualizarEstadoFinanciero")==0){
			$cc->actualizarAprobacionFinanciero($conexion, $idRequerimiento, $estado, $numeroCertificacion, $nombrePartida ,$fechaPartida, $archivo);	
		}
		
		
		
		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
		$conexion->ejecutarConsulta("commit;");
		$conexion->desconectar();
		echo json_encode($mensaje);
							
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		pg_close($conexion);
		$error=$ex->getMessage();
		$mensaje['estado'] = 'error';
		$suma_cod_error;
		$error_code=0;
		$suma_cod_error= $error_code + (stristr($error, 'duplicate key')!=FALSE)?1:0;
		$error_code= $error_code + $suma_cod_error;
		$suma_cod_error= $error_code + (stristr($error, 'numero_contrato')!=FALSE)?2:0;
		$error_code= $error_code + $suma_cod_error;
					
		switch($error_code){
			case 0:		$mensaje['mensaje'] = 'No se puede ejecutar la sentencia';
			break;	
			case 3:		$mensaje['mensaje'] = 'Error: Ya existe un contrato con el mismo número';
			break;
		}
		echo json_encode($mensaje);
	}

} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>

