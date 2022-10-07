<?php
/**
 * Lógica del negocio de DetalleRegistroSgcModelo
 *
 * Este archivo se complementa con el archivo DetalleRegistroSgcControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-10-18
 * @uses DetalleRegistroSgcLogicaNegocio
 * @package RegistroControlDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroControlDocumentos\Modelos;

use Agrodb\RegistroControlDocumentos\Modelos\IModelo;

class DetalleRegistroSgcLogicaNegocio implements IModelo{

	private $modeloDetalleRegistroSgc = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleRegistroSgc = new DetalleRegistroSgcModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleRegistroSgcModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleRegistroSgc() != null && $tablaModelo->getIdDetalleRegistroSgc() > 0){
			return $this->modeloDetalleRegistroSgc->actualizar($datosBd, $tablaModelo->getIdDetalleRegistroSgc());
		}else{
			unset($datosBd["id_detalle_registro_sgc"]);
			return $this->modeloDetalleRegistroSgc->guardar($datosBd);
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
		$this->modeloDetalleRegistroSgc->borrar($id);
	}

	public function borrarPorParametro($param, $value){
		$this->modeloDetalleRegistroSgc->borrarPorParametro($param, $value);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleRegistroSgcModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleRegistroSgc->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleRegistroSgc->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleRegistroSgc->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleRegistroSgc(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleRegistroSgc->getEsquema() . ". detalle_registro_sgc";
		return $this->modeloDetalleRegistroSgc->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_registro_sgc',
			'enlace_socializar');
		return $columnas;
	}
}
