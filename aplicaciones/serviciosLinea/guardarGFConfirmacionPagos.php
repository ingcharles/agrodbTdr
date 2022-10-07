<?php
session_start ();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosLinea.php';
require_once '../../clases/ControladorMail.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../general/PHPExcel.php';

$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$csl = new ControladorServiciosLinea();
	$cMail = new ControladorMail();
	$cc = new ControladorCatastro();

	try {
		$fecha = htmlspecialchars ( $_POST['fecha'], ENT_NOQUOTES, 'UTF-8' );
		$localizacion = htmlspecialchars ( $_POST['localizacion'], ENT_NOQUOTES, 'UTF-8' );
		$identificadorResponsable = htmlspecialchars ( $_POST['identificadorResponsable'], ENT_NOQUOTES, 'UTF-8' );
		$rutaExcel = htmlspecialchars ( $_POST['archivo'], ENT_NOQUOTES, 'UTF-8' );
		$archivo=str_replace('aplicaciones/serviciosLinea/', '', $rutaExcel);

		if (!file_exists($archivo)) {
			$mensaje ['mensaje'] = 'Archivo excel no encontrado....!!.\n';
			echo json_encode($mensaje);
			exit();
		}
			$objPHPExcel = PHPExcel_IOFactory::load($archivo);
			$objPHPExcel->setActiveSheetIndex(0);
			$sheet = $objPHPExcel->getActiveSheet();
			$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
				
			$numCurV=$objPHPExcel->getActiveSheet()->getCell('A3')->getValue();
			$descripcionV=$objPHPExcel->getActiveSheet()->getCell('B3')->getValue();
			$identificadorBeneficiarioV=$objPHPExcel->getActiveSheet()->getCell('C3')->getValue();
			$nombreBeneficiarioV=$objPHPExcel->getActiveSheet()->getCell('D3')->getValue();
			$fechaPagoV=$objPHPExcel->getActiveSheet()->getCell('E3')->getValue();
			$montoPagoV=$objPHPExcel->getActiveSheet()->getCell('F3')->getValue();
			$bancoV=$objPHPExcel->getActiveSheet()->getCell('G3')->getValue();
				
			if($numCurV=='NO. CUR' && $descripcionV=='DESCRIPCION' && $identificadorBeneficiarioV=='RUC' &&
					$nombreBeneficiarioV=='NOMBRE BENEFICIARIO' && $fechaPagoV=='FECHA PAGO' &&
					$montoPagoV=='MONTO PAGADO' &&	$bancoV=='BANCO'){
				
				$conexion->ejecutarConsulta("begin;");
				$res = $csl->verificarArchivoExistente($conexion, $localizacion, $fecha);
				if(pg_num_rows($res)!=0)
					$csl->actualizarEstadoConfirmacionPago($conexion, $localizacion, $fecha, 'inactivo', $identificadorResponsable);
					
				$qConfirmacionPago=$csl->guardarNuevaConfirmacionPago($conexion, $identificadorResponsable, $localizacion, $rutaExcel, $fecha);
				$idConfirmacionPago=pg_fetch_result($qConfirmacionPago, 0, 'id_confirmacion_pago');
				$highestRow = $sheet->getHighestRow();
				$banderaRUC=false;
				$banderaDecimal=false;
				$banderaPaso=false;
				
				for($i=4; $i<=$highestRow;$i++){
					$filaRegistro=$i;
					$numCur=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
					$descripcion=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
					$caracteres = array(",", "'", '"');
					$descripcion = str_replace($caracteres, " ",$descripcion);
					$identificadorBeneficiario=$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
					
					if(pg_num_rows($cc->obtenerDatosUsuarioAgrocalidad($conexion, $identificadorBeneficiario))==0){
						$banderaRUC=true;
						$msj="El funcionario con cédula ".$identificadorBeneficiario." no está registrado...!!";
					}

					if(strlen($identificadorBeneficiario)!=10){
						$banderaRUC=true;
						$msj="En el campo RUC el número ".$identificadorBeneficiario." ingresado no es valido...!!!";
					}
					
					$nombreBeneficiario=$objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
					$fechaPago=$objPHPExcel->getActiveSheet()->getCell('E'.$i)->getValue();
					$fechaPago = PHPExcel_Style_NumberFormat::toFormattedString($fechaPago, "dd/mm/yyyy");
					$fechaPago=substr($fechaPago,0,10);
					$montoPago=$objPHPExcel->getActiveSheet()->getCell('F'.$i)->getValue();
					$bits = explode(",",$montoPago); 
					$first = strlen($bits[0]); 
					$last = strlen($bits[1]); 
					
					if($numCur=='' || $descripcion=='' || $identificadorBeneficiario==''
							|| $nombreBeneficiario=='' || $fechaPago=='' || $montoPago==''){
						$banderaRUC=true;
						$msj="Algún campo se encuentra vacio - FILA ".$filaRegistro;
							
					}
					
					if(count($bits)<=2){
						
						if ($last <3 && $last >1){
							if($first<6){
								$decimal= str_replace('.',"",$bits[1]);
								$entero= str_replace('.',"",$bits[0]);
								$valor=$entero.'.'.$decimal;
								$montoPago=$valor;
							}else{
								$banderaDecimal=true;
								$msj="En el campo monto pago existe un valor entero con más de cinco digitos - FILA ".$filaRegistro;
							}
							
						}else if($last ==0){
							$uno = explode(".",$bits[0]);
							$entero= str_replace('.',"",$uno[0]);
							$first = strlen($entero);
							$decimal=$uno[1];
							$decimal= str_pad($decimal, 2, "0", STR_PAD_RIGHT);
							if($first<6){
								$valor=$entero.'.'.$decimal;;
								$montoPago=$valor;
							}else{
								$banderaDecimal=true;
								$msj="En el campo monto pago existe un valor entero con más de cinco digitos - fila ".$filaRegistro;
							}
						}else{
							$banderaDecimal=true;
							$msj="En el campo monto pago existe un valor decimal con más de dos digitos - fila ".$filaRegistro;
						}
					}else{
						$banderaDecimal=true;
						$msj="En el campo monto pago existe un valor con dos o mas comas(,) por favor revisar - fila ".$filaRegistro;
					}
					
					$banco=$objPHPExcel->getActiveSheet()->getCell('G'.$i)->getValue();

					
					
					if($banderaRUC || $banderaDecimal){
						$banderaPaso=true;
					}else{
						
						$csl->guardarNuevoDetalleConfirmacionPagos($conexion, $idConfirmacionPago, $numCur, $descripcion, $identificadorBeneficiario, $nombreBeneficiario, $fechaPago, $montoPago, $banco);
					}
					//$filaRegistro++;					
				}

				$asunto = 'CONFIRMACIÓN DE PAGOS AGROCALIDAD';
				$codigoModulo='PRG_SERVI_LINEA';
				$tablaModulo='g_servicios_linea.detalle_confirmacion_pagos';
				$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
				$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
				$date=$dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " de ".date('Y') ;
				
				$qIdentificadorresBeneficiarios=$csl->buscarIdentificadorBeneficiarioMatriz($conexion, $idConfirmacionPago);
				while($ident=pg_fetch_assoc($qIdentificadorresBeneficiarios)){		
					$idSolicitudCorreo='';
					$qMatriz=$csl->buscarTipoPagoPorIdMatrizUsuario($conexion, $idConfirmacionPago, $ident['identificador_beneficiario']);
					while($filas=pg_fetch_assoc($qMatriz)){
						
						$cuerpoMensaje= '<html xmlns="http://www.w3.org/1999/xhtml"><body style="margin:0; padding:0;">
							<style type="text/css">
							.titulo  {
									margin-top: 30px;
								width: 800px;
								text-align: center;
								font-size: 14px;
								font-weight: bold;
									font-family:Times New Roman;
							}
							.lineaDos{
								font-style: oblique;
							    font-weight: normal;
							}	
							.lineaLeft{
							   text-align: left;
							}
							.lineaEspacio{
								height: 35px;
							}	
							.lineaEspacioMedio{
								height: 50px;
							}
							.espacioLeft{
								padding-left: 15px;
							}
							</style>';
						$cuerpoMensaje.='<table class="titulo">
											<thead>
												<tr><th>Gestión Financiera da a conocer que usted ha recibido un pago.</th></tr>
											</thead>
											<tbody>
												<tr><td class="lineaDos lineaEspacio">Por favor ingrese al Módulo de Servicios en Línea en el Sistema GUIA para consultar el detalle de los montos recibidos.</td>	</tr>
												<tr><td class=""><a href="https://guia.agrocalidad.gob.ec">guia.agrocalidad.gob.ec</a></td></tr>
											</tbody>
											<tfooter>
												<tr><td class="lineaEspacioMedio"></td></tr>
												<tr><td class="lineaDos lineaLeft espacioLeft">Dirección de Tecnologías de Información y Comunicación</td></tr>
												<tr><td class="lineaDos lineaLeft espacioLeft">'.$date.'</td></tr>
											</tfooter>
										</table>';

						
						$idSolicitudCorreo.=$filas['id_detalle_confirmacion_pago'].', ';
					}
					
					$fila=pg_fetch_assoc($csl->obtenerDatosUsuarioAgrocalidad($conexion, $ident['identificador_beneficiario']));

					$destinatarios = array();
					if($fila['mail_institucional']!= ''){
						$destinatarios  = explode('; ',$fila['mail_institucional']);

					}else if($fila['mail_personal'] !=''){
						$destinatarios  = explode('; ',$fila['mail_personal']);
					}

					$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, rtrim($idSolicitudCorreo,', '));
					$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');

					$cMail->guardarDestinatario($conexion, $idCorreo,$destinatarios);
			
				}
				
				if($banderaPaso){
					$mensaje ['estado'] = 'error';
					$mensaje ['mensaje'] = $msj;
				}else{
					$mensaje ['estado'] = 'exito';
					$mensaje ['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
					$conexion->ejecutarConsulta("commit;");
				}
			}else{
				$mensaje ['estado'] = 'error';
				$mensaje ['mensaje'] = 'El archivo no tiene el formato correcto...!!!';
			}
	} catch (Exception $ex) {
		$conexion->ejecutarConsulta("rollback;");
		$mensaje['mensaje'] = $ex->getMessage();
		$mensaje['error'] = $conexion->mensajeError;
	} finally {
		$conexion->desconectar();
	}
} catch (Exception $ex) {
	$mensaje['mensaje'] = $ex->getMessage();
	$mensaje['error'] = $conexion->mensajeError;
} finally {
	echo json_encode($mensaje);
}
?>