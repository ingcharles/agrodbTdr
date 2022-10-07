<?php
require_once '../general/fpdf.php';
require_once '../../clases/Conexion.php';
require_once 'PHPExcel.php';
require_once '../../clases/ControladorMail.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorAreas.php';
require_once '../../clases/ControladorCatastro.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$cv = new ControladorVacaciones();
	$cMail = new ControladorMail();	
	$ca = new ControladorAreas();
	$cat = new ControladorCatastro();
	
	
	$mes = htmlspecialchars ( $_POST['mes'], ENT_NOQUOTES, 'UTF-8' );
	$ano = htmlspecialchars ( $_POST['ano'], ENT_NOQUOTES, 'UTF-8' );
	$rutaArchivoExcel = htmlspecialchars ( $_POST['archivo'], ENT_NOQUOTES, 'UTF-8' );
	
	switch ($mes){
					case 'Enero':
						$fechaSalida = $ano.'-01-01';
						$fechaRetorno =$ano.'-01-30';
					break;
					case 'Febrero':
						$fechaSalida = $ano.'-02-01';
						$fechaRetorno =$ano.'-02-28';
					break;
					case 'Marzo':
						$fechaSalida = $ano.'-03-01';
						$fechaRetorno =$ano.'-03-30';
					break;
					case 'Abril':
						$fechaSalida = $ano.'-04-01';
						$fechaRetorno =$ano.'-04-30';
					break;
					case 'Mayo':
						$fechaSalida = $ano.'-05-01';
						$fechaRetorno =$ano.'-05-30';
					break;
					case 'Junio':
						$fechaSalida = $ano.'-06-01';
						$fechaRetorno =$ano.'-06-30';
					break;
					case 'Julio':
						$fechaSalida = $ano.'-07-01';
						$fechaRetorno =$ano.'-07-30';
					break;
					case 'Agosto':
						$fechaSalida = $ano.'-08-01';
						$fechaRetorno =$ano.'-08-30';
					break;
					case 'Septiembre':
						$fechaSalida = $ano.'-09-01';
						$fechaRetorno =$ano.'-09-30';
					break;
					case 'Octubre':
						$fechaSalida = $ano.'-10-01';
						$fechaRetorno =$ano.'-10-30';
					break;
					case 'Noviembre':
						$fechaSalida = $ano.'-11-01';
						$fechaRetorno =$ano.'-11-30';
					break;
					case 'Diciembre':
						$fechaSalida = $ano.'-12-01';
						$fechaRetorno =$ano.'-12-30';
					break;					
	}
	
	try {
		set_time_limit(1000);
		$archivo=str_replace('aplicaciones/vacacionesPermisos/', '', $rutaArchivoExcel);

		// Check prerequisites
		if (!file_exists($archivo)) {
			$mensaje ['mensaje'] = 'Archivo excel no encontrado....!!.\n';
			echo json_encode($mensaje);
			exit();
		}
      
		if(pg_num_rows($cv->buscarExcelDescuentos ($conexion, $mes, $ano))==0){
		$conexion->ejecutarConsulta("begin;");
		$nombreArchivoExcel=str_replace('aplicaciones/vacacionesPermisos/archivosDescuentos/', '', $rutaArchivoExcel);

		$guardarDescuentos=$cv->guardarNuevoExcelDescuento($conexion, $mes, $ano, $rutaArchivoExcel,$nombreArchivoExcel);
		
		$objPHPExcel = PHPExcel_IOFactory::load($archivo);
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

		$highestRow = $sheet->getHighestRow();
		
		$arrayCedula=array();
		$fila=0;
		
		for($i=2;$i<=$highestRow;$i++){
			$cedula='';
			$minutos_utilizados=0;
			$cedula=$objPHPExcel->getActiveSheet()->getCell('A'.$i);			
			
			$minutos_utilizados =abs(intval($objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue()));	
			
			$minutos_utilizados+= round($minutos_utilizados*0.36);
			$minutos_utilizados = round($minutos_utilizados);
			
			$time = time();
			$fechaActual=strtotime(date("Y-m-d H:i:s", $time));
			
			$fechaSalida = new DateTime($fechaSalida);
			date_time_set($fechaSalida,'08','00');
			
			$fechaRetorno = new DateTime($fechaRetorno);
			date_time_set($fechaRetorno,'17','00');
			
            $fechaSalida=date_format($fechaSalida, 'Y-m-d H:i:s');
			$fechaRetorno=date_format($fechaRetorno, 'Y-m-d H:i:s');
					//$cedula=='1104724149' || 
		//	if(1 ){
			if($cedula=='0201798907' || $cedula=='0301992756' ){
			if(1 ){	
				//Área de usuario para revisión y aprobación de jefe inmediato
				$areaUsuario = pg_fetch_assoc($ca->areaUsuario($conexion, $cedula));
				$resultadoConsulta=$cd->devolverJefeImnediato($conexion, $cedula);
				$idAreaPermiso=$resultadoConsulta['idarea'];
				$idAreaJefe=$resultadoConsulta['idareajefe'];
				$identificadorJefe=$resultadoConsulta['identificador'];
							
					if($minutos_utilizados > 0 and $cedula != ''){
					//	if(0){
						$fila=$cv->nuevoPermisoDescuento($conexion,30,$fechaSalida,$fechaRetorno,'08:00','17:00',$cedula,$minutos_utilizados,$fechaSalida,0,'',$fechaSalida, $idAreaPermiso, '', 'hora');
						$permiso = pg_fetch_assoc($fila);															
						$id_permiso = $permiso['id_permiso_empleado'];							
						$cv->identificadorJefeSuperior($conexion, $identificadorJefe, $id_permiso, $idAreaJefe);					
						//Registro de observaciones del proceso
						$msg='El usuario '.$identificador.' ha creado la solicitud de '.$subtipoPermiso['descripcion_subtipo'].' con fecha de salida '
								.$fechaSalida.', fecha de retorno '.$fechaRetorno.' y con '.$minutos_utilizados.' minutos solicitados';
						$cv->agregarObservacion($conexion, $msg, $id_permiso, $cedula);					
						
						$cv->actualizarSaldosFuncionario($conexion,$cedula,$minutos_utilizados, $id_permiso);
						//------------------------------------------------------------------------
						$respuesta=$cv->consultarSaldoFuncionario($conexion,$cedula);
						$minutos = pg_fetch_assoc($respuesta);
						
						$respuesta=$cv->actualizarMinutosActuales($conexion,$id_permiso,$minutos['minutos_disponibles']);
					
					$tiempoDescontado = pg_fetch_result($cv->obtenerTiempoPermisoSolicitado($conexion, $id_permiso), 0, 'minutos_utilizados');					
					$tiempoFinSemana=($tiempoDescontado/1.36)*0.36;
					$tiempoFinSemana=$cv->devolverFormatoDiasDisponibles(round($tiempoFinSemana));
					$tiempoDescontado=$cv->devolverFormatoDiasDisponibles($tiempoDescontado);
					
						
				$asunto = 'AGROCALIDAD';
				
				$familiaLetra = "font-family:'Text Me One', 'Segoe UI', 'Tahoma', 'Helvetica', 'freesans', 'sans-serif'";
				$letraCodigo = "font-family:'Segoe UI', 'Helvetica'";
				
				$cuerpoMensaje = '<table><tbody>
				<tr><td style="'.$familiaLetra.'; text-align:center; font-size:30px; color:rgb(255,206,0); font-weight:bold; text-transform:uppercase;">Sistema GUIA <span style="color:rgb(19,126,255);">te </span> <span style="color:rgb(204,41,44);">saluda</span></td></tr>
				<tr><td style="'.$familiaLetra.'; padding-top:2px; font-size:17px; color:rgb(19,126,255); font-weight:bold;"></td></tr>
				<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:20px; color:rgb(46,78,158);font-weight:bold;">DESCUENTOS DEL MES DE '.strtoupper($mes).' DEL '.$ano.'</td></tr>
				<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Estimad@,</tr>
				<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Se descuenta '.$tiempoDescontado.' de las vacaciones por exceder la jornada laboral de hora de almuerzo (hora de entrada), y de ellos '.$tiempoFinSemana.' corresponden a vacaciones de fines de semana. <span style="color:rgb(255,192,0);font-weight:bold;" >Estos descuentos sólo se realizan si tú NO cumples con la jornada laboral, es tu responsabilidad RESPETAR el horario de trabajo.</span> Recuerde qué esta información lo tiene disponible en:<br> <span style="color:rgb(46,78,158); font-weight:bold;">Sistema GUIA</span> >>> <span style="color:rgb(46,78,158); font-weight:bold;">Permisos y Vacaciones</span> >>> <span style="color:rgb(46,78,158); font-weight:bold;">Descuento vacaciones.</span> </td></tr>
				<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;"><span style="'.$familiaLetra.'; padding-top:20px; font-size:14px; color:rgb(204,41,44);font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
				<tr><td style="'.$familiaLetra.'; padding-top:25px; font-size:14px;color:#2a2a2a;">Atentamente, <br>Dirección General de Administración de Talento Humano</td></tr>
				</tbody></table>';
				
				$fila=pg_fetch_assoc($cat->obtenerDatosUsuarioAgrocalidad($conexion, $cedula));
				$destinatario = array();
				if($fila['mail_institucional']!= ''){
					array_push($destinatario, $fila['mail_institucional']);
				}else if($fila['mail_personal'] !=''){
					array_push($destinatario, $fila['mail_personal']);
				}						
				//array_push($destinatario, 'david.rodriguez@agrocalidad.gob.ec');
				
				$arrayAdjunto=array();
		  		$arrayAdjunto[]= "";
		  		//$estadoMail = $cMail->enviarMail($destinatario, $asunto, $cuerpoMensaje);

			     }
			}
			//-------
			}
            //------
		}
		$mensaje ['estado'] = 'exito';
		$mensaje ['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
		$conexion->ejecutarConsulta("commit;");
		}else{
			$mensaje ['estado'] = 'error';
			$mensaje ['mensaje'] = 'El mes de '.$mes.' del año '.$ano.' ya existe....!!!';			
		}
	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] =$ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	} finally {
		$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['mensaje'] =$ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
} finally {
	echo json_encode($mensaje);
}
?>