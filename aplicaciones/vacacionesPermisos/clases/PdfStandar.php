<?php


require_once '../general/tcpdf/tcpdf.php';
require_once '../general/tcpdf/lang/spa.php';

class PdfStandar extends TCPDF{
	private $esBorrador=false;
	public function PonerBorrador($esBorrador=false){
		$this->esBorrador=$esBorrador;
	}
	
	public function AddPage($orientation='', $format='', $keepmargins=false, $tocpage=false){
		parent::AddPage();
		if($this->esBorrador){
			parent::SetFont('times', '', 100);
			parent::SetTextColor(0,15,0,2);
			parent::SetXY(30,30);
			parent::StartTransform();
			parent::Rotate(-35, 35, 45);
			parent::Text(35,45, 'BORRADOR',false,false,true);
			parent::StopTransform();
			parent::SetTextColor();
			parent::SetFont('times', '', 8);
		}
	}

	public function Header() {
		$this->setJPEGQuality(90);

		$bMargin = $this->getBreakMargin();
		$auto_page_break = $this->AutoPageBreak;
		$this->SetAutoPageBreak(false, 0);	//Deshabilita auto pagina
		$img_file = '../ensayoEficacia/img/fondo.png';
		$this->Image($img_file, 0, 0, 209, 296, 'PNG', '', '', false, 300, '', false, false, 0);
		$this->SetAutoPageBreak($auto_page_break, $bMargin);	//Habilita auto pagina
		$this->setPageMark();
	}

	public function Footer() {
		$this->SetY(-17);
		$this->SetFont(PDF_FONT_NAME_MAIN, 'I', 8);
		$this->Cell(0, 10, 'Pagina '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}


}

?>


