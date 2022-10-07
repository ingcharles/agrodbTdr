<?php
/**
 * Controlador para reportes de pdf
 * @date    2019-07-20
 *
 */

namespace Agrodb\InspeccionAntePostMortemCF\Modelos;
use TCPDF;

class ReportesPdfAnteModelo extends TCPDF{
		// Page header
		public function Header(){
			$this->setJPEGQuality(90);
			$bMargin = $this->getBreakMargin();
			$auto_page_break = $this->AutoPageBreak;
			$this->SetAutoPageBreak(false, 0);
			// $img_file = INSP_FORM_AP_CF_IMG . "fondo_certificado5.png";
			// $this->Image($img_file, 0, 0, 209, 296, 'PNG', '', '', false, 300, '', false, false, 0);
			$this->SetAutoPageBreak($auto_page_break, $bMargin);
			$this->setPageMark();
		}
		
		public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false){
			parent::AddPage();
		}
		
		public function Footer(){
			// Position at 15 mm from bottom
			$this->SetY(- 13);
			// Set font
			$this->SetFont('Helvetica', '', 6);
			// Page number
			$this->Cell(0, 10, 'Pagina ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
		}
		
		public function firma($x, $y, $ancho, $altoCelda, $txtHeader, $txtFirma, $tipoLetra, $arrayColor){
			$this->SetFont($tipoLetra, 'B', 8);
			$style = array(
				'width' => 0.2);
			$this->RoundedRect($x, $y, $ancho, $altoCelda, 1.2, '1111', 'DF', $style, $arrayColor);
			$this->writeHTMLCell($ancho, 0, $x, $y + 1, $txtHeader, '', 1, 0, TRUE, 'C', TRUE);
			$xfull = $this->getPageWidth() / 2;
			$this->setCellHeightRatio(1.25);
			$this->Line($xfull - strlen($txtFirma) - 3, $y + 25, $xfull + strlen($txtFirma) + 3, $y + 25);
			$this->writeHTMLCell(0, 0, $x, $y + 26, $txtFirma, '', 1, 0, TRUE, 'C', TRUE);
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
		
		public function crearTablaHeader($tipoLetra, $margenIzq, $ancho, $y, $txtHeader, $alto, $arrayColor, $altoCelda){
			$style = array(
				'width' => 0.2);
			$y = $this->GetY() + $y;
			$this->RoundedRect($margenIzq, $y, $ancho, $altoCelda, 1.20, '1001', 'DF', $style, $arrayColor);
			$this->writeHTMLCell(0, 0, $margenIzq, $y + 0.6, $txtHeader, '', 1, 0, FALSE, 'C', FALSE);
			$this->RoundedRect($margenIzq, $y + 6, $ancho, $alto, 1.20, '0110', 'DF', $style, array(
				255,
				255,
				255));
		}
		
		public function crearEncabezadoTabla($tipoLetra, $margenIzq, $ancho, $y, $txtHeader, $arrayColor, $altoCelda, $redondear, $postY = null){
			$style = array(
				'width' => 0.2);
			$this->RoundedRect($margenIzq, $y, $ancho, $altoCelda, $redondear, '1111', 'DF', $style, $arrayColor);
			$this->writeHTMLCell($ancho, $altoCelda, $margenIzq, $y + 0.6 + $postY, $txtHeader, '', 1, 0, FALSE, 'C', FALSE);
		}
		
		public function crearTablaFirma($tipoLetra, $margenIzq, $ancho, $y, $txtHeader, $alto, $arrayColor, $altoCelda, $redondear){
			$style = array(
				'width' => 0.2);
			$this->RoundedRect($margenIzq, $y, $ancho, $altoCelda, 1.20, '1001', 'DF', $style, $arrayColor);
			$this->writeHTMLCell(0, 0, $margenIzq, $y + 0.6, $txtHeader, '', 1, 0, FALSE, 'C', FALSE);
			$this->RoundedRect($margenIzq, $y + 6, $ancho, $alto, 1.20, '0110', 'DF', $style, array(
				255,
				255,
				255));
		}
		
		public function textoVertical($texto, $x, $y, $tipoLetra, $tamaño, $ancho, $posTxt = null){
			$this->SetFont($tipoLetra, '', $tamaño);
			$this->StartTransform();
			$y1 = $y + 33;
			$x1 = $x;
			$style = array(
				'width' => 0.2);
			$this->Rotate(90, $x1, $y1);
			$this->RoundedRect($x1, $y1, 30, $ancho, 0.1, '1111', 'DF', $style, array(
				255,
				255,
				255));
			$this->writeHTMLCell(29, '', $x1, $y + 33 + $posTxt, $texto, '', 1, 0, true, 'C', true);
			$this->StopTransform();
		}
		
		public function textoHorizontal($texto, $x, $y, $tipoLetra, $tamaño, $ancho, $posTxt, $postTxtX = 0, $tamanio = 0){
			$this->SetFont($tipoLetra, '', $tamaño);
			$y1 = $y;
			$x1 = $x;
			$style = array(
				'width' => 0.2);
			$this->RoundedRect($x1, $y1, $ancho, 4, 0.1, '1111', 'DF', $style, array(
				255,
				255,
				255));
			
			$this->writeHTMLCell($ancho + 1, '', $x1 + $postTxtX + 0.5, $y1 + 0.5 + $posTxt, $this->cortarTexto($texto, $tamanio), '', 1, 0, true, 'R', true);
		}
		
		// *******************************crear filas para el formulario animales**************************************************************************
		public function crearFilasAnimales($arrayGenera, $y2, $genera, $anima, $signos, $locomo, $dictamen, $observacion, $tipoLetra, $p1, $p2, $p3, $p4, $p5, $p6){
			$x = $genera;
			$tamañoLetra = 6;
			$ancho = $p1 / 9;
			$this->textoHorizontal($arrayGenera['fecha_formulario'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 13);
			$this->textoHorizontal($arrayGenera['num_csmi'], $x + $ancho, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 9);
			$this->textoHorizontal($arrayGenera['num_lote'], $x + $ancho * 2, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, - 2, 9);
			$this->textoHorizontal($arrayGenera['especie'], $x + $ancho * 3, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 10);
			$this->textoHorizontal($arrayGenera['categoria_etaria'], $x + $ancho * 4, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 8);
			$this->textoHorizontal($arrayGenera['peso_vivo_promedio'], $x + $ancho * 5, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 10);
			$this->textoHorizontal($arrayGenera['num_machos'], $x + $ancho * 6 - 1, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 10);
			$this->textoHorizontal($arrayGenera['num_hembras'], $x + $ancho * 7 - 2, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 8);
			$this->textoHorizontal($arrayGenera['num_total_animales'], $x + $ancho * 8 - 3, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 10);
			
			$x = $anima;
			$ancho = $p2 / 4;
			$this->textoHorizontal($arrayGenera['arraAnima']['num_animales_muertos'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho - 3, 0, 0, 6);
			$this->textoHorizontal($arrayGenera['arraAnima']['causa_probable'], $x + $ancho - 3, $y2, $tipoLetra, $tamañoLetra, $ancho + 9, 0, - 0.4, 19);
			$this->textoHorizontal($arrayGenera['arraAnima']['decomiso'], $x + $ancho * 2 + 6, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, - 3.5, 8);
			$this->textoHorizontal($arrayGenera['arraAnima']['aprovechamiento'], $x + $ancho * 3 + 3, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, - 3.5, 8);
			
			$x = $signos;
			$ancho = $p3 / 5;
			$this->textoHorizontal($arrayGenera['arraySignos']['num_animales_nerviosos'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['arraySignos']['num_animales_digestivo'], $x + $ancho, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['arraySignos']['num_animales_respiratorio'], $x + $ancho * 2, $y2, $tipoLetra, $tamañoLetra, $ancho + 2, 0, - 2, 5);
			$this->textoHorizontal($arrayGenera['arraySignos']['num_animales_vesicular'], $x + $ancho * 3, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['arraySignos']['num_animales_reproductivo'], $x + $ancho * 4, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 5);
			
			$x = $locomo;
			$ancho = $p4 / 2;
			$this->textoHorizontal($arrayGenera['arrayLocomo']['num_animales_cojera'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['arrayLocomo']['num_animales_ambulatorios'], $x + $ancho, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 5);
			
			$x = $dictamen;
			$ancho = $p5 / 4;
			$this->textoHorizontal($arrayGenera['matanza_normal'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['matanza_especiales'], $x + $ancho, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['matanza_emergencia'], $x + $ancho * 2, $y2, $tipoLetra, $tamañoLetra, $ancho + 2, 0, - 2, 5);
			$this->textoHorizontal($arrayGenera['aplazamiento_matanza'], $x + $ancho * 3, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 5);
			
			$x = $observacion;
			$ancho = $p6;
			$this->textoHorizontal($arrayGenera['observacion'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 33);
		}
		
		// *********************************crear filas para formulario de aves*************************************************************
		public function crearFilasAves($arrayGenera, $y2, $genera, $aves, $caract, $probl, $exter, $dictamen, $observacion, $tipoLetra, $p1, $p2, $p3, $p4, $p5, $p6, $p7){
			$x = $genera;
			$tamañoLetra = 5;
			$ancho = $p1 / 6;
			$this->textoHorizontal($arrayGenera['fecha_formulario'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 13);
			$this->textoHorizontal($arrayGenera['tipo_ave'], $x + $ancho + 1, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 15);
			$this->textoHorizontal($arrayGenera['lugar_procedencia'], $x + $ancho * 2 + 2, $y2, $tipoLetra, $tamañoLetra, $ancho + 5, 0, 0, 18);
			$this->textoHorizontal($arrayGenera['num_csmi'], $x + $ancho * 3 + 7, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 10);
			$this->textoHorizontal($arrayGenera['total_aves'], $x + $ancho * 4 + 7, $y2, $tipoLetra, $tamañoLetra, $ancho - 3, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['promedio_aves'], $x + $ancho * 5 + 4, $y2, $tipoLetra, $tamañoLetra, $ancho - 4, 0, 0, 6);
			
			$x = $aves;
			$ancho = $p2 / 3;
			$this->textoHorizontal($arrayGenera['arrayAves']['aves_muertas'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho - 2, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['arrayAves']['porcent_aves_muertas'], $x + $ancho - 2, $y2, $tipoLetra, $tamañoLetra, $ancho - 4, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['arrayAves']['causa_probable'], $x + $ancho * 2 - 6, $y2, $tipoLetra, $tamañoLetra, $ancho + 6, 0, 0, 17);
			
			$x = $caract;
			$ancho = $p3 / 4;
			$this->textoHorizontal($arrayGenera['arrayCaract']['decaidas'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['arrayCaract']['porcent_decaidas'], $x + $ancho + 1, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['arrayCaract']['num_traumas'], $x + $ancho * 2, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['arrayCaract']['porcent_traumas'], $x + $ancho * 3 + 1, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 5);
			
			$x = $probl;
			$ancho = $p4 / 6;
			$this->textoHorizontal($arrayGenera['arrayProble']['probl_respirat'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['arrayProble']['porcent_probl_respirat'], $x + $ancho + 1, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['arrayProble']['probl_nerviosos'], $x + $ancho * 2, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['arrayProble']['porcent_proble_nerviosos'], $x + $ancho * 3 + 1, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['arrayProble']['probl_digestivos'], $x + $ancho * 4, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['arrayProble']['porcent_probl_digestivos'], $x + $ancho * 5 + 1, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 5);
			
			$x = $exter;
			$ancho = $p5 / 4;
			$this->textoHorizontal($arrayGenera['arrayExtern']['cabeza_hinchada'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['arrayExtern']['porcent_cabeza_hinchada'], $x + $ancho + 1, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['arrayExtern']['plumas_erizadas'], $x + $ancho * 2, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['arrayExtern']['porcent_plumas_erizadas'], $x + $ancho * 3 + 1, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 5);
			
			$x = $dictamen;
			$ancho = $p6 / 9;
			$this->textoHorizontal($arrayGenera['faenamiento_normal'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['procent_faenamiento_normal'], $x + $ancho + 1, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['faenamiento_especial'], $x + $ancho * 2, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['porcent_faenamiento_especial'], $x + $ancho * 3 + 1, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['faenamiento_emergencia'], $x + $ancho * 4, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['porcent_emergencia'], $x + $ancho * 5 + 1, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['aplazamiento_faenamiento'], $x + $ancho * 6, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, 0, 7);
			$this->textoHorizontal($arrayGenera['porcent_aplazamiento_faenamiento'], $x + $ancho * 7 + 1, $y2, $tipoLetra, $tamañoLetra, $ancho - 1, 0, 0, 5);
			$this->textoHorizontal($arrayGenera['total_faenamiento'], $x + $ancho * 8, $y2, $tipoLetra, $tamañoLetra, $ancho + 1, 0, - 1, 7);
			
			$x = $observacion;
			$ancho = $p7;
			$this->textoHorizontal($arrayGenera['observacion'], $x, $y2, $tipoLetra, $tamañoLetra, $ancho, 0, 0, 28);
		}
		
		public function cortarTexto($txt, $tamanio){
			$newTxt = substr($txt, 0, $tamanio);
			return $newTxt;
		}
		
		public function verificarFilas($num, $opt = false){
			if (($num > 0 && $num <= 17) && $opt){
				return false;
			}
			if (($num > 17 && $num <= 25) && $opt){
				return true;
			}
			if (($num > 63 && $num <= 71) && $opt){
				return true;
			}
			if (($num > 145 && $num <= 161) && $opt){
				return true;
			}
			if ($num == 25){
				return true;
			}
			if ($num == 71){
				return true;
			}
			if ($num == 117){
				return true;
			}
			
			return false;
		}
	}