<?php
 /**
 * Lógica del negocio de PerfilesModelo
 *
 * Este archivo se complementa con el archivo PerfilesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-07
 * @uses    PerfilesLogicaNegocio
 * @package AplicacionMovilInternos
 * @subpackage Modelos
 */
  namespace Agrodb\AplicacionMovilInternos\Modelos;
  
  use Agrodb\AplicacionMovilInternos\Modelos\IModelo;
 
class PerfilesLogicaNegocio implements IModelo 
{

	 private $modeloPerfiles = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloPerfiles = new PerfilesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new PerfilesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdPerfil() != null && $tablaModelo->getIdPerfil() > 0) {
		return $this->modeloPerfiles->actualizar($datosBd, $tablaModelo->getIdPerfil());
		} else {
		unset($datosBd["id_perfil"]);
		return $this->modeloPerfiles->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloPerfiles->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return PerfilesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloPerfiles->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloPerfiles->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloPerfiles->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarPerfiles()
	{
	$consulta = "SELECT * FROM ".$this->modeloPerfiles->getEsquema().". perfiles";
		 return $this->modeloPerfiles->ejecutarSqlNativo($consulta);
	}

}
