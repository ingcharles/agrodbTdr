<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorUsuarios.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorReportes.php';

$conexion = new Conexion();
$cc = new ControladorVacaciones();
$cu = new ControladorUsuarios();
$ca = new ControladorAreas();

$mensaje = array();
$mensaje['estado'] = 'error';
$mensaje['mensaje'] = 'Ha ocurrido un error!';

try {
	try {

		$id_registro=$_POST['id_registro'];
		$identificadorFuncionario = $_POST['identificadorFuncionario'];
		$identificadorTH=$_POST['identificadorTH'];
		
		$codigoAccionPersonal = '';
		
		$filaSolicitud = pg_fetch_assoc($cc->obtenerDatosPermiso($conexion,$id_registro));
		
		//Obtiene el tiempo disponible para permisos del funcionario por años
		$tiempoDisponible = pg_fetch_assoc($cc->obtenerTiempoDisponibleFuncionario($conexion, $identificadorFuncionario));
		
		//Obtiene el subtipo de permiso de la solicitud
		$subtipos = pg_fetch_assoc($cc->obtenerSubTipoPermiso($conexion,null,$filaSolicitud['sub_tipo']));
		$codigoSubtipoPermiso = $subtipos['codigo'];
		
		//Obtiene información de jefe superior
		$filaDirector = pg_fetch_assoc($cc->obtenerNombreDirector($conexion,$filaSolicitud['identificador_jefe_superior']));
		$nombreDirector = $filaDirector['directorath'];
		$cargoDirector = $filaDirector['puestoth'];
		
		//Obtiene información de responsable de talento humano
		$filaDirectorTH = pg_fetch_assoc($cc->obtenerNombreDirector($conexion,$identificadorTH));
		$nombreResponsableTH = $filaDirectorTH['directorath'];
		$cargoResponsableTH = $filaDirectorTH['puestoth'];
		$idArea = $ca->areaUsuario($conexion, $identificadorTH);
		
		//Generando número secuencial de acción de personal por oficina técnica
		$numero = pg_fetch_assoc($cc->generarNumeroAccionPersonal($conexion, $filaSolicitud['id_area_permiso']));
		$nAccion = ($numero['numero'] == '' ? 0001 : $numero['numero']);
		$numeroAccionPersonal = str_pad($nAccion, 4, "0", STR_PAD_LEFT);
		
		//Generar código para acción de personal por la ubicación del responsable de TH
		if(pg_fetch_result($idArea, 0, 'id_area') == 'DGATH' || pg_fetch_result($idArea, 0, 'id_area') == 'GMTTH' || pg_fetch_result($idArea, 0, 'id_area') == 'GATH'){
			$codigoArea = 'DARH';
			$codigoAccionPersonal=$codigoArea.'-'.$numeroAccionPersonal;
		}else if(pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTS' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTP'  || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTT' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTSD' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTG' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTCA' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTEO' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTNL03' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTS03' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTL03' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'PCF03' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTQ04' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTC04' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTM04' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTS04'	||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTPVM04' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'CPQ04' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'UMP04' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'AQ04' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTA07' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'CM07' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'CVQ10' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'CVQU10'	||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTG12' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'AIG' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'PMGB' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'PMGS' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTN12' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTY12' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTEE12' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTA14'	||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTC14' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTLT14' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTM16' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTPB16' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTH16' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTC16' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTP16' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTA16'	||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'ASR16' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTB16'	
				){
			$idZona = pg_fetch_result($ca->buscarArea($conexion, pg_fetch_result($idArea, 0, 'id_area_padre')), 0, 'zona_area');
			$codigoArea = pg_fetch_result($ca->buscarArea($conexion, pg_fetch_result($idArea, 0, 'id_area_padre')), 0, 'id_area_padre');
			//$codigoArea = 'DDAT';
			$codigoAccionPersonal=$codigoArea./*''.$idZona.*/'-'.$numeroAccionPersonal;
		}else if(pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTC' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTE' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTO'  || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTCO' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTCH' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTM' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTLR' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTSE' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTMS' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTL' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTI' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTN' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTPA' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTB' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTZC' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTRPI01' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTSG01' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTEA01'  || pg_fetch_result($idArea, 0, 'id_area_padre') == 'CM01' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTPE02' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTAE02' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTQ02' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTSL02' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') ==  'CLM02' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTI03' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTAA03' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'CM03' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTL05' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTTE04' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTEC04' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'CQJ04' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTL06' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'AIL06' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTS06'  || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTLM06' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTR08' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTC08' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTA08' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTPU07' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') ==  'CMJ07' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTP09' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTM09' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTC09' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTEC09' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'AIM09' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'CEC09' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'UMEC09' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'UMC09'  || pg_fetch_result($idArea, 0, 'id_area_padre') == 'CSE13' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTQ11' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTB11' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'CV11' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'UMB11' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTGJ12' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTM15' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTG15' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTL15' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTME15' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTP15' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'CJ15' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTAJ14' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTCPEJ14' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTSIJ14' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTGJ14'  || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTZ17' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTM17' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTG17' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTS17' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTA17' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTC17' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'CPM17' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'CPA17' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTZJ16' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTYJ16' || pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTZUJ16' ||
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'OTPJ16' || pg_fetch_result($idArea, 0, 'id_area_padre') ==  'OTEPJ16' || 
				pg_fetch_result($idArea, 0, 'id_area_padre') == 'CELJ16' ||	pg_fetch_result($idArea, 0, 'id_area_padre') == 'CEPJ16' 		
				){
			$idZona = pg_fetch_result($ca->buscarArea($conexion, pg_fetch_result($idArea, 0, 'id_area_padre')), 0, 'zona_area');
			$codigoArea = pg_fetch_result($ca->buscarAreaPadrePorClasificacion($conexion, $idZona, 'Dirección Distrital A'), 0, 'id_area');
			//$codigoArea = 'DD';
			$codigoAccionPersonal=$codigoArea./*''.$idZona.*/'-'.$numeroAccionPersonal;
		}
		 
		//Generar texto para acción de personal del catálogo provisto por TH
		$arrayFechaHoraInicio = explode(" ", $filaSolicitud['fecha_inicio']);
		$fechaInicio = $arrayFechaHoraInicio[0];
		$horaInicio = $arrayFechaHoraInicio[1];
		
		//-------------------------------------------------//
		$anioPermiso = date("Y", strtotime($filaSolicitud['fecha_inicio']));
		$numMes=date('m', strtotime($filaSolicitud['fecha_inicio']));
		$meses = array('01' => 'enero','02' => 'febrero','03' => 'marzo','04' => 'abril','05' => 'mayo','06' =>'junio','07' => 'julio',
				'08' => 'agosto','09' => 'septiembre','10' => 'octubre','11' => 'noviembre','12' =>'diciembre');
		$mesPermiso = $meses[$numMes];
		//-------------------------------------------------//

		$arrayFechaHoraFin = explode(" ", $filaSolicitud['fecha_fin']);
		$fechaFin = $arrayFechaHoraFin[0];
		$horaFin = $arrayFechaHoraFin[1];
		
		$arrayFechaHoraSuceso = explode(" ", $filaSolicitud['fecha_suceso']);
		$fechaSuceso = $arrayFechaHoraSuceso[0];
		$horaSuceso = $arrayFechaHoraSuceso[1];
		
		//Días y horas solicitadas en el permiso
		$tiemposDescontado=$cc->devolverFormatoDiasDisponibles($filaSolicitud['minutos_utilizados']);
		$tiempoFinSemana=($filaSolicitud['minutos_utilizados']/1.36)*0.36;
		$tiempoFinSemana=$cc->devolverFormatoDiasDisponibles(round($tiempoFinSemana));
		
		
		//$filaSolicitud['minutos_utilizados'] = $filaSolicitud['minutos_utilizados']/1.27;
		$filaSolicitud['minutos_utilizados'] = $filaSolicitud['minutos_utilizados']/1.36;
		
		$dias=floor(intval($filaSolicitud['minutos_utilizados'])/480);
		$horas=floor((intval($filaSolicitud['minutos_utilizados'])-$dias*480)/60);
		$minutos=(intval($filaSolicitud['minutos_utilizados'])-$dias*480)-$horas*60;
		

		$diasDescontados = $cc->devolverFormatoDiasDisponibles($filaSolicitud['minutos_utilizados']);
		
		//Días fin de semana
		//$diasFinSemana = $filaSolicitud['minutos_utilizados']*0.27;
		$diasFinSemana = $filaSolicitud['minutos_utilizados']*0.36;
		
		$dias=floor(intval($diasFinSemana)/480);
		$horas=floor((intval($diasFinSemana)-$dias*480)/60);
		$minutos=(intval($diasFinSemana)-$dias*480)-$horas*60;
		
		$diasFinSemana = $dias .' días, '.$horas.' horas, '.$minutos.' minutos';
		
		//Obtener listado de saldos de tiempo por año del funcionario
		//$dias=floor(intval($tiempoDisponible['minutos_disponibles'])/480);
		//$horas=floor((intval($tiempoDisponible['minutos_disponibles'])-$dias*480)/60);
		//$minutos=(intval($tiempoDisponible['minutos_disponibles'])-$dias*480)-$horas*60;
		
		$diasSaldo='';
		if($filaSolicitud['minutos_actuales'] != ''){
			$diasSaldo=$cc->devolverFormatoDiasDisponibles($filaSolicitud['minutos_actuales']);
			
		//$dias=floor(intval($filaSolicitud['minutos_actuales'])/480);
		//$horas=floor((intval($filaSolicitud['minutos_actuales'])-$dias*480)/60);
		//$minutos=(intval($filaSolicitud['minutos_actuales'])-$dias*480)-$horas*60;
		//$diasSaldo = 'le restan '. $dias .' días, '.$horas.' horas, '.$minutos.' minutos';
		$diasSaldo = 'le restan '.$diasSaldo;
		}
		
		$anioSaldo = $tiempoDisponible['anio'];
		
		//Obtener destino de comisión local o provincial
		$lugar = $filaSolicitud['destino_comision'];
		
		
		/*$filaSolicitud['texto_accion_personal'];*/
		//echo $subtipos['texto_accion_personal'];
		
		$texto = $subtipos['texto_accion_personal'];
		
		$texto = str_replace('$fechaInicio', $fechaInicio, $texto);
		$texto = str_replace('$horaInicio', $horaInicio, $texto);
		$texto = str_replace('$fechaFin', $fechaFin, $texto);
		$texto = str_replace('$horaFin', $horaFin, $texto);
		$texto = str_replace('$fechaSuceso', $fechaSuceso, $texto);
		$texto = str_replace('$horaSuceso', $horaSuceso, $texto);
		$texto = str_replace('$diasSaldo', $diasSaldo, $texto);
		$texto = str_replace('$diasFinSemana', $diasFinSemana, $texto);
		$texto = str_replace('$diasDescontados', $diasDescontados, $texto);
		$texto = str_replace('$anioSaldo', $anioSaldo, $texto);
		$texto = str_replace('$lugar', $lugar, $texto);
		$texto = str_replace('$mesPermiso', $mesPermiso, $texto);
		$texto = str_replace('$anioPermiso', $anioPermiso, $texto);
		$texto = str_replace('$tiemposDescontado', $tiemposDescontado, $texto);	
		$texto = str_replace('$tiempoFinSemana', $tiempoFinSemana, $texto);
		
		//echo $texto;
		$textoAccionPersonal = $texto;
		
		///JASPER///
		
		//Ruta del reporte compilado por Jasper y generado por IReports
		
		$jru = new ControladorReportes();
		
		$filename = $filaSolicitud['identificador'].'-AccionPersonal-'.$id_registro.'.pdf';
		$ReporteJasper='aplicaciones/vacacionesPermisos/reportes/accionPersonal.jrxml';
		$salidaReporte = 'aplicaciones/vacacionesPermisos/accionPersonal/'.$filename;
		
		$parameters['parametrosReporte'] = array(
			'identificador'=> $identificadorFuncionario,
			'codigoAccionPersonal'=> $codigoAccionPersonal,
			'textoAccionPersonal'=> $textoAccionPersonal,
			'nombreResponsableTH'=> $nombreResponsableTH,
			'cargoResponsableTH'=> $cargoResponsableTH,
			'nombreDirector'=> $nombreDirector,
			'cargoDirector'=> $cargoDirector,
			'rigeDesde'=> $fechaInicio,
			'resolucion'=> ' '
		);
		
		if($codigoSubtipoPermiso == "VA-VA"){
			$parameters['parametrosReporte'] += array('vacaciones'=> ' ');
		}else{ /*if($codigoSubtipoPermiso == 'PE-PIV')*/
				$parameters['parametrosReporte'] += array('licencia'=> ' ');
		}
		
		//CAMBIAR RUTA IMAGEN A accionPersonal
		$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'defecto');
		$cc->actualizarRutaDocumento($conexion, $id_registro, $salidaReporte);
		$cc->actualizarEstadoPermiso($conexion, $id_registro, 'InformeGenerado');
		$cc->actualizarNumeroAccionPersonal($conexion, $id_registro, $numeroAccionPersonal);
		
		//Registro de observaciones del proceso
		$cc->agregarObservacion($conexion, 'El usuario '.$_SESSION['usuario'].' ha creado la acción de personal para la solicitud de '.$filaSolicitud['descripcion_subtipo'].' con fecha de salida '
				.$filaSolicitud['fecha_inicio'].', fecha de retorno '.$filaSolicitud['fecha_fin'].' y con '.$filaSolicitud['minutos_utilizados'].' minutos solicitados', $id_registro, $_SESSION['usuario']);
		

		$mensaje['estado'] = 'exito';
		$mensaje['mensaje'] = 'Los datos han sido actualizados satisfactoriamente.';


		$conexion->desconectar();
		echo json_encode($mensaje);
	} catch (Exception $ex){		
		pg_close($conexion);
		$mensaje['estado'] = 'error';
		$mensaje['mensaje'] = "Error al ejecutar sentencia";
		echo json_encode($mensaje);
	}
} catch (Exception $ex) {
	$mensaje['estado'] = 'error';
	$mensaje['mensaje'] = 'Error de conexión a la base de datos';
	echo json_encode($mensaje);
}
?>