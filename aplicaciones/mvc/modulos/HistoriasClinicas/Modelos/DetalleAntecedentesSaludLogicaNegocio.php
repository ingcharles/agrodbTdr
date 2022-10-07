<?php
/**
 * Lógica del negocio de DetalleAntecedentesSaludModelo
 *
 * Este archivo se complementa con el archivo DetalleAntecedentesSaludControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses DetalleAntecedentesSaludLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class DetalleAntecedentesSaludLogicaNegocio implements IModelo{

	private $modeloDetalleAntecedentesSalud = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleAntecedentesSalud = new DetalleAntecedentesSaludModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleAntecedentesSaludModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleAntecedentesSalud() != null && $tablaModelo->getIdDetalleAntecedentesSalud() > 0){
			return $this->modeloDetalleAntecedentesSalud->actualizar($datosBd, $tablaModelo->getIdDetalleAntecedentesSalud());
		}else{
			unset($datosBd["id_detalle_antecedentes_salud"]);
			return $this->modeloDetalleAntecedentesSalud->guardar($datosBd);
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
		$this->modeloDetalleAntecedentesSalud->borrar($id);
	}

	public function borrarPorParametro($param, $value){
		$this->modeloDetalleAntecedentesSalud->borrarPorParametro($param, $value);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleAntecedentesSaludModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleAntecedentesSalud->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleAntecedentesSalud->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleAntecedentesSalud->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleAntecedentesSalud(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleAntecedentesSalud->getEsquema() . ". detalle_antecedentes_salud";
		return $this->modeloDetalleAntecedentesSalud->ejecutarSqlNativo($consulta);
	}

	public function columnas(){
		$columnas = array(
			'id_antecedentes_salud',
			'id_cie',
			'diagnostico',
			'observaciones',
			'ciclo_mestrual',
			'fecha_ultima_regla',
			'fecha_ultima_citologia',
			'resultado_citologia',
			'numero_gestaciones',
			'numero_partos',
			'numero_cesareas',
			'numero_abortos',
			'numero_hijos_vivos',
			'numero_hijos_muertos',
			'embarazo',
			'semanas_gestacion',
			'numero_ecos',
			'numero_controles_embarazo',
			'complicaciones',
			'vida_sexual_activa',
			'planificacion_familiar',
			'metodo_planificacion');
		return $columnas;
	}
}
