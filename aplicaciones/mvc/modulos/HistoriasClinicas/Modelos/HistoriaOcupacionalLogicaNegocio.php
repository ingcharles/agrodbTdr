<?php
/**
 * Lógica del negocio de HistoriaOcupacionalModelo
 *
 * Este archivo se complementa con el archivo HistoriaOcupacionalControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses HistoriaOcupacionalLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class HistoriaOcupacionalLogicaNegocio implements IModelo{

	private $modeloHistoriaOcupacional = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloHistoriaOcupacional = new HistoriaOcupacionalModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new HistoriaOcupacionalModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdHistoriaOcupacional() != null && $tablaModelo->getIdHistoriaOcupacional() > 0){
			return $this->modeloHistoriaOcupacional->actualizar($datosBd, $tablaModelo->getIdHistoriaOcupacional());
		}else{
			unset($datosBd["id_historia_ocupacional"]);
			return $this->modeloHistoriaOcupacional->guardar($datosBd);
		}
	}

	/**
	 * guardar historia ocupacional y detalle de historia ocupacional
	 */
	public function guardarHistoriaDetalle(Array $datos){
		try{
			$this->modeloHistoriaOcupacional = new HistoriaOcupacionalModelo();
			$proceso = $this->modeloHistoriaOcupacional->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción: Agregar historia ocupacional');
			}
			$tablaModelo = new HistoriaOcupacionalModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			unset($datosBd["id_historia_ocupacional"]);
			$idHistoriaOcupacional = $this->modeloHistoriaOcupacional->guardar($datosBd);

			if (! $idHistoriaOcupacional){
				throw new \Exception('No se registo los datos en la tabla historia_ocupacional');
			}
			if (isset($_POST['subtipoList'])){
				$lnegocioDetalleHistoria = new DetalleHistorialOcupacionalLogicaNegocio();
				foreach ($_POST['subtipoList'] as $item){
					$datos = array(
						'id_historia_ocupacional' => $idHistoriaOcupacional,
						'id_subtipo_proced_medico' => $item);
					$statement = $this->modeloHistoriaOcupacional->getAdapter()
						->getDriver()
						->createStatement();
					$sqlInsertar = $this->modeloHistoriaOcupacional->guardarSql('detalle_historial_ocupacional', $this->modeloHistoriaOcupacional->getEsquema());
					$sqlInsertar->columns($lnegocioDetalleHistoria->columnas());
					$sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
					$sqlInsertar->prepareStatement($this->modeloHistoriaOcupacional->getAdapter(), $statement);
					$statement->execute();
				}
			}
			$proceso->commit();
			return true;
		}catch (\Exception $ex){
			$proceso->rollback();
			throw new \Exception($ex->getMessage());
			return false;
		}
		;
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloHistoriaOcupacional->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return HistoriaOcupacionalModelo
	 */
	public function buscar($id){
		return $this->modeloHistoriaOcupacional->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloHistoriaOcupacional->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloHistoriaOcupacional->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array ResultSet
	 */
	public function buscarHistoriaOcupacional(){
		$consulta = "SELECT * FROM " . $this->modeloHistoriaOcupacional->getEsquema() . ". historia_ocupacional";
		return $this->modeloHistoriaOcupacional->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas de la tabla g_historias_clinicas.historia_ocupacional
	 *
	 * @return string
	 */
	public function columnas(){
		$columnas = array(
			'id_historia_clinica',
			'empresa',
			'cargo',
			'id_procedimiento_medico',
			'id_tipo_procedimiento_medico',
			'tiempo_exposicion');
		return $columnas;
	}
}

