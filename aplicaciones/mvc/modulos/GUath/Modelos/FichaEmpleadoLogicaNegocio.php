<?php

/**
 * Lógica del negocio de FichaEmpleadoModelo
 *
 * Este archivo se complementa con el archivo FichaEmpleadoControlador.
 *
 * @author DATASTAR
 * @uses FichaEmpleadoLogicaNegocio
 * @package Laboratorios
 * @subpackage Modelo
 */
namespace Agrodb\GUath\Modelos;

use Agrodb\GUath\Modelos\IModelo;

class FichaEmpleadoLogicaNegocio implements IModelo{

	private $modelo = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modelo = new FichaEmpleadoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new FichaEmpleadoModelo($datos);
		if ($tablaModelo->getIdentificador() != null && $tablaModelo->getIdentificador() > 0){
			return $this->modelo->actualizar($datos, $tablaModelo->getIdentificador());
		}else{
			unset($datos["identificador"]);
			return $this->modelo->guardar($datos);
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
		$this->modelo->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return FichaEmpleadoModelo
	 */
	public function buscar($id){
		return $this->modelo->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modelo->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modelo->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarFichaEmpleado(){
		$consulta = "SELECT * FROM " . $this->modelo->getEsquema() . ". ficha_empleado";
		return $this->modelo->ejecutarConsulta($consulta);
	}

	/**
	 * Busca los datos del usuario interno/externo
	 *
	 * @param type $identificador
	 * @return type
	 */
	public function buscarDatosUsuario($identificador){
		$consulta = "SELECT * FROM g_laboratorios.f_datos_usuario_ie('$identificador');";
		return $this->modelo->ejecutarSqlNativo($consulta);
	}

	/**
	 * Busca los datos del usuario interno/externo con su respectivo contrato
	 *
	 * @param type $identificador
	 * @return type
	 */
	public function buscarDatosUsuarioContrato($identificador){
		$consulta = "SELECT 
						fe.apellido ||' '||fe.nombre as nombre,
						fe.identificador,
						dc.provincia,
						dc.nombre_puesto,
                        dc.oficina
					FROM 
						" . $this->modelo->getEsquema() . ". ficha_empleado fe
						INNER JOIN " . $this->modelo->getEsquema() . ". datos_contrato dc ON fe.identificador = dc.identificador
					WHERE
						fe.estado_empleado = 'activo' and 
						dc.estado = '1' and 
						fe.identificador = '$identificador';";

		return $this->modelo->ejecutarSqlNativo($consulta);
	}
}
