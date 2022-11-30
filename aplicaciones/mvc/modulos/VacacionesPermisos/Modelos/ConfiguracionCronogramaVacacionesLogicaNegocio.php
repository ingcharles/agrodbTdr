<?php
 /**
 * Lógica del negocio de ConfiguracionCronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo ConfiguracionCronogramaVacacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-11-21
 * @uses    ConfiguracionCronogramaVacacionesLogicaNegocio
 * @package VacacionesPermisos
 * @subpackage Modelos
 */
  namespace Agrodb\VacacionesPermisos\Modelos;
  
  use Agrodb\VacacionesPermisos\Modelos\IModelo;
  use Agrodb\Core\Constantes;
  use PhpOffice\PhpSpreadsheet\IOFactory;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
  use PhpOffice\PhpSpreadsheet\Spreadsheet;
 
class ConfiguracionCronogramaVacacionesLogicaNegocio implements IModelo 
{

	 private $modeloConfiguracionCronogramaVacaciones = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloConfiguracionCronogramaVacaciones = new ConfiguracionCronogramaVacacionesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{

			$tablaModelo = new ConfiguracionCronogramaVacacionesModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			if ($tablaModelo->getIdConfiguracionCronogramaVacacion() != null && $tablaModelo->getIdConfiguracionCronogramaVacacion() > 0) {
			return $this->modeloConfiguracionCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdConfiguracionCronogramaVacacion());
			} else {
			unset($datosBd["id_configuracion_cronograma_vacacion"]);
			return $this->modeloConfiguracionCronogramaVacaciones->guardar($datosBd);
			}
	}

	public function guardarEnviarDirectorEjecutivo(Array $datos)
	{

		$proceso = true;

		//TODO_: Generar el archivo de excel y el archivo de pdf
		//generamos la cosulta personalizada y devolvemos en una variable

		$generarExcel = $this->exportarArchivoExcelEstadoSolicitudes(array('hola' => 'dd'));

		if($generarExcel /*&& $generarPdf*/){

			$tablaModelo = new ConfiguracionCronogramaVacacionesModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			//cambiar por actualizarSql

			if ($tablaModelo->getIdConfiguracionCronogramaVacacion() != null && $tablaModelo->getIdConfiguracionCronogramaVacacion() > 0) {
			return $this->modeloConfiguracionCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdConfiguracionCronogramaVacacion());
			} else {
			unset($datosBd["id_configuracion_cronograma_vacacion"]);
			return $this->modeloConfiguracionCronogramaVacaciones->guardar($datosBd);
			}

		}else{

			$proceso = false;

		}

		return $proceso;

	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloConfiguracionCronogramaVacaciones->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ConfiguracionCronogramaVacacionesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloConfiguracionCronogramaVacaciones->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloConfiguracionCronogramaVacaciones->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloConfiguracionCronogramaVacaciones->buscarLista($where, $order, $count, $offset);
	}

	public function buscarEstadosConfiguracionCronogramaVacaciones()
	{
		$consulta = "SELECT DISTINCT estado_configuracion_cronograma_vacacion as estado FROM ".$this->modeloConfiguracionCronogramaVacaciones->getEsquema().". configuracion_cronograma_vacaciones";
		 return $this->modeloConfiguracionCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}
	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarConfiguracionCronogramaVacaciones()
	{
	$consulta = "SELECT * FROM ".$this->modeloConfiguracionCronogramaVacaciones->getEsquema().". configuracion_cronograma_vacaciones";
		 return $this->modeloConfiguracionCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta un reporte en Excel de los estado de las soliictudes de habilitacion
	 *
	 * @return array|ResultSet
	 */
	public function exportarArchivoExcelEstadoSolicitudes($datos){
	    $hoja = new Spreadsheet();
	    $documento = $hoja->getActiveSheet();
	    $i = 3;
	    
	    $estiloArrayTitulo = [
	        'alignment' => [
	            'horizontal' => 'center',
	            'vertical' => 'center',
	        ],
	        'font' => [
	            'name' => 'Calibri',
	            'bold' => true,
	            'size' => 18
	        ]
	    ];
	    
	    $estiloArrayCabecera = [
	        'alignment' => [
	            'horizontal' => 'center',
	            'vertical' => 'center',
	        ],
	        'borders' => [
	            'allBorders' => [
	                'borderStyle' => 'thin',
	                'color' => ['argb' => 'FF000000'],
	            ],
	        ],
	        'font' => [
	            'name' => 'Calibri',
	            'bold' => true,
	            'size' => 11,
	            'color' => ['argb' => 'FFFFFFFF'],
	        ],
	        'fill' => [
	            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
	            'rotation' => 90,
	            'startColor' => [
	                'argb' => 'FF6495ED',
	            ],
	            'endColor' => [
	                'argb' => 'FF6495ED',
	            ],
	        ],
	    ];
	    
	    /*$estiloArrayDetalle = [
	        'borders' => [
	            'allBorders' => [
	                'borderStyle' => 'thin',
	                'color' => ['argb' => 'FF000000'],
	            ],
	        ],
	        'font' => [
	            'name' => 'Calibri',
	            'bold' => true,
	            'size' => 11,
	            'color' => ['argb' => 'FFFFFFFF'],
	        ],
	    ];*/
	    
	    $documento->getStyle('A1:D1')->applyFromArray($estiloArrayTitulo);
	    $documento->getStyle('A2:S2')->applyFromArray($estiloArrayCabecera);
	    
	    $documento->setCellValueByColumnAndRow(1, 1, 'Reporte general de planificación de cronograma de vacaciones año');
	    $documento->mergeCells('A1:D1');
	    $documento->getColumnDimension('A')->setAutoSize(true);
	    $documento->getColumnDimension('B')->setAutoSize(true);
	    $documento->getColumnDimension('C')->setAutoSize(true);
	    $documento->getColumnDimension('D')->setAutoSize(true);
	    $documento->getColumnDimension('E')->setAutoSize(true);
	    $documento->getColumnDimension('F')->setAutoSize(true);
	    $documento->getColumnDimension('G')->setAutoSize(true);
	    $documento->getColumnDimension('H')->setAutoSize(true);
	    $documento->getColumnDimension('I')->setAutoSize(true);
	    $documento->getColumnDimension('J')->setAutoSize(true);
	    $documento->getColumnDimension('K')->setAutoSize(true);
	    $documento->getColumnDimension('L')->setAutoSize(true);
	    $documento->getColumnDimension('M')->setAutoSize(true);
	    $documento->getColumnDimension('N')->setAutoSize(true);
	    $documento->getColumnDimension('O')->setAutoSize(true);
	    $documento->getColumnDimension('P')->setAutoSize(true);
	    $documento->getColumnDimension('Q')->setAutoSize(true);
	    $documento->getColumnDimension('R')->setAutoSize(true);
	    $documento->getColumnDimension('S')->setAutoSize(true);
	    
	    $documento->setCellValue('A2','Fecha creación');
	    $documento->setCellValue('B2','N° de Solicitud');
	    $documento->setCellValue('C2','Nombre operador');
	    $documento->setCellValue('D2','Provincia Operador');
	    $documento->setCellValue('E2','RUC/RISE');	    
	    $documento->setCellValue('F2','Nombre fabricante');
	    $documento->setCellValue('G2','País fabricante');
	    $documento->setCellValue('H2','Dirección fabricante');
	    $documento->setCellValue('I2','Fecha aprobación solicitud');	    
	    $documento->setCellValue('J2','Código aprobación solicitud');	    
	    $documento->setCellValue('K2','Estado');
	    $documento->setCellValue('L2','Provincia Técnico');
	    $documento->setCellValue('M2','Identificador revisor');
	    $documento->setCellValue('N2','Nombre revisor');
	    $documento->setCellValue('O2','Fecha asignación solicitud');
	    $documento->setCellValue('P2','Fecha respuesta solicitud');
	    $documento->setCellValue('Q2','Decisión');
	    $documento->setCellValue('R2','Tiempo real de atención');
	    $documento->setCellValue('S2','Observación');
	    
	    /*if ($datos != ''){
	        foreach ($datos as $fila){
	            //$documento->getStyle('A'.$i, 'M'.$i)->applyFromArray($estiloArrayDetalle);
	            $documento->setCellValueByColumnAndRow(1, $i, $fila['fecha_creacion_solicitud'] == "" ? "" : date('Y-m-d', strtotime($fila['fecha_creacion_solicitud'])));
	            $documento->setCellValueByColumnAndRow(2, $i, $fila['codigo_creacion_solicitud']);
	            $documento->setCellValueByColumnAndRow(3, $i, $fila['nombre_operador']);
	            $documento->setCellValueByColumnAndRow(4, $i, $fila['nombre_provincia_operador']);
	            $documento->getCellByColumnAndRow(5, $i)->setValueExplicit($fila['identificador_operador'], 's');	            
	            $documento->setCellValueByColumnAndRow(6, $i, $fila['nombre_fabricante']);
	            $documento->setCellValueByColumnAndRow(7, $i, $fila['nombre_pais_fabricante']);
	            $documento->setCellValueByColumnAndRow(8, $i, $fila['direccion_fabricante']);
	            $documento->setCellValueByColumnAndRow(9, $i, $fila['fecha_aprobacion_solicitud']);
	            $documento->setCellValueByColumnAndRow(10, $i, $fila['codigo_aprobacion_solicitud']);	            
	            $documento->setCellValueByColumnAndRow(11, $i, $fila['estado_solicitud']);
	            $documento->setCellValueByColumnAndRow(12, $i, $fila['provincia_inspector']);
	            $documento->getCellByColumnAndRow(13,  $i)->setValueExplicit($fila['identificador_inspector'], 's');
	            $documento->setCellValueByColumnAndRow(14, $i, $fila['nombre_inspector']);
	            $documento->setCellValueByColumnAndRow(15, $i, $fila['fecha_envio_documental'] == "" ? "" : date('Y-m-d', strtotime($fila['fecha_envio_documental'])));
	            $documento->setCellValueByColumnAndRow(16, $i, $fila['fecha_atencion_documental'] == "" ? "" : date('Y-m-d', strtotime($fila['fecha_atencion_documental'])));
	            $documento->setCellValueByColumnAndRow(17, $i, $fila['estado_solicitud']);
	            $documento->setCellValueByColumnAndRow(18, $i, $fila['dias_atencion']);
	            $documento->setCellValueByColumnAndRow(19, $i, $fila['observacion_inspector']);
	            
	            $i ++;
	        }
	        
	        
	    }*/

		$writer = new Xlsx($hoja);
		//NOTA: Crear constantes
		$writer->save(Constantes::RUTA_SERVIDOR_OPT . '/' . Constantes::RUTA_APLICACION . '/'. VACA_PER_DOC_ADJ . 'excel/2022-fechas.xlsx');

		return true;

	}

	

}
