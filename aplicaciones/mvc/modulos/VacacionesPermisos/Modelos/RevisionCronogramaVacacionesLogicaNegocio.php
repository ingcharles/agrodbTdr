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
class RevisionCronogramaVacacionesLogicaNegocio implements IModelo 
{

	 private $modeloRevisionCronogramaVacaciones = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloRevisionCronogramaVacaciones = new RevisionCronogramaVacacionesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
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

		$datosCronogramaVacacion = ['id_cronograma_vacacion' => $idCronogramaVacacion
									, 'estado_cronograma_vacacion' => $estadoCronogramaVacacion ];

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
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
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
	$consulta = "SELECT * FROM ".$this->modeloRevisionCronogramaVacaciones->getEsquema().". revision_cronograma_vacaciones";
		 return $this->modeloRevisionCronogramaVacaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener las planificaciones en estado De
	 *
	 * @return array
	 */

	public function consultarPlanificacionPorEstadoAnio($estado, $anio)
	{
		$sqlScript = "SELECT fe.identificador,
						CONCAT(fe.apellido,' ',fe.nombre) as nombres_completos,
						cv.estado_cronograma_vacacion,
						ar.nombre AS nombre_unidad_administrativa,
						arr.nombre AS nombre_gestion_administrativa, 
						dc.nombre_puesto AS puesto_institucional,
						CONCAT(feb.apellido,' ',feb.nombre) as nombres_completos_backup,
						cv.total_dias_planificados
					FROM g_vacaciones.cronograma_vacaciones cv
						INNER JOIN g_uath.ficha_empleado fe ON fe.identificador = cv.identificador_funcionario
						INNER JOIN g_uath.ficha_empleado feb ON feb.identificador = cv.identificador_backup
						INNER JOIN g_uath.datos_contrato dc ON dc.identificador = cv.identificador_funcionario AND dc.estado=1
						INNER JOIN g_estructura.area arr ON arr.id_area = dc.id_gestion AND dc.estado = 1
						INNER JOIN g_estructura.area ar ON ar.id_area = arr.id_area_padre AND ar.estado = 1
					WHERE cv.estado_cronograma_vacacion = " . $estado . " AND cv.anio_cronograma_vacacion = " . $anio . "
						ORDER BY 
						ar.nombre,
						arr.nombre,
						CONCAT(fe.apellido,' ',fe.nombre);";
		$res = $this->modeloRevisionCronogramaVacaciones->ejecutarSqlNativo($sqlScript);
		return $res;
	}

	public function guardarEnviarDirectorEjecutivo(Array $datos)
	{

		$proceso = true;

		//TODO_: Generar el archivo de excel y el archivo de pdf
		//generamos la cosulta personalizada y devolvemos en una variable

		$qDatoPlanificacion = $this->consultarPlanificacionPorEstadoAnio('EnviadoDe',2023);
		//$generarExcel = $this->exportarArchivoExcelEstadoSolicitudes(array('hola' => 'dd'));
		$generarExcel = $this->exportarArchivoExcelEstadoSolicitudes($qDatoPlanificacion);
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
		// 	try {
            
		// 	 $procesoIngreso = $this->modeloCertificadoFitosanitario->getAdapter()
        //     ->getDriver()
        //     ->getConnection();
        //     $procesoIngreso->beginTransaction();
		// 	$statement = $this->modeloCertificadoFitosanitario->getAdapter()
        //     ->getDriver()
        //     ->createStatement();
            
        //     $sqlActualizar = $this->modeloCertificadoFitosanitario->actualizarSql('certificado_fitosanitario', $this->modeloCertificadoFitosanitario->getEsquema());
        //     $sqlActualizar->set($arrayParametrosCertificado);
        //     $sqlActualizar->where(array('id_certificado_fitosanitario' => $idCertificadoFitosanitario));
        //     $sqlActualizar->prepareStatement($this->modeloCertificadoFitosanitario->getAdapter(), $statement);
        //     $statement->execute();
            
        //     $procesoIngreso->commit();            
          
        //     return $idCertificadoFitosanitario;
        // } catch (GuardarExcepcion $ex) {
        //     $procesoIngreso->rollback();
        //     throw new \Exception($ex->getMessage());
        // }

		}else{

			$proceso = false;

		}

