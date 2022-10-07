<?php
 /**
 * Lógica del negocio de RegistroProduccionModelo
 *
 * Este archivo se complementa con el archivo RegistroProduccionControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-09-18
 * @uses    RegistroProduccionLogicaNegocio
 * @package EmisionCertificacionOrigen
 * @subpackage Modelos
 */
  namespace Agrodb\EmisionCertificacionOrigen\Modelos;
  
  use Agrodb\EmisionCertificacionOrigen\Modelos\IModelo;
 
class RegistroProduccionLogicaNegocio implements IModelo 
{

	 private $modeloRegistroProduccion = null;


	/**
	* Constructor
	* 
	* @retorna void
	 */
	 public function __construct()
	{
	 $this->modeloRegistroProduccion = new RegistroProduccionModelo();
	}

	/**
	* Guarda el registro actual
	* @param array $datos
	* @return int
	*/
	public function guardar(Array $datos)
	{
		$tablaModelo = new RegistroProduccionModelo($datos);
		$datosBd = $tablaModelo->getPrepararDatos();
		if ($tablaModelo->getIdRegistroProduccion() != null && $tablaModelo->getIdRegistroProduccion() > 0) {
		return $this->modeloRegistroProduccion->actualizar($datosBd, $tablaModelo->getIdRegistroProduccion());
		} else {
		unset($datosBd["id_registro_produccion"]);
		return $this->modeloRegistroProduccion->guardar($datosBd);
	}
	}
	
	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardarProduccion(Array $datos)
	{
	    $lNegocioProductosTemp = new ProductosTempLogicaNegocio();
	    $resultado =  $lNegocioProductosTemp->buscarLista("identificador_operador='".$_SESSION['usuario']."' order by 1");
	    if($resultado->count() > 0){
	        try{
	            $this->modeloRegistroProduccion = new RegistroProduccionModelo();
	            $proceso = $this->modeloRegistroProduccion->getAdapter()
	            ->getDriver()
	            ->getConnection();
	            if (! $proceso->beginTransaction()){
	                throw new \Exception('No se pudo iniciar la transacción: Guardar productos');
	            }
	            $datos['identificador_operador'] = $_SESSION['usuario'];
	            $tablaModelo = new RegistroProduccionModelo($datos);
	            $datosBd = $tablaModelo->getPrepararDatos();
	            if ($tablaModelo->getIdRegistroProduccion() != null && $tablaModelo->getIdRegistroProduccion() > 0) {
	                $idRegistro = $this->modeloRegistroProduccion->actualizar($datosBd, $tablaModelo->getIdRegistroProduccion());
	            } else {
	                unset($datosBd["id_registro_produccion"]);
	                $idRegistro = $this->modeloRegistroProduccion->guardar($datosBd);
	            }
	            if (!$idRegistro)
	            {
	                throw new \Exception('No se registo los datos en la tabla registro_produccion');
	            }
	            //*************guadar productos*************
	            foreach ($resultado as $item) {
	                $lnegocioProductos = new ProductosLogicaNegocio();
	                $datos = array(
	                    'id_registro_produccion' => $idRegistro,
	                    'num_canales_obtenidos' => $item['num_canales_obtenidos'],
	                    'num_canales_obtenidos_uso' => $item['num_canales_obtenidos_uso'],
	                    'num_canales_uso_industri' =>$item['num_canales_uso_industri'],
	                    'tipo_especie' => $item['tipo_especie'],
	                    'num_animales_recibidos' => $item['num_animales_recibidos'],
	                    'fecha_recepcion' => $item['fecha_recepcion'],
	                    'codigo_canal' => $item['codigo_canal'],
	                    'fecha_faenamiento' => $item['fecha_faenamiento']
	                );
	                $statement = $this->modeloRegistroProduccion->getAdapter()
	                ->getDriver()
	                ->createStatement();
	                $sqlInsertar = $this->modeloRegistroProduccion->guardarSql('productos', $this->modeloRegistroProduccion->getEsquema());
	                $sqlInsertar->columns($lnegocioProductos->columnas());
	                $sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
	                $sqlInsertar->prepareStatement($this->modeloRegistroProduccion->getAdapter(), $statement);
	                $statement->execute();
	                $idProducto= $this->modeloRegistroProduccion->adapter->driver->getLastGeneratedValue($this->modeloRegistroProduccion->getEsquema() . '.productos_id_productos_seq');
	                if (!$idProducto)
	                {
	                    throw new \Exception('No se registo los datos en la tabla productos');
	                }
	                $lNegocioSubproductosTemp = new SubproductosTempLogicaNegocio();
	                $subPro =  $lNegocioSubproductosTemp->buscarLista("id_productos_temp=".$item['id_productos_temp']." order by 1");
	                if($subPro->count() > 0){
	                    $lNegocioSubproductos = new SubproductosLogicaNegocio();
	                    foreach ($subPro as $value) {
	                        $datos = array(
	                            'id_productos' => $idProducto,
	                            'subproducto' => $value['subproducto'],
	                            'cantidad' => $value['cantidad']
	                        );
	                        $statement = $this->modeloRegistroProduccion->getAdapter()
	                        ->getDriver()
	                        ->createStatement();
	                        $sqlInsertar = $this->modeloRegistroProduccion->guardarSql('subproductos', $this->modeloRegistroProduccion->getEsquema());
	                        $sqlInsertar->columns($lNegocioSubproductos->columnas());
	                        $sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
	                        $sqlInsertar->prepareStatement($this->modeloRegistroProduccion->getAdapter(), $statement);
	                        $statement->execute();
	                        
	                    }
	                }
	                
	            }
	           	            
	            $proceso->commit();
	            return $idRegistro;
	        }catch (\Exception $ex){
	            $proceso->rollback();
	            throw new \Exception($ex->getMessage());
	            return 0;
	        }
	    }
	    
// 	    $tablaModelo = new RegistroProduccionModelo($datos);
// 	    $datosBd = $tablaModelo->getPrepararDatos();
// 	    if ($tablaModelo->getIdRegistroProduccion() != null && $tablaModelo->getIdRegistroProduccion() > 0) {
// 	        return $this->modeloRegistroProduccion->actualizar($datosBd, $tablaModelo->getIdRegistroProduccion());
// 	    } else {
// 	        unset($datosBd["id_registro_produccion"]);
// 	        return $this->modeloRegistroProduccion->guardar($datosBd);
// 	    }
	}
	/**
	 * Guarda el registro actual
	 * @param array $datos
	 * @return int
	 */
	public function guardarProductos(Array $datos)
	{
	    try{
	        $this->modeloRegistroProduccion = new RegistroProduccionModelo();
	        $proceso = $this->modeloRegistroProduccion->getAdapter()
	    ->getDriver()
	    ->getConnection();
	    if (! $proceso->beginTransaction()){
	        throw new \Exception('No se pudo iniciar la transacción: Guardar productos');
	    }
	    $datos['identificador_operador'] = $_SESSION['usuario'];
	    $tablaModelo = new RegistroProduccionModelo($datos);
	    $datosBd = $tablaModelo->getPrepararDatos();
	    if ($tablaModelo->getIdRegistroProduccion() != null && $tablaModelo->getIdRegistroProduccion() > 0) {
	        $idRegistro = $this->modeloRegistroProduccion->actualizar($datosBd, $tablaModelo->getIdRegistroProduccion());
	    } else {
	        unset($datosBd["id_registro_produccion"]);
	        $idRegistro = $this->modeloRegistroProduccion->guardar($datosBd);
	    }
	    if (!$idRegistro)
	    {
	        throw new \Exception('No se registo los datos en la tabla registro_produccion');
	    }
	    //*************guadar detalle de productos*************
        	    if(isset($datos['tipo_especie'])){
        	            $lnegocioDetalleSolicitudInspeccion = new ProductosLogicaNegocio();
        	            $datos = array(
        	                'id_registro_produccion' => $idRegistro,
        	                'num_canales_obtenidos' => $datos['num_canales_obtenidos'],
        	                'num_canales_obtenidos_uso' => $datos['num_canales_obtenidos_uso'],
        	                'num_canales_uso_industri' =>$datos['num_canales_uso_industri'],
        	                'tipo_especie' => $datos['tipo_especie'],
        	                'num_animales_recibidos' => $datos['num_animales_recibidos'],
        	                'fecha_recepcion' => $datos['fecha_recepcion']
        	            );
        	            $statement = $this->modeloRegistroProduccion->getAdapter()
        	            ->getDriver()
        	            ->createStatement();
        	            $sqlInsertar = $this->modeloRegistroProduccion->guardarSql('productos', $this->modeloRegistroProduccion->getEsquema());
        	            $sqlInsertar->columns($lnegocioDetalleSolicitudInspeccion->columnas());
        	            $sqlInsertar->values($datos, $sqlInsertar::VALUES_MERGE);
        	            $sqlInsertar->prepareStatement($this->modeloRegistroProduccion->getAdapter(), $statement);
        	            $statement->execute();
        	    }else{
        	        throw new \Exception('No existe productos..!!');
        	    }
	    
	    $proceso->commit();
	    return $idRegistro;
	    }catch (\Exception $ex){
	        $proceso->rollback();
	        throw new \Exception($ex->getMessage());
	        return 0;
	    }
	    
	}

	/**
	* Borra el registro actual
	* @param string Where|array $where
	* @return int
	*/
	public function borrar($id)
	{
		$this->modeloRegistroProduccion->borrar($id);
	}
	public function borrarPorParametro($param, $value) {
	    $this->modeloRegistroProduccion->borrarPorParametro($param, $value);
	}
	/**
	*
	* Buscar un registro de con la clave primaria
	*
	* @param  int $id
	* @return RegistroProduccionModelo
	*/
	public function buscar($id)
	{
		return $this->modeloRegistroProduccion->buscar($id);
	}

	/**
	* Busca todos los registros
	*
	* @return array|ResultSet
	*/
	public function buscarTodo()
	{
		return $this->modeloRegistroProduccion->buscarTodo();
	}

	/**
	* Busca una lista de acuerdo a los parámetros <params> enviados.
	*
	* @return array|ResultSet
	*/
	public function buscarLista($where=null, $order=null, $count=null, $offset=null)
	{
		return $this->modeloRegistroProduccion->buscarLista($where, $order, $count, $offset);
	}

	/**
	* Ejecuta una consulta(SQL) personalizada .
	*
	* @return array|ResultSet
	*/
	public function buscarRegistroProduccion()
	{
	$consulta = "SELECT * FROM ".$this->modeloRegistroProduccion->getEsquema().". registro_produccion";
		 return $this->modeloRegistroProduccion->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function listarRegistroProduccion($arrayParametros,$order='order by 1')
	{
	    $busqueda='';
	    if(array_key_exists('fecha_creacion', $arrayParametros)){
	        $busqueda .= " and rp.fecha_creacion::date = '".$arrayParametros['fecha_creacion']."'";
	    }
	    if(array_key_exists('id_registro_produccion', $arrayParametros)){
	        $busqueda .= " and rp.id_registro_produccion = '".$arrayParametros['id_registro_produccion']."'";
	    }
	    if(array_key_exists('id_productos', $arrayParametros)){
	        $busqueda .= " and p.id_productos = ".$arrayParametros['id_productos'];
	    }
	    if(array_key_exists('fechaInicio', $arrayParametros)){
	        $busqueda .= " and rp.fecha_creacion::date BETWEEN '".$arrayParametros['fechaInicio']."' AND '".$arrayParametros['fechaFin']."'";
	    }
	    if(array_key_exists('fecha_recepcion', $arrayParametros)){
	        $busqueda .= " and fecha_recepcion ='".$arrayParametros['fecha_recepcion']."'";
	    }
	    if(array_key_exists('tipo_especie', $arrayParametros)){
	        $busqueda .= " and tipo_especie ='".$arrayParametros['tipo_especie']."'";
	    }
	    if(array_key_exists('fecha_faenamiento', $arrayParametros)){
	        $busqueda .= " and fecha_faenamiento ='".$arrayParametros['fecha_faenamiento']."'";
	    }
	   $consulta = "
                    SELECT 
                            rp.id_registro_produccion, fecha_recepcion, tipo_especie, num_canales_obtenidos,num_canales_obtenidos_uso,
                            num_canales_uso_industri,num_animales_recibidos, codigo_canal, string_agg(distinct s.subproducto,', ') as subproducto,
                            p.id_productos, fecha_faenamiento
                    FROM 
                            g_emision_certificacion_origen.registro_produccion rp INNER JOIN g_emision_certificacion_origen.productos p
                            ON rp.id_registro_produccion = p.id_registro_produccion LEFT JOIN g_emision_certificacion_origen.subproductos S
                            ON s.id_productos = p.id_productos
                    WHERE 
                            rp.identificador_operador ='".$arrayParametros['identificador_operador']."' 
                            ".$busqueda."
							GROUP BY rp.id_registro_produccion,fecha_recepcion, tipo_especie,num_canales_obtenidos,num_canales_obtenidos_uso,
							num_canales_uso_industri,num_animales_recibidos, codigo_canal,  rp.id_registro_produccion,p.id_productos,fecha_faenamiento ".$order.";";
	    return $this->modeloRegistroProduccion->ejecutarSqlNativo($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function listarRegistroSubProduccion($arrayParametros,$order='order by 1')
	{
	    $busqueda='';
	    if(array_key_exists('fecha_creacion', $arrayParametros)){
	        $busqueda .= " and rp.fecha_creacion::date = '".$arrayParametros['fecha_creacion']."'";
	    }
	    if(array_key_exists('id_registro_produccion', $arrayParametros)){
	        $busqueda .= " and rp.id_registro_produccion = '".$arrayParametros['id_registro_produccion']."'";
	    }
	    if(array_key_exists('id_productos', $arrayParametros)){
	        $busqueda .= " and p.id_productos = '".$arrayParametros['id_productos']."'";
	    }
	    if(array_key_exists('fechaInicio', $arrayParametros)){
	        $busqueda .= " and rp.fecha_creacion::date BETWEEN '".$arrayParametros['fechaInicio']."' AND '".$arrayParametros['fechaFin']."'";
	    }
	    if(array_key_exists('fecha_faenamiento', $arrayParametros)){
	        $busqueda .= " and p.fecha_faenamiento ='".$arrayParametros['fecha_faenamiento']."'";
	    }
	    if(array_key_exists('tipo_especie', $arrayParametros)){
	        $busqueda .= " and tipo_especie ='".$arrayParametros['tipo_especie']."'";
	    }
	   $consulta = "
                    SELECT
                            rp.id_registro_produccion, fecha_recepcion, tipo_especie, num_canales_obtenidos,num_canales_obtenidos_uso,
                            num_canales_uso_industri,num_animales_recibidos, codigo_canal, string_agg(distinct s.subproducto,', ') as subproducto,
                            p.id_productos, s.id_subproductos
                    FROM
                            g_emision_certificacion_origen.registro_produccion rp INNER JOIN g_emision_certificacion_origen.productos p
                            ON rp.id_registro_produccion = p.id_registro_produccion INNER JOIN g_emision_certificacion_origen.subproductos S
                            ON s.id_productos = p.id_productos
                    WHERE
                            rp.identificador_operador ='".$arrayParametros['identificador_operador']."'
                            ".$busqueda."
							GROUP BY rp.id_registro_produccion,fecha_recepcion, tipo_especie,num_canales_obtenidos,num_canales_obtenidos_uso,
							num_canales_uso_industri,num_animales_recibidos, codigo_canal,  rp.id_registro_produccion,p.id_productos,s.id_subproductos  ".$order.";";
	    return $this->modeloRegistroProduccion->ejecutarSqlNativo($consulta);
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada para obtener los sitios.
	 *
	 * @return array|ResultSet
	 */
	public function buscarSitioFaenamiento($arrayParametros)
	{
	    
	    $busqueda='';
	    if(array_key_exists('id_centro_faenamiento', $arrayParametros)){
	        $busqueda .= " and id_centro_faenamiento ='".$arrayParametros['id_centro_faenamiento']."'";
	    }
	   $consulta = "SELECT
                            cf.identificador_operador,
                            cf.id_centro_faenamiento,
                            s.nombre_lugar as sitio,
                            a.nombre_area as area,
                            trim(unaccent(upper(cf.especie))) as especie
                        FROM
                        	g_centros_faenamiento.centros_faenamiento cf
                        	INNER JOIN g_operadores.sitios s ON s.id_sitio = cf.id_sitio
                        	INNER JOIN g_operadores.areas a ON a.id_area = cf.id_area and a.id_sitio = s.id_sitio
                        WHERE
                        	cf.identificador_operador = '" . $arrayParametros['identificador_operador'] . "' and
                        	cf.criterio_funcionamiento in ('Habilitado','Activo')
                            ".$busqueda.";";
	    
	    $datosFaenadorSitio = $this->modeloRegistroProduccion->ejecutarConsulta($consulta);
	    $especie = $opcion=$idCentroFaenamiento='';
	    foreach ($datosFaenadorSitio as $item) {
	        $especie = explode(',', $item['especie']);
	        foreach ($especie as $valor) {
	            if (trim($valor) === 'AVICOLA') {
	                $valoresEspecie[] = 'Aves';
	            } else {
	                $valoresEspecie[] = 'Otros';
	            }
	        }
	        //print_r($valoresEspecie);
	        $valoresEspecie = array_unique($valoresEspecie);
	        $idCentroFaenamiento = $item['id_centro_faenamiento'];
	        if (in_array('Aves', $valoresEspecie)) {
	            $opcion = 'Menores';
	        }else{
	            $opcion = 'Mayores';
	        }
	    }
	    return array('tipo' => $opcion, 'idCentroFaenamiento' => $idCentroFaenamiento, 'especie' => $especie);
	}
	
	public function obtenerSubproducto($arrayParametros){
	    $busqueda='';
	    if (array_key_exists('nombre_comun', $arrayParametros)) {
	        $busqueda .= " and nombre_comun = '".$arrayParametros['nombre_comun']."'";
	    }
	  $consulta ="
            SELECT 
                p.id_producto, nombre_comun, numero_piezas  
            FROM 
                g_catalogos.tipo_productos tp inner join g_catalogos.subtipo_productos stp on stp.id_tipo_producto = tp.id_tipo_producto
                inner join  g_catalogos.productos p on p.id_subtipo_producto = stp.id_subtipo_producto
            WHERE 
                id_area='" . $arrayParametros['id_area'] . "' and tp.nombre='" . $arrayParametros['nombreTipoProducto'] . "'
                and trim(unaccent(upper(stp.nombre))) = trim(upper('" . $arrayParametros['nombreSubtipo'] . "')) 
                and upper(nombre_comun) not in (upper('Canal')) and numero_piezas is not null and numero_piezas > 0
                 ".$busqueda.";";    
	   return $this->modeloRegistroProduccion->ejecutarConsulta($consulta);
	}
	
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function buscarSubproductosXProductos($arrayParametros)
	{
	    $busqueda='true';
	    if (array_key_exists('id_productos', $arrayParametros)) {
	        $busqueda .= " and p.id_productos = ".$arrayParametros['id_productos'];
	    }
	    if(array_key_exists('id_registro_produccion', $arrayParametros)){
	        $busqueda .= " and rp.id_registro_produccion = ".$arrayParametros['id_registro_produccion'];
	    }
	    if(array_key_exists('identificador_operador', $arrayParametros)){
	        $busqueda .= " and rp.identificador_operador = '".$arrayParametros['identificador_operador']."'";
	    }
	    $consulta = "
                    SELECT
                            s.id_subproductos, fecha_recepcion,fecha_faenamiento, tipo_especie, s.subproducto, cantidad, 
                            (SELECT sum(cantidad) FROM g_emision_certificacion_origen.subproductos WHERE id_productos = p.id_productos) as resultado
                    FROM
                            g_emision_certificacion_origen.registro_produccion rp INNER JOIN g_emision_certificacion_origen.productos p
                            ON rp.id_registro_produccion = p.id_registro_produccion INNER JOIN g_emision_certificacion_origen.subproductos S
                            ON s.id_productos = p.id_productos
                    WHERE
                            ".$busqueda."
							 order by 1;";
	    return $this->modeloRegistroProduccion->ejecutarConsulta($consulta);
	}
	/**
	 * Ejecuta una consulta(SQL) personalizada .
	 *
	 * @return array|ResultSet
	 */
	public function validarRegistroProduccion($arrayParametros)
	{
	    $consulta = "
                    SELECT
                            *
                    FROM
                            g_emision_certificacion_origen.registro_produccion rp INNER JOIN 
                            g_emision_certificacion_origen.productos p ON rp.id_registro_produccion = p.id_registro_produccion 
                    WHERE
                            rp.identificador_operador ='".$arrayParametros['identificador_operador']."'
                            and trim(p.tipo_especie) = trim('".$arrayParametros['tipo_especie']."')
                            and p.fecha_faenamiento = '".$arrayParametros['fecha_faenamiento']."'";
	    return $this->modeloRegistroProduccion->ejecutarSqlNativo($consulta);
	}
}
