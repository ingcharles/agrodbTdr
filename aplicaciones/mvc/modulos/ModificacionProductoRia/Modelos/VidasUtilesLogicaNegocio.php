<?php
 /**
 * Lógica del negocio de VidasUtilesModelo
 *
 * Este archivo se complementa con el archivo VidasUtilesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    VidasUtilesLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\ModificacionProductoRia\Modelos\IModelo;
 
class VidasUtilesLogicaNegocio implements IModelo 
{

	 private $modeloVidasUtiles = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloVidasUtiles = new VidasUtilesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new VidasUtilesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdVidaUtil() != null && $tablaModelo->getIdVidaUtil() > 0) {
		return $this->modeloVidasUtiles->actualizar($datosBd, $tablaModelo->getIdVidaUtil());
		} else {
		unset($datosBd["id_vida_util"]);
		return $this->modeloVidasUtiles->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloVidasUtiles->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return VidasUtilesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloVidasUtiles->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloVidasUtiles->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloVidasUtiles->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarVidasUtiles()
	{
	$consulta = "SELECT * FROM ".$this->modeloVidasUtiles->getEsquema().". vidas_utiles";
		 return $this->modeloVidasUtiles->ejecutarSqlNativo($consulta);
	}

}
