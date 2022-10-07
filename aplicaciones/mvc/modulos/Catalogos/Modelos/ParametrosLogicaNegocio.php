<?php
/**
 * Lógica del negocio de ParametrosModelo
 *
 * Este archivo se complementa con el archivo ParametrosControlador.
 *
 * @author AGROCALIDAD
 * @date    2022-02-18
 * @uses ParametrosLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;
use Agrodb\Core\Constantes;

class ParametrosLogicaNegocio implements IModelo{

	private $modeloParametros = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloParametros = new ParametrosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new ParametrosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdParametro() != null && $tablaModelo->getIdParametro() > 0){
			return $this->modeloParametros->actualizar($datosBd, $tablaModelo->getIdParametro());
		}else{
			unset($datosBd["id_parametro"]);
			return $this->modeloParametros->guardar($datosBd);
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
		$this->modeloParametros->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ParametrosModelo
	 */
	public function buscar($id){
		return $this->modeloParametros->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloParametros->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloParametros->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarParametros(){
		$consulta = "SELECT * FROM " . $this->modeloParametros->getEsquema() . ". parametros";
		return $this->modeloParametros->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Validar ingreso parametro
	 *
	 * @param array $datos
	 * @return array
	 */
	public function validarGuardarParametro(Array $datos){
		
		$resultado = array();
		$procesoActualizacion = false;
		
		$verificacionNombre = $this->buscarExistenciaNombreParametroPorProducto($datos['descripcion'], $datos['id_producto']);
		
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
			$resultado= array('validacion' => false, 'estado' => 'FALLO', 'mensaje' => 'El parámetro ingresado ya se encuentra registrado sobre el producto seleccionado.');
		}
		
		return $resultado;
		
	}
	
	/**
	 * Verifica la existencia de un parametro bajo el mismo producto.
	 *
	 * @return array|ResultSet
	 */
	public function buscarExistenciaNombreParametroPorProducto($nombreParametro, $idProducto){
		
		$consulta = "SELECT
						*
					FROM
						g_catalogos.parametros
					WHERE
						quitar_caracteres_especiales(descripcion)
						ILIKE quitar_caracteres_especiales('$nombreParametro') and
						id_producto = $idProducto;";
		
		return $this->modeloParametros->ejecutarSqlNativo($consulta);
	}
}
