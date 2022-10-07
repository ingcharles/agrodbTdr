<?php
/**
 * Lógica del negocio de DetalleDestinatarioModelo
 *
 * Este archivo se complementa con el archivo DetalleDestinatarioControlador.
 *
 * @author AGROCALIDAD
 * @date    2021-10-18
 * @uses DetalleDestinatarioLogicaNegocio
 * @package RegistroControlDocumentos
 * @subpackage Modelos
 */
namespace Agrodb\RegistroControlDocumentos\Modelos;

use Agrodb\RegistroControlDocumentos\Modelos\IModelo;

class DetalleDestinatarioLogicaNegocio implements IModelo{

	private $modeloDetalleDestinatario = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloDetalleDestinatario = new DetalleDestinatarioModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new DetalleDestinatarioModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleDestinatario() != null && $tablaModelo->getIdDetalleDestinatario() > 0){
			return $this->modeloDetalleDestinatario->actualizar($datosBd, $tablaModelo->getIdDetalleDestinatario());
		}else{
			unset($datosBd["id_detalle_destinatario"]);
			return $this->modeloDetalleDestinatario->guardar($datosBd);
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
		$this->modeloDetalleDestinatario->borrar($id);
	}

	public function borrarPorParametro($param, $value){
		$this->modeloDetalleDestinatario->borrarPorParametro($param, $value);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return DetalleDestinatarioModelo
	 */
	public function buscar($id){
		return $this->modeloDetalleDestinatario->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloDetalleDestinatario->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloDetalleDestinatario->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarDetalleDestinatario(){
		$consulta = "SELECT * FROM " . $this->modeloDetalleDestinatario->getEsquema() . ". detalle_destinatario";
		return $this->modeloDetalleDestinatario->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_registro_sgc',
			'nombre',
			'identificador',
			'id_area',
			'nombre_area');
		return $columnas;
	}
}
