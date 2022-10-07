<?php
/**
 * Lógica del negocio de ExportadoresProductosModelo
 *
 * Este archivo se complementa con el archivo ExportadoresProductosControlador.
 *
 * @author  AGROCALIDAD
 * @date    2020-07-04
 * @uses    ExportadoresProductosLogicaNegocio
 * @package CertificadoFitosanitario
 * @subpackage Modelos
 */
namespace Agrodb\CertificadoFitosanitario\Modelos;

use Agrodb\CertificadoFitosanitario\Modelos\IModelo;
use Agrodb\Catalogos\Modelos\ProductosLogicaNegocio;
use Agrodb\Catalogos\Modelos\ProductosModelo;

class ExportadoresProductosLogicaNegocio implements IModelo
{

    private $modeloExportadoresProductos = null;

    private $lNegocioProductos = null;

    private $modeloProductos = null;

    /**
     * Constructor
     *
     * @retorna void
     */
    public function __construct()
    {
        $this->modeloExportadoresProductos = new ExportadoresProductosModelo();

        $this->lNegocioProductos = new ProductosLogicaNegocio();
        $this->modeloProductos = new ProductosModelo();
    }

    /**
     * Guarda el registro actual
     *
     * @param array $datos
     * @return int
     */
    public function guardar(Array $datos)
    {
        switch ($datos['estado_exportador_producto']) {

            case '':
                {

                    $datos['estado_exportador_producto'] = "Creado";

                    break;
                }

            case 'FechaConfirmada':
                {
                    $datos['identificador_revision'] = $_SESSION['usuario'];
                    $datos['fecha_revision'] = 'now()';
                    $datos['observacion_revision'] = 'Hora de inspección asignada';

                    break;
                }

            case 'InspeccionAprobada':
                {
                    $datos['identificador_revision'] = $_SESSION['usuario'];
                    $datos['fecha_revision'] = 'now()';

                    break;
                }

            case 'DocumentalAprobada':
                {
                    $datos['identificador_revision'] = $_SESSION['usuario'];
                    $datos['fecha_revision'] = 'now()';

                    break;
                }

            case 'DevueltoTecnico':
                {
                    $datos['identificador_revision'] = $_SESSION['usuario'];
                    $datos['fecha_revision'] = 'now()';

                    break;
                }

            case 'Subsanacion':
                {
                    $datos['identificador_revision'] = $_SESSION['usuario'];
                    $datos['fecha_revision'] = 'now()';

                    break;
                }

            default:
                {
                    $datos['identificador_revision'] = $_SESSION['usuario'];
                    $datos['fecha_revision'] = 'now()';

                    break;
                }
        }

        $tablaModelo = new ExportadoresProductosModelo($datos);

        $datosBd = $tablaModelo->getPrepararDatos();

        if ($tablaModelo->getIdExportadorProducto() != null && $tablaModelo->getIdExportadorProducto() > 0) {
            return $this->modeloExportadoresProductos->actualizar($datosBd, $tablaModelo->getIdExportadorProducto());
        } else {
            unset($datosBd["id_exportador_producto"]);
            return $this->modeloExportadoresProductos->guardar($datosBd);
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
        $this->modeloExportadoresProductos->borrar($id);
    }

    /**
     *
     * Buscar un registro de con la clave primaria
     *
     * @param int $id
     * @return ExportadoresProductosModelo
     */
    public function buscar($id)
    {
        return $this->modeloExportadoresProductos->buscar($id);
    }

    /**
     * Busca todos los registros
     *
     * @return array|ResultSet
     */
    public function buscarTodo()
    {
        return $this->modeloExportadoresProductos->buscarTodo();
    }

    /**
     * Busca una lista de acuerdo a los parámetros <params> enviados.
     *
     * @return array|ResultSet
     */
    public function buscarLista($where = null, $order = null, $count = null, $offset = null)
    {
        return $this->modeloExportadoresProductos->buscarLista($where, $order, $count, $offset);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     *
     * @return array|ResultSet
     */
    public function buscarExportadoresProductos()
    {
        $consulta = "SELECT * FROM " . $this->modeloExportadoresProductos->getEsquema() . ". exportadores_productos";
        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos del operador
     * de acuerdo al identificador del operador
     *
     * @return array|ResultSet
     */
    public function obtenerDatosOperadorPorIdentificador($arrayParametros)
    {
        $consulta = "SELECT
						identificador as identificador_operador,
						case when razon_social = '' then nombre_representante ||' '|| apellido_representante else razon_social end nombre_operador,
                        direccion as direccion_operador
    				FROM
    					g_operadores.operadores
                    WHERE identificador = '" . $arrayParametros['identificadorOperador'] . "'
					ORDER BY 2;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos de tipos de productos
     * de acuerdo al identificador del operador y el tipo de solicitud del certificado (ornamentales, musaceas)
     *
     * @return array|ResultSet
     */
    public function obtenerTipoProductoPorOperadorPorTipoSolicitud($arrayParametros)
    {
        $identificadorOperador = $arrayParametros['identificadorOperador'];
        $tipoSolicitud = $arrayParametros['tipoSolicitud'];
        $tipoOperacion = "";

        switch ($tipoSolicitud) {

            case "otros":
                $tipoSolicitud = "('otros', 'musaceas')";
                $tipoOperacion = "('ACOSV')"; // "('ACOSV', 'COMSV', 'EXPSV', 'EXBSV')";
                break;
            case "musaceas":
                $tipoSolicitud = "('musaceas')";
                $tipoOperacion = "('ACOSV', 'EXPSV', 'EXBSV')";
                break;
            case "ornamentales":
                $tipoSolicitud = "('ornamentales')";
                $tipoOperacion = "('ACOSV', 'COMSV')";
                break;
        }

        $consulta = "SELECT DISTINCT
                        tp.id_tipo_producto
                        , tp.nombre as nombre_tipo_producto
                        , tp.estado
                    FROM 
                        g_catalogos.tipo_productos tp
                        INNER JOIN g_catalogos.subtipo_productos stp ON tp.id_tipo_producto = stp.id_tipo_producto
                        INNER JOIN g_catalogos.productos p ON stp.id_subtipo_producto = p.id_subtipo_producto
                        INNER JOIN g_operadores.operaciones op ON p.id_producto = op.id_producto
                        INNER JOIN g_operadores.productos_areas_operacion pao ON op.id_operacion = pao.id_operacion
                        INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                    WHERE
                        tp.estado = 1
                        and p.clasificacion in " . $tipoSolicitud . "
                        and op.identificador_operador = '" . $identificadorOperador . "'
                        and op.estado in ('registrado', 'registradoObservacion')
                        and top.codigo || top.id_area in " . $tipoOperacion . "
                    ORDER BY tp.nombre ASC;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos de subtipos de productos
     * de acuerdo al tipo de producto
     *
     * @return array|ResultSet
     */
    public function obtenerSubtiposProductoOperadorPorIdTipoProductoPorTipoSolicitud($arrayParametros)
    {
        $identificadorOperador = $arrayParametros['identificadorOperador'];
        $idTipoProducto = $arrayParametros['idTipoProducto'];
        $tipoSolicitud = $arrayParametros['tipoSolicitud'];
        $tipoOperacion = "";

        $tipoOperacion = "";

        switch ($tipoSolicitud) {

            case "otros":
                $tipoSolicitud = "('otros', 'musaceas')";
                $tipoOperacion = "('ACOSV')"; // "('ACOSV', 'COMSV', 'EXPSV', 'EXBSV')";
                break;
            case "musaceas":
                $tipoSolicitud = "('musaceas')";
                $tipoOperacion = "('ACOSV', 'EXPSV', 'EXBSV')";
                break;
            case "ornamentales":
                $tipoSolicitud = "('ornamentales')";
                $tipoOperacion = "('ACOSV', 'COMSV')";
                break;
        }

        $consulta = "SELECT DISTINCT
                    	stp.id_subtipo_producto
                    	, stp.nombre as nombre_subtipo_producto
                    FROM
                    	g_catalogos.subtipo_productos stp
                    	INNER JOIN g_catalogos.productos p ON stp.id_subtipo_producto = p.id_subtipo_producto
                    	INNER JOIN g_operadores.operaciones op ON p.id_producto = op.id_producto
                    	INNER JOIN g_operadores.productos_areas_operacion pao ON op.id_operacion = pao.id_operacion
                    	INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                    WHERE
                    	stp.id_tipo_producto = " . $idTipoProducto . "
                    	and p.clasificacion in " . $tipoSolicitud . "
                    	and op.identificador_operador = '" . $identificadorOperador . "'
                    	and op.estado in ('registrado', 'registradoObservacion')
                    	and top.codigo || top.id_area in " . $tipoOperacion . "
                    ORDER BY stp.nombre ASC;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos de productos
     * de acuerdo al subtipo de producto y verificar si el producto posee requisitos asignados para el país de desino seleccionado
     *
     * @return array|ResultSet
     */
    public function obtenerProductosOperadorPorIdSubtipoProductoPorTipoSolicitudPorPais($arrayParametros)
    {
        $identificadorOperador = $arrayParametros['identificadorOperador'];
        $idSubtipoProducto = $arrayParametros['idSubtipoProducto'];
        $tipoSolicitud = $arrayParametros["tipoSolicitud"];
        $idLocalizacion = $arrayParametros["idLocalizacion"];
        $tipoOperacion = "";

        switch ($tipoSolicitud) {

            case "otros":
                $tipoSolicitud = "('otros', 'musaceas')";
                $tipoOperacion = "('ACOSV')"; // "('ACOSV', 'COMSV', 'EXPSV', 'EXBSV')";
                break;
            case "musaceas":
                $tipoSolicitud = "('musaceas')";
                $tipoOperacion = "('ACOSV', 'EXPSV', 'EXBSV')";
                break;
            case "ornamentales":
                $tipoSolicitud = "('ornamentales')";
                $tipoOperacion = "('ACOSV', 'COMSV')";
                break;
        }

        $consulta = "SELECT DISTINCT
                        		p.id_producto
                        		, p.partida_arancelaria
                                , p.programa
                                , p.clasificacion
                        		, p.nombre_comun
                        FROM
                        	g_catalogos.productos p
                        	INNER JOIN g_operadores.operaciones op ON p.id_producto = op.id_producto
                        	INNER JOIN g_operadores.productos_areas_operacion pao ON op.id_operacion = pao.id_operacion
                        	INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
							INNER JOIN g_requisitos.requisitos_comercializacion rc ON p.id_producto = rc.id_producto
							INNER JOIN g_requisitos.requisitos_asignados ra ON rc.id_requisito_comercio = ra.id_requisito_comercio
                            INNER JOIN g_requisitos.requisitos r ON r.id_requisito = ra.requisito
                        WHERE
                        	p.id_subtipo_producto = '" . $idSubtipoProducto . "'
                        	and p.clasificacion in " . $tipoSolicitud . "
                        	and op.identificador_operador = '" . $identificadorOperador . "'
                        	and op.estado in ('registrado', 'registradoObservacion')
                        	and top.codigo || top.id_area in " . $tipoOperacion . "
							and rc.tipo = 'SV'
							and rc.id_localizacion = '" . $idLocalizacion . "'
                            and ra.tipo = 'Exportación'
                        	and ra.estado = 'activo'
                            and r.estado = '1'
							ORDER BY p.nombre_comun ASC;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los tipos de producto
     * del área de sanidad vegetal
     *
     * @return array|ResultSet
     */
    public function obtenerTiposProductosSVPorTipoSolicitud($arrayParametros)
    {
        $consulta = "SELECT DISTINCT
                        	tp.id_tipo_producto
                        	, tp.nombre as nombre_tipo_producto
                        	, tp.estado
                        	, tp.id_area
                        FROM 
                        	g_catalogos.tipo_productos tp
                        INNER JOIN g_catalogos.subtipo_productos stp ON tp.id_tipo_producto = stp.id_tipo_producto
                        WHERE
                        	tp.id_area = 'SV'
                        	and tp.estado = 1
                        ORDER BY tp.nombre ASC;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los subtipos de producto
     * de acuedo al tipo de producto
     *
     * @return array|ResultSet
     */
    public function obtenerSubtiposProductoPorIdTipoProducto($arrayParametros)
    {
        $idTipoProducto = $arrayParametros["idTipoProducto"];

        $consulta = "SELECT DISTINCT
                        	id_subtipo_producto
                        	, nombre as nombre_subtipo_producto
                        FROM
                        	g_catalogos.subtipo_productos
                        WHERE
                        	id_tipo_producto = " . $idTipoProducto . "
                            ORDER BY nombre ASC;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los subtipos de producto
     * de acuedo al tipo de producto
     *
     * @return array|ResultSet
     */
    public function obtenerProductoPorIdSubipoProducto($arrayParametros)
    {
        $idTipoProducto = $arrayParametros["idTipoProducto"];

        $consulta = "SELECT DISTINCT
                        	id_producto
                        	, nombre_comun as nombre_producto
                        FROM
                        	g_catalogos.productos
                        WHERE
                        	id_subtipo_producto = " . $idTipoProducto . "
                            ORDER BY nombre_comun ASC;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para verificar
     * si el proveedor ingresado es proveedor del exportador
     *
     * @return array|ResultSet
     */
    public function obtenerProveedoresPorExportador($arrayParametros)
    {
        $identificadorExportador = $arrayParametros["identificadorExportador"];
        $identificadorProveedor = $arrayParametros["identificadorOperador"];
        $idProducto = $arrayParametros["idProducto"];

        $consulta = "SELECT 
                        id_proveedor
                        , codigo_proveedor
                        , identificador_operador
                        , id_producto
                        , nombre_producto
                     FROM 
                        g_operadores.proveedores
                     WHERE 
                        identificador_operador = '" . $identificadorExportador . "'
                        and codigo_proveedor = '" . $identificadorProveedor . "'
                        and id_producto = '" . $idProducto . "'
                        and estado_proveedor = 'activo';";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los centros de acopio
     * autorizados de acuerdo al tipo solicitud, programa, operador y pais de destino
     *
     * @return array|ResultSet
     */
    public function obtenerProtocolosAreasAsignados($idArea, $idTipoOperacion)
    {
        $consulta = "SELECT
                    	pra.id_area
                    	, pra.codigo_area
                    	, STRING_AGG (distinct(paa.id_protocolo::text), ',') as protocolo_area
                     FROM 
                    	g_protocolos.protocolos_areas pra
                    	INNER JOIN g_protocolos.protocolos_areas_asignados paa ON pra.id_protocolo_area = paa.id_protocolo_area
                    	INNER JOIN g_protocolos.protocolos pr1 ON paa.id_protocolo = pr1.id_protocolo
                     WHERE 
                    	pra.id_area = '" . $idArea . "'
                    	and pra.id_tipo_operacion  = '" . $idTipoOperacion . "'
                        and paa.estado_protocolo_asignado in ('aprobado', 'implementacion')
                     GROUP BY 1, 2;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los protocolos
     * de acuedo al producto y al pais
     *
     * @return array|ResultSet
     */
    public function obtenerAreasProductosPorOperadorPorProducto($arrayParametros)
    {
        $identificadorOperador = $arrayParametros["identificadorOperador"];
        $idProducto = $arrayParametros["idProducto"];
        $tipoSolicitud = $arrayParametros["tipoSolicitud"];
        $tipoOperacion = "";

        switch ($tipoSolicitud) {

            case "otros":
                $tipoSolicitud = "('otros', 'musaceas')";
                $tipoOperacion = "('ACOSV')"; // "('ACOSV', 'COMSV', 'EXPSV', 'EXBSV')";
                break;
            case "musaceas":
                $tipoSolicitud = "('musaceas')";
                $tipoOperacion = "('ACOSV', 'EXPSV', 'EXBSV')";
                break;
            case "ornamentales":
                $tipoSolicitud = "('ornamentales')";
                $tipoOperacion = "('ACOSV', 'COMSV')";
                break;
        }

        $consulta = "SELECT DISTINCT
                            a.nombre_area
                            , a.id_area                        
                            , op.id_producto
                            , op.id_operacion
                            , op.id_tipo_operacion
                            , s.identificador_operador||'.'||s.codigo_provincia || s.codigo ||a.codigo||a.secuencial as codigo_area
    						, s.nombre_lugar
    						, a.nombre_area
    						, l.id_localizacion
    						, l.nombre
                         FROM
                            g_operadores.areas a
    						INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
    						INNER JOIN g_catalogos.localizacion l ON UPPER(s.provincia) = UPPER(l.nombre)	
                            INNER JOIN g_operadores.productos_areas_operacion pao ON a.id_area = pao.id_area
                            INNER JOIN g_operadores.operaciones op ON pao.id_operacion = op.id_operacion
                            INNER JOIN g_catalogos.productos p ON op.id_producto = p.id_producto
                            INNER JOIN g_catalogos.tipos_operacion top ON op.id_tipo_operacion = top.id_tipo_operacion
                         WHERE
                            op.identificador_operador = '" . $identificadorOperador . "'
                            and op.estado = 'registrado'
                            and s.estado = 'creado'
                        	and a.estado = 'creado'
                            and l.categoria = 1
                            and top.codigo || top.id_area in " . $tipoOperacion . "
                            and p.clasificacion in " . $tipoSolicitud . "
                            and op.id_producto = '" . $idProducto . "';";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos
     * de acuedo al id del area del operador
     *
     * @return array|ResultSet
     */
    public function obtenerDatosArea($areas)
    {
        $idAreas = " in ('" . $areas . "')";

        $consulta = "SELECT 
                        a.id_area
                        , a.nombre_area
								
                        , s.nombre_lugar
                        , s.provincia
                        , l.id_localizacion
                    FROM
                        g_operadores.areas a
                    INNER JOIN g_operadores.sitios s ON a.id_sitio = s.id_sitio
                    INNER JOIN g_catalogos.localizacion l ON UPPER(s.provincia) = UPPER(l.nombre)
                    WHERE
                        a.id_area" . $idAreas;

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para verificar los datos
     * de los exportadores productos
     *
     * @return array|ResultSet
     */
    public function verificarDatosCantidadesExportadoresProductos($arrayParametros)
    {
        $idExportadorProducto = $arrayParametros["idExportadorProducto"];

        $consulta = "SELECT 
                            id_exportador_producto
                            , cantidad_comercial
                            , peso_bruto
                            , peso_neto
                         FROM 
                            g_certificado_fitosanitario.exportadores_productos
                         WHERE 
                            id_exportador_producto = '" . $idExportadorProducto . "';";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para actualizar los datos
     * de los exportadores productos
     *
     * @return array|ResultSet
     */
    public function actualizarDatosCantidadesExportadoresProductos($arrayParametros)
    {
        $banderaPesoBruto = "";

        if (isset($arrayParametros["pesoBruto"]) && $arrayParametros["pesoBruto"] != "") {
            $banderaPesoBruto = ",peso_bruto = '" . $arrayParametros["pesoBruto"] . "' ";
        }

        $idExportadorProducto = $arrayParametros["idExportadorProducto"];
        $cantidadComercial = $arrayParametros["cantidadComercial"];

        $pesoNeto = $arrayParametros["pesoNeto"];

        $consulta = "UPDATE 
                        g_certificado_fitosanitario.exportadores_productos
                     SET 
                        cantidad_comercial = '" . $cantidadComercial . "'
                        , peso_neto = '" . $pesoNeto . "'" . $banderaPesoBruto . "WHERE 
                        id_exportador_producto = '" . $idExportadorProducto . "';";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los datos
     * de los exportadores productos
     *
     * @return array|ResultSet
     */
    public function obtenerExportadoresProductos($arrayParametros)
    {
        $area = "";
        $idCertificadoFitosanitario = $arrayParametros['id_certificado_fitosanitario'];
        $identificadorExportador = $arrayParametros['identificador_exportador'];
        $idProducto = $arrayParametros['id_producto'];

        // Obtiene la clasificación del producto
        $this->modeloProductos = $this->lNegocioProductos->buscar($idProducto);
        $clasificacionProducto = $this->modeloProductos->getClasificacion();

        if (isset($arrayParametros['id_area']) && $clasificacionProducto != "musaceas") {
            $idArea = $arrayParametros['id_area'];
            $area = " and id_area = '" . $idArea . "'";
        }

        $consulta = "SELECT
                            id_exportador_producto
                            , cantidad_comercial
                            , peso_bruto
                            , peso_neto
                         FROM
                            g_certificado_fitosanitario.exportadores_productos
                         WHERE
                            id_certificado_fitosanitario = '" . $idCertificadoFitosanitario . "'
                            and identificador_exportador = '" . $identificadorExportador . "'
                            and id_producto = '" . $idProducto . "'" . $area . ";";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los centros de acopio por provincia y por solicitud.
     *
     * @return array|ResultSet
     */
    public function buscarCentrosAcopioXProvinciaXSolicitud($idSolicitud, $idProvincia, $estado)
    {
        $consulta = "  SELECT
                        	distinct ep.id_area,
                            ep.nombre_area,
                            ep.codigo_centro_acopio,
                            ep.fecha_inspeccion,
                            ep.hora_inspeccion,
                            ep.estado_exportador_producto,
                            ep.nombre_producto,
                            ep.nombre_subtipo_producto,
                            ep.id_producto	  
                        FROM
                        	g_certificado_fitosanitario.exportadores_productos ep
                        WHERE
                            ep.id_certificado_fitosanitario = $idSolicitud and
                            ep.id_provincia_area = $idProvincia and
                            ep.estado_exportador_producto in ($estado)
                        GROUP BY ep.id_area,
                            ep.nombre_area,
                            ep.codigo_centro_acopio,
                            ep.fecha_inspeccion,
                            ep.hora_inspeccion,
                            ep.estado_exportador_producto,
							ep.nombre_producto,
                            ep.nombre_subtipo_producto,
                            ep.id_producto
                        ORDER BY
                        	ep.nombre_area ASC;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los centros de acopio por provincia y por solicitud.
     *
     * @return array|ResultSet
     */
    public function buscarProductosPorCentroAcopioInspeccion($arrayParametros)
    {
        $consulta = "SELECT
                        	distinct ep.id_producto,
                        	ep.nombre_producto,
                        	ep.estado_exportador_producto,
                        	ep.identificador_exportador,
                        	cf.id_pais_destino
                        FROM
                        	g_certificado_fitosanitario.exportadores_productos ep
                        	INNER JOIN g_certificado_fitosanitario.certificado_fitosanitario cf ON ep.id_certificado_fitosanitario = cf.id_certificado_fitosanitario
                        WHERE
                            ep.id_certificado_fitosanitario = " . $arrayParametros['id_certificado_fitosanitario'] . " and
                            ep.id_provincia_area = " . $arrayParametros['id_provincia_area'] . " and
                            ep.id_area = " . $arrayParametros['id_area'] . " and
                            ep.estado_exportador_producto in (" . $arrayParametros['estado_exportador_producto'] . ")
                        GROUP BY 
                            ep.id_area,
                        	ep.id_producto,
                        	ep.nombre_producto,
                        	ep.estado_exportador_producto,
                        	ep.identificador_exportador,
                        	cf.id_pais_destino
                        ORDER BY
                        	ep.nombre_producto ASC;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los centros de acopio por provincia y por solicitud.
     *
     * @return array|ResultSet
     */
    public function buscarProvinciaXSolicitud($idSolicitud, $estado)
    {
        $consulta = "  SELECT
                        	distinct ep.id_provincia_area,
                            ep.nombre_provincia_area
                        FROM
                        	g_certificado_fitosanitario.exportadores_productos ep
                        WHERE
                            ep.id_certificado_fitosanitario = $idSolicitud and
                            ep.estado_exportador_producto in ($estado)
                        GROUP BY ep.id_provincia_area,
                            ep.nombre_provincia_area
                        ORDER BY
                        	ep.nombre_provincia_area ASC;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para obtener los centros de acopio por provincia y por solicitud.
     *
     * @return array|ResultSet
     */
    public function buscarCentrosAcopioXProvincia($arrayParametros)
    {
        $consulta = "  SELECT
                        	distinct ep.id_area,
                            ep.nombre_area
                        FROM
                        	g_certificado_fitosanitario.exportadores_productos ep
                        WHERE
                            ep.id_certificado_fitosanitario = " . $arrayParametros['id_certificado_fitosanitario'] . " and
                            ep.id_provincia_area = " . $arrayParametros['id_provincia_area'] . " and
                            ep.estado_exportador_producto in (" . $arrayParametros['estado_exportador_producto'] . ")
                        GROUP BY ep.id_area,
                            ep.id_area,
                            ep.nombre_area
                        ORDER BY
                        	ep.nombre_area ASC;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Consulta para obtener los exportadores y productos que serán remitidos en el envío.
     *
     * @return array|ResultSet
     */
    public function obtenerExportadoresProductosPorIdSolicitud($arrayParametros)
    {
        $consulta = "SELECT
                        ep.*
                    FROM
                        g_certificado_fitosanitario.exportadores_productos ep
                    WHERE
                        ep.id_certificado_fitosanitario = " . $arrayParametros['id_certificado_fitosanitario'] . " 
                        and ep.estado_exportador_producto not in ('Rechazado')
                    ORDER BY
                        ep.razon_social_exportador, ep.nombre_producto, ep.id_area ASC;";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Consulta para obtener la cantidad de registros de exportadores y productos.
     *
     * @return array|ResultSet
     */
    public function obtenerNumeroExportadoresProductosXSolicitud($idSolicitud)
    {
        $consulta = "SELECT
                        count(ep.id_exportador_producto) cantidad_exportadores
                    FROM
                        g_certificado_fitosanitario.exportadores_productos ep
                    WHERE
                        ep.id_certificado_fitosanitario = $idSolicitud and
                        ep.estado_exportador_producto in ('Aprobado');";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada .
     * Consulta para obtener si existe un protocolo para un producto para un pais.
     *
     * @return array|ResultSet
     */
    public function validarProtocoloPorProductoPorPais($arrayParametros)
    {
        $consulta = "SELECT
                        STRING_AGG (distinct(pr.id_protocolo::text), ',') as protocolo_producto_pais
                      FROM 
                        g_protocolos.protocolos_asignados pa 
                        INNER JOIN g_protocolos.protocolos pr ON pa.id_protocolo = pr.id_protocolo
                        INNER JOIN g_protocolos.protocolos_comercializacion pc ON pa.id_protocolo_comercio = pc.id_protocolo_comercio
                      WHERE 
                        pc.id_localizacion = '" . $arrayParametros['idLocalizacion'] . "'
                        and pc.id_producto = '" . $arrayParametros['idProducto'] . "'
                        and pa.estado = 'activo'
                        and pr.estado_protocolo = '1';";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para validar
     * que la cantidadades no sea mayores a la de la solicitud original en una reimpresion
     *
     * @return array|ResultSet
     */
    public function verificarCantidades($arrayParametros)
    {
        $consulta = "SELECT
						id_exportador_producto
                        , cantidad_comercial
                        , peso_bruto
                        , peso_neto
    				FROM
    					g_certificado_fitosanitario.exportadores_productos
                    WHERE 
                        id_exportador_producto = '" . $arrayParametros['idExportadorProducto'] . "';";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para actualizar
     * las cantidades de productos de una solicitud
     *
     * @return array|ResultSet
     */
    public function actualizarCantidades($arrayParametros)
    {
        $idExportadorProducto = $arrayParametros['idExportadorProducto'];
        $cantidad = $arrayParametros['cantidad'];
        $tipoCantidad = $arrayParametros['tipoCantidad'];
        $dato = "";

        switch ($tipoCantidad) {
            case "cantidad_comercial":
                $dato = $tipoCantidad;
                break;
            case "peso_bruto":
                $dato = $tipoCantidad;
                break;
            case "peso_neto":
                $dato = $tipoCantidad;
                break;
        }

        $consulta = "UPDATE 
                    	g_certificado_fitosanitario.exportadores_productos
                    SET
                    	" . $dato . " = '" . $cantidad . "'
                    WHERE
                    id_exportador_producto = '" . $idExportadorProducto . "';";

        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }

    /**
     * Ejecuta una consulta(SQL) personalizada para validar
     * que en una solicitud existan productos categorizados como "musaceas"
     *
     * @return array|ResultSet
     */
    public function verificarEstadoPorClasificacionProducto($arrayParametros)
    {
        $idSolicitud = $arrayParametros['idSolicitud'];
        $estadoExportador = $arrayParametros['estadoExportador'];
        $tipoRevision = $arrayParametros['tipoRevision'];

        $consulta = "SELECT 
                    	id_exportador_producto
                    	, id_certificado_fitosanitario
                    	, id_producto
                    	, fecha_inspeccion
                    	, hora_inspeccion
                    	, estado_exportador_producto
                    	, fecha_revision
                    	, tipo_revision
                    	, identificador_revision
                    	, observacion_revision
                    FROM 
                    	g_certificado_fitosanitario.exportadores_productos ep
                    WHERE
                    	id_certificado_fitosanitario = " . $idSolicitud . "
                    	and (estado_exportador_producto not in " . $estadoExportador . " or
                    	tipo_revision not in " . $tipoRevision . ");";
        
        return $this->modeloExportadoresProductos->ejecutarSqlNativo($consulta);
    }
         
}