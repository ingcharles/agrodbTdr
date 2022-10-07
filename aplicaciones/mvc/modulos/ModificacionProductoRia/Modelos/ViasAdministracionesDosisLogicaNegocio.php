<?php
 /**
 * Lógica del negocio de ViasAdministracionesDosisModelo
 *
 * Este archivo se complementa con el archivo ViasAdministracionesDosisControlador.
 *
 * @author  AGROCALIDAD
 * @date    2022-07-21
 * @uses    ViasAdministracionesDosisLogicaNegocio
 * @package ModificacionProductoRia
 * @subpackage Modelos
 */
  namespace Agrodb\ModificacionProductoRia\Modelos;
  
  use Agrodb\ModificacionProductoRia\Modelos\IModelo;
 
class ViasAdministracionesDosisLogicaNegocio implements IModelo 
{

	 private $modeloViasAdministracionesDosis = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloViasAdministracionesDosis = new ViasAdministracionesDosisModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ViasAdministracionesDosisModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdViaAdministracionDosis() != null && $tablaModelo->getIdViaAdministracionDosis() > 0) {
		return $this->modeloViasAdministracionesDosis->actualizar($datosBd, $tablaModelo->getIdViaAdministracionDosis());
		} else {
		unset($datosBd["id_via_administracion_dosis"]);
		return $this->modeloViasAdministracionesDosis->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloViasAdministracionesDosis->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ViasAdministracionesDosisModelo
	*/
	public function buscar($id)
	{
		return $this->modeloViasAdministracionesDosis->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloViasAdministracionesDosis->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloViasAdministracionesDosis->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarViasAdministracionesDosis()
	{
	$consulta = "SELECT * FROM ".$this->modeloViasAdministracionesDosis->getEsquema().". vias_administraciones_dosis";
		 return $this->modeloViasAdministracionesDosis->ejecutarSqlNativo($consulta);
	}

}
