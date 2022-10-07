<?php
 /**
 * Lógica del negocio de ModeloAdministrativoModelo
 *
 * Este archivo se complementa con el archivo ModeloAdministrativoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2021-03-17
 * @uses    ModeloAdministrativoLogicaNegocio
 * @package ProcesosAdministrativosJuridico
 * @subpackage Modelos
 */
  namespace Agrodb\ProcesosAdministrativosJuridico\Modelos;
  
  use Agrodb\ProcesosAdministrativosJuridico\Modelos\IModelo;
 
class ModeloAdministrativoLogicaNegocio implements IModelo 
{

	 private $modeloModeloAdministrativo = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloModeloAdministrativo = new ModeloAdministrativoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ModeloAdministrativoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdModeloAdministrativo() != null && $tablaModelo->getIdModeloAdministrativo() > 0) {
		return $this->modeloModeloAdministrativo->actualizar($datosBd, $tablaModelo->getIdModeloAdministrativo());
		} else {
		unset($datosBd["id_modelo_administrativo"]);
		return $this->modeloModeloAdministrativo->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloModeloAdministrativo->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ModeloAdministrativoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloModeloAdministrativo->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloModeloAdministrativo->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloModeloAdministrativo->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarModeloAdministrativo()
	{
	$consulta = "SELECT * FROM ".$this->modeloModeloAdministrativo->getEsquema().". modelo_administrativo";
		 return $this->modeloModeloAdministrativo->ejecutarSqlNativo($consulta);
	}

}
