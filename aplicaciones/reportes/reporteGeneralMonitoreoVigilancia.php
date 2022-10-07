<?php
include_once '../../clases/Conexion.php';
include_once '../../clases/ControladorReportesCSV.php';
$conexion = new Conexion();
$cr = new ControladorReportesCSV();
$actividades = $cr->listarActividadesVigilanciaF02($conexion);
$especiesVegetales = $cr->listarEspeciesVegetalesVigilanciaF02($conexion);
$diagnosticosVisuales = $cr->listarDiagnosticosVisualesVigilanciaF02($conexion);
//$incidencias = $cr->listarIncidenciasVigilanciaF02($conexion);
//$severidades = $cr->listarSeveridadesVigilanciaF02($conexion);
?>

<header>
    <nav>
        <form action="aplicaciones/reportes/generarReporteGeneralMonitoreoVigilancia.php" method="post">

            <table class="filtro">
                <tbody>
                <tr>
                    <td>Fecha inicial</td>
                    <td><input name="fechaInicio" id="fechaInicio" type="text" required readonly></td>
                </tr>
                <tr>
                    <td>Fecha final</td>
                    <td><input name="fechaFin" id="fechaFin" type="text" required readonly></td>
                </tr>
                <tr>
                    <td>Actividad</td>
                    <td>
                        <select name="actividad">
                            <option value="TODOS">- TODOS -</option>
                            <?php
                            while ($actividad = pg_fetch_assoc($actividades)) {
                                echo '<option value="' . strtoupper($actividad['actividad']) . '">' . strtoupper($actividad['actividad']) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Especie vegetal</td>
                    <td>
                        <select name="especie">
                            <option value="TODOS">- TODOS -</option>
                            <?php
                            while ($especieVegetal = pg_fetch_assoc($especiesVegetales)) {
                                echo '<option value="' . strtoupper($especieVegetal['especie_vegetal']) . '">' . strtoupper($especieVegetal['especie_vegetal']) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Diagnóstico visual</td>
                    <td>
                        <select name="diagnostico">
                            <option value="TODOS">- TODOS -</option>
                            <?php
                            while ($diagnosticoVisual = pg_fetch_assoc($diagnosticosVisuales)) {
                                echo '<option value="' . strtoupper($diagnosticoVisual['diagnostico_visual']) . '">' . strtoupper($diagnosticoVisual['diagnostico_visual']) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>% incidencia</td>
                    <td>
                        <select name="incidencia">
                            <option value="TODOS">- TODOS -</option>
                            <option value="rangoIncidencia1">< 15%</option>
                            <option value="rangoIncidencia2">15% - 25%</option>
                            <option value="rangoIncidencia3">&ge; 25%</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>% severidad</td>
                    <td>
                        <select name="severidad">
                            <option value="TODOS">- TODOS -</option>
                            <option value="rangoSeveridad1">< 15%</option>
                            <option value="rangoSeveridad2">15% - 25%</option>
                            <option value="rangoSeveridad3">&ge; 25%</option>
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
<script>
    $(document).ready(function () {
        var fecha = new Date();
        fecha.setMonth(fecha.getMonth() - 3);
        $("#fechaInicio").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '-5:+0',
            dateFormat: "yy-mm-dd",
            defaultDate: -1
        }).datepicker('setDate', fecha);
        $("#fechaFin").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '-5:+0',
            dateFormat: "yy-mm-dd"
        }).datepicker('setDate', new Date());
    });
</script>

