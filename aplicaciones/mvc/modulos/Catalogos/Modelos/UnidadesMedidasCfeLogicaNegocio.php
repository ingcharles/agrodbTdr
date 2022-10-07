<?php
 /**
 * Lógica del negocio de UnidadesMedidasCfeModelo
 *
 * Este archivo se complementa con el archivo UnidadesMedidasCfeControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-09-10
 * @uses    UnidadesMedidasCfeLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class UnidadesMedidasCfeLogicaNegocio implements IModelo 
{

	 private $modeloUnidadesMedidasCfe = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloUnidadesMedidasCfe = new UnidadesMedidasCfeModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new UnidadesMedidasCfeModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdUnidadMedida() != null && $tablaModelo->getIdUnidadMedida() > 0) {
		return $this->modeloUnidadesMedidasCfe->actualizar($datosBd, $tablaModelo->getIdUnidadMedida());
		} else {
		unset($datosBd["id_unidad_medida"]);
		return $this->modeloUnidadesMedidasCfe->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloUnidadesMedidasCfe->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return UnidadesMedidasCfeModelo
	*/
	public function buscar($id)
	{
		return $this->modeloUnidadesMedidasCfe->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloUnidadesMedidasCfe->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloUnidadesMedidasCfe->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarUnidadesMedidasCfe()
	{
	$consulta = "SELECT * FROM ".$this->modeloUnidadesMedidasCfe->getEsquema().". unidades_medidas_cfe";
		 return $this->modeloUnidadesMedidasCfe->ejecutarSqlNativo($consulta);
	}

}
