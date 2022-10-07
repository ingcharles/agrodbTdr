<?php
/**
 * Lógica del negocio de ExamenesClinicosModelo
 *
 * Este archivo se complementa con el archivo ExamenesClinicosControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses ExamenesClinicosLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class ExamenesClinicosLogicaNegocio implements IModelo{

	private $modeloExamenesClinicos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloExamenesClinicos = new ExamenesClinicosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new ExamenesClinicosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdExamenesClinicos() != null && $tablaModelo->getIdExamenesClinicos() > 0){
			return $this->modeloExamenesClinicos->actualizar($datosBd, $tablaModelo->getIdExamenesClinicos());
		}else{
			unset($datosBd["id_examenes_clinicos"]);
			return $this->modeloExamenesClinicos->guardar($datosBd);
		}
	}

	/**
	 * guardar examenes
	 */
	public function guardarExamenesDetalle(Array $datos){
		try{
			$this->modeloExamenesClinicos = new ExamenesClinicosModelo();
			$proceso = $this->modeloExamenesClinicos->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción: Agregar examenes ');
			}

			$estadoClinico = array();
			$observa = array();
			$combinar = array();

			if (isset($datos['estado_clinico'])){
				foreach ($datos['estado_clinico'] as $value){
					$subtip = explode("-", $value);
					$datosIndi[] = $subtip[1];
					$estadoClinico[] = [
						$subtip[1] => [
							$subtip[2],
							$subtip[3]]];
				}
			}
			if (isset($datos['observaciones'])){
				foreach ($datos['observaciones'] as $value){
					$subtipTxt = explode("-", $value);
					$datosIndi[] = $subtipTxt[1];
					$observa[] = [
						$subtipTxt[1] => [
							$subtipTxt[2],
							$subtipTxt[3]]];
				}
			}

			$datosIndi = array_unique($datosIndi);
			foreach ($datosIndi as $value){
				foreach ($estadoClinico as $item){
					$ban = 1;
					foreach ($observa as $item2){
						if ($item2[$value][0] == $item[$value][0]){
							$combinar[] = [
								$value => [
									$item[$value][0],
									$item[$value][1],
									$item2[$value][1]]];
							$ban = 0;
						}
					}
					if ($ban){
						$combinar[] = [
							$value => [
								$item[$value][0],
								$item[$value][1],
								'']];
					}
				}
			}

			foreach ($datosIndi as $value){
				$arrayRevision = array(
					'id_historia_clinica' => $datos['id_historia_clinica'],
					'id_procedimiento_medico' => $datos['id_procedimiento_medico'],
					'id_tipo_procedimiento_medico' => $value,
					'fecha_examen' => $datos['fecha_examen']);
				$tablaModelo = new ExamenesClinicosModelo($arrayRevision);
				$datosBd = $tablaModelo->getPrepararDatos();
				unset($datosBd["id_examenes_clinicos"]);
				$idExamenesClinicos = $this->modeloExamenesClinicos->guardar($datosBd);

				if (! $idExamenesClinicos){
					throw new \Exception('No se registo los datos en la tabla examenes_clinicos');
				}

				foreach ($combinar as $item){
					if (isset($item[$value][0])){
						$detalleExamenes = array(
							'id_examenes_clinicos' => $idExamenesClinicos,
							'id_subtipo_proced_medico' => intval($item[$value][0]),
							'estado_clinico' => $item[$value][1],
							'observaciones' => $item[$value][2]);
						$this->guardarDetalles($detalleExamenes);
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
		$this->modeloExamenesClinicos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ExamenesClinicosModelo
	 */
	public function buscar($id){
		return $this->modeloExamenesClinicos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloExamenesClinicos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloExamenesClinicos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarExamenesClinicos(){
		$consulta = "SELECT * FROM " . $this->modeloExamenesClinicos->getEsquema() . ". examenes_clinicos";
		return $this->modeloExamenesClinicos->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para guardar detalles
	 *
	 * @return array
	 */
	public function guardarDetalles($datos){
		$statement = $this->modeloExamenesClinicos->getAdapter()
			->getDriver()
			->createStatement();
		$lnegocioDetalleExamenesClinicos = new DetalleExamenesClinicosLogicaNegocio();
		$sqlInsertar = $this->modeloExamenesClinicos->guardarSql('detalle_examenes_clinicos', $this->modeloExamenesClinicos->getEsquema());
		$sqlInsertar->columns($lnegocioDetalleExamenesClinicos->columnas());
		$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
		$sqlInsertar->prepareStatement($this->modeloExamenesClinicos->getAdapter(), $statement);
		$statement->execute();
	}
}
