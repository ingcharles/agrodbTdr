<?php
include_once '../../clases/Conexion.php';
include_once '../../clases/ControladorCatalogos.php';
include_once '../../clases/ControladorReportesCSV.php';
$conexion = new Conexion();
$cc = new ControladorCatalogos();
$cr = new ControladorReportesCSV();
$paises = $cc->listarLocalizacion($conexion, 'PAIS');
$subtipos = $cr->listarSubtipoProductoControlF01($conexion);
$productos = $cr->listarProductosInspeccionadosControlF01($conexion);
$productosList;

while ($producto = pg_fetch_assoc($productos)) {
    $productosList[] = $producto;
}
?>
<header>
    <nav>
        <form action="aplicaciones/reportes/generarReporteCantidadProductosImportadosPorPais.php"
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
                    <td>Subtipo</td>
                    <td style="text-align: left">
                        <select id="subtipo" name="subtipo">
                            <option value="TODOS">- TODOS -</option>
                            <?php
                            while ($subtipo = pg_fetch_assoc($subtipos)) {
                                echo '<option value="' . $subtipo['subtipo'] . '">' . strtoupper($subtipo['subtipo']) . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Productos</td>
                    <td style="text-align: left">
                        <select id="producto" name="producto">
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
    var productosList = <?php echo json_encode($productosList);?>;
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
        actualizarComboProductos();
    });

    $("#subtipo").change(function(){
        actualizarComboProductos();
    });

    function actualizarComboProductos() {
        $("#producto").html("<option value='TODOS'>- TODOS -</option>");
        for(i=0; i<productosList.length; i++){
            if($("#subtipo").val()=="TODOS" || ($("#subtipo").val()!="TODOS" && $("#subtipo").val()==productosList[i]['subtipo']))
                $("#producto").append("<option value='" + productosList[i]['nombre'] + "'>" + (productosList[i]['nombre']).toUpperCase() + "</option>");
        }
    }
</script>

