<?php
/**
 * Lógica del negocio de ProveedorExteriorModelo
 *
 * Este archivo se complementa con el archivo ProveedorExteriorControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-13
 * @uses ProveedorExteriorLogicaNegocio
 * @package ProveedoresExterior
 * @subpackage Modelos
 */
namespace Agrodb\ProveedoresExterior\Modelos;

use Agrodb\ProveedoresExterior\Modelos\IModelo;
use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\ProveedoresExterior\Modelos\ProductosProveedorLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\DocumentosAdjuntosLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\SubsanacionLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\DetalleSubsanacionLogicaNegocio;
use Agrodb\ProveedoresExterior\Modelos\PeriodoSubsanacionLogicaNegocio;
use Agrodb\RevisionFormularios\Modelos\AsignacionInspectorLogicaNegocio;
use Agrodb\Catalogos\Modelos\LocalizacionLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresLogicaNegocio;
use Agrodb\Correos\Modelos\CorreosLogicaNegocio;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Agrodb\Core\JasperReport;
use Agrodb\RegistroOperador\Modelos\OperacionesLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\OperadoresModelo;

class ProveedorExteriorLogicaNegocio implements IModelo{

	private $modeloProveedorExterior = null;

	private $lNegocioProductosProveedor = null;

	private $lNegocioDocumentosAdjuntos = null;

	private $lNegocioLocalizacion = null;

	private $lNegocioSubsanacion = null;

	private $lNegocioDetalleSubsanacion = null;

	private $lNegocioPeriodoSubsanacion = null;
	
	private $lNegocioAsignacionInspector = null;
	
	private $lNegocioOperadores = null;
	
	private $lNegocioCorreos = null;

	private $rutaFecha = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloProveedorExterior = new ProveedorExteriorModelo();

		$this->lNegocioProductosProveedor = new ProductosProveedorLogicaNegocio();
		$this->lNegocioDocumentosAdjuntos = new DocumentosAdjuntosLogicaNegocio();
		$this->lNegocioLocalizacion = new LocalizacionLogicaNegocio();
		$this->lNegocioSubsanacion = new SubsanacionLogicaNegocio();
		$this->lNegocioDetalleSubsanacion = new DetalleSubsanacionLogicaNegocio();
		$this->lNegocioPeriodoSubsanacion = new PeriodoSubsanacionLogicaNegocio();
		$this->lNegocioAsignacionInspector = new AsignacionInspectorLogicaNegocio();
		$this->lNegocioOperadores = new OperadoresLogicaNegocio();
		$this->lNegocioCorreos = new CorreosLogicaNegocio();

