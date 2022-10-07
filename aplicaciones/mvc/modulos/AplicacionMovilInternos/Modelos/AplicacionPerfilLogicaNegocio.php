<?php
 /**
 * Lógica del negocio de AplicacionPerfilModelo
 *
 * Este archivo se complementa con el archivo AplicacionPerfilControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-07
 * @uses    AplicacionPerfilLogicaNegocio
 * @package AplicacionMovilInternos
 * @subpackage Modelos
 */
  namespace Agrodb\AplicacionMovilInternos\Modelos;
  
  use Agrodb\AplicacionMovilInternos\Modelos\IModelo;
 
class AplicacionPerfilLogicaNegocio implements IModelo 
{

	 private $modeloAplicacionPerfil = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAplicacionPerfil = new AplicacionPerfilModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new AplicacionPerfilModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdAplicacion() != null && $tablaModelo->getIdAplicacion() > 0) {
		return $this->modeloAplicacionPerfil->actualizar($datosBd, $tablaModelo->getIdAplicacion());
		} else {
		unset($datosBd["id_aplicacion"]);
		return $this->modeloAplicacionPerfil->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloAplicacionPerfil->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return AplicacionPerfilModelo
	*/
	public function buscar($id)
	{
		return $this->modeloAplicacionPerfil->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAplicacionPerfil->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAplicacionPerfil->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAplicacionPerfil()
	{
	$consulta = "SELECT * FROM ".$this->modeloAplicacionPerfil->getEsquema().". aplicacion_perfil";
		 return $this->modeloAplicacionPerfil->ejecutarSqlNativo($consulta);
	}

}
