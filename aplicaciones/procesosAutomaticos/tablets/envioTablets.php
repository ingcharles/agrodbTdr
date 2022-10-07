<?php
require_once '../../../clases/Conexion.php';
require_once '../../../clases/ControladorMonitoreo.php';
require_once '../../../clases/ControladorTablets.php';
require_once '../../../clases/ControladorRegistroOperador.php';
require_once '../../../clases/ControladorRevisionSolicitudesVUE.php';

if($_SERVER['REMOTE_ADDR'] == ''){
//if(1){
	$conexion = new Conexion();
	$ct = new ControladorTablets();
	$cm = new ControladorMonitoreo();
	$crs = new ControladorRevisionSolicitudesVUE();
	$cro = new ControladorRegistroOperador();

	$resultadoMonitoreo = $cm->obtenerCronPorCodigoEstado($conexion, 'CRON_TABLET_POST');

	if($resultadoMonitoreo){
	//if(1){
		define('IN_MSG','<br/> >>> ');

		$fecha = date("Y-m-d h:m:s");
		echo IN_MSG .' INICIO '. $fecha;

		//OBTENER LOS REGISTROS A SER ENVIADOS AL ESQUEMA DE REVISIÓN DE SOLICITUDES
		$datosPorEnviar = $ct->obtenerRegistrosTabletsPorEstado($conexion, 'COMPLETO', 'POR ENVIAR');

		while ($fila = pg_fetch_assoc($datosPorEnviar)){
				
			$idInspeccion = $fila['id_inspeccion'];
			$identificadorTablet = $fila['identificador_tablet'];
			$versionBD =  $fila['version_bd'];
			$inspector = $fila['identificador_inspector'];
			$fechaInspeccion = $fila['fecha'];
			$resultadoInspeccion = $fila['resultado'];
			$tipoSolicitud = 'Operadores';
			$tipoInspector = 'Técnico';
			$serial = $fila['serial'];
				
			echo IN_MSG . 'Número de inspección '.$idInspeccion. ' Identificador tablet '.$identificadorTablet. ' Versión base de datos '.$versionBD, ' Serial '.$serial;
			
			$ct->actualizarEstadoInspeccionTablet($conexion, $idInspeccion, $identificadorTablet, $versionBD, $serial, 'W');
				
			//REVISAR EL ESTADO DE LA INSPECCION
			switch ($resultadoInspeccion){
				case 'APROBADO':
					$estadoInspeccion = 'registrado';
					break;

				case 'APROBADO_OBSERVACION':
					$estadoInspeccion = 'registradoObservacion';
					break;

				case 'RECHAZADO':
					$estadoInspeccion = 'noHabilitado';
					break;

				default:
					$estadoInspeccion = 'error';
					break;

			}
				
			//OBTENER REGISTROS PARA CREAR EL GRUPO DE SOLICITUDES.
				
			echo IN_MSG . 'Obtener registros de agrupación de tablets.';
				
			$grupoSolicitudesTablet = $ct->obtenerRegistrosTabletsGrupo($conexion, $idInspeccion, $identificadorTablet, $versionBD, $serial);

			$agrupacionOperaciones = '';
				
			//CREAR REGISTRO DE GRUPO DE SOLICITUDES
			foreach ($grupoSolicitudesTablet as $grupo){
				$agrupacionOperaciones .= "'".$grupo['idOperacion']."',";
			}
			$agrupacionOperaciones = "(".rtrim($agrupacionOperaciones,',').")";
			
			//VERIFICACION SI ES PROCESO DE REVISION DE PRODUCTOR DE BANANO - MUSACEAS
			echo IN_MSG . 'Verificacion proceso  de inspeccion de productor banano musaceas.';
			
			$verificacionOperacionBanano = $cro->verificarOperacionesBananoPostRegistro($conexion, $agrupacionOperaciones , "('PRB')", 'SV');
			
			if(pg_num_rows($verificacionOperacionBanano) == 0){
					//CREAR EL REGISTRO DE ASIGNADO INSPECCION EN ESQUEMA DE REVISIÓN DE SOLICITUDES.
					
					echo IN_MSG . 'Creación de asignación de operador';
					
					$inspectorAsignado= $crs->guardarNuevoInspector($conexion, $inspector, $inspector, $tipoSolicitud, $tipoInspector);
					$idInspeccionAsignado = pg_fetch_assoc($inspectorAsignado);
					
				echo IN_MSG . 'Obtener operaciones por area de operación.';
				$qAreasOperacion = $cro->buscarOperacionesPorAreasDeOperacion($conexion, $agrupacionOperaciones);

				while ($areasOperacion = pg_fetch_assoc($qAreasOperacion)){
					echo IN_MSG . 'Actualización de operación '.$areasOperacion['id_operacion'].' al estado '.$estadoInspeccion;
					$crs->guardarGrupo($conexion, $areasOperacion['id_operacion'],$idInspeccionAsignado['id_grupo'], $tipoInspector);
					$cro ->cambiarEstadoAreaXidSolicitud($conexion, $areasOperacion['id_operacion'], $estadoInspeccion, 'Estado actualizado por inspección post registro en tablets el '.$fechaInspeccion);																																									 
					$cro->enviarOperacionEstadoAnterior($conexion, $areasOperacion['id_operacion']);
					$cro -> actualizarEstadoOperacion($conexion, $areasOperacion['id_operacion'],$estadoInspeccion, 'Estado actualizado por inspección post registro en tablets el '.$fechaInspeccion);
																																														 
					$verificacionOperacionViverista = $cro->verificarOperacionesBananoPostRegistro($conexion, "(".$areasOperacion['id_operacion'].")" , "('PRP','VVE','ALM','MIM')", 'SV');
					
					//PROCESO DE ACTUALIZACIÓN PARA LA GENERACIÓN DEL CERTIFICADO
					if(pg_num_rows($verificacionOperacionViverista) != 0 && ($estadoInspeccion == 'registrado' || $estadoInspeccion == 'registradoObservacion')){
						$cro->cambiarEstadoActualizarCertificadoPorIdentificadorOperacion($conexion, $areasOperacion['id_operacion'], 'SI');
						
					}
				}
					
				//OBTENER ID DE AREA
				$qCodigoArea = $cro->buscarIdAreasPorGrupoOperacion($conexion, $agrupacionOperaciones);
				while($idArea = pg_fetch_assoc($qCodigoArea)){

					$cantidadOperacionesPorArea = pg_fetch_result($cro->buscarCantidadOperacionesPorIdAreas($conexion, $idArea['id_area']), 0, 'cantidad');

					$agrupacionObservacion = '';
					$agrupacionObservacion = str_replace("'","",'Estado actualizado por inspección post registro en tablets el '.$fechaInspeccion.' Con las operaciones '.$agrupacionOperaciones);

					if(strlen($agrupacionObservacion)>=1020){
						$agrupacionObservacion = substr($agrupacionObservacion, 0,1020);
					}


					if($cantidadOperacionesPorArea == 0){
						//Proceso de inactivacion o activacion de área.
						echo IN_MSG . 'Actualización de área '.$idArea['id_area'].' al estado inactivo';
						$cro->enviarAreas($conexion, $idArea['id_area'], 'inactivo', $agrupacionObservacion);
					}else{
						echo IN_MSG . 'Actualización de área '.$idArea['id_area'].' al estado activo';
						$cro->enviarAreas($conexion, $idArea['id_area'], 'creado', $agrupacionObservacion);
					}

				}
					
				//OBTENER ORDEN DE INSEPCCIÓN
				$ordenInspeccion = $crs->buscarSerialOrden($conexion, $idInspeccionAsignado['id_grupo'], $tipoInspector);
				$orden = pg_fetch_assoc($ordenInspeccion);
					
				//CREAR EL REGISTRO DE REVISIÓN DE INSPECCIÓN.
				echo IN_MSG . 'Ingreso de datos de inspección';
				$idInspeccionRegistro =  $crs->guardarDatosInspeccionTablets($conexion, $idInspeccionAsignado['id_grupo'], $inspector, $fechaInspeccion, $estadoInspeccion, $orden['orden'], $idInspeccion, $identificadorTablet, $versionBD, $serial);
					
				//OBTENER REGISTROS PARA CREAR LAS OBSERVACIONES.
				echo IN_MSG . 'Ingreso de observaciones de inspección';
				$observacionesTablets = $ct->obtenerRegistrosTabletsObservaciones($conexion, $idInspeccion, $identificadorTablet, $versionBD, $serial);
					
				//CREAR LOS REGISTROS DE OBSERVACIÓN.
				$agrupacionObservacion = '';
				if(pg_num_rows($observacionesTablets)!= 0){
					while ($observacion = pg_fetch_assoc($observacionesTablets)){
						//foreach ($grupoSolicitudesTablet as $grupoOperacion){
						$observacionOperador = str_replace("'", "''", $observacion['observacion']);
						$idOperacion = pg_fetch_result($cro->buscarMaximoOperacionPorArea($conexion, $observacion['id_area']), 0, 'id_operacion');
						$crs->guardarDatosInspeccionObservaciones($conexion, pg_fetch_result($idInspeccionRegistro, 0, 'id_inspeccion'), $observacion['id_area'], $observacionOperador, 'Área',  $idOperacion);
						$agrupacionObservacion .= $observacion['observacion'].", ";
						//}
					}
				}
					
				$agrupacionObservacion = rtrim('Inspección post registro en tablets: '.$agrupacionObservacion,', ');
					
				if(strlen($agrupacionObservacion)>= 1020){
					$agrupacionObservacion = substr($agrupacionObservacion,0,1020);
				}
				
				$agrupacionObservacion = str_replace("'", "''", $agrupacionObservacion);
				
				echo IN_MSG . 'Actualización de observación en tabla de inspección.';
				$crs->actualizarObservacionTablets($conexion, pg_fetch_result($idInspeccionRegistro, 0, 'id_inspeccion'), $agrupacionObservacion);
				
			}else{
				echo IN_MSG . 'Ingreso de operación de tipo productor bananero - musáceas, no se realiza cambio de estado de operación ni ingreso de información de proceso de inspección en esquema de revisión de formularios.';
			}
			
			
			$ct->actualizarEstadoInspeccionTablet($conexion, $idInspeccion, $identificadorTablet, $versionBD, $serial, 'FINALIZADO');
			echo IN_MSG . 'FIN';
				
		}
	}
}else{

	$minutoS1=microtime(true);
	$minutoS2=microtime(true);
	$tiempo=$minutoS2-$minutoS1;
	$xcadenota = "FECHA ".date("d/m/Y")." ".date("H:i:s");
	$xcadenota.= "; IP REMOTA ".$_SERVER['REMOTE_ADDR'];
	$xcadenota.= "; SERVIDOR HTTP ".$_SERVER['HTTP_REFERER'];
	$xcadenota.= "; SEGUNDOS ".$tiempo."\n";
	$arch = fopen("../../../aplicaciones/logs/cron/tablets_".date("d-m-Y").".txt", "a+");
	fwrite($arch, $xcadenota);
	fclose($arch);

}
?>