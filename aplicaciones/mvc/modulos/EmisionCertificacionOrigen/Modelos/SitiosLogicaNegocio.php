<?php
 /**
 * Lógica del negocio de SitiosModelo
 *
 * Este archivo se complementa con el archivo SitiosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    SitiosLogicaNegocio
 * @package EmisionCertificacionOrigen
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\EmisionCertificacionOrigen\Modelos\IModelo;
 
class SitiosLogicaNegocio implements IModelo 
{

	 private $modeloSitios = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloSitios = new SitiosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new SitiosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdSitio() != null && $tablaModelo->getIdSitio() > 0) {
		return $this->modeloSitios->actualizar($datosBd, $tablaModelo->getIdSitio());
		} else {
		unset($datosBd["id_sitio"]);
		return $this->modeloSitios->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloSitios->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return SitiosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloSitios->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloSitios->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloSitios->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarSitios()
	{
	$consulta = "SELECT * FROM ".$this->modeloSitios->getEsquema().". sitios";
		 return $this->modeloSitios->ejecutarSqlNativo($consulta);
	}

}
