<?php
/**
 * Lógica del negocio de SolicitudesAtenderModelo
 *
 * Este archivo se complementa con el archivo SolicitudesAtenderControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-14
 * @uses SolicitudesAtenderLogicaNegocio
 * @package Vue
 * @subpackage Modelos
 */
namespace Agrodb\Vue\Modelos;

use Agrodb\Vue\Modelos\IModelo;

class SolicitudesAtenderLogicaNegocio implements IModelo{

	private $modeloSolicitudesAtender = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloSolicitudesAtender = new SolicitudesAtenderModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new SolicitudesAtenderModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0){
			return $this->modeloSolicitudesAtender->actualizar($datosBd, $tablaModelo->getId());
		}else{
			unset($datosBd["id"]);
			return $this->modeloSolicitudesAtender->guardar($datosBd);
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
		$this->modeloSolicitudesAtender->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return SolicitudesAtenderModelo
	 */
	public function buscar($id){
		return $this->modeloSolicitudesAtender->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloSolicitudesAtender->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloSolicitudesAtender->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarSolicitudesAtender(){
		$consulta = "SELECT * FROM " . $this->modeloSolicitudesAtender->getEsquema() . ". solicitudes_atender";
		return $this->modeloSolicitudesAtender->ejecutarSqlNativo($consulta);
	}
}
