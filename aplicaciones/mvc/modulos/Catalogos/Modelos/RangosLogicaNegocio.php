<?php
/**
 * Lógica del negocio de RangosModelo
 *
 * Este archivo se complementa con el archivo RangosControlador.
 *
 * @author AGROCALIDAD
 * @date    2022-02-18
 * @uses RangosLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;
use Agrodb\Core\Constantes;

class RangosLogicaNegocio implements IModelo{

	private $modeloRangos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloRangos = new RangosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new RangosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRango() != null && $tablaModelo->getIdRango() > 0){
			return $this->modeloRangos->actualizar($datosBd, $tablaModelo->getIdRango());
		}else{
			unset($datosBd["id_rango"]);
			return $this->modeloRangos->guardar($datosBd);
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
		$this->modeloRangos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return RangosModelo
	 */
	public function buscar($id){
		return $this->modeloRangos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloRangos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloRangos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarRangos(){
		$consulta = "SELECT * FROM " . $this->modeloRangos->getEsquema() . ". rangos";
		return $this->modeloRangos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Validar ingreso parametro
	 *
	 * @param array $datos
	 * @return array
	 */
	public function validarGuardarRango(Array $datos){
		
		$resultado = array();
		$procesoActualizacion = false;
		
		$verificacionNombre = $this->buscarExistenciaNombreRangoPorMetodo($datos['descripcion'], $datos['id_metodo']);
		
		if($datos['tipo_proceso'] == 'actualizar' && isset($verificacionNombre->current()->descripcion)){
			if($datos['descripcion_original'] == $verificacionNombre->current()->descripcion){
				$procesoActualizacion = true;
			}
		}else{
			if(empty($verificacionNombre->current())){
				$procesoActualizacion = true;
			}
		}
		
		if($procesoActualizacion){
			$resultado= array('validacion' => true, 'estado' => 'EXITO', 'mensaje' => Constantes::GUARDADO_CON_EXITO);
		}else{
			$resultado= array('validacion' => false, 'estado' => 'FALLO', 'mensaje' => 'El método ingresado ya se encuentra registrado sobre el parámetro seleccionado.');
		}
		
		return $resultado;
		
	}
	
	/**
	 * Verifica la existencia de un parametro bajo el mismo producto.
	 *
	 * @return array|ResultSet
	 */
	public function buscarExistenciaNombreRangoPorMetodo($nombreRango, $idMetodo){
		
		$consulta = "SELECT
						*
					FROM
						g_catalogos.rangos
					WHERE
						quitar_caracteres_especiales(descripcion)
						ILIKE quitar_caracteres_especiales('$nombreRango') and
						id_metodo = $idMetodo;";
		
		return $this->modeloRangos->ejecutarSqlNativo($consulta);
	}
}
