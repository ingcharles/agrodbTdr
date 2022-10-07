<?php
/**
 * Lógica del negocio de DetalleHistorialOcupacionalModelo
 *
 * Este archivo se complementa con el archivo DetalleHistorialOcupacionalControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses DetalleHistorialOcupacionalLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class DetalleHistorialOcupacionalLogicaNegocio implements IModelo{

	private $modeloDetalleHistorialOcupacional = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleHistorialOcupacional = new DetalleHistorialOcupacionalModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleHistorialOcupacionalModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleHistorialOcupa() != null && $tablaModelo->getIdDetalleHistorialOcupa() > 0){
			return $this->modeloDetalleHistorialOcupacional->actualizar($datosBd, $tablaModelo->getIdDetalleHistorialOcupa());
		}else{
			unset($datosBd["id_detalle_historial_ocupa"]);
			return $this->modeloDetalleHistorialOcupacional->guardar($datosBd);
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
		$this->modeloDetalleHistorialOcupacional->borrar($id);
	}

	public function borrarPorParametro($param, $value){
		$this->modeloDetalleHistorialOcupacional->borrarPorParametro($param, $value);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleHistorialOcupacionalModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleHistorialOcupacional->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleHistorialOcupacional->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleHistorialOcupacional->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleHistorialOcupacional(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleHistorialOcupacional->getEsquema() . ". detalle_historial_ocupacional";
		return $this->modeloDetalleHistorialOcupacional->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas de la tabla g_historias_clinicas.detalle_historial_ocupacional
	 *
	 * @return string
	 */
	public function columnas(){
		$columnas = array(
			'id_historia_ocupacional',
			'id_subtipo_proced_medico');
		return $columnas;
	}
}
