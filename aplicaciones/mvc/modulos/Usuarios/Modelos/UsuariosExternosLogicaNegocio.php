<?php
 /**
 * Lógica del negocio de UsuariosExternosModelo
 *
 * Este archivo se complementa con el archivo UsuariosExternosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    UsuariosExternosLogicaNegocio
 * @package Usuarios
 * @subpackage Modelos
 */
  namespace Agrodb\Usuarios\Modelos;
  
  use Agrodb\Usuarios\Modelos\IModelo;
 
class UsuariosExternosLogicaNegocio implements IModelo 
{

	 private $modeloUsuariosExternos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloUsuariosExternos = new UsuariosExternosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new UsuariosExternosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdentificador() != null && $tablaModelo->getIdentificador() > 0) {
		return $this->modeloUsuariosExternos->actualizar($datosBd, $tablaModelo->getIdentificador());
		} else {
		unset($datosBd["identificador"]);
		return $this->modeloUsuariosExternos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloUsuariosExternos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return UsuariosExternosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloUsuariosExternos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloUsuariosExternos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloUsuariosExternos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarUsuariosExternos()
	{
	$consulta = "SELECT * FROM ".$this->modeloUsuariosExternos->getEsquema().". usuarios_externos";
		 return $this->modeloUsuariosExternos->ejecutarSqlNativo($consulta);
	}

}
