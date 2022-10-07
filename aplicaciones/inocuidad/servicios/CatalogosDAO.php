<?php

/**
 * User: ccarrera
 * Date: 1/30/18
 * Time: 8:31 PM
 */

class CatalogosDAO
{


    public function obtenerCatalogoPorGrupo($conexion,$grupo){
        $queryCatalogo = "SELECT ic_catalogo_id, grupo, nombre, valor, valor_num, descripcion, referencia_id FROM g_inocuidad.ic_catalogo WHERE grupo='$grupo' ORDER BY  nombre";
        $res = $conexion->ejecutarConsulta($queryCatalogo);
        return $res;
    }

    public function obtenerCatalogoPorId($conexion,$ic_catalogo_id){
        $queryCatalogo = "SELECT ic_catalogo_id, grupo, nombre, valor, valor_num, descripcion, referencia_id FROM g_inocuidad.ic_catalogo WHERE ic_catalogo_id=$ic_catalogo_id ";
        $res = $conexion->ejecutarConsulta($queryCatalogo);
        return $res;
    }

    /**
     * Obtiene programas actuales
     */
    public function obtenerProgramas($conexion)
    {
        $queryProgramas = "SELECT ic_catalogo_id, grupo, nombre, valor, valor_num, descripcion, referencia_id FROM g_inocuidad.ic_catalogo WHERE grupo='programa_id'";
        $consulta = $conexion->ejecutarConsulta($queryProgramas);
        return $consulta;
    }

    /**
     * Obtiene programas actuales
     */
    public function obtenerProgramaById($programa_id,$conexion)
    {
        $queryProgramas = "SELECT ic_catalogo_id, grupo, nombre, valor, valor_num, descripcion, referencia_id FROM g_inocuidad.ic_catalogo WHERE grupo='programa_id' and ic_catalogo_id=$programa_id";
        $consulta = $conexion->ejecutarConsulta($queryProgramas);
        return $consulta;
    }

    public function obtenerAreaById($id_area,$conexion)
    {
        $queryProgramas = "SELECT * FROM g_estructura.area WHERE id_area='$id_area'";
        $consulta = $conexion->ejecutarConsulta($queryProgramas);
        return $consulta;
    }

    /**
     * Obtiene fuentes de denuncia
     */
    public function obtenerFuentesDenuncia($conexion)
    {
        $queryProgramas = "SELECT ic_catalogo_id, grupo, nombre, valor, valor_num, descripcion, referencia_id FROM g_inocuidad.ic_catalogo WHERE grupo='fuente_denuncia_id'";
        $consulta = $conexion->ejecutarConsulta($queryProgramas);
        return $consulta;
    }

    /**
     * Obtiene productos de Inocuidad
     */
    public function obtenerIcProductos($conexion)
    {
        $queryProductos = "SELECT ic_producto_id, producto_id, programa_id, nombre FROM g_inocuidad.ic_producto";
        $consulta = $conexion->ejecutarConsulta($queryProductos);
        return $consulta;
    }

    public function obtenerIcProductoById($ic_producto_id,$conexion)
    {
        $queryProductos = "SELECT ic_producto_id, producto_id, programa_id, nombre FROM g_inocuidad.ic_producto WHERE ic_producto_id=$ic_producto_id";
        $consulta = $conexion->ejecutarConsulta($queryProductos);
        return $consulta;
    }

    /**
     * Obtiene insumos de Inocuidad
     */
    public function obtenerIcInsumosXprograma($conexion, $param)
    {
        $queryInsumos = "SELECT ic_insumo_id, descripcion, programa_id, nombre FROM g_inocuidad.ic_insumo";
        $queryInsumos.=" WHERE programa_id=$param";
        $consulta = $conexion->ejecutarConsulta($queryInsumos);
        return $consulta;
    }

