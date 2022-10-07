<?php
/**
 * Lógica del negocio de MetodosModelo
 *
 * Este archivo se complementa con el archivo MetodosControlador.
 *
 * @author AGROCALIDAD
 * @date    2022-02-18
 * @uses MetodosLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;
use Agrodb\Core\Constantes;

class MetodosLogicaNegocio implements IModelo{

	private $modeloMetodos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloMetodos = new MetodosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new MetodosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdMetodo() != null && $tablaModelo->getIdMetodo() > 0){
			return $this->modeloMetodos->actualizar($datosBd, $tablaModelo->getIdMetodo());
		}else{
			unset($datosBd["id_metodo"]);
			return $this->modeloMetodos->guardar($datosBd);
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
		$this->modeloMetodos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return MetodosModelo
	 */
	public function buscar($id){
		return $this->modeloMetodos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloMetodos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloMetodos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarMetodos(){
		$consulta = "SELECT * FROM " . $this->modeloMetodos->getEsquema() . ". metodos";
		return $this->modeloMetodos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Validar ingreso parametro
	 *
	 * @param array $datos
	 * @return array
	 */
	public function validarGuardarMetodo(Array $datos){
		
		$resultado = array();
		$procesoActualizacion = false;
		
		$verificacionNombre = $this->buscarExistenciaNombreMetodoPorParametro($datos['descripcion'], $datos['id_parametro']);
		
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
	public function buscarExistenciaNombreMetodoPorParametro($nombreMetodo, $idParametro){
		
		$consulta = "SELECT
						*
					FROM
						g_catalogos.metodos
					WHERE
						quitar_caracteres_especiales(descripcion)
						ILIKE quitar_caracteres_especiales('$nombreMetodo') and
						id_parametro = $idParametro;";
		
		return $this->modeloMetodos->ejecutarSqlNativo($consulta);
	}
}
