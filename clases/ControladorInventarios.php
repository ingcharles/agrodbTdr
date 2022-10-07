<?php

    /**
     * Created by PhpStorm.
     * User: Carlos Eduardo
     * Date: 29/08/14
     * Time: 11:01 AM
     */
    class ControladorInventario
    {


        public function asignarItemPorSerial($conexion, $identificador, $serial)
        {
            $res = $conexion->ejecutarConsulta("insert into
                                                    g_inventarios.asignaciones (identificador, id_area, id_item, fecha_asignacion)
                                                    values ('$identificador',
                                                            (
                                                                select id_area
                                                                from g_estructura.funcionarios
                                                                where identificador = '$identificador'
                                                            ),
                                                            (
                                                                select id_item
                                                                from g_inventarios.items
                                                                where serial = '$serial'
                                                            ),
                                                            now())
                                                    returning id_asignacion;");
            return $res;
        }

        public function listarEquiposAsignados($conexion)
        {
            $res = $conexion->ejecutarConsulta("select *
                                                from g_inventarios.asignaciones
            									order by id_area, identificador ;");
            return $res;
        }
    }