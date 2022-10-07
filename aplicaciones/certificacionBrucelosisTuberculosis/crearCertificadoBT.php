<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorBrucelosisTuberculosis.php';
require_once '../general/fpdf.php';

define('RUTA_SERVIDOR', $_SERVER['DOCUMENT_ROOT']);
define('RUTA_APLICACION', '/agrodbPrueba');
define('RUTA_FIRMAS', RUTA_SERVIDOR . RUTA_APLICACION . '/aplicaciones/certificacionBrucelosisTuberculosis/img/firmas/');

class PDF_BT extends FPDF
{
	function Header(){
		// Fondo
		$this->Image('http://181.112.155.173/agrodbPrueba/aplicaciones/certificacionBrucelosisTuberculosis/img/fondo.png',0,0,210, 295);
		
	}
	
	function Body($estado, $idCertificacionBT){
		$conexion = new Conexion();
		$cbt = new ControladorBrucelosisTuberculosis();
		
		if($estado == 'certificacion'){
			$certificacionBT = pg_fetch_assoc($cbt->abrirCertificacionBT($conexion, $idCertificacionBT));
		}else{
			$certificacionBT = pg_fetch_assoc($cbt->abrirRecertificacionBT($conexion, $idCertificacionBT));
		}
		
		$this->Ln(50);
		$this->SetFont('Arial','',12);		
		$this->MultiCell(0,5,utf8_decode('La Agencia de Regulación y Control Fito y Zoosanitario - AGROCALIDAD concede el'),0,'C',false);
		$this->MultiCell(0,5,utf8_decode('presente certificado:'),0,'C',false);
		
		// Salto de línea
		$this->Ln(10);
		
		$this->SetFont('Arial','B',20);
		$this->SetTextColor(23,55,94);
		
		// Título
		if($certificacionBT['certificacion_bt'] == 'Tuberculosis'){
			$this->MultiCell(0,10,utf8_decode('CERTIFICADO DE'),0,'C',false);
			$this->MultiCell(0,10,utf8_decode('PREDIO LIBRE DE'),0,'C',false);
			$this->MultiCell(0,10,utf8_decode('TUBERCULOSIS'),0,'C',false);
			$this->MultiCell(0,10,utf8_decode('BOVINA'),0,'C',false);
		}else{
			$this->MultiCell(0,10,utf8_decode('CERTIFICADO DE'),0,'C',false);
			$this->MultiCell(0,10,utf8_decode('PREDIO LIBRE DE'),0,'C',false);
			$this->MultiCell(0,10,utf8_decode('BRUCELOSIS'),0,'C',false);
			$this->MultiCell(0,10,utf8_decode('BOVINA'),0,'C',false);
		}
		
		$this->SetTextColor(0,0,0);
		$this->Ln(10);
		$this->Cell(15);
		
		$this->SetFont('Arial','',10);
		//$this->MultiCell(100,5,utf8_decode('Predio: '. $certificacionBT['nombre_predio']),0,'J',false);
		$this->Cell(8,5,utf8_decode('Predio: '. $certificacionBT['nombre_predio']),0,0,'C');
		
		// Movernos a la derecha
		//$this->MultiCell(100,5,utf8_decode('Certificado N°: '. $certificacionBT['num_solicitud']),0,'J',false);
		$this->Cell(250,5,utf8_decode('Certificado N°: '. $certificacionBT['num_solicitud']),0,0,'C');
		$this->Ln(15);
		$this->MultiCell(100,5,utf8_decode('Propietario: '. $certificacionBT['nombre_propietario']),0,'J',false);
		//$this->Cell(0,5,utf8_decode('Propietario: '. $predio),0,0,'C');
		// Salto de línea
		$this->Ln(10);
		
		$this->SetFont('Arial','',10);
		$this->MultiCell(0,5,utf8_decode('En razón de haber cumplido con los requerimientos de diagnóstico negativo de los bovinos, haber eliminado'),0,'C', false);
		$this->MultiCell(0,5,utf8_decode('los animales positivos encontrados en el predio y mantener las medidas de bioseguridad que garanticen su'),0,'C', false);
		$this->MultiCell(0,5,utf8_decode('condición sanitaria según la resolución 0238, publicada el 13 de Octubre del 2016.'),0,'C', false);
		
		// Salto de línea
		$this->Ln(10);
		$this->Cell(15);
		
		$this->SetFont('Arial','',10);
		//$this->MultiCell(100,5,utf8_decode('Provincia: '. $certificacionBT['provincia']),0,'J',false);
		$this->Cell(8,5,utf8_decode('Provincia: '. $certificacionBT['provincia']),0,0,'C');
		// Movernos a la derecha
		//$this->MultiCell(100,5,utf8_decode('Cantón: '. $certificacionBT['canton']),0,'J',false);
		$this->Cell(110,5,utf8_decode('Cantón: '. $certificacionBT['canton']),0,0,'C');
		$this->MultiCell(200,5,utf8_decode('Parroquia: '. $certificacionBT['parroquia']),0,'J',false);
		// Salto de línea
		//OK$this->Ln(5);
		
		//OK$this->MultiCell(100,5,utf8_decode('Parroquia: '. $certificacionBT['parroquia']),0,'J',false);
		//$this->Cell(0,5,utf8_decode('Parroquia: '. $certificacionBT['parroquia']),0,0,'C');
		// Salto de línea
		$this->Ln(10);
		
		//Procesamiento de Fecha
		$fecha = getdate();
		$anio = $fecha['year'];
		$mes = $fecha['mon'];
		$dia = $fecha['mday'];
		
		switch($mes){
			case 1:
				$mes = 'enero';
			break;
			case 2:
				$mes = 'febrero';
			break;
			case 3:
				$mes = 'marzo';
			break;
			case 4:
				$mes = 'abril';
			break;
			case 5:
				$mes = 'mayo';
			break;
			case 6:
				$mes = 'junio';
			break;
			case 7:
				$mes = 'julio';
			break;
			case 8:
				$mes = 'agosto';
			break;
			case 9:
				$mes = 'septiembre';
			break;
			case 10:
				$mes = 'octubre';
			break;
			case 11:
				$mes = 'noviembre';
			break;
			case 12:
				$mes = 'diciembre';
			break;
			default:
				$mes;
			break;
		}
		
		
		$this->SetFont('Arial','',10);
		$this->MultiCell(0,5,utf8_decode('En la ciudad de '. $certificacionBT['canton'] .' a los ' . $dia . ' días del mes de ' . $mes . ' del ' . $anio),0, 'C',false);
		//$this->MultiCell(0,5,utf8_decode('En el cantón '. $certificacionBT['canton'] .' de la provincia de '. $certificacionBT['provincia'] . ' a los ' . $dia . ' días del mes de ' . $mes . ' del ' . $anio),0,1);
		// Salto de línea
		$this->Ln(10);
		
		//Buscar firma del Director Distrital por Provincia
		$this->Cell(10);
                
                $nombreFirma = $certificacionBT['provincia'];
                $nombreFirma = str_replace(' ','',$nombreFirma); 
                $nombreFirma = $this->normaliza($nombreFirma);
		
		$this->Image(RUTA_FIRMAS . $nombreFirma. '.png',70, null, 70);
		
		$this->SetFont('Arial','B',10);
		$this->MultiCell(0,5,utf8_decode('DIRECTOR DISTRITAL DE AGROCALIDAD / JEFE DE SERVICIO DE'),0,'C',false);
		$this->MultiCell(0,5,utf8_decode('SANIDAD AGROPECUARIA'),0,'C',false);
		$this->Ln(5);
		$this->MultiCell(0,5,utf8_decode('Este certificado es válido por un año a partir de su emisión'),0,'C',false);
	}

	function Footer(){
	}
        
        function normaliza ($cadena){
            $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
        ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
            $modificadas = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUY
        bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
            $cadena = utf8_decode($cadena);
            $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
            //$cadena = strtolower($cadena);
            return utf8_encode($cadena);
        }
}
?>