		$this->rutaFecha = date('Y') . '/' . date('m') . '/' . date('d');
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){

		$tablaModelo = new ProveedorExteriorModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();

		if ($tablaModelo->getIdProveedorExterior() != null && $tablaModelo->getIdProveedorExterior() > 0){
		    return $this->modeloProveedorExterior->actualizar($datosBd, $tablaModelo->getIdProveedorExterior());
		}else{
			unset($datosBd["id_proveedor_exterior"]);
			return $this->modeloProveedorExterior->guardar($datosBd);
		}

	}
	
	/**
	 * Guarda el registro una nueva solicitud
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardarSolicitud(Array $datos){
	    
	    try{
	    
	        $procesoIngreso = $this->modeloProveedorExterior->getAdapter()
	        ->getDriver()
	        ->getConnection();
	        $procesoIngreso->beginTransaction();	        
	        
	        $idProveedorExterior = $this->guardar($datos);
	        
    	    $procesoIngreso->commit();
    	    return $idProveedorExterior;
    	}catch (GuardarExcepcion $ex){
    	    $procesoIngreso->rollback();
    	    throw new \Exception($ex->getMessage());
    	}
	    
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloProveedorExterior->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ProveedorExteriorModelo
	 */
	public function buscar($id){
		return $this->modeloProveedorExterior->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloProveedorExterior->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloProveedorExterior->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarProveedorExterior(){
		$consulta = "SELECT * FROM " . $this->modeloProveedorExterior->getEsquema() . ". proveedor_exterior";
		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarEstadoSolicitudesProveedorExterior($identificador){
		$consulta = "SELECT
                        DISTINCT estado_solicitud
                    FROM
                    	g_proveedores_exterior.proveedor_exterior
                    WHERE
	                   identificador_operador = ('$identificador')
                    ORDER BY estado_solicitud ASC; ";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para generar
	 * el secuencial de un codigo de creacion de solicitud.
	 *
	 * @return array|ResultSet
	 */
	public function generarSecuencialCodigoCreacionSolicitud($arrayParametros){
		
		$secuencial = $arrayParametros['secuencial'];

		$consulta = "SELECT
                    	MAX(SPLIT_PART(codigo_creacion_solicitud, '" . $secuencial . "' , 2)::int) + 1 as numero 
                    FROM
                    	g_proveedores_exterior.proveedor_exterior
                    WHERE codigo_creacion_solicitud LIKE '%" . $secuencial . "%';";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Método generar el codigo de Creacion de la solicitud
	 */
	public function generarCodigoCreacionSolicitud($idProvinciaOperador){
		$fabricanteExterior = "FE";
		$provincia = "";
		$anio = date('Y');
		
		$codigoProvincia = $this->lNegocioLocalizacion->buscarProvinciaPorIdProvincia($idProvinciaOperador);
		
		if (isset($codigoProvincia->current()->id_localizacion)){
			$provincia = str_pad(ltrim($codigoProvincia->current()->codigo_vue, '0'), 2, '0', STR_PAD_LEFT);

			$arrayParametros = array(
				'secuencial' => $fabricanteExterior . '-' . $provincia . '-' . $anio . '-');
			
			$secuencial = $this->generarSecuencialCodigoCreacionSolicitud($arrayParametros);
			
			if (isset($secuencial->current()->numero)){
				return $fabricanteExterior . '-' . $provincia . '-' . $anio . '-' . str_pad($secuencial->current()->numero, 4, '0', STR_PAD_LEFT);
			}else{
				return $fabricanteExterior . '-' . $provincia . '-' . $anio . '-0001';
			}
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para actualizar
	 * los datos del solicitante.
	 *
	 * @return array|ResultSet
	 */
	public function actualizarDatosProveedorExterior($arrayParametros){
		$idProveedorExterior = $arrayParametros['id_proveedor_exterior'];
		$nombreFabricante = $arrayParametros['nombre_fabricante'];
		$idPaisFabricante = $arrayParametros['id_pais_fabricante'];
		$nombrePaisFabricante = $arrayParametros['nombre_pais_fabricante'];
		$direccionFabricante = $arrayParametros['direccion_fabricante'];
		$servicioOficial = $arrayParametros['servicio_oficial'];

		$consulta = "UPDATE 
                    	g_proveedores_exterior.proveedor_exterior
                    SET 
                    	nombre_fabricante = '" . $nombreFabricante . "'
                    	, id_pais_fabricante = '" . $idPaisFabricante . "'
                    	, nombre_pais_fabricante = '" . $nombrePaisFabricante . "'
                    	, direccion_fabricante = '" . $direccionFabricante . "'
                    	, servicio_oficial = '" . $servicioOficial . "'
                    WHERE 
                    	id_proveedor_exterior = '" . $idProveedorExterior . "';";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardarFinalizarSolicitud(Array $datos){
		try{
            		    
			$idProveedorExterior = $datos['id_proveedor_exterior'];
			$arrayDocumentosAnexosNombre = json_decode($datos['array_documentos_anexos_nombre'], true);
			$arrayDocumentosAnexos = json_decode($datos['array_documentos_anexos'], true);
			
			$procesoIngreso = $this->modeloProveedorExterior->getAdapter()
			->getDriver()
			->getConnection();
			$procesoIngreso->beginTransaction();
			
			$solicitudProveedorExterior = $this->buscar($_POST["id_proveedor_exterior"]);
			$idProvinciaOperador = $solicitudProveedorExterior->getIdProvinciaOperador();
			$estadoSolicitud = $solicitudProveedorExterior->getEstadoSolicitud();
			
			$estadoSiguienteSolicitud = "RevisionDocumental";
			
			$arrayParametros = array(
			    'id_proveedor_exterior' => (integer) $idProveedorExterior,
			    'estado_solicitud' => $estadoSiguienteSolicitud,
			    'fecha_envio_documental' => 'now()',
			    'fecha_atencion_documental' => null);
			
			switch ($estadoSolicitud) {
			    
			    case "SinEnviar":
			        
			        $codigoCreacionSolicitud = $this->generarCodigoCreacionSolicitud($idProvinciaOperador);
			        $arrayParametros += [
			            'codigo_creacion_solicitud' => $codigoCreacionSolicitud];
			        
			        break;
			        
			    case "Subsanacion":
			        
			        $subsanacionProveedor = $this->lNegocioSubsanacion->buscarSubsanacion($idProveedorExterior);
			        
			        if (isset($subsanacionProveedor->current()->id_proveedor_exterior)){
			            
			            $idSubsanacionProveedor = $subsanacionProveedor->current()->id_subsanacion;
			            
			            $detalleSubsanacion = $this->lNegocioDetalleSubsanacion->obtenerDetalleSubsanacionPorIdSubsanacion($idSubsanacionProveedor);
			            
			            $idDetalleSubsanacion = $detalleSubsanacion->current()->id_detalle_subsanacion;
			            $diasTranscurridos = $detalleSubsanacion->current()->dias_transcurridos;
			            
			            $arraySubsanacion = array(
			                'id_subsanacion' => $idSubsanacionProveedor,
			                'descontar_dias' => 'NO',
			                'id_detalle_subsanacion' => $idDetalleSubsanacion,
			                'fecha_subsanacion_operador' => 'now()',
			                'dias_transcurridos' => $diasTranscurridos);
			            
			            $this->lNegocioSubsanacion->actualizarSubsanacion($arraySubsanacion);
			        }
			        
			        break;
			}
			
			$this->guardar($arrayParametros);			
			
			$statement = $this->modeloProveedorExterior->getAdapter()
				->getDriver()
				->createStatement();

			if (! empty($arrayDocumentosAnexos)){

				for ($i = 0; $i < count($arrayDocumentosAnexosNombre); $i ++){

					$arrayParametros = array(
						'id_proveedor_exterior' => (integer) $idProveedorExterior,
						'tipo_adjunto' => $arrayDocumentosAnexosNombre[$i],
						'ruta_adjunto' => $arrayDocumentosAnexos[$i],
						'estado_adjunto' => 'Activo');

					$query = "id_proveedor_exterior = '" . $idProveedorExterior . "' and tipo_adjunto = '" . $arrayDocumentosAnexosNombre[$i] . "'";
					$validarDocumentosAdjuntos = $this->lNegocioDocumentosAdjuntos->buscarLista($query);

					// Verificar si la solicitud ya posee documentos adjuntos
					if (isset($validarDocumentosAdjuntos->current()->id_documento_adjunto)){

						// Verificar si el documento adjunto que ingresa es diferente de cero
						if ($arrayDocumentosAnexos[$i] != "0"){

							$idDocumentoAdjunto = $validarDocumentosAdjuntos->current()->id_documento_adjunto;

							$sqlActualizar = $this->modeloProveedorExterior->actualizarSql('documentos_adjuntos', $this->modeloProveedorExterior->getEsquema());
							$sqlActualizar->set($arrayParametros);
							$sqlActualizar->where(array(
								'id_documento_adjunto' => $idDocumentoAdjunto));
							$sqlActualizar->prepareStatement($this->modeloProveedorExterior->getAdapter(), $statement);
							$statement->execute();
						}
					}else{

						$sqlInsertar = $this->modeloProveedorExterior->guardarSql('documentos_adjuntos', $this->modeloProveedorExterior->getEsquema());
						$sqlInsertar->columns($this->lNegocioDocumentosAdjuntos->columnas());
						$sqlInsertar->values($arrayParametros, $sqlInsertar::VALUES_MERGE);
						$sqlInsertar->prepareStatement($this->modeloProveedorExterior->getAdapter(), $statement);
						$statement->execute();
					}
				}
			}

			$procesoIngreso->commit();
			return $idProveedorExterior;
		}catch (GuardarExcepcion $ex){
			$procesoIngreso->rollback();
			throw new \Exception($ex->getMessage());
		}
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
	    
	    $documento->setCellValueByColumnAndRow(1, 1, 'Reporte de estado de solicitudes de habilitación');
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
	    
	    if ($datos != ''){
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
	        
	        
	    }
	    
	    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	    header('Content-Disposition: attachment;filename="excelEstadoSolicitudesProveedoresExterior.xlsx"');
	    header("Pragma: no-cache");
	    header("Expires: 0");

		$writer = IOFactory::createWriter($hoja, 'Xlsx');
		$writer->save('php://output');
		exit();
	}

	/**
	 * Ejecuta un reporte en Excel de las solicitudes de proveedores del exterior habilitadas
	 *
	 * @return array|ResultSet
	 */
	public function exportarArchivoExcelSolicitudesHabilitadas($datos){
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
		$documento->getStyle('A2:G2')->applyFromArray($estiloArrayCabecera);
		
		$documento->setCellValueByColumnAndRow(1, 1, 'Reporte de proveedores en el exterior habilitados');
		$documento->mergeCells('A1:D1');
		$documento->getColumnDimension('A')->setAutoSize(true);
		$documento->getColumnDimension('B')->setAutoSize(true);
		$documento->getColumnDimension('C')->setAutoSize(true);
		$documento->getColumnDimension('D')->setAutoSize(true);
		$documento->getColumnDimension('E')->setAutoSize(true);
		$documento->getColumnDimension('F')->setAutoSize(true);
		$documento->getColumnDimension('G')->setAutoSize(true);
		
		$documento->setCellValue('A2','N°');
		$documento->setCellValue('B2','Código de habilitación');
		$documento->setCellValue('C2','Fabricante en el exterior');
		$documento->setCellValue('D2','País fabricante');
		$documento->setCellValue('E2','Dirección fabricante');
		$documento->setCellValue('F2','Tipos de productos');
		$documento->setCellValue('G2','Servicios oficiales que regulan los productos de uso veterinario');

		if ($datos != ''){

			$contador = 1;

			foreach ($datos as $fila){
				$documento->setCellValueByColumnAndRow(1, $i, $contador ++);
				$documento->getCellByColumnAndRow(2, $i)->setValueExplicit($fila['codigo_aprobacion_solicitud'], 's');
				$documento->setCellValueByColumnAndRow(3, $i, $fila['nombre_fabricante']);				
				$documento->setCellValueByColumnAndRow(4, $i, $fila['nombre_pais_fabricante']);
				$documento->setCellValueByColumnAndRow(5, $i, $fila['direccion_fabricante']);
				$documento->setCellValueByColumnAndRow(6, $i, $fila['tipos_productos']);
				$documento->setCellValueByColumnAndRow(7, $i, $fila['servicio_oficial']);
				$i ++;
			}
		}

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="excelSolicitudesProveedoresExterior.xlsx"');
		header("Pragma: no-cache");
		header("Expires: 0");

		$writer = IOFactory::createWriter($hoja, 'Xlsx');
		$writer->save('php://output');
		exit();
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los datos de los operadores con solicitudes de proveedor exterior
	 * los datos del solicitante.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerSolicitudesOperadoresProveedorExteriorPorEstado($arrayParametros){
		$estadoSolicitud = $arrayParametros['estadoSolicitud'];

		$consulta = "SELECT 
                    	DISTINCT o.identificador
                    	, CASE WHEN o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social END nombre_operador
                    FROM 
                    	g_proveedores_exterior.proveedor_exterior pe 
                    INNER JOIN g_operadores.operadores o ON pe.identificador_operador = o.identificador
                    WHERE 
                    	pe.estado_solicitud = '" . $estadoSolicitud . "';";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los datos de los operadores con solicitudes de proveedor exterior asignados
	 * los datos del solicitante.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerSolicitudesAsignadasOperadoresProveedorExteriorPorEstado($arrayParametros){
		$estadoSolicitud = $arrayParametros['estadoSolicitud'];
		$inspector = $arrayParametros['inspector'];
		$tipoSolicitud = $arrayParametros['tipoSolicitud'];
		$tipoInspector = $arrayParametros['tipoInspector'];

		$consulta = "SELECT 
                    	DISTINCT o.identificador
                    	, CASE WHEN o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social END nombre_operador
                    	, o.direccion
                    	, o.provincia
                    FROM 
                    	g_proveedores_exterior.proveedor_exterior pe 
                    INNER JOIN g_operadores.operadores o ON pe.identificador_operador = o.identificador
                    INNER JOIN g_revision_solicitudes.asignacion_coordinador ac ON pe.id_proveedor_exterior = ac.id_solicitud
                    WHERE 
                    	pe.estado_solicitud = '" . $estadoSolicitud . "'
                    	and ac.identificador_inspector = '" . $inspector . "'
                    	and ac.tipo_solicitud = '" . $tipoSolicitud . "'
                    	and ac.tipo_inspector = '" . $tipoInspector . "';";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener las solicitudes en revision documental de los operadores
	 *
	 *
	 * @return array|ResultSet
	 */
	public function obtenerSolicitudesProveedorExteriorPorOperadorPorEstado($arrayParametros){
		$estadoSolicitud = $arrayParametros['estadoSolicitud'];
		$condicion = "";

		if(isset($arrayParametros['identificadorOperador']) && ($arrayParametros['identificadorOperador'] != '')) {
		    $identificadorOperador = $arrayParametros['identificadorOperador'];
		    $condicion .= "and pe.identificador_operador = '" . $identificadorOperador . "'";
		}

		$consulta = "SELECT
                        pe.id_proveedor_exterior
                        , pe.nombre_provincia_operador
                        , pe.identificador_operador
                        , pe.codigo_creacion_solicitud
                        , pe.estado_solicitud
                        , CASE WHEN o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social END nombre_operador
                        , o.direccion
                    FROM
                        g_proveedores_exterior.proveedor_exterior pe
                        INNER JOIN g_operadores.operadores o ON pe.identificador_operador = o.identificador
                    WHERE
                        pe.estado_solicitud = '" . $estadoSolicitud . "' "
                            . $condicion . ";";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener las solicitudes en revision documental asignadas de los operadores
	 *
	 *
	 * @return array|ResultSet
	 */
	public function obtenerSolicitudesAsignadasProveedorExteriorPorEstado($arrayParametros){
		$estadoSolicitud = $arrayParametros['estadoSolicitud'];
		$inspector = $arrayParametros['inspector'];
		$tipoSolicitud = $arrayParametros['tipoSolicitud'];
		$tipoInspector = $arrayParametros['tipoInspector'];

		$consulta = "SELECT 
                    	pe.id_proveedor_exterior
                    	, pe.nombre_provincia_operador
                    	, pe.identificador_operador
                    	, pe.codigo_creacion_solicitud
                        , pe.estado_solicitud
                    	, CASE WHEN o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social END nombre_operador
                    	, o.direccion
                    FROM 
                    	g_proveedores_exterior.proveedor_exterior pe 
                    INNER JOIN g_operadores.operadores o ON pe.identificador_operador = o.identificador
                    INNER JOIN g_revision_solicitudes.asignacion_coordinador ac ON pe.id_proveedor_exterior = ac.id_solicitud
                    WHERE 
                    	pe.estado_solicitud = '" . $estadoSolicitud . "'
                    	and ac.identificador_inspector = '" . $inspector . "'
                    	and ac.tipo_solicitud = '" . $tipoSolicitud . "'
                    	and ac.tipo_inspector = '" . $tipoInspector . "';";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para actualizar
	 * los datos del estado de la solicitud.
	 *
	 * @return array|ResultSet
	 */
	public function actualizarEstadoProveedorExterior($arrayParametros){
		$idProveedorExterior = $arrayParametros['id_proveedor_exterior'];
		$estadoSolicitud = $arrayParametros['estado_solicitud'];

		$consulta = "UPDATE
                    	g_proveedores_exterior.proveedor_exterior
                    SET
                    	estado_solicitud = '" . $estadoSolicitud . "'
                    WHERE
                    	id_proveedor_exterior = '" . $idProveedorExterior . "';";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Guarda el registro del resultado de la revisión de la solicitud
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardarRevisionSolicitud(Array $datos){
	    $generarCertificado = false;
		try{
			
			$idProveedorExterior = $datos['id_proveedor_exterior'];
			$estadoSolicitud = $datos['estado_solicitud'];
			$observacionSolicitud = $datos['observacion_solicitud'];
			$identificadorRevisor = $datos['identificador_revisor'];
			$identificadorAsignante = $datos['identificador_asignante'];
			$fechaAtencionDocumental = $datos['fecha_atencion_documental'];
			$rutaAdjunto = $datos['ruta_adjunto'];

			$arrayResultadoRevision = array(
				'id_proveedor_exterior' => $idProveedorExterior,
				'estado_solicitud' => $estadoSolicitud,
				'fecha_atencion_documental' => $fechaAtencionDocumental,
				'observacion_solicitud' => $observacionSolicitud,
				'identificador_revisor' => $identificadorRevisor);

			$procesoIngreso = $this->modeloProveedorExterior->getAdapter()
				->getDriver()
				->getConnection();
			$procesoIngreso->beginTransaction();

			$statement = $this->modeloProveedorExterior->getAdapter()
				->getDriver()
				->createStatement();

			if ($estadoSolicitud == "Aprobado"){

				$generarCertificado = true;
				$fechaAprobacionSolicitud = $datos['fecha_aprobacion_solicitud'];
				$datosProveedorExterior = $this->buscarSolicitudReemplazo($idProveedorExterior);

				if (isset($datosProveedorExterior->current()->id_proveedor_exterior)){

					$idProveedorExteriorInhabilitar = $datosProveedorExterior->current()->id_proveedor_exterior;
					$codigoAprobacionSolicitud = $datosProveedorExterior->current()->codigo_aprobacion_solicitud;

					$arrayParametros = array(
						'id_proveedor_exterior' => $idProveedorExteriorInhabilitar,
						'estado_solicitud' => 'Inhabilitado');

					$this->actualizarDatosProveedorExteriorReemplazo($arrayParametros);

					$arrayParametrosAdjuntos = array(
						'id_proveedor_exterior' => $idProveedorExteriorInhabilitar,
						'estado_adjunto' => 'Inactivo',
						'tipo_adjunto' => 'Certificado Proveedor Exterior');

					$this->lNegocioDocumentosAdjuntos->actualizarEstadoAdjuntoPorIdProveedorPorTipo($arrayParametrosAdjuntos);
				}else{

					$codigoAprobacionSolicitud = $this->generarCodigoAprobacionSolicitud();
				}

				$arrayResultadoRevision += [
					'codigo_aprobacion_solicitud' => $codigoAprobacionSolicitud];
				$arrayResultadoRevision += [
					'fecha_aprobacion_solicitud' => $fechaAprobacionSolicitud];
			}else{

				$subsanacionProveedor = $this->lNegocioSubsanacion->buscarSubsanacion($idProveedorExterior);

				if (isset($subsanacionProveedor->current()->id_proveedor_exterior)){

					$idSubsanacionProveedor = $subsanacionProveedor->current()->id_subsanacion;

					$arraySubsanacion = array(
						'id_subsanacion' => $idSubsanacionProveedor,
						'identificador_revisor' => $identificadorRevisor,
						'observacion_subsanacion' => $observacionSolicitud,
						'fecha_subsanacion' => 'now()',
						'fecha_subsanacion_operador' => null,
						'descontar_dias' => 'SI');

					if (isset($datos['ruta_archivo_subsanacion'])){
						$arraySubsanacion += [
							'ruta_archivo_subsanacion' => $datos['ruta_archivo_subsanacion']];
					}

					$this->lNegocioSubsanacion->actualizarSubsanacion($arraySubsanacion);
				}else{

					$periodoSubsanacion = $this->lNegocioPeriodoSubsanacion->buscarPeriodoSubsanacion();
					$idPeriodoSubsanacion = $periodoSubsanacion->current()->id_periodo_subsanacion;
					$tiempoPeriodoSubsanacion = $periodoSubsanacion->current()->tiempo_periodo_subsanacion;

					$arraySubsanacion = array(
						'id_proveedor_exterior' => $idProveedorExterior,
						'id_periodo_subsanacion' => $idPeriodoSubsanacion,
						'dias_subsanacion' => $tiempoPeriodoSubsanacion,
						'saldo_dias_subsanacion' => $tiempoPeriodoSubsanacion,
						'identificador_revisor' => $identificadorRevisor,
						'observacion_subsanacion' => $observacionSolicitud,
						'fecha_subsanacion' => 'now()',
						'descontar_dias' => 'SI');

					if (isset($datos['ruta_archivo_subsanacion'])){
						$arraySubsanacion += [
							'ruta_archivo_subsanacion' => $datos['ruta_archivo_subsanacion']];
					}

					$this->lNegocioSubsanacion->guardar($arraySubsanacion);
				}
			}

			$sqlActualizar = $this->modeloProveedorExterior->actualizarSql('proveedor_exterior', $this->modeloProveedorExterior->getEsquema());
			$sqlActualizar->set($arrayResultadoRevision);
			$sqlActualizar->where(array(
				'id_proveedor_exterior' => $idProveedorExterior));
			$sqlActualizar->prepareStatement($this->modeloProveedorExterior->getAdapter(), $statement);
			$statement->execute();
			$statement->getParameterContainer();
			
			//Construye el array para el registro de informacion en tablas de revision de solicitudes
			
			$arrayDatosRevisor = array(
			    'identificador_inspector' => $identificadorRevisor,
			    'fecha_asignacion' => 'now()',
			    'identificador_asignante' => $identificadorAsignante,
			    'tipo_solicitud' => 'proveedorExterior',
			    'tipo_inspector' => 'Documental',
			    'id_operador_tipo_operacion' => 0,
			    'id_historial_operacion' => 0,
			    'id_solicitud' => $idProveedorExterior,
			    'estado' => 'Documental',
			    'fecha_inspeccion' => 'now()',
			    'observacion' => $observacionSolicitud,
			    'estado_siguiente' => $estadoSolicitud,
			    'orden' => 1);
			
			if (isset($rutaAdjunto)){
			    $arrayDatosRevisor += [
			        'ruta_archivo_documental' => $rutaAdjunto];
			}

			$this->lNegocioAsignacionInspector->guardar($arrayDatosRevisor);
			
			//Construye en array para en envío de correo			
			$arrayDatosRevisor = array(
			    'identificador_inspector' => $identificadorRevisor,
			    'fecha_asignacion' => 'now()',
			    'identificador_asignante' => $identificadorAsignante
			);
			
			$this->enviarCorreo($idProveedorExterior);

			$procesoIngreso->commit();
			
			if ($generarCertificado){
				$idCertificado = md5(rand());
				$this->generarCertificadoProveedorExterior($idProveedorExterior, $idCertificado);
			}
			
			return $idProveedorExterior;
		}catch (GuardarExcepcion $ex){
			$procesoIngreso->rollback();
			throw new \Exception($ex->getMessage());
		}
	}

	/**
	 * Método generar el codigo de Aprobación de solicitud
	 */
	public function generarCodigoAprobacionSolicitud(){
		$fabricanteExterior = "RIP-EH";
		$anio = date('Y');
		$codigoAprovacionSolicitud = "";

		$arrayParametros = array(
			'secuencial' => $fabricanteExterior . '-' . $anio . '-');

		$secuencial = $this->generarSecuencialCodigoAprobacionSolicitud($arrayParametros);

		if (isset($secuencial->current()->numero)){
			$codigoAprovacionSolicitud = $fabricanteExterior . '-' . $anio . '-' . str_pad($secuencial->current()->numero, 4, '0', STR_PAD_LEFT);
		}else{
			$codigoAprovacionSolicitud = $fabricanteExterior . '-' . $anio . '-0001';
		}

		return $codigoAprovacionSolicitud;
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para generar
	 * el secuencial de un codigo de aprobacion de solicitud.
	 *
	 * @return array|ResultSet
	 */
	public function generarSecuencialCodigoAprobacionSolicitud($arrayParametros){
		$secuencial = $arrayParametros['secuencial'];

		$consulta = "SELECT
                    	max(split_part(codigo_aprobacion_solicitud, '" . $secuencial . "' , 2)::int) + 1 as numero
                    FROM
                    	g_proveedores_exterior.proveedor_exterior
                    WHERE codigo_aprobacion_solicitud LIKE '%" . $secuencial . "%';";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para actualizar
	 * los datos del revisor y fecha de revision
	 *
	 * @return array|ResultSet
	 */
	public function actualizarInspectorRevisorFechaRevision($arrayParametros){
		$idProveedorExterior = $arrayParametros['id_proveedor_exterior'];
		$estadoSolicitud = $arrayParametros['estado_solicitud'];
		$observacionSolicitud = $arrayParametros['observacion_solicitud'];
		$identificadorRevisor = $arrayParametros['identificador_revisor'];

		$consulta = "UPDATE
                    	g_proveedores_exterior.proveedor_exterior
                    SET
                    	estado_solicitud = '" . $estadoSolicitud . "'
                        , observacion_solicitud = '" . $observacionSolicitud . "'
                        , identificador_revisor = '" . $identificadorRevisor . "' 
                    WHERE
                    	id_proveedor_exterior = '" . $idProveedorExterior . "';";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Función para crear el PDF del certificado
	 */
	public function generarCertificadoProveedorExterior($idProveedorExterior, $nombreArchivo){
		$jasper = new JasperReport();
		$datosReporte = array();

		$ruta = PROV_EXTE_CERT_URL_TCPDF . 'certificados/' . $this->rutaFecha . '/';

		if (! file_exists($ruta)){
			mkdir($ruta, 0777, true);
		}

		$rutaCertificado = PROV_EXTE_CERT . 'certificados/' . $this->rutaFecha . '/';

		$datosReporte = array(
			'rutaReporte' => 'ProveedoresExterior/vistas/reportes/proveedorExterior.jasper',
			'rutaSalidaReporte' => 'ProveedoresExterior/archivos/certificados/' . $this->rutaFecha . '/' . $nombreArchivo,
			'tipoSalidaReporte' => array('pdf'),
			'parametrosReporte' => array(
				'idProveedorExterior' => $idProveedorExterior,
				'fondoCertificado' => RUTA_IMG_GENE . 'fondoCertificado.png'),
			'conexionBase' => 'SI');

		$jasper->generarArchivo($datosReporte);

		$arrayParametros = array(
			'id_proveedor_exterior' => $idProveedorExterior,
			'tipo_adjunto' => 'Certificado Proveedor Exterior',
			'ruta_adjunto' => $rutaCertificado . $nombreArchivo . '.pdf',
			'estado_adjunto' => 'Activo');

		$this->lNegocioDocumentosAdjuntos->guardar($arrayParametros);
	}

	/**
	 * Guarda el registro de una solicitud modificada
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardarFinalizarSolicitudModificada(Array $datos){
		try{

			$idProveedorExterior = $datos['id_proveedor_exterior'];
			$arrayIdProductosProveedor = $datos['array_id_productos_proveedor'];
			$arrayNombreProductosProveedor = $datos['array_nombre_productos_proveedor'];
			$arrayDocumentosAnexosNombre = $datos['array_documentos_anexos_nombre'];
			$arrayDocumentosAnexos = $datos['array_documentos_anexos'];

			$procesoIngreso = $this->modeloProveedorExterior->getAdapter()
				->getDriver()
				->getConnection();
			$procesoIngreso->beginTransaction();

			$statement = $this->modeloProveedorExterior->getAdapter()
				->getDriver()
				->createStatement();

			if (! empty($arrayIdProductosProveedor)){

				for ($i = 0; $i < count($arrayIdProductosProveedor); $i ++){

					$arrayParametros = array(
						'id_proveedor_exterior' => (integer) $idProveedorExterior,
						'id_subtipo_producto' => $arrayIdProductosProveedor[$i],
						'nombre_subtipo_producto' => $arrayNombreProductosProveedor[$i]);

					$sqlInsertar = $this->modeloProveedorExterior->guardarSql('productos_proveedor', $this->modeloProveedorExterior->getEsquema());
					$sqlInsertar->columns($this->lNegocioProductosProveedor->columnas());
					$sqlInsertar->values($arrayParametros, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloProveedorExterior->getAdapter(), $statement);
					$statement->execute();
				}
			}

			$statement = $this->modeloProveedorExterior->getAdapter()
				->getDriver()
				->createStatement();

			if (! empty($arrayDocumentosAnexos)){

				for ($i = 0; $i < count($arrayDocumentosAnexosNombre); $i ++){

					$arrayParametros = array(
						'id_proveedor_exterior' => (integer) $idProveedorExterior,
						'tipo_adjunto' => $arrayDocumentosAnexosNombre[$i],
						'ruta_adjunto' => $arrayDocumentosAnexos[$i],
						'estado_adjunto' => 'Activo');

					$sqlInsertar = $this->modeloProveedorExterior->guardarSql('documentos_adjuntos', $this->modeloProveedorExterior->getEsquema());
					$sqlInsertar->columns($this->lNegocioDocumentosAdjuntos->columnas());
					$sqlInsertar->values($arrayParametros, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloProveedorExterior->getAdapter(), $statement);
					$statement->execute();
				}
			}

			$arrayParametros = array(
				'id_proveedor_exterior' => (integer) $idProveedorExterior);

			$this->guardar($arrayParametros);

			$procesoIngreso->commit();
			return $idProveedorExterior;
		}catch (GuardarExcepcion $ex){
			$procesoIngreso->rollback();
			throw new \Exception($ex->getMessage());
		}
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para verificar
	 * si es reemplazo de una solicitud
	 *
	 * @return array|ResultSet
	 */
	public function buscarSolicitudReemplazo($idProveedorExterior){
		$consulta = "SELECT 
                    	id_proveedor_exterior
                    	, codigo_aprobacion_solicitud
                    FROM 
                    	g_proveedores_exterior.proveedor_exterior
                    WHERE
                    	id_proveedor_exterior in (SELECT 
                    								id_solicitud_modificada
                    							FROM 
                    								g_proveedores_exterior.proveedor_exterior
                    							WHERE
                    								id_proveedor_exterior = $idProveedorExterior
                    								and es_modificada = 'SI');";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para verificar
	 * si la solicitud se encuentra en proceso de modificacion
	 *
	 * @return array|ResultSet
	 */
	public function verificarSolicitudProcesoModificacion($idSolicitudModificada){
		$consulta = "SELECT
                    	id_proveedor_exterior
                    FROM
                    	g_proveedores_exterior.proveedor_exterior
                    WHERE
                    	id_solicitud_modificada = $idSolicitudModificada;";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para actualizar
	 * los datos de solicitud reemplazad.
	 *
	 * @return array|ResultSet
	 */
	public function actualizarDatosProveedorExteriorReemplazo($arrayParametros){
		$idProveedorExterior = $arrayParametros['id_proveedor_exterior'];
		$estadoSolicitud = $arrayParametros['estado_solicitud'];

		$consulta = "UPDATE
                    	g_proveedores_exterior.proveedor_exterior
                    SET
                    	estado_solicitud = '" . $estadoSolicitud . "'
                    WHERE
                    	id_proveedor_exterior = '" . $idProveedorExterior . "';";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar proveedores exterior habilitados usando filtros.
	 *
	 * @return array|ResultSet
	 */
	public function buscarProveedoresExteriorHabilitados($arrayParametros){
		$condicion = "";
		$idProvincia = $arrayParametros['id_provincia'];

		if ($idProvincia != "Todas"){
			$condicion = " and pe.id_provincia_operador = " . $idProvincia;
		}

		$consulta = "SELECT pe.id_proveedor_exterior 
                        , pe.identificador_operador 
                        , pe.id_provincia_operador 
                        , pe.nombre_provincia_operador 
                        , pe.nombre_fabricante 
                        , pe.id_pais_fabricante 
                        , pe.nombre_pais_fabricante 
                        , pe.direccion_fabricante 
                        , pe.servicio_oficial 
                        , pe.codigo_aprobacion_solicitud 
                        , string_agg(stp.nombre, ', ') as tipos_productos 
                    FROM 
                        g_proveedores_exterior.proveedor_exterior pe 
                        INNER JOIN g_proveedores_exterior.productos_proveedor pp ON pe.id_proveedor_exterior = pp.id_proveedor_exterior 
                        INNER JOIN g_catalogos.subtipo_productos stp ON pp.id_subtipo_producto = stp.id_subtipo_producto 
                    WHERE
                    	pe.fecha_aprobacion_solicitud >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' 
                    	and pe.fecha_aprobacion_solicitud <= '" . $arrayParametros['fecha_fin'] . " 24:00:00'
                    	and pe.estado_solicitud = 'Aprobado'
                        " . $condicion . "
                    	GROUP BY 1, 2, 3, 4, 5, 6, 7, 8, 9, 10
                        ORDER BY pe.codigo_aprobacion_solicitud ASC;";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar proveedores exterior por estado.
	 *
	 * @return array|ResultSet
	 */
	public function buscarProveedoresExteriorEstadoSolictudes($arrayParametros){
		$condicion = "";
		$idProvincia = $arrayParametros['id_provincia'];

		if ($idProvincia != "Todas"){
			$condicion = " and pe.id_provincia_operador = " . $idProvincia;
		}

        $consulta = "SELECT 
                        	pe.id_proveedor_exterior 
                        	, pe.fecha_creacion_solicitud 
                        	, pe.codigo_creacion_solicitud 
                        	, pe.identificador_operador 
                        	, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador 
                        	, pe.nombre_provincia_operador
                            , pe.nombre_fabricante
							, pe.nombre_pais_fabricante
							, pe.direccion_fabricante
							, pe.fecha_aprobacion_solicitud
							, pe.codigo_aprobacion_solicitud
                        	, pe.estado_solicitud
                        	, dc.provincia as provincia_inspector 
                        	, t1.identificador_inspector 
                        	, CASE WHEN pe.estado_solicitud = 'AsignadoDocumental' THEN (SELECT 
                        																	fe.nombre || ' ' || fe.apellido 
                        																FROM 
                        																g_revision_solicitudes.asignacion_coordinador ac
                        																	INNER JOIN g_uath.datos_contrato dc ON ac.identificador_inspector = dc.identificador and dc.estado = 1 
                        																	INNER JOIN g_uath.ficha_empleado fe ON dc.identificador = fe.identificador
                        																	WHERE pe.id_proveedor_exterior = ac.id_solicitud and ac.tipo_solicitud = 'proveedorExterior') ELSE fe.nombre || ' ' || fe.apellido END nombre_inspector
                        	, pe.fecha_envio_documental 
                        	, pe.fecha_atencion_documental 
                        	, extract(days from (pe.fecha_atencion_documental - pe.fecha_envio_documental)) as dias_atencion , 
                        	pe.observacion_solicitud as observacion_inspector 
                    FROM 
                    	g_proveedores_exterior.proveedor_exterior pe
                    	INNER JOIN g_operadores.operadores o ON pe.identificador_operador = o.identificador
                    	LEFT JOIN (SELECT
                    					rd.id_grupo
                    					, grupo.id_solicitud
                    					, rd.identificador_inspector
                    					, rd.fecha_inspeccion
                    					, rd.observacion
                    				FROM
                    					g_revision_solicitudes.revision_documental rd INNER JOIN (SELECT 
                    																				max(gs.id_grupo) as id_grupo, gs.id_solicitud
                    																			FROM 
                    																				g_revision_solicitudes.grupos_solicitudes gs
                    																				INNER JOIN g_revision_solicitudes.asignacion_inspector ai ON gs.id_grupo = ai.id_grupo
                    																			WHERE
                    																				tipo_solicitud = 'proveedorExterior'
                    																				and tipo_inspector = 'Documental'
                    																			GROUP BY 2) as grupo ON rd.id_grupo = grupo.id_grupo) as t1 ON pe.id_proveedor_exterior = t1.id_solicitud
                    	LEFT JOIN g_uath.datos_contrato dc ON t1.identificador_inspector = dc.identificador and dc.estado = 1
                    	LEFT JOIN g_uath.ficha_empleado fe ON dc.identificador = fe.identificador
                        " . $condicion . "
                    WHERE
                            pe.fecha_creacion_solicitud >= '" . $arrayParametros['fecha_inicio'] . " 00:00:00' and
	                        pe.fecha_creacion_solicitud <= '" . $arrayParametros['fecha_fin'] . " 24:00:00'
                    	ORDER BY pe.codigo_creacion_solicitud ASC;";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para inhabilitar
	 * solicitudes sin subsanar.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerSolicitudesPorIntervaloPorEstadoSubsanacion($intervalo, $estado){
		
		$consulta = "SELECT
                    	pe.id_proveedor_exterior
                    FROM
                    	g_proveedores_exterior.proveedor_exterior pe
                        INNER JOIN g_proveedores_exterior.subsanacion s ON pe.id_proveedor_exterior = s.id_proveedor_exterior
                    WHERE
                    	to_char(s.fecha_subsanacion,'YYYY-MM-DD')::date + interval '" . $intervalo . " days' = current_date
                        and estado_solicitud = '" . $estado . "';";
		
		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}
	
	// Para Dossier Pecuario
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener la información del proveedor
	 * para el módulo de Dossier Pecuario
	 *
	 * @return array|ResultSet
	 */
	public function buscarInformacionProveedorDossier($codigoAprobacionSolicitud){
		$consulta = "SELECT
                    	p.id_proveedor_exterior,
                    	p.nombre_fabricante,
                    	p.id_pais_fabricante,
                    	p.nombre_pais_fabricante,
                    	p.direccion_fabricante,
                    	(SELECT 
                    		distinct RTRIM(array_to_string(array_agg(distinct nombre_subtipo_producto || ', '), ''),'') as nombre_tipo_producto 
                    	 FROM
                    		g_proveedores_exterior.productos_proveedor pp
                    	 WHERE
                    		pp.id_proveedor_exterior = p.id_proveedor_exterior) as tipo_producto
                    FROM
                    	g_proveedores_exterior.proveedor_exterior p
                    WHERE
                    	p.codigo_aprobacion_solicitud = '$codigoAprobacionSolicitud' and 
						p.estado_solicitud = 'Aprobado';";

		return $this->modeloProveedorExterior->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Función para enviar correo electrónico
	 */
	public function enviarCorreo($idSolicitud)
	{
	    $solicitud = $this->buscar($idSolicitud);
	    $identificadorOperador = $solicitud->getIdentificadorOperador();
	    $codigoCreacionSolicitud = $solicitud->getCodigoCreacionSolicitud();
	    $estadoSolicitud = $solicitud->getEstadoSolicitud();
	    
	    $operador = $this->lNegocioOperadores->buscar($identificadorOperador);
	    $correo = $operador->getCorreo();
	    $nombreOperador = ($operador->getRazonSocial() == "") ? $operador->getApellidoRepresentante() . ' ' . $operador->getNombreRepresentante() : $operador->getRazonSocial();
	    
	    $arrayCorreo = array(
	        'asunto' => 'Trámite de Proveedores en el exterior',
	        'cuerpo' => 'El área de Registros de la Agencia remite el día ' . $this->rutaFecha . ' el resultado "' . $estadoSolicitud .'" del análisis de la solicitud Nº ' . $idSolicitud . ' ('. $codigoCreacionSolicitud .'), remitido por ' . $nombreOperador . ' solicitando la habilitación del fabricante en el exterior.',
	        'estado' => 'Por enviar',
	        'codigo_modulo' => 'PRG_PROV_EXTE',
	        'tabla_modulo' => 'g_proveedores_exterior.proveedor_exterior',
	        'id_solicitud_tabla' => $idSolicitud
	    );
	    
	    $arrayDestinatario = array(
	        $correo
	    );
	    
	    return $this->lNegocioCorreos->crearCorreoElectronico($arrayCorreo, $arrayDestinatario);
	}
}
