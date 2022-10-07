<?php
include_once '../../clases/Conexion.php';
include_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cc = new ControladorCatalogos();
$tipoOperacion = $cc->obtenerTiposOperacionPorIdAreaTematica($conexion, "SV");
$tipoProducto = $cc->listarTipoProductosXareas($conexion, "='SV'");

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
        <form id="reporteGeneralOperadores" action="aplicaciones/reportes/generarReporteOperadorGeneral.php" data-rutaAplicacion='reportes' method="post">
            <input type="hidden" id="opcion" name="opcion" />
            <input type="hidden" id="tituloReporte" name="tituloReporte" value="Reporte Operadores por Producto" />
            <input type="hidden" id="archivoSalida" name="archivoSalida" value="REPORTE_OPERADORES_POR_PRODUCTO" />
            <table class="filtro">
                <tbody>
                    <tr>
                        <th style="text-align: center;">REPORTE POR PRODUCTO</th>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td>
                            <select name="estado" required>
                                <option value="">Seleccione...</option>
                                <option value="asignadoInspeccion">Asignada Inspección</option>
                                <option value="cargarIA">Cargar IA</option>
                                <option value="cargarProducto">Cargar Producto</option>
                                <option value="inspeccion">Inspección</option>
                                <option value="noHabilitado">No Habilitado</option>
                                <option value="registrado">Registrado</option>
                                <option value="registradoObservacion">Registrado con observaciones</option>
                                <option value="representanteTecnico">Representante Técnico</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th>Tipo operación</th>
                        <td>
                            <select name="tipoOperacion" required>
                                <option value="">Seleccione...</option>
                                <?php
                                while ($fila = pg_fetch_assoc($tipoOperacion)) {
                                    echo '<option value="' . $fila['id_tipo_operacion'] . '">' . $fila['nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th>Tipo producto</th>
                        <td>
                            <select name="tipoProducto" id="tipoProducto" required>
                                <option value="">Seleccione...</option>
                                <?php
                                while ($fila = pg_fetch_assoc($tipoProducto)) {
                                    echo '<option value="' . $fila['id_tipo_producto'] . '">' . $fila['nombre'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th>Subtipo Producto</th>
                        <td>
                            <div id="resultadosubtipoProducto">
                                <select id="subtipoProducto" name="subtipoProducto" required>
                                    <option value="">Seleccione....</option>
                                </select>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th>Producto</th>
                        <td>
                            <div id="resultadoProducto" required>
                                <select name="producto">
                                    <option value="">Seleccione....</option>
                                </select>
                            </div>
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
            dateFormat: "yy-mm-dd",
            maxDate: "0"
        }).datepicker('setDate', new Date());        

        $("#fechaFin").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            maxDate: "0",
        }).datepicker('setDate', new Date());      
    });

    $("#reporteGeneralOperadores").submit(function(){
        if($("#fechaInicio").val()==''){
            return false;
        }
        if($("#fechaFin").val()==''){
            return false;
        }
    });

    $("#tipoProducto").change(function(event) {
        $('#resultadoProducto').html('<select name="producto"><option value="">Seleccione....</option></select>');
        $('#reporteGeneralOperadores').attr('data-opcion', 'accionesReporteGeneralOperadores');
        $('#reporteGeneralOperadores').attr('data-destino', 'resultadosubtipoProducto');
        $('#opcion').val('tipoProducto');
        abrir($("#reporteGeneralOperadores"), event, false);
    });
</script>