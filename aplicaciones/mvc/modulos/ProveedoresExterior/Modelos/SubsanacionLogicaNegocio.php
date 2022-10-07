<?php
/**
 * Lógica del negocio de SubsanacionModelo
 *
 * Este archivo se complementa con el archivo SubsanacionControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-13
 * @uses SubsanacionLogicaNegocio
 * @package ProveedoresExterior
 * @subpackage Modelos
 */
namespace Agrodb\ProveedoresExterior\Modelos;

use Agrodb\ProveedoresExterior\Modelos\IModelo;
use Agrodb\ProveedoresExterior\Modelos\DetalleSubsanacionLogicaNegocio;
use Agrodb\Core\Excepciones\GuardarExcepcion;

class SubsanacionLogicaNegocio implements IModelo{

	private $modeloSubsanacion = null;

	private $lNegocioDetalleSubsanacion = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloSubsanacion = new SubsanacionModelo();
		$this->lNegocioDetalleSubsanacion = new DetalleSubsanacionLogicaNegocio();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		try{

			$tablaModelo = new SubsanacionModelo($datos);
			$procesoIngreso = $this->modeloSubsanacion->getAdapter()
				->getDriver()
				->getConnection();
			$procesoIngreso->beginTransaction();

			$datosBd = $tablaModelo->getPrepararDatos();
			if ($tablaModelo->getIdSubsanacion() != null && $tablaModelo->getIdSubsanacion() > 0){
				$this->modeloSubsanacion->actualizar($datosBd, $tablaModelo->getIdSubsanacion());
				$idSubsanacion = $tablaModelo->getIdSubsanacion();
			}else{
				unset($datosBd["id_subsanacion"]);
				$idSubsanacion = $this->modeloSubsanacion->guardar($datosBd);
			}

			$datos += [
				'id_subsanacion' => $idSubsanacion];

			// -------------------------------------------//
			// Se hace el guardar detalle de la subsanacion
			$statement = $this->modeloSubsanacion->getAdapter()
				->getDriver()
				->createStatement();

			$arrayParametros = array(
				'id_subsanacion' => $idSubsanacion,
				'identificador_revisor' => $datos['identificador_revisor'],
				'fecha_subsanacion' => $datos['fecha_subsanacion']);

			$sqlInsertar = $this->modeloSubsanacion->guardarSql('detalle_subsanacion', $this->modeloSubsanacion->getEsquema());
			$sqlInsertar->columns($this->lNegocioDetalleSubsanacion->columnas());
			$sqlInsertar->values($arrayParametros, $sqlInsertar::VALUES_MERGE);
			$sqlInsertar->prepareStatement($this->modeloSubsanacion->getAdapter(), $statement);
			$statement->execute();

			$procesoIngreso->commit();
			return $idSubsanacion;
		}catch (GuardarExcepcion $ex){
			$procesoIngreso->rollback();
			throw new \Exception($ex->getMessage());
		}
	}

	/**
	 * Actualizar el registro actual de subsanacion
	 *
	 * @param array $datos
	 * @return int
	 */
	public function actualizarSubsanacion(Array $datos){
		try{

			$idSubsanacion = $datos['id_subsanacion'];

			if (isset($datos['id_detalle_subsanacion'])){

				$arrayParametrosSubsanacion = array(
					'id_subsanacion' => $datos['id_subsanacion'],
					'fecha_subsanacion_operador' => $datos['fecha_subsanacion_operador'],
					'descontar_dias' => $datos['descontar_dias']);
			}else{

				$arrayParametrosSubsanacion = array(
					'id_subsanacion' => $datos['id_subsanacion'],
					'identificador_revisor' => $datos['identificador_revisor'],
					'observacion_subsanacion' => $datos['observacion_subsanacion'],
					'fecha_subsanacion' => $datos['fecha_subsanacion'],
					'fecha_subsanacion_operador' => $datos['fecha_subsanacion_operador'],
					'descontar_dias' => $datos['descontar_dias']);

				if (isset($datos['ruta_archivo_subsanacion'])){
					$arrayParametrosSubsanacion += [
						'ruta_archivo_subsanacion' => $datos['ruta_archivo_subsanacion']];
				}
			}

			$procesoIngreso = $this->modeloSubsanacion->getAdapter()
				->getDriver()
				->getConnection();
			$procesoIngreso->beginTransaction();

			$statement = $this->modeloSubsanacion->getAdapter()
				->getDriver()
				->createStatement();

			$sqlActualizar = $this->modeloSubsanacion->actualizarSql('subsanacion', $this->modeloSubsanacion->getEsquema());
			$sqlActualizar->set($arrayParametrosSubsanacion);
			$sqlActualizar->where(array(
				'id_subsanacion' => $idSubsanacion));
			$sqlActualizar->prepareStatement($this->modeloSubsanacion->getAdapter(), $statement);
			$statement->execute();
			$statement->getParameterContainer();

			// -------------------------------------------//
			// Se hace el guardar detalle de la subsanacion

			$statement = $this->modeloSubsanacion->getAdapter()
				->getDriver()
				->createStatement();

			if (isset($datos['id_detalle_subsanacion'])){

				$arrayParametrosDetalleSubsanacion = array(
					'id_detalle_subsanacion' => $datos['id_detalle_subsanacion'],
					'fecha_subsanacion_operador' => $datos['fecha_subsanacion_operador'],
					'dias_transcurridos' => $datos['dias_transcurridos']);

				$idDetalleSubsanacion = $datos['id_detalle_subsanacion'];

				$sqlActualizar = $this->modeloSubsanacion->actualizarSql('detalle_subsanacion', $this->modeloSubsanacion->getEsquema());
				$sqlActualizar->set($arrayParametrosDetalleSubsanacion);
				$sqlActualizar->where(array(
					'id_detalle_subsanacion' => $idDetalleSubsanacion));
				$sqlActualizar->prepareStatement($this->modeloSubsanacion->getAdapter(), $statement);
				$statement->execute();
				$statement->getParameterContainer();
			}else{

				$arrayParametrosDetalleSubsanacion = array(
					'id_subsanacion' => $idSubsanacion,
					'identificador_revisor' => $datos['identificador_revisor'],
					'fecha_subsanacion' => $datos['fecha_subsanacion']);

				$sqlInsertar = $this->modeloSubsanacion->guardarSql('detalle_subsanacion', $this->modeloSubsanacion->getEsquema());
				$sqlInsertar->columns($this->lNegocioDetalleSubsanacion->columnas());
				$sqlInsertar->values($arrayParametrosDetalleSubsanacion, $sqlInsertar::VALUES_MERGE);
				$sqlInsertar->prepareStatement($this->modeloSubsanacion->getAdapter(), $statement);
				$statement->execute();
			}

			$procesoIngreso->commit();
			return $idSubsanacion;
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
		$this->modeloSubsanacion->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return SubsanacionModelo
	 */
	public function buscar($id){
		return $this->modeloSubsanacion->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloSubsanacion->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloSubsanacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarSubsanacion($idProveedorExterior){
		$consulta = "SELECT * FROM " . $this->modeloSubsanacion->getEsquema() . ". subsanacion WHERE id_proveedor_exterior = '" . $idProveedorExterior . "'";
		return $this->modeloSubsanacion->ejecutarSqlNativo($consulta);
	}
}
