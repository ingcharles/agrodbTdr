<?php
 /**
 * Lógica del negocio de UnidadesMedidasModelo
 *
 * Este archivo se complementa con el archivo UnidadesMedidasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-01-03
 * @uses    UnidadesMedidasLogicaNegocio
 * @package Catalogos
 * @subpackage Modelos
 */
  namespace Agrodb\Catalogos\Modelos;
  
  use Agrodb\Catalogos\Modelos\IModelo;
 
class UnidadesMedidasLogicaNegocio implements IModelo 
{

	 private $modeloUnidadesMedidas = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloUnidadesMedidas = new UnidadesMedidasModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new UnidadesMedidasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdUnidadMedida() != null && $tablaModelo->getIdUnidadMedida() > 0) {
		return $this->modeloUnidadesMedidas->actualizar($datosBd, $tablaModelo->getIdUnidadMedida());
		} else {
		unset($datosBd["id_unidad_medida"]);
		return $this->modeloUnidadesMedidas->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloUnidadesMedidas->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return UnidadesMedidasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloUnidadesMedidas->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloUnidadesMedidas->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloUnidadesMedidas->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarUnidadesMedidas()
	{
	$consulta = "SELECT * FROM ".$this->modeloUnidadesMedidas->getEsquema().". unidades_medidas";
		 return $this->modeloUnidadesMedidas->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Busca una unidad de medida por código
	 *
	 * @return ResultSet
	 */
	public function buscarUnidadMedidaPorCodigo($codigoUnidadMedida)
	{
	    $where = "upper(unaccent(codigo)) = upper(unaccent('$codigoUnidadMedida'))";
	    return $this->modeloUnidadesMedidas->buscarLista($where, 'codigo');
	}
	
	/**
	 * Busca una unidad de medida por código por idioma
	 *
	 * @return ResultSet
	 */
	public function buscarUnidadMedidaPorCodigoPorIdioma($codigoUnidadMedida, $idioma)
	{
	    if($idioma == 'SPA'){
	        $clave = 'nombre';	        
	    }else{
	        $clave = 'nombre_ingles';
	    }       
	    $where = "codigo = '$codigoUnidadMedida' and estado = 'activo'";
	    return $this->modeloUnidadesMedidas->buscarLista($where, $clave);
	}
}