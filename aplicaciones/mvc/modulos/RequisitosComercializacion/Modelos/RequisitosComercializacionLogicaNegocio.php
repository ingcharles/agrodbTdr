<?php
/**
 * Lógica del negocio de RequisitosComercializacionModelo
 *
 * Este archivo se complementa con el archivo RequisitosComercializacionControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-06-06
 * @uses RequisitosComercializacionLogicaNegocio
 * @package RequisitosComercializacion
 * @subpackage Modelos
 */
namespace Agrodb\RequisitosComercializacion\Modelos;

use Agrodb\RequisitosComercializacion\Modelos\IModelo;
use Agrodb\Token\Modelos\TokenLogicaNegocio;
use Exception;

class RequisitosComercializacionLogicaNegocio implements IModelo{

	private $modeloRequisitosComercializacion = null;
	private $lNegocioToken = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloRequisitosComercializacion = new RequisitosComercializacionModelo();
		$this->lNegocioToken = new TokenLogicaNegocio();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new RequisitosComercializacionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRequisitoComercio() != null && $tablaModelo->getIdRequisitoComercio() > 0){
			return $this->modeloRequisitosComercializacion->actualizar($datosBd, $tablaModelo->getIdRequisitoComercio());
		}else{
			unset($datosBd["id_requisito_comercio"]);
			return $this->modeloRequisitosComercializacion->guardar($datosBd);
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
		$this->modeloRequisitosComercializacion->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return RequisitosComercializacionModelo
	 */
	public function buscar($id){
		return $this->modeloRequisitosComercializacion->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloRequisitosComercializacion->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloRequisitosComercializacion->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarRequisitosComercializacion(){
		$consulta = "SELECT * FROM " . $this->modeloRequisitosComercializacion->getEsquema() . ". requisitos_comercializacion";
		return $this->modeloRequisitosComercializacion->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) para obtención de producto y requisito.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerProductoPorLocalizacionNombreAreaTipoRequisito($arrayParametros) {

		$consulta = "SELECT distinct
						pr.nombre_comun,
						tp.nombre as tipo_producto,
						sp.nombre as subtipo_producto,
						ra.tipo as tipo_requisito,
						rc.id_producto
						".($arrayParametros['id_localizacion'] != '--' ? " , rc.id_localizacion " : "")."
					FROM
						g_requisitos.requisitos_comercializacion rc
						INNER JOIN g_catalogos.productos pr ON rc.id_producto = pr.id_producto
						INNER JOIN g_catalogos.subtipo_productos sp ON pr.id_subtipo_producto = sp.id_subtipo_producto
						INNER JOIN g_catalogos.tipo_productos tp ON sp.id_tipo_producto = tp.id_tipo_producto
						INNER JOIN g_requisitos.requisitos_asignados ra ON rc.id_requisito_comercio = ra.id_requisito_comercio
					WHERE
						tp.id_area = '".$arrayParametros['id_area']."'
						and ra.tipo='".$arrayParametros['tipo_requisito']."'
						".($arrayParametros['nombre_producto'] != '' ? " and quitar_caracteres_especiales_sin_espacio(rc.nombre_producto) ilike '%".$arrayParametros['nombre_producto']."%'" : "")."
						".($arrayParametros['id_localizacion'] != '--' ? " and rc.id_localizacion = ".$arrayParametros['id_localizacion']."" : "")."
						and ra.estado = 'activo'
					ORDER BY
						2, 3, 1;";

		return $this->modeloRequisitosComercializacion->ejecutarSqlNativo($consulta);
						
	}
	
	/**
	 * Ejecuta una consulta(SQL) para obtención de requisitos por localización.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerRequisitoPorProductoTipoRequisitoLocalizacion($arrayParametros) {
		
		$consulta = "SELECT
						r.id_requisito, 
						r.nombre, 
						r.detalle_impreso, 
						(case when r.detalle != '' then r.detalle else r.detalle_impreso ||' '|| '(Requisito que unicamente se muestra en el certificado impreso.)' end ) as requisito
					FROM
						g_requisitos.requisitos r
						INNER JOIN g_requisitos.requisitos_asignados ra ON r.id_requisito = ra.requisito
						INNER JOIN g_requisitos.requisitos_comercializacion rc ON rc.id_requisito_comercio = ra.id_requisito_comercio
					WHERE	
						rc.id_producto = ".$arrayParametros['id_producto']."
						and ra.tipo = '".$arrayParametros['tipo_requisito']."'
						and rc.id_localizacion=".$arrayParametros['id_localizacion']."
						and r.estado=1
						and ra.estado = 'activo'
					ORDER BY
						r.codigo;";
		
		return $this->modeloRequisitosComercializacion->ejecutarSqlNativo($consulta);
		
	}
	
	/**
	 * Ejecuta una consulta(SQL) para obtención de país por tipo de requisito.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerPaisPorProductoTipoRequisito($arrayParametros) {
		
		$consulta = "SELECT
						 distinct rc.id_localizacion, rc.nombre_pais
					FROM
						g_requisitos.requisitos r
						INNER JOIN g_requisitos.requisitos_asignados ra ON r.id_requisito = ra.requisito
						INNER JOIN g_requisitos.requisitos_comercializacion rc ON rc.id_requisito_comercio = ra.id_requisito_comercio
					WHERE
						rc.id_producto = ".$arrayParametros['id_producto']."
						and ra.tipo = '".$arrayParametros['tipo_requisito']."'
						and r.estado=1
						and ra.estado = 'activo'
					ORDER BY
						rc.nombre_pais;";
		
		return $this->modeloRequisitosComercializacion->ejecutarSqlNativo($consulta);
		
	}
	
	/**
	 * Ejecuta una consulta(SQL) para obtención producto por nombre producto, partida arancelaria y área temática.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerProductoPorTipoRequisitoAreaNombreProducto($arrayParametros) {
		
		$arrayParametros['partida_arancelaria'] = $arrayParametros['partida_arancelaria']!="" ?  "'" .$arrayParametros['partida_arancelaria']. "'"   : "NULL";
		
		$consulta = "SELECT
						 distinct rc.id_producto
					FROM
						g_requisitos.requisitos_asignados ra
						INNER JOIN g_requisitos.requisitos r ON ra.requisito = r.id_requisito
						INNER JOIN g_requisitos.requisitos_comercializacion rc ON ra.id_requisito_comercio = rc.id_requisito_comercio
						INNER JOIN g_catalogos.productos pr ON rc.id_producto = pr.id_producto
					WHERE
						ra.estado='activo'
						and r.estado = 1
						and r.tipo in ('Importación', 'Exportación', 'Tránsito')
						and quitar_caracteres_especiales_sin_espacio(pr.nombre_comun) ilike '%".$arrayParametros['nombre_producto']."%'
						and (".$arrayParametros['partida_arancelaria']." is NULL or pr.partida_arancelaria = ".$arrayParametros['partida_arancelaria'].")
						and rc.tipo='".$arrayParametros['id_area']."' 
					ORDER BY
							rc.id_producto;";
		
		$productosRequisito = $this->modeloRequisitosComercializacion->ejecutarSqlNativo($consulta);
		
		$idProductos='';
		
		if($productosRequisito->count() !=0){
			foreach ($productosRequisito as $producto){
				$idProductos.="".$producto['id_producto'].",";
			}
			$idProductos = rtrim ( $idProductos, ',' );
		} else{
			$idProductos = '0';
		}
		
		return $idProductos;
		
	}


	/**
	 * Ejecuta una consulta(SQL) para obtener el catálogo de paises de origen para tránsito internacional con requisitos comerciales.
	 *
	 * Token requerido 
	 * 
	 * @return array|ResultSet
	 */
	public function obtenerCatalogoPaisesOrigenProcedenciaTransito() {

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){
		
			$consulta = "SELECT 
				distinct rc.id_localizacion,
					rc.nombre_pais AS nombre
				FROM 
					g_catalogos.productos p, 
					g_requisitos.requisitos r, 
					g_requisitos.requisitos_comercializacion rc, 
					g_requisitos.requisitos_asignados ra
				WHERE 
					rc.id_producto = p.id_producto and 
					ra.id_requisito_comercio = rc.id_requisito_comercio and 
					ra.requisito = r.id_requisito and 
					r.id_area = 'SV' and 
					r.tipo = 'Tránsito' and 
					r.estado = 1 and 
					ra.estado = 'activo' 
				ORDER BY 
					1";
			
			try {
				$res = $this->modeloRequisitosComercializacion->ejecutarSqlNativo($consulta);
				$array['estado'] = 'exito';
				$array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";
				$array['cuerpo'] = $res->toArray();
				echo json_encode($array);
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = 'Error al obtener datos: ' . $ex;
				http_response_code(400);
				echo json_encode($array);
			}
		} else{
			echo json_encode($arrayToken);
		}
	}

	/**
	 * Ejecuta una consulta(SQL) para obtener el catálogo de productos con requisitos comerciales para tránsito internacional.
	 *
	 * Token requerido 
	 * 
	 * @return array|ResultSet
	 */
	public function obtenerProductoaRequisitosTransito() {

		$arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

		if($arrayToken['estado'] == 'exito'){

			$consulta = "SELECT 
							DISTINCT p.partida_arancelaria,
							p.nombre_comun As nombre,
							sp.nombre AS subtipo
						FROM 
							g_catalogos.tipo_productos tp, 
							g_catalogos.subtipo_productos sp, 
							g_catalogos.productos p, 
							g_requisitos.requisitos r, 
							g_requisitos.requisitos_comercializacion rc, 
							g_requisitos.requisitos_asignados ra 
						WHERE 
							p.id_subtipo_producto = sp.id_subtipo_producto and 
							sp.id_tipo_producto = tp.id_tipo_producto and 
							rc.id_producto = p.id_producto and 
							ra.id_requisito_comercio = rc.id_requisito_comercio and 
							ra.requisito = r.id_requisito and 
							r.id_area = 'SV' and 
							r.tipo = 'Tránsito' and 
							r.estado = 1 and 
							ra.estado = 'activo' 
						ORDER BY 1";
			
			try {
				$res = $this->modeloRequisitosComercializacion->ejecutarSqlNativo($consulta);
				$array['estado'] = 'exito';
				$array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";
				$array['cuerpo'] = $res->toArray();
				echo json_encode($array);
			} catch (Exception $ex) {
				$array['estado'] = 'error';
				$array['mensaje'] = 'Error al obtener datos: ' . $ex;
				http_response_code(400);
				echo json_encode($array);
			}

		} else{
			echo json_encode($arrayToken);
		}
	}

}
