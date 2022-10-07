<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorEventoSanitario.php';

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

$ruta = 'seguimientoEventosSanitarios';

try{
	$conexion = new Conexion();
	$cpco = new ControladorEventoSanitario();
	
	$identificador = $_SESSION['usuario'];
	
	$idEventoSanitario = htmlspecialchars ($_POST['idEventoSanitario'],ENT_NOQUOTES,'UTF-8');
	
	$explotacionAves = htmlspecialchars ($_POST['explotacionAves'],ENT_NOQUOTES,'UTF-8');
	
	$numeroRegistroGranja = htmlspecialchars ($_POST['numeroRegistroGranja'],ENT_NOQUOTES,'UTF-8');
	$numeroCertInspeccion = htmlspecialchars ($_POST['numeroCertInspeccion'],ENT_NOQUOTES,'UTF-8');
	
	$numeroGalpones = htmlspecialchars ($_POST['numeroGalpones'],ENT_NOQUOTES,'UTF-8');
	$capacidadInstalada = htmlspecialchars ($_POST['capacidadInstalada'],ENT_NOQUOTES,'UTF-8');	
	
	$capacidadOcupada = htmlspecialchars ($_POST['capacidadOcupada'],ENT_NOQUOTES,'UTF-8');
	
	$idTipoAve = htmlspecialchars ($_POST['especieAves'],ENT_NOQUOTES,'UTF-8');
	$nombreTipoAve = htmlspecialchars ($_POST['nombreEspecieAves'],ENT_NOQUOTES,'UTF-8');
	
	$idLineaAve = htmlspecialchars ($_POST['lineaAve'],ENT_NOQUOTES,'UTF-8');	
	$nombreLineaAve = htmlspecialchars ($_POST['nombreLineaAve'],ENT_NOQUOTES,'UTF-8');
	
	$tipoExplotacion = htmlspecialchars ($_POST['TipoExplotacionAve'],ENT_NOQUOTES,'UTF-8');
	
	$descripcionExplotacion = htmlspecialchars ($_POST['descripcionExplotacionAve'],ENT_NOQUOTES,'UTF-8');
	$plantaIncuvacion = htmlspecialchars ($_POST['plantaIncuvacion'],ENT_NOQUOTES,'UTF-8');
	
	$faenadoraAves = htmlspecialchars ($_POST['faenadoraAves'],ENT_NOQUOTES,'UTF-8');
	$viaPrincipal = htmlspecialchars ($_POST['viaPrincipal'],ENT_NOQUOTES,'UTF-8');
	
	$lagunasHumedales = htmlspecialchars ($_POST['lagunas'],ENT_NOQUOTES,'UTF-8');
	$centroPoblado = htmlspecialchars ($_POST['centroPoblado'],ENT_NOQUOTES,'UTF-8');
	
	$diagnosticoGranja = htmlspecialchars ($_POST['realizadoDiagnosticos'],ENT_NOQUOTES,'UTF-8');
	
	$idEnfermedad = htmlspecialchars ($_POST['enfermedadAve'],ENT_NOQUOTES,'UTF-8');
	$nombreEnfermedad = htmlspecialchars ($_POST['nombreEnfermedadAve'],ENT_NOQUOTES,'UTF-8');
	
	$fechaDiagnostico = htmlspecialchars ($_POST['fechaDiagnostico'],ENT_NOQUOTES,'UTF-8');
	
	try {
		
		$especieInspeccion = $cpco->buscarTiposExplotacionesAves($conexion, $idEventoSanitario, $nombreTipoAve, $nombreLineaAve);
		
		if(pg_num_rows($especieInspeccion) == 0){
			$conexion->ejecutarConsulta("begin;");
			
			if($explotacionAves == 'No'){
				$numeroRegistroGranja = 0;
				$numeroCertInspeccion = 0;				
				$numeroGalpones = 0;
				$capacidadInstalada = 0;				
				$capacidadOcupada = 0;				
				$idTipoAve = 0;
				$nombreTipoAve = 'No Aplica';				
				$idLineaAve = 0;
				$nombreLineaAve = 'No Aplica';				
				$tipoExplotacion = 'No Aplica';				
				$descripcionExplotacion = 'No Aplica';
				$diagnosticoGranja = 0;
				$idEnfermedad = 0;
				$nombreEnfermedad = 'No Aplica';
				$fechaDiagnostico = 'now()';
				$plantaIncuvacion = 0;				
				$faenadoraAves = 0;
				$viaPrincipal = 0;				
				$lagunasHumedales = 0;
				$centroPoblado = 0;						
			}
			
				$idExplotacionAves = pg_fetch_result($cpco->nuevaExplotacionAves($conexion, $idEventoSanitario, 
												$explotacionAves, $numeroRegistroGranja, 
												$numeroCertInspeccion, $numeroGalpones, $capacidadInstalada,
												$capacidadOcupada, $idTipoAve, $nombreTipoAve, $idLineaAve,$nombreLineaAve,
												$tipoExplotacion, $descripcionExplotacion, $plantaIncuvacion,
												$faenadoraAves, $viaPrincipal, $lagunasHumedales,
												$centroPoblado, $diagnosticoGranja, $idEnfermedad,$nombreEnfermedad,
												$fechaDiagnostico, $identificador), 
												0, 'id_explotacion_aves');
				
			$conexion->ejecutarConsulta("commit;");
		
			if($explotacionAves == 'No'){
				$fecha = getdate();
				$fechaDiagnostico = $fecha['mday'].'/'.$fecha['mon'].'/'.$fecha['year'];
			}
			
			$mensaje['estado'] = 'exito';
			$mensaje['mensaje'] = $cpco->imprimirLineaTipoExplotacionAves(	$idExplotacionAves, $idEventoSanitario, $numeroRegistroGranja, 
															$numeroCertInspeccion, $numeroGalpones, $capacidadInstalada,
															$capacidadOcupada, $nombreTipoAve, $nombreLineaAve,
															$tipoExplotacion, $descripcionExplotacion, $plantaIncuvacion,
															$faenadoraAves, $viaPrincipal, $lagunasHumedales,
															$centroPoblado, $diagnosticoGranja, $nombreEnfermedad,
															$fechaDiagnostico, $ruta );
		}else{
			$mensaje['estado'] = 'error';
			$mensaje['mensaje'] = "registro ingresado ya existe, por favor verificar en el listado.";
		}
		
		$conexion->desconectar();
		echo json_encode($mensaje);
	
	} catch (Exception $ex){
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
		$conexion->desconectar();
	}
		
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
	$conexion->desconectar();
}
?>