<?php
/**
 * Lógica del negocio de DetalleEventosModelo
 *
 * Este archivo se complementa con el archivo DetalleEventosControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-07-24
 * @uses DetalleEventosLogicaNegocio
 * @package AplicacionMovilExternos
 * @subpackage Modelos
 */
namespace Agrodb\AplicacionMovilExternos\Modelos;

use Agrodb\AplicacionMovilExternos\Modelos\IModelo;

use Agrodb\Core\Constantes;
use Agrodb\Core\Mensajes;

class DetalleEventosLogicaNegocio implements IModelo{

	private $modeloDetalleEventos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleEventos = new DetalleEventosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleEventosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleEvento() != null && $tablaModelo->getIdDetalleEvento() > 0){
			return $this->modeloDetalleEventos->actualizar($datosBd, $tablaModelo->getIdDetalleEvento());
		}else{
			unset($datosBd["id_detalle_evento"]);
			return $this->modeloDetalleEventos->guardar($datosBd);
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
		$this->modeloDetalleEventos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleEventosModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleEventos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleEventos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleEventos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleEventos(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleEventos->getEsquema() . ". detalle_eventos";
		return $this->modeloDetalleEventos->ejecutarSqlNativo($consulta);
	}
}
