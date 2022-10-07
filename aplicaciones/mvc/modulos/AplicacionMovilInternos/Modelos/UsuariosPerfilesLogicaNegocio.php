<?php
 /**
 * Lógica del negocio de UsuariosPerfilesModelo
 *
 * Este archivo se complementa con el archivo UsuariosPerfilesControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-07
 * @uses    UsuariosPerfilesLogicaNegocio
 * @package AplicacionMovilInternos
 * @subpackage Modelos
 */
  namespace Agrodb\AplicacionMovilInternos\Modelos;
  
  use Agrodb\AplicacionMovilInternos\Modelos\IModelo;
 
class UsuariosPerfilesLogicaNegocio implements IModelo 
{

	 private $modeloUsuariosPerfiles = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloUsuariosPerfiles = new UsuariosPerfilesModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new UsuariosPerfilesModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdentificador() != null && $tablaModelo->getIdentificador() > 0) {
		return $this->modeloUsuariosPerfiles->actualizar($datosBd, $tablaModelo->getIdentificador());
		} else {
		unset($datosBd["identificador"]);
		return $this->modeloUsuariosPerfiles->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloUsuariosPerfiles->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return UsuariosPerfilesModelo
	*/
	public function buscar($id)
	{
		return $this->modeloUsuariosPerfiles->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloUsuariosPerfiles->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloUsuariosPerfiles->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarUsuariosPerfiles()
	{
	$consulta = "SELECT * FROM ".$this->modeloUsuariosPerfiles->getEsquema().". usuarios_perfiles";
		 return $this->modeloUsuariosPerfiles->ejecutarSqlNativo($consulta);
	}

}
