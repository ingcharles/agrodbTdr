<?php
require_once '../general/fpdf.php';
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';
require_once '../general/PHPExcel.php';
require_once '../../clases/ControladorMail.php';


$mensaje = array ();
$mensaje ['estado'] = 'error';
$mensaje ['mensaje'] = 'Ha ocurrido un error!';

try {
	$conexion = new Conexion ();
	$ca = new ControladorCatastro();
	$cMail = new ControladorMail();
	
	$mes = htmlspecialchars ( $_POST['mes'], ENT_NOQUOTES, 'UTF-8' );
	$ano = htmlspecialchars ( $_POST['ano'], ENT_NOQUOTES, 'UTF-8' );
	$area = htmlspecialchars ( $_POST['area'], ENT_NOQUOTES, 'UTF-8' );
	$descrip = htmlspecialchars ( $_POST['descrip'], ENT_NOQUOTES, 'UTF-8' );
	$rutaArchivoExcel = htmlspecialchars ( $_POST['archivo'], ENT_NOQUOTES, 'UTF-8' );
	$nombreAgro='AGENCIA DE REGULACIÓN Y CONTROL FITO Y ZOOSANITARIO'; //----cambio-------------------

	try {
		set_time_limit(2000);

		$archivo=str_replace('aplicaciones/uath/', '', $rutaArchivoExcel);

		// Check prerequisites
		if (!file_exists($archivo)) {
			$mensaje ['mensaje'] = 'Archivo excel no encontrado....!!.\n';
			echo json_encode($mensaje);
			exit();
		}
      
		//if(pg_num_rows($ca->buscarExcelRolPagos ($conexion, $mes, $ano, $area))==0){
		if(1){
		
		$conexion->ejecutarConsulta("begin;");
		$nombreArchivoExcel=str_replace('aplicaciones/uath/archivosRolPagos/excel/', '', $rutaArchivoExcel);

		$qGuardarNuevoExcelRolPagos=$ca->guardarNuevoExcelRolPagos($conexion, $mes, $ano, $rutaArchivoExcel,$nombreArchivoExcel,$area);
		$idExcelRol=pg_fetch_result($qGuardarNuevoExcelRolPagos, 0, 'id_excel_rol');

		$objPHPExcel = PHPExcel_IOFactory::load($archivo);
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();
		$cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();

		$highestRow = $sheet->getHighestRow();
		//$highestRow=180;

		$arrayCedula=array();
		$fila=0;
		for($i=9,$l=59;$i<=$highestRow;$i+=59,$l+=59){
		$cedula=$objPHPExcel->getActiveSheet()->getCell('C'.$i);
						
				$pdf=new FPDF();
				$pdf->AliasNbPages();
				$pdf->AddPage();

				//BLOQUE UNO
				$fila+=4;
				$pdf->Cell(77);//----cambio-------------------
				$pdf->SetFont('Arial','B',12);
				$pdf->Cell(0,5,utf8_decode($objPHPExcel->getActiveSheet()->getCell('B'.$fila)->getValue()),0,0);

				$pdf->Ln(8);
				$pdf->Image('img/iconoAgrocalidad.gif' , 8 ,8, 18 , 18,'GIF', 'http://www.agrocalidad.gob.ec');
				$fila+=1;

				$pdf->Cell(34);//----cambio-------------------
				$pdf->SetFont('Arial','B',11);
				//$pdf->Cell(0,0,utf8_decode($objPHPExcel->getActiveSheet()->getCell('B'.$fila)->getValue()),0,0);
				$pdf->Cell(0,0,utf8_decode($nombreAgro),0,0);//----cambio-------------------
				$fila+=1;
				$pdf->Ln(5);
				$pdf->Cell(64);//----cambio-------------------
				$pdf->SetFont('Arial','B',11);
				
				//$MesRol=str_replace(" ", "_",$objPHPExcel->getActiveSheet()->getCell('B'.$fila)->getValue());
				//if($MesRol == '')$MesRol='ROL_MENSUAL_'.strtoupper($mes).'_'.$ano;
				$MesRol='ROL_MENSUAL_'.strtoupper($mes).'_'.$ano;
				$cuerpoMail=str_replace("_", " ",$MesRol);
				$pdf->Cell(0,0,utf8_decode($cuerpoMail) ,0,0);
				//BLOQUE 2
				//BLOQUE 2.1

				$fila+=3;

				$pdf->Ln(7);

				$pdf->Cell(0, 0, "", 1, 0, 'L', true);
				$pdf->Ln(5);
				$pdf->SetFont('Arial','',9);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('A'.$fila)->getValue());
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(46);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('C'.$fila)->getValue());
				$pdf->Cell(73);
				$pdf->SetFont('Arial','',9);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('E'.$fila)->getValue());
				$pdf->SetFont('Arial','B',9);
				$pdf->Cell(90);
				$pdf->MultiCell(0,0,utf8_decode($objPHPExcel->getActiveSheet()->getCell('F'.$fila)->getValue()));

				//BLOQUE 2.2
				$fila+=1;

				$pdf->Ln(6);
				$pdf->SetFont('Arial','',9);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('A'.$fila)->getValue());
				$pdf->Cell(32);
				$pdf->MultiCell(15,0,$objPHPExcel->getActiveSheet()->getCell('B'.$fila)->getValue(),0,'R',0);
				$pdf->Cell(51);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('D'.$fila)->getValue());
				$pdf->Cell(109);
				$pdf->MultiCell(15,0,number_format( $objPHPExcel->getActiveSheet()->getCell('G'.$fila)->getValue(), 2, '.', ''),0,'R',0 );
				$pdf->Cell(128);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('H'.$fila)->getValue());
				$pdf->Cell(142);
				$pdf->MultiCell(15,0,number_format( $objPHPExcel->getActiveSheet()->getCell('I'.$fila)->getValue(), 2, '.', ''),0,'R',0 );
				$pdf->Cell(161);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('J'.$fila)->getValue());
				$pdf->Cell(176);
				$pdf->MultiCell(15,0,number_format( $objPHPExcel->getActiveSheet()->getCell('K'.$fila)->getValue(), 2, '.', ''),0,'R',0 );

				//BLOQUE 2.3
				$fila+=1;

				$pdf->Ln(6);

				$pdf->SetFont('Arial','',9);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('A'.$fila)->getValue());
				$pdf->Cell(32);
				$pdf->MultiCell(15,0,number_format($objPHPExcel->getActiveSheet()->getCell('B'.$fila)->getValue(), 2, '.', ''),0,'R',0);
				$pdf->Cell(51);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('D'.$fila)->getValue());
				$pdf->Cell(109);				
				$pdf->MultiCell(15,0,number_format( $objPHPExcel->getActiveSheet()->getCell('G'.$fila)->getValue(), 2, '.', ''),0,'R',0 );
				$pdf->Cell(128);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('H'.$fila)->getValue());
				$pdf->Cell(142);
				$pdf->MultiCell(15,0,number_format( $objPHPExcel->getActiveSheet()->getCell('I'.$fila)->getValue(), 2, '.', ''),0,'R',0 );
				$pdf->Cell(161);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('J'.$fila)->getValue());
				$pdf->Cell(176);
				$pdf->MultiCell(15,0,number_format( $objPHPExcel->getActiveSheet()->getCell('K'.$fila)->getValue(), 2, '.', ''),0,'R',0 );

				//BLOQUE 3
				//BLOQUE 3.1
				$fila+=1;
				$pdf->Ln(7);
				$pdf->Cell(0, 0, "", 1, 0, 'L', true);
				$pdf->Ln(5);
				$pdf->SetFont('helvetica','B',13);
				$pdf->Cell(32);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('B'.$fila)->getValue());
				$pdf->Cell(125);
				$pdf->MultiCell(0,0,$objPHPExcel->getActiveSheet()->getCell('H'.$fila)->getValue());

				//BLOQUE 3.2Formato 
				$fila+=1;
				$pdf->Ln(7);
				$pdf->SetFont('Arial','',7);

				$txt=$objPHPExcel->getActiveSheet()->getCell('A'.$fila)->getValue();
				$dat = preg_replace("/[\t\s]+/", "-", $txt);

				$data=array();
				$data=explode("-", $dat);

				$arrayUnoNumero=array();
				$arrayUnoLetra=array();

				$x=0; $ban=1;
				for ($k=0;$k<count($data);$k++){
					if (ctype_digit($data[$k]))$ban=0;
					if(floatval($data[$k]) and $ban==1 ){
						$arrayUnoNumero[]=$data[$k];
						$x++;
					}else{
						if(strcmp($data[$k], '0.00')==0){
							$arrayUnoNumero[]=$data[$k];
							$x++;
						}else{
							$arrayUnoLetra[$x].=$data[$k].' ';
						}
					}
					$ban=1;
				}

				for($j=0;$j<=count($arrayUnoLetra);$j++){
					if($j==count($arrayUnoLetra)-1){
						$pdf->Cell(10);
						$pdf->MultiCell(50,0,utf8_decode($arrayUnoLetra[$j]));
					}else{
						$pdf->MultiCell(50,0,utf8_decode($arrayUnoLetra[$j]));
					}

					$pdf->Cell(50);

					if($j==0)
						$pdf->MultiCell(30,0,number_format( utf8_decode($arrayUnoNumero[$j]), 2, '.', ''),0,'R',0 );
					else
						$pdf->MultiCell(30,0,number_format( utf8_decode($arrayUnoNumero[$j]), 2, '.', ''),0,'R',0 );

					$pdf->Ln(4);
				}

				$txt=$objPHPExcel->getActiveSheet()->getCell('F'.$fila)->getValue();
				$dat = preg_replace("/[\t\s]+/", "-", $txt);

				$data=array();
				$data=explode("-", $dat);

				$arrayDosNumero=array();
				$arrayDosLetra=array();
				$x=0; $ban=1;
				for ($k=0;$k<count($data);$k++){
					if (ctype_digit($data[$k]))$ban=0;
					if(floatval($data[$k]) and $ban==1){
						$arrayDosNumero[]=$data[$k];
						$x++;
					}else{
						if(strcmp($data[$k], '0.00')==0){
							$arrayDosNumero[]=$data[$k];
							$x++;
						}else{

							$arrayDosLetra[$x].=$data[$k].' ';
						}
					}
					$ban=1;
				}
				$pdf->SetY(67);
				for($j=0;$j<=count($arrayDosLetra);$j++){
					$pdf->SetX(100);
					if($j==count($arrayDosLetra)-1){
						$pdf->Cell(15);
						$pdf->MultiCell(50,0,utf8_decode($arrayDosLetra[$j]));
					}else
						$pdf->MultiCell(50,0,utf8_decode($arrayDosLetra[$j]));
					$pdf->Cell(150);

					$pdf->MultiCell(30,0,number_format( utf8_decode($arrayDosNumero[$j]), 2, '.', ''),0,'R',0 );
					$pdf->Ln(4);

				}
				//BLOQUE 4
				//BLOQUE 4.1
				$pdf->Ln(60);
				$pdf->SetX(67);
				$pdf->Cell(80, 0, "", 1, 0, 'L', true);

				$fila+=41;

				$pdf->Ln(4);
				$pdf->Cell(62);
				$pdf->MultiCell(100,0,$objPHPExcel->getActiveSheet()->getCell('D'.$fila)->getValue());

				$pdf->Cell(118);
				$pdf->MultiCell(15,0,number_format( $objPHPExcel->getActiveSheet()->getCell('G'.$fila)->getValue(), 2, '.', ''),0,'R',0 );

				$pdf->Ln(4);
				$pdf->SetX(67);
				$pdf->Cell(80, 0, "", 1, 0, 'L', true);

				//BLOQUE 4.2
				$fila+=2;
				$pdf->Ln(7);
				$pdf->SetX(142);
				$pdf->MultiCell(100,0,$objPHPExcel->getActiveSheet()->getCell('H'.$fila)->getValue());

				//BLOQUE 4.3
				$fila+=4;
				$pdf->Ln(20);
				$pdf->SetX(12);
				$pdf->MultiCell(20,0,utf8_decode($objPHPExcel->getActiveSheet()->getCell('A'.$fila)->getValue()));
				$pdf->SetX(30);
				$pdf->MultiCell(100,0,$objPHPExcel->getActiveSheet()->getCell('B'.$fila)->getValue());
				$pdf->SetX(120);
				$pdf->Cell(70, 0, "", 1, 0, 'L', true);

				//BLOQUE 4.4
				$fila+=1;
				$pdf->Ln(3);
				$pdf->SetX(115);
				$pdf->MultiCell(80,0,utf8_decode($objPHPExcel->getActiveSheet()->getCell('G'.$fila)->getValue()),0,'C',0 );

			 	$ruta = dirname(__FILE__).'/archivosRolPagos/pdf/'.$cedula;
				if(!file_exists($ruta))
				{
					mkdir ($ruta, 0777,true);
				}

				$pdf->Output('archivosRolPagos/pdf/'.$cedula.'/'.$MesRol.".pdf",'F');
				$rutaArchivoPdf='aplicaciones/uath/archivosRolPagos/pdf/'.$cedula.'/'.$MesRol.".pdf";
				$nombreArchivoPdf=str_replace('aplicaciones/uath/archivosRolPagos/pdf/'.$cedula.'/', '', $rutaArchivoPdf);
				$idrolpago=$ca->guardarNuevoRolPagos($conexion, $idExcelRol, $cedula, $rutaArchivoPdf, $nombreArchivoPdf,$area);
				$idRol=pg_fetch_result($idrolpago, 0, 'id_funcionario_rol_pago');
			
				//------------------------------------enviar mail de roles de pago--------------------------------------------------------------------------------
						$fila=pg_fetch_assoc($ca->obtenerDatosUsuarioAgrocalidad($conexion, $cedula));
						$asunto = $cuerpoMail.' AGROCALIDAD';
						$familiaLetra = "font-family:Text Me One,Segoe UI, Tahoma, Helvetica, freesans, sans-serif";
						$letraCodigo = "font-family:'Segoe UI', 'Helvetica'";
						$cuerpoMensaje = '<table><tbody>
				<tr><td style="'.$familiaLetra.'; text-align:center; font-size:30px; color:rgb(255,206,0); font-weight:bold; text-transform:uppercase;">Sistema GUIA <span style="color:rgb(19,126,255);">te </span> <span style="color:rgb(204,41,44);">saluda</span></td></tr>
				<tr><td style="'.$familiaLetra.'; padding-top:2px; font-size:17px; color:rgb(19,126,255); font-weight:bold;"></td></tr>
				<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:20px; color:rgb(46,78,158);font-weight:bold;">ROL DE PAGOS DEL MES DE '.strtoupper($mes).' DEL '.$ano.'</td></tr>
				<tr><td style="'.$familiaLetra.'; padding-top:20px; font-size:14px;color:#2a2a2a;">Estimad@,</tr>
				<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;">Se ha enviado su rol de pagos correspondiente al mes de '.strtolower($mes).' del '.$ano.'. Recuerde qué también este documento lo tiene disponible en:<br> <span style="color:rgb(46,78,158); font-weight:bold;">Sistema GUIA</span> >>> <span style="color:rgb(46,78,158); font-weight:bold;">OPCION MIS DATOS</span> >>> <span style="color:rgb(46,78,158); font-weight:bold;">ROL DE PAGOS.</span> </td></tr>
				<tr><td style="'.$familiaLetra.'; padding-top:30px; font-size:14px;color:#2a2a2a;"><span style="'.$familiaLetra.'; padding-top:20px; font-size:14px; color:rgb(204,41,44);font-weight:bold;" >NOTA: </span>Este correo fue generado automaticamente por el sistema GUIA, por favor no responder a este mensaje. </td></tr>
				<tr><td style="'.$familiaLetra.'; padding-top:25px; font-size:14px;color:#2a2a2a;">El equipo de Desarrollo Tecnológico de Agrocalidad </td></tr>
				</tbody></table>';
						$destinatario = array();
						$mailDestino='';
						if($fila['mail_institucional']!= ''){
							array_push($destinatario, $fila['mail_institucional']);
							$mailDestino=$fila['mail_institucional'];
						}else if($fila['mail_personal'] !=''){
							array_push($destinatario, $fila['mail_personal']);
							$mailDestino=$fila['mail_personal'];
						}
				
						if($mailDestino != ''){
							//----------------guardar correo para proceso automatico-----------------------
							$codigoModulo = 'PRG_CATASTRO';
							$tablaModulo = 'g_uath.funcionario_rol_pagos';
							
							$qGuardarCorreo=$cMail->guardarCorreo($conexion, $asunto, $cuerpoMensaje, 'Por enviar', $codigoModulo, $tablaModulo, $idRol);
							$idCorreo=pg_fetch_result($qGuardarCorreo, 0, 'id_correo');
							$cMail->guardarDestinatario($conexion, $idCorreo, $destinatario);
							
							$adjuntos = array();
							$rutaArchivo = dirname(__FILE__).'/archivosRolPagos/pdf/'.$cedula.'/'.$nombreArchivoPdf;
							array_push($adjuntos, $rutaArchivo);
							$cMail->guardarDocumentoAdjunto($conexion, $idCorreo, $adjuntos);
							
		                 }
						//-------------------------------------------------------------------------------------------------------------------------------------------------
			
			$fila=$l;
		}
		$mensaje ['estado'] = 'exito';
		$mensaje ['mensaje'] = 'Los datos han sido ingresados satisfactoriamente';
		$conexion->ejecutarConsulta("commit;");
		}else{
			$mensaje ['estado'] = 'error';
			$mensaje ['mensaje'] = 'Rol de pagos del mes de '.$mes.' del año '.$ano.' ya existe....!!!';			
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