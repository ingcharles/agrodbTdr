<?php
 /**
 * Lógica del negocio de DetalleCantonProvinciaModelo
 *
 * Este archivo se complementa con el archivo DetalleCantonProvinciaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    DetalleCantonProvinciaLogicaNegocio
 * @package CentrosFaenamiento
 * @subpackage Modelos
 */
  namespace Agrodb\CentrosFaenamiento\Modelos;
  
  use Agrodb\CentrosFaenamiento\Modelos\IModelo;
 
class DetalleCantonProvinciaLogicaNegocio implements IModelo 
{

	 private $modeloDetalleCantonProvincia = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloDetalleCantonProvincia = new DetalleCantonProvinciaModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new DetalleCantonProvinciaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdDetalleCantonProvincia() != null && $tablaModelo->getIdDetalleCantonProvincia() > 0) {
		return $this->modeloDetalleCantonProvincia->actualizar($datosBd, $tablaModelo->getIdDetalleCantonProvincia());
		} else {
		unset($datosBd["id_detalle_canton_provincia"]);
		return $this->modeloDetalleCantonProvincia->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloDetalleCantonProvincia->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return DetalleCantonProvinciaModelo
	*/
	public function buscar($id)
	{
		return $this->modeloDetalleCantonProvincia->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloDetalleCantonProvincia->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloDetalleCantonProvincia->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarDetalleCantonProvincia()
	{
	$consulta = "SELECT * FROM ".$this->modeloDetalleCantonProvincia->getEsquema().". detalle_canton_provincia";
		 return $this->modeloDetalleCantonProvincia->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 *
	 * @return string[]
	 */
	public function columnas(){
	    $columnas = array(
	        'id_centro_faenamiento',
	        'id_localizacion');
	    return $columnas;
	}
}
