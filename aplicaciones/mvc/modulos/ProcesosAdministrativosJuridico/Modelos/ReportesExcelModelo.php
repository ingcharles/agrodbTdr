<?php
/**
 * Controlador reporte excel
 *
 * @date    2019-07-20
 *
 */
namespace Agrodb\ProcesosAdministrativosJuridico\Modelos;

use PhpOffice\PhpSpreadsheet\Spreadsheet as Excel;

class ReportesExcelModelo extends Excel{

	public function crearCabeceraExcel($y, $titulo, $colorFill, $border, $tamaño, $col){
		$styleArray = $this->estylos($border, $tamaño, $colorFill);
		$col = $this->getNameFromNumber($col);
		$this->getActiveSheet()->mergeCells("B" . ($y) . ":" . $col . '' . ($y));
		$this->getActiveSheet()
			->getRowDimension($y)
			->setRowHeight(20);
		$this->getActiveSheet()
			->getStyle("B" . $y . ":" . $col . '' . $y)
			->applyFromArray($styleArray);
		$this->getActiveSheet()->setCellValue("B" . $y, $titulo);
	}

	public function cuerpoInspeccion($y, $texto, $colorFill, $border, $tamaño, $row, $fil, $col){
		$styleArray = $this->estylos($border, $tamaño, $colorFill);
		$this->getActiveSheet()->mergeCells($fil . ($y) . ":" . $col . ($y + $row));
		// $this->getActiveSheet()->getRowDimension($y)->setRowHeight(20);
		$this->getActiveSheet()
			->getStyle($fil . $y . ":" . $col . $y)
			->applyFromArray($styleArray);
		$this->getActiveSheet()->setCellValue($fil . $y, $texto);
	}

	public function cuerpoDinamicoHorizontal($y, $texto, $colorFill, $border, $tamaño, $row, $fil, $col, $ancho = null){
		$styleArray = $this->estylos($border, $tamaño, $colorFill);
		if($col == -1){
			$col = 0;
		}
		$col = $this->getNameFromNumber($fil + $col);
		$fil = $this->getNameFromNumber($fil);
		$this->getActiveSheet()->mergeCells($fil . ($y) . ":" . $col . ($y + $row));
		if ($ancho != null){
			$this->getActiveSheet()
				->getColumnDimension($fil)
				->setWidth($ancho);
		}
		$this->getActiveSheet()
			->getStyle($fil . $y . ":" . $fil . $y)
			->applyFromArray($styleArray);
		$this->getActiveSheet()->setCellValue($fil . $y, $texto);
	}

	public function cuerpoDinamicoVertical($y, $texto, $colorFill, $border, $tamaño, $row, $fil, $col, $alto, $ancho){
		$styleArray = $this->estylos($border + 1, $tamaño, $colorFill);
		$col = $this->getNameFromNumber($fil + $col);
		$fil = $this->getNameFromNumber($fil);
		$this->getActiveSheet()->mergeCells($fil . ($y) . ":" . $col . ($y + $row));
		$this->getActiveSheet()
			->getStyle($fil . $y . ":" . $fil . $y)
			->applyFromArray($styleArray);

		$this->getActiveSheet()->setCellValue($fil . $y, $texto);
		$this->getActiveSheet()
			->getColumnDimension($fil)
			->getWidth($ancho);
	}

	public function estylos($border, $tamaño, $colorFill){
		switch ($border) {
			case 1:
				$styleArray = [
					'font' => [
						'bold' => false,
						'size' => $tamaño],
					'alignment' => [
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
						'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
						'wrapText' => true],
					'fill' => [
						'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
						'rotation' => 90,
						'color' => [
							'argb' => $colorFill]],
					'borders' => [
						'outline' => [
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => [
								'argb' => '000000']]]];
			break;
			case 2:
				$styleArray = [
					'font' => [
						'bold' => false,
						'size' => $tamaño],
					'alignment' => [
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
						'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
						'textRotation' => 90,
						'wrapText' => true],

					'fill' => [
						'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
						'rotation' => 90,
						'color' => [
							'argb' => $colorFill]],
					'borders' => [
						'outline' => [
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => [
								'argb' => '000000']]]];
			break;
			case 3:
				$styleArray = [
					'font' => [
						'bold' => false,
						'size' => $tamaño],
					'alignment' => [
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
						'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
						'textRotation' => 90]];
			break;
			case 4:
				$styleArray = [
					'font' => [
						'bold' => false,
						'size' => $tamaño],
					'alignment' => [
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
						'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]];
			break;
			case 5:
				$styleArray = [
					'font' => [
						'bold' => false,
						'size' => $tamaño],
					'alignment' => [
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
						'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
						'wrapText' => true],
					'fill' => [
						'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
						'rotation' => 90,
						'color' => [
							'argb' => $colorFill]],
					'borders' => [
						'outline' => [
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => [
								'argb' => '000000']]]];
			break;
			case 6:
				$styleArray = [
					'font' => [
						'bold' => false,
						'size' => $tamaño
						],
					'alignment' => [
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_JUSTIFY,
						'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
						'wrapText' => true,
						//'shrinkToFit' => true,
						],
					'fill' => [
						'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
						'rotation' => 90,
						'color' => [
							'argb' => $colorFill]],
					'borders' => [
						'outline' => [
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => [
								'argb' => '000000']]]];
			break;
			case 7:
				$styleArray = [
				'font' => [
				'bold' => true,
				'size' => $tamaño
				],
				'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'wrapText' => true,
				//'shrinkToFit' => true,
				],
				'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'rotation' => 90,
				'color' => [
				'argb' => $colorFill]],
				'borders' => [
				'top' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color' => [
				'argb' => '000000']]]];
				break;
			default:
				$styleArray = [
					'font' => [
						'bold' => true,
						'size' => $tamaño],
					'alignment' => [
						'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
						'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]];
			break;
		}
		return $styleArray;
	}

	function getNameFromNumber($num){
		return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($num);
	}
}