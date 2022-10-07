<?php
/**
 * Lógica del negocio de AusentismoMedicoModelo
 *
 * Este archivo se complementa con el archivo AusentismoMedicoControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses AusentismoMedicoLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class AusentismoMedicoLogicaNegocio implements IModelo{

	private $modeloAusentismoMedico = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloAusentismoMedico = new AusentismoMedicoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new AusentismoMedicoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAusentismoMedico() != null && $tablaModelo->getIdAusentismoMedico() > 0){
			return $this->modeloAusentismoMedico->actualizar($datosBd, $tablaModelo->getIdAusentismoMedico());
		}else{
			unset($datosBd["id_ausentismo_medico"]);
			return $this->modeloAusentismoMedico->guardar($datosBd);
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
		$this->modeloAusentismoMedico->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return AusentismoMedicoModelo
	 */
	public function buscar($id){
		return $this->modeloAusentismoMedico->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloAusentismoMedico->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloAusentismoMedico->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarAusentismoMedico(){
		$consulta = "SELECT * FROM " . $this->modeloAusentismoMedico->getEsquema() . ". ausentismo_medico";
		return $this->modeloAusentismoMedico->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_historia_clinica',
			'ausentismo',
			'causa',
			'tiempo');
		return $columnas;
	}
}
