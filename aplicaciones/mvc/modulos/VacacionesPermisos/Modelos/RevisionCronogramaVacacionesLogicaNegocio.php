<?php

/**
 * Lógica del negocio de RevisionCronogramaVacacionesModelo
 *
 * Este archivo se complementa con el archivo RevisionCronogramaVacacionesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-10-22
 * @uses    RevisionCronogramaVacacionesLogicaNegocio
 * @package VacacionesPermisos
 * @subpackage Modelos
 */

namespace Agrodb\VacacionesPermisos\Modelos;

use Agrodb\VacacionesPermisos\Modelos\IModelo;
use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\Core\Constantes;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Zend\Validator\File\Count;
use Agrodb\Core\JasperReport;

class RevisionCronogramaVacacionesLogicaNegocio implements IModelo
{

	private $modeloRevisionCronogramaVacaciones = null;
	private $modeloConfiguracionCronogramaVacaciones = null;
	private $lNegocioPeriodoCronogramaVacaciones = null;

	/**
	 * Constructor
	 * 
	 * @retorna void
	 */
	public function __construct()
	{
		$this->modeloRevisionCronogramaVacaciones = new RevisionCronogramaVacacionesModelo();
		$this->modeloConfiguracionCronogramaVacaciones = new ConfiguracionCronogramaVacacionesModelo();
		$this->lNegocioPeriodoCronogramaVacaciones = new PeriodoCronogramaVacacionesLogicaNegocio();

	}

	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardar(array $datos)
	{

		try {

			$tablaModelo = new RevisionCronogramaVacacionesModelo($datos);

			$procesoIngreso = $this->modeloRevisionCronogramaVacaciones->getAdapter()
				->getDriver()
				->getConnection();
			$procesoIngreso->beginTransaction();

			$datosBd = $tablaModelo->getPrepararDatos();
			/*echo '<pre>';
		print_r($datos);
		echo '<pre>';*/

			if ($tablaModelo->getIdRevisionCronogramaVacacion() != null && $tablaModelo->getIdRevisionCronogramaVacacion() > 0) {
				$idRevisionCronogramaVacacion = $this->modeloRevisionCronogramaVacaciones->actualizar($datosBd, $tablaModelo->getIdRevisionCronogramaVacacion());
			} else {
				unset($datosBd["id_revision_cronograma_vacacion"]);
				$idRevisionCronogramaVacacion = $this->modeloRevisionCronogramaVacaciones->guardar($datosBd);
			}

			$statement = $this->modeloRevisionCronogramaVacaciones->getAdapter()
				->getDriver()
				->createStatement();

			$idCronogramaVacacion = $datos['id_cronograma_vacacion'];
			$estadoCronogramaVacacion = $datos['estado_cronograma_vacacion'];

			$datosCronogramaVacacion = [
				'id_cronograma_vacacion' => $idCronogramaVacacion, 'estado_cronograma_vacacion' => $estadoCronogramaVacacion
			];

			$sqlActualizar = $this->modeloRevisionCronogramaVacaciones->actualizarSql('cronograma_vacaciones', $this->modeloRevisionCronogramaVacaciones->getEsquema());
			$sqlActualizar->set($datosCronogramaVacacion);
			$sqlActualizar->where(array('id_cronograma_vacacion' => $idCronogramaVacacion));
			$sqlActualizar->prepareStatement($this->modeloRevisionCronogramaVacaciones->getAdapter(), $statement);
			$statement->execute();

			$procesoIngreso->commit();

			return $idRevisionCronogramaVacacion;
		} catch (GuardarExcepcion $ex) {
			$procesoIngreso->rollback();
			throw new \Exception($ex->getMessage());
		}
	}

	/**
	 * Borra el registro actual
	 * @param string Where|array $where
	 * @return int
	 */
	public function borrar($id)
	{
		$this->modeloRevisionCronogramaVacaciones->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param  int $id
	 * @return RevisionCronogramaVacacionesModelo
	 */
	public function buscar($id)
	{
		return $this->modeloRevisionCronogramaVacaciones->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo()
	{
		return $this->modeloRevisionCronogramaVacaciones->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null)
	{
		return $this->modeloRevisionCronogramaVacaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarRevisionCronogramaVacaciones()
	{
		$consulta = "SELECT * FROM " . $this->modeloRevisionCronogramaVacaciones->getEsquema() . ". revision_cronograma_vacaciones";
		return $this->modeloRevisionCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener las planificaciones en estado De
	 *
	 * @return array
	 */

	public function consultarPlanificacionPorEstadoAnio($estado, $idConfiguracionCronogramaVacacion)
	{
		$sqlScript = "SELECT cv.id_cronograma_vacacion,
							fe.identificador,
							UPPER(CONCAT(fe.apellido,' ',fe.nombre)) as nombres_completos,
							mdc.fecha_inicio as fecha_ingreso,
							cv.estado_cronograma_vacacion,
							ar.nombre AS nombre_unidad_administrativa,
							arr.nombre AS nombre_gestion_administrativa, 
							dc.nombre_puesto AS puesto_institucional,
							UPPER(CONCAT(feb.apellido,' ',feb.nombre)) as nombres_completos_backup,
							cv.total_dias_planificados
						FROM g_vacaciones.cronograma_vacaciones cv
							INNER JOIN g_uath.ficha_empleado fe ON fe.identificador = cv.identificador_funcionario
							INNER JOIN g_uath.ficha_empleado feb ON feb.identificador = cv.identificador_backup
							INNER JOIN g_uath.datos_contrato dc ON dc.identificador = cv.identificador_funcionario AND dc.estado=1
							INNER JOIN g_estructura.area arr ON arr.id_area = dc.id_gestion AND dc.estado = 1
							INNER JOIN g_estructura.area ar ON ar.id_area = arr.id_area_padre AND ar.estado = 1
							INNER JOIN (
								(SELECT dci.fecha_inicio, dci.identificador, tdc.id_datos_contrato FROM g_uath.datos_contrato dci INNER JOIN 
								(SELECT MIN(dcc.id_datos_contrato) id_datos_contrato, dcc.identificador FROM g_uath.datos_contrato dcc group by 2 order by 2) tdc ON dci.identificador = tdc.identificador and dci.id_datos_contrato=tdc.id_datos_contrato)
							) mdc ON mdc.identificador = fe.identificador
						WHERE cv.estado_cronograma_vacacion = '" . $estado . "' AND cv.id_configuracion_cronograma_vacacion = " . $idConfiguracionCronogramaVacacion . "
							ORDER BY 
							ar.nombre,
							arr.nombre,
							CONCAT(fe.apellido,' ',fe.nombre);";
		$res = $this->modeloRevisionCronogramaVacaciones->ejecutarSqlNativo($sqlScript);
		return $res;
	}

	
	public function guardarEnviarDirectorEjecutivo(array $datos)
	{


		$proceso = true;

		//TODO_: Generar el archivo de excel y el archivo de pdf
		//generamos la cosulta personalizada y devolvemos en una variable
		// $anio = 2023;
		$estado = 'EnviadoDe';
		$idConfiguracionCronogramaVacacion = $datos["id_configuracion_cronograma_vacacion"];
		$qDatosConfiguracionCronograma = $this->modeloConfiguracionCronogramaVacaciones->buscar($idConfiguracionCronogramaVacacion);
		
		 $anio = $qDatosConfiguracionCronograma->getAnioConfiguracionCronogramaVacacion();
		


		$qDatoPlanificacion = $this->consultarPlanificacionPorEstadoAnio($estado, $idConfiguracionCronogramaVacacion);

		
		if (count($qDatoPlanificacion)) {
			$nombreArchivo = $anio . '_' . date('Y-m-d_H-i-s');
			$rutaArchivoExcel = VACA_PER_DOC_ADJ . 'excel/cronograma/' . $nombreArchivo . '.xlsx';
			$rutaArchivoPdf = VACA_PER_DOC_ADJ . 'pdf/cronograma/' . $nombreArchivo . '.pdf';
			try {
				$generarPdf = true;			
				$jasper = new JasperReport();
				$datosReporte = array();

				$ruta = VACA_PER_URL_TCPDF . 'pdf/cronograma/';

				if (!file_exists($ruta)) {
					mkdir($ruta, 0777, true);
				}

				$ruta = VACA_PER_URL_TCPDF . 'excel/cronograma/';

				if (!file_exists($ruta)) {
					mkdir($ruta, 0777, true);
				}
				
				$datosReporte = array(
					'rutaReporte' => 'VacacionesPermisos/vistas/reportes/cronogramaVacacionesDe.jasper',
					'rutaSalidaReporte' => 'VacacionesPermisos/archivos/pdf/cronograma/' . $nombreArchivo,
					'tipoSalidaReporte' => array('pdf'),
					'parametrosReporte' => array(
						'idConfiguracionCronogramaVacacion' => (int)$idConfiguracionCronogramaVacacion,
						'anio' => (int)$anio,
						'fondoReporte' => RUTA_IMG_GENE . 'fondoCertificadoBlanco.png'
					),
					'conexionBase' => 'SI'
				);
				//print_r($datosReporte);
				$jasper->generarArchivo($datosReporte);
			} catch (GuardarExcepcion $ex) {
				$generarPdf = false;
				throw new \Exception($ex->getMessage());
			}

			$generarExcel = $this->exportarArchivoExcelEstadoSolicitudes($qDatoPlanificacion, $rutaArchivoExcel, $anio);

			if ($generarExcel && $generarPdf) {

				try {

					$procesoIngreso = $this->modeloConfiguracionCronogramaVacaciones->getAdapter()
						->getDriver()
						->getConnection();
					$procesoIngreso->beginTransaction();
					$statement = $this->modeloConfiguracionCronogramaVacaciones->getAdapter()
						->getDriver()
						->createStatement();

					$arrayParametrosCertificado = array(
						'id_configuracion_cronograma_vacacion' => $idConfiguracionCronogramaVacacion, 'ruta_consolidado_excel' => $rutaArchivoExcel, 'ruta_consolidado_pdf' => $rutaArchivoPdf, 'estado_configuracion_cronograma_vacacion' => $estado
					);

					$sqlActualizar = $this->modeloConfiguracionCronogramaVacaciones->actualizarSql('configuracion_cronograma_vacaciones', $this->modeloConfiguracionCronogramaVacaciones->getEsquema());
					$sqlActualizar->set($arrayParametrosCertificado);
					$sqlActualizar->where(array('id_configuracion_cronograma_vacacion' => $idConfiguracionCronogramaVacacion));
					$sqlActualizar->prepareStatement($this->modeloConfiguracionCronogramaVacaciones->getAdapter(), $statement);
					$statement->execute();

					$procesoIngreso->commit();
				} catch (GuardarExcepcion $ex) {
					$procesoIngreso->rollback();
					throw new \Exception($ex->getMessage());
				}
			} else {

				$proceso = false;
			}
		} else {
			$proceso = false;
		}
		return $proceso;
	}

	/**
	 * Ejecuta un reporte en Excel de los estado de las soliictudes de habilitacion
	 *
	 * @return array|ResultSet
	 */
	public function exportarArchivoExcelEstadoSolicitudes($datos, $rutaArchivoExcel, $anio)
	{
		$hoja = new Spreadsheet();
		$documento = $hoja->getActiveSheet();
		$documento->setTitle('CRONOGRAMA DE VACACIONES');
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



		$documento->getStyle('A1:G1')->applyFromArray($estiloArrayTitulo);
		$documento->getStyle('A2:T2')->applyFromArray($estiloArrayCabecera);
		$documento->getStyle('A3:T3')->applyFromArray($estiloArrayCabecera);

		$documento->setCellValueByColumnAndRow(1, 1, 'Reporte general de planificación de cronograma de vacaciones año ' . $anio);
		$documento->mergeCells('A1:G1');
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
		$documento->getColumnDimension('T')->setAutoSize(true);

		$documento->setCellValueByColumnAndRow(1, 2, 'Cédula de Ciudadania');
		$documento->mergeCells('A2:A3');
		$documento->setCellValueByColumnAndRow(2, 2, 'Apellidos y Nombres');
		$documento->mergeCells('B2:B3');
		$documento->setCellValueByColumnAndRow(3, 2, 'Fecha de Ingreso');
		$documento->mergeCells('C2:C3');
		$documento->setCellValueByColumnAndRow(4, 2, 'Unidad Administrativa');
		$documento->mergeCells('D2:D3');
		$documento->setCellValueByColumnAndRow(5, 2, 'Gestión Administrativa');
		$documento->mergeCells('E2:E3');
		$documento->setCellValueByColumnAndRow(6, 2, 'Puesto Institucional');
		$documento->mergeCells('F2:F3');
		$documento->setCellValueByColumnAndRow(7, 2, 'Apellidos y Nombres del servidor remplazo');
		$documento->mergeCells('G2:G3');
		$documento->setCellValueByColumnAndRow(8, 2, 'Primer Periodo');
		$documento->mergeCells('H2:I2');
		$documento->setCellValue('H3', 'Fecha Inicio');
		$documento->setCellValue('I3', 'Fecha Fin');
		$documento->setCellValueByColumnAndRow(10, 2, 'Total días a tomar');
		$documento->mergeCells('J2:J3');
		$documento->setCellValueByColumnAndRow(11, 2, 'Segundo Periodo');
		$documento->mergeCells('K2:L2');
		$documento->setCellValue('K3', 'Fecha Inicio');
		$documento->setCellValue('L3', 'Fecha Fin');
		$documento->setCellValueByColumnAndRow(13, 2, 'Total días a tomar');
		$documento->mergeCells('M2:M3');
		$documento->setCellValueByColumnAndRow(14, 2, 'Tercer Periodo');
		$documento->mergeCells('N2:O2');
		$documento->setCellValue('N3', 'Fecha Inicio');
		$documento->setCellValue('O3', 'Fecha Fin');
		$documento->setCellValueByColumnAndRow(16, 2, 'Total días a tomar');
		$documento->mergeCells('P2:P3');
		$documento->setCellValueByColumnAndRow(17, 2, 'Cuarto Periodo');
		$documento->mergeCells('Q2:R2');
		$documento->setCellValue('Q3', 'Fecha Inicio');
		$documento->setCellValue('R3', 'Fecha Fin');
		$documento->setCellValueByColumnAndRow(19, 2, 'Total días a tomar');
		$documento->mergeCells('S2:S3');
		$documento->setCellValueByColumnAndRow(20, 2, 'Total de días planificados');
		$documento->mergeCells('T2:T3');
		$i++;
		if ($datos != '') {
			foreach ($datos as $fila) {

				$documento->setCellValueByColumnAndRow(1, $i, $fila['identificador']);
				$documento->setCellValueByColumnAndRow(2, $i, $fila['nombres_completos']);
				$documento->setCellValueByColumnAndRow(3, $i, date('Y-m-d', strtotime($fila['fecha_ingreso'])));
				$documento->setCellValueByColumnAndRow(4, $i, $fila['nombre_unidad_administrativa']);
				$documento->setCellValueByColumnAndRow(5, $i, $fila['nombre_gestion_administrativa']);
				$documento->setCellValueByColumnAndRow(6, $i, $fila['puesto_institucional']);
				$documento->setCellValueByColumnAndRow(7, $i, $fila['nombres_completos_backup']);
				$periodos = $this->lNegocioPeriodoCronogramaVacaciones->buscarLista(array('id_cronograma_vacacion' => $fila['id_cronograma_vacacion'], 'estado_registro' => 'Activo'), 'numero_periodo ASC');
				$columnaPeriodo = 0;
				foreach ($periodos as $filaPeriodo) {

					switch ($filaPeriodo['numero_periodo']) {
						case 1:
							$columnaPeriodo = 8;
							break;
						case 2:
							$columnaPeriodo = 11;
							break;
						case 3:
							$columnaPeriodo = 14;
							break;
						case 4:
							$columnaPeriodo = 17;
							break;
					}

					$documento->setCellValueByColumnAndRow($columnaPeriodo, $i, date("Y-m-d", strtotime($filaPeriodo['fecha_inicio'])));
					$columnaPeriodo++;
					$documento->setCellValueByColumnAndRow($columnaPeriodo, $i, date("Y-m-d", strtotime($filaPeriodo['fecha_fin'])));

					$columnaPeriodo++;
					$documento->setCellValueByColumnAndRow($columnaPeriodo, $i, $filaPeriodo['total_dias']);
				}
				$documento->setCellValueByColumnAndRow(20, $i, $fila['total_dias_planificados']);
				$i++;
			}
		}

		$writer = new Xlsx($hoja);
		$writer->save(Constantes::RUTA_SERVIDOR_OPT . '/' . Constantes::RUTA_APLICACION . '/' . $rutaArchivoExcel);

		return true;
	}

		/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los datos de la ultima revision de cronograma
	 *
	 * @return array
	 */

	 public function obtenerDatosUltimaRevisionCronograma($arrayParametros)
	 {

		$idCronogramaVacacion = $arrayParametros['id_cronograma_vacacion'];

		 $sqlScript = "SELECT 
							rcv.id_revision_cronograma_vacacion
							, rcv.id_cronograma_vacacion
							, rcv.identificador_revisor
							, rcv.id_area_revisor
							, rcv.estado_solicitud
							, rcv.observacion
							, rcv.fecha_creacion
							, fe.nombre || ' ' || fe.apellido as nombre_revisor
						FROM 
						g_vacaciones.revision_cronograma_vacaciones rcv
						INNER JOIN (SELECT 
										MAX(rcv.id_revision_cronograma_vacacion) as id_revision_cronograma_vacacion, rcv.id_cronograma_vacacion
									FROM 
										g_vacaciones.revision_cronograma_vacaciones rcv
									GROUP BY rcv.id_cronograma_vacacion) tmcv ON tmcv.id_revision_cronograma_vacacion = rcv.id_revision_cronograma_vacacion
						INNER JOIN g_uath.ficha_empleado fe ON fe.identificador = rcv.identificador_revisor
						WHERE
							tmcv.id_cronograma_vacacion = " . $idCronogramaVacacion . ";";
		 $res = $this->modeloRevisionCronogramaVacaciones->ejecutarSqlNativo($sqlScript);
		 return $res;
	 }
}
