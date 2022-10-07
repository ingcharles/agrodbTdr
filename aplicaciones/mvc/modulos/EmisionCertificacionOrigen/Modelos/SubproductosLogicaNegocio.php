<?php
 /**
 * Lógica del negocio de SubproductosModelo
 *
 * Este archivo se complementa con el archivo SubproductosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    SubproductosLogicaNegocio
 * @package EmisionCertificacionOrigen
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\EmisionCertificacionOrigen\Modelos\IModelo;
 
class SubproductosLogicaNegocio implements IModelo 
{

	 private $modeloSubproductos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloSubproductos = new SubproductosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new SubproductosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdSubproductos() != null && $tablaModelo->getIdSubproductos() > 0) {
		return $this->modeloSubproductos->actualizar($datosBd, $tablaModelo->getIdSubproductos());
		} else {
		unset($datosBd["id_subproductos"]);
		return $this->modeloSubproductos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloSubproductos->borrar($id);
	}
	public function borrarPorParametro($param, $value) {
	    $this->modeloSubproductos->borrarPorParametro($param, $value);
	}
	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return SubproductosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloSubproductos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloSubproductos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloSubproductos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarSubproductos()
	{
	$consulta = "SELECT * FROM ".$this->modeloSubproductos->getEsquema().". subproductos";
		 return $this->modeloSubproductos->ejecutarSqlNativo($consulta);
	}
	/**
	 * Columnas para guardar junto con el formulario
	 * @return string[]
	 */
	public function columnas()
	{
	    $columnas = array(
	        'id_productos',
	        'subproducto',
	        'cantidad'
	    );
	    return $columnas;
	}
}
