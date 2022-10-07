<?php

require_once '../general/tcpdf/tcpdf.php';
require_once '../general/tcpdf/lang/spa.php';

class PdfCertificado extends TCPDF{

	private $esBorrador=false;
	public function PonerBorrador($esBorrador=false){
		$this->esBorrador=$esBorrador;
	}
	
	
	public function AddPage($orientation='', $format='', $keepmargins=false, $tocpage=false){
		parent::AddPage();
		if($this->esBorrador){

		}
	}

	public function Header() {
		$this->setJPEGQuality(90);


		$bMargin = $this->getBreakMargin();
		$auto_page_break = $this->AutoPageBreak;
		$this->SetAutoPageBreak(false, 0);	//Deshabilita auto pagina
		$img_file = '../ensayoEficacia/img/fondo_certificado.png';
		$this->Image($img_file, 0, 0, 209, 296, 'PNG', '', '', false, 300, '', false, false, 0);
		$this->SetAutoPageBreak($auto_page_break, $bMargin);	//Habilita auto pagina
		$this->setPageMark();											//pone marca de agua

	}

	public function Footer() {
		$this->SetY(-17);
		$this->SetFont(PDF_FONT_NAME_MAIN, 'I', 8);
		$this->Cell(0, 10, 'Página: '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}


}

?>