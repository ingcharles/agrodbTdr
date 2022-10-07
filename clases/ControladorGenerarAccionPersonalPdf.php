<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../vacacionesPermisos/clases/PdfAccionPersonal.php';
require_once '../../clases/ControladorVacaciones.php';
require_once '../../clases/ControladorCatalogos.php';

class GeneradorDocumentoPDF{
    
	public function generarCertificado($salidaReporte,$arrayDatos,$arrayTipo,$arraySituacionActual,$arrayFirmaTTHH,$arrayFirmaDE,$arrayFirmaRC){
        ob_start();
        $mensaje=array();
        $mensaje['mensaje'] = 'Error generando documento';
        $mensaje['estado'] = 'NO';
        $conexion = new Conexion();
        $cc  =new ControladorVacaciones();
        $cat = new ControladorCatalogos();
        //************************************************** INICIO ***********************************************************
        
        $margen_superior=6;
        $margen_inferior=6;
        $margen_izquierdo=6;
        $margen_derecho=6;
        
        if($_SERVER['REMOTE_ADDR'] <> ''){
        	header('Content-type: application/pdf');
        }
        
        $doc=new PdfAccionPersonal('P','mm','A4',true,'UTF-8');
  
        $tipoLetra='helvetica';
        
       //***************************************************************************************************************************
        $doc->SetLineWidth(0.1);
        $doc->SetMargins($margen_izquierdo, $margen_superior, $margen_derecho);
        $doc->SetAutoPageBreak(TRUE, $margen_inferior);
        $doc->AddPage();
        $doc->SetFont($tipoLetra, '', 9);
        $xfull=$doc->getPageWidth()-$margen_izquierdo-$margen_derecho;
        
        //****************************** INICIA *************************************
        $tamañoColumn = 63;
        $ancho=18;
        
        $arrayColor = array(
        	255,
        	255,
        	255);
        $posicionX = $doc->getPageWidth() - $margen_derecho-$tamañoColumn;
        $doc->crearCuadrado($tipoLetra, $posicionX,$tamañoColumn, $margen_superior, $arrayColor, $ancho);
        
        $doc->SetFont($tipoLetra, 'B', 10);
        $doc->writeHTMLCell($tamañoColumn, 0, $posicionX, $margen_superior+2, 'ACCION DE PERSONAL', '', 0, 0, false, 'C', false);
        $doc->writeHTMLCell($tamañoColumn, 0, $posicionX, $margen_superior+7.5, 'No.:', '', 0, 0, false, 'L', false);
        $doc->SetFont($tipoLetra, '', 10);
        $doc->writeHTMLCell($tamañoColumn, 0, $posicionX+15, $margen_superior+7.5, $arrayDatos['codigoPermiso'], '', 0, 0, false, 'L', false);
        $doc->SetFont($tipoLetra, 'B', 10);
        $doc->writeHTMLCell($tamañoColumn, 0, $posicionX, $margen_superior+12, 'Fecha:', '', 0, 0, false, 'L', false);
        $doc->SetFont($tipoLetra, '', 10);
        $doc->writeHTMLCell($tamañoColumn, 0, $posicionX+15, $margen_superior+12, date('d/m/Y'), '', 0, 0, false, 'L', false);
        $doc->Image($arrayDatos['rutaImagen'], $margen_izquierdo, $margen_superior, $xfull-$tamañoColumn-0.4, $ancho, 'PNG', '', '', false, 300, '', false, false, 0);
        
        $y = $margen_superior+$ancho;
        $ancho=15;
        $arrayColor = array(
        	243,
        	243,
        	243);
        $posicionX = $doc->getPageWidth() - $margen_derecho-$xfull;
        $doc->crearCuadrado($tipoLetra, $posicionX,$xfull,$y,$arrayColor, $ancho);
        $doc->SetFont($tipoLetra, 'B', 8);
        $arrayColor = array(
        	255,
        	255,
        	255);
        $doc->writeHTMLCell($tamañoColumn, 0, $posicionX, $y+2, 'DECRETO', '', 0, 0, false, 'L', false);
        $doc->crearCuadrado($tipoLetra, $posicionX+22,5,$y+3.1,$arrayColor, 3);
        $doc->writeHTMLCell($tamañoColumn, 0, $posicionX+22.5, $y+2.4, $arrayTipo['decreto'], '', 0, 0, false, 'L', false);
        
        $doc->writeHTMLCell($tamañoColumn, 0, $xfull/2-5, $y+2, 'ACUERDO', '', 0, 0, false, 'L', false);
        $doc->crearCuadrado($tipoLetra, $xfull/2-5+22,5,$y+3.1,$arrayColor, 3);
        $doc->writeHTMLCell($tamañoColumn, 0, $xfull/2+17.5, $y+2.3, $arrayTipo['acuerdo'], '', 0, 0, false, 'L', false);
        
        $doc->writeHTMLCell($tamañoColumn, 0,$xfull/2+69, $y+2, 'RESOLUCION', '', 0, 0, false, 'L', false);
        $doc->crearCuadrado($tipoLetra, $xfull-($xfull/3)+62,5,$y+3.1,$arrayColor, 3);
        $doc->writeHTMLCell($tamañoColumn, 0, $xfull-($xfull/3)+62.5,$y+2.3, $arrayTipo['resolucion'], '', 0, 0, false, 'L', false);
        
        $doc->writeHTMLCell($tamañoColumn, 0, $posicionX, $y+8, 'NO.', '', 0, 0, false, 'L', false);
        $doc->writeHTMLCell($tamañoColumn, 0, $xfull/2-5, $y+8, 'FECHA:', '', 0, 0, false, 'L', false);
        $doc->SetFont($tipoLetra, '', 8);
        $doc->writeHTMLCell($tamañoColumn, 0, $xfull/2+15.4, $y+8, date('d/m/Y'), '', 0, 0, false, 'L', false);
       //***********************************dos**********************************************************************
        $arrayColor = array(
        	255,
        	255,
        	255);
        $y = $y+$ancho+1;
        $ancho = 11;
        $posicionX = $doc->getPageWidth() /2;
        $doc->crearCuadrado($tipoLetra, $margen_izquierdo,$xfull/2-0.5,$y,$arrayColor, $ancho);
        $doc->SetFont($tipoLetra, '', 10);
        $doc->writeHTMLCell($xfull/2-0.5, 0, $margen_izquierdo, $y+1, $arrayDatos['apellidoFuncionario'], '', 0, 0, false, 'C', false);
        $doc->SetFont($tipoLetra, 'B', 8);
        $doc->writeHTMLCell($xfull/2-0.5, 0, $margen_izquierdo, $y+6, 'APELLIDOS', '', 0, 0, false, 'C', false);
        
        $tamañoColumn = $xfull/2-0.5;
        $posicionX = $doc->getPageWidth() - $margen_derecho-$tamañoColumn;
        $doc->crearCuadrado($tipoLetra, $posicionX,$tamañoColumn, $y, $arrayColor, $ancho);
        $doc->SetFont($tipoLetra, '', 10);
        $doc->writeHTMLCell($tamañoColumn, 0, $posicionX, $y+1, $arrayDatos['nombreFuncionario'], '', 0, 0, false, 'C', false);
        $doc->SetFont($tipoLetra, 'B', 8);
        $doc->writeHTMLCell($tamañoColumn, 0, $posicionX, $y+6, 'NOMBRES', '', 0, 0, false, 'C', false);
        //************************************tres*********************************************************************
        $y = $y + $ancho +1;
        $tamañoColumn = $xfull/2-0.5;
        $posicionX = $doc->getPageWidth() - $margen_derecho-$tamañoColumn;
        $doc->crearCuadrado($tipoLetra, $margen_izquierdo,$xfull/3-0.5, $y, $arrayColor, $ancho);
        
        $doc->SetFont($tipoLetra, 'B', 8);
        $doc->writeHTMLCell($xfull/3-0.5, 0, $margen_izquierdo, $y+1, 'No. de Cédula de Ciudadanía', '', 0, 0, false, 'C', false);
        $doc->SetFont($tipoLetra, '', 10);
        $doc->writeHTMLCell($xfull/3-0.5, 0, $margen_izquierdo, $y+6, $arrayDatos['identificador'], '', 0, 0, false, 'C', false);
        
        $doc->crearCuadrado($tipoLetra, $margen_izquierdo+$xfull/3+0.5,$xfull/3-0.5, $y, $arrayColor, $ancho);
        $doc->SetFont($tipoLetra, 'B', 8);
        $doc->writeHTMLCell($xfull/3-0.5, 0, $margen_izquierdo+$xfull/3+0.5, $y+1, 'No. de Afiliación IESS', '', 0, 0, false, 'C', false);
        $doc->SetFont($tipoLetra, '', 10);
        $doc->writeHTMLCell($xfull/3-0.5, 0, $margen_izquierdo+$xfull/3+0.5, $y+6, '', '', 0, 0, false, 'C', false);
        
        $tamañoColumn = $xfull/3-1;
        $posicionX = $doc->getPageWidth() - $margen_derecho-$tamañoColumn;
        $doc->crearCuadrado($tipoLetra,$posicionX,$tamañoColumn, $y, $arrayColor, $ancho);
        $doc->SetFont($tipoLetra, 'B', 8);
        $doc->writeHTMLCell($tamañoColumn, 0, $posicionX, $y+1, 'Rige a partir de:', '', 0, 0, false, 'C', false);
        $doc->SetFont($tipoLetra, '', 10);
        $doc->writeHTMLCell($tamañoColumn, 0, $posicionX, $y+6, $arrayDatos['rigeDesde'], '', 0, 0, false, 'C', false);
        //************************************cuatro*********************************************************************
        $y = $y + $ancho +1;
        $ancho = 36;
        $posX = 2;
        $doc->crearCuadrado($tipoLetra, $margen_izquierdo,$xfull,$y,$arrayColor, $ancho);
        $doc->SetFont($tipoLetra, 'B', 8);
        $doc->writeHTMLCell($xfull, 0, $margen_izquierdo+$posX, $y+2, 'EXPLICACIÓN:', '', 0, 0, false, 'L', false);
        $doc->SetFont($tipoLetra, '', 8);
        $doc->writeHTMLCell($xfull, 0, $margen_izquierdo+$posX, $y+7, $arrayDatos['texoAcccionPersonal'], '', 0, 0, false, 'L', false);
        //*********************************************************************************************************
        $y = $y + $ancho +1;
        $ancho = 28;
        $doc->crearCuadrado($tipoLetra, $margen_izquierdo,$xfull,$y,$arrayColor, $ancho);
        
        $arrayTxt = array('INGRESO','NOMBRAMIENTO','ASCENSO','SUBROGACION','ENCARGO','VACACIONES');
        $contador=0;
        $posX = 2;
        foreach ($arrayTxt as $item) {
        	$posY=($ancho/6)*$contador+$y+0.3;
        	$doc->crearTexto($tipoLetra, 'B', 8, $xfull/4, $margen_izquierdo+$posX, $posY, $item, 'L');
        	$contador++;
        }
        
        $arrayTxt = array('','','','','','');
        $contador=0;
        $posX = 2;
        foreach ($arrayTxt as $item) {
        	$posY=($ancho/6)*$contador+$y+0.3;
        	$doc->crearCuadrado($tipoLetra, $xfull/4-$margen_izquierdo+$posX-8,5,$posY+0.7,$arrayColor, 3);
        	$doc->crearTexto($tipoLetra, 'B', 8, 12, $xfull/4-$margen_izquierdo+$posX-7.5, $posY+0.5, $item, 'L');
        	$contador++;
        }
        
        $arrayTxt = array('TRASLADO','TRASPASO','CAMBIO ADMINISTRATIVO','INTERCAMBIO','COMISION DE SERVICIOS','LICENCIA');
        $contador=0;
        foreach ($arrayTxt as $item) {
        	$posY=($ancho/6)*$contador+$y+0.3;
        	$doc->crearTexto($tipoLetra, 'B', 8, $xfull/4, $margen_izquierdo+$xfull/4+$posX-8, $posY, $item, 'L');
        	$contador++;
        }
        
        $arrayTxt = array('','','','','','');
        $contador=0;
        foreach ($arrayTxt as $item) {
        	$posY=($ancho/6)*$contador+$y+0.3;
        	$doc->crearCuadrado($tipoLetra, $xfull/4-$margen_izquierdo+$xfull/4+$posX,5,$posY+0.7,$arrayColor, 3);
        	$doc->crearTexto($tipoLetra, 'B', 8, 12,$xfull/4-$margen_izquierdo+$xfull/4+$posX+0.3, $posY+0.5, $item, 'L');
        	$contador++;
        }
        
        $arrayTxt = array('REVALORACION','RECLASIFICACION','UBICACION','REINTEGRO','RESTITUCION','RENUNCIA');
        $contador=0;
        foreach ($arrayTxt as $item) {
        	$posY=($ancho/6)*$contador+$y+0.3;
        	$doc->crearTexto($tipoLetra, 'B', 8, $xfull/4, $margen_izquierdo+($xfull/4)*2+$posX, $posY, $item, 'L');
        	$contador++;
        }
        
        $arrayTxt = array('','','','','','');
        $contador=0;
        foreach ($arrayTxt as $item) {
        	$posY=($ancho/6)*$contador+$y+0.3;
        	$doc->crearCuadrado($tipoLetra, $xfull/4-$margen_izquierdo+($xfull/4)*2+$posX,5,$posY+0.7,$arrayColor,3);
        	$doc->crearTexto($tipoLetra, 'B', 8, 12, $xfull/4-$margen_izquierdo+($xfull/4)*2+$posX+0.4, $posY+0.5, $item, 'L');
        	$contador++;
        }
        
        
        $arrayTxt = array('SUPRESION','DESTITUCION','REMOCION','JUBILACION','OTRO');
        $contador=0;
        foreach ($arrayTxt as $item) {
        	$posY=($ancho/6)*$contador+$y+0.3;
        	$doc->crearTexto($tipoLetra, 'B', 8, $xfull/4, $margen_izquierdo+($xfull/4)*3+$posX, $posY, $item, 'L');
        	$contador++;
        }
        $doc->Line($margen_izquierdo+($xfull/4)*3+$posX+10 ,$posY+4  ,$margen_izquierdo+($xfull/4)*3+$posX+40, $posY+4, '');
        
        $arrayTxt = array('','','','');
        $contador=0;
        foreach ($arrayTxt as $item) {
        	$posY=($ancho/6)*$contador+$y+0.3;
        	$doc->crearCuadrado($tipoLetra, $xfull/4-$margen_izquierdo+($xfull/4)*3,5,$posY+0.7,$arrayColor, 3);
        	$doc->crearTexto($tipoLetra, 'B', 8, 12, $xfull/4- $margen_izquierdo+($xfull/4)*3+0.4, $posY+0.5, $item, 'L');
        	$contador++;
        }
        
        //*********************************************************************************************************
        $y = $y + $ancho +1;
        $ancho = 52;
        $posX =2;
        $posY =$y;
        $posicionX = $doc->getPageWidth() /2;
        $doc->crearCuadrado($tipoLetra, $margen_izquierdo,$xfull/2-0.5,$y,$arrayColor, $ancho);
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo, $posY+1, 'SITUACION ACTUAL', 'C');
        $posY=$posY+2;
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo+$posX, $posY+5, 'PROCESO', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo+$posX, $posY+13, 'SUBPROCESO', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo+$posX, $posY+21, 'PUESTO', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo+$posX, $posY+29, 'LUGAR DE TRABAJO', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo+$posX, $posY+35, 'REMUNERACIÓN MENSUAL', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo+$posX, $posY+41, 'PARTIDA PRESUPUESTARIA', 'L');
        
        $tamañoColumn = $xfull/2-0.5;
        $posicionX = $doc->getPageWidth() - $margen_derecho-$tamañoColumn;
        $doc->crearCuadrado($tipoLetra, $posicionX,$tamañoColumn, $y, $arrayColor, $ancho);
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$posicionX, $y+1, 'SITUACION PROPUESTA', 'C');
        
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$posicionX+$posX, $posY+5, 'PROCESO', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$posicionX+$posX, $posY+13, 'SUBPROCESO', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$posicionX+$posX, $posY+21, 'PUESTO', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$posicionX+$posX, $posY+29, 'LUGAR DE TRABAJO', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$posicionX+$posX, $posY+35, 'REMUNERACIÓN MENSUAL', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$posicionX+$posX, $posY+41, 'PARTIDA PRESUPUESTARIA', 'L');
        
        //***********************situacion actual**********************************************************
        $txtPosX = 25;
        $doc->crearTexto($tipoLetra, '', 8, 72,$margen_izquierdo+$posX+$txtPosX, $posY+4, $arraySituacionActual['proceso'], 'L',false);
        $doc->crearTexto($tipoLetra, '', 8, 72,$margen_izquierdo+$posX+$txtPosX, $posY+12, $arraySituacionActual['subProceso'], 'L');
        $doc->crearTexto($tipoLetra, '', 8, 72,$margen_izquierdo+$posX+$txtPosX, $posY+21, $arraySituacionActual['puesto'], 'L');
        $txtPosX = 35;
        $doc->crearTexto($tipoLetra, '', 8, 68,$margen_izquierdo+$posX+$txtPosX, $posY+28, $arraySituacionActual['lugarTrabajo'], 'L');
        $txtPosX = 42;
        $doc->crearTexto($tipoLetra, '', 8, $xfull/2-0.5,$margen_izquierdo+$posX+$txtPosX, $posY+35, $arraySituacionActual['remuneracion'], 'L');
        $doc->crearTexto($tipoLetra, '', 7, $xfull/2-0.5,$margen_izquierdo+$posX+8, $posY+45, $arraySituacionActual['partidaPresupuestaria'], 'L');
        
        
        //*************************situacion propuesta****************************************************
        $txtPosX = 25;
        $doc->crearTexto($tipoLetra, '', 8, 72,$posicionX+$posX+$txtPosX, $posY+4, '', 'L',false);
        $doc->crearTexto($tipoLetra, '', 8, 72,$posicionX+$posX+$txtPosX, $posY+12, '', 'L');
        $doc->crearTexto($tipoLetra, '', 8, 72,$posicionX+$posX+$txtPosX, $posY+21, '', 'L');
        $txtPosX = 35;
        $doc->crearTexto($tipoLetra, '', 8, 68,$posicionX+$posX+$txtPosX, $posY+28, '', 'L');
        $txtPosX = 42;
        $doc->crearTexto($tipoLetra, '', 8, $xfull/2-0.5,$posicionX+$posX+$txtPosX, $posY+35, '', 'L');
        $doc->crearTexto($tipoLetra, '', 7, $xfull/2-0.5,$posicionX+$posX+8, $posY+45, '', 'L');
        
        //*********************************************************************************************************
        
        $y = $y + $ancho +1;
        $ancho = 30;
        
        $posicionX = $doc->getPageWidth() /2;
        $doc->crearCuadrado($tipoLetra, $margen_izquierdo,$xfull/2-0.5,$y,$arrayColor, $ancho);
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo, $y+1, 'ACTA FINAL DEL CONCURSO', 'C');
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo+2, $y+$ancho-8, 'No.', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo+(($xfull/2)/2), $y+$ancho-8, 'FECHA:.', 'L');
        
        $tamañoColumn = $xfull/2-0.5;
        $posicionX = $doc->getPageWidth() - $margen_derecho-$tamañoColumn;
        $doc->crearCuadrado($tipoLetra, $posicionX,$tamañoColumn, $y, $arrayColor, $ancho);
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$posicionX, $y+1, 'PROCESO DE RECURSOS HUMANOS', 'C');
        
        
        if($arrayFirmaTTHH['firma'] == 'si'){
        	//******************************************* FIRMA *************************************************************************
        	$datosCertificado=$cat->obtenerDatosCertificado($conexion,$arrayFirmaTTHH['identificador']);
        	$datosCertificado['info']['Reason']='ACCION PERSONAL';
        	$doc->setSignature($datosCertificado['rutaCertificado'], $datosCertificado['rutaCertificado'], $datosCertificado['password'], '',1, $datosCertificado['info']);
        	
        	$rutaQRF = '
		        Location: '.$datosCertificado['info']['Location'].'
		        Reason: '. $datosCertificado['info']['Reason'].'
		        ContactInfo: '.$datosCertificado['info']['ContactInfo'].'
		        FIRMADO POR: '.$datosCertificado['info']['Name'].'
		        FECHA FIRMADO:' . date('d-m-Y').$datosCertificado['rutaCertificado'];
        	$doc->setSignatureAppearance(10 ,50, 33, 23,2);
        	$doc->crearFirmaElectronica($tipoLetra,$posicionX, $y, $tamañoColumn, $rutaQRF,$ancho,$arrayFirmaTTHH, $datosCertificado,'si');
        }else{
        	$doc->crearFirmaNormal($tipoLetra,$posicionX, $y, $tamañoColumn, $ancho, $arrayFirmaTTHH);
        }
        
        //*********************************************************************************************************
        $y = $y + $ancho +1;
        $ancho =30;
        $doc->crearCuadrado($tipoLetra, $margen_izquierdo,$xfull,$y,$arrayColor, $ancho);
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull,$margen_izquierdo, $y+1, 'DIOS, PATRIA Y LIBERTAD', 'C');
        
        if($arrayFirmaTTHH['firma'] == 'si'){
        	//******************************************* FIRMA *************************************************************************
        	$datosCertificado=$cat->obtenerDatosCertificado($conexion,$arrayFirmaDE['identificador']);
        	$datosCertificado['info']['Reason']='ACCION PERSONAL';
        	$doc->setSignature($datosCertificado['rutaCertificado'], $datosCertificado['rutaCertificado'], $datosCertificado['password'], '',1, $datosCertificado['info']);
        	
        	$rutaQRDE = '
		        Location: '.$datosCertificado['info']['Location'].'
		        Reason: '. $datosCertificado['info']['Reason'].'
		        ContactInfo: '.$datosCertificado['info']['ContactInfo'].'
		        FIRMADO POR: '.$datosCertificado['info']['Name'].'
		        FECHA FIRMADO:' . date('d-m-Y').$datosCertificado['rutaCertificado'];
        	$doc->setSignatureAppearance(10 ,50, 33, 23,2);
        	
        	$doc->crearFirmaElectronica($tipoLetra,$xfull/2-40, $y, 100, $rutaQRDE,$ancho,$arrayFirmaDE,$datosCertificado);
        }else{
        	$doc->crearFirmaNormal($tipoLetra,$xfull/2-40, $y, $tamañoColumn, $ancho, $arrayFirmaDE);
        }
        
        //*********************************************************************************************************
        $y = $y + $ancho +1;
        $ancho = 30;
        
        $posicionX = $doc->getPageWidth() /2;
        $doc->crearCuadrado($tipoLetra, $margen_izquierdo,$xfull/2-0.5,$y,$arrayColor, $ancho);
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo, $y+1, 'RECURSOS HUMANOS', 'C');
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo+2, $y+$ancho-8, 'No.', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $xfull/2-0.5,$margen_izquierdo+(($xfull/2)/2), $y+$ancho-8, 'FECHA:.', 'L');
      
        
        $tamañoColumn = $xfull/2-0.5;
        $posicionX = $doc->getPageWidth() - $margen_derecho-$tamañoColumn;
        $doc->crearCuadrado($tipoLetra, $posicionX,$tamañoColumn, $y, $arrayColor, $ancho);
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$posicionX, $y+1, 'REGISTRO Y CONTROL', 'C');
        
        if($arrayFirmaTTHH['firma'] == 'si'){
        	//******************************************* FIRMA *************************************************************************
        	$datosCertificado=$cat->obtenerDatosCertificado($conexion,$arrayFirmaRC['identificador']);
        	$datosCertificado['info']['Reason']='ACCION PERSONAL';
        	$doc->setSignature($datosCertificado['rutaCertificado'], $datosCertificado['rutaCertificado'], $datosCertificado['password'], '',1, $datosCertificado['info']);
        	
        	$rutaQRRC = '
		        Location: '.$datosCertificado['info']['Location'].'
		        Reason: '. $datosCertificado['info']['Reason'].'
		        ContactInfo: '.$datosCertificado['info']['ContactInfo'].'
		        FIRMADO POR: '.$datosCertificado['info']['Name'].'
		        FECHA FIRMADO:' . date('d-m-Y').$datosCertificado['rutaCertificado'];
        	$doc->setSignatureAppearance(10 ,50, 33, 23,2);
        	
        	$doc->crearFirmaElectronica($tipoLetra,$posicionX, $y, $tamañoColumn, $rutaQRRC,$ancho,$arrayFirmaRC,$datosCertificado);
        }else{
        	$doc->crearFirmaNormal($tipoLetra,$posicionX, $y, $tamañoColumn, $ancho, $arrayFirmaRC);
        }
        
        $doc->AddPage();
        $posY= $doc->getPageHeight()- $margen_superior -$margen_inferior;
        //****************************************************************************************************************
        $doc->crearCuadrado($tipoLetra, $margen_izquierdo,$xfull, $margen_superior, $arrayColor, ($posY/2)/3-5);
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2, $margen_superior+3, 'CAUCION REGISTRADA CON No.', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+$xfull/2, $margen_superior+3, 'Fecha:.', 'L');
        $doc->Line($margen_izquierdo  + 50, $margen_superior+7  ,$margen_izquierdo+97, $margen_superior+7, '');
        $doc->Line($margen_izquierdo +$xfull/2 + 13, $margen_superior+7  ,$margen_izquierdo+$xfull/2+97, $margen_superior+7, '');
        $doc->Line($margen_izquierdo+2, $margen_superior+17  ,$xfull+4, $margen_superior+17, '');
        $doc->Line($margen_izquierdo+2, $margen_superior+30  ,$xfull+4, $margen_superior+30, '');
        
        $doc->Line($margen_izquierdo +$xfull/2 + 13, $margen_superior+7  ,$margen_izquierdo+$xfull/2+97, $margen_superior+7, '');
        
        //****************************************************************************************************************
        $doc->crearCuadrado($tipoLetra, $margen_izquierdo,$xfull, $margen_superior+($posY/2)/3-5, $arrayColor,$posY/3+5);
        
        $Y=$margen_superior+($posY/2)/3+6;
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2, $Y, 'LA PERSONA REEMPLAZA A:', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+$xfull/2, $Y, 'EN EL PUESTO DE:', 'L');
        $doc->Line($margen_izquierdo  + 47, $Y+4  ,$margen_izquierdo+97, $Y+4, '');
        $doc->Line($margen_izquierdo +$xfull/2 + 30, $Y+4  ,$xfull+4, $Y+4, '');
        
        $Y=$Y +16;
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2, $Y, 'QUIEN CESO EN FUNCIONES POR:', 'L');
        $doc->Line($margen_izquierdo +54, $Y+4  ,$xfull+4, $Y+4, '');
        
        $Y=$Y +16;
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2, $Y, 'ACCION DE PERSONAL REGISTRADA CON No.', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+$xfull/2, $Y, 'FECHA:', 'L');
        $doc->Line($margen_izquierdo  + 70, $Y+4  ,$margen_izquierdo+97, $Y+4, '');
        $doc->Line($margen_izquierdo +$xfull/2 + 14, $Y+4  ,$xfull+4, $Y+4, '');
        
        $Y=$Y +20;
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2, $Y, 'AFILIADO AL COLEGIO DE PROFECIONALES DE', 'L');
        $doc->Line($margen_izquierdo +72, $Y+4  ,$xfull+4, $Y+4, '');
        
        $Y=$Y +20;
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2, $Y, 'NO.', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+$xfull/2, $Y, 'FECHA:', 'L');
        $doc->Line($margen_izquierdo  +9, $Y+4  ,$margen_izquierdo+97, $Y+4, '');
        $doc->Line($margen_izquierdo +$xfull/2 + 14, $Y+4  ,$xfull+4, $Y+4, '');
        //*************************************************************************************************************************************
        $doc->crearCuadrado($tipoLetra, $margen_izquierdo,$xfull, $margen_superior+($posY/3)+($posY/2)/3, $arrayColor, $posY/2-20);
        
        $Y=$margen_superior+($posY/3)+($posY/2)/3+10;
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2, $Y, 'POSESION DEL CARGO', 'L');
       
        
        $Y=$Y +20;
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2, $Y, 'YO.', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+$xfull/2, $Y, 'CON CEDULA DE CIUDADANIA No.', 'L');
        $doc->Line($margen_izquierdo  + 10, $Y+4  ,$margen_izquierdo+97, $Y+4, '');
        $doc->Line($margen_izquierdo +$xfull/2 + 51, $Y+4  ,$xfull+4, $Y+4, '');
        
        $Y=$Y +14;
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2, $Y, 'JURO LEADTAD AL ESTADO ECUATORIANO.', 'L');
        
        $Y=$Y +16;
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2, $Y, 'LUGAR.', 'L');
        $doc->Line($margen_izquierdo + 16, $Y+4  ,$margen_izquierdo+97, $Y+4, '');
        $Y=$Y +14;
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2, $Y, 'FECHA.', 'L');
        $doc->Line($margen_izquierdo + 16, $Y+4  ,$margen_izquierdo+97, $Y+4, '');
        
        $Y=$Y +30;
        $doc->crearTexto($tipoLetra, 'B', 8, 20,$margen_izquierdo+2, $Y, 'f.', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2, $Y+6, 'Funcionario', 'C');
        $doc->Line($margen_izquierdo  + 10, $Y+4  ,$margen_izquierdo+97, $Y+4, '');
        
        $doc->crearTexto($tipoLetra, 'B', 8, 20,$margen_izquierdo+2+$xfull/2, $Y, 'f.', 'L');
        $doc->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$margen_izquierdo+2+$xfull/2, $Y+6, 'Funcionario', 'C');
        $doc->Line($margen_izquierdo +$xfull/2 + 10, $Y+4  ,$xfull+4, $Y+4, '');
        
            //******************************* FIN DE LA EDICION ****************************************************************************************
            
        $doc->Output($salidaReporte,'F');
            
            ob_end_clean();
            $mensaje['mensaje'] = 'Archivo generado';
            $mensaje['estado'] = 'exito';
            $mensaje['id_certificado'] = $datos['id_certificado'];
            return $mensaje;
           
    }
       
    public function limitarCaracteres($textoOriginal,$largoMaximo){
        $nuevoTexto=trim($textoOriginal);
        if(strlen($nuevoTexto)>$largoMaximo){
            $nuevoTexto=substr($nuevoTexto,0,$largoMaximo);
        }
        return $nuevoTexto;
    }
    
    private $esBorrador=false;
    public function PonerBorrador($esBorrador=false){
        $this->esBorrador=$esBorrador;
    }
    
    
    
}

?>


