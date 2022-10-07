<?php
/**
 * Controlador para reportes de pdf
 *
 * @date    2019-07-20
 *
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use TCPDF;

class ReportesPdfModelo extends TCPDF{

	// Page header
	public function Header(){
		$this->setJPEGQuality(90);
		$bMargin = $this->getBreakMargin();
		$auto_page_break = $this->AutoPageBreak;
		$this->SetAutoPageBreak(false, 0);
		$img_file = HIST_CLI_URL_IMG . "fondoCertificado.png"; // fondo_certificado5 //fondoCertificado
		$this->Image($img_file, 0, 0, 209, 296, 'PNG', '', '', false, 300, '', false, false, 0);
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		$this->setPageMark();
	}

	public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false){
		parent::AddPage();
	}

	public function Footer(){
		$this->SetY(- 30);
		$this->SetFont(PDF_FONT_NAME_MAIN, 'I', 8);
		$this->writeHTMLCell('', '', 10, '', '<b>Dirección: </b>Av. Eloy Alfaro N30-350 y Amazonas, esq. <b>&middot; Código Postal: </b>170518 / Quito - Ecuador <b>&middot; Teléfono: </b> 593-2 256-7232', 'T', 0, 0, true, 'C', true);
		$this->Ln();
		$this->Cell(0, 0, 'www.agrocalidad.gob.ec', 0, false, 'C', 0, '', 0, false, 'T', 'M');
		$this->SetY(- 12);
		// $this->Cell(0, 10, 'Pagina ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}

	public function textoHorizontal($aling, $fuent, $texto, $x, $y, $tipoLetra, $tamaño, $ancho, $posYtxt, $posXtxt = 0, $tamanio = 0, $color = 0){
		$this->SetFont($tipoLetra, $fuent, $tamaño);
		$y1 = $y;
		$x1 = $x;
		$trans = null;
		$style = array(
			'width' => 0.2);
		if ($color){
			$colorFondo = array(
				235,
				245,
				251);
			$trans = 'DF';
		}else{
			$colorFondo = array(
				255,
				255,
				255);
		}
		$this->RoundedRect($x1, $y1, $ancho, 6, 0.1, '1111', $trans, $style, $colorFondo);

		$this->writeHTMLCell($ancho + 1, '', $x1 + $posXtxt + 0.5, $y1 - 0.3 + $posYtxt, $this->cortarTexto($texto, $tamanio), '', 1, 0, true, $aling, true);
	}

	public function cortarTexto($txt, $tamanio){
		$newTxt = substr($txt, 0, $tamanio);
		return $newTxt;
	}

	public function crearTabla($margenIzq, $y, $ancho, $alto){
		$style = array(
			'width' => 0.2);
		$this->RoundedRect($margenIzq, $y, $ancho, $alto, 0.1, '0110', null, $style, array(
			255,
			255,
			255));
	}

	public function firma($x, $y, $ancho, $altoCelda, $txtFirma, $tipoLetra){
		$this->SetFont($tipoLetra, 'B', 10);
		$style = array(
			'width' => 0.2);

		$styleQr = array(
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
		$arrayColor = array(
			255,
			255,
			255);

		// $datosQR='
		// Identificador del paciente: '.$txtFirma['identificador'].'
		// Nombre del paciente: '.$txtFirma['funcionario'].'

		// CMP: '.$txtFirma['identificador_medico'].'
		// Nombre del médico: '.$txtFirma['nombre_medico'].'
		// Cargo: '.$txtFirma['cargo_medico'];

		$this->RoundedRect($x, $y, $ancho, $altoCelda, 0.1, '1111', null, $style, $arrayColor);
		$this->Line($x + $ancho / 4, $y + $altoCelda - 8, $x + ($ancho - $ancho / 4), $y + $altoCelda - 8);
		$this->writeHTMLCell($ancho, 0, $x + 2, $y + $altoCelda - 7, 'Firma del Trabajador', '', 1, 0, TRUE, 'C', TRUE);

		$this->RoundedRect($x + $ancho, $y, $ancho, $altoCelda, 0.1, '1111', null, $style, $arrayColor);
		$this->writeHTMLCell($ancho, 0, $x + 2 + $ancho, $y + $altoCelda - 7, 'Huella del Trabajador', '', 1, 0, TRUE, 'C', TRUE);

		$this->RoundedRect($x, $y + $altoCelda, $ancho, $altoCelda, 0.1, '1111', null, $style, $arrayColor);
		$this->writeHTMLCell($ancho, 0, $x, $y + ($altoCelda * 2) - 7, 'Realizado por:', '', 1, 0, TRUE, 'R', TRUE);
		// $this->write2DBarcode($datosQR, 'QRCODE,Q', $x+$ancho/3, $y+$altoCelda+1, 26, 26, $styleQr, 'N');

		$this->RoundedRect($x + $ancho, $y + $altoCelda, $ancho, $altoCelda, 0.1, '1111', null, $style, $arrayColor);
		$this->Line($x + 10 + $ancho, $y + $altoCelda + $altoCelda / 3, $x + $ancho * 2 - 10, $y + $altoCelda + $altoCelda / 3);
		$this->SetFont($tipoLetra, '', 12);
		$this->writeHTMLCell($ancho, 0, $x + 2 + $ancho, $y + $altoCelda + $altoCelda / 3 - 8, 'f. Autorizado mediante Sistema GUIA', '', 1, 0, TRUE, 'C', TRUE);
		$this->SetFont($tipoLetra, 'B', 10);
		$this->writeHTMLCell($ancho, 0, $x + 2 + $ancho, $y + $altoCelda + $altoCelda / 3 + 1, 'Dr/Dra: ' . $txtFirma['nombre_medico'], '', 1, 0, TRUE, 'C', TRUE);
		$this->writeHTMLCell($ancho, 0, $x + 2 + $ancho, $y + $altoCelda + $altoCelda / 3 + 6, 'CMP: ' . $txtFirma['identificador_medico'], '', 1, 0, TRUE, 'C', TRUE);
		$this->SetFont($tipoLetra, 'B', 10);
		$this->writeHTMLCell($ancho, 0, $x + 2 + $ancho, $y + $altoCelda + $altoCelda / 3 + 11, $txtFirma['cargo_medico'], '', 1, 0, TRUE, 'C', TRUE);
	}

	public function crearFila($aling, $fuent, array $texto, $x, $y, $tipoLetra, $tamaño, $ancho, $posYtxt, $posXtxt = 0, $tamanio = 0, $color = 0, $opt = 0){
		$this->SetFont($tipoLetra, $fuent, $tamaño);
		$y1 = $y;
		$x1 = $x;

		switch ($color) {
			case 1:
				$this->SetFillColor(235, 245, 251);
			break;
			case 2:
				$this->SetFillColor(232, 218, 239);
			break;
			case 3:
				$this->SetFillColor(234, 237, 237);
			break;
			case 4:
				$this->SetFillColor(178, 186, 187);
			break;
			default:
			break;
		}

		if ($opt){
			$primer = strlen($texto[0]);
			$segundo = strlen($texto[2]);
			if ($primer >= $segundo && $primer > 60){
				$texto[2] .= '<br>';
				$texto[1] .= '<br>';
				if ($segundo == 0){
					$texto[2] .= '<br>';
				}
			}
		}
		$this->writeHTMLCell($ancho + 20, '', $x1 + $posXtxt + 0.5, $y1 - 0.3 + $posYtxt, $texto[0], '', 1, 1, true, $aling, true);
		$this->writeHTMLCell($ancho - 20 + 1, '', $x1 + $posXtxt + 0.5 + $ancho + 20, $y1 - 0.3 + $posYtxt, $this->cortarTexto($texto[1], $ancho - 35), '', 1, 1, true, $aling, true);
		$this->writeHTMLCell($ancho + 1, '', $x1 + $posXtxt + 0.5 + $ancho * 2, $y1 - 0.3 + $posYtxt, $this->cortarTexto($texto[2], $ancho), '', 1, 1, true, $aling, true);
	}

	public function crearObservaciones($aling, $fuent, $texto, $x, $y, $tipoLetra, $tamaño, $ancho){
		$this->SetFont($tipoLetra, $fuent, $tamaño);
		$this->writeHTMLCell($ancho, '', $x, $y, $texto, '', 1, 0, true, $aling, true);
	}

	public function crearApto($fuent, $x, $y, $tipoLetra, $tamaño, $width, $apto){
		$this->SetFont($tipoLetra, $fuent, $tamaño);
		$y1 = $y;
		$x1 = $x;
		$style = array(
			'width' => 0.2);
		$resul1 = $resul2 = $resul3 = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		if ($apto == 'Apto'){
			$resul1 = 'X';
		}else if ($apto == 'Apto condicionado'){
			$resul2 = 'X';
		}else{
			$resul3 = 'X';
		}
		$this->RoundedRect($x1 + 10, $y1, $width - 20, 6, 0.1, '1111', null, $style);
		$this->RoundedRect($x1 + 40, $y1 + 1, 4, 4, 0.1, '1111', null, $style);
		$this->RoundedRect($x1 + $width / 2, $y1 + 1, 4, 4, 0.1, '1111', null, $style);
		$this->RoundedRect($x1 + $width - 40, $y1 + 1, 4, 4, 0.1, '1111', null, $style);
		$this->writeHTMLCell(30, '', $x1 + 15 + 0.5, $y1 - 0.4, 'Apto     ' . $resul1, '', 1, 0, true, 'R', true);
		$this->writeHTMLCell(60, '', $x1 + $width / 2 - 55 + 0.5, $y1 - 0.4, 'Apto Condicionado     ' . $resul2, '', 1, 0, true, 'R', true);
		$this->writeHTMLCell(30, '', $x1 + $width - 65 + 0.5, $y1 - 0.4, 'No Apto     ' . $resul3, '', 1, 0, true, 'R', true);
	}
}

class ReportesPdfRecetaModelo extends TCPDF{

	// Page header
	public function Header(){
		$this->setJPEGQuality(90);
		$bMargin = $this->getBreakMargin();
		$auto_page_break = $this->AutoPageBreak;
		$this->SetAutoPageBreak(false, 0);
		$img_file = HIST_CLI_URL_IMG . "fondoCertificado.png"; // fondo_certificado5 //fondoCertificado
		$this->Image($img_file, 0, 0, 209, 296, 'PNG', '', '', false, 300, '', false, false, 0);
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		$this->setPageMark();
	}

	public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false){
		parent::AddPage();
	}

	public function Footer(){
		$this->SetY(- 30);
		$this->SetFont(PDF_FONT_NAME_MAIN, 'I', 8);
		$this->Ln();
	}

	public function fecha($ciudad, $opt, $fecha){
		$date = new \DateTime($fecha);
		$meses = array(
			"Enero",
			"Febrero",
			"Marzo",
			"Abril",
			"Mayo",
			"Junio",
			"Julio",
			"Agosto",
			"Septiembre",
			"Octubre",
			"Noviembre",
			"Diciembre");
		if ($opt == 1){
			$fechaFinal = $ciudad . ', ' . $date->format('d') . " de " . $meses[$date->format('n') - 1] . " del " . $date->format('Y') . ' ';
		}else if ($opt == 2){
			$fechaFinal = $date->format('d') . " de " . $meses[$date->format('n') - 1] . " del " . $date->format('Y') . ' ';
		}

		return $fechaFinal;
	}

	public function firma($x, $y, $ancho, $txtFirma, $tipoLetra){
		$this->SetFont($tipoLetra, '', 12);
		$this->writeHTMLCell($ancho, 0, $x, $y, 'f. Autorizado mediante Sistema GUIA', '', 1, 0, TRUE, 'C', TRUE);
		$this->Line($x + 30, $y + 8, $x + 120, $y + 8);
		$this->SetFont($tipoLetra, 'B', 10);
		$this->writeHTMLCell($ancho, 0, $x, $y + 9, 'Dr/Dra: ' . $txtFirma['nombre_medico'], '', 1, 0, TRUE, 'C', TRUE);
		$this->writeHTMLCell($ancho, 0, $x, $y + 13, 'CMP: ' . $txtFirma['indetificador_medico'], '', 1, 0, TRUE, 'C', TRUE);
		$this->SetFont($tipoLetra, 'B', 10);
		$this->writeHTMLCell($ancho, 0, $x, $y + 17, $txtFirma['cargo_medico'], '', 1, 0, TRUE, 'C', TRUE);
	}

	public function crearTabla($margenIzq, $y, $ancho, $alto){
		$style = array(
			'width' => 0.2);
		$this->RoundedRect($margenIzq, $y, $ancho, $alto, 0.1, '0110', null, $style, array(
			255,
			255,
			255));
	}

	public function crearTablaConsulta($margenIzq, $y, $ancho, $txtInfo, $opt = 0){
		if ($opt){
			$this->SetFillColor(178, 186, 187);
		}else{
			$this->SetFillColor(255, 255, 255);
		}
		$this->writeHTMLCell($ancho, 0, $margenIzq, $y, $txtInfo['medicamento'], 1, 1, 1, TRUE, 'C', TRUE);
		$this->writeHTMLCell($ancho + 10, 0, $margenIzq + $ancho, $y, $txtInfo['forma'], 1, 1, 1, TRUE, 'C', TRUE);
		$this->writeHTMLCell($ancho - 10, 0, $margenIzq + $ancho * 2 + 10, $y, $txtInfo['concentracion'], 1, 1, 1, TRUE, 'C', TRUE);
	}

	public function crearTablaIndicacion($margenIzq, $y, $ancho, $txtInfo, $opt = 0){
		if ($opt){
			$this->SetFillColor(178, 186, 187);
		}else{
			$this->SetFillColor(255, 255, 255);
		}
		$this->writeHTMLCell($ancho, 0, $margenIzq, $y, '<strong>' . $txtInfo['medicamento'] . '</strong>: ' . $txtInfo['indicaciones'], 1, 1, 1, TRUE, 'L', TRUE);
	}
}

class ReportesPdfCertificadoModelo extends TCPDF{

	// Page header
	public function Header(){
		$this->setJPEGQuality(90);
		$bMargin = $this->getBreakMargin();
		$auto_page_break = $this->AutoPageBreak;
		$this->SetAutoPageBreak(false, 0);
		$img_file = HIST_CLI_URL_IMG . "fondoCertificado.png"; // fondo_certificado5 //fondoCertificado
		$this->Image($img_file, 0, 0, 209, 296, 'PNG', '', '', false, 300, '', false, false, 0);
		$this->SetAutoPageBreak($auto_page_break, $bMargin);
		$this->setPageMark();
	}

	public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false){
		parent::AddPage();
	}

	public function Footer(){
		$this->SetY(- 30);
		$this->SetFont(PDF_FONT_NAME_MAIN, 'I', 8);
		$this->Ln();
	}

	public function fecha($ciudad, $opt, $fecha){
		$date = new \DateTime($fecha);
		$meses = array(
			"Enero",
			"Febrero",
			"Marzo",
			"Abril",
			"Mayo",
			"Junio",
			"Julio",
			"Agosto",
			"Septiembre",
			"Octubre",
			"Noviembre",
			"Diciembre");
		if ($opt == 1){
			$fechaFinal = $ciudad . ', ' . $date->format('d') . " de " . $meses[$date->format('n') - 1] . " del " . $date->format('Y') . ' ';
		}else if ($opt == 2){
			$fechaFinal = $date->format('d') . " de " . $meses[$date->format('n') - 1] . " del " . $date->format('Y') . ' ';
		}

		return $fechaFinal;
	}

	public function firma($x, $y, $ancho, $txtFirma, $tipoLetra){
		$this->SetFont($tipoLetra, '', 12);
		$this->writeHTMLCell($ancho, 0, $x, $y, 'f. Autorizado mediante Sistema GUIA', '', 1, 0, TRUE, 'C', TRUE);
		$this->Line($x + 30, $y + 8, $x + 120, $y + 8);
		$this->SetFont($tipoLetra, 'B', 12);
		$this->writeHTMLCell($ancho, 0, $x, $y + 9, 'Dr/Dra: ' . $txtFirma['nombre_medico'], '', 1, 0, TRUE, 'C', TRUE);
		$this->writeHTMLCell($ancho, 0, $x, $y + 13, 'CMP: ' . $txtFirma['indetificador_medico'], '', 1, 0, TRUE, 'C', TRUE);
		$this->SetFont($tipoLetra, 'B', 12);
		$this->writeHTMLCell($ancho, 0, $x, $y + 17, $txtFirma['cargo_medico'], '', 1, 0, TRUE, 'C', TRUE);
	}

	public function crearTabla($margenIzq, $y, $ancho, $alto){
		$style = array(
			'width' => 0.2);
		$this->RoundedRect($margenIzq, $y, $ancho, $alto, 0.1, '0110', null, $style, array(
			255,
			255,
			255));
	}

	public function crearTablaConsulta($margenIzq, $y, $ancho, $txtInfo, $opt = 0){
		if ($opt){
			$this->SetFillColor(178, 186, 187);
		}else{
			$this->SetFillColor(255, 255, 255);
		}
		$this->writeHTMLCell($ancho, 0, $margenIzq, $y, $txtInfo['medicamento'], 1, 1, 1, TRUE, 'C', TRUE);
		$this->writeHTMLCell($ancho + 10, 0, $margenIzq + $ancho, $y, $txtInfo['forma'], 1, 1, 1, TRUE, 'C', TRUE);
		$this->writeHTMLCell($ancho - 10, 0, $margenIzq + $ancho * 2 + 10, $y, $txtInfo['concentracion'], 1, 1, 1, TRUE, 'C', TRUE);
	}

	public function crearTablaIndicacion($margenIzq, $y, $ancho, $txtInfo, $opt = 0){
		if ($opt){
			$this->SetFillColor(178, 186, 187);
		}else{
			$this->SetFillColor(255, 255, 255);
		}
		$this->writeHTMLCell($ancho, 0, $margenIzq, $y, '<strong>' . $txtInfo['medicamento'] . '</strong>: ' . $txtInfo['indicaciones'], 1, 1, 1, TRUE, 'L', TRUE);
	}
}