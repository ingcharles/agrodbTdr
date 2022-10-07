<?php
/**
 * Lógica del negocio de TecnicoModelo
 *
 * Este archivo se complementa con el archivo TecnicoControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-10-18
 * @uses TecnicoLogicaNegocio
 * @package RegistroControlDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroControlDocumentos\Modelos;

use Agrodb\RegistroControlDocumentos\Modelos\IModelo;

class TecnicoLogicaNegocio implements IModelo{

	private $modeloTecnico = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloTecnico = new TecnicoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new TecnicoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdTecnico() != null && $tablaModelo->getIdTecnico() > 0){
			return $this->modeloTecnico->actualizar($datosBd, $tablaModelo->getIdTecnico());
		}else{
			unset($datosBd["id_tecnico"]);
			return $this->modeloTecnico->guardar($datosBd);
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
		$this->modeloTecnico->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return TecnicoModelo
	 */
	public function buscar($id){
		return $this->modeloTecnico->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloTecnico->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloTecnico->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarTecnico(){
		$consulta = "SELECT * FROM " . $this->modeloTecnico->getEsquema() . ". tecnico";
		return $this->modeloTecnico->ejecutarSqlNativo($consulta);
	}
}
