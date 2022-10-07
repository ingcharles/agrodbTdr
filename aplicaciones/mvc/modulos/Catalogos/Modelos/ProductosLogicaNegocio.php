<?php
/**
 * Lógica del negocio de ProductosModelo
 *
 * Este archivo se complementa con el archivo ProductosControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-06-06
 * @uses ProductosLogicaNegocio
 * @package RequisitosComercializacion
 * @subpackage Modelos
 */
namespace Agrodb\Catalogos\Modelos;

use Agrodb\Catalogos\Modelos\IModelo;
use Agrodb\Core\Constantes;

class ProductosLogicaNegocio implements IModelo{

	private $modeloProductos = null;

	/**
	 * Constructor
	 *
	 * @retorna void
	 */
	public function __construct(){
		$this->modeloProductos = new ProductosModelo();
	}

	/**
	 * Guarda el registro actual
	 *
	 * @param array $datos
	 * @return int
	 */
	public function guardar(Array $datos){
		$tablaModelo = new ProductosModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdProducto() != null && $tablaModelo->getIdProducto() > 0){
			return $this->modeloProductos->actualizar($datosBd, $tablaModelo->getIdProducto());	
		}else{
			unset($datosBd["id_producto"]);
			return $this->modeloProductos->guardar($datosBd);
		}
	}
	
	/**
	 * Validar ingreso producto
	 *
	 * @param array $datos
	 * @return array
	 */
	public function validarGuardarProducto(Array $datos){
		
		$resultado = array();
		$procesoActualizacion = false;
		
		$verificacionNombre = $this->buscarExistenciaNombreProductoPorSubtipoProducto($datos['nombre_comun'], $datos['id_subtipo_producto']);
		
		if($datos['tipo_proceso'] == 'actualizar' && isset($verificacionNombre->current()->nombre_comun)){
			if($datos['nombre_comun_original'] == $verificacionNombre->current()->nombre_comun){
				$procesoActualizacion = true;
			}
		}else{
			if(empty($verificacionNombre->current())){
				$procesoActualizacion = true;
			}
		}
		
		if ($procesoActualizacion) {
			if ($datos['partida_arancelaria'] != '') {
				$partidaArancelaria = $datos['partida_arancelaria'];

				if (isset($datos['id_producto'])) {
					if ($datos['partida_arancelaria_original'] != $partidaArancelaria) {
						$datos['codigo_producto'] = $this->generarCodigoProductoPartida($partidaArancelaria);
					}
				} else {
					$datos['codigo_producto'] = $this->generarCodigoProductoPartida($partidaArancelaria);
				}
			} else {
				$datos['codigo_producto'] = '';
			}

			$resultado= array('validacion' => true, 'estado' => 'EXITO', 'mensaje' => Constantes::GUARDADO_CON_EXITO, 'codigo' => $datos['codigo_producto']);
		}else{
			$resultado= array('validacion' => false, 'estado' => 'FALLO', 'mensaje' => 'El producto seleccionado ya existe dentro de esta clasificación, por favor verificar en el listado.');
		}

		return $resultado;		
	}

	/**
	 * Borra el registro actual
	 *
	 * @param
	 *        	string Where|array $where
	 * @return int
	 */
	public function borrar($id){
		$this->modeloProductos->borrar($id);
	}

	/**
	 *
	 * Buscar un registro de con la clave primaria
	 *
	 * @param int $id
	 * @return ProductosModelo
	 */
	public function buscar($id){
		return $this->modeloProductos->buscar($id);
	}

	/**
	 * Busca todos los registros
	 *
	 * @return array|ResultSet
	 */
	public function buscarTodo(){
		return $this->modeloProductos->buscarTodo();
	}

	/**
	 * Busca una lista de acuerdo a los parámetros <params> enviados.
	 *
	 * @return array|ResultSet
	 */
	public function buscarLista($where = null, $order = null, $count = null, $offset = null){
		return $this->modeloProductos->buscarLista($where, $order, $count, $offset);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarProductos(){
		$consulta = "SELECT * FROM " . $this->modeloProductos->getEsquema() . ". productos";
		return $this->modeloProductos->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) que permite obtener un productos por su un determiando subtipo de producto.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerProductoPorSubtipoProducto($arrayParametros){
		
		$consulta = "SELECT
						id_producto, nombre_comun
					FROM
						g_catalogos.productos p
						INNER JOIN g_catalogos.subtipo_productos stp ON p.id_subtipo_producto = stp.id_subtipo_producto
					WHERE
						p.id_subtipo_producto = '" . $arrayParametros['id_subtipo_producto'] . "'
						and p.estado = 1
					order by 2;";

		return $this->modeloProductos->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) que permite obtener los datos generales de un producto.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerDatosProducto($arrayParametros){
		
		$consulta = "SELECT
						tp.nombre as tipo,
						stp.nombre as subtipo,
						p.id_producto,
						p.nombre_comun as producto,
						p.nombre_cientifico as cientifico,
						p.partida_arancelaria,
						p.codigo_producto,
						p.unidad_medida,
						tp.id_area
					FROM
						g_catalogos.productos p
						INNER JOIN g_catalogos.subtipo_productos stp ON p.id_subtipo_producto = stp.id_subtipo_producto
						INNER JOIN g_catalogos.tipo_productos tp ON stp.id_tipo_producto = tp.id_tipo_producto
					WHERE
						p.id_producto = '" . $arrayParametros['id_producto'] . "'
						and p.estado = 1
					ORDER BY 
						p.nombre_comun;";

		return $this->modeloProductos->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) que permite obtener los datos generales de un producto por área, nombre producto, partida arancelaria.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerDatosProductoPorAreaNombreProductoPartidaArancelaria($arrayParametros){
		
		$arrayParametros['partida_arancelaria'] = $arrayParametros['partida_arancelaria'] != "" ? "'" . $arrayParametros['partida_arancelaria'] . "'" : "NULL";

		$consulta = "SELECT
						tp.nombre as tipo_producto,
						stp.nombre as subtipo_producto,
						p.id_producto,
						p.nombre_comun, 
						p.nombre_cientifico, 
						p.partida_arancelaria,
						p.codigo_producto,
						p.unidad_medida
					FROM
						g_catalogos.productos p
						INNER JOIN g_catalogos.subtipo_productos stp ON p.id_subtipo_producto = stp.id_subtipo_producto
						INNER JOIN g_catalogos.tipo_productos tp ON stp.id_tipo_producto = tp.id_tipo_producto
					WHERE
						p.estado = 1
						and p.id_producto not in (" . $arrayParametros['producto_excluido'] . ")
						and  quitar_caracteres_especiales_sin_espacio(p.nombre_comun) ilike '%" . $arrayParametros['nombre_producto'] . "%'
						and (" . $arrayParametros['partida_arancelaria'] . " is NULL or p.partida_arancelaria = " . $arrayParametros['partida_arancelaria'] . ")
						and tp.id_area='" . $arrayParametros['id_area'] . "'
					ORDER BY
						p.nombre_comun;";

		return $this->modeloProductos->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) que permite obtener los datos generales de un producto por área, nombre producto, partida arancelaria.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerDatosEspecificosProductoPorIdProductoArea($arrayParametros){
		
		$busqueda = '';
		if ($arrayParametros['id_area'] == 'IAV'){
			$busqueda = " limit 5";
		}

		$consulta = "SELECT 
						pin.numero_registro,
						pin.id_operador,
						o.razon_social,
						to_char(pin.fecha_registro,'dd/mm/yyyy')::date fecha,

						array_to_string(ARRAY(SELECT
								nombre ||' - '||pais_origen
							FROM
								g_catalogos.fabricante_formulador
							WHERE
								id_producto =  p.id_producto 
							ORDER BY
								nombre
						) ,', ') as formulador,
						
						array_to_string(ARRAY(SELECT 
								u.nombre_uso ||' aplicado a '||pr.nombre_comun nombre_producto_inocuidad
							FROM
								g_catalogos.producto_inocuidad_uso piu,
								g_catalogos.productos pr,
								g_catalogos.usos u
							WHERE
								piu.id_uso = u.id_uso and
								piu.id_producto = p.id_producto and
								pr.id_producto=piu.id_aplicacion_producto
							ORDER BY
								u.nombre_uso
						) ,', ')as usos,

						array_to_string(ARRAY(SELECT 
								presentacion ||' '|| (case when unidad_medida is not null then unidad_medida else '' end)
							FROM
								g_catalogos.codigos_inocuidad
							WHERE
								id_producto =  p.id_producto 
						) ,', ') as presentacion,
						
						array_to_string(ARRAY(SELECT
							   ingrediente_activo ||' '|| concentracion ||' '|| (case when unidad_medida is not null then unidad_medida else '' end)
								
							FROM
							   g_catalogos.composicion_inocuidad 
							WHERE
								id_producto =  p.id_producto 
							ORDER BY
									ingrediente_activo " . $busqueda . "
						) ,' + ') as composicion,
						

						CASE WHEN p.estado=1 THEN 'Vigente'
						WHEN p.estado=2 THEN 'Suspendido'
						WHEN p.estado=3 THEN 'Caducado'
						END as estado
					FROM g_catalogos.productos as p
						FULL OUTER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto
						FULL OUTER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
						FULL OUTER JOIN g_catalogos.productos_inocuidad as pin ON pin.id_producto = p.id_producto
						FULL OUTER JOIN g_operadores.operadores o ON o.identificador = pin.id_operador
					WHERE
						tp.id_area = '" . $arrayParametros['id_area'] . "' and p.id_producto = '" . $arrayParametros['id_producto'] . "';";

		return $this->modeloProductos->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada, para obtener la información de los producto
	 * registrados por un operador con productos para movilización de
	 * Sanidad Vegetal como origen.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerProductoAreasOperadoresOrigen($arrayParametros){
		$consulta = "SELECT
						distinct p.id_producto, p.nombre_comun, p.id_subtipo_producto
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
						a.id_area = " . $arrayParametros['id_area_origen'] . "
						and p.movilizacion = 'SI'
                        and tp.id_area in ('" . $arrayParametros['area'] . "')
                        and p.id_subtipo_producto = " . $arrayParametros['id_subtipo_producto'] . "
					ORDER BY
                        p.nombre_comun ASC";

		return $this->modeloProductos->ejecutarSqlNativo($consulta);
	}

	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los productos que tiene un operador
	 * de acuerdo a una operación por área temática.
	 *
	 * @return array|ResultSet
	 */
	public function obtenerProductoXOperacionAreaOperador($arrayParametros){
		$consulta = "   SELECT
                        	distinct p.id_producto, p.nombre_comun
                        FROM
                        	g_operadores.operaciones op
                        	INNER JOIN g_catalogos.productos p ON op.id_producto = p.id_producto
                        	INNER JOIN g_catalogos.tipos_operacion ot ON op.id_tipo_operacion = ot.id_tipo_operacion
                        WHERE
                        	ot.codigo in (" . $arrayParametros['codigo_operacion'] . ") and
                            p.id_subtipo_producto = " . $arrayParametros['id_subtipo_producto'] . " and
                            op.estado = 'registrado' and
                            op.identificador_operador in (" . $arrayParametros['identificador'] . ")
                        ORDER BY
                        	p.nombre_comun ASC;";

		return $this->modeloProductos->ejecutarSqlNativo($consulta);
	}

	/**
	 * Busca un producto por nombre
	 *
	 * @return ResultSet
	 */
	public function buscarProductoPorNombre($nombreProducto){
		$where = "upper(unaccent(nombre_comun)) = upper(unaccent('$nombreProducto'))";
		return $this->modeloProductos->buscarLista($where, 'nombre');
	}

	/**
	 * Busca un producto por nombre y subtipo de producto
	 *
	 * @return ResultSet
	 */
	public function buscarProductoPorNombreYSubtipo($nombreProducto, $idSubtipoProducto){
		$where = "upper(unaccent(nombre_comun)) = upper(unaccent('$nombreProducto')) and id_subtipo_producto = $idSubtipoProducto";
		return $this->modeloProductos->buscarLista($where, 'nombre_comun');
	}

	/**
	 * Busca un producto por nombre y subtipo de producto
	 *
	 * @return ResultSet
	 */
	public function buscarProductoPorNombreSubtipoClasificacion($nombreProducto, $idSubtipoProducto, $clasificacion){
		$where = "upper(unaccent(nombre_comun)) = upper(unaccent('$nombreProducto')) and id_subtipo_producto = $idSubtipoProducto and clasificacion in $clasificacion";
		return $this->modeloProductos->buscarLista($where, 'nombre_comun');
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 * Busca un producto por nombre en las solicitudes de Dossier Pecuario activas
	 * y en los productos registrados en el módulo Registro de Productos RIA (catalogo)
	 *
	 * @return array|ResultSet
	 */
	public function buscarProductoRegistroProductoRIA($nombreProducto)
	{
	    $consulta = "   SELECT
                        	p.nombre_comun as nombre_producto
                        FROM
                        	g_catalogos.productos p
                            INNER JOIN g_catalogos.subtipo_productos sp ON p.id_subtipo_producto = p.id_subtipo_producto
                        	INNER JOIN g_catalogos.tipo_productos tp ON tp.id_tipo_producto = sp.id_tipo_producto
                        WHERE
                        	quitar_caracteres_especiales(upper(trim(p.nombre_comun))) ilike quitar_caracteres_especiales(upper('$nombreProducto')) and
                            p.estado not in ('9') and
                            tp.codificacion_tipo_producto = 'TIPO_VETERINARIO'; ";
	    
	    //echo $consulta;
	    
	    return $this->modeloProductos->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Genera el código de producto por partida arancelaria
	 *
	 * @return array|ResultSet
	 */
	public function generarCodigoProductoPartida($partida)
	{
	    $consulta = "  SELECT 
					       COALESCE(MAX(CAST(codigo_producto as  numeric(5))),0)+1 as codigo 
					   FROM 
						      g_catalogos.productos 
    				   WHERE 
    						  partida_arancelaria = '$partida';";
	    
	    $codigo = $this->modeloProductos->ejecutarSqlNativo($consulta);
	    
	    $num = $codigo->current()->codigo;
	    
	    $codProducto = str_pad($num, 4, "0", STR_PAD_LEFT);
	    
	    return $codProducto;
	}
	
	/**
	 * Generar un combo de productos para proceso de rectificacion de importaciones VUE
	 *
	 * @return ResultSet
	 */
	public function comboProductoImportacion($idProducto, $partidaArancelaria){
		
		$tipoSelectProducto= '';
		
		$producto = $this->buscar($idProducto);
		
		if($partidaArancelaria == $producto->getPartidaArancelaria()){
			$tipoSelectProducto = 'selected';
		}
		
		$selectProducto = '<option value="' . $producto->getPartidaArancelaria() . '" '.$tipoSelectProducto.'>' .  $producto->getPartidaArancelaria() . '</option>';
		
		$datosProducto = array('select_producto' => $selectProducto, 'codigo_producto' => $producto->getCodigoProducto());
		
		return $datosProducto;
	}
	
	/**
	 * Verifica la existencia de un producto bajo el mismo subtipo de producto.
	 *
	 * @return array|ResultSet
	 */
	public function buscarExistenciaNombreProductoPorSubtipoProducto($nombreProducto, $idSubtipoProducto){
		
		$consulta = "SELECT
						*
					FROM
						g_catalogos.productos
					WHERE
						quitar_caracteres_especiales(nombre_comun)
						ILIKE quitar_caracteres_especiales('$nombreProducto') and
						id_subtipo_producto = $idSubtipoProducto;";
		
		return $this->modeloProductos->ejecutarSqlNativo($consulta);
	}
}