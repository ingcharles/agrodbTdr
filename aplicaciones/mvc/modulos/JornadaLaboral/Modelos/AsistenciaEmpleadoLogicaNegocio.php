<?php
/**
 * Lógica del negocio de AsistenciaEmpleadoModelo
 *
 * Este archivo se complementa con el archivo AsistenciaEmpleadoControlador.
 *
 * @author AGROCALIDAD
 * @date    2020-06-09
 * @uses AsistenciaEmpleadoLogicaNegocio
 * @package JornadaLaboral
 * @subpackage Modelos
 */
namespace Agrodb\JornadaLaboral\Modelos;

use Agrodb\JornadaLaboral\Modelos\IModelo;

class AsistenciaEmpleadoLogicaNegocio implements IModelo{

	private $modeloAsistenciaEmpleado = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloAsistenciaEmpleado = new AsistenciaEmpleadoModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new AsistenciaEmpleadoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAsistenciaEmpleado() != null && $tablaModelo->getIdAsistenciaEmpleado() > 0){
			return $this->modeloAsistenciaEmpleado->actualizar($datosBd, $tablaModelo->getIdAsistenciaEmpleado());
		}else{
			unset($datosBd["id_asistencia_empleado"]);
			return $this->modeloAsistenciaEmpleado->guardar($datosBd);
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
		$this->modeloAsistenciaEmpleado->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return AsistenciaEmpleadoModelo
	 */
	public function buscar($id){
		return $this->modeloAsistenciaEmpleado->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloAsistenciaEmpleado->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloAsistenciaEmpleado->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarAsistenciaEmpleado(){
		$consulta = "SELECT * FROM " . $this->modeloAsistenciaEmpleado->getEsquema() . ". asistencia_empleado";
		return $this->modeloAsistenciaEmpleado->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarAsistenciaEmpleadoPivoteado($identificador, $fechaRegistro){
		
		$identificador = "''" . $identificador . "''";
		$fechaInicio = "''" . $fechaRegistro . " 00:00:00''";
		$fechaFin = "''" . $fechaRegistro . " 24:00:00''";
		
		$consulta = "SELECT
							*
					FROM crosstab(
 								'SELECT
									identificador||''¬''||to_char(fecha_registro, ''yyyy-mm-dd'') identificador_fecha,
									tipo_registro, 
									array_to_string(array_agg(to_char(fecha_registro, ''yyyy-mm-dd HH24:MI:SS'')),'' / '') fechas
								FROM
									g_uath.asistencia_empleado
								WHERE
									identificador = $identificador
									and fecha_registro between $fechaInicio and $fechaFin
								GROUP BY identificador_fecha, tipo_registro
								ORDER BY identificador_fecha, tipo_registro',
								".'$$VALUES'." ('1'), ('2'), ('3'), ('4')$$)
								AS ( identificador text, inicio_jornada text, inicio_receso text, fin_receso text, fin_jornada text)";

		return $this->modeloAsistenciaEmpleado->ejecutarSqlNativo($consulta);
	}
}
