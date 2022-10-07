<?php
/**
 * Lógica del negocio de SubtipoProductosModelo
 *
 * Este archivo se complementa con el archivo SubtipoProductosControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-06-06
 * @uses SubtipoProductosLogicaNegocio
 * @package RequisitosComercializacion
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;

class SubtipoProductosLogicaNegocio implements IModelo{

	private $modeloSubtipoProductos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloSubtipoProductos = new SubtipoProductosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new SubtipoProductosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdSubtipoProducto() != null && $tablaModelo->getIdSubtipoProducto() > 0){
			return $this->modeloSubtipoProductos->actualizar($datosBd, $tablaModelo->getIdSubtipoProducto());
		}else{
			unset($datosBd["id_subtipo_producto"]);
			return $this->modeloSubtipoProductos->guardar($datosBd);
		}
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloSubtipoProductos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return SubtipoProductosModelo
	 */
	public function buscar($id){
		return $this->modeloSubtipoProductos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloSubtipoProductos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloSubtipoProductos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarSubtipoProductos(){
		$consulta = "SELECT * FROM " . $this->modeloSubtipoProductos->getEsquema() . ". subtipo_productos";
		return $this->modeloSubtipoProductos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) que permite obtener un determinado subtipo de producto por su codificación.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerSubtipoProductoPorCodificacion($arrayParametros){
		
		$consulta = "SELECT
						dp.id_subtipo_producto,
						dp.nombre,
						tp.id_area,
						dp.id_tipo_producto
					FROM
						g_catalogos.subtipo_productos dp
						INNER JOIN g_catalogos.tipo_productos tp ON dp.id_tipo_producto = tp.id_tipo_producto
					WHERE
						tp.codificacion_tipo_producto = '".$arrayParametros['codificacion_subtipo']."'
						and dp.estado = 1
					order by 2;";
		
		return $this->modeloSubtipoProductos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtener la información de los subtipos
	 * de producto registrados por un operador con productos para movilización de 
	 * Sanidad Vegetal como origen.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerSubtipoProductoAreasOperadoresOrigen($arrayParametros) {
	    
	    $consulta = "SELECT
						distinct sp.id_subtipo_producto, sp.nombre
					FROM
                    	g_operadores.operadores o
                    	INNER JOIN g_operadores.sitios s ON s.identificador_operador = o.identificador
                    	INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio
                    	INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area
                    	INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
                    	INNER JOIN g_catalogos.productos p ON op.id_producto = p.id_producto
                        INNER JOIN g_catalogos.subtipo_productos sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                        INNER JOIN g_catalogos.tipo_productos tp ON sp.id_tipo_producto = tp.id_tipo_producto
                    WHERE
						a.id_area = ".$arrayParametros['id_area_origen']."
						and p.movilizacion = 'SI'
                        and tp.id_area in ('" . $arrayParametros['area'] . "')
					ORDER BY
						sp.nombre;";
	    
	    return $this->modeloSubtipoProductos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar información de Tipos de Productos por área temática.
	 *
	 * @return array|ResultSet
	 */
	public function buscarSubtipoProductoXArea($idTipoProducto)
	{
	    $consulta = "  SELECT
                        	sp.*
                        FROM
                        	g_catalogos.subtipo_productos sp
                        WHERE
                            sp.id_tipo_producto = $idTipoProducto and
                            sp.estado = 1;";
	    
	    $tipoProducto = $this->modeloSubtipoProductos->ejecutarSqlNativo($consulta);

	    return $tipoProducto;
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los subtipos de productos que tiene un operador
	 * de acuerdo a una operación por área temática.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerSubtipoProductoXOperacionAreaOperador($arrayParametros) {
	    
	    $consulta = "   SELECT
                        	distinct sp.id_subtipo_producto, sp.nombre
                        FROM
                        	g_operadores.operaciones op
                        	INNER JOIN g_catalogos.productos p ON op.id_producto = p.id_producto
                        	INNER JOIN g_catalogos.subtipo_productos sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                        	INNER JOIN g_catalogos.tipos_operacion ot ON op.id_tipo_operacion = ot.id_tipo_operacion
                        WHERE
                        	ot.codigo in (".$arrayParametros['codigo_operacion'].") and
                            sp.id_tipo_producto = ".$arrayParametros['id_tipo_producto']." and
                            op.estado = 'registrado' and
                            op.identificador_operador in (".$arrayParametros['identificador'].")
                        ORDER BY
                        	sp.nombre ASC;";
	    
	    return $this->modeloSubtipoProductos->ejecutarSqlNativo($consulta);
	    
	}
	
	/**
	 * Busca un subtipo de producto por nombre
	 *
	 * @return ResultSet
	 */
	public function buscarSubtipoProductoPorNombre($nombreSubtipoProducto)
	{
	    $where = "upper(unaccent(nombre)) = upper(unaccent('$nombreSubtipoProducto'))";
	    return $this->modeloSubtipoProductos->buscarLista($where, 'nombre');
	}
	
	/**
	 * Busca un subtipo de producto por nombre y subtipo de producto
	 *
	 * @return ResultSet
	 */
	public function buscarSubtipoProductoPorNombreYTipo($nombreSubtipoProducto, $idTipoProducto)
	{
	    $where = "upper(unaccent(nombre)) = upper(unaccent('$nombreSubtipoProducto')) and id_tipo_producto = $idTipoProducto";
	    return $this->modeloSubtipoProductos->buscarLista($where, 'nombre');
	}
	
	/**
	 * Busca un subtipo de producto por nombre y subtipo de producto
	 *
	 * @return ResultSet
	 */
	public function buscarSubtipoProductoPorNombreTipoClasificacion($nombreSubtipoProducto, $idTipoProducto, $clasificacion)
	{
	    $where = "upper(unaccent(nombre)) = upper(unaccent('$nombreSubtipoProducto')) and id_tipo_producto = $idTipoProducto and clasificacion in ('$clasificacion')";
	    return $this->modeloSubtipoProductos->buscarLista($where, 'nombre');
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Buscar información de Subtipos de Productos por área temática.
	 *
	 * @return array|ResultSet
	 */
	public function buscarSubtipoProductoPorAreaTematica($idArea)
	{
	    $consulta = "SELECT
                    	stp.*
                    FROM
                        g_catalogos.tipo_productos tp
                    INNER JOIN g_catalogos.subtipo_productos stp ON tp.id_tipo_producto = stp.id_tipo_producto
                    WHERE
                        tp.id_area = '" . $idArea . "' 
                        and tp.estado = 1
                        and stp.estado = 1
                    ORDER BY stp.nombre ASC;";
	    
	    $tipoProducto = $this->modeloSubtipoProductos->ejecutarSqlNativo($consulta);
	    
	    return $tipoProducto;
	}
	
	/**
	 * Ejecuta una consulta(SQL) que permite obtener los productos pr un idSubtipoProducto.
	 *
	 * @return array|ResultSet
	 */
	public function buscarProductosSubtipoProductosPorIdSubtipoProducto($arrayParametros){
	    
	    $consulta = "SELECT
						stp.id_subtipo_producto
                        , stp.nombre
                        , p.id_producto
                        , p.nombre_comun
                        , p.partida_arancelaria
					FROM
						g_catalogos.subtipo_productos stp
						INNER JOIN g_catalogos.productos p ON stp.id_subtipo_producto = p.id_subtipo_producto
					WHERE
						stp.id_subtipo_producto = '".$arrayParametros['id_subtipo_producto']."'
						and stp.estado = 1
					ORDER BY 1;";
	    
	    return $this->modeloSubtipoProductos->ejecutarSqlNativo($consulta);
	}
}