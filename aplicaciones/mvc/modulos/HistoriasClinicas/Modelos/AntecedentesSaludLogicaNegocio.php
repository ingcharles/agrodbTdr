<?php
/**
 * Lógica del negocio de AntecedentesSaludModelo
 *
 * Este archivo se complementa con el archivo AntecedentesSaludControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses AntecedentesSaludLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class AntecedentesSaludLogicaNegocio implements IModelo{

	private $modeloAntecedentesSalud = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloAntecedentesSalud = new AntecedentesSaludModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new AntecedentesSaludModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAntecedentesSalud() != null && $tablaModelo->getIdAntecedentesSalud() > 0){
			return $this->modeloAntecedentesSalud->actualizar($datosBd, $tablaModelo->getIdAntecedentesSalud());
		}else{
			unset($datosBd["id_antecedentes_salud"]);
			return $this->modeloAntecedentesSalud->guardar($datosBd);
		}
	}

	/**
	 * guardar historia ocupacional y detalle de historia ocupacional
	 */
	public function guardarAntecedentesDetalle(Array $datos){
		try{
			$this->modeloAntecedentesSalud = new AntecedentesSaludModelo();
			$proceso = $this->modeloAntecedentesSalud->getAdapter()
				->getDriver()
				->getConnection();
			if (! $proceso->beginTransaction()){
				throw new \Exception('No se pudo iniciar la transacción: Agregar antecedentes de salud');
			}
			$tablaModelo = new AntecedentesSaludModelo($datos);
			$datosBd = $tablaModelo->getPrepararDatos();
			unset($datosBd["id_antecedentes_salud"]);
			$idAntecedentesSalud = $this->modeloAntecedentesSalud->guardar($datosBd);

			if (! $idAntecedentesSalud){
				throw new \Exception('No se registo los datos en la tabla antecedentes_salud');
			}

			$detalleAntecedSalud = array(
				'id_antecedentes_salud' => $idAntecedentesSalud,
				'id_cie' => (isset($datos['id_cie'])) ? $datos['id_cie'] : NULL,
				'diagnostico' => (isset($datos['diagnostico'])) ? $datos['diagnostico'] : NULL,
				'observaciones' => (isset($datos['observaciones'])) ? $datos['observaciones'] : NULL,
				'ciclo_mestrual' => (isset($datos['ciclo_mestrual'])) ? $datos['ciclo_mestrual'] : NULL,
				'fecha_ultima_regla' => (isset($datos['fecha_ultima_regla'])) ? (($datos['fecha_ultima_regla'] != '') ? $datos['fecha_ultima_regla'] : NULL) : NULL,
				'fecha_ultima_citologia' => (isset($datos['fecha_ultima_citologia'])) ? (($datos['fecha_ultima_citologia'] != '') ? $datos['fecha_ultima_citologia'] : NULL) : NULL,
				'resultado_citologia' => (isset($datos['resultado_citologia'])) ? $datos['resultado_citologia'] : NULL,
				'numero_gestaciones' => (isset($datos['numero_gestaciones'])) ? $datos['numero_gestaciones'] : NULL,
				'numero_partos' => (isset($datos['numero_partos'])) ? $datos['numero_partos'] : NULL,
				'numero_cesareas' => (isset($datos['numero_cesareas'])) ? $datos['numero_cesareas'] : NULL,
				'numero_abortos' => (isset($datos['numero_abortos'])) ? $datos['numero_abortos'] : NULL,
				'numero_hijos_vivos' => (isset($datos['numero_hijos_vivos'])) ? $datos['numero_hijos_vivos'] : NULL,
				'numero_hijos_muertos' => (isset($datos['numero_hijos_muertos'])) ? $datos['numero_hijos_muertos'] : NULL,
				'embarazo' => (isset($datos['embarazo'])) ? $datos['embarazo'] : NULL,
				'semanas_gestacion' => (isset($datos['semanas_gestacion'])) ? (($datos['semanas_gestacion'] != '') ? $datos['semanas_gestacion'] : NULL) : NULL,
				'numero_ecos' => (isset($datos['numero_ecos'])) ? (($datos['numero_ecos'] != '') ? $datos['numero_ecos'] : NULL) : NULL,
				'numero_controles_embarazo' => (isset($datos['numero_controles_embarazo'])) ? (($datos['numero_controles_embarazo'] != '') ? $datos['numero_controles_embarazo'] : NULL) : NULL,
				'complicaciones' => (isset($datos['complicaciones'])) ? $datos['complicaciones'] : NULL,
				'vida_sexual_activa' => (isset($datos['vida_sexual_activa'])) ? $datos['vida_sexual_activa'] : NULL,
				'planificacion_familiar' => (isset($datos['planificacion_familiar'])) ? $datos['planificacion_familiar'] : NULL,
				'metodo_planificacion' => (isset($datos['metodo_planificacion'])) ? $datos['metodo_planificacion'] : NULL);

			$lnegocioDetalleAntecedentesSalud = new DetalleAntecedentesSaludLogicaNegocio();
			$statement = $this->modeloAntecedentesSalud->getAdapter()
				->getDriver()
				->createStatement();
			$sqlInsertar = $this->modeloAntecedentesSalud->guardarSql('detalle_antecedentes_salud', $this->modeloAntecedentesSalud->getEsquema());
			$sqlInsertar->columns($lnegocioDetalleAntecedentesSalud->columnas());
			$sqlInsertar->values($detalleAntecedSalud, $sqlInsertar::VALUES_MERGE);
			$sqlInsertar->prepareStatement($this->modeloAntecedentesSalud->getAdapter(), $statement);
			$statement->execute();
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
		$this->modeloAntecedentesSalud->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return AntecedentesSaludModelo
	 */
	public function buscar($id){
		return $this->modeloAntecedentesSalud->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloAntecedentesSalud->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloAntecedentesSalud->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarAntecedentesSalud(){
		$consulta = "SELECT * FROM " . $this->modeloAntecedentesSalud->getEsquema() . ". antecedentes_salud";
		return $this->modeloAntecedentesSalud->ejecutarSqlNativo($consulta);
	}
}
