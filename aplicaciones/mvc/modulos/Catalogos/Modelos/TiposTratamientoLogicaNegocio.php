<?php
 /**
 * Lógica del negocio de TiposTratamientoModelo
 *
 * Este archivo se complementa con el archivo TiposTratamientoControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    TiposTratamientoLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class TiposTratamientoLogicaNegocio implements IModelo 
{

	 private $modeloTiposTratamiento = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloTiposTratamiento = new TiposTratamientoModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new TiposTratamientoModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdTipoTratamiento() != null && $tablaModelo->getIdTipoTratamiento() > 0) {
		return $this->modeloTiposTratamiento->actualizar($datosBd, $tablaModelo->getIdTipoTratamiento());
		} else {
		unset($datosBd["id_tipo_tratamiento"]);
		return $this->modeloTiposTratamiento->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloTiposTratamiento->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return TiposTratamientoModelo
	*/
	public function buscar($id)
	{
		return $this->modeloTiposTratamiento->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloTiposTratamiento->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloTiposTratamiento->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarTiposTratamiento()
	{
	$consulta = "SELECT * FROM ".$this->modeloTiposTratamiento->getEsquema().". tipos_tratamiento";
		 return $this->modeloTiposTratamiento->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Busca un tipo de tratamiento por código por idioma
	 *
	 * @return ResultSet
	 */
	public function buscarTiposTratamientoPorCodigoPorIdioma($codigoTipoTratamiento, $idioma)
	{
	    if($idioma == 'SPA'){
	        $clave = 'nombre_tipo_tratamiento';
	    }else{
	        $clave = 'nombre_tipo_tratamiento_ingles';
	    }
	    $where = "codigo_tipo_tratamiento = '$codigoTipoTratamiento' and estado_tipo_tratamiento = 'activo'";
	    return $this->modeloTiposTratamiento->buscarLista($where, $clave);
	}

}
