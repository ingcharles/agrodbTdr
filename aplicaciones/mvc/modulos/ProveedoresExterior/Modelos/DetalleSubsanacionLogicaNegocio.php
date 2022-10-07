<?php
/**
 * Lógica del negocio de DetalleSubsanacionModelo
 *
 * Este archivo se complementa con el archivo DetalleSubsanacionControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-07-13
 * @uses DetalleSubsanacionLogicaNegocio
 * @package ProveedoresExterior
 * @subpackage Modelos
 */
namespace Agrodb\ProveedoresExterior\Modelos;

use Agrodb\ProveedoresExterior\Modelos\IModelo;

class DetalleSubsanacionLogicaNegocio implements IModelo{

	private $modeloDetalleSubsanacion = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleSubsanacion = new DetalleSubsanacionModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleSubsanacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleSubsanacion() != null && $tablaModelo->getIdDetalleSubsanacion() > 0){
			return $this->modeloDetalleSubsanacion->actualizar($datosBd, $tablaModelo->getIdDetalleSubsanacion());
		}else{
			unset($datosBd["id_detalle_subsanacion"]);
			return $this->modeloDetalleSubsanacion->guardar($datosBd);
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
		$this->modeloDetalleSubsanacion->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleSubsanacionModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleSubsanacion->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleSubsanacion->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleSubsanacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleSubsanacion(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleSubsanacion->getEsquema() . ". detalle_subsanacion";
		return $this->modeloDetalleSubsanacion->ejecutarSqlNativo($consulta);
	}

	/**
	 * Devuelve las columnas a ser insertadas.
	 *
	 * @return String
	 */
	public function columnas(){
		$columnas = array(
			'id_subsanacion',
			'identificador_revisor',
			'fecha_subsanacion');

		return $columnas;
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function obtenerDetalleSubsanacionPorIdSubsanacion($idSubsanacion){
		$consulta = "SELECT 
                    	id_detalle_subsanacion
                    	, id_subsanacion
                    	, identificador_revisor
                    	, ABS(EXTRACT(Epoch FROM now() - fecha_subsanacion)/3600) as dias_transcurridos
                    FROM
                    	g_proveedores_exterior.detalle_subsanacion
                    WHERE 
                    	id_detalle_subsanacion = (SELECT 
                    									max (id_detalle_subsanacion)
                    								FROM
                    									g_proveedores_exterior.detalle_subsanacion
                    								WHERE 
                    									id_subsanacion = '" . $idSubsanacion . "'
                    									and fecha_subsanacion_operador is null)";

		return $this->modeloDetalleSubsanacion->ejecutarSqlNativo($consulta);
	}
}
