<?php
/**
 * Lógica del negocio de MonedasModelo
 *
 * Este archivo se complementa con el archivo MonedasControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-06
 * @uses MonedasLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class MonedasLogicaNegocio implements IModelo{

	private $modeloMonedas = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloMonedas = new MonedasModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new MonedasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdMoneda() != null && $tablaModelo->getIdMoneda() > 0){
			return $this->modeloMonedas->actualizar($datosBd, $tablaModelo->getIdMoneda());
		}else{
			unset($datosBd["id_moneda"]);
			return $this->modeloMonedas->guardar($datosBd);
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
		$this->modeloMonedas->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return MonedasModelo
	 */
	public function buscar($id){
		return $this->modeloMonedas->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloMonedas->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloMonedas->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarMonedas(){
		$consulta = "SELECT * FROM " . $this->modeloMonedas->getEsquema() . ". monedas";
		return $this->modeloMonedas->ejecutarSqlNativo($consulta);
	}
}