    /**
     * Obtiene insumos de Inocuidad
     */
    public function obtenerIcInsumoAplicado($conexion, $param)
    {
        $queryInsumos = "SELECT DISTINCT ins.id_producto as id_ingrediente_activo, ins.nombre_cientifico as ingrediente_quimico, ins.nombre_comun as ingrediente_activo 
                        FROM g_inocuidad.ic_muestra m
                        JOIN g_inocuidad.ic_requerimiento r on m.ic_requerimiento_id = r.ic_requerimiento_id
                        JOIN g_inocuidad.ic_producto_insumo pi on r.ic_producto_id = pi.ic_producto_id
                        JOIN g_catalogos.productos ins ON pi.ic_insumo_id = ins.id_producto
                        WHERE m.ic_muestra_id= $param
                        ORDER BY ins.nombre_comun";
        $consulta = $conexion->ejecutarConsulta($queryInsumos);
        return $consulta;
    }

    public function obtenerIcInsumos($conexion)
    {
        $queryInsumos = "SELECT ic_insumo_id, descripcion, programa_id, nombre FROM g_inocuidad.ic_insumo";
        $queryInsumos.=" ORDER BY nombre";
        $consulta = $conexion->ejecutarConsulta($queryInsumos);
        return $consulta;
    }

    /**
     * Obtiene lmr
     */
    public function obtenerIcLmr($conexion)
    {
        $queryLmr = "SELECT ic_lmr_id, descripcion, nombre FROM g_inocuidad.ic_lmr";
        $consulta = $conexion->ejecutarConsulta($queryLmr);
        return $consulta;
    }

    /**
     * Obtiene tipo de requerimiento
     */
    public function obtenerTipoRequerimiento($conexion)
    {
        $queryRequerimientos="SELECT ic_tipo_requerimiento_id, nombre, codigo, descripcion";
        $queryRequerimientos.=" FROM g_inocuidad.ic_tipo_requerimiento";
        $consulta = $conexion->ejecutarConsulta($queryRequerimientos);
        return $consulta;
    }

    public function obtenerResultadosDesicion($conexion){
        $sql = "SELECT ic_resultado_decision_id, nombre, tipo_desicion 
                  FROM G_INOCUIDAD.IC_RESULTADO_DESICION 
                  ORDER BY TIPO_DESICION, NOMBRE";
        $consulta = $conexion->ejecutarConsulta($sql);
        return $consulta;
    }

    public function obtenerEstadoMouduloLaboratorio($conexion){
        $sql = "SELECT valor FROM g_inocuidad.ic_catalogo WHERE grupo='config_mod_lab'";
        $consulta = $conexion->ejecutarConsulta($sql);
        return $consulta;
    }

    public function obtenerRegistroDesicionById($conexion, $ic_resultado_decision_id){
        $sql = "SELECT ic_resultado_decision_id, nombre, tipo_desicion 
                  FROM G_INOCUIDAD.IC_RESULTADO_DESICION 
                  WHERE ic_resultado_decision_id = $ic_resultado_decision_id";
        $consulta = $conexion->ejecutarConsulta($sql);
        return $consulta;
    }

    public function obtenerTipoRequerimientoById($conexion, $ic_tipo_requerimiento_id)
    {
        $queryRequerimientos="SELECT ic_tipo_requerimiento_id, nombre, codigo, descripcion";
        $queryRequerimientos.=" FROM g_inocuidad.ic_tipo_requerimiento";
        $queryRequerimientos.=" WHERE ic_tipo_requerimiento_id=$ic_tipo_requerimiento_id";
        $consulta = $conexion->ejecutarConsulta($queryRequerimientos);
        return $consulta;
    }

    public function obtenerSitios($provincia,$canton,$parroquia, $conexion){
        $query = "SELECT id_sitio, nombre_lugar, parroquia, direccion, latitud, longitud, 
                           superficie_total, croquis, identificador_operador, referencia, 
                           estado, telefono, canton, provincia, codigo, zona, codigo_provincia, 
                           fecha_sitio
                      FROM g_operadores.sitios 
                    WHERE provincia = '$provincia' 
                    AND canton = '$canton' 
                    AND parroquia='$parroquia'";
        $consulta = $conexion->ejecutarConsulta($query);
        return $consulta;
    }

    public function obtenerSitioPorId($id_sitio, $conexion){
        $query = "SELECT id_sitio, nombre_lugar, parroquia, direccion, latitud, longitud, 
                       superficie_total, croquis, identificador_operador, referencia, 
                       estado, telefono, canton, provincia, codigo, zona, codigo_provincia, 
                       fecha_sitio
                  FROM g_operadores.sitios
                  WHERE id_sitio = $id_sitio";
        $consulta = $conexion->ejecutarConsulta($query);
        return $consulta;
    }

