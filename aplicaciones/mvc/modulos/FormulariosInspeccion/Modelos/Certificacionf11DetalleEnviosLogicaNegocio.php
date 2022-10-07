<?php
 /**
 * Lógica del negocio de Certificacionf11DetalleEnviosModelo
 *
 * Este archivo se complementa con el archivo Certificacionf11DetalleEnviosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    Certificacionf11DetalleEnviosLogicaNegocio
 * @package FormulariosInspeccion
 * @subpackage Modelos
 */
  namespace Agrodb\FormulariosInspeccion\Modelos;
  
  use Agrodb\FormulariosInspeccion\Modelos\IModelo;
 
class Certificacionf11DetalleEnviosLogicaNegocio implements IModelo 
{

	 private $modeloCertificacionf11DetalleEnvios = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloCertificacionf11DetalleEnvios = new Certificacionf11DetalleEnviosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new Certificacionf11DetalleEnviosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getId() != null && $tablaModelo->getId() > 0) {
		return $this->modeloCertificacionf11DetalleEnvios->actualizar($datosBd, $tablaModelo->getId());
		} else {
		unset($datosBd["id"]);
		return $this->modeloCertificacionf11DetalleEnvios->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloCertificacionf11DetalleEnvios->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return Certificacionf11DetalleEnviosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloCertificacionf11DetalleEnvios->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloCertificacionf11DetalleEnvios->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloCertificacionf11DetalleEnvios->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarCertificacionf11DetalleEnvios()
	{
	$consulta = "SELECT * FROM ".$this->modeloCertificacionf11DetalleEnvios->getEsquema().". certificacionf11_detalle_envios";
		 return $this->modeloCertificacionf11DetalleEnvios->ejecutarSqlNativo($consulta);
	}

}
