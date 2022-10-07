<?php
include_once '../../clases/Conexion.php';
include_once '../../clases/ControladorCatalogos.php';
$conexion = new Conexion();
$cc = new ControladorCatalogos();
$paises = $cc->listarLocalizacion($conexion, 'PAIS');
?>
<header>
    <nav>
        <form action="aplicaciones/reportes/generarReporteInspeccionProductosImportadosPorIncumplimiento.php"
              method="post">

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
                    <td>País</td>
                    <td>
                        <select name="pais">
                            <option value="TODOS">- TODOS -</option>
                            <?php
                            while ($pais = pg_fetch_assoc($paises)) {
                                echo '<option value="' . strtoupper($pais['nombre']) . '">' . strtoupper($pais['nombre']) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Incumplimientos</td>
                    <td style="text-align: left">
                        <input type="checkbox" name="incumplimientos[]" checked value="pregunta03">Sin marca autorizada de país de origen<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="pregunta04">Ilegibilidad de la marca<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="pregunta05">Daño de insectos<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="pregunta06">Insectos vivos<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="pregunta07">Cortezas<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="pregunta08">Empaques nuevos de primer uso<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="ausencia_suelo">Suelo<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="ausencia_contaminantes">Contaminantes vegetales<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="ausencia_sintomas">Síntomas de plagas<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="ausencia_plagas">Plagas
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
            dateFormat: "yy-mm-dd"
        }).datepicker('setDate', fecha);
        $("#fechaFin").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: '-5:+0',
            dateFormat: "yy-mm-dd"
        }).datepicker('setDate', new Date());
    });
</script>

