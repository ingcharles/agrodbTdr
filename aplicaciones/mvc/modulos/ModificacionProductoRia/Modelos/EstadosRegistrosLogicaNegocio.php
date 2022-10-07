<?php
 /**
 * Lógica del negocio de EstadosRegistrosModelo
 *
 * Este archivo se complementa con el archivo EstadosRegistrosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    EstadosRegistrosLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\ModificacionProductoRia\Modelos\IModelo;
 
class EstadosRegistrosLogicaNegocio implements IModelo 
{

	 private $modeloEstadosRegistros = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloEstadosRegistros = new EstadosRegistrosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new EstadosRegistrosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdEstadoRegistro() != null && $tablaModelo->getIdEstadoRegistro() > 0) {
		return $this->modeloEstadosRegistros->actualizar($datosBd, $tablaModelo->getIdEstadoRegistro());
		} else {
		unset($datosBd["id_estado_registro"]);
		return $this->modeloEstadosRegistros->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloEstadosRegistros->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return EstadosRegistrosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloEstadosRegistros->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloEstadosRegistros->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloEstadosRegistros->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarEstadosRegistros()
	{
	$consulta = "SELECT * FROM ".$this->modeloEstadosRegistros->getEsquema().". estados_registros";
		 return $this->modeloEstadosRegistros->ejecutarSqlNativo($consulta);
	}

}
