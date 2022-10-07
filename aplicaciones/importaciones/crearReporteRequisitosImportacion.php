<?php
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../general/fpdf.php';

class PDF extends FPDF
{
	function Header(){
		// Logo Magap
		$this->Image('http://localhost/agrodb/aplicaciones/general/img/magap.png',10,8,20);
		// Logo Agrocalidad
		$this->Image('http://localhost/agrodb/aplicaciones/general/img/Agro.png',175,8,20);
		
		$this->SetFont('Times','B',15);
		// Movernos a la derecha
		$this->Cell(80);
		// Título
		$this->Cell(30,10,utf8_decode('Requisitos de Importación'),0,0,'C');
		// Salto de línea
		$this->Ln(20);
	}
	
	function Body($idSolicitud){
		$conexion = new Conexion();
		$ci = new ControladorImportaciones();
		$qImportacion = $ci->abrirImportacionReporte($conexion, $idSolicitud);
		
		$this->SetFont('Times','',12);
		$this->Cell(0,10,utf8_decode('País de origen: '.$qImportacion[0]['paisExportacion']),0,0);
		// Salto de línea
		$this->Ln(5);
		
		$this->Cell(0,10,utf8_decode('Detalle de productos:'),0,0);
		// Salto de línea
		$this->Ln(10);
		
		foreach ($qImportacion as $importacion){
			$this->SetFillColor(200,220,255);
			$this->SetFont('Times','I',12);
			// Título
			$this->Cell(0,6,utf8_decode($importacion['nombreProducto']),0,1,'L',true);
			// Salto de línea
			$this->Ln(4);
		
			$qRequisitos = $ci->listarRequisitosProducto($conexion, $importacion['paisExportacion'], $importacion['nombreProducto']);
			$this->SetFont('Times','',10);
			
			for ($i=0; $i<count($qRequisitos);$i++){
				$this->MultiCell(0,5,'* '.utf8_decode($qRequisitos[$i]['nombre']),0,1);
			}
		
			$this->Ln(8);
		}
	}

	function Footer(){
		// Go to 1.5 cm from bottom
		$this->SetY(-15);
		// Select Arial italic 8
		$this->SetFont('Times','I',8);
		// Print centered page number
		$this->Cell(0,10,utf8_decode('Pág. ').$this->PageNo()."/{nb}",0,0,'C');
	}
}

/*$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($qImportacion);
$pdf->SetFont('Times','',12);
$pdf->Output("archivosRequisitos/".$qImportacion[0]['identificador']."-".$qImportacion[0]['idImportacion'].".pdf");*/
?>