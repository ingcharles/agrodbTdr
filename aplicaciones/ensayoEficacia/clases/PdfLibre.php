<?php


require_once '../general/tcpdf/tcpdf.php';
require_once '../general/tcpdf/lang/spa.php';

class PdfLibre extends TCPDF{
	
	private $esBorrador=false;
	private $numeroSolicitud='';
	public function PonerBorrador($esBorrador=false){
		$this->esBorrador=$esBorrador;
	}

	public function setNumeroSolicitud($nuevoNumero){
		$this->numeroSolicitud=$nuevoNumero;
	}
	
	
	public function AddPage($orientation='', $format='', $keepmargins=false, $tocpage=false){
		parent::AddPage();
		if($this->esBorrador){
			parent::SetFont('times', '', 100);
			parent::SetTextColor(0,15,0,2);
			parent::SetXY(30,30);
			parent::StartTransform();
			parent::Rotate(-17, 35, 45);
			parent::Text(35,45, 'BORRADOR',false,false,true);
			parent::StopTransform();
			parent::SetTextColor();
			parent::SetFont('times', '', 8);
		}
	}

	public function Header() {
		$this->setJPEGQuality(90);
		parent::Image('./img/logo1.png', 20, 5,58, 15, 'PNG', '','',2);
		parent::Image('./img/logo2.png', 220, 5, 38, 15, 'PNG', '','',2);
	}

	public function Footer() {
		$this->SetY(-15);
		$this->SetFont(PDF_FONT_NAME_MAIN, 'I', 8);
		$texto='Pagina '.$this->getAliasNumPage().' de '.$this->getAliasNbPages();
		if($this->numeroSolicitud!='')
			$texto=$texto.' del protocolo '.$this->numeroSolicitud;
		$this->Cell(0, 10, $texto, 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}

}

?>


