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
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Date;

class OperacionesLogicaNegocio implements IModelo
{

    private $modeloOperaciones = null;

    private $lNegocioAsignacionInspector = null;
    
    private $modeloDatosVehiculoTransporteAnimales = null;    
    private $lNegocioDatosVehiculoTransporteAnimales = null;
    
    private $modeloVehiculoTransporteAnimalesExprirado = null;    
    private $lNegocioVehiculoTransporteAnimalesExpirado = null;
    
    private $lNegocioTiposOperacion = null;    
    private $modeloTiposOperacion = null;

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
        
        $this->lNegocioDatosVehiculoTransporteAnimales = new DatosVehiculoTransporteAnimalesLogicaNegocio();
        $this->modeloVehiculoTransporteAnimales = new DatosVehiculoTransporteAnimalesModelo();
        
        $this->modeloVehiculoTransporteAnimalesExprirado = new VehiculoTransporteAnimalesExpiradoModelo();
        $this->lNegocioVehiculoTransporteAnimalesExpirado = new VehiculoTransporteAnimalesExpiradoLogicaNegocio();
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
    
}