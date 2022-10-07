<?php
/**
 * Lógica del negocio de FormatoCertificadoModelo
 *
 * Este archivo se complementa con el archivo FormatoCertificadoControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses FormatoCertificadoLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class FormatoCertificadoLogicaNegocio implements IModelo{

	private $modeloFormatoCertificado = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloFormatoCertificado = new FormatoCertificadoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new FormatoCertificadoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdFormatoCertificado() != null && $tablaModelo->getIdFormatoCertificado() > 0){
			return $this->modeloFormatoCertificado->actualizar($datosBd, $tablaModelo->getIdFormatoCertificado());
		}else{
			unset($datosBd["id_formato_certificado"]);
			return $this->modeloFormatoCertificado->guardar($datosBd);
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
		$this->modeloFormatoCertificado->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return FormatoCertificadoModelo
	 */
	public function buscar($id){
		return $this->modeloFormatoCertificado->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloFormatoCertificado->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloFormatoCertificado->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarFormatoCertificado(){
		$consulta = "SELECT * FROM " . $this->modeloFormatoCertificado->getEsquema() . ". formato_certificado";
		return $this->modeloFormatoCertificado->ejecutarSqlNativo($consulta);
	}
}
