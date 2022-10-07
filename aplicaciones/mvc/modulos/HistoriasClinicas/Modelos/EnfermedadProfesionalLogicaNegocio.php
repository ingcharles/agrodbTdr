<?php
/**
 * Lógica del negocio de EnfermedadProfesionalModelo
 *
 * Este archivo se complementa con el archivo EnfermedadProfesionalControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-03-16
 * @uses EnfermedadProfesionalLogicaNegocio
 * @package HistoriasClinicas
 * @subpackage Modelos
 */
namespace Agrodb\HistoriasClinicas\Modelos;

use Agrodb\HistoriasClinicas\Modelos\IModelo;

class EnfermedadProfesionalLogicaNegocio implements IModelo{

	private $modeloEnfermedadProfesional = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloEnfermedadProfesional = new EnfermedadProfesionalModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new EnfermedadProfesionalModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdEnfermedadProfesional() != null && $tablaModelo->getIdEnfermedadProfesional() > 0){
			return $this->modeloEnfermedadProfesional->actualizar($datosBd, $tablaModelo->getIdEnfermedadProfesional());
		}else{
			unset($datosBd["id_enfermedad_profesional"]);
			return $this->modeloEnfermedadProfesional->guardar($datosBd);
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
		$this->modeloEnfermedadProfesional->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return EnfermedadProfesionalModelo
	 */
	public function buscar($id){
		return $this->modeloEnfermedadProfesional->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloEnfermedadProfesional->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloEnfermedadProfesional->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarEnfermedadProfesional(){
		$consulta = "SELECT * FROM " . $this->modeloEnfermedadProfesional->getEsquema() . ". enfermedad_profesional";
		return $this->modeloEnfermedadProfesional->ejecutarSqlNativo($consulta);
	}

	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
		$columnas = array(
			'id_historia_clinica',
			'tiene_enfermedad',
			'fecha_diagnostico',
			'descripcion');
		return $columnas;
	}
}
