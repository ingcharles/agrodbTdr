<?php
 /**
 * Lógica del negocio de UnidadesDuracionModelo
 *
 * Este archivo se complementa con el archivo UnidadesDuracionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    UnidadesDuracionLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class UnidadesDuracionLogicaNegocio implements IModelo 
{

	 private $modeloUnidadesDuracion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloUnidadesDuracion = new UnidadesDuracionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new UnidadesDuracionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdUnidadDuracion() != null && $tablaModelo->getIdUnidadDuracion() > 0) {
		return $this->modeloUnidadesDuracion->actualizar($datosBd, $tablaModelo->getIdUnidadDuracion());
		} else {
		unset($datosBd["id_unidad_duracion"]);
		return $this->modeloUnidadesDuracion->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloUnidadesDuracion->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return UnidadesDuracionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloUnidadesDuracion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloUnidadesDuracion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloUnidadesDuracion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarUnidadesDuracion()
	{
	$consulta = "SELECT * FROM ".$this->modeloUnidadesDuracion->getEsquema().". unidades_duracion";
		 return $this->modeloUnidadesDuracion->ejecutarSqlNativo($consulta);
	}	
	
	/**
	 * Busca un unidad de duracion por código por idioma
	 *
	 * @return ResultSet
	 */
	public function buscarUnidadesDuracionPorCodigoPorIdioma($codigoUnidadDuracion, $idioma)
	{
	    if($idioma == 'SPA'){
	        $clave = 'nombre_unidad_duracion';
	    }else{
	        $clave = 'nombre_unidad_duracion_ingles';
	    }
	    $where = "codigo_unidad_duracion = '$codigoUnidadDuracion' and estado_unidad_duracion = 'activo'";
	    return $this->modeloUnidadesDuracion->buscarLista($where, $clave);
	}	

}
