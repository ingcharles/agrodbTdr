<?php
 /**
 * Lógica del negocio de TratamientosModelo
 *
 * Este archivo se complementa con el archivo TratamientosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    TratamientosLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class TratamientosLogicaNegocio implements IModelo 
{

	 private $modeloTratamientos = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloTratamientos = new TratamientosModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new TratamientosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdTratamiento() != null && $tablaModelo->getIdTratamiento() > 0) {
		return $this->modeloTratamientos->actualizar($datosBd, $tablaModelo->getIdTratamiento());
		} else {
		unset($datosBd["id_tratamiento"]);
		return $this->modeloTratamientos->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloTratamientos->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return TratamientosModelo
	*/
	public function buscar($id)
	{
		return $this->modeloTratamientos->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloTratamientos->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloTratamientos->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarTratamientos()
	{
	$consulta = "SELECT * FROM ".$this->modeloTratamientos->getEsquema().". tratamientos";
		 return $this->modeloTratamientos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Busca un tratamiento por código por idioma
	 *
	 * @return ResultSet
	 */
	public function buscarTratamientosPorCodigoPorIdioma($codigoTratamiento, $idioma)
	{
	    if($idioma == 'SPA'){
	        $clave = 'nombre_tratamiento';
	    }else{
	        $clave = 'nombre_tratamiento_ingles';
	    }
	    $where = "codigo_tratamiento = '$codigoTratamiento' and estado_tratamiento = 'activo'";
	    return $this->modeloTratamientos->buscarLista($where, $clave);
	}
	
}
