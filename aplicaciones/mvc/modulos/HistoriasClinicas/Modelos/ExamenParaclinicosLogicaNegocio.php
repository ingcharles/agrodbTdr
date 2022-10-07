<?php
/**
 * Lógica del negocio de ExamenParaclinicosModelo
 *
 * Este archivo se complementa con el archivo ExamenParaclinicosControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses ExamenParaclinicosLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class ExamenParaclinicosLogicaNegocio implements IModelo{

	private $modeloExamenParaclinicos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloExamenParaclinicos = new ExamenParaclinicosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new ExamenParaclinicosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdExamenParaclinicos() != null && $tablaModelo->getIdExamenParaclinicos() > 0){
			return $this->modeloExamenParaclinicos->actualizar($datosBd, $tablaModelo->getIdExamenParaclinicos());
		}else{
			unset($datosBd["id_examen_paraclinicos"]);
			return $this->modeloExamenParaclinicos->guardar($datosBd);
		}
	}

	public function guardarParaclinicosDetalle(Array $datos){
		try{
			$this->modeloExamenParaclinicos = new ExamenParaclinicosModelo();
			$proceso = $this->modeloExamenParaclinicos->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción: Agregar examenes ');
			}
			$tablaModelo = new ExamenParaclinicosModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			unset($datosBd["id_examen_paraclinicos"]);
			$idExamenParaclinicos = $this->modeloExamenParaclinicos->guardar($datosBd);
			if (! $idExamenParaclinicos){
				throw new \Exception('No se registo los datos en la tabla examen_paraclinicos');
			}

			$respuesta = array();
			$oido = array();
			$combinar = array();
			$id = '';

			if (isset($datos['respuesta_check'])){
				foreach ($datos['respuesta_check'] as $value){
					$subtip = explode("-", $value);
					$id = $subtip[1];
					if ($subtip[0] == 'r'){
						$respuesta[] = [
							$subtip[1] => [
								$subtip[2],
								'']];
					}else{
						$respuesta[] = [
							$subtip[1] => [
								$subtip[2],
								$subtip[3]]];
					}
				}
			}
			if (isset($datos['oido_check'])){
				foreach ($datos['oido_check'] as $value){
					$subtipTxt = explode("-", $value);
					$id = $subtipTxt[1];
					$oido[] = [
						$subtipTxt[1] => [
							$subtipTxt[2],
							$subtipTxt[3]]];
				}
			}

			foreach ($respuesta as $item){
				$ban = 1;
				foreach ($oido as $item2){
					if ($item2[$id][0] == $item[$id][0]){
						$combinar[] = [
							$id => [
								$item[$id][0],
								$item[$id][1],
								$item2[$id][1]]];
						$ban = 0;
					}
				}
				if ($ban){
					$combinar[] = [
						$id => [
							$item[$id][0],
							$item[$id][1],
							'']];
				}
			}

			foreach ($combinar as $item){
				if (isset($item[$id][0])){
					$detalleExamenes = array(
						'id_examen_paraclinicos' => $idExamenParaclinicos,
						'id_subtipo_proced_medico' => intval($item[$id][0]),
						'respuesta' => $item[$id][1],
						'oido' => $item[$id][2]);
					$this->guardarDetalles($detalleExamenes);
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
		$this->modeloExamenParaclinicos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ExamenParaclinicosModelo
	 */
	public function buscar($id){
		return $this->modeloExamenParaclinicos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloExamenParaclinicos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloExamenParaclinicos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarExamenParaclinicos(){
		$consulta = "SELECT * FROM " . $this->modeloExamenParaclinicos->getEsquema() . ". examen_paraclinicos";
		return $this->modeloExamenParaclinicos->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar detalles
	 *
	 * @return array
	 */
	public function guardarDetalles($datos){
		$statement = $this->modeloExamenParaclinicos->getAdapter()
			->getDriver()
			->createStatement();
		$lnegocioDetalleExamenParaclinicos = new DetalleExamenParaclinicosLogicaNegocio();
		$sqlInsertar = $this->modeloExamenParaclinicos->guardarSql('detalle_examen_paraclinicos', $this->modeloExamenParaclinicos->getEsquema());
		$sqlInsertar->columns($lnegocioDetalleExamenParaclinicos->columnas());
		$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
		$sqlInsertar->prepareStatement($this->modeloExamenParaclinicos->getAdapter(), $statement);
		$statement->execute();
	}
}
