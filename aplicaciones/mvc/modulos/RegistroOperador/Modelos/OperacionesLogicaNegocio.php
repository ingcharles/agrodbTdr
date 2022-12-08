<?php
/**
 * Lógica del negocio de OperacionesModelo
 *
 * Este archivo se complementa con el archivo OperacionesControlador.
 *
 * @author AGROCALIDAD
 * @date    2019-06-06
 * @uses OperacionesLogicaNegocio
 * @package RegistroOperador
 * @subpackage Modelos
 */
namespace Agrodb\RegistroOperador\Modelos;

use Agrodb\RegistroOperador\Modelos\IModelo;
use Agrodb\RevisionFormularios\Modelos\AsignacionInspectorLogicaNegocio;

use Agrodb\RegistroOperador\Modelos\DatosVehiculoTransporteAnimalesLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\DatosVehiculoTransporteAnimalesModelo;

use Agrodb\RegistroOperador\Modelos\VehiculoTransporteAnimalesExpiradoLogicaNegocio;
use Agrodb\RegistroOperador\Modelos\VehiculoTransporteAnimalesExpiradoModelo;

use Agrodb\Catalogos\Modelos\TiposOperacionLogicaNegocio;
use Agrodb\Catalogos\Modelos\TiposOperacionModelo;
use Agrodb\CertificacionBPA\Modelos\SolicitudesLogicaNegocio;
use Agrodb\Core\Excepciones\BuscarExcepcion;
use Agrodb\Token\Modelos\TokenLogicaNegocio;
use Agrodb\Core\Excepciones\GuardarExcepcion;
use Exception;
class OperacionesLogicaNegocio implements IModelo
{

    private $modeloOperaciones = null;

    private $lNegocioAsignacionInspector = null;
    
 
    private $lNegocioDatosVehiculoTransporteAnimales = null;
    
   
    private $lNegocioVehiculoTransporteAnimalesExpirado = null;


    private $lNegocioSolicitudes = null;
    
    private $lNegocioTiposOperacion = null;    
    private $modeloTiposOperacion = null;

    private $lNegocioToken = null;
    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloOperaciones = new OperacionesModelo();
        $this->lNegocioAsignacionInspector = new AsignacionInspectorLogicaNegocio();
        
        $this->lNegocioTiposOperacion = new TiposOperacionLogicaNegocio();
        $this->modeloTiposOperacion = new TiposOperacionModelo();
        

        $this->modeloVehiculoTransporteAnimales = new DatosVehiculoTransporteAnimalesModelo();
        

        $this->lNegocioVehiculoTransporteAnimalesExpirado = new VehiculoTransporteAnimalesExpiradoLogicaNegocio();


        $this->lNegocioSolicitudes = new SolicitudesLogicaNegocio();

