<?php
/**
 * Lógica del negocio de OperadoresModelo
 *
 * Este archivo se complementa con el archivo OperadoresControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-06-06
 * @uses OperadoresLogicaNegocio
 * @package RegistroOperador
 * @subpackage Modelos
 */
namespace Agrodb\RegistroOperador\Modelos;

use Agrodb\RegistroOperador\Modelos\IModelo;

class OperadoresLogicaNegocio implements IModelo{

	private $modeloOperadores = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloOperadores = new OperadoresModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new OperadoresModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdentificador() != null && $tablaModelo->getIdentificador() > 0){
			return $this->modeloOperadores->actualizar($datosBd, $tablaModelo->getIdentificador());
		}else{
			unset($datosBd["identificador"]);
			return $this->modeloOperadores->guardar($datosBd);
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
		$this->modeloOperadores->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return OperadoresModelo
	 */
	public function buscar($id){
		return $this->modeloOperadores->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloOperadores->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloOperadores->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarOperadores(){
		$consulta = "SELECT * FROM " . $this->modeloOperadores->getEsquema() . ". operadores";
		return $this->modeloOperadores->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtrener los operadores registrados.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerOperadoresPorRazonSocialProvincia($arrayParametros){
		$consulta = "SELECT
						distinct o.identificador, o.razon_social as razon
					FROM
						g_operadores.operadores o
						INNER JOIN g_operadores.sitios s ON s.identificador_operador = o.identificador
					WHERE
						quitar_caracteres_especiales_sin_espacio(o.razon_social) ilike '%" . $arrayParametros['razon_social'] . "%'
						" . ($arrayParametros['nombre_provincia'] != '--' ? " and s.provincia ilike '%" . $arrayParametros['nombre_provincia'] . "%'" : "") . "
					ORDER BY
						razon;";

		return $this->modeloOperadores->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtener la información de un operador con
	 * productos para movilización de Sanidad Vegetal como origen.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerOperadorSitioMovilizacionOrigen($arrayParametros){
		$consulta = "SELECT
						distinct o.identificador, o.razon_social as razon, s.id_sitio, s.nombre_lugar as sitio, s.provincia, s.codigo_provincia, s.codigo
					FROM
                    	g_operadores.operadores o
                    	INNER JOIN g_operadores.sitios s ON s.identificador_operador = o.identificador
                    	INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio
                    	INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_area = a.id_area
                    	INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
                    	INNER JOIN g_catalogos.productos p ON op.id_producto = p.id_producto
                        INNER JOIN g_catalogos.subtipo_productos sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                        INNER JOIN g_catalogos.tipo_productos tp ON sp.id_tipo_producto = tp.id_tipo_producto
                        INNER JOIN g_requisitos.requisitos_comercializacion rc ON rc.id_producto = p.id_producto
	                    INNER JOIN g_requisitos.requisitos_asignados ra ON ra.id_requisito_comercio = rc.id_requisito_comercio
                    WHERE
						s.provincia ilike '%" . $arrayParametros['nombre_provincia'] . "%'
						" . ($arrayParametros['identificador'] != '' ? " and o.identificador ilike '" . $arrayParametros['identificador'] . "%'" : "") . "
                        " . ($arrayParametros['razon_social'] != '' ? " and o.razon_social ilike '%" . $arrayParametros['razon_social'] . "%'" : "") . "
                        and p.movilizacion = 'SI' 
                        and tp.id_area in ('" . $arrayParametros['area'] . "')
                        and ra.tipo = 'Movilización'
                        and ra.estado = 'activo'
					ORDER BY
						razon, sitio;";

		// echo $consulta;
		return $this->modeloOperadores->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtener la información de un operador con
	 * productos para movilización de Sanidad Vegetal como destino.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerOperadorSitioMovilizacionDestino($arrayParametros){
		$consulta = "SELECT
						distinct o.identificador, o.razon_social as razon, s.id_sitio, s.nombre_lugar as sitio, s.provincia, s.codigo_provincia, s.codigo
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
						s.provincia ilike '%" . $arrayParametros['nombre_provincia'] . "%'
						" . ($arrayParametros['identificador'] != '' ? " and o.identificador ilike '" . $arrayParametros['identificador'] . "%'" : "") . "
                        " . ($arrayParametros['razon_social'] != '' ? " and o.razon_social ilike '%" . $arrayParametros['razon_social'] . "%'" : "") . "
                        and tp.id_area in ('" . $arrayParametros['area'] . "')
                        " . ($arrayParametros['id_sitio_origen'] != '' ? " and s.id_sitio not in (" . $arrayParametros['id_sitio_origen'] . ")" : "") . "
					ORDER BY
						razon, sitio;";

		return $this->modeloOperadores->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtener la información de la razón social de un operador.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerDatosOperadores($arrayParametros) {
		
		$consulta ="SELECT
						o.identificador
                        , o.nombre_representante || ' ' || o.apellido_representante as representante_legal
						, case when o.razon_social = '' then o.nombre_representante ||' '|| o.apellido_representante else o.razon_social end nombre_operador
					    , o.direccion
                    	, COALESCE (o.telefono_uno, o.telefono_dos) as telefono
                    	, COALESCE (o.celular_uno, o.celular_dos) as celular
                    	, o.correo
                    	, o.provincia
                        , l.id_localizacion as id_provincia
                    FROM
						g_operadores.operadores o
						LEFT JOIN g_catalogos.localizacion l ON UPPER(o.provincia) = UPPER(l.nombre) and l.categoria = 1
					WHERE
						o.identificador = '" . $arrayParametros['identificador'] . "'";
		
		return $this->modeloOperadores->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtener los operadores registrados con .
	 *
	 * @return array|ResultSet
	 */
	public function obtenerRazonSocialOperadoresXOperacion($identificador) {
	    
	    $consulta = "   SELECT
                        	distinct(o.razon_social)
                        FROM
                        	g_operadores.operadores o
                        	INNER JOIN g_operadores.operaciones op ON o.identificador = op.identificador_operador
                        	INNER JOIN g_catalogos.tipos_operacion tp ON op.id_tipo_operacion = tp.id_tipo_operacion
                        WHERE
                        	tp.codigo in ('PRO', 'PRA') and
                        	tp.id_area in ('SV', 'SA', 'AI') and
                            op.estado = 'registrado' and
                        	o.identificador = '$identificador';";
	    
	    return $this->modeloOperadores->ejecutarSqlNativo($consulta);
	    
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los datos del operador
	 * de acuerdo al identificador del operador
	 *
	 * @return array|ResultSet
	 */
	public function obtenerInformacionOperadorPorIdentificador($identificador)
	{
	    $consulta = "SELECT
						identificador as identificador_operador,
						case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end nombre_operador,
                        direccion as direccion_operador
    				FROM
    					g_operadores.operadores
                    WHERE 
                        identificador = '" . $identificador . "'
					ORDER BY 2;";
	    
	    return $this->modeloOperadores->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtener los operadores registrados una operacion especifica .
	 *
	 * @return array|ResultSet
	 */
	public function obtenerDatosOperadorPorCodigoOperacionPorEstado($identificador, $codigoOperacion, $estado) {
	    
	    $consulta = "SELECT
                    	DISTINCT o.identificador
                    	, CASE WHEN o.razon_social = '' THEN o.nombre_representante ||' '|| o.apellido_representante ELSE o.razon_social END as razon_social
                    	, o.nombre_representante || ' ' || o.apellido_representante as representante_legal
                    	, o.direccion
                    	, COALESCE (o.telefono_uno, o.telefono_dos) as telefono
                    	, COALESCE (o.celular_uno, o.celular_dos) as celular
                    	, o.correo
                    	, o.provincia
                    	, l.id_localizacion as id_provincia
                    FROM
                    	g_operadores.operadores o
                    	INNER JOIN g_operadores.operaciones op ON o.identificador = op.identificador_operador
                    	INNER JOIN g_catalogos.tipos_operacion tp ON op.id_tipo_operacion = tp.id_tipo_operacion
                    	INNER JOIN g_catalogos.localizacion l ON UPPER(o.provincia) = UPPER(l.nombre)
                    WHERE                    	
                    	tp.id_area || tp.codigo in " . $codigoOperacion . "
                    	and op.estado = '$estado'
                    	and o.identificador = '$identificador'
                        and l.categoria = 1;";
	    
	    return $this->modeloOperadores->ejecutarSqlNativo($consulta);  
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtener la información de un operador con operaciones específicas.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerDatosOperadorXOperacionDossierPecuario($identificador) {
	    
	    $consulta = "   SELECT
                        	o.*
                        FROM
                        	g_operadores.operadores o
                        	INNER JOIN g_operadores.operaciones op ON o.identificador = op.identificador_operador
                        	INNER JOIN g_catalogos.tipos_operacion tp ON op.id_tipo_operacion = tp.id_tipo_operacion
                        WHERE
                        	tp.codigo in ('FRA', 'FOR', 'DIS') and
                        	tp.id_area in ('IAV') and
                            op.estado = 'registrado' and
                        	o.identificador = '$identificador';";
	    
	    return $this->modeloOperadores->ejecutarSqlNativo($consulta);
	    
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtener la información de del sitio de un área.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerDatosSitioPorIdArea($idArea) {
	    
	    $consulta = "SELECT
                        	s.id_sitio, s.nombre_lugar, s.provincia
                        FROM
                        	g_operadores.sitios s
                        	INNER JOIN g_operadores.areas a ON a.id_sitio = s.id_sitio
                        WHERE
                        	a.id_Area = " . $idArea . ";";
	    
	    return $this->modeloOperadores->ejecutarSqlNativo($consulta);
	    
	}	
}