    public function obtenerProvincia($id_provincia, $conexion){
        $query = "SELECT *
                  FROM g_catalogos.localizacion
                  WHERE categoria = 1 and id_localizacion= $id_provincia";
        $consulta = $conexion->ejecutarConsulta($query);
        return $consulta;
    }

    public function obtenerImportacionPorCertificado($certificado,$conexion){
        $query = "SELECT id_importacion, identificador_operador, nombre_exportador, direccion_exportador, 
                       id_pais_exportacion, pais_exportacion, nombre_embarcador, id_localizacion, 
                       pais_embarque, id_puerto_embarque, puerto_embarque, id_puerto_destino, 
                       puerto_destino, codigo_certificado, id_vue, estado, tipo_certificado, 
                       moneda, informe_requisitos, fecha_inicio, fecha_vigencia, tipo_transporte, 
                       fecha_modificacion, fecha_creacion, regimen_aduanero, fecha_ampliacion, 
                       id_area, id_provincia, nombre_provincia, id_ciudad, nombre_ciudad
                  FROM g_importaciones.importaciones
                  WHERE id_vue = '$certificado' ";
        $consulta = $conexion->ejecutarConsulta($query);
        return $consulta;
    }

    public function obtenerInspectoresPorProvincia($conexion,$provincia){
        $queryCatalogo = "SELECT OP.identificador, apellido||' '||nombre as nombre_completo, nombre, apellido, tipo_documento, nacionalidad, 
		                convencional,celular, mail_institucional, extension_magap, tipo_empleado
                        FROM G_UATH.FICHA_EMPLEADO OP
                        JOIN G_USUARIO.USUARIOS_PERFILES UP ON OP.IDENTIFICADOR = UP.IDENTIFICADOR
                        JOIN G_ESTRUCTURA.FUNCIONARIOS FUN ON OP.IDENTIFICADOR = FUN.IDENTIFICADOR
                        WHERE UP.ID_PERFIL = (SELECT ID_PERFIL FROM G_USUARIO.PERFILES WHERE CODIFICACION_PERFIL='PFL_INP_INOC')
                        AND CASE WHEN ($provincia IS NOT NULL AND $provincia>0) THEN FUN.ID_PROVINCIA = $provincia ELSE 1=1 END
                        ORDER BY apellido,nombre";
        $res = $conexion->ejecutarConsulta($queryCatalogo);
        return $res;
    }

    public function obtenerTecnicosPorProvincia($conexion,$provincia){
        $queryCatalogo = "SELECT OP.identificador, apellido||' '||nombre as nombre_completo, nombre, apellido, tipo_documento, nacionalidad, 
		              convencional,celular, mail_institucional, extension_magap, tipo_empleado
                    FROM G_UATH.FICHA_EMPLEADO OP
                    JOIN G_USUARIO.USUARIOS_PERFILES UP ON OP.IDENTIFICADOR = UP.IDENTIFICADOR
                    WHERE UP.ID_PERFIL = (SELECT ID_PERFIL FROM G_USUARIO.PERFILES WHERE CODIFICACION_PERFIL='PFL_TEC_INOC')
                    AND CASE WHEN ($provincia IS NOT NULL AND $provincia>0) THEN id_localizacion_provincia = $provincia ELSE 1=1 END
                    ORDER BY apellido,nombre";
        $res = $conexion->ejecutarConsulta($queryCatalogo);
        return $res;
    }

    public function obtenerInspectorPorIdentificacion($identificador, $conexion){
        $query = "SELECT OP.identificador, apellido||' '||nombre as nombre_completo, nombre, apellido, tipo_documento, nacionalidad, 
		          convencional,celular, mail_institucional, extension_magap, tipo_empleado
                    FROM G_UATH.FICHA_EMPLEADO OP
                    JOIN G_USUARIO.USUARIOS_PERFILES UP ON OP.IDENTIFICADOR = UP.IDENTIFICADOR
                    WHERE OP.identificador='$identificador'";
        $consulta = $conexion->ejecutarConsulta($query);
        return $consulta;
    }

}