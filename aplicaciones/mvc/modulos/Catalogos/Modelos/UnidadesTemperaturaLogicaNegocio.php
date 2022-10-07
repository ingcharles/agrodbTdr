<?php
 /**
 * Lógica del negocio de UnidadesTemperaturaModelo
 *
 * Este archivo se complementa con el archivo UnidadesTemperaturaControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    UnidadesTemperaturaLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class UnidadesTemperaturaLogicaNegocio implements IModelo 
{

	 private $modeloUnidadesTemperatura = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloUnidadesTemperatura = new UnidadesTemperaturaModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new UnidadesTemperaturaModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdUnidadTemperatura() != null && $tablaModelo->getIdUnidadTemperatura() > 0) {
		return $this->modeloUnidadesTemperatura->actualizar($datosBd, $tablaModelo->getIdUnidadTemperatura());
		} else {
		unset($datosBd["id_unidad_temperatura"]);
		return $this->modeloUnidadesTemperatura->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloUnidadesTemperatura->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return UnidadesTemperaturaModelo
	*/
	public function buscar($id)
	{
		return $this->modeloUnidadesTemperatura->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloUnidadesTemperatura->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloUnidadesTemperatura->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarUnidadesTemperatura()
	{
	$consulta = "SELECT * FROM ".$this->modeloUnidadesTemperatura->getEsquema().". unidades_temperatura";
		 return $this->modeloUnidadesTemperatura->ejecutarSqlNativo($consulta);
	}	
	
	/**
	 * Busca un unidad de temperatura por código por idioma
	 *
	 * @return ResultSet
	 */
	public function buscarUnidadesTemperaturaPorCodigoPorIdioma($codigoUnidadTemperatura, $idioma)
	{
	    if($idioma == 'SPA'){
	        $clave = 'nombre_unidad_temperatura';
	    }else{
	        $clave = 'nombre_unidad_temperatura_ingles';
	    }
	    $where = "codigo_unidad_temperatura = '$codigoUnidadTemperatura' and estado_unidad_temperatura = 'activo'";
	    return $this->modeloUnidadesTemperatura->buscarLista($where, $clave);
	}	

}
