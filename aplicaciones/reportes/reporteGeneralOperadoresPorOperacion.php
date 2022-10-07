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
            <input type="hidden" id="tituloReporte" name="tituloReporte" value="Reporte Operadores por Operación" />
            <input type="hidden" id="archivoSalida" name="archivoSalida" value="REPORTE_OPERADORES_POR_OPERACION" />
            <table class="filtro">
                <tbody>
                    <tr>
                        <th style="text-align: center;">REPORTE POR OPERACIÓN</th>
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
                        <td colspan="2">
                            <button>Generar reporte</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </nav>
</header>