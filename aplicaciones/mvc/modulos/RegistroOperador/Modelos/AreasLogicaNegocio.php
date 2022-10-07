<?php
 /**
 * Lógica del negocio de AreasModelo
 *
 * Este archivo se complementa con el archivo AreasControlador.
 *
 * @author  AGROCALIDAD
 * @date    2019-09-02
 * @uses    AreasLogicaNegocio
 * @package MovilizacionVegetal
 * @subpackage Modelos
 */
  namespace Agrodb\RegistroOperador\Modelos;
  
  use Agrodb\RegistroOperador\Modelos\IModelo;
 
class AreasLogicaNegocio implements IModelo 
{

	 private $modeloAreas = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloAreas = new AreasModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new AreasModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdArea() != null && $tablaModelo->getIdArea() > 0) {
		return $this->modeloAreas->actualizar($datosBd, $tablaModelo->getIdArea());
		} else {
		unset($datosBd["id_area"]);
		return $this->modeloAreas->guardar($datosBd);
	}
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloAreas->borrar($id);
	}

	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return AreasModelo
	*/
	public function buscar($id)
	{
		return $this->modeloAreas->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloAreas->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloAreas->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarAreas()
	{
	$consulta = "SELECT * FROM ".$this->modeloAreas->getEsquema().". areas";
		 return $this->modeloAreas->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtener la información de las áreas de un operador con
	 * productos para movilización de Sanidad Vegetal como origen.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerAreasOperadorMovilizacionOrigen($arrayParametros) {
	    
	    $consulta = "SELECT
                    	distinct a.id_area, a.nombre_area, s.codigo_provincia ||''|| s.codigo||''|| a.codigo||''|| a.secuencial codigo_area
                    FROM
                    	g_operadores.areas a
                    	INNER JOIN g_operadores.sitios s ON s.id_sitio = a.id_sitio
                    	INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area
                    	INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
                    	INNER JOIN g_catalogos.productos p ON op.id_producto = p.id_producto
                        INNER JOIN g_catalogos.subtipo_productos sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                        INNER JOIN g_catalogos.tipo_productos tp ON sp.id_tipo_producto = tp.id_tipo_producto
	                    INNER JOIN g_requisitos.requisitos_comercializacion rc ON rc.id_producto = p.id_producto
		                INNER JOIN g_requisitos.requisitos_asignados ra ON ra.id_requisito_comercio = rc.id_requisito_comercio
                    WHERE
						p.movilizacion = 'SI'
                        and tp.id_area in ('" . $arrayParametros['area'] . "')
                        and a.id_sitio = " . $arrayParametros['id_sitio'] . "
                        and ra.tipo = 'Movilización'
                        and ra.estado = 'activo'
					ORDER BY
						nombre_area ASC;";
	    
	    //echo $consulta;
	    return $this->modeloAreas->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Busca un producto por nombre
	 *
	 * @return ResultSet
	 */
	public function buscarAreaPorOperadorCodigoOperacion($arrayParametros)
	{
	    $consulta = "  SELECT 
                        	a.*,
                            s.provincia
                        FROM 
                        	g_operadores.areas a 
                        	INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                        	INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area
                        	INNER JOIN g_operadores.operaciones o ON pao.id_operacion = o.id_operacion
                        	INNER JOIN g_catalogos.tipos_operacion top ON o.id_tipo_operacion = top.id_tipo_operacion
                        WHERE
                        	s.identificador_operador = '" . $arrayParametros['identificadorOperador'] . "' and
                        	s.codigo_provincia = '" . $arrayParametros['codProvincia'] . "' and
                        	s.codigo = '" . $arrayParametros['codSitio'] . "' and
                        	a.codigo = '" . $arrayParametros['codArea'] . "' and
                        	a.secuencial = '" . $arrayParametros['codSecuencial'] . "' and
                        	s.estado = 'creado' and
                        	a.estado = 'creado' and
                        	o.estado = 'registrado' and
                        	top.codigo || top.id_area in " . $arrayParametros['tipoOperacion'] . " and
                            o.id_producto = '" . $arrayParametros['idProducto'] . "';";

	    return $this->modeloAreas->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Busca las provincias en donde se tiene operaciones de un tipo especificado registradas
	 *
	 * @return array|ResultSet
	 */
	public function buscarAreasDestinoXOperacion($arrayParametros)
	{
	    $busqueda = '';
	    
	    if (isset($arrayParametros['tipoOperacion']) && ($arrayParametros['tipoOperacion'] != '')) {
	        $busqueda .= " and tp.codigo in ( " . $arrayParametros['tipoOperacion'] .") ";
	    }
	    
	    if (isset($arrayParametros['idSitio']) && ($arrayParametros['idSitio'] != '')) {
	        $busqueda .= " and s.id_sitio not in ( " . $arrayParametros['idSitio'] .") ";
	    }
	    
	    if (isset($arrayParametros['idSitioDestino']) && ($arrayParametros['idSitioDestino'] != '')) {
	        $busqueda .= " and s.id_sitio = " . $arrayParametros['idSitioDestino'] ;
	    }
	    
	    $consulta = "   SELECT
                        	distinct a.id_area,
                        	a.nombre_area
                        FROM g_operadores.operaciones o
                        	INNER JOIN g_catalogos.tipos_operacion tp on tp.id_tipo_operacion = o.id_tipo_operacion
                        	INNER JOIN g_operadores.productos_areas_operacion pao on pao.id_operacion = o.id_operacion
                        	INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
                        	INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                        WHERE
                        	o.estado='registrado' and
                            s.estado = 'creado' and
                            a.estado = 'creado' ". $busqueda .";";
	    
	    //echo $consulta;
	    return $this->modeloAreas->ejecutarSqlNativo($consulta);
	}
	
	public function buscarAreasXOperadorOperacion($arrayParametros)
	{
	    $consulta = "select
                            o.id_operacion,
                            o.id_tipo_operacion,
                            o.id_operador_tipo_operacion,
							a.id_area,
							a.nombre_area as area,
							a.tipo_area,
							a.superficie_utilizada,
                            ss.identificador_operador||'.'||ss.codigo_provincia || ss.codigo || a.codigo||a.secuencial as codificacion_area
						from
							g_operadores.operaciones o,
							g_operadores.productos_areas_operacion pao,
							g_operadores.areas a,
							g_catalogos.tipos_operacion t,
							g_operadores.sitios ss
						where
							o.identificador_operador = '" . $arrayParametros['identificadorOperador'] . "' and
							o.id_operacion = " . $arrayParametros['idOperacion'] . " and
							o.id_operacion = pao.id_operacion and
							pao.id_area = a.id_area and
							o.id_operacion = pao.id_operacion and
							o.id_tipo_operacion = t.id_tipo_operacion and
							a.id_sitio = ss.id_sitio
						order by
							o.id_producto;";
	    
	    //echo $consulta;
	    return $this->modeloAreas->ejecutarSqlNativo($consulta);
	}
}