		return $proceso;

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
	    
	    $documento->getStyle('A1:G1')->applyFromArray($estiloArrayTitulo);
	    $documento->getStyle('A2:G2')->applyFromArray($estiloArrayCabecera);
	    
	    $documento->setCellValueByColumnAndRow(1, 1, 'Reporte general de planificación de cronograma de vacaciones año');
	    $documento->mergeCells('A1:G1');
	    $documento->getColumnDimension('A')->setAutoSize(true);
	    $documento->getColumnDimension('B')->setAutoSize(true);
	    $documento->getColumnDimension('C')->setAutoSize(true);
	    $documento->getColumnDimension('D')->setAutoSize(true);
	    $documento->getColumnDimension('E')->setAutoSize(true);
	    $documento->getColumnDimension('F')->setAutoSize(true);
	    $documento->getColumnDimension('G')->setAutoSize(true);
	    $documento->getColumnDimension('H')->setAutoSize(true);
		    
	    $documento->setCellValue('A2','Cédula');
	    $documento->setCellValue('B2','Apellidos y Nombres');
	    $documento->setCellValue('C2','Fecha de Ingreso');
	    $documento->setCellValue('D2','Unidad Administrativ');
	    $documento->setCellValue('E2','Gestión Administrativ');	    
	    $documento->setCellValue('F2','Puesto Institucional');
	    $documento->setCellValue('G2','Apellidos y Nombres del servidor remplazo');
		$documento->setCellValue('H2','Total días planificados');
	 
	    
	    if ($datos != ''){
	        foreach ($datos as $fila){
	            //$documento->getStyle('A'.$i, 'M'.$i)->applyFromArray($estiloArrayDetalle);
	            $documento->setCellValueByColumnAndRow(1, $i, $fila['identificador']);
	            $documento->setCellValueByColumnAndRow(2, $i, $fila['nombres_completos']);
	            $documento->setCellValueByColumnAndRow(3, $i, $fila['nombre_unidad_administrativa']);
	            $documento->setCellValueByColumnAndRow(4, $i, $fila['nombre_gestion_administrativa']);
	            //$documento->getCellByColumnAndRow(5, $i)->setValueExplicit($fila['identificador_operador'], 's');	            
	            $documento->setCellValueByColumnAndRow(5, $i, $fila['puesto_institucional']);
	            $documento->setCellValueByColumnAndRow(6, $i, $fila['nombres_completos_backup']);
	            $documento->setCellValueByColumnAndRow(7, $i, $fila['total_dias_planificados']);

				
	            // $documento->setCellValueByColumnAndRow(10, $i, $fila['codigo_aprobacion_solicitud']);	            
	            // $documento->setCellValueByColumnAndRow(11, $i, $fila['estado_solicitud']);
	            // $documento->setCellValueByColumnAndRow(12, $i, $fila['provincia_inspector']);
	            // $documento->getCellByColumnAndRow(13,  $i)->setValueExplicit($fila['identificador_inspector'], 's');
	            // $documento->setCellValueByColumnAndRow(14, $i, $fila['nombre_inspector']);
	            // $documento->setCellValueByColumnAndRow(15, $i, $fila['fecha_envio_documental'] == "" ? "" : date('Y-m-d', strtotime($fila['fecha_envio_documental'])));
	            // $documento->setCellValueByColumnAndRow(16, $i, $fila['fecha_atencion_documental'] == "" ? "" : date('Y-m-d', strtotime($fila['fecha_atencion_documental'])));
	            // $documento->setCellValueByColumnAndRow(17, $i, $fila['estado_solicitud']);
	            // $documento->setCellValueByColumnAndRow(18, $i, $fila['dias_atencion']);
	            // $documento->setCellValueByColumnAndRow(19, $i, $fila['observacion_inspector']);
	            
	            $i ++;
	        }
	        
	        
	    }

		$writer = new Xlsx($hoja);
		//NOTA: Crear constantes
		$writer->save(Constantes::RUTA_SERVIDOR_OPT . '/' . Constantes::RUTA_APLICACION . '/'. VACA_PER_DOC_ADJ . 'excel/2022-fechas.xlsx');

		return true;

	}

}
