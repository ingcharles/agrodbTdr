<?php
/**
 * Lógica del negocio de RecomendacionesModelo
 *
 * Este archivo se complementa con el archivo RecomendacionesControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses RecomendacionesLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class RecomendacionesLogicaNegocio implements IModelo{

	private $modeloRecomendaciones = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloRecomendaciones = new RecomendacionesModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new RecomendacionesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRecomendaciones() != null && $tablaModelo->getIdRecomendaciones() > 0){
			return $this->modeloRecomendaciones->actualizar($datosBd, $tablaModelo->getIdRecomendaciones());
		}else{
			unset($datosBd["id_recomendaciones"]);
			return $this->modeloRecomendaciones->guardar($datosBd);
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
		$this->modeloRecomendaciones->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return RecomendacionesModelo
	 */
	public function buscar($id){
		return $this->modeloRecomendaciones->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloRecomendaciones->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloRecomendaciones->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarRecomendaciones(){
		$consulta = "SELECT * FROM " . $this->modeloRecomendaciones->getEsquema() . ". recomendaciones";
		return $this->modeloRecomendaciones->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_historia_clinica',
			'descripcion',
			'reubicacion_laboral');
		return $columnas;
	}
}
