<?php
/**
 * Lógica del negocio de ImportacionesFertilizantesModelo
 *
 * Este archivo se complementa con el archivo ImportacionesFertilizantesControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-02-20
 * @uses ImportacionesFertilizantesLogicaNegocio
 * @package ImportacionFertilizantes
 * @subpackage Modelos
 */
namespace Agrodb\ImportacionFertilizantes\Modelos;

use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\ImportacionFertilizantes\Modelos\IModelo;
use Agrodb\Core\JasperReport;

class ImportacionesFertilizantesLogicaNegocio implements IModelo{

	private $modeloImportacionesFertilizantes = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloImportacionesFertilizantes = new ImportacionesFertilizantesModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		try{
			
			if($datos['id_pais_origen'] == 'No aplica'){
				unset($datos['id_pais_origen']);
			}
			
			if($datos['id_pais_procedencia'] == 'No aplica'){
				unset($datos['id_pais_procedencia']);
			}
			
			$tablaModelo = new ImportacionesFertilizantesModelo($datos);
			$procesoGuardar = false;
			$procesoIngreso = $this->modeloImportacionesFertilizantes->getAdapter()
				->getDriver()
				->getConnection();
			$procesoIngreso->beginTransaction();

			$datosBd = $tablaModelo->getPrepararDatos();
			if ($tablaModelo->getIdImportacionFertilizantes() != null && $tablaModelo->getIdImportacionFertilizantes() > 0){
				$this->modeloImportacionesFertilizantes->actualizar($datosBd, $tablaModelo->getIdImportacionFertilizantes());
				$idImportacionFertilizantes = $tablaModelo->getIdImportacionFertilizantes();
				$procesoGuardar = true;
			}else{
				unset($datosBd["id_importacion_fertilizantes"]);
				$idImportacionFertilizantes = $this->modeloImportacionesFertilizantes->guardar($datosBd);
			}
			
			$lNegocioImportacionesFertilizantesProductos = new ImportacionesFertilizantesProductosLogicaNegocio();
			
			if($procesoGuardar){
				$datosActualizacionProducto = array(
					'estado' => 'inactivo');
				
				$statement = $this->modeloImportacionesFertilizantes->getAdapter()
				->getDriver()
				->createStatement();
				$sqlActualizar = $this->modeloImportacionesFertilizantes->actualizarSql('importaciones_fertilizantes_productos', $this->modeloImportacionesFertilizantes->getEsquema());
				$sqlActualizar->set($datosActualizacionProducto);
				$sqlActualizar->where(array('id_importacion_fertilizantes' => $idImportacionFertilizantes));
				$sqlActualizar->prepareStatement($this->modeloImportacionesFertilizantes->getAdapter(), $statement);
				$statement->execute();
			}
			
			$statement = $this->modeloImportacionesFertilizantes->getAdapter()
			->getDriver()
			->createStatement();
			
			for ($i = 0; $i < count($datos['nombre_producto_origen']); $i ++){
				
				$datosDetalle = array(
					'id_importacion_fertilizantes' => (integer) $idImportacionFertilizantes,
					'nombre_comercial_producto' => $datos['nombre_comercial_producto'][$i],
					'nombre_producto_origen' => $datos['nombre_producto_origen'][$i],
					'numero_registro' => $datos['numero_registro'][$i],
					'composicion' => $datos['composicion'][$i],
					'cantidad' => $datos['cantidad'][$i],
					'peso_neto' => $datos['peso_neto'][$i],
					'partida_arancelaria' => $datos['partida_arancelaria'][$i],
					'estado' => 'activo');
				
				$sqlInsertar = $this->modeloImportacionesFertilizantes->guardarSql('importaciones_fertilizantes_productos', $this->modeloImportacionesFertilizantes->getEsquema());
				$sqlInsertar->columns($lNegocioImportacionesFertilizantesProductos->columnas());
				$sqlInsertar->values($datosDetalle, $sqlInsertar::VALUES_MERGE);
				$sqlInsertar->prepareStatement($this->modeloImportacionesFertilizantes->getAdapter(), $statement);
				$statement->execute();
				
			}
			
			array_push($datos['ruta_archivo'], IMP_FERT_DOC_ADJ.$datos['ruta_fecha'].'imf_'.$datos['nombre_archivo'].'.pdf');
			array_push($datos['tipo_archivo'], 'Autorización de importación de fertilizantes');

			$lNegocioDocumentosAdjuntos = new DocumentosAdjuntosLogicaNegocio();

			if($procesoGuardar){
				$datosActualizacionDocumento = array(
					'estado' => 'inactivo');
				
				$statement = $this->modeloImportacionesFertilizantes->getAdapter()
				->getDriver()
				->createStatement();
				$sqlActualizar = $this->modeloImportacionesFertilizantes->actualizarSql('documentos_adjuntos', $this->modeloImportacionesFertilizantes->getEsquema());
				$sqlActualizar->set($datosActualizacionDocumento);
				$sqlActualizar->where(array('id_importacion_fertilizantes' => $idImportacionFertilizantes));
				$sqlActualizar->prepareStatement($this->modeloImportacionesFertilizantes->getAdapter(), $statement);
				$statement->execute();
			}
			
			$statement = $this->modeloImportacionesFertilizantes->getAdapter()
				->getDriver()
				->createStatement();

			for ($i = 0; $i < count($datos['ruta_archivo']); $i ++){

				if ($datos['ruta_archivo'][$i] != '0'){
					
					if($datos['tipo_archivo'][$i] == 'Autorización de importación de fertilizantes'){
						$estado = 'temporal';
					}else{
						$estado = 'activo';
					}

					$datosDetalle = array(
						'id_importacion_fertilizantes' => (integer) $idImportacionFertilizantes,
						'tipo_archivo' => $datos['tipo_archivo'][$i],
						'ruta_archivo' => $datos['ruta_archivo'][$i],
						'estado' => $estado);

					$sqlInsertar = $this->modeloImportacionesFertilizantes->guardarSql('documentos_adjuntos', $this->modeloImportacionesFertilizantes->getEsquema());
					$sqlInsertar->columns($lNegocioDocumentosAdjuntos->columnas());
					$sqlInsertar->values($datosDetalle, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloImportacionesFertilizantes->getAdapter(), $statement);
					$statement->execute();
				}
			}

			$procesoIngreso->commit();
			return $idImportacionFertilizantes;
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
		$this->modeloImportacionesFertilizantes->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ImportacionesFertilizantesModelo
	 */
	public function buscar($id){
		return $this->modeloImportacionesFertilizantes->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|Zend/Db/ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloImportacionesFertilizantes->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|Zend/Db/ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloImportacionesFertilizantes->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|Zend/Db/ResultSet
	 */
	public function buscarImportacionesFertilizantes(){
		$consulta = "SELECT * FROM " . $this->modeloImportacionesFertilizantes->getEsquema() . ". importaciones_fertilizantes";
		return $this->modeloImportacionesFertilizantes->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Generar documento de impotacion de fertilizantes.
	 *
	 * @return 
	 */
	public function generarDocumentoFertilizantes($idImportacionFertilizantes, $rutaFecha, $nombreDocumento){
		
		$jasper = new JasperReport();
		$datosReporte = array();
		
		$ruta = IMP_FERT_RUT_COMPL . $rutaFecha;
		
		if (! file_exists($ruta)){
			mkdir($ruta, 0777, true);
		}
		
		$rutaArchivo = 'ImportacionFertilizantes/archivos/';
		$nombreArchivo = 'imf_'.$nombreDocumento;
		
		$datosReporte = array(
			'rutaReporte' => 'ImportacionFertilizantes/vistas/reportes/importacionFertilizantes.jasper',
			'rutaSalidaReporte' => $rutaArchivo.$rutaFecha.$nombreArchivo,
			'tipoSalidaReporte' => array('pdf'),
			'parametrosReporte' => array('idImportacionFertilizante' => (integer) $idImportacionFertilizantes,
										 'fondoCertificado' => RUTA_IMG_GENE.'fondoCertificado.png',
										 'rutaArchivo' => URL_MVC_MODULO.$rutaArchivo.$rutaFecha.$nombreArchivo.'.pdf'),
			'conexionBase' => 'SI'
		);
		
		$jasper->generarArchivo($datosReporte);
		
	}	
	
}
