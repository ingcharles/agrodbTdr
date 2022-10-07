<?php
/**
 * Lógica del negocio de DetalleRevisionOrganosSistemasModelo
 *
 * Este archivo se complementa con el archivo DetalleRevisionOrganosSistemasControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses DetalleRevisionOrganosSistemasLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class DetalleRevisionOrganosSistemasLogicaNegocio implements IModelo{

	private $modeloDetalleRevisionOrganosSistemas = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleRevisionOrganosSistemas = new DetalleRevisionOrganosSistemasModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleRevisionOrganosSistemasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleRevisionOrgSist() != null && $tablaModelo->getIdDetalleRevisionOrgSist() > 0){
			return $this->modeloDetalleRevisionOrganosSistemas->actualizar($datosBd, $tablaModelo->getIdDetalleRevisionOrgSist());
		}else{
			unset($datosBd["id_detalle_revision_org_sist"]);
			return $this->modeloDetalleRevisionOrganosSistemas->guardar($datosBd);
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
		$this->modeloDetalleRevisionOrganosSistemas->borrar($id);
	}

	public function borrarPorParametro($param, $value){
		$this->modeloDetalleRevisionOrganosSistemas->borrarPorParametro($param, $value);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleRevisionOrganosSistemasModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleRevisionOrganosSistemas->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleRevisionOrganosSistemas->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleRevisionOrganosSistemas->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleRevisionOrganosSistemas(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleRevisionOrganosSistemas->getEsquema() . ". detalle_revision_organos_sistemas";
		return $this->modeloDetalleRevisionOrganosSistemas->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_revision_organos_sistemas',
			'id_subtipo_proced_medico',
			'otros');
		return $columnas;
	}
}
