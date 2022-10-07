<?php
 /**
 * Lógica del negocio de ConcentracionesTratamientoModelo
 *
 * Este archivo se complementa con el archivo ConcentracionesTratamientoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    ConcentracionesTratamientoLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class ConcentracionesTratamientoLogicaNegocio implements IModelo 
{

	 private $modeloConcentracionesTratamiento = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloConcentracionesTratamiento = new ConcentracionesTratamientoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new ConcentracionesTratamientoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdConcentracionTratamiento() != null && $tablaModelo->getIdConcentracionTratamiento() > 0) {
		return $this->modeloConcentracionesTratamiento->actualizar($datosBd, $tablaModelo->getIdConcentracionTratamiento());
		} else {
		unset($datosBd["id_concentracion_tratamiento"]);
		return $this->modeloConcentracionesTratamiento->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloConcentracionesTratamiento->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return ConcentracionesTratamientoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloConcentracionesTratamiento->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloConcentracionesTratamiento->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloConcentracionesTratamiento->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarConcentracionesTratamiento()
	{
	$consulta = "SELECT * FROM ".$this->modeloConcentracionesTratamiento->getEsquema().". concentraciones_tratamiento";
		 return $this->modeloConcentracionesTratamiento->ejecutarSqlNativo($consulta);
	}	
	
	/**
	 * Busca un codigo de tratamiento por código por idioma
	 *
	 * @return ResultSet
	 */
	public function buscarConcentracionesTratamientoCodigoPorIdioma($codigoConcentracionTratamiento, $idioma)
	{
	    if($idioma == 'SPA'){
	        $clave = 'nombre_concentracion_tratamiento';
	    }else{
	        $clave = 'nombre_concentracion_tratamiento_ingles';
	    }
	    $where = "codigo_concentracion_tratamiento = '$codigoConcentracionTratamiento' and estado_concentracion_tratamiento = 'activo'";
	    return $this->modeloConcentracionesTratamiento->buscarLista($where, $clave);
	}	

}
