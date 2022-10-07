<?php

class ControladorRIA
{

    public function listarTipos($conexion, $areas, $estado = null)
    {
        $condicion = '';
        if ($estado === 1) {
            $condicion = ' AND tp.estado = 1 ';
        } elseif ($estado === 0) {
            $condicion = ' AND tp.estado = 0 ';
        }
        $consulta = "
            SELECT
                tp.*,
                a.nombre AS nombre_area
            FROM
                g_catalogos.tipo_productos AS tp,
                g_estructura.area a
            WHERE
                tp.id_area = a.id_area
                AND tp.id_area IN ('" . implode($areas, "', '") . "')" . $condicion . "
            ORDER BY
                tp.id_area,
                tp.nombre;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarSubtipos($conexion, $areas, $estado = null)
    {
        $condicion = '';
        if ($estado === 1) {
            $condicion = ' AND sp.estado = 1 ';
        } elseif ($estado === 0) {
            $condicion = ' AND sp.estado = 0 ';
        }
        $consulta = "
            SELECT sp.*
            FROM
                g_catalogos.tipo_productos tp,
                g_catalogos.subtipo_productos sp
            WHERE
                tp.id_tipo_producto = sp.id_tipo_producto
                AND tp.id_area IN ('" . implode($areas, "', '") . "')" . $condicion . "
            ORDER BY
                sp.nombre;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarProductos($conexion, $idSubtipo, $estado = null)
    {
        $condicion = '';
        if ($estado === 1) {
            $condicion = ' AND p.estado = 1 ';
        } elseif ($estado === 0) {
            $condicion = ' AND p.estado = 0 ';
        }
        $consulta = "
            SELECT
                *
            FROM
                g_catalogos.productos p,
                g_catalogos.productos_inocuidad pi
            WHERE
                p.id_producto = pi.id_producto
                AND p.id_subtipo_producto = $idSubtipo" . $condicion . "
            ORDER BY
                p.nombre_comun;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarProductosPorArea($conexion, $areas)
    {

        $consulta = "
            SELECT
                p.*,
                stp.nombre AS nombre_subtipo,
                tp.id_area
            FROM
                g_catalogos.productos p,
                g_catalogos.productos_inocuidad pi,
                g_catalogos.subtipo_productos stp,
                g_catalogos.tipo_productos tp
            WHERE
                p.id_producto = pi.id_producto
                and p.id_subtipo_producto = stp.id_subtipo_producto
                and stp.id_tipo_producto = tp.id_tipo_producto
                and tp.id_area IN ('" . implode($areas, "', '") . "')
                and upper(stp.nombre) != 'CULTIVO'
            ORDER BY
                p.nombre_comun;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function abrirTipo($conexion, $idTipo)
    {
        $res = $conexion->ejecutarConsulta("SELECT
                                                tp.*,
                                                a.nombre AS nombre_area
											FROM
												g_catalogos.tipo_productos tp,
												g_estructura.area a
											WHERE
											    tp.id_area = a.id_area
												AND tp.id_tipo_producto = $idTipo;");
        return $res;
    }

    public function guardarTipo($conexion, $nombre, $area)
    {
        $res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.tipo_productos (nombre, estado, id_area)
											VALUES
												('$nombre', 1, '$area')
											RETURNING
												id_tipo_producto;");
        return $res;
    }

    public function modificarTipo($conexion, $idTipo, $nombre, $estado)
    {
        $res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.tipo_productos
											SET
												nombre = '$nombre',
												estado = '$estado'
											where
												id_tipo_producto = $idTipo;");
        return $res;
    }

    public function abrirSubtipo($conexion, $idSubtipo)
    {
        $res = $conexion->ejecutarConsulta("SELECT
                                                st.*,
                                                tp.nombre AS nombre_tipo
											FROM
												g_catalogos.subtipo_productos st,
												g_catalogos.tipo_productos tp
											WHERE
											    st.id_tipo_producto = tp.id_tipo_producto
												AND st.id_subtipo_producto = $idSubtipo;");
        return $res;
    }

    public function guardarSubtipo($conexion, $nombre, $idTipoProducto)
    {
        $res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.subtipo_productos (nombre, estado, id_tipo_producto)
											VALUES
												('$nombre', 1, '$idTipoProducto')
											RETURNING
												id_subtipo_producto;");
        return $res;
    }

    public function modificarSubtipo($conexion, $idSubtipo, $idTipo, $nombre, $estado)
    {
        $res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.subtipo_productos
											SET
												id_tipo_producto = '$idTipo',
												nombre = '$nombre',
												estado = '$estado'
											where
												id_subtipo_producto = $idSubtipo;");
        return $res;
    }

    public function listarComposiciones($conexion, $estado = null)
    {
        $condicion = '';
        if ($estado === 1) {
            $condicion = ' WHERE estado = 1 ';
        } elseif ($estado === 0) {
            $condicion = ' WHERE estado = 0 ';
        }
        $consulta = "
            SELECT
                *
            FROM
                g_catalogos.composicion
            " . $condicion . "
            ORDER BY
                nombre;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function abrirComposicion($conexion, $idComposicion)
    {
        $res = $conexion->ejecutarConsulta("SELECT
                                                *
											FROM
												g_catalogos.composicion
											WHERE
											    id_composicion = $idComposicion;");
        return $res;
    }

    public function modificarComposicion($conexion, $idComposicion, $idArea, $idCategoriaToxicologica)
    {
        $res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.composicion
											SET
											    id_area = '$idArea',
												id_categoria_toxicologica = $idCategoriaToxicologica
											WHERE
												id_composicion = $idComposicion;");
        return $res;
    }

    public function listarCategoriasToxicologicas($conexion, $areas)
    {
        $consulta = "
            SELECT
                *
            FROM
                g_catalogos.categoria_toxicologica
            WHERE
                id_area IN ('" . implode($areas, "', '") . "');";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarUnidadesMedida($conexion)
    {
        $consulta = "
            SELECT
                *
            FROM
                g_catalogos.unidades_medidas
            ORDER BY
                tipo_unidad, nombre;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function guardarComposicion($conexion, $idArea, $idCategoriaToxicologica)
    {
        $res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.composicion (id_area, id_categoria_toxicologica, estado)
											VALUES
												('$idArea', '$idCategoriaToxicologica', 1)
											RETURNING
												id_composicion;");
        return $res;
    }

    public function modificarNombreComposicion($conexion, $idComposicion)
    {
        $ingredientes = $this->listarComposicionIngredienteActivo($conexion, $idComposicion);
        $nombre = '';

        while ($ingrediente = pg_fetch_assoc($ingredientes)) {
            if ($nombre != '') {
                $nombre .= ' + ';
            }
            $nombre .= $ingrediente['ingrediente_activo'] . ' (' . $ingrediente['concentracion'] . ' ' . $ingrediente['unidad_medida'] . ')';
        }

        $res = $conexion->ejecutarConsulta("UPDATE
												g_catalogos.composicion
											SET
												nombre = '$nombre'
											WHERE
											    id_composicion = $idComposicion;");
        return $nombre;
    }

    public function listarComposicionIngredienteActivo($conexion, $idComposicion)
    {
        $consulta = "
            SELECT
                cia.*,
                iai.ingrediente_activo
            FROM
                g_catalogos.composicion_ingrediente_activo cia,
                g_catalogos.ingrediente_activo_inocuidad iai
            WHERE
              cia.id_composicion = $idComposicion
              AND cia.id_ingrediente_activo = iai.id_ingrediente_activo
            ORDER BY
                iai.ingrediente_activo;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function nuevoComposicionIngredienteActivo($conexion, $idComposicion, $idIngredienteActivo, $concentracion, $unidad, $restriccion)
    {
        $res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.composicion_ingrediente_activo (id_composicion, id_ingrediente_activo, concentracion, unidad_medida, restriccion)
											VALUES
												($idComposicion, $idIngredienteActivo, '$concentracion', '$unidad', '$restriccion')
											RETURNING
												id_composicion, id_ingrediente_activo;");
        return $res;
    }

    public function eliminarComposicionIngredienteActivo($conexion, $idComposicion, $idIngredienteActivo)
    {
        $res = $conexion->ejecutarConsulta("DELETE FROM
												g_catalogos.composicion_ingrediente_activo
											WHERE
												id_composicion = $idComposicion
												AND id_ingrediente_activo = $idIngredienteActivo;");
        return $res;
    }

    public function imprimirLineaComposicionIngredienteActivo($ids, $nombreIngredienteActivo)
    {
        return '<tr id="R' . $ids . '">' .
        '<td width="100%">' .
        $nombreIngredienteActivo .
        '</td>' .
        '<td>' .
        '<form class="borrar" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="eliminarComposicionIngredienteActivo">' .
        '<input type="hidden" name="ids" value="' . $ids . '" >' .
        '<button type="submit" class="icono"></button>' .
        '</form>' .
        '</td>' .
        '</tr>';
    }

    public function listarIngredientesActivos($conexion, $areas, $estado = null)
    {
        $condicion = '';
        if ($estado === 1) {
            $condicion = ' AND estado = 1 ';
        } elseif ($estado === 0) {
            $condicion = ' AND estado = 0 ';
        }
        $consulta = "
            SELECT
                *
            FROM
                g_catalogos.ingrediente_activo_inocuidad
            WHERE
                id_area IN ('" . implode($areas, "', '") . "')" . $condicion . "
            ORDER BY
                ingrediente_activo;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function abrirIngredienteActivo($conexion, $idIngredienteActivo)
    {
        $res = $conexion->ejecutarConsulta("SELECT
                                                iai.*,
                                                a.nombre AS nombre_area
											FROM
												g_catalogos.ingrediente_activo_inocuidad iai,
												g_estructura.area a
											WHERE
											    iai.id_area = a.id_area
											    AND iai.id_ingrediente_activo = $idIngredienteActivo;");
        return $res;
    }

    public function guardarIngredienteActivo($conexion, $nombreIngredienteActivo, $casIngredienteActivo, $restriccionIngredienteActivo, $area)
    {
        $res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.ingrediente_activo_inocuidad (ingrediente_activo, cas, estado_ingrediente_activo, restriccion, id_area)
											VALUES
												('$nombreIngredienteActivo', '$casIngredienteActivo', 1, '$restriccionIngredienteActivo', '$area')
											RETURNING
												id_ingrediente_activo;");
        return $res;
    }

    public function modificarIngredienteActivo($conexion, $idIngredienteActivo, $nombreIngredienteActivo, $casIngredienteActivo, $estadoIngredienteActivo, $restriccionIngredienteActivo)
    {
        $conexion->ejecutarConsulta("begin;");
        try {
            $res = $conexion->ejecutarConsulta("UPDATE
                                                    g_catalogos.ingrediente_activo_inocuidad
                                                SET
                                                    ingrediente_activo = '$nombreIngredienteActivo',
                                                    cas = '$casIngredienteActivo',
                                                    estado_ingrediente_activo = '$estadoIngredienteActivo',
                                                    restriccion = '$restriccionIngredienteActivo'
                                                WHERE
                                                    id_ingrediente_activo = $idIngredienteActivo;");

            //ACTUALIZA LAS COMPOSICIONES Y PRODUCTOS
            if ($estadoIngredienteActivo == 0) {
                $res2 = $conexion->ejecutarConsulta("WITH actualizados AS (
                                                        UPDATE
                                                            g_catalogos.composicion c
                                                        SET
                                                            estado = 0
                                                        FROM
                                                            g_catalogos.composicion_ingrediente_activo cia
                                                        WHERE
                                                            c.id_composicion = cia.id_composicion
                                                            AND cia.id_ingrediente_activo = $idIngredienteActivo
                                                        RETURNING
                                                            c.id_composicion
                                                        )
                                                    UPDATE
                                                        g_catalogos.productos
                                                    SET
                                                        estado = 0
                                                    WHERE
                                                        id_producto IN (
                                                            SELECT
                                                                pi.id_producto
                                                            FROM
                                                                g_catalogos.productos_inocuidad pi, actualizados a
                                                            WHERE
                                                                pi.id_composicion = a.id_composicion
                                                            );");

            } elseif ($estadoIngredienteActivo == 1) {
                $res2 = $conexion->ejecutarConsulta("WITH actualizados AS (
                                                        UPDATE
                                                            g_catalogos.composicion c
                                                        SET
                                                            estado = 1
                                                        FROM
                                                            g_catalogos.composicion_ingrediente_activo cia
                                                        WHERE
                                                            c.id_composicion = cia.id_composicion
                                                            AND cia.id_ingrediente_activo = $idIngredienteActivo
                                                            AND 1 = ALL (
                                                                SELECT
                                                                    iai.estado_ingrediente_activo
                                                                FROM
                                                                    g_catalogos.composicion_ingrediente_activo cia2,
                                                                    g_catalogos.ingrediente_activo_inocuidad iai
                                                                WHERE
                                                                    cia2.id_ingrediente_activo = iai.id_ingrediente_activo
                                                                    AND cia2.id_composicion = c.id_composicion
                                                            )
                                                        RETURNING
                                                            c.id_composicion
                                                        )
                                                    UPDATE
                                                        g_catalogos.productos
                                                    SET
                                                        estado = 1
                                                    WHERE
                                                        id_producto IN (
                                                            SELECT
                                                                pi.id_producto
                                                            FROM
                                                                g_catalogos.productos_inocuidad pi, actualizados a
                                                            WHERE
                                                                pi.id_composicion = a.id_composicion
                                                            );");
            }

            $conexion->ejecutarConsulta("commit;");
            return $res;
        } catch (Exception $ex) {
            $conexion->ejecutarConsulta("rollback;");
        }
    }

    public function listarUsos($conexion, $areas)
    {
        $consulta = "
            SELECT
                *
            FROM
                g_catalogos.usos
            WHERE
                id_area IN ('" . implode($areas, "', '") . "')
            ORDER BY
                nombre_uso;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function abrirUso($conexion, $idUso)
    {
        $res = $conexion->ejecutarConsulta("SELECT
                                                u.*,
                                                a.nombre AS nombre_area
											FROM
												g_catalogos.usos u,
												g_estructura.area a
											WHERE
											    u.id_area = a.id_area
											    AND u.id_uso = $idUso;");
        return $res;
    }

    public function listarComposicionUsos($conexion, $idComposicion)
    {
        $consulta = "
            SELECT
                cu.*,
                u.nombre_uso
            FROM
                g_catalogos.composicion_usos cu,
                g_catalogos.usos u
            WHERE
              cu.id_composicion = $idComposicion
              AND cu.id_uso = u.id_uso
            ORDER BY
                u.nombre_uso;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function nuevoComposicionUso($conexion, $idComposicion, $idUso)
    {
        $res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.composicion_usos (id_composicion, id_uso)
											VALUES
												($idComposicion, $idUso)
											RETURNING
												id_composicion, id_uso;");
        return $res;
    }

    public function eliminarComposicionUso($conexion, $idComposicion, $idUso)
    {
        $res = $conexion->ejecutarConsulta("DELETE FROM
												g_catalogos.composicion_usos
											WHERE
												id_composicion = $idComposicion
												AND id_uso = $idUso;");
        return $res;
    }

    public function imprimirLineaComposicionUso($ids, $nombreUso)
    {
        return '<tr id="R' . $ids . '">' .
        '<td width="100%">' .
        $nombreUso .
        '</td>' .
        '<td>' .
        '<form class="borrar" data-rutaAplicacion="registroInsumosAgropecuarios" data-opcion="eliminarComposicionUso">' .
        '<input type="hidden" name="ids" value="' . $ids . '" >' .
        '<button type="submit" class="icono"></button>' .
        '</form>' .
        '</td>' .
        '</tr>';
    }

    public function abrirProducto($conexion, $idProducto)
    {
        $consulta = "
            select
                *, stp.nombre AS nombre_subtipo
            from
                g_catalogos.productos p,
                g_catalogos.productos_inocuidad pi,
                g_catalogos.tipo_productos tp,
                g_catalogos.subtipo_productos stp
            where
		        tp.id_tipo_producto = stp.id_tipo_producto
		        and stp.id_subtipo_producto = p.id_subtipo_producto
                and p.id_producto = pi.id_producto
                and p.id_producto = $idProducto;
        ";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarComposicionesPorCoincidencia($conexion, $cadenaDeBusqueda)
    {
        $consulta = "
            	select
                    *
                from
                    g_catalogos.composicion
                where
                    upper(nombre) similar to upper('%($cadenaDeBusqueda)%')
                    and estado=1
                order by
                    nombre;

        ";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarTiposSubtipos($conexion, $areas, $estado)
    {
        $res = $conexion->ejecutarConsulta("
                    select row_to_json(listado)
                    from (
                        select array_to_json(array_agg(row_to_json(tipos)))
                        from (
                            select
                                tp.nombre,
                                (
                                    select array_to_json(array_agg(row_to_json(subtipos)))
                                    from (
                                        select
                                            stp.id_subtipo_producto, stp.nombre
                                        from
                                            g_catalogos.subtipo_productos stp
                                        where
                                            stp.estado=$estado
                                            and tp.id_tipo_producto = stp.id_tipo_producto
                                            and upper(stp.nombre) != 'CULTIVO'
                                        order by
                                            stp.nombre
                                    ) as subtipos
                                )
                            from
                                g_catalogos.tipo_productos tp
                            where
                                tp.id_area IN ('" . implode($areas, "', '") . "')
                                and tp.estado=$estado
                                and upper(tp.nombre) != 'CULTIVO'
                            order by
                                tp.nombre
                ) as tipos)
            as listado;");
        $json = pg_fetch_assoc($res);
        return json_decode($json['row_to_json'],true);
    }

    public function listarEmpresasPorCoincidencia($conexion, $cadenaDeBusqueda)
    {
        $consulta = "
            	select
                    *
                from
                    g_operadores.operadores
                where
                    upper(razon_social) similar to upper('%($cadenaDeBusqueda)%')
                    and validacion_sri='TRUE'
                order by
                    razon_social;

        ";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarEnfermedadesPorCoincidencia($conexion, $cadenaDeBusqueda)
    {
        $consulta = "
            	select
                    *
                from
                    g_catalogos.enfermedades_inocuidad
                where
                    upper(nombre) similar to upper('%($cadenaDeBusqueda)%')
                order by
                    nombre;

        ";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function guardarProducto($conexion, $idSubtipo, $nombreComun, $partidaArancelaria, $viaAdministracion, $codigoProducto, $idComposicion, $idEmpresa, $idPais, $idFabricante)
    {
        try {
            $res = $conexion->ejecutarConsulta("INSERT INTO
                                                    g_catalogos.productos (nombre_comun, partida_arancelaria, codigo_producto, estado, id_subtipo_producto)
                                                VALUES
                                                    ('$nombreComun', '$partidaArancelaria', '$codigoProducto', 1, $idSubtipo)
                                                RETURNING
                                                    id_producto;");
            $id = pg_fetch_row($res);
            $res2 = $conexion->ejecutarConsulta("INSERT INTO
                                                    g_catalogos.productos_inocuidad (id_producto, id_operador, id_composicion, via_administracion, id_pais, id_fabricante)
                                                VALUES
                                                    ($id[0], $idEmpresa, $idComposicion, '$viaAdministracion', $idPais, $idFabricante)
                                                RETURNING
                                                    id_producto, codigo_secuencial;");


            return $res2;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function listarAditivos($conexion, $areas, $estado = null)
    {
        $condicion = '';
        if ($estado === 1) {
            $condicion = ' AND estado = 1 ';
        } elseif ($estado === 0) {
            $condicion = ' AND estado = 0 ';
        }
        $consulta = "
            SELECT
                *
            FROM
                g_catalogos.aditivo_inocuidad
            WHERE
                id_area IN ('" . implode($areas, "', '") . "')" . $condicion . "
            ORDER BY
                nombre;";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarUsosPorComposicion($conexion, $idComposicion)
    {
        $consulta = "
            select row_to_json(listado)
            from (
                select array_to_json(array_agg(row_to_json(usos)))
                from (
                    SELECT
                        *
                    FROM
                        g_catalogos.composicion_usos cu,
                        g_catalogos.usos u
                    WHERE
                        cu.id_composicion = $idComposicion
                        AND cu.id_uso = u.id_uso
                    ORDER BY
                        u.nombre_uso
                ) as usos
            )  as listado;";
        $res = $conexion->ejecutarConsulta($consulta);
        $json = pg_fetch_assoc($res);
        return json_decode($json['row_to_json'], true);
    }

    public function listarProductosPorNombre($conexion, $nombre)
    {
        $consulta = "
            	select
                    *
                from
                    g_catalogos.productos
                where
                    upper(nombre_comun) = upper('$nombre')
                order by
                    nombre_comun;

        ";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function nuevoProductoUso($conexion, $idProducto, $idUso, $idEnfermedad, $dosisCompleta, $idAplicacion, $productoConsumo, $periodoCompleto)
    {
        $res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.producto_inocuidad_uso_v2 (id_producto, id_uso, id_aplicacion_producto, dosis, id_enfermedad, producto_consumo, periodo)
											VALUES
												($idProducto, $idUso, '$idAplicacion', '$dosisCompleta', $idEnfermedad, '$productoConsumo', '$periodoCompleto')
											RETURNING
												id_producto_inocuidad_uso;");
        return $res;
    }

    public function eliminarProductoUso($conexion, $idProductoUso)
{
    $res = $conexion->ejecutarConsulta("DELETE FROM
                                            g_catalogos.producto_inocuidad_uso_v2
                                        WHERE
                                            id_producto_inocuidad_uso = $idProductoUso
                                        RETURNING
                                            id_producto;");
    return $res;
}

    public function imprimirLineaProductoUso($id, $nombreUso)
    {
        return '<tr id="RPU_' . $id . '">' .
        '<td width="100%">' .
        $nombreUso .
        '</td>' .
        '<td>' .
        '<form class="borrar" data-rutaAplicacion="../../registroInsumosAgropecuarios" data-opcion="eliminarProductoUso">' .
        '<input type="hidden" name="id" value="' . $id . '" >' .
        '<button type="submit" class="icono"></button>' .
        '</form>' .
        '</td>' .
        '</tr>';
    }



    public function nuevoProductoAditivo($conexion, $idProducto, $idAditivo, $concentracion, $unidad)
    {
        $res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.producto_inocuidad_aditivo (id_producto, id_aditivo, concentracion, unidad_medida)
											VALUES
												($idProducto, $idAditivo, '$concentracion', '$unidad')
											RETURNING
												id_producto, id_aditivo;");
        return $res;
    }

    public function eliminarProductoAditivo($conexion, $idProducto, $idAditivo)
    {
        $res = $conexion->ejecutarConsulta("DELETE FROM
												g_catalogos.producto_inocuidad_aditivo
											WHERE
												id_producto = $idProducto
												AND id_aditivo = $idAditivo;");
        return $res;
    }

    public function imprimirLineaProductoAditivo($ids, $texto)
    {
        return '<tr id="RPA_' . $ids . '">' .
        '<td width="100%">' .
        $texto .
        '</td>' .
        '<td>' .
        '<form class="borrar" data-rutaAplicacion="../../registroInsumosAgropecuarios" data-opcion="eliminarProductoAditivo">' .
        '<input type="hidden" name="ids" value="' . $ids . '" >' .
        '<button type="submit" class="icono"></button>' .
        '</form>' .
        '</td>' .
        '</tr>';
    }

    public function obtenerCodigoProducto($conexion, $idProducto)
    {
        $codigosUso = $conexion->ejecutarConsulta("SELECT
                                                        DISTINCT u.codificacion
                                                    FROM
                                                        g_catalogos.usos u, g_catalogos.producto_inocuidad_uso_v2 piu
                                                    WHERE
                                                        u.id_uso = piu.id_uso
                                                        AND piu.id_producto = $idProducto
                                                    ORDER BY
                                                        u.codificacion;");
        $codigo = "";
        while($codigoUso = pg_fetch_assoc($codigosUso)) {
            if ($codigo != "") {
                $codigo .= "/";
            }
            $codigo .= $codigoUso["codificacion"];
        }
        $datos = pg_fetch_row($conexion->ejecutarConsulta("SELECT
                                                                pi.id_composicion, c.id_area, pi.codigo_secuencial
                                                            FROM
                                                                g_catalogos.productos_inocuidad pi, g_catalogos.composicion c
                                                            WHERE
                                                                pi.id_composicion = c.id_composicion
                                                                AND pi.id_producto = $idProducto;"));
        $codigo = $datos[0] . substr($datos[1], -1) . $datos[2] . ($codigo == "" ? "" : "-" . $codigo);
        return $codigo;
    }

    public function listarFabricantePorCoincidencia($conexion, $cadenaDeBusqueda)
    {
        $consulta = "
            	select
                    *
                from
                    g_catalogos.fabricante
                where
                    upper(nombre) similar to upper('%($cadenaDeBusqueda)%')
                order by
                    nombre;

        ";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function nuevoFabricanteIngredienteProducto($conexion, $idFabricante, $idProducto, $idIngredienteActivo, $idPais)
    {
        $res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.fabricante_ingrediente_producto (id_fabricante, id_producto, id_ingrediente_activo, id_pais)
											VALUES
												($idFabricante, $idProducto, $idIngredienteActivo, $idPais)
											RETURNING
												id_fabricante, id_producto, id_ingrediente_activo, id_pais;");
        return $res;
    }


    public function eliminarFabricanteIngredienteProducto($conexion, $idFabricante, $idProducto, $idIngredienteActivo, $idPais)
    {
        $res = $conexion->ejecutarConsulta("DELETE FROM
												g_catalogos.fabricante_ingrediente_producto
											WHERE
											    id_fabricante = $idFabricante
											    AND id_producto = $idProducto
											    AND id_ingrediente_activo = $idIngredienteActivo
												AND id_pais = $idPais;");
        return $res;
    }

    public function imprimirLineaFabricanteIngredienteProducto($ids, $texto)
    {
        return '<tr id="RFIP_' . $ids . '">' .
        '<td width="100%">' .
        $texto .
        '</td>' .
        '<td>' .
        '<form class="borrar" data-rutaAplicacion="../../registroInsumosAgropecuarios" data-opcion="eliminarFabricanteIngredienteProducto">' .
        '<input type="hidden" name="ids" value="' . $ids . '" >' .
        '<button type="submit" class="icono"></button>' .
        '</form>' .
        '</td>' .
        '</tr>';
    }

    public function nuevoFabricante($conexion, $nombreFabricante)
    {
        $res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.fabricante (nombre)
											VALUES
												('$nombreFabricante')
											RETURNING
												id_fabricante;");
        return $res;
    }

    public function listarAplicacionesPorCoincidencia($conexion, $cadenaDeBusqueda, $area)
    {
        $consulta = "
            	select
                    *
                from
                    g_catalogos.aplicacion_producto
                where
                    upper(aplicacion_producto) similar to upper('%($cadenaDeBusqueda)%')
                    and id_area = '$area'
                order by
                    aplicacion_producto;

        ";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function nuevaAplicacion($conexion, $aplicacion, $area)
    {
        $res = $conexion->ejecutarConsulta("INSERT INTO
												g_catalogos.aplicacion_producto (aplicacion_producto, id_area)
											VALUES
												('$aplicacion','$area')
											RETURNING
												id_aplicacion_producto;");
        return $res;
    }

    public function contarUsosDeProducto($conexion, $idProducto)
    {
        $res = $conexion->ejecutarConsulta("
                    select
                        count(piu.id_producto) as usos
                    from
                        g_catalogos.producto_inocuidad_uso_v2 piu
                    where
                        piu.id_producto = $idProducto");
        return $res;
    }

    public function contarFabricantesDeIngredienteFaltantes($conexion, $idProducto)
    {
        $res = $conexion->ejecutarConsulta("
                    select
                        count(coalesce(f.id_ingrediente_activo,1)) as fabricantes
                    from
                        g_catalogos.composicion_ingrediente_activo cia left outer join
                            (select
                                fip.id_ingrediente_activo
                            from
                                g_catalogos.fabricante_ingrediente_producto fip
                            where
                                fip.id_producto = $idProducto) f
                            on(cia.id_ingrediente_activo = f.id_ingrediente_activo ),
                        g_catalogos.productos_inocuidad pi
                    where
                        pi.id_producto = $idProducto
                        and pi.id_composicion = cia.id_composicion
                        and f.id_ingrediente_activo is null");
        return $res;
    }

    public function finalizarProductoInocuidad($conexion, $idProducto)
    {
        $res = $conexion->ejecutarConsulta("
                    update
                        g_catalogos.productos_inocuidad
                    set
                        estado = 'finalizado'
                    where
                        id_producto = $idProducto");
        return $res;
    }

    public function abrirFabricante($conexion, $idFabricante)
    {
        $res = $conexion->ejecutarConsulta("
                    select
                        *
                    from
                        g_catalogos.fabricante f
                    where
                        f.id_fabricante = $idFabricante");
        return $res;
    }

    public function abrirPais($conexion, $idPais)
    {
        $res = $conexion->ejecutarConsulta("
                    select
                        *
                    from
                        g_catalogos.localizacion l
                    where
                        l.id_localizacion = $idPais");
        return $res;
    }

    public function abrirEmpresa($conexion, $idEmpresa)
    {
        $res = $conexion->ejecutarConsulta("
                    select
                        *
                    from
                        g_operadores.operadores
                    where
                        identificador = '$idEmpresa'");
        return $res;
    }

    public function listarIngredientesProducto($conexion, $idProducto)
    {
        $consulta = "
            	SELECT
                    *,
                    f.nombre AS nombre_fabricante,
                    l.nombre AS nombre_pais
                FROM
                    g_catalogos.ingrediente_activo_inocuidad iai,
                    g_catalogos.fabricante_ingrediente_producto fip,
                    g_catalogos.fabricante f,
                    g_catalogos.localizacion l
                WHERE
                    iai.id_ingrediente_activo = fip.id_ingrediente_activo
                    AND fip.id_fabricante = f.id_fabricante
                    AND fip.id_pais = l.id_localizacion
                    AND fip.id_producto = $idProducto
                ORDER BY
                    iai.ingrediente_activo;

        ";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarAditivosProducto($conexion, $idProducto)
    {
        $consulta = "
            	SELECT
                    *
                FROM
                    g_catalogos.aditivo_inocuidad ai,
                    g_catalogos.producto_inocuidad_aditivo pia
                WHERE
                    ai.id_aditivo = pia.id_aditivo
                    AND pia.id_producto = $idProducto
                ORDER BY
                    ai.nombre;

        ";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function listarUsosProducto($conexion, $idProducto)
    {
        $consulta = "
            	SELECT
                    *
                FROM
                    g_catalogos.producto_inocuidad_uso_v2 piu,
                    g_catalogos.aplicacion_producto ap,
					g_catalogos.enfermedades_inocuidad e,
                    g_catalogos.usos u
                WHERE
                    piu.id_uso = u.id_uso
                    AND piu.id_aplicacion_producto = ap.id_aplicacion_producto
                    AND piu.id_enfermedad = e.id_enfermedad
                    AND piu.id_producto = $idProducto
                ORDER BY
                    u.nombre_uso;

        ";
        $res = $conexion->ejecutarConsulta($consulta);
        return $res;
    }

    public function duplicarProducto($conexion, $idProducto, $codigoArancelProducto, $idSubtipo, $partidaArancelaria)
    {
        try {

            $conexion->ejecutarConsulta("begin;");

            //Tabla productos
            $res = pg_fetch_row($conexion->ejecutarConsulta("
                        INSERT INTO g_catalogos.productos(
                                    nombre_comun, nombre_cientifico, partida_arancelaria,
                                    codigo_producto, subcodigo_producto, ruta, estado, id_subtipo_producto,
                                    certificado_semillas, licencia_magap, unidad_medida, fecha_creacion,
                                    fecha_modificacion)
                        (SELECT nombre_comun, nombre_cientifico, $partidaArancelaria,
                               '$codigoArancelProducto', subcodigo_producto, ruta, estado, $idSubtipo,
                               certificado_semillas, licencia_magap, unidad_medida, fecha_creacion,
                               fecha_modificacion
                        FROM g_catalogos.productos
                        WHERE id_producto = $idProducto)
                        RETURNING id_producto;"));
            $idProductoDuplicado = $res[0];

            //Tabla productos_incuidad
            $res = $conexion->ejecutarConsulta("
                        INSERT INTO g_catalogos.productos_inocuidad(
                                    id_producto, composicion, formulacion, fecha_creacion, fecha_modificacion,
                                    id_formulacion, numero_registro, dosis, periodo_carencia_retiro,
                                    periodo_reingreso, observacion, unidad_dosis, id_categoria_toxicologica,
                                    categoria_toxicologica, fecha_registro, id_operador, fecha_vencimiento,
                                    fecha_revaluacion, ingrediente_activo, declaracion_venta, id_composicion,
                                    via_administracion, estado, id_pais, id_fabricante, codigo_secuencial)
                        SELECT $idProductoDuplicado, composicion, formulacion, fecha_creacion, fecha_modificacion,
                               id_formulacion, numero_registro, dosis, periodo_carencia_retiro,
                               periodo_reingreso, observacion, unidad_dosis, id_categoria_toxicologica,
                               categoria_toxicologica, fecha_registro, id_operador, fecha_vencimiento,
                               fecha_revaluacion, ingrediente_activo, declaracion_venta, id_composicion,
                               via_administracion, estado, id_pais, id_fabricante, codigo_secuencial
                        FROM g_catalogos.productos_inocuidad
                        WHERE id_producto = $idProducto;");

            //Tabla fabricante_ingrediente_producto
            $res = $conexion->ejecutarConsulta("
                        INSERT INTO g_catalogos.fabricante_ingrediente_producto(
                                    id_fabricante, id_producto, id_ingrediente_activo, id_pais)
                        SELECT id_fabricante, $idProductoDuplicado, id_ingrediente_activo, id_pais
                        FROM g_catalogos.fabricante_ingrediente_producto
                        WHERE id_producto = $idProducto;");

            //Tabla productos_aditivos_inocuidad
            $res = $conexion->ejecutarConsulta("
                        INSERT INTO g_catalogos.productos_aditivos_inocuidad(
                                    id_producto, id_aditivo, concentracion, unidad_medida)
                        SELECT $idProductoDuplicado, id_aditivo, concentracion, unidad_medida
                        FROM g_catalogos.productos_aditivos_inocuidad
                        WHERE id_producto = $idProducto;");

            //Tabla producto_inocuidad_uso_v2
            $res = $conexion->ejecutarConsulta("
                        INSERT INTO g_catalogos.producto_inocuidad_uso_v2(
                                    id_producto, id_uso, dosis, id_enfermedad, producto_consumo,
                                    periodo, id_aplicacion_producto)
                        SELECT $idProductoDuplicado, id_uso, dosis, id_enfermedad, producto_consumo,
                               periodo, id_aplicacion_producto
                        FROM g_catalogos.producto_inocuidad_uso_v2
                        WHERE id_producto = $idProducto;");

            $conexion->ejecutarConsulta("commit;");

        } catch (Exception $ex) {
            $conexion->ejecutarConsulta("rollback;");
            throw $ex;
        }
    }
}