        $this->lNegocioToken = new TokenLogicaNegocio();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        $tablaModelo = new OperacionesModelo($datos);
        $datosBd = $tablaModelo->getPrepararDatos();
        if ($tablaModelo->getIdOperacion() != null && $tablaModelo->getIdOperacion() > 0) {
            return $this->modeloOperaciones->actualizar($datosBd, $tablaModelo->getIdOperacion());
        } else {
            unset($datosBd["id_operacion"]);
            return $this->modeloOperaciones->guardar($datosBd);
        }
    }

    public function guardarResultado(Array $datos, Array $resultado)
    {
        try {
            $this->modeloOperaciones = new OperacionesModelo();
            $proceso = $this->modeloOperaciones->getAdapter()
                ->getDriver()
                ->getConnection();
            
            if (! $proceso->beginTransaction()) {
                throw new \Exception('No se pudo iniciar la transacción: Guardar operaciones');
            }
            
            $tablaModelo = new OperacionesModelo($datos);
            $datosBd = $tablaModelo->getPrepararDatos();
            
            if ($tablaModelo->getIdOperacion() != null && $tablaModelo->getIdOperacion() > 0) {
                $this->modeloOperaciones->actualizar($datosBd, $tablaModelo->getIdOperacion());
                $idRegistro = $tablaModelo->getIdOperacion();
            }
            
            if (! $idRegistro) {
                throw new \Exception('No se registo los datos en la tabla productos_areas_operacion');
            }

            $arrayProdAreaOpe = array(
                'estado' => $datos['estado'],
                'observacion' => $datos['observacion']
            );
            
            $statement = $this->modeloOperaciones->getAdapter()
                ->getDriver()
                ->createStatement();
            
            $sqlActualizar = $this->modeloOperaciones->actualizarSql('productos_areas_operacion', $this->modeloOperaciones->getEsquema());
            $sqlActualizar->set($arrayProdAreaOpe);
            
            $sqlActualizar->where(array(
                'id_operacion' => $idRegistro
            ));
            
            $sqlActualizar->prepareStatement($this->modeloOperaciones->getAdapter(), $statement);
            $statement->execute();

            $this->lNegocioAsignacionInspector->guardar($resultado);

            $proceso->commit();
            return $idRegistro;
            
        } catch (\Exception $ex) {
            $proceso->rollback();
            throw new \Exception($ex->getMessage());
            return 0;
        }
    }
    
    /**
     * Borra el registro actual
     *
     * @param
     *            string Where|array $where
     * @return int
     */
    public function borrar($id)
    {
        $this->modeloOperaciones->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return OperacionesModelo
     */
    public function buscar($id)
    {
        return $this->modeloOperaciones->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloOperaciones->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloOperaciones->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarOperaciones()
    {
        $consulta = "SELECT * FROM " . $this->modeloOperaciones->getEsquema() . ". operaciones";
        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    /**
     */
    public function listarOperacionesXOperador($arrayParametros)
    {
        $busqueda = '';
        if (array_key_exists('identificador_operador', $arrayParametros)) {
            if ($arrayParametros['identificador_operador'] != '') {
                $busqueda .= " and s.identificador_operador  = '" . $arrayParametros['identificador_operador'] . "'";
            }
        }
        if (array_key_exists('razon_social', $arrayParametros)) {
            if ($arrayParametros['razon_social'] != '') {
                $busqueda .= " and upper(o.razon_social)  = upper('" . $arrayParametros['razon_social'] . "')";
            }
        }
        if (array_key_exists('codigo', $arrayParametros)) {
            if ($arrayParametros['codigo'] != '') {
                $busqueda .= " and upper(t.codigo)  = upper('" . $arrayParametros['codigo'] . "')";
            }
        }
        if (array_key_exists('id_area', $arrayParametros)) {
            $busqueda .= " and t.id_area = '" . $arrayParametros['id_area'] . "'";
        }
        
        $consulta = " select
								distinct min(s.id_operacion) as id_operacion,
								s.identificador_operador,
								s.estado,
								s.id_tipo_operacion,
								t.nombre as nombre_tipo_operacion,
								st.provincia,
								st.id_sitio,
								st.nombre_lugar,
                                t.codigo,
                                t.permite_desplegar_administracion_operacion
							from
								g_operadores.operaciones s,
								g_catalogos.tipos_operacion t,
								g_operadores.operadores o,
								g_operadores.productos_areas_operacion sa,
								g_operadores.areas a,
								g_operadores.sitios st,
								g_operadores.flujos_operaciones fo
							where
								s.id_tipo_operacion = t.id_tipo_operacion and
								s.identificador_operador = o.identificador and
								s.id_operacion = sa.id_operacion and
								sa.id_area = a.id_area and
								a.id_sitio = st.id_sitio and
								t.id_flujo_operacion = fo.id_flujo and 
                                upper(st.provincia) = upper('" . $arrayParametros['provincia'] . "') and
                                t.permite_desplegar_administracion_operacion is true and
                                t.estado = 1
                                " . $busqueda . "
							group by s.identificador_operador, s.estado, s.id_tipo_operacion, nombre_tipo_operacion, st.provincia, st.id_sitio, a.id_area, t.codigo,
                                t.permite_desplegar_administracion_operacion
							order by id_operacion;";
        
        //--s.estado " . $arrayParametros['estado'] . " and
        
        //echo $consulta;
        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    public function buscarNombreAreaPorSitioPorTipoOperacion($arrayParametros)
    {
        $consulta = "SELECT array_to_string(ARRAY(
													SELECT
														distinct a.nombre_area
													FROM
														g_operadores.areas a INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
														INNER JOIN g_operadores.operaciones o ON pao.id_operacion = o.id_operacion
													WHERE
                                                        a.id_sitio = " . $arrayParametros['idSitio'] . " and o.id_tipo_operacion = " . $arrayParametros['idTipoOperacion'] . "
													),', ') as nombre_area;";

        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    /**
     * obtener informacion del operador
     *
     * @param string $identificador
     */
    public function obtenerOperador($identificador)
    {
        $consulta = "
                        SELECT row_to_json (operador)
                        FROM (
                            SELECT
                                o1.* ,
                                (
                                    SELECT array_to_json(array_agg(row_to_json(operaciones_n2)))
                                    FROM (
                                            select
                                                distinct on(topc2.id_area, topc2.nombre) topc2.*
                                            from
                                                g_operadores.operadores opr2
                                                , g_operadores.operaciones opc2
                                                , g_catalogos.tipos_operacion topc2
                                            where
                                                opr2.identificador = opc2.identificador_operador
                                                and opc2.id_tipo_operacion = topc2.id_tipo_operacion
                                                and opr2.identificador = o1.identificador
                                            order by
                                                topc2.id_area, topc2.nombre ) operaciones_n2
                                ) operaciones
                            FROM
                                g_operadores.operadores o1
                            WHERE
                                o1.identificador = '$identificador'
                        ) as operador";

        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    public function abrirDatosOperacionSitioArea($arrayParametros)
    {
        $consulta = "select
							o.id_operacion,
							o.id_tipo_operacion,
							o.identificador_operador,
							o.id_producto,
							o.nombre_producto,
							o.estado,
                            o.estado_anterior,
							o.id_producto,
							o.nombre_producto,
							o.observacion,
							o.nombre_pais,
							o.fecha_aprobacion,
							o.fecha_finalizacion,
							o.id_operador_tipo_operacion,
							o.id_historial_operacion,
							t.nombre,
							t.id_area as codigo_area,
							t.codigo as codigo_tipo_operacion,
                            a.id_area,
							a.nombre_area as area,
							a.tipo_area,
							a.superficie_utilizada,
							ss.provincia,
							ss.canton,
							ss.parroquia,
							ss.id_sitio,
							ss.nombre_lugar as sitio,
							ss.direccion,
							ss.referencia,
							ss.croquis,
							pao.estado as estado_area,
							pao.ruta_archivo,
							pao.id_area,
							pao.observacion as observacion_area,
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
							a.id_sitio = ss.id_sitio and
                            t.estado=1
						order by
							o.id_producto;";
        
        //echo $consulta;
        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }
    
    public function buscarOperacionEspecificaEnSitio($arrayParametros)
    {
        $consulta = "select
							o.id_operacion,
							o.id_tipo_operacion,
							o.identificador_operador,
							o.estado,
                            t.nombre,
							t.id_area as codigo_area,
							t.codigo as codigo_tipo_operacion,
                            a.id_area,
							a.nombre_area as area,
							a.tipo_area							
						from
							g_operadores.operaciones o,
							g_operadores.productos_areas_operacion pao,
							g_operadores.areas a,
							g_catalogos.tipos_operacion t,
							g_operadores.sitios ss
						where
							o.identificador_operador = '" . $arrayParametros['identificadorOperador'] . "' and
							t.codigo in ( " . $arrayParametros['codigoOperacion'] . " ) and
							o.id_operacion = pao.id_operacion and
							pao.id_area = a.id_area and
							o.id_operacion = pao.id_operacion and
							o.id_tipo_operacion = t.id_tipo_operacion and
							a.id_sitio = ss.id_sitio and
                            t.estado=1 and
							ss.id_sitio = " . $arrayParametros['idSitio'] . " and
							o.estado = " . $arrayParametros['estado'] . ";";
        
        //echo $consulta;
        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    /**
     */
    public function listarDatosVehiculoXIdAreaXidTipoOperacion($arrayParametros)
    {
        $consulta = "SELECT
                        nombre_marca_vehiculo as marca,
                        nombre_modelo_vehiculo  as modelo,
                        nombre_tipo_vehiculo as tipoVehiculo,
                        nombre_color_vehiculo as colorVehiculo,
                        nombre_clase_vehiculo as clase,
                        placa_vehiculo,
                        anio_vehiculo,
                        capacidad_vehiculo,
                        codigo_unidad_medida
					FROM
						g_operadores.datos_vehiculos
					WHERE
						id_area = " . $arrayParametros['id_area'] . " and
						id_tipo_operacion = " . $arrayParametros['id_tipo_operacion'] . " and
						id_operador_tipo_operacion = " . $arrayParametros['id_operador_tipo_operacion'] . " and
						estado_dato_vehiculo = '" . $arrayParametros['estado'] . "'
						and id_dato_vehiculo = (SELECT
													max(id_dato_vehiculo)
												FROM
													g_operadores.datos_vehiculos
												WHERE
													id_area = " . $arrayParametros['id_area'] . " and
													id_tipo_operacion = " . $arrayParametros['id_tipo_operacion'] . " and
													id_operador_tipo_operacion = " . $arrayParametros['id_operador_tipo_operacion'] . " and
													estado_dato_vehiculo = '" . $arrayParametros['estado'] . "');";

        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    /**
     */
    public function obtenerAreaXIdOperacion($idOperacion)
    {
        $consulta = "SELECT
			        	pao.id_area
			        FROM
				        g_operadores.operaciones op,
				        g_operadores.productos_areas_operacion pao
			        WHERE
				        op.id_operacion=pao.id_operacion
				        and op.id_operacion=" . $idOperacion . ";";
        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    public function obtenerProductosPorIdOperadorTipoOperacionHistorico($arrayParametros)
    {
        $consulta = "SELECT
						o.id_operacion, p.id_producto, nombre_comun, sp.nombre as nombre_subtipo, 
                        codificacion_subtipo_producto, tp.nombre as nombre_tipo, o.estado, o.estado_anterior
					FROM
						g_operadores.operaciones o,
						g_catalogos.productos p,
						g_catalogos.subtipo_productos sp,
						g_catalogos.tipo_productos tp
					WHERE
						o.id_producto = p.id_producto
						and p.id_subtipo_producto = sp.id_subtipo_producto
						and sp.id_tipo_producto = tp.id_tipo_producto
						and id_operador_tipo_operacion in (" . $arrayParametros['id_operador_tipo_operacion'] . ")
						and id_historial_operacion in (" . $arrayParametros['id_historial_operacion'] . ")
                        and o.estado = '" . $arrayParametros['estado'] . "'
                        ;";

        //echo $consulta;
        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    public function variedadesXOperacionesXProductos($idOperacion)
    {
        $consulta = "SELECT
								       				v.nombre
								       			FROM
								       				g_operadores.operaciones_variedades ov,
								       				g_catalogos.variedades v,
								       				g_operadores.operaciones ope
								       			WHERE
								       				ov.id_operacion=ope.id_operacion and
								       				ov.id_variedad=v.id_variedad and
								       				ope.id_operacion='$idOperacion'
								       				order by 1";
        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    public function obtenerTipoOperacionesPorIdentificadorProvincia($arrayParametros)
    {
        $consulta = "SELECT
						distinct top.nombre, 
						top.id_tipo_operacion, 
						top.id_area,
						array_to_string(array_agg(distinct s.provincia), ', ') as nombre_provincia
					FROM
						g_operadores.sitios s
						INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio
						INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
						INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
						INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
						INNER JOIN g_estructura.area ar ON top.id_area = ar.id_area
					WHERE
						op.identificador_operador = '" . $arrayParametros['identificador_operador'] . "'
						" . ($arrayParametros['nombre_provincia'] != '--' ? " and s.provincia ilike '%" . $arrayParametros['nombre_provincia'] . "%'" : "") . "
					GROUP BY 
						1, 2, 3
					ORDER BY
						top.nombre;";

        $tipoOperaciones = $this->modeloOperaciones->ejecutarSqlNativo($consulta);

        $arrayTipoOperacion = array();

        foreach ($tipoOperaciones as $tipoOperacion) {

            switch ($tipoOperacion->id_area) {
                case 'SV':
                    $codigoArea = strtolower($tipoOperacion->id_area);
                    $nombreArea = 'Sanidad Vegetal';
                    break;
                case 'SA':
                    $codigoArea = strtolower($tipoOperacion->id_area);
                    $nombreArea = 'Sanidad Animal';
                    break;
                case 'IAV':
                    $codigoArea = 'ria';
                    $nombreArea = 'Registro de Insumos Pecuarios';
                    break;
                case 'IAP':
                    $codigoArea = 'ria';
                    $nombreArea = 'Registro de Insumos Agrícolas';
                    break;
                case 'IAF':
                    $codigoArea = 'ria';
                    $nombreArea = 'Registro de insumos fertilzantes';
                    break;
                case 'CGRIA':
                    $codigoArea = 'ria';
                    $nombreArea = 'Coordinación de registros de insumos agropecuarios';
                    break;
                case 'AI':
                    $codigoArea = strtolower($tipoOperacion->id_area);
                    $nombreArea = 'Inocuidad de los alimentos';
                    break;
                case 'LT':
                    $codigoArea = strtolower($tipoOperacion->id_area);
                    $nombreArea = 'Laboratorios Tumbaco';
                    break;
            }

            $arrayTipoOperacion[] = array(
                'operacion' => $tipoOperacion->nombre,
                'area' => $nombreArea,
                'areacod' => $codigoArea,
                'provincia' => $tipoOperacion->nombre_provincia
            );
        }

        return $arrayTipoOperacion;
    }

    public function buscarOperacionesProveedoresOperadorProducto($arrayParametros)
    {
        $consulta = "SELECT
						op.identificador_operador,
						op.id_tipo_operacion,
						op.estado,
						op.id_producto,
						top.nombre
					FROM
						g_operadores.proveedores pr,
						g_operadores.operaciones op,
						g_catalogos.tipos_operacion top
					WHERE
						pr.identificador_operador = '" . $arrayParametros['identificador_operador'] . "'
						and pr.codigo_proveedor = op.identificador_operador
						and op.id_tipo_operacion = top.id_tipo_operacion
						and pr.id_producto = op.id_producto
						and top.nombre not in ('Exportador', 'Importador')
						and pr.id_producto = " . $arrayParametros['id_producto'] . "
						and op.estado IN " . $arrayParametros['estado'] . ";";

        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    /**
     * obtener tipo de operaciones registradas
     */
    public function obtenerTipoOperacionesOperador($arrayParametros)
    {
        $busqueda = '';

        if (array_key_exists('identificador_operador', $arrayParametros)) {
            if ($arrayParametros['identificador_operador'] != '') {
                $busqueda .= " and op.identificador_operador  = '" . $arrayParametros['identificador_operador'] . "'";
            }
        }
        if (array_key_exists('razon_social', $arrayParametros)) {
            if ($arrayParametros['razon_social'] != '') {
                $busqueda .= " and upper(o.razon_social)  = upper('" . $arrayParametros['razon_social'] . "')";
            }
        }
        if (array_key_exists('id_area', $arrayParametros)) {
            $busqueda .= " and top.id_area in ('" . $arrayParametros['id_area'] . "')";
        }

        $consulta = " 
                    SELECT 
                        top.nombre as operaciones_registradas, top.codigo
                    FROM 
                        g_operadores.sitios s
                        INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio
                        INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
                        INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
                        INNER JOIN g_operadores.operadores o ON op.identificador_operador = o.identificador
                        INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                    WHERE
                        op.estado " . $arrayParametros['estado'] . " and 
						top.permite_desplegar_administracion_operacion = 'true' and
                        top.estado = 1 and
                        upper(s.provincia) = upper('" . $arrayParametros['provincia'] . "')  
                        " . $busqueda . "
                    GROUP BY 1,2;";

        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    public function buscarOperacionSitioOperador($arrayParametros)
    {
        $consulta = "SELECT
                        	distinct o.identificador_operador,
                        	op.razon_social,
                        	s.nombre_lugar,
                        	o.id_operador_tipo_operacion,
                            s.provincia
                        FROM
                        	g_operadores.operaciones o
                        	INNER JOIN g_operadores.operadores op ON o.identificador_operador = op.identificador
                        	INNER JOIN g_operadores.productos_areas_operacion pao ON o.id_operacion = pao.id_operacion
                        	INNER JOIN g_operadores.areas a ON a.id_area = pao.id_area
                        	INNER JOIN g_operadores.sitios s ON s.id_sitio = a.id_sitio
                        	INNER JOIN g_catalogos.tipos_operacion tp ON o.id_tipo_operacion = tp.id_tipo_operacion
                        WHERE
                        	tp.codigo in ('" . $arrayParametros['codigo_operacion'] . "') and
                        	tp.id_area in ('" . $arrayParametros['id_area'] . "') and
                        	o.identificador_operador = '" . $arrayParametros['identificador_operador'] . "' and
                        	o.estado = 'registrado'
                        LIMIT 1;";

        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las provincias en donde se tiene operaciones de un tipo especificado registradas
     *
     * @return array|ResultSet
     */
    public function comboProvinciaXOperacionesRegistradas($tipoOperacion)
    {
        $consulta = "   SELECT 
                        	distinct s.provincia,
                        	(SELECT l.id_localizacion from g_catalogos.localizacion l WHERE l.nombre = s.provincia and l.categoria=1) as id_provincia
                        FROM g_operadores.operaciones o
                            INNER JOIN g_catalogos.tipos_operacion tp on tp.id_tipo_operacion = o.id_tipo_operacion
                            INNER JOIN g_operadores.productos_areas_operacion pao on pao.id_operacion = o.id_operacion
                            INNER JOIN g_operadores.areas a ON pao.id_area = a.id_area
                            INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                        WHERE
                            o.estado='registrado' and
                            tp.codigo in ($tipoOperacion);";

        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }
    
    public function guardarActualizaciónEstadoOperaciones($datos)
    {
        $estado = 'exito';
        $mensaje = '';
        $banderaActualizar = true;
        $observacionPost = '';
        $actualizarCertificado = 'NO';
        
        $validacion = array(
            'bandera' => true,
            'estado' => "exito",
            'mensaje' => "Cambio de estado guardado con éxito",
            'contenido' => null
        );
        
        $qTipoOperacion = $this->lNegocioTiposOperacion->buscarTipoOperacionPorIdOperacion($datos);
        $idArea = $qTipoOperacion->current()->id_area;
        $codigoOperacion = $qTipoOperacion->current()->codigo;
        
        //Validaciones por área
        switch ($idArea) {
            case 'SA':
                switch ($codigoOperacion) {
                    case 'TAV':
                        $datosOperacion = $this->buscar($datos['id_operacion']);
                        $idOperadorTipoOperacion = $datosOperacion->getIdOperadorTipoOperacion();
                        
                        $arrayParametros = array(
                            'id_operador_tipo_operacion' => $idOperadorTipoOperacion
                        );
                        
                        $qOperacionesTransporteAnimales = $this->lNegocioDatosVehiculoTransporteAnimales->buscarDatosVehiculoTransporteAnimalesPorIdOperadorTipoOperacion($arrayParametros);
                        
                        if (isset($qOperacionesTransporteAnimales->current()->id_dato_vehiculo_transporte_animales)) {
                            
                            $idDatoVehiculoAntiguo = $qOperacionesTransporteAnimales->current()->id_dato_vehiculo_transporte_animales;
                            
                            $arrayParametros = array(
                                'id_dato_vehiculo_antiguo' => $idDatoVehiculoAntiguo
                            );
                            
                            $verificarHabilitarOperaciones = $this->lNegocioVehiculoTransporteAnimalesExpirado->verificarVehiculoTransporteAnimalesExpirado($arrayParametros);
                            
                            if (isset($verificarHabilitarOperaciones->current()->id_dato_vehiculo_antiguo)) {
                                $banderaActualizar = false;
                                $estado = 'ERROR';
                                $mensaje = 'La operación no puede ser habilitada, por que esta atada a un vehiculo registrado por el operador ' . $verificarHabilitarOperaciones->current()->identificador_propietario_vehiculo . '.';
                            }
                        } else {
                            $banderaActualizar = false;
                            $estado = 'ERROR';
                            $mensaje = 'La operación no posee vehículos asociados (No puede ser habilitada).';
                        }
                        break;
                }
                break;
        }
        
        //Casos de porCaducar, se valida que el tiempo de vigencia sea mayor a la fecha actual para poder volver al estado anterior, caso contrario se mantiene el mismo estado actual
        /*if($estadoAnteriorRegistro == 'porCaducar'){
            
            $fechaFinalizacion = date('Y-m-d H:i:s', strtotime($operacion->getFechaFinalizacion()));
            $fechaActual = date('Y-m-d H:i:s');
            
            if($fechaFinalizacion >= $fechaActual){
                $bandera = true;
                $validacion['contenido'] = 'Fin mayor: ' . $fechaFinalizacion . ' - ' . 'actual: ' . $fechaActual;
            }else{
                $bandera = false;
                $validacion['contenido'] = 'Fin menor: ' . $fechaFinalizacion . ' - ' . 'actual: ' . $fechaActual;
            }
        }*/
        
        if ($banderaActualizar) { 
            
            if($datos['productosDeclarados'] == 'Si'){
                //Revisa si hay productos asignados a la operación y han sido seleccionados
                if (isset($datos['check'])) {
                    $observacionPost = $datos['observacion'];
                    
                    foreach ($datos['check'] as $value) {
                        
                        $datos['id_operacion'] = $value;
                        
                        $operacion = $this->buscar($value);
                        $estadoAnterior = $operacion->getEstado();
                        $estadoAnteriorRegistro = $operacion->getEstadoAnterior();
                        
                        //Estado actual: Registrado
                        //Solamente se permite inactivar. Debe actualizar el certificado, operacion en true
                        if ($datos['resultado'] == 'noHabilitado') {
                            $estado = 'noHabilitado';
                            $observacion = 'Inactivación realizada por el módulo Administración Operaciones GUIA por el funcionario ' . $_SESSION['usuario'] . ' Estado anterior ' . $estadoAnterior . '-' . $observacionPost;
                            $actualizarCertificado = 'SI';
                            $bandera = true;
                        }
                        //Estado actual: noHabilitado
                        //Se cambia al estado anterior. Si el anterior era registrado o registradoObservacion actualizar certificado, operacion true
                        else if($datos['resultado'] == 'estadoAnterior'){
                            
                            $bandera = true;
                            
                            $estado = $estadoAnteriorRegistro;
                            $observacion = 'Cambio de estado realizado por el módulo Administración Operaciones GUIA por el funcionario ' . $_SESSION['usuario'] . ' Estado anterior ' . $estadoAnterior . '-' . $observacionPost;
                            
                            if($estadoAnteriorRegistro == 'registrado' || $estadoAnteriorRegistro == 'registradoObservacion'){
                                $actualizarCertificado = 'SI';
                            }else{
                                $actualizarCertificado = 'NO';
                            }

                            //Casos de porCaducar, se valida que el tiempo de vigencia sea mayor a la fecha actual para poder volver al estado anterior, caso contrario se mantiene el mismo estado actual
                            if($estadoAnteriorRegistro == 'porCaducar'){
                                
                                $fechaFinalizacion = date('Y-m-d H:i:s', strtotime($operacion->getFechaFinalizacion()));
                                $fechaActual = date('Y-m-d H:i:s');
                                
                                if($fechaFinalizacion >= $fechaActual){
                                    $bandera = true;
                                    $contenido = 'Fin vigencia mayor: ' . $fechaFinalizacion . ' - ' . ' fecha actual: ' . $fechaActual;
                                }else{
                                    $bandera = false;
                                    $contenido = 'Fin vigencia menor: ' . $fechaFinalizacion . ' - ' . 'fecha actual: ' . $fechaActual;
                                }
                            }
                        }
                        //Estado actual: cualquier estado parcial antes de aprobación o eliminación
                        //Con cualquier otro estado solamente se inactiva
                        else if($datos['resultado'] == 'noHabilitadoOtro'){
                            $estado = 'noHabilitado';
                            $observacion = 'Inactivación realizada por el módulo Administración Operaciones GUIA por el funcionario ' . $_SESSION['usuario'] . ' Estado anterior ' . $estadoAnterior . '-' . $observacionPost;
                            $actualizarCertificado = 'NO';
                            $bandera = true;
                        }
                        
                        if($bandera){
                            $datos['estado'] = $estado;
                            $datos['observacion'] = $observacion;
                            $datos['observacion_tecnica'] = $observacion;
                            $datos['estado_anterior'] = $estadoAnterior;
                            $datos['actualizar_certificado'] = $actualizarCertificado;
                            
                            // ******************Revision de solicitudes******************
                            
                            $arrayParametros = array(
                                'identificadorOperador' => $operacion->getIdentificadorOperador(),
                                'idOperacion' => $value
                            );
                            
                            $operador = $this->abrirDatosOperacionSitioArea($arrayParametros);
                            
                            foreach ($operador as $item) {
                                
                                $arrayRevisionSolicitudes = array(
                                    'identificador_inspector' => $_SESSION['usuario'],
                                    'fecha_asignacion' => 'now()',
                                    'identificador_asignante' => $_SESSION['usuario'],
                                    'tipo_solicitud' => 'Operadores',
                                    'tipo_inspector' => 'Técnico',
                                    'id_operador_tipo_operacion' => $item['id_operador_tipo_operacion'],
                                    'id_historial_operacion' => $item['id_historial_operacion'],
                                    'id_solicitud' => $value,
                                    'estado' => 'Técnico',
                                    'fecha_inspeccion' => 'now()',
                                    'observacion' => $observacion,
                                    'estado_siguiente' => $estado,
                                    'orden' => 1
                                );
                            }
                            
                            $resultado = $this->guardarResultado($datos, $arrayRevisionSolicitudes);
                            
                            if ($resultado) {
                                $validacion['bandera'] = true;
                                $validacion['estado'] = "exito";
                                $validacion['mensaje'] = "Se ha realizado la actualización de estado";
                                $validacion['contenido'] = null;
                                
                            } else {
                                $validacion['bandera'] = false;
                                $validacion['estado'] = "ERROR";
                                $validacion['mensaje'] = "No se ha podido realizar la actualización de estado";
                                $validacion['contenido'] = null;
                                
                                break;
                            }
                        
                        }else{
                            $validacion['bandera'] = false;
                            $validacion['estado'] = "ERROR";
                            $validacion['mensaje'] = 'El tiempo de vigencia de la operación ha expirado, no puede cambiar el estado.';
                            $validacion['contenido'] = $contenido;
                        }
                    }
                }else{
                    $estado = 'ERROR';
                    $mensaje = 'Debe seleccionar por lo menos un producto, o la operación no dispone de productos para inactivar.';
                }
            }else{
                //Revisa si la operación está en estado cargarProducto o  y cambia el estado a noHabilitado
                
                $value = $datos['id_operacion'];
                $observacionPost = $datos['observacion'];
                
                $operacion = $this->buscar($value);
                $estadoAnterior = $operacion->getEstado();
                $estadoAnteriorRegistro = $operacion->getEstadoAnterior();
                
                //Estado actual: Registrado
                //Solamente se permite inactivar. Debe actualizar el certificado, operacion en true
                if ($datos['resultado'] == 'noHabilitado') {
                    $estado = 'noHabilitado';
                    $observacion = 'Inactivación realizada por el módulo Administración Operaciones GUIA por el funcionario ' . $_SESSION['usuario'] . ' Estado anterior ' . $estadoAnterior . '-' . $observacionPost;
                }
                //Estado actual: cargarProducto
                //Solamente se permite inactivar. No se actualiza certificado porque no hay uno emitido.
                if($datos['resultado'] == 'noHabilitadoOtro'){// && $estadoAnterior == 'cargarProducto'
                    $estado = 'noHabilitado';
                    $observacion = 'Inactivación realizada por el módulo Administración Operaciones GUIA por el funcionario ' . $_SESSION['usuario'] . ' Estado anterior ' . $estadoAnterior . '-' . $observacionPost;
                    $actualizarCertificado = 'NO'; //Consultar si se debe poner
                }
                
                //Estado actual: noHabilitado
                //Se cambia al estado anterior si era cargarProducto. No se actualiza certificado porque no hay uno emitido.
                else if($datos['resultado'] == 'estadoAnterior'){
                    $estado = $estadoAnteriorRegistro;
                    $observacion = 'Cambio de estado realizado por el módulo Administración Operaciones GUIA por el funcionario ' . $_SESSION['usuario'] . ' Estado anterior ' . $estadoAnterior . '-' . $observacionPost;
                    $actualizarCertificado = 'NO'; //Consultar si se debe poner
                }
                
                $datos['estado'] = $estado;
                $datos['observacion'] = $observacion;
                $datos['observacion_tecnica'] = $observacion;
                $datos['estado_anterior'] = $estadoAnterior;
                $datos['actualizar_certificado'] = $actualizarCertificado;
                
                // ******************Revision de solicitudes******************
                
                $arrayParametros = array(
                    'identificadorOperador' => $operacion->getIdentificadorOperador(),
                    'idOperacion' => $value
                );
                
                $operador = $this->abrirDatosOperacionSitioArea($arrayParametros);
                
                foreach ($operador as $item) {
                    
                    $arrayRevisionSolicitudes = array(
                        'identificador_inspector' => $_SESSION['usuario'],
                        'fecha_asignacion' => 'now()',
                        'identificador_asignante' => $_SESSION['usuario'],
                        'tipo_solicitud' => 'Operadores',
                        'tipo_inspector' => 'Técnico',
                        'id_operador_tipo_operacion' => $item['id_operador_tipo_operacion'],
                        'id_historial_operacion' => $item['id_historial_operacion'],
                        'id_solicitud' => $value,
                        'estado' => 'Técnico',
                        'fecha_inspeccion' => 'now()',
                        'observacion' => $observacion,
                        'estado_siguiente' => $estado,
                        'orden' => 1
                    );
                }
                
                $resultado = $this->guardarResultado($datos, $arrayRevisionSolicitudes);//revisar si la funcion sirve para los dos casos
                
                if ($resultado) {
                    $validacion['bandera'] = true;
                    $validacion['estado'] = "exito";
                    $validacion['mensaje'] = "Se ha realizado la actualización de estado";
                    $validacion['contenido'] = null;
                    
                } else {
                    $validacion['bandera'] = false;
                    $validacion['estado'] = "ERROR";
                    $validacion['mensaje'] = "No se ha podido realizar la actualización de estado";
                    $validacion['contenido'] = null;
                    
                }
            }

        }else{
            $validacion['bandera'] = false;
            $validacion['estado'] = "ERROR";
            $validacion['mensaje'] = "Los datos no pueden ser modificados";
            $validacion['contenido'] = null;
            
        }
        
        return $validacion;
    }   
    
    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Busca las provincias en donde se tiene operaciones de un tipo especificado registradas
     *
     * @return array|ResultSet
     */
    public function actualizarCertificadoOperacion($idArea)
    {
        $consulta = "   UPDATE 
                            g_operadores.operaciones
                        SET 
                            actualizar_certificado='SI'
                        WHERE id_operacion in (
                                SELECT 
                                    o.id_operacion
                                FROM 
                                    g_operadores.operaciones o
                                    INNER JOIN g_operadores.productos_areas_operacion pao ON pao.id_operacion = o.id_operacion
                                    INNER JOIN g_catalogos.tipos_operacion tp ON tp.id_tipo_operacion = o.id_tipo_operacion
                                WHERE 
                                    pao.id_area in ($idArea) and 
                                    tp.estado=1
                                );";
        
        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }
	
	public function obtenerOperacionesOperador($arrayParametros)
    {
        $busqueda = '';
        if (array_key_exists('identificador_operador', $arrayParametros)) {
            if ($arrayParametros['identificador_operador'] != '') {
                $busqueda .= " and s.identificador_operador  = '" . $arrayParametros['identificador_operador'] . "'";
            }
        }
        if (array_key_exists('razon_social', $arrayParametros)) {
            if ($arrayParametros['razon_social'] != '') {
                $busqueda .= " and upper(o.razon_social)  = upper('" . $arrayParametros['razon_social'] . "')";
            }
        }
        if (array_key_exists('codigo', $arrayParametros)) {
            if ($arrayParametros['codigo'] != '') {
                $busqueda .= " and upper(t.codigo)  = upper('" . $arrayParametros['codigo'] . "')";
            }
        }
        if (array_key_exists('id_area', $arrayParametros)) {
            $busqueda .= " and t.id_area = '" . $arrayParametros['id_area'] . "'";
        }
        
        $consulta = " select
								distinct min(s.id_operacion) as id_operacion,
								s.identificador_operador,
								s.estado,
								s.id_tipo_operacion,
								t.nombre as nombre_tipo_operacion,
								st.provincia,
								st.id_sitio,
								st.nombre_lugar,
                                t.codigo,
                                t.permite_desplegar_administracion_operacion
							from
								g_operadores.operaciones s,
								g_catalogos.tipos_operacion t,
								g_operadores.operadores o,
								g_operadores.productos_areas_operacion sa,
								g_operadores.areas a,
								g_operadores.sitios st,
								g_operadores.flujos_operaciones fo
							where
								s.id_tipo_operacion = t.id_tipo_operacion and
								s.identificador_operador = o.identificador and
								s.id_operacion = sa.id_operacion and
								sa.id_area = a.id_area and
								a.id_sitio = st.id_sitio and
								t.id_flujo_operacion = fo.id_flujo and 
                                upper(st.provincia) = upper('" . $arrayParametros['provincia'] . "') and
                                t.permite_desplegar_administracion_operacion is true and
                                t.estado = 1
                                " . $busqueda . "
							group by s.identificador_operador, s.estado, s.id_tipo_operacion, nombre_tipo_operacion, st.provincia, st.id_sitio, a.id_area, t.codigo,
                                t.permite_desplegar_administracion_operacion
							order by id_operacion;";

        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }
    
	public function abrirOperacion($arrayParametros){
		$consulta = "select
							o.id_operacion,
							o.id_tipo_operacion,
							o.identificador_operador,
							o.id_producto,
							o.nombre_producto,
							o.estado,
							o.id_producto,
							o.nombre_producto,
							o.observacion,
							o.nombre_pais,
							o.fecha_aprobacion,
							o.fecha_finalizacion,
							o.id_operador_tipo_operacion,
							o.id_historial_operacion,
							t.nombre,
							t.id_area as codigo_area,
							t.codigo as codigo_tipo_operacion,
							a.nombre_area as area,
							a.tipo_area,
							a.superficie_utilizada,
							ss.provincia,
							ss.canton,
							ss.parroquia,
							ss.id_sitio,
							ss.nombre_lugar as sitio,
							ss.direccion,
							ss.referencia,
							ss.croquis,
							pao.estado as estado_area,
							pao.ruta_archivo,
							pao.id_area,
							pao.observacion as observacion_area,
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
		return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	}

    public function obtenerProductosPorIdOperadorTipoOperacion($arrayParametros)
    {
        $consulta = "SELECT
						string_agg(DISTINCT(o.estado), ', ') as estado, o.id_operacion, p.id_producto, nombre_comun, sp.nombre as nombre_subtipo, 
                        codificacion_subtipo_producto, tp.nombre as nombre_tipo, o.estado, o.estado_anterior
					FROM
						g_operadores.operaciones o,
						g_catalogos.productos p,
						g_catalogos.subtipo_productos sp,
						g_catalogos.tipo_productos tp
					WHERE
						o.id_producto = p.id_producto
						and p.id_subtipo_producto = sp.id_subtipo_producto
						and sp.id_tipo_producto = tp.id_tipo_producto
						and id_operador_tipo_operacion in (" . $arrayParametros['id_operador_tipo_operacion'] . ")
						and id_historial_operacion in (" . $arrayParametros['id_historial_operacion'] . ")
                        and o.estado = 'registrado'
                        GROUP BY o.id_operacion, p.id_producto, nombre_comun, sp.nombre , 
                        codificacion_subtipo_producto, tp.nombre , o.estado, o.estado_anterior;";

        
        return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
    }

    public function inactivarVehiculo($idOperadorTipoOperacion,$estadoNuevo,$estadoActual,$placaVehiculo){
	   
		   $consulta= "UPDATE 
							g_operadores.datos_vehiculos
						SET 
							estado_dato_vehiculo = '$estadoNuevo'												
						WHERE 
							id_dato_vehiculo = (SELECT 
													MAX(id_dato_vehiculo) 
												FROM 
													g_operadores.datos_vehiculos 
												WHERE 
													placa_vehiculo = '$placaVehiculo') 
													and estado_dato_vehiculo = '$estadoActual' 
													and id_operador_tipo_operacion = $idOperadorTipoOperacion;";
			return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
		}

    
    /**
	 * obtener tipo de operaciones para realizar revision desde aliactivo movil
	 */
	public function obtenerOperacionesRegistroOperadorPorEstado($arrayParametros){

	    $provincia = $arrayParametros['provincia'];
	    $idAreaTematica = $arrayParametros['idAreaTematica'];
	    $codigoTipoOperacion = $arrayParametros['codigoTipoOperacion'];
	   $estado = $arrayParametros['estado'];
	    $condicion = "";

	    $arrayToken = $this->lNegocioToken->validarToken(RUTA_PUBLIC_KEY_AGROSERVICIOS);

	    if ($arrayToken['estado'] == 'exito') {
	        $res = null;
	        $campos = "";
	        $consulta = "";
	        $condicion = "";
	        $agrupar = "";

    	    switch($idAreaTematica){

    	        case 'AI':
    	            
    	            switch($codigoTipoOperacion){
    	                
    	                case 'MDT':
    	                    
    	                    $campos = ", dv.id_dato_vehiculo
                                        , cttt.nombre AS tipo_tanque
                                        , UPPER(dv.placa_vehiculo) AS placa_vehiculo
                                        , dv.capacidad_vehiculo || ' ' || dv.codigo_unidad_medida AS capacidad_vehiculo
                                        , dv.fecha_creacion
                                        , dv.id_operador_tipo_operacion
                                        , dv.hora_inicio_recoleccion
                                        , dv.hora_fin_recoleccion";
    	                    $consulta = " INNER JOIN g_operadores.datos_vehiculos dv ON op.id_operador_tipo_operacion = dv.id_operador_tipo_operacion
                                        INNER JOIN (SELECT ic.* FROM g_administracion_catalogos.items_catalogo ic
                                        INNER JOIN g_administracion_catalogos.catalogos_negocio cn ON ic.id_catalogo_negocios = cn.id_catalogo_negocios) AS cttt ON cttt.id_item = dv.id_tipo_tanque_vehiculo";
    	                    $condicion = " and dv.estado_dato_vehiculo = 'activo'";
    	                    $agrupar = ", dv.id_dato_vehiculo, placa_vehiculo, tipo_tanque ";
    	                    
    	                break;
    	                
    	                case 'ACO':
    	                    $campos = ", ca.id_centro_acopio
                                        , ca.capacidad_instalada || ' ' || ca.codigo_unidad_medida AS capacidad_instalada
                                        , ca.codigo_unidad_medida
                                        , ca.numero_trabajadores
                                        , ctlab.nombre AS nombre_laboratorio
                                        , ca.numero_proveedores
                                        , ca.fecha_creacion
                                        , ca.id_operador_tipo_operacion
                                        , ca.estado_centro_acopio
                                        , ca.hora_recoleccion_maniana
                                        , ca.hora_recoleccion_tarde
                                        , ca.pertenece_mag";
    	                    $consulta = "INNER JOIN g_operadores.centros_acopio ca ON op.id_operador_tipo_operacion = ca.id_operador_tipo_operacion
                                         INNER JOIN (SELECT ic.* FROM g_administracion_catalogos.items_catalogo ic
                                                     INNER JOIN g_administracion_catalogos.catalogos_negocio cn ON ic.id_catalogo_negocios = cn.id_catalogo_negocios) AS ctlab ON ctlab.id_item = ca.id_laboratorio_leche";
    	                    $condicion = " and ca.estado_centro_acopio = 'activo'";
    	                    $agrupar = ", ca.id_centro_acopio, ctlab.nombre ";
    	                break;
    	                
    	            }
    	            
    	        break;
    	            	        
    	    }

    	   $consulta = "SELECT row_to_json (res) as res FROM ( SELECT array_to_json(array_agg(row_to_json(listado))) as cuerpo FROM (
                                SELECT
                                	operaciones.* ,
                                    (
                                    	SELECT jsonb_agg(tvd)
                                    	FROM (
                            	        	SELECT
                            						vd.id_vigencia_declarada
                            						, cvd.id_vigencia_documento
                            						, vd.valor_tiempo_vigencia_declarada
                            						, vd.tipo_tiempo_vigencia_declarada
                            						, CASE WHEN valor_tiempo_vigencia_declarada = 1 
                            						THEN 'Aprobado por ' || valor_tiempo_vigencia_declarada || 
                            							CASE WHEN tipo_tiempo_vigencia_declarada = 'anio' THEN ' año' WHEN tipo_tiempo_vigencia_declarada = 'mes' THEN ' mes' WHEN tipo_tiempo_vigencia_declarada = ' dia' THEN 'día'END
                            						WHEN valor_tiempo_vigencia_declarada != 1
                            						THEN 'Aprobado por ' || valor_tiempo_vigencia_declarada || 
                            							CASE WHEN tipo_tiempo_vigencia_declarada = 'anio' THEN ' años' WHEN tipo_tiempo_vigencia_declarada = 'mes' THEN ' meses' WHEN tipo_tiempo_vigencia_declarada = ' dia' THEN 'días'END
                            						END AS descripcion_vigencia
                            				FROM
                            					g_vigencia_documento.cabecera_vigencia_documento cvd,
                            					g_vigencia_documento.vigencia_declarada vd
                            				WHERE
                            					cvd.id_vigencia_documento = vd.id_vigencia_documento
                            					and cvd.etapa_vigencia = 'inspeccion'
                            				and operaciones.id_vigencia_documento = cvd.id_vigencia_documento ORDER BY 1 
                                    	) AS tvd
                                    ) AS vigencia
                                FROM (SELECT  
                                            op.id_operador_tipo_operacion 
                                        	,op.identificador_operador
                                        	, UPPER(COALESCE (o.razon_social, o.nombre_representante ||' '|| o.apellido_representante)) nombre_operador
                                        	, o.nombre_representante ||' '|| o.apellido_representante as representante_legal
                                        	, s.nombre_lugar AS nombre_sitio
                                            , s.provincia || ' - ' || s.canton || ' - ' || s.parroquia  AS ubicacion_sitio
                                            , o.telefono_uno || ' - ' || o.celular_uno || ' - ' || o.correo  AS contacto_operador
											, s.direccion AS direccion_sitio
                                        	, a.nombre_area
                                        	, top.nombre AS nombre_tipo_operacion
                                        	, op.estado
                                            , MIN(op.id_operacion) AS id_solicitud
                                        	, string_agg(DISTINCT(stp.nombre), ', ') as nombre_subtipo_producto
                                        	, string_agg(DISTINCT(p.nombre_comun), ', ') as nombre_productos
                                            , op.id_vigencia_documento
                                            " . $campos . "
                                        FROM 
                                            g_operadores.sitios s 
                                            INNER JOIN g_operadores.areas a ON s.id_sitio = a.id_sitio 
                                            INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area 
                                            INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion 
                                            INNER JOIN g_operadores.operadores o ON op.identificador_operador = o.identificador 
                                            INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion 
                                            INNER JOIN g_catalogos.productos p ON p.id_producto = op.id_producto 
                                            INNER JOIN g_catalogos.subtipo_productos stp ON stp.id_subtipo_producto = p.id_subtipo_producto
                                            INNER JOIN g_catalogos.localizacion l ON UPPER(s.provincia) = UPPER(l.nombre) and categoria = 1 
                                            " . $consulta . "
                                        WHERE 
                                            top.id_area || top.codigo in ('" . $idAreaTematica . $codigoTipoOperacion . "')
                                            AND op.estado IN (" . $estado . ")" . $condicion . "
                                            AND l.id_localizacion = " . $provincia . "
                                        GROUP BY 
                                            op.id_operador_tipo_operacion, op.identificador_operador, nombre_operador, representante_legal, nombre_sitio,
                                            ubicacion_sitio, direccion_sitio,
                                            a.nombre_area,
                                        	nombre_tipo_operacion,
                                            contacto_operador,
                                            op.estado, op.id_vigencia_documento" 
                                            . $agrupar . "
                                        ORDER BY op.identificador_operador ASC)	operaciones
                            ) as listado ) AS res;";
									
    	    $array = array();
    	    
    	    try {
    	        $res = $this->modeloOperaciones->ejecutarSqlNativo($consulta);
				
    	        $array['estado'] = 'exito';
    	        $array['mensaje'] = "Los datos han sido obtenidos satisfactoriamente";
    	        $cuerpo = json_decode($res->current()->res, true);
    	        $array['cuerpo'] = $cuerpo['cuerpo'] != null ? $cuerpo['cuerpo'] : [];
    	        echo json_encode($array);
    	    } catch (Exception $ex) {
    	        $array['estado'] = 'error';
    	        $array['mensaje'] = 'Error al obtener datos: ' . $ex;
    	        http_response_code(400);
    	        echo json_encode($array);
    	        throw new BuscarExcepcion($ex, array('archivo' => 'OperacionesLogicaNegocio', 'metodo' => 'buscarOperacionesPorProvinciaPorTipoOperacionPorEstado', 'consulta' => $consulta));
    	    }
 	    } else{
 	        echo json_encode($arrayToken);
 	    }
	}
	
	public function guardarResultadoInspeccion(Array $resultadoInspeccion){

	    try{
	        
	        $procesoIngreso = $this->modeloOperaciones->getAdapter()
	        ->getDriver()
	        ->getConnection();
	        $procesoIngreso->beginTransaction();

			$resultado = $resultadoInspeccion['estado'];
			$tipoSolicitud = $resultadoInspeccion['tipo_solicitud'];
			$idOperacion = $resultadoInspeccion['id_operacion'];
    	    $fechaActual = date("Y-m-d h:m:s");

			$modulosAgregados = "";
    	    $perfilesAgregados = "";
    	    
    	    $qOperacion = $this->buscar($idOperacion);
    	    $idOperadorTipoOperacion = $qOperacion->getIdOperadorTipoOperacion();
    	    $identificadorOperador = $qOperacion->getIdentificadorOperador();
    	    $idTipoOperacion = $qOperacion->getIdTipoOperacion();
    	    
    	    $qAreasOperador = $this->obtenerOperadorOperacionAreaInspeccion($idOperacion);
    	    $idArea = $qAreasOperador->current()->id_area;
    	    
    	    $qHistorialOperacion = $this->obtenerMaximoIdentificadorHistoricoOperacion($idOperadorTipoOperacion);
    	    $idHistorialOperacion = $qHistorialOperacion->current()->id_historial_operacion;

			switch ($tipoSolicitud){
				case 'Operadores' :{
					$actualizacionFechas = true;	    
					$arrayResultados = array('noHabilitado','subsanacion', 'subsanacionRepresentanteTecnico','subsanacionProducto');//Verificar si existe subsanacion
							
					if (!in_array($resultado, $arrayResultados)) {
						
						if($resultado == 'registrado'){
							//TODO:VERIFICAR LAS VIGENCIAS QUE VIENEN
							
							$idVigenciaDeclarada = null;
						}else{
							$idVigenciaDeclarada = $resultado;
							$resultado = 'registrado';
						}
					}

					if($resultado == 'registrado'){      
    	       
						$idflujoOperacion = $this->obtenerIdFlujoXOperacion($idOperacion);
						$idFlujoActual = $this->obtenerEstadoActualFlujoOperacion($idflujoOperacion->current()->id_flujo_operacion, 'inspeccion');
						if($idFlujoActual->count()){
							$estado = $this->obtenerEstadoFlujoOperacion($idflujoOperacion->current()->id_flujo_operacion, $idFlujoActual->current()->predecesor);
							
							if($qOperacion->getModuloProvee() == 'moduloExterno' && $estado->current()->estado == 'cargarProducto'){
								$estado = $this->obtenerEstadoFlujoOperacion($idflujoOperacion->current()->id_flujo_operacion, $idFlujoActual->current()->predecesor + 1);
							}
						}else{
							$estado = $this->obtenerEstadoActualFlujoOperacion($idflujoOperacion->current()->id_flujo_operacion, 'registrado');
							
						}
									
						$idVigenciaDocumento = null;
						
						if($qOperacion->getProcesoModificacion() != 't'){
							
							$valorVigencia = null;
							$tipoTiempoVigencia = null;
							if($idVigenciaDeclarada != null){
								$qVigenciaDeclarada = $this->obtenerVigenciaDeclaradaPorIdVigenciaDeclarada($idVigenciaDeclarada);
								$valorVigencia = $qVigenciaDeclarada->current()->valor_tiempo_vigencia_declarada;
								$idVigenciaDocumento = $qVigenciaDeclarada->current()->id_vigencia_documento;
								$tipoTiempoVigenciaDocumento = $qVigenciaDeclarada->current()->tipo_tiempo_vigencia_declarada;
								$tipoTiempoVigencia = $this->transformarvalorTipoVigencia($tipoTiempoVigenciaDocumento);
							}
							
							$arrayVerificarOperaciones = array('identificador_operador' => $identificadorOperador
																, 'id_tipo_operacion' => $idTipoOperacion
																, 'id_area' => $idArea
																, 'estado' => 'porCaducar'
																, 'id_vigencia_documento' => $idVigenciaDocumento
																);
							
							$qExistenciaOperacion = $this->verificarExistenciaOperaciones($arrayVerificarOperaciones);
							
							$arrayParametros = array('id_operador_tipo_operacion' => $idOperadorTipoOperacion
													, 'id_historial_operacion' => $idHistorialOperacion
													, 'valor_vigencia' => $valorVigencia
													, 'tipo_tiempo_vigencia' => $tipoTiempoVigencia
													, 'id_vigencia_documento' => $idVigenciaDocumento	               
													);
												
							if(!isset($qExistenciaOperacion->current->id_operacion)){
								$this->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($arrayParametros);
								if($idVigenciaDocumento != null){
									$this->actualizarFechaFinalizacionOperaciones($arrayParametros);
								}
								
							}else{
								
								$arrayParametrosOperacionExistente = array('id_operador_tipo_operacion' => $qExistenciaOperacion->current()->id_operador_tipo_operacion
																			, 'id_historial_operacion' => $qExistenciaOperacion->current()->id_historial_operacion
																			, 'id_vigencia_documento' => $idVigenciaDocumento
																			, 'valor_vigencia' => $valorVigencia
																			, 'tipo_tiempo_vigencia' => $tipoTiempoVigencia
																			, 'id_vigencia_documento' => $idVigenciaDocumento
																			);
								
								$this->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($arrayParametrosOperacionExistente);
								if($idVigenciaDocumento != null){
									$this->actualizarFechaFinalizacionOperaciones($arrayParametrosOperacionExistente);
								}
								
								$arrayParametrosOperacionActualizar = array('id_operador_tipo_operacion' => $qExistenciaOperacion->current()->id_operador_tipo_operacion
																		   , 'id_historial_operacion' => $qExistenciaOperacion->current()->id_historial_operacion
																		   , 'estado' => 'noHabilitado'
																		   , 'observacion' => 'Cambio de estado no habilitado por registro de nueva operación ' . $fechaActual
																		   , 'id_vigencia_documento' => $idVigenciaDocumento
																		   );
								
								$this->actualizarEstadoPorOperadorTipoOperacionHistorial($arrayParametrosOperacionActualizar);
								$this->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($arrayParametrosOperacionActualizar);
								$this->actualizarEstadoTipoOperacionPorIndentificadorSitio($arrayParametrosOperacionActualizar);
							
							}
							
						}else{
							
							$actualizacionFechas = false;
							
						}
						
						$qcodigoTipoOperacion = $this->obtenerCodigoTipoOperacion($idOperacion);
						$codigoArea = $qcodigoTipoOperacion->current()->codigo;
						$idArea = $qcodigoTipoOperacion->current()->id_area;
					  
						switch ($estado->current()->estado){
						
							case 'registrado':
								
								$arrayParametros = array('id_operador_tipo_operacion' => $idOperadorTipoOperacion
																			, 'id_historial_operacion' => $idHistorialOperacion
																			, 'estado' => $estado->current()->estado
																			, 'observacion' => 'Solicitud aprobada ' . $fechaActual
																			, 'id_vigencia_documento' => $idVigenciaDocumento
																			, 'actualizar_certificado' => 'SI'
																			, 'proceso_modificacion' => ''
																			);
								
								$this->actualizarEstadoPorOperadorTipoOperacionHistorial($arrayParametros);
								
								if($actualizacionFechas){
									$this->actualizarFechaAprobacionOperaciones($arrayParametros);
								}else{
									$this->actualizarFechaAprobacionOperacionesProcesoModificacion($arrayParametros);
								}
								$this->cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial($arrayParametros);
								$this->actualizarProcesoActualizacionOperacion($arrayParametros);
								
										switch ($idArea){
									
											case 'AI':
												
												switch ($codigoArea){
													
													case 'MDT':
													case 'ACO':
													
														$modulosAgregados .= "('PRG_AUM_CAP_INST'),('PRG_DOSSIER_PEC'),";
														$perfilesAgregados .= "('PFL_AUM_CAP_INST'),";
														  
														$this->cambiarEstadoActualizarCertificado($arrayParametros);
														
													break;
																										   
												}
											   
											break;
												
										}
								  
							 
								
							break;
							
						}
						
						$this->actualizarEstadoTipoOperacionPorIndentificadorSitio($arrayParametros);
						
						
					}else{
						$idVigenciaDocumento = '';
						   
						$qHistorialOperacion = $this->obtenerMaximoIdentificadorHistoricoOperacion($idOperadorTipoOperacion);
						$idHistorialOperacion = $qHistorialOperacion->current()->id_historial_operacion;
						
						$arrayParametros = array('id_operador_tipo_operacion' => $idOperadorTipoOperacion
							, 'id_historial_operacion' => $idHistorialOperacion, 'id_vigencia_documento' => $idVigenciaDocumento
							
						);
						
						$this->actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($arrayParametros);
		
						$arrayParametrosOperacionActualizar = array('id_operador_tipo_operacion' => $idOperadorTipoOperacion
							, 'id_historial_operacion' => $idHistorialOperacion
							, 'estado' => $resultado
							, 'observacion' => $resultadoInspeccion['observacion_revision']
							, 'id_vigencia_documento' => ''
						);
						
						$this->actualizarEstadoPorOperadorTipoOperacionHistorial($arrayParametrosOperacionActualizar);
			
						$this->actualizarEstadoTipoOperacionPorIndentificadorSitio($arrayParametrosOperacionActualizar);
		
					}
					   

					
				}
				break;
				case 'CertificacionBPA' :{
					if($resultado == 'Aprobado'){				
						
						$qSolicitud = $this->lNegocioSolicitudes->buscar($idSolicitud);
						$qOperacion->getIdOperadorTipoOperacion();
						$tipoExplotacion = $qSolicitud->getTipoExplotacion();
						$identificador = $qSolicitud->getIdentificadorOperador();
						
						$solicitudBPA = THIS lNegocioSolicitudes $ccb->abrirSolicitud($conexion, $idSolicitud);
						
						$fechaAuditoriaReal = pg_fetch_result($solicitudBPA, 0, 'fecha_auditoria');
						$fechaAuditoriaComplementaria = pg_fetch_result($solicitudBPA, 0, 'fecha_auditoria_complementaria');
						
						if($fechaAuditoriaComplementaria != null){
							$fechaAuditoria = $fechaAuditoriaComplementaria;
						}else{
							$fechaAuditoria = $fechaAuditoriaReal;
						}
						
						//Cambiar de estado a los sitios de la solicitud
						$ccb->actualizarEstadoSitiosSolicitud($conexion, $idSolicitud, $resultado);
						
						//poner las fechas de aprobacion de inicio y fin (3 años)
						$ccb->generarFechasVigencia($conexion, $idSolicitud, $_POST['tipo_solicitud'], $fechaAuditoria);
						
						//crear el numero de certificado y guardar en el registro (crear funcion de numero certificado y de actualizar en registro
						$certificado = '';
						
						
						
						
						switch($tipoExplotacion){
							case 'SA':
								$area= 'PP';
								break;
							case 'SV':
								$area= 'PA';
								break;
							case 'AI':
								$area= 'PO';
								break;
						}
						
						//verificar la provincia del tecnico y buscar en localizacion el nombre de la provincia y ubicar el numero de zona
						//aumentar a dos digitos con ceros
						$localizacion = pg_fetch_result($cc->obtenerZonaLocalizacion($conexion, $_SESSION['idProvincia'], 1), 0, 'zona');
						$codigoLocalizacion = str_pad($localizacion, 2, "0", STR_PAD_LEFT);
						
						//buscar la combinacion del codigo hasta antes de la provincia y ver el numero para crear un secuencial
						$anio = date("Y");
						$formato = 'AGRO-CBPA-'.$area.'-'.$identificador;//.$anio.'-'
						$secuencial = $ccb->generarNumeroCertificado($conexion, $formato);
						
						//generarNumeroCertificado
						//AGRO-CBPA-PO-2020-05-00001
						//$certificado = $formato . $codigoLocalizacion . '-' . $secuencial;
						$certificado = $formato;
						
						//guardar el código del certificado y el secuencial
						$ccb->actualizarSecuencialCertificado($conexion, $idSolicitud, $secuencial, $certificado);
						
						//Creación de Certificado PDF
						$jru = new ControladorReportes();
						
						//mandar las rutas al mvc para jrxml
						$ReporteJasper= '/aplicaciones/mvc/modulos/CertificacionBPA/vistas/reportes/CertificadoNacional.jrxml';
						$salidaReporte= '/aplicaciones/mvc/modulos/CertificacionBPA/archivos/certificados/bpa_'.$idSolicitud.'.pdf';
						$rutaArchivo= 'aplicaciones/mvc/modulos/CertificacionBPA/archivos/certificados/bpa_'.$idSolicitud.'.pdf';
						
						$firmaResponsable = pg_fetch_assoc($cc->obtenerFirmasResponsablePorProvincia($conexion, $_SESSION['nombreProvincia'], 'AI'));
						
						$parameters['parametrosReporte'] = array(
							'idSolicitud'=>(int)$idSolicitud,
							'identificador'=>$firmaResponsable['identificador']
						);
						
						$jru->generarReporteJasper($ReporteJasper,$parameters,$conexion,$salidaReporte,'CertificacionBPA');
						$ccb->guardarRutaCertificado($conexion, $idSolicitud, $rutaArchivo);
						
						$rutaArchivo = $constg::RUTA_SERVIDOR_OPT . '/' . $constg::RUTA_APLICACION . '/' .$rutaArchivo;
						
						//Firma Electrónica
						$parametrosFirma = array(
							'archivo_entrada'=>$rutaArchivo,
							'archivo_salida'=>$rutaArchivo,
							'identificador'=>$firmaResponsable['identificador'],
							'razon_documento'=>'Certificado BPA',
							'tabla_origen'=>'g_certificacion_bpa.solicitudes',
							'campo_origen'=>'ruta_certificado',
							'id_origen'=>$idSolicitud,
							'estado'=>'Por atender',
							'proceso_firmado'=>'NO'
						);
						
						//Guardar registro para firma
						$cfd->ingresoFirmaDocumento($conexion, $parametrosFirma);
					
					}else if($resultado == 'Rechazado'){
						//Cambiar de estado a los sitios de la solicitud
						$ccb->actualizarEstadoSitiosSolicitud($conexion, $idSolicitud, $resultado);
					}

				}
				break;
			}











	    
    	   
    	         
		

  
    	    
    	    
    	   
    	
    	    // Construye el array para el registro de informacion en tablas de revision de solicitudes
    	    $arrayDatosRevisor = array(
    	        'identificador_inspector' => $resultadoInspeccion['identificador_revisor'],
    	        'fecha_asignacion' => 'now()',
    	        'identificador_asignante' => $resultadoInspeccion['identificador_revisor'],
    	        'tipo_solicitud' => $tipoSolicitud,
    	        'tipo_inspector' => 'Técnico',
    	        'id_operador_tipo_operacion' => $idOperadorTipoOperacion,
    	        'id_historial_operacion' => $idHistorialOperacion,
    	        'id_solicitud' => $resultadoInspeccion['id_solicitud'],
    	        'estado' => 'Técnico',
    	        'fecha_inspeccion' => 'now()',
    	        'observacion' => $resultadoInspeccion['observacion_revision'],
    	        'estado_siguiente' => $resultado,
    	        'orden' => 1
    	    );
    
    	    $this->lNegocioAsignacionInspector->guardar($arrayDatosRevisor);

    	    $procesoIngreso->commit();
    	    
	    }catch (GuardarExcepcion $ex){
	        $procesoIngreso->rollback();
	        throw new \Exception($ex->getMessage());
	    }
	    
	}
	
	public function obtenerMaximoIdentificadorHistoricoOperacion($idOperadorTipoOperacion){
	    
	    $consulta = "SELECT
							max(id_historial_operacion) as id_historial_operacion
						FROM
							g_operadores.historial_operaciones
						WHERE
							 id_operador_tipo_operacion = $idOperadorTipoOperacion;";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	//TODO:Verificar si se hace logica de negocio
	public function obtenerIdFlujoXOperacion($idOperacion){
	    
	    $consulta = "SELECT
	        			top.id_flujo_operacion
	        		FROM
	        			g_operadores.operaciones op,
						g_catalogos.tipos_operacion top
	        		WHERE
						op.id_tipo_operacion = top.id_tipo_operacion
						and op.id_operacion = " . $idOperacion . ";";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	//TODO:Verificar si se hace logica de negocio
	public function obtenerEstadoActualFlujoOperacion($idFlujo, $estado){
	    
	    $consulta = "SELECT
	        			*
	        		FROM
	        			g_operadores.flujos_operaciones
	        		WHERE
	        			id_flujo = " . $idFlujo . " 
	        			and estado = '" . $estado . "';";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	//TODO:Verificar si se hace logica de negocio
	public function obtenerEstadoFlujoOperacion($idFlujo, $idFase){
	    
	    $consulta = "SELECT
	        			*
	        		FROM
	        			g_operadores.flujos_operaciones
	        		WHERE
	        			id_flujo = " . $idFlujo . "
                        and id_fase = " . $idFase . ";";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	//TODO:Verificar si se hace logica de negocio
	public function obtenerVigenciaDeclaradaPorIdVigenciaDeclarada($idVigenciaDeclarada){
	    
	    $consulta = "SELECT
						*
					FROM
						g_vigencia_documento.vigencia_declarada
					WHERE
						id_vigencia_declarada = " . $idVigenciaDeclarada . ";";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	//TODO:Verificar si se hace logica de negocio
	public function transformarvalorTipoVigencia($tipoTiempoVigencia){
	    
	    switch ($tipoTiempoVigencia){
	        
	        case 'anio':
	            $tipoTiempo = 'year';
	            break;
	            
	        case 'mes':
	            $tipoTiempo = 'month';
	            break;
	            
	        case 'dia':
	            $tipoTiempo = 'day';
	            break;
	            
	    }
	    
	    return $tipoTiempo;
	}
	
	public function verificarExistenciaOperaciones($arrayParametros){
	    
	    $identificadorOperador = $arrayParametros['identificador_operador'];
	    $idTipoOperacion = $arrayParametros['id_tipo_operacion'];
	    $idArea = $arrayParametros['id_area'];
	    $estado = $arrayParametros['estado'];
	    $idVigenciaDocumento = $arrayParametros['id_vigencia_documento'] != "" ? "'" . $arrayParametros['id_vigencia_documento'] . "'" : "NULL";
	    
	    $consulta = "SELECT
							*
						FROM
							g_operadores.operaciones op,
							g_operadores.productos_areas_operacion pao
						WHERE
							op.id_operacion = pao.id_operacion 
                            and	op.id_tipo_operacion = " . $idTipoOperacion . " 
                            and	pao.id_area = " . $idArea . " 
                            and op.identificador_operador = '" . $identificadorOperador . "' 
                            and op.estado = '" . $estado . "' 
                            and (" . $idVigenciaDocumento . " is NULL or op.id_vigencia_documento = " . $idVigenciaDocumento. ")
                        ORDER BY 1
                        LIMIT 1;";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	public function actualizarEstadoAnteriorPorOperadorTipoOperacionHistorial($arrayParametros){
	    
	    $idOperadorTipoOperacion = $arrayParametros['id_operador_tipo_operacion'];
	    $idHistorialOperacion = $arrayParametros['id_historial_operacion'];
	    $idVigenciaDocumento = $arrayParametros['id_vigencia_documento'] != "" ? "'" . $arrayParametros['id_vigencia_documento'] . "'" : "NULL";
	    
																	  
  
	    $consulta = "UPDATE
						g_operadores.operaciones o
					SET
						estado_anterior = op.estado
					FROM
						g_operadores.operaciones op
					WHERE
						o.id_operacion = op.id_operacion
						and op.id_operador_tipo_operacion = " . $idOperadorTipoOperacion . " 
						and op.id_historial_operacion = " . $idHistorialOperacion . "
                        and (" . $idVigenciaDocumento . " is NULL or op.id_vigencia_documento = " . $idVigenciaDocumento. ")
						and op.estado not in ('noHabilitado');";
													 
															
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	public function actualizarFechaFinalizacionOperaciones($arrayParametros){
	    
	    $idOperadorTipoOperacion = $arrayParametros['id_operador_tipo_operacion'];
	    $idHistorialOperacion = $arrayParametros['id_historial_operacion'];
	    $valorVigencia = $arrayParametros['valor_vigencia'];
	    $tipoTiempoVigencia = $arrayParametros['tipo_tiempo_vigencia'];
	    $idVigenciaDocumento = $arrayParametros['id_vigencia_documento'] != "" ? "'" . $arrayParametros['id_vigencia_documento'] . "'" : "NULL";
	    
	    $consulta = "UPDATE
						g_operadores.operaciones
					SET
						fecha_finalizacion = now() + interval '" . $valorVigencia . "' " . $tipoTiempoVigencia . "
					WHERE
						id_operador_tipo_operacion = " . $idOperadorTipoOperacion . "
                        and id_historial_operacion = " . $idHistorialOperacion . "
                        and	(" . $idVigenciaDocumento . " is NULL or id_vigencia_documento = " . $idVigenciaDocumento . ");";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	public function actualizarEstadoPorOperadorTipoOperacionHistorial($arrayParametros){
	    
	    $idOperadorTipoOperacion = $arrayParametros['id_operador_tipo_operacion'];
	    $idHistorialOperacion = $arrayParametros['id_historial_operacion'];
	    $estado = $arrayParametros['estado'];
	    $observacion = $arrayParametros['observacion'];
	    $idVigenciaDocumento = $arrayParametros['id_vigencia_documento'] != "" ? "'" . $arrayParametros['id_vigencia_documento'] . "'" : "NULL";
	    
	    $consulta = "UPDATE
						g_operadores.operaciones
					SET
						estado = '" . $estado . "',
						observacion = '" . $observacion . "',
                        fecha_modificacion = now()
					WHERE
						id_operador_tipo_operacion = " . $idOperadorTipoOperacion . "
						and	id_historial_operacion = " . $idHistorialOperacion . "
                        and (" . $idVigenciaDocumento . " is NULL or id_vigencia_documento = " . $idVigenciaDocumento . ")
						and estado not in ('noHabilitado');";
	  
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	public function cambiarEstadoAreaOperacionPorPorOperadorTipoOperacionHistorial ($arrayParametros){
	    
	    $idOperadorTipoOperacion = $arrayParametros['id_operador_tipo_operacion'];
	    $idHistorialOperacion = $arrayParametros['id_historial_operacion'];
	    $idVigenciaDocumento = $arrayParametros['id_vigencia_documento'] != "" ? "'" . $arrayParametros['id_vigencia_documento'] . "'" : "NULL";
	    
	    $consulta = "UPDATE
													g_operadores.productos_areas_operacion as pao
												SET
													estado = o.estado,
													observacion = o.observacion
												FROM
													g_operadores.operaciones o
												WHERE
													pao.id_operacion = o.id_operacion
													and id_operador_tipo_operacion = " . $idOperadorTipoOperacion . "
													and id_historial_operacion = " . $idHistorialOperacion . "
													and	(" . $idVigenciaDocumento . " is NULL or id_vigencia_documento = " . $idVigenciaDocumento . ")
													and o.estado not in ('noHabilitado');";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	public function actualizarEstadoTipoOperacionPorIndentificadorSitio($arrayParametros){
	    
	    $idOperadorTipoOperacion = $arrayParametros['id_operador_tipo_operacion'];
	    $estado = $arrayParametros['estado'];
	    
	    $consulta = "UPDATE
							g_operadores.operadores_tipo_operaciones
						SET
							estado = '" . $estado . "',
							fecha_modificacion = 'now()'
						WHERE
							id_operador_tipo_operacion = " . $idOperadorTipoOperacion . ";";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	public function obtenerCodigoTipoOperacion($idOperacion){
	   	    
	    $consulta = "SELECT
						top.codigo,
						top.id_tipo_operacion,
						top.id_area,
						top.nombre
					FROM
						g_operadores.operaciones op,
						g_catalogos.tipos_operacion top
					WHERE
						op.id_tipo_operacion= top.id_tipo_operacion
						and op.id_operacion = " . $idOperacion . ";";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	public function actualizarFechaAprobacionOperaciones($arrayParametros){
	    
	    $idOperadorTipoOperacion = $arrayParametros['id_operador_tipo_operacion'];
	    $idHistorialOperacion = $arrayParametros['id_historial_operacion'];
	    $idVigenciaDocumento = $arrayParametros['id_vigencia_documento'] != "" ? "'" . $arrayParametros['id_vigencia_documento'] . "'" : "NULL";
	    
	    $consulta = "UPDATE
						g_operadores.operaciones
					SET
						fecha_aprobacion = now()
					WHERE
						id_operador_tipo_operacion = " . $idOperadorTipoOperacion . "
                        and id_historial_operacion = " . $idHistorialOperacion . "
                        and (" . $idVigenciaDocumento . " is NULL or id_vigencia_documento = " . $idVigenciaDocumento . ")
						and estado not in ('noHabilitado');";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	public function actualizarFechaAprobacionOperacionesProcesoModificacion($arrayParametros){
	    
	    $idOperadorTipoOperacion = $arrayParametros['id_operador_tipo_operacion'];
	    $idHistorialOperacion = $arrayParametros['id_historial_operacion'];
	    $idVigenciaDocumento = $arrayParametros['id_vigencia_documento'] != "" ? "'" . $arrayParametros['id_vigencia_documento'] . "'" : "NULL";
	    
	    $consulta = "UPDATE
						g_operadores.operaciones
					SET
						fecha_aprobacion = now()
					WHERE
						id_operador_tipo_operacion = " . $idOperadorTipoOperacion . " 
                        and id_historial_operacion = " . $idHistorialOperacion . " 
                        and (" . $idVigenciaDocumento . " is NULL or id_vigencia_documento = " . $idVigenciaDocumento . ")
						and estado not in ('noHabilitado')
						and fecha_aprobacion is null;";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	public function actualizarProcesoActualizacionOperacion($arrayParametros){
	    
	    $idOperadorTipoOperacion = $arrayParametros['id_operador_tipo_operacion'];
	    $idHistorialOperacion = $arrayParametros['id_historial_operacion'];
	    $procesoModiciacion = $arrayParametros['proceso_modificacion'] != "" ? "'" . $arrayParametros['proceso_modificacion'] . "'" : "null";
	    
	    $consulta = "UPDATE
                            g_operadores.operaciones
                    SET
                            proceso_modificacion = " . $procesoModiciacion . "
                    WHERE
                            id_operador_tipo_operacion = " . $idOperadorTipoOperacion . " 
                            and id_historial_operacion = " . $idHistorialOperacion . "
							and estado not in ('noHabilitado');";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	public function cambiarEstadoActualizarCertificado($arrayParametros){
	    
	    $idOperadorTipoOperacion = $arrayParametros['id_operador_tipo_operacion'];
	    $idHistorialOperacion = $arrayParametros['id_historial_operacion'];
	    $actualizarCertificado = $arrayParametros['actualizar_certificado'];
	    
	    $consulta = "UPDATE
                        g_operadores.operaciones
                     SET
                        actualizar_certificado = '" . $actualizarCertificado . "'
                     WHERE
                        id_operador_tipo_operacion = " . $idOperadorTipoOperacion . "
                        and id_historial_operacion = " . $idHistorialOperacion . ";";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	    
	}
	
	public function obtenerOperadorOperacionAreaInspeccion($idOperacion){
	    
	    $consulta = "SELECT
						a.nombre_area,
						a.tipo_area,
						tp.nombre as nombre_operacion,
                        tp.id_area as area_operacion,
						a.id_area,
						a.superficie_utilizada,
						op.id_operacion
					FROM
						g_operadores.operaciones op,
						g_operadores.productos_areas_operacion pao,
						g_operadores.areas a,
						g_catalogos.tipos_operacion tp
					WHERE
						op.id_operacion = pao.id_operacion and
						pao.id_area = a.id_area and
						op.id_tipo_operacion = tp.id_tipo_operacion and
						op.id_operacion = " . $idOperacion . ";";
	    
	    return $this->modeloOperaciones->ejecutarSqlNativo($consulta);
	
	}



	
}