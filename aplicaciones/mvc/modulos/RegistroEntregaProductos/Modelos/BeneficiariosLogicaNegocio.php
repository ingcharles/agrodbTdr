<?php
 /**
 * Lógica del negocio de BeneficiariosModelo
 *
 * Este archivo se complementa con el archivo BeneficiariosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    BeneficiariosLogicaNegocio
 * @package RegistroEntregaProductos
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroEntregaProductos\Modelos;
  
  use Agrodb\RegistroEntregaProductos\Modelos\IModelo;
 
class BeneficiariosLogicaNegocio implements IModelo 
{

	 private $modeloBeneficiarios = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloBeneficiarios = new BeneficiariosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new BeneficiariosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdBeneficiario() != null && $tablaModelo->getIdBeneficiario() > 0) {
		return $this->modeloBeneficiarios->actualizar($datosBd, $tablaModelo->getIdBeneficiario());
		} else {
		unset($datosBd["id_beneficiario"]);
		return $this->modeloBeneficiarios->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloBeneficiarios->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return BeneficiariosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloBeneficiarios->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloBeneficiarios->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloBeneficiarios->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarBeneficiarios()
	{
	$consulta = "SELECT * FROM ".$this->modeloBeneficiarios->getEsquema().". beneficiarios";
		 return $this->modeloBeneficiarios->ejecutarSqlNativo($consulta);
	}

}
