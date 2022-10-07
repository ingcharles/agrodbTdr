<?php
/**
 * Lógica del negocio de RevisionOrganosSistemasModelo
 *
 * Este archivo se complementa con el archivo RevisionOrganosSistemasControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses RevisionOrganosSistemasLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class RevisionOrganosSistemasLogicaNegocio implements IModelo{

	private $modeloRevisionOrganosSistemas = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloRevisionOrganosSistemas = new RevisionOrganosSistemasModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new RevisionOrganosSistemasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRevisionOrganosSistemas() != null && $tablaModelo->getIdRevisionOrganosSistemas() > 0){
			return $this->modeloRevisionOrganosSistemas->actualizar($datosBd, $tablaModelo->getIdRevisionOrganosSistemas());
		}else{
			unset($datosBd["id_revision_organos_sistemas"]);
			return $this->modeloRevisionOrganosSistemas->guardar($datosBd);
		}
	}

	/**
	 * guardar organos, sistemas y detalle de organos y sistemas
	 */
	public function guardarOrganosSistemasDetalle(Array $datos){
		try{
			$this->modeloRevisionOrganosSistemas = new RevisionOrganosSistemasModelo();
			$proceso = $this->modeloRevisionOrganosSistemas->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción: Agregar Organos y Sistemas ');
			}

			$datosSubTxt = array();
			$datosSub = array();
			if (isset($datos['subtipoList'])){
				foreach ($datos['subtipoList'] as $value){
					$subtip = explode(",", $value);
					$datosIndi[] = $subtip[0];
					$datosSub[] = [
						$subtip[0] => $subtip[1]];
				}
			}

			if (isset($datos['subtipoTxt'])){
				foreach ($datos['subtipoTxt'] as $value){
					$subtipTxt = explode(",", $value);
					$datosIndi[] = $subtipTxt[0];
					$datosSubTxt[] = [
						$subtipTxt[0] => $subtipTxt[1]];
				}
			}
			$datosIndi = array_unique($datosIndi);
			$arrayDet = array();
			foreach ($datosIndi as $value){
				$arrayRevision = array(
					'id_historia_clinica' => $datos['id_historia_clinica'],
					'id_procedimiento_medico' => $datos['id_procedimiento_medico'],
					'id_tipo_procedimiento_medico' => $value,
					'observaciones' => $datos['observaciones']);

				$tablaModelo = new RevisionOrganosSistemasModelo($arrayRevision);
				$datosBd = $tablaModelo->getPrepararDatos();
				unset($datosBd["id_revision_organos_sistemas"]);
				$idRevisionOrganosSistemas = $this->modeloRevisionOrganosSistemas->guardar($datosBd);

				if (! $idRevisionOrganosSistemas){
					throw new \Exception('No se registo los datos en la tabla revision_organos_sistemas');
				}
				$arrayDet[] = [
					$value,
					$idRevisionOrganosSistemas];
			}

			foreach ($arrayDet as $det){
				foreach ($datosSub as $item){

					if (isset($item[$det[0]])){
						$arrayRevisionDetal = array(
							'id_revision_organos_sistemas' => intval($det[1]),
							'id_subtipo_proced_medico' => intval($item[$det[0]]),
							'otros' => '');

						$this->guardarDetalles($arrayRevisionDetal);
					}
				}
			}
			foreach ($arrayDet as $det){
				foreach ($datosSubTxt as $itemTxt){
					if (isset($itemTxt[$det[0]])){
						$arrayRevisionTxt = array(
							'id_revision_organos_sistemas' => intval($det[1]),
							'id_subtipo_proced_medico' => null,
							'otros' => $itemTxt[$det[0]]);
						$this->guardarDetalles($arrayRevisionTxt);
					}
				}
			}

			$proceso->commit();
			return true;
		}catch (\Exception $ex){
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
			return false;
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
		$this->modeloRevisionOrganosSistemas->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return RevisionOrganosSistemasModelo
	 */
	public function buscar($id){
		return $this->modeloRevisionOrganosSistemas->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloRevisionOrganosSistemas->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloRevisionOrganosSistemas->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarRevisionOrganosSistemas(){
		$consulta = "SELECT * FROM " . $this->modeloRevisionOrganosSistemas->getEsquema() . ". revision_organos_sistemas";
		return $this->modeloRevisionOrganosSistemas->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar detalles
	 *
	 * @return array
	 */
	public function guardarDetalles($datos){
		$statement = $this->modeloRevisionOrganosSistemas->getAdapter()
			->getDriver()
			->createStatement();
		$lnegocioDetalleRevisionOrganosSistemas = new DetalleRevisionOrganosSistemasLogicaNegocio();
		$sqlInsertar = $this->modeloRevisionOrganosSistemas->guardarSql('detalle_revision_organos_sistemas', $this->modeloRevisionOrganosSistemas->getEsquema());
		$sqlInsertar->columns($lnegocioDetalleRevisionOrganosSistemas->columnas());
		$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
		$sqlInsertar->prepareStatement($this->modeloRevisionOrganosSistemas->getAdapter(), $statement);
		$statement->execute();
	}
}
