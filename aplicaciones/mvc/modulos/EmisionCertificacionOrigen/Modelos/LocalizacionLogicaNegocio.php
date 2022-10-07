<?php
 /**
 * Lógica del negocio de LocalizacionModelo
 *
 * Este archivo se complementa con el archivo LocalizacionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    LocalizacionLogicaNegocio
 * @package EmisionCertificacionOrigen
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\EmisionCertificacionOrigen\Modelos\IModelo;
 
class LocalizacionLogicaNegocio implements IModelo 
{

	 private $modeloLocalizacion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloLocalizacion = new LocalizacionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new LocalizacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdLocalizacion() != null && $tablaModelo->getIdLocalizacion() > 0) {
		return $this->modeloLocalizacion->actualizar($datosBd, $tablaModelo->getIdLocalizacion());
		} else {
		unset($datosBd["id_localizacion"]);
		return $this->modeloLocalizacion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloLocalizacion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return LocalizacionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloLocalizacion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloLocalizacion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloLocalizacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarLocalizacion()
	{
	$consulta = "SELECT * FROM ".$this->modeloLocalizacion->getEsquema().". localizacion";
		 return $this->modeloLocalizacion->ejecutarSqlNativo($consulta);
	}

}
