<?php
/**
 * Lógica del negocio de DetalleExamenParaclinicosModelo
 *
 * Este archivo se complementa con el archivo DetalleExamenParaclinicosControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses DetalleExamenParaclinicosLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class DetalleExamenParaclinicosLogicaNegocio implements IModelo{

	private $modeloDetalleExamenParaclinicos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleExamenParaclinicos = new DetalleExamenParaclinicosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleExamenParaclinicosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalExaParaclinicos() != null && $tablaModelo->getIdDetalExaParaclinicos() > 0){
			return $this->modeloDetalleExamenParaclinicos->actualizar($datosBd, $tablaModelo->getIdDetalExaParaclinicos());
		}else{
			unset($datosBd["id_detal_exa_paraclinicos"]);
			return $this->modeloDetalleExamenParaclinicos->guardar($datosBd);
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
		$this->modeloDetalleExamenParaclinicos->borrar($id);
	}

	public function borrarPorParametro($param, $value){
		$this->modeloDetalleExamenParaclinicos->borrarPorParametro($param, $value);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleExamenParaclinicosModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleExamenParaclinicos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleExamenParaclinicos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleExamenParaclinicos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleExamenParaclinicos(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleExamenParaclinicos->getEsquema() . ". detalle_examen_paraclinicos";
		return $this->modeloDetalleExamenParaclinicos->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_examen_paraclinicos',
			'id_subtipo_proced_medico',
			'respuesta',
			'oido');
		return $columnas;
	}
}
