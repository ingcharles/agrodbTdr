<?php

require_once '../general/tcpdf/tcpdf.php';
require_once '../general/tcpdf/lang/spa.php';

class PdfAccionPersonal extends TCPDF{

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
		$this->SetAutoPageBreak($auto_page_break, $bMargin);	//Habilita auto pagina
		$this->setPageMark();											//pone marca de agua

	}

	public function Footer() {
		$this->SetY(-17);
		$y=$this->GetY()+1.5;
		$xfull=$this->getPageWidth()-12;
		$style = array(
			'width' => 0);
		$tamañoColumn = 25;
		$posicionX = $this->getPageWidth() - 6-$tamañoColumn;
		$this->RoundedRect($posicionX, $y, $tamañoColumn, 6, 0, '1111', 'DF', $style, array(
			215,
			215,
			215));
		$this->RoundedRect(6, $y, $tamañoColumn, 6, 0, '1111', 'DF', $style, array(
			215,
			215,
			215));
		$this->RoundedRect(6+$tamañoColumn, $y,$xfull-$tamañoColumn*2, 6, 0, '1111', 'DF', $style, array(
			243,
			243,
			243));
		$this->SetFont(PDF_FONT_NAME_MAIN, 'I', 6);
		$this->writeHTMLCell(0, 0, 10, $y+1.6, 'Fecha de creación de formato: 2014-05-27 / Revisión: 00 /', '', 1, 0, FALSE, 'C', FALSE);
		$this->writeHTMLCell(0, 0, $xfull-45, $y+1.6,'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), '', 1, 0, FALSE, 'L', FALSE);
		
	}
	
	public function crearTabla($tipoLetra, $margenIzq, $ancho, $y, $txtHeader, $alto, $arrayColor, $altoCelda){
		$style = array(
			'width' => 0.2);
		$this->RoundedRect($margenIzq, $y + 0.3, $ancho, $altoCelda, 1.20, '1001', 'DF', $style, $arrayColor);
		$this->writeHTMLCell(0, 0, $margenIzq, $y, $txtHeader, '', 1, 0, FALSE, 'C', FALSE);
		$this->RoundedRect($margenIzq, $y + 6, $ancho, $alto, 1.20, '0110', 'DF', $style, array(
			255,
			255,
			255));
	}
	public function crearCuadrado($tipoLetra, $margenIzq, $ancho, $y, $arrayColor, $altoCelda){
		$style = array(
			'width' => 0.3);
		$this->RoundedRect($margenIzq, $y, $ancho, $altoCelda, 0.1, '1111', 'DF', $style, $arrayColor);
	}
	public function crearTexto($tipoLetra, $negrita, $tamanio, $ancho, $x, $y, $texto, $alineacion, $auto=false){
		$this->SetFont($tipoLetra, $negrita, $tamanio);
		$this->writeHTMLCell($ancho, 0, $x, $y, $texto, '', 0, 0, true, $alineacion, true);
		
	}
	
	public function crearFirmaElectronica($tipoLetra,$x, $y, $tamañoColumn, $rutaQRG,$ancho,$arrayParametros,$datosCertificado,$opt='no'){
		$style = array(
			'border' => 0,
			'vpadding' => 'auto',
			'hpadding' => 'auto',
			'fgcolor' => array(
				0,
				0,
				0),
			'bgcolor' => false,
			'module_width' => 1,
			'module_height' => 1);
				
		$this->SetFont($tipoLetra, 'B', 8);
		// ***********************************QR EN FIRMA ELECTRONICA**********************
		$rutaQRG = '
		        Location: '.$datosCertificado['info']['Location'].'
		        Reason: '. $datosCertificado['info']['Reason'].'
		        ContactInfo: '.$datosCertificado['info']['ContactInfo'].'
		        FIRMADO POR: '.$datosCertificado['info']['Name'].'
		        FECHA FIRMADO:' . date('d-m-Y').$datosCertificado['rutaCertificado'];
		
		$this->write2DBarcode($rutaQRG, 'QRCODE,Q', $x, $y+3, 23,23, $style, 'N');
		
		//$this->setSignature($datosCertificado['rutaCertificado'], $datosCertificado['rutaCertificado'], $datosCertificado['password'], '',2, $datosCertificado['info']);
		
		//$this->setSignatureAppearance($x , $y +3, 23, 23,1);
		
		//$fir = new s
		
		if($opt == 'si'){
		$this->SetFont($tipoLetra, '', 8);
		$this->writeHTMLCell(0, '', $x + 23, $y+5, 'Firmado electrónicamente por:', 0, 0, 0, true, 'L', true);
		$this->Ln();
		$this->SetFont($tipoLetra, 'B', 9);
		$this->writeHTMLCell(90, '', $x  + 23, '', $arrayParametros['nombreFirma'], 0, 0, 0, true, 'L', true);
		$this->Ln();
		$this->crearTexto($tipoLetra, '', 8, '',$x+23, '', $arrayParametros['cargoFirma'], 'L');
		$y = $this->GetY();
		$this->Line($x  + 24, $y+7  ,$x+95, $y+7, '');
		$this->SetFont($tipoLetra, '', 6);
		$this->writeHTMLCell(82, '', $x  + 23, $y+7, 'Autorizado por:', 0, 0, false, false, 'L', true);
		$this->SetFont($tipoLetra, '', 8);
		$this->writeHTMLCell(82, '', $x  + 38, $y+7, $arrayParametros['nombreAutorizado'], 0, 0, false, false, 'L', true);
		$this->Ln();
		$this->crearTexto($tipoLetra, '', 8, '',$x+23, $y+10, $arrayParametros['cargoAutorizado'], 'L');
		}else{
			$this->SetFont($tipoLetra, '', 8);
			$this->writeHTMLCell(0, '', $x + 23, $y+8, 'Firmado electrónicamente por:', 0, 0, 0, true, 'L', true);
			$this->Ln();
			$this->SetFont($tipoLetra, 'B', 9);
			$this->writeHTMLCell(90, '', $x  + 23, '', $arrayParametros['nombreFirma'], 0, 0, 0, true, 'L', true);
			$this->Ln();
			$this->SetFont($tipoLetra, '', 9);
			$this->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$x, $y+$ancho-4, $arrayParametros['cargoFirma'], 'C');
		}
		
		
	}
	public function crearFirmaNormal($tipoLetra,$x, $y, $tamañoColumn, $ancho, $arrayParametros){
				
		if($arrayParametros['tthh'] == 'si'){
			$this->SetFont($tipoLetra, 'B', 11);
			
			$this->Line($x+20 , $y+14  ,$x+80, $y+14, '');
			$this->crearTexto($tipoLetra, 'B', 8, 10,$x+13, $y+11, 'f.', 'C');
			$this->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$x, $y+14, $arrayParametros['nombreFirma'], 'C');
			$this->crearTexto($tipoLetra, '', 8, $tamañoColumn,$x, $y+18, $arrayParametros['cargoFirma'], 'C');
			
			$this->Line($x+10 , $y+$ancho-7.5  ,$x+$tamañoColumn-10, $y+$ancho-7.5, '');
			$this->crearTexto($tipoLetra, '', 8, $tamañoColumn,$x, $y+$ancho-7, 'Autorizado por: '.$arrayParametros['nombreAutorizado'], 'C');
			$this->crearTexto($tipoLetra, '', 8, $tamañoColumn,$x, $y+$ancho-4, $arrayParametros['cargoAutorizado'], 'C');
			
		}else{
				$this->SetFont($tipoLetra, 'B', 11);
				$this->Line($x+20 , $y+$ancho-10  ,$x+80, $y+$ancho-10, '');
				$this->crearTexto($tipoLetra, 'B', 8, 10,$x+13, $y+$ancho-13, 'f.', 'C');
				$this->crearTexto($tipoLetra, 'B', 8, $tamañoColumn,$x, $y+$ancho-8, $arrayParametros['nombreFirma'], 'C');
				$this->crearTexto($tipoLetra, '', 8, $tamañoColumn,$x, $y+$ancho-4, $arrayParametros['cargoFirma'], 'C');
		}
				
				
				
	}
	public function crearFirmaDocumento($tipoLetra, $negrita, $tamanio, $ancho, $x, $y, $texto, $alineacion, $auto=false){
		$this->SetFont($tipoLetra, $negrita, $tamanio);
		$this->writeHTMLCell($ancho, 0, $x, $y, $texto, '', 0, 0, true, $alineacion, true);
		
	}

}

?>