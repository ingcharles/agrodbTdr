<?php
include_once '../../clases/Conexion.php';
include_once '../../clases/ControladorCatalogos.php';
include_once '../../clases/ControladorReportesCSV.php';
$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cr = new ControladorReportesCSV();
$paises = $cc->listarLocalizacion($conexion, 'PAIS');
$puntosControl = $cr->listarPuntosControlControlF03($conexion);
?>
<header>
    <nav>
        <form action="aplicaciones/reportes/generarReporteIncumplimientoEmbalajeMaderaPorPais.php"
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
                    <td>Puntos de control</td>
                    <td>
                        <select name="puntoControl">
                            <option value="TODOS">TODOS</option>
                            <?php
                            while ($puntoControl = pg_fetch_assoc($puntosControl)) {
                                echo '<option value="' . strtoupper($puntoControl['punto_control']) . '">' . strtoupper($puntoControl['punto_control']) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Incumplimientos</td>
                    <td style="text-align: left">
                        <input type="checkbox" name="incumplimientos[]" checked value="marca_autorizada">Sin marca autorizada de país origen<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="marca_legible">Marca ilegible<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="ausencia_dano_insectos">Daño de insectos<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="ausencia_insectos_vivos">Insectos vivos<br>
                        <input type="checkbox" name="incumplimientos[]" checked value="ausencia_corteza">Cortezas<br>
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

