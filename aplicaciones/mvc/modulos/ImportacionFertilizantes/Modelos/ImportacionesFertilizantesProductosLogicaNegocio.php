<?php
/**
 * Lógica del negocio de ImportacionesFertilizantesProductosModelo
 *
 * Este archivo se complementa con el archivo ImportacionesFertilizantesProductosControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-02-20
 * @uses ImportacionesFertilizantesProductosLogicaNegocio
 * @package ImportacionFertilizantes
 * @subpackage Modelos
 */
namespace Agrodb\ImportacionFertilizantes\Modelos;

use Agrodb\Core\Excepciones\GuardarExcepcion;
use Agrodb\ImportacionFertilizantes\Modelos\IModelo;

class ImportacionesFertilizantesProductosLogicaNegocio implements IModelo{

	private $modeloImportacionesFertilizantesProductos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloImportacionesFertilizantesProductos = new ImportacionesFertilizantesProductosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		
		try{
			
			$idImportacionFertilizantes = $datos['id_importacion_fertilizantes'];
			
			$procesoIngreso = $this->modeloImportacionesFertilizantesProductos->getAdapter()
				->getDriver()
				->getConnection();
			$procesoIngreso->beginTransaction();
			
			$datosActualizacionProducto = array(
				'estado' => 'inactivo');
			
			$statement = $this->modeloImportacionesFertilizantesProductos->getAdapter()
			->getDriver()
			->createStatement();
			
			$sqlActualizar = $this->modeloImportacionesFertilizantesProductos->actualizarSql('importaciones_fertilizantes_productos', $this->modeloImportacionesFertilizantesProductos->getEsquema());
			$sqlActualizar->set($datosActualizacionProducto);
			$sqlActualizar->where(array('id_importacion_fertilizantes' => $idImportacionFertilizantes));
			$sqlActualizar->prepareStatement($this->modeloImportacionesFertilizantesProductos->getAdapter(), $statement);
			$statement->execute();

			
			$statement = $this->modeloImportacionesFertilizantesProductos->getAdapter()
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

				$sqlInsertar = $this->modeloImportacionesFertilizantesProductos->guardarSql('importaciones_fertilizantes_productos', $this->modeloImportacionesFertilizantesProductos->getEsquema());
				$sqlInsertar->columns($this->columnas());
				$sqlInsertar->values($datosDetalle, $sqlInsertar::VALUES_MERGE);
				$sqlInsertar->prepareStatement($this->modeloImportacionesFertilizantesProductos->getAdapter(), $statement);
				$statement->execute();
			}
			
			$datos['ruta_archivo'] = IMP_FERT_DOC_ADJ.$datos['ruta_fecha'].'imf_'.$datos['nombre_archivo'].'.pdf';
			$datos['tipo_archivo'] = 'Autorización de importación de fertilizantes';
			
			$lNegocioDocumentosAdjuntos = new DocumentosAdjuntosLogicaNegocio();
			
			$datosActualizacionDocumento = array(
				'estado' => 'inactivo');
			
			$statement = $this->modeloImportacionesFertilizantesProductos->getAdapter()
			->getDriver()
			->createStatement();
			$sqlActualizar = $this->modeloImportacionesFertilizantesProductos->actualizarSql('documentos_adjuntos', $this->modeloImportacionesFertilizantesProductos->getEsquema());
			$sqlActualizar->set($datosActualizacionDocumento);
			$sqlActualizar->where(array('id_importacion_fertilizantes' => $idImportacionFertilizantes, 'tipo_archivo' => $datos['tipo_archivo']));
			$sqlActualizar->prepareStatement($this->modeloImportacionesFertilizantesProductos->getAdapter(), $statement);
			$statement->execute();
			
			$statement = $this->modeloImportacionesFertilizantesProductos->getAdapter()
			->getDriver()
			->createStatement();
			
			$datosDetalle = array(
				'id_importacion_fertilizantes' => (integer) $idImportacionFertilizantes,
				'tipo_archivo' => $datos['tipo_archivo'],
				'ruta_archivo' => $datos['ruta_archivo'],
				'estado' => 'activo');
			
			$sqlInsertar = $this->modeloImportacionesFertilizantesProductos->guardarSql('documentos_adjuntos', $this->modeloImportacionesFertilizantesProductos->getEsquema());
			$sqlInsertar->columns($lNegocioDocumentosAdjuntos->columnas());
			$sqlInsertar->values($datosDetalle, $sqlInsertar::VALUES_MERGE);
			$sqlInsertar->prepareStatement($this->modeloImportacionesFertilizantesProductos->getAdapter(), $statement);
			$statement->execute();
			
			$procesoIngreso->commit();
			
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
		$this->modeloImportacionesFertilizantesProductos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ImportacionesFertilizantesProductosModelo
	 */
	public function buscar($id){
		return $this->modeloImportacionesFertilizantesProductos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|Zend/Db/ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloImportacionesFertilizantesProductos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|Zend/Db/ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloImportacionesFertilizantesProductos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|Zend/Db/ResultSet
	 */
	public function buscarImportacionesFertilizantesProductos(){
		$consulta = "SELECT * FROM " . $this->modeloImportacionesFertilizantesProductos->getEsquema() . ". importaciones_fertilizantes_productos";
		return $this->modeloImportacionesFertilizantesProductos->ejecutarSqlNativo($consulta);
	}

	/**
	 * Devuelve las columnas a ser insertadas.
	 *
	 * @return String
	 */
	public function columnas(){
		$columnas = array(
			'id_importacion_fertilizantes',
			'nombre_comercial_producto',
			'nombre_producto_origen',
			'numero_registro',
			'composicion',
			'cantidad',
			'peso_neto',
			'partida_arancelaria',
			'estado');

		return $columnas;
	}
}
