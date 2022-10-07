<?php
include_once '../../clases/Conexion.php';
include_once '../../clases/ControladorReportesCSV.php';
$conexion = new Conexion();
$cr = new ControladorReportesCSV();
$provincias = $cr->listarProvinciasVigilanciaF01($conexion);
$cantones = $cr->listarCantonesVigilanciaF01($conexion);
$plagas = $cr->listarPlagasVigilanciaF01($conexion);
$especies = $cr->listarEspeciesVigilanciaF01($conexion);
//$envios = $cr->listarEnviosVigilanciaF01($conexion);
?>
<header>
    <nav>
        <form action="aplicaciones/reportes/generarReporteGeneralTrampeoVigilancia.php" method="post">

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
                    <td>Provincia</td>
                    <td>
                        <select id="provincia" name="provincia">
                            <option value="TODOS">- TODOS -</option>
                            <?php
                            while ($provincia = pg_fetch_assoc($provincias)) {
                                echo '<option value="' . $provincia['nombre_provincia'] . '">' . $provincia['nombre_provincia'] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Cantón</td>
                    <td>
                        <select id="canton" name="canton">
                            <option value="TODOS">- TODOS -</option>
                        </select></td>
                </tr>

                <tr>
                    <td>Plaga monitoreada</td>
                    <td>
                        <select name="plaga">
                            <option value="TODOS">- TODOS -</option>
                            <?php
                            while ($plaga = pg_fetch_assoc($plagas)) {
                                echo '<option value="' . $plaga['diagnostico_visual'] . '">' . $plaga['diagnostico_visual'] . '</option>';
                            }
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td>Especie vegetal</td>
                    <td>
                        <select name="especie">
                            <option value="TODOS">- TODOS -</option>
                            <?php
                            while ($especie = pg_fetch_assoc($especies)) {
                                echo '<option value="' . $especie['especie'] . '">' . $especie['especie'] . '</option>';
                            }
                            ?>
                        </select></td>
                </tr>

                <tr>
                    <td>Envío a laboratorio</td>
                    <td>
                        <select name="envioLab">
                            <option value="Si">Si</option>
                            <option value="No">No</option>
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
            yearRange: ' - 5:+0',
            dateFormat: "yy-mm-dd",
            defaultDate: -1
        }).datepicker('setDate', fecha);
        $("#fechaFin").datepicker({
            changeMonth: true,
            changeYear: true,
            yearRange: ' - 5:+0',
            dateFormat: "yy-mm-dd"
        }).datepicker('setDate', new Date());
    });
</script>

