<?php

class ControladorCgina
{

    public function obtenerCaracterizacionFruticola($conexion, $fecha)
    {

        $consulta = "SELECT
            id,
            fecha_inspeccion as fecha_registro,
            nombre_asociacion_productor,
            identificador,
            telefono,
            provincia,
            canton,
            parroquia,
            sitio,
            especie as especie_producto_hortofruticula,
            variedad as variedad_producto_hortofruticula,
            area_produccion_estimada,
            coordenada_x,
            coordenada_y,
            coordenada_z,
            observaciones,
            usuario as inspector
        FROM
            f_inspeccion.moscaf02 f
        WHERE
            f.fecha_ingreso_guia between '$fecha 00:00:00' and '$fecha 24:00:00'";


        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarCaracterizacionFruticola($conexion, $valores)
    {
        $consulta = "INSERT INTO 
                            mag.caracterizacion_fruticola (id, fecha_registro, nombre_asociacion_productor, identificador, 
                            telefono, provincia, canton, parroquia, sitio, especie_producto_hortofruticula, 
                            variedad_producto_hortofruticula, area_produccion_estimada, coordenada_x, 
                            coordenada_y, coordenada_z, observaciones, inspector, fecha_actualizacion) 
                        VALUES 
                            $valores;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function obtenerMonitoreoVigilancia($conexion, $fecha)
    {

        $consulta = "SELECT
                        f.id,
                        fecha_inspeccion,
                        nombre_provincia,
                        nombre_canton,
                        nombre_parroquia,
                        nombre_propietario_finca,
                        localidad_via,
                        coordenada_x,
                        coordenada_y,
                        coordenada_z,
                        denuncia_fitosanitaria,
                        nombre_denunciante,
                        telefono_denunciante,
                        direccion_denunciante,
                        correo_electronico_denunciante,
                        especie_vegetal,
                        cantidad_total as cantidad_total_especie,
                        cantidad_vigilada as cantidad_vigilada_especie,
                        unidad,
                        sitio_operacion,
                        condicion_produccion,
                        etapa_cultivo,
                        actividad,
                        manejo_sitio_operacion,
                        ausencia_plaga,
                        plaga_diagnostico_visual_prediagnostico,
                        cantidad_afectada,
                        porcentaje_incidencia,
                        porcentaje_severidad,
                        tipo_plaga,
                        fase_desarrollo_plaga,
                        organo_afectado,
                        distribucion_plaga,
                        poblacion,
                        diagnostico_visual,
                        descripcion_sintomas_p,
                        envio_muestra,
                        usuario as inspector,
                        observaciones
                    FROM
                        f_inspeccion.vigilanciaf02 f
                    WHERE
                        f.fecha_ingreso_guia between '$fecha 00:00:00' and '$fecha 24:00:00'";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarMonitoreoVigilancia($conexion, $valores)
    {
        $consulta = "INSERT INTO 
                            mag.monitoreo_vigilancia (id, fecha_inspeccion, nombre_provincia, nombre_canton, nombre_parroquia, 
                            nombre_propietario_finca, localidad_via, coordenada_x, coordenada_y, 
                            coordenada_z, denuncia_fitosanitaria, nombre_denunciante, telefono_denunciante, 
                            direccion_denunciante, correo_electronico_denunciante, especie_vegetal, 
                            cantidad_total_especie, cantidad_vigilada_especie, unidad, sitio_operacion, 
                            condicion_produccion, etapa_cultivo, actividad, manejo_sitio_operacion, 
                            ausencia_plaga, plaga_diagnostico_visual_prediagnostico, cantidad_afectada, 
                            porcentaje_incidencia, porcentaje_severidad, tipo_plaga, fase_desarrollo_plaga, 
                            organo_afectado, distribucion_plaga, poblacion, diagnostico_visual, 
                            descripcion_sintomas_p, envio_muestra, inspector, observaciones, 
                            fecha_actualizacion) 
                        VALUES 
                            $valores
                            ON CONFLICT (id) DO NOTHING;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function obtenerMonitoreoVigilanciaDetalle($conexion, $fecha)
    {

        $consulta = "SELECT
                        fdo.id,
                        fdo.id_padre,
                        codigo_muestra
                    FROM
                        f_inspeccion.vigilanciaf02_detalle_ordenes fdo INNER JOIN f_inspeccion.vigilanciaf02 f ON fdo.id_padre = f.id
                    WHERE
                        f.fecha_ingreso_guia between '$fecha 00:00:00' and '$fecha 24:00:00'";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarMonitoreoVigilanciaDetalle($conexion, $valores)
    {
        $consulta = "INSERT INTO 
                            mag.monitoreo_vigilancia_detalle(id, id_padre, codigo_muestra, fecha_actualizacion)
                        VALUES 
                            $valores;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }


    public function obtenerSeguimientoCuarentenario($conexion, $fecha)
    {

        $consulta = "SELECT
                        f.id,
                        fecha_inspeccion,
                        razon_social,
                        nombre_scpe,
                        usuario as inspector,
                        actividad,
                        pais_origen,
                        subtipo_producto,
                        producto,
                        peso,
                        tipo_operacion,
                        tipo_cuarentena_condicion_produccion,
                        fase_seguimiento,
                        codigo_lote,
                        numero_seguimientos_planificados,
                        numero_plantas_ingreso,
                        numero_plantas_inspeccion,
                        registro_monitoreo_plagas,
                        ausencia_plagas,
                        cantidad_afectada,
                        porcentaje_incidencia,
                        porcentaje_severidad,
                        fase_desarrollo_plaga,
                        organo_afectado,
                        distribucion_plaga,
                        envio_muestra as envio_muestra_laboratorio,
                        resultado_inspeccion,
                        observaciones,
                        codigo_muestra
                    FROM
                        f_inspeccion.controlf04 f LEFT JOIN f_inspeccion.controlf04_detalle_ordenes fdo ON (fdo.id_padre = f.id)
                    WHERE
                        f.fecha_ingreso_guia between '$fecha 00:00:00' and '$fecha 24:00:00'";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarSeguimientoCuarentenario($conexion, $valores)
    {
        $consulta = "INSERT INTO 
                            mag.seguimiento_cuarentenario(id, fecha_inspeccion, razon_social, nombre_scpe, inspector, actividad, 
                            pais_origen, subtipo_producto, producto, peso, tipo_operacion, 
                            tipo_cuarentena_condicion_produccion, fase_seguimiento, codigo_lote, 
                            numero_seguimientos_planificados, numero_plantas_ingreso, numero_plantas_inspeccion, 
                            registro_monitoreo_plagas, ausencia_plagas, cantidad_afectada, 
                            porcentaje_incidencia, porcentaje_severidad, fase_desarrollo_plaga, 
                            organo_afectado, distribucion_plaga, envio_muestra_laboratorio, 
                            resultado_inspeccion, observaciones, codigo_muestra, fecha_actualizacion)
                        VALUES 
                            $valores;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function obtenerProductosRIA($conexion, $fecha, $tipo)
    {        

        $consulta = "SELECT
                        distinct p.id_producto,
                        pin.numero_registro,
                        to_char(pin.fecha_registro,'dd/mm/yyyy')::date fecha_registro,
                        CASE WHEN p.estado=1 THEN 'Vigente'
                        WHEN p.estado=2 THEN 'Suspendido'
                        WHEN p.estado=3 THEN 'Caducado'
                        END as estado,
                        pin.id_operador,
                        o.razon_social,
                        sp.nombre as subtipo_producto,
                        p.nombre_comun as nombre_comercial,
                        --presentacion es tabla detalle 	
                        -- composicion es otra tabla detalle
                        pin.formulacion,
                        pin.dosis ||' '|| pin.unidad_dosis as dosis,
                        pin.categoria_toxicologica,
                        pin.periodo_reingreso,
                        pin.periodo_carencia_retiro as periodo_carencia,
                        --usos es otra tabla de detalle
                        --fabricante_formulador es otra tabla de detalle
                        pin.observacion,
                        p.fecha_creacion,
                        p.fecha_modificacion
                    FROM 
                        g_catalogos.productos as p
                        FULL OUTER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                        FULL OUTER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
                        FULL OUTER JOIN g_catalogos.productos_inocuidad as pin ON pin.id_producto = p.id_producto
                        FULL OUTER JOIN g_operadores.operadores o ON o.identificador = pin.id_operador
                    WHERE
                        tp.id_area = '$tipo' and tp.nombre!='Cultivo'
                        and p.estado not in (9)
                        and (p.fecha_creacion between '$fecha 00:00:00' and '$fecha 24:00:00' 
                        OR p.fecha_modificacion between '$fecha 00:00:00' and '$fecha 24:00:00')
                    ORDER BY 1";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarProductosRIA($conexion, $valores, $tabla)
    {
        $consulta = "INSERT INTO 
                            mag.$tabla(id_producto, numero_registro, fecha_registro, estado, id_operador, 
                            razon_social, subtipo_producto, nombre_comercial, formulacion, 
                            dosis, categoria_toxicologica, periodo_reingreso, periodo_carencia, 
                            observacion, fecha_actualizacion)
                        VALUES 
                            $valores
                            ON CONFLICT (id_producto) DO NOTHING;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function actualizarProductosRIA($conexion, $valores, $tabla)
    {
        $consulta = "UPDATE mag.$tabla as t1 set
                        numero_registro = t2.numero_registro,
                        fecha_registro = t2.fecha_registro:: timestamp without time zone,
                        estado = t2.estado,
                        id_operador = t2.id_operador,
                        razon_social = t2.razon_social,
                        subtipo_producto = t2.subtipo_producto,
                        nombre_comercial = t2.nombre_comercial,
                        formulacion = t2.formulacion,
                        dosis = t2.dosis,
                        categoria_toxicologica = t2.categoria_toxicologica,
                        periodo_reingreso = t2.periodo_reingreso,
                        periodo_carencia = t2.periodo_carencia,
                        observacion = t2.observacion,
                        fecha_actualizacion = t2.fecha_actualizacion::timestamp without time zone
                    FROM 
                        (values
                            $valores
                        ) as t2(id_producto, numero_registro, fecha_registro, estado, id_operador, 
                                razon_social, subtipo_producto, nombre_comercial, formulacion, 
                                dosis, categoria_toxicologica, periodo_reingreso, periodo_carencia, 
                                observacion, fecha_actualizacion)
                    WHERE
                        t1.id_producto::text = t2.id_producto;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function obtenerProductosVeterinarios($conexion, $fecha)
    {
        
        $consulta = "SELECT
                        distinct p.id_producto,
                        pin.numero_registro,
                        to_char(pin.fecha_registro,'dd/mm/yyyy')::date fecha_registro,
                        CASE WHEN p.estado=1 THEN 'Vigente'
                            WHEN p.estado=2 THEN 'Suspendido'
                            WHEN p.estado=3 THEN 'Caducado'
                        END as estado,
                        pin.id_operador,
                        o.razon_social,
                        sp.nombre subtipo_producto,
                        p.nombre_comun as nombre_comercial,                       
                        pin.formulacion,
                        pin.dosis ||' '|| pin.unidad_dosis as dosis,                        
                        pin.declaracion_venta,
                        pin.observacion,
                        p.fecha_creacion,
                        p.fecha_modificacion
                    FROM 
                        g_catalogos.productos as p
                        FULL OUTER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto 
                        FULL OUTER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
                        FULL OUTER JOIN g_catalogos.productos_inocuidad as pin ON pin.id_producto = p.id_producto
                        FULL OUTER JOIN g_operadores.operadores o ON o.identificador = pin.id_operador
                    WHERE
                        tp.id_area = 'IAV' and tp.nombre!='Cultivo'
                        and p.estado not in (9)
                        and (p.fecha_creacion between '$fecha 00:00:00' and '$fecha 24:00:00' 
                            OR p.fecha_modificacion between '$fecha 00:00:00' and '$fecha 24:00:00')
                    ORDER BY 1";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarProductosVeterinarios($conexion, $valores)
    {
        $consulta = "INSERT INTO 
                            mag.productos_veterinarios(id_producto, numero_registro, fecha_registro, estado, id_operador, 
                            razon_social, subtipo_producto, nombre_comercial, formulacion, 
                            dosis, declaracion_venta, observacion, fecha_actualizacion)
                        VALUES 
                            $valores
                            ON CONFLICT (id_producto) DO NOTHING;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function actualizarProductosVeterinarios($conexion, $valores)
    {
        $consulta = "UPDATE mag.productos_veterinarios as t1 set
                        numero_registro = t2.numero_registro,
                        fecha_registro = t2.fecha_registro:: timestamp without time zone,
                        estado = t2.estado,
                        id_operador = t2.id_operador,
                        razon_social = t2.razon_social,
                        subtipo_producto = t2.subtipo_producto,
                        nombre_comercial = t2.nombre_comercial,
                        formulacion = t2.formulacion,
                        dosis = t2.dosis,   
                        declaracion_venta = t2.declaracion_venta,
                        observacion = t2.observacion,
                        fecha_actualizacion = t2.fecha_actualizacion::timestamp without time zone
                    FROM 
                        (values
                            $valores
                        ) as t2(id_producto, numero_registro, fecha_registro, estado, id_operador, 
                                razon_social, subtipo_producto, nombre_comercial, formulacion, 
                                dosis, declaracion_venta, observacion, fecha_actualizacion)
                    WHERE
                        t1.id_producto::text = t2.id_producto;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }


    public function obtenerIngredientesActivos($conexion, $fecha)
    {

        $consulta = "SELECT 
                            *
                        FROM
                            g_catalogos.ingrediente_activo_inocuidad 
                        WHERE 
                            (fecha_creacion_ingrediente_activo between '$fecha 00:00:00' and '$fecha 24:00:00' 
                            OR fecha_modificacion_ingrediente_activo between '$fecha 00:00:00' and '$fecha 24:00:00')";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarIngredientesActivos($conexion, $valores)
    {
        $consulta = "INSERT INTO 
                            catalogos.ingredientes_activos(id_ingrediente_activo, ingrediente_activo, id_area, ingrediente_quimico, 
                            cas, formula_quimica, grupo_quimico, fecha_actualizacion)
                        VALUES 
                            $valores
                            ON CONFLICT (id_ingrediente_activo) DO NOTHING;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function actualizarIngredientesActivos($conexion, $valores)
    {
        $consulta = "UPDATE catalogos.ingredientes_activos as t1 set
                        ingrediente_activo = t2.ingrediente_activo,
                        id_area = t2.id_area,
                        ingrediente_quimico = t2.ingrediente_quimico,
                        cas = t2.cas,
                        formula_quimica = t2.formula_quimica,
                        grupo_quimico = t2.grupo_quimico,                        
                        fecha_actualizacion = t2.fecha_actualizacion::timestamp without time zone
                    FROM 
                        (values
                            $valores
                        ) as t2(id_ingrediente_activo, ingrediente_activo, id_area, ingrediente_quimico, 
                            cas, formula_quimica, grupo_quimico, fecha_actualizacion)
                    WHERE
                        t1.id_ingrediente_activo::text = t2.id_ingrediente_activo;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }


    public function obtenerCultivos($conexion, $fecha)
    {
        $consulta = "SELECT
                        p.id_producto as id_cultivo,
                        p.nombre_comun as nombre_producto,
                        nombre_cientifico,
                        sp.nombre as subtipo_producto
                    FROM g_catalogos.productos as p
                        FULL OUTER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                        FULL OUTER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
                    WHERE
                        upper(tp.nombre)=upper('Cultivo')
                        and p.estado not in (9)
                        and p.fecha_creacion between '$fecha 00:00:00' and '$fecha 24:00:00' 
                    ORDER BY 1";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarCultivos($conexion, $valores)
    {
        $consulta = "INSERT INTO 
                            catalogos.cultivos(id_cultivo, nombre_producto, nombre_cientifico, subtipo_producto, 
                            fecha_actualizacion)
                        VALUES 
                            $valores;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }


    public function obtenerPlagas($conexion, $fecha)
    {

        $consulta = "SELECT 
                        id_uso, 
                        nombre_uso as nombre_cientifico,                        
                        id_area, 
                        nombre_comun_uso,
                        fecha_creacion_uso,
                        fecha_modificacion_uso
                    FROM 
                        g_catalogos.usos
                    WHERE 
                        (fecha_creacion_uso between '$fecha 00:00:00' and '$fecha 24:00:00' 
                        OR fecha_modificacion_uso between '$fecha 00:00:00' and '$fecha 24:00:00')";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarPlagas($conexion, $valores)
    {
        $consulta = "INSERT INTO 
                            catalogos.plagas(id_uso, nombre_cientifico, id_area, nombre_comun_uso, fecha_actualizacion)
                        VALUES 
                            $valores
                            ON CONFLICT (id_uso) DO NOTHING;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function actualizarPlagas($conexion, $valores)
    {
        $consulta = "UPDATE catalogos.plagas as t1 set
                        nombre_cientifico = t2.nombre_cientifico,
                        id_area = t2.id_area,
                        nombre_comun_uso = t2.nombre_comun_uso,                    
                        fecha_actualizacion = t2.fecha_actualizacion::timestamp without time zone
                    FROM 
                        (values
                            $valores
                        ) as t2(id_uso, nombre_cientifico, id_area, nombre_comun_uso, fecha_actualizacion)
                    WHERE
                        t1.id_uso::text = t2.id_uso;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function obtenerMovilizacion($conexion, $fecha)
    {

        $consulta = "SELECT
                        mo.id_movilizacion,
                        dm.id_detalle_movilizacion,
                        mo.numero_certificado,
                        mo.provincia_emision,
                        mo.oficina_emision,
                        too.nombre operacion_origen,
                        sio.provincia provincia_origen,
                        sio.canton canton_origen,
                        sio.parroquia parroquia_origen,
                        sio.nombre_lugar sitio_origen,
                        opo.identificador identificador_operador_origen,
                        opo.razon_social razon_social_operador_origen,
                        opo.nombre_representante || ' ' || opo.apellido_representante nombre_operador_origen,
                        tod.nombre operacion_destino,
                        sid.provincia provincia_destino,
                        sid.canton canton_destino,
                        sid.parroquia parroquia_destino,
                        sid.nombre_lugar sitio_destino,
                        opd.identificador identificador_operador_destino,
                        opd.razon_social razon_social_operador_destino,
                        opd.nombre_representante || ' ' || opd.apellido_representante nombre_operador_destino,
                        mo.usuario_responsable identificacion_usuario_responsable,
                        (SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)
                        FROM g_uath.ficha_empleado rsv WHERE  mo.usuario_responsable= rsv.identificador )
                                else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end
                                FROM g_operadores.operadores oa WHERE  mo.usuario_responsable = oa.identificador ) end nombre_usuario_responsable
                                    FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT') and up.identificador=mo.usuario_responsable),
                        pr.nombre_comun producto,
                        dm.cantidad,
                        mo.identificador_conductor identificacion_conductor,
                        mo.nombre_conductor,
                        (SELECT tipo FROM g_catalogos.medios_transporte where id_medios_transporte=mo.medio_transporte)  medio_transporte,
                        mo.observacion,
                        mo.placa_transporte placa_transporte,
                        (to_char(mo.fecha_registro,'dd-mm-yyyy HH24:mi:ss')) fecha_registro,
                        (to_char(mo.fecha_inicio_vigencia,'dd-mm-yyyy HH24:mi')) fecha_inicio_vigencia,
                        (to_char(mo.fecha_fin_vigencia,'dd-mm-yyyy HH24:mi')) fecha_fin_vigencia,
                        mo.estado,
                        (to_char(mo.fecha_anulacion,'dd-mm-yyyy HH24:mi')) fecha_anulacion,
                        mo.observacion_anulacion,
                        mo.motivo_anulacion,
                        (select nombre_representante ||' '|| apellido_representante   AS nombre_solicitante from g_operadores.operadores os where os.identificador=mo.identificador_solicitante),
                        mo.identificador_solicitante
                    FROM  
                        g_movilizacion_producto.movilizacion mo, g_operadores.operadores opo, g_operadores.operadores opd, g_operadores.sitios sio,g_operadores.sitios sid,
                        g_movilizacion_producto.detalle_movilizacion dm, g_catalogos.productos pr ,g_catalogos.subtipo_productos stp, g_catalogos.tipos_operacion too, g_catalogos.tipos_operacion tod
                    WHERE
                        mo.sitio_origen=sio.id_sitio and sio.identificador_operador=opo.identificador and 
                        mo.sitio_destino=sid.id_sitio and sid.identificador_operador=opd.identificador and 
                        dm.id_movilizacion=mo.id_movilizacion and pr.id_producto=dm.producto and
                        too.id_tipo_operacion=dm.tipo_operacion_origen and
                        tod.id_tipo_operacion=dm.tipo_operacion_destino and
                        pr.id_subtipo_producto=stp.id_subtipo_producto and
                        (mo.fecha_registro between '$fecha 00:00:00' and '$fecha 24:00:00' 
                        OR fecha_anulacion between '$fecha 00:00:00' and '$fecha 24:00:00'
                        OR fecha_inicio_vigencia between '$fecha 00:00:00' and '$fecha 24:00:00'
		                OR fecha_fin_vigencia between '$fecha 00:00:00' and '$fecha 24:00:00')";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarMovilizacion($conexion, $valores)
    {
        $consulta = "INSERT INTO 
                            mag.movilizacion(
                            id_movilizacion, id_detalle_movilizacion, numero_certificado, 
                            provincia_emision, oficina_emision, operacion_origen, provincia_origen, 
                            canton_origen, parroquia_origen, sitio_origen, identificador_operador_origen, 
                            razon_social_operador_origen, nombre_operador_origen, operacion_destino, 
                            provincia_destino, canton_destino, parroquia_destino, sitio_destino, 
                            identificador_operador_destino, razon_social_operador_destino, 
                            nombre_operador_destino, identificacion_usuario_responsable, 
                            nombre_usuario_responsable, producto, cantidad, identificacion_conductor, 
                            nombre_conductor, medio_transporte, observacion, placa_transporte, 
                            fecha_registro, fecha_inicio_vigencia, fecha_fin_vigencia, estado, 
                            fecha_anulacion, observacion_anulacion, motivo_anulacion, nombre_solicitante, 
                            identificador_solicitante, fecha_actualizacion)
                        VALUES 
                            $valores;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function actualizarMovilizacion($conexion, $valores)
    {
        $consulta = "UPDATE mag.movilizacion as t1 set
                        estado = t2.estado,
                        fecha_anulacion = t2.fecha_anulacion,
                        observacion_anulacion = t2.observacion_anulacion,   
                        motivo_anulacion = t2.motivo_anulacion,                 
                        fecha_actualizacion = t2.fecha_actualizacion::timestamp without time zone
                    FROM 
                        (values
                            $valores
                        ) as t2(id_movilizacion, estado, fecha_anulacion, observacion_anulacion, motivo_anulacion, fecha_actualizacion)
                    WHERE
                        t1.id_movilizacion::text = t2.id_movilizacion;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }


    public function obtenerComposicion($conexion, $fecha)
    {

        $consulta = "SELECT
                            id_composicion,
                            ci.id_producto,
                            id_ingrediente_activo,
                            concentracion,
                            ci.unidad_medida
                        FROM g_catalogos.productos as p
                            INNER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                            INNER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
                            INNER JOIN g_catalogos.composicion_inocuidad as ci ON ci.id_producto =  p.id_producto
                        WHERE
                            tp.id_area IN ('IAP','IAV', 'IAF') and tp.nombre!='Cultivo'
                            and p.estado not in (9)
                            and ci.fecha_creacion_composicion_inocuidad between '$fecha 00:00:00' and '$fecha 24:00:00'";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarComposicion($conexion, $valores)
    {
        $consulta = "INSERT INTO 
                            mag.productos_composicion(id_composicion, id_producto, id_ingrediente_activo, concentracion, unidad_medida, fecha_actualizacion)
                        VALUES 
                            $valores
                            ON CONFLICT (id_composicion) DO NOTHING;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }


    public function obtenerFabricanteFormulador($conexion, $fecha)
    {

        $consulta = "SELECT
                            id_fabricante_formulador,
                            p.id_producto,
                            ff.nombre as fabricante_formulador,
                            id_pais_origen
                        FROM g_catalogos.productos as p
                            INNER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                            INNER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
                            INNER JOIN g_catalogos.fabricante_formulador ff ON ff.id_producto =  p.id_producto
                        WHERE
                            tp.id_area IN ('IAP','IAV','IAF') and tp.nombre!='Cultivo'
                            and p.estado not in (9)
                            and fecha_creacion_fabricante_formulador between '$fecha 00:00:00' and '$fecha 24:00:00'";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarFabricanteFormulador($conexion, $valores)
    {
        $consulta = "INSERT INTO 
                            mag.productos_fabricante_formulador(
                            id_fabricante_formulador, id_producto, fabricante_formulador, 
                            id_pais_origen, fecha_actualizacion)
                        VALUES 
                            $valores
                            ON CONFLICT (id_fabricante_formulador) DO NOTHING;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }


    public function obtenerPresentacion($conexion, $fecha)
    {

        $consulta = "SELECT
                            p.id_producto,
                            subcodigo,
                            trim(upper(coalesce(presentacion ||' '||ci.unidad_medida, presentacion))) as presentacion
                        FROM g_catalogos.productos as p
                            INNER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                            INNER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
                            INNER JOIN g_catalogos.codigos_inocuidad as ci ON ci.id_producto =  p.id_producto
                        WHERE
                            tp.id_area IN ('IAP','IAV', 'IAF') and tp.nombre!='Cultivo'
                            and p.estado not in (9)
                            and fecha_creacion_codigos_inocuidad between '$fecha 00:00:00' and '$fecha 24:00:00'";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarPresentacion($conexion, $valores)
    {
        $consulta = "INSERT INTO 
                            mag.productos_presentacion(id_producto, subcodigo, presentacion, fecha_actualizacion)
                        VALUES 
                            $valores
                            ON CONFLICT (id_producto, subcodigo) DO NOTHING;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }


    public function obtenerPlagasIavIaf($conexion, $fecha)
    {

        $consulta = "SELECT
                            id_producto_uso,
                            p.id_producto,
                            id_uso,
                            pr.id_producto as id_cultivo
                        FROM g_catalogos.productos as p
                            INNER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto
                            INNER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
                            INNER JOIN g_catalogos.producto_inocuidad_uso as piu ON piu.id_producto = p.id_producto
                            INNER JOIN g_catalogos.productos as pr ON pr.id_producto=piu.id_aplicacion_producto
                        WHERE
                            tp.id_area IN ('IAP','IAF') and tp.nombre!='Cultivo'
                            and p.estado not in (9)
                            and fecha_creacion_producto_inocuidad_uso between '$fecha 00:00:00' and '$fecha 24:00:00'";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarPlagasIavIaf($conexion, $valores)
    {
        $consulta = "INSERT INTO mag.productos_plagas_iap_iaf(id_producto_uso, id_producto, id_uso, id_cultivo, fecha_actualizacion)
                        VALUES 
                            $valores
                        ON CONFLICT (id_producto_uso) DO NOTHING;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }


    public function obtenerPlagasIav($conexion, $fecha)
    {

        $consulta = "SELECT
                            id_producto_uso, 
                            p.id_producto, 
                            id_uso, 
                            id_especie
                        FROM 
                            g_catalogos.productos as p
                            INNER JOIN g_catalogos.subtipo_productos as sp ON p.id_subtipo_producto = sp.id_subtipo_producto 
                            INNER JOIN g_catalogos.tipo_productos as tp ON sp.id_tipo_producto = tp.id_tipo_producto
                            INNER JOIN g_catalogos.producto_inocuidad_uso as piu ON piu.id_producto = p.id_producto
                        WHERE
                            tp.id_area = 'IAV' and tp.nombre!='Cultivo'
                            and p.estado not in (9)
                            and fecha_creacion_producto_inocuidad_uso between '$fecha 00:00:00' and '$fecha 24:00:00'
                        ORDER BY 1";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarPlagasIav($conexion, $valores)
    {
        $consulta = "INSERT INTO mag.productos_plagas_iav(id_producto_uso, id_producto, id_uso, id_especie, fecha_actualizacion)
                        VALUES 
                            $valores
                        ON CONFLICT (id_producto_uso) DO NOTHING;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }



    ///////////////// ELIMINACION DE REGISTROS /////////////////


    public function obtenerComposicionElimiandos($conexion, $fecha)
    {

        $consulta = "SELECT 
                            log_old_values->'id_composicion' as id_composicion
                        FROM
                            g_catalogos.auditoria_productos_ria
                        WHERE
                            log_table = 'composicion_inocuidad'
                            and log_operation = 'DELETE'
                            and log_when between '$fecha 00:00:00' and '$fecha 24:00:00'";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function eliminarComposicion($conexion, $valores)
    {
        $consulta = "DELETE FROM 
                                mag.productos_composicion
                            WHERE
                                id_composicion in ($valores);
                            ";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }


    public function obtenerFabricanteFormuladorEliminados($conexion, $fecha)
    {

        $consulta = "SELECT 
                            log_old_values->'id_fabricante_formulador' as id_fabricante_formulador
                        FROM
                            g_catalogos.auditoria_productos_ria
                        WHERE
                            log_table = 'fabricante_formulador'
                            and log_operation = 'DELETE'
                            and log_when between '$fecha 00:00:00' and '$fecha 24:00:00'";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function eliminarFabricanteFormulador($conexion, $valores)
    {
        $consulta = "DELETE FROM 
                                mag.productos_fabricante_formulador
                            WHERE
                                id_fabricante_formulador in ($valores);
                            ";
                                    
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }


    public function obtenerPresentacionEliminados($conexion, $fecha)
    {

        $consulta = "SELECT 
                            log_old_values->'id_producto' as id_producto, log_old_values->'subcodigo' as subcodigo
                        FROM
                            g_catalogos.auditoria_productos_ria
                        WHERE
                            log_table = 'codigos_inocuidad'
                            and log_operation = 'DELETE'
                            and log_when between '$fecha 00:00:00' and '$fecha 24:00:00'";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function eliminarPresentacion($conexion, $valores)
    {
        $consulta = "DELETE FROM 
                                 mag.productos_presentacion
                            WHERE
                                $valores;
                            ";
                                   
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }



    public function obtenerPlagasIapIafIavEliminados($conexion, $fecha)
    {

        $consulta = "SELECT 
                            log_old_values->'id_producto_uso' as id_producto_uso,
	                        log_old_values->'id_especie' as id_especie
                        FROM
                            g_catalogos.auditoria_productos_ria
                        WHERE
                            log_table = 'producto_inocuidad_uso'
                            and log_operation = 'DELETE'
                            and log_when between '$fecha 00:00:00' and '$fecha 24:00:00'";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function eliminarPlagasIapIafIav($conexion, $valores, $tabla)
    {
        $consulta = "DELETE FROM 
                                 mag.$tabla 
                            WHERE
                                id_producto_uso in ($valores);
                            ";
                              
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function obtenerVacuncacionAretes($conexion, $fecha){

        $consulta = "SELECT
                v.id_vacunacion, dc.id_detalle_vacunacion, di.id_detalle_identificador,v.numero_certificado,
                (SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  FROM g_uath.ficha_empleado rsv WHERE v.usuario_responsable = rsv.identificador )
                else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end
                FROM g_operadores.operadores oa WHERE v.usuario_responsable = oa.identificador ) end digitador
                FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT')
                and up.identificador=v.usuario_responsable),
                (SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  FROM g_uath.ficha_empleado rsv WHERE v.identificador_vacunador = rsv.identificador )
                else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end
                FROM g_operadores.operadores oa WHERE v.identificador_vacunador = oa.identificador ) end vacunador
                FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT')
                and up.identificador=v.identificador_vacunador),
                (SELECT case when p.codificacion_perfil='PFL_USUAR_INT' then (SELECT upper((rsv.nombre::text || ' '::text) || rsv.apellido::text)  FROM g_uath.ficha_empleado rsv WHERE v.identificador_distribuidor = rsv.identificador )
                else (SELECT case when oa.razon_social = '' then oa.nombre_representante ||' '|| oa.apellido_representante else oa.razon_social end
                FROM g_operadores.operadores oa WHERE v.identificador_distribuidor = oa.identificador ) end distribuidor
                FROM g_usuario.perfiles p, g_usuario.usuarios_perfiles up WHERE p.id_perfil=up.id_perfil and p.codificacion_perfil in ('PFL_USUAR_INT','PFL_USUAR_EXT')
                and up.identificador=v.identificador_distribuidor),
                s.provincia provincia_sitio,
                s.canton canton_sitio,
                s.parroquia parroquia_sitio,
                s.nombre_lugar nombre_sitio,
                od.identificador identificacion_propietario,
                CASE WHEN od.razon_social::text = ''::text THEN upper((od.nombre_representante::text || ' '::text) || od.apellido_representante::text)::character varying::text
                ELSE upper(od.razon_social::text)END AS nombre_propietario,
                pr.nombre_comun producto,
                di.identificador identificador_producto,
                tv.nombre_vacuna tipo_vacunacion,
                (to_char(v.fecha_registro,'dd/mm/yyyy HH24:mi:ss')) fecha_registro,
                (to_char(v.fecha_vacunacion,'dd/mm/yyyy')) fecha_vacunacion,
                v.estado as estado_vacunacion
                --,CASE WHEN (select extract(days from (to_char(v.fecha_vencimiento,'YYYY-MM-DD')::timestamp) - current_date)) >= 1 and (select extract(days from (to_char(v.fecha_vencimiento,'YYYY-MM-DD')::timestamp) - current_date)) <=15 THEN 'PROXIMO A CADUCAR' ELSE v.estado END as estado
            FROM
                g_catalogos.productos pr,
                g_catalogos.subtipo_productos stp,
                g_vacunacion.vacunacion v
                LEFT OUTER JOIN g_operadores.operadores as oa ON v.identificador_operador_vacunacion = oa.identificador
                LEFT OUTER JOIN g_operadores.sitios as s ON v.id_sitio = s.id_sitio
                LEFT OUTER JOIN g_vacunacion.detalle_vacunacion as dc ON v.id_vacunacion = dc.id_vacunacion
                LEFT OUTER JOIN g_vacunacion.detalle_identificadores as di ON di.id_detalle_vacunacion = dc.id_detalle_vacunacion
                LEFT OUTER JOIN g_operadores.operadores as od ON od.identificador=s.identificador_operador
                LEFT OUTER JOIN g_catalogos.tipo_vacunas as tv ON tv.id_tipo_vacuna=v.id_tipo_vacuna
            WHERE
                pr.id_producto=dc.id_producto 
                and pr.id_subtipo_producto=stp.id_subtipo_producto 
                and stp.id_subtipo_producto=736 
                and (NULL is NULL or s.provincia = NULL) 
                and (NULL is NULL or s.canton = NULL) 
                and (NULL is NULL or s.parroquia = NULL) 
                --and ('2022-08-01' is NULL or v.fecha_registro >= '2022-08-01') 
                --and ('2022-08-11' is NULL or v.fecha_registro <= '2022-08-11');
                and v.fecha_registro between '$fecha 00:00:00' and '$fecha 24:00:00'                
                ";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function insertarVacunacionAretes($conexion, $valores)
    {
        $consulta = "INSERT INTO mag.vacunacion_aretes(id_vacunacion, id_detalle_vacunacion, id_detalle_identificador, numero_certificado, digitador, vacunador, distribuidor, 
                        provincia_sitio, canton_sitio, parroquia_sitio, nombre_sitio, identificacion_propietario, nombre_propietario, producto, identificador_producto, 
                        tipo_vacunacion, fecha_registro, fecha_vacunacion, estado, fecha_actualizacion)        
                        VALUES 
                            $valores
                        ON CONFLICT (id_detalle_identificador) DO NOTHING;";

        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }
}
