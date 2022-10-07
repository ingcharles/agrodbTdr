<?php
/**
 * Lógica del negocio de ImpresionDiagnosticaModelo
 *
 * Este archivo se complementa con el archivo ImpresionDiagnosticaControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses ImpresionDiagnosticaLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class ImpresionDiagnosticaLogicaNegocio implements IModelo{

	private $modeloImpresionDiagnostica = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloImpresionDiagnostica = new ImpresionDiagnosticaModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new ImpresionDiagnosticaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdImpresionDiagnostica() != null && $tablaModelo->getIdImpresionDiagnostica() > 0){
			return $this->modeloImpresionDiagnostica->actualizar($datosBd, $tablaModelo->getIdImpresionDiagnostica());
		}else{
			unset($datosBd["id_impresion_diagnostica"]);
			return $this->modeloImpresionDiagnostica->guardar($datosBd);
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
		$this->modeloImpresionDiagnostica->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ImpresionDiagnosticaModelo
	 */
	public function buscar($id){
		return $this->modeloImpresionDiagnostica->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloImpresionDiagnostica->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloImpresionDiagnostica->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarImpresionDiagnostica(){
		$consulta = "SELECT * FROM " . $this->modeloImpresionDiagnostica->getEsquema() . ". impresion_diagnostica";
		return $this->modeloImpresionDiagnostica->ejecutarSqlNativo($consulta);
	}
}
