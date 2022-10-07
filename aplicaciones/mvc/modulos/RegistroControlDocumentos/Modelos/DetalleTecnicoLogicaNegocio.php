<?php
/**
 * Lógica del negocio de DetalleTecnicoModelo
 *
 * Este archivo se complementa con el archivo DetalleTecnicoControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-10-18
 * @uses DetalleTecnicoLogicaNegocio
 * @package RegistroControlDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroControlDocumentos\Modelos;

use Agrodb\RegistroControlDocumentos\Modelos\IModelo;

class DetalleTecnicoLogicaNegocio implements IModelo{

	private $modeloDetalleTecnico = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleTecnico = new DetalleTecnicoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleTecnicoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleTecnico() != null && $tablaModelo->getIdDetalleTecnico() > 0){
			return $this->modeloDetalleTecnico->actualizar($datosBd, $tablaModelo->getIdDetalleTecnico());
		}else{
			unset($datosBd["id_detalle_tecnico"]);
			return $this->modeloDetalleTecnico->guardar($datosBd);
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
		$this->modeloDetalleTecnico->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleTecnicoModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleTecnico->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleTecnico->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleTecnico->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleTecnico(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleTecnico->getEsquema() . ". detalle_tecnico";
		return $this->modeloDetalleTecnico->ejecutarSqlNativo($consulta);
	}
}
