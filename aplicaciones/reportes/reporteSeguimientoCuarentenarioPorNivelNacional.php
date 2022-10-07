<?php

session_start();

include_once '../../clases/Conexion.php';
include_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorSeguimientoCuarentenario.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cs = new ControladorSeguimientoCuarentenario();


$tipoOperacion = $cc->obtenerTiposOperacionPorIdAreaTematica($conexion, "SV");
$tipoProducto = $cc->listarTipoProductosXareas($conexion, "='SV'");
$provincia = $cc->listarLocalizacion($conexion, 'PROVINCIAS');
$productos = $cs->listarProductosSeguimientoSA($conexion);

?>
<style>
    input[type="text"],
    select {
        width: 100%;
        box-sizing: border-box;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
    }
</style>
<header>
    <nav>
        <form id="reporteSeguimientoCuarentenario" action="aplicaciones/reportes/generarReporteSeguimientoCuarentenarioSA.php" data-rutaAplicacion='reportes' method="post">
            <input type="hidden" id="opcion" name="opcion" />            
            <table class="filtro">
                <tbody>
                    <tr>
                        <th style="text-align: center;" colspan="2">REPORTE DE SEGUIMIENTOS CUARENTENARIOS A NIVEL NACIONAL</th>
                    </tr>
                    <tr>
                        <th>Provincia</th>
                        <td>
                            <select id="provincia" name="provincia">
                                <option value="">Todos</option>
                                <?php
                                while ($fila = pg_fetch_assoc($provincia)) {
                                    echo '<option value="' . $fila['nombre'] . '">' . $fila['nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th>Estado</th>
                        <td>
                            <select name="estado">
                                <option value="">Todos</option>
                                <option value="notificado">Notificado</option>
                                <option value="abierto">Abierto</option>
                                <option value="cerrado">Cerrado</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th>Especie (Producto)</th>
                        <td>
                            <select name="producto">
                                <option value="">Todos</option>
                                <?php
                                while ($fila = pg_fetch_assoc($productos)) {
                                    echo '<option value="' . $fila['id_producto'] . '">' . $fila['nombre_producto'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th>Fecha inicial</th>
                        <td><input name="fechaInicio" id="fechaInicio" type="text" required readonly></td>
                    </tr>
                    <tr>
                        <th>Fecha final</th>
                        <td><input name="fechaFin" id="fechaFin" type="text" required readonly></td>
                    </tr>

                    <tr>
                        <td colspan="2">
                            <button>Generar reporte</button>
                        </td>
                    </tr>

                    <tr>
                        <td id="resultadoError" colspan="2" style="text-align: center;">                            
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </nav>
</header>
<script>
    $(document).ready(function() {

        $("#fechaInicio").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText, inst) {
                var fecha = new Date($('#fechaInicio').datepicker('getDate'));
                fecha.setDate(fecha.getDate() + 180);
                $('#fechaFin').datepicker('option', 'minDate', $("#fechaInicio").val());
                $('#fechaFin').datepicker('option', 'maxDate', fecha);
            }
        });

        $("#fechaFin").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText, inst) {
                var fecha = new Date($('#fechaInicio').datepicker('getDate'));
            }
        });

    });

    $("#reporteSeguimientoCuarentenario").submit(function() {
        if ($("#fechaInicio").val() == '') {            
            $("#resultadoError").html("Los campos de fecha son obligatorios").addClass("alerta");
            return false;
        } else{
            $("#resultadoError").html("");
        }
        if ($("#fechaFin").val() == '') {
            $("#resultadoError").html("Los campos de fecha son obligatorios").addClass("alerta");
            return false;
        } else{
            $("#resultadoError").html("");
        }
    });
    
</script>