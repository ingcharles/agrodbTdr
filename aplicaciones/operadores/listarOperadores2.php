<?php
    session_start();

    require_once '../../clases/Conexion.php';
    require_once '../../clases/ControladorRegistroOperador.php';

    $conexion = new Conexion ();
    $cro = new ControladorRegistroOperador();

    $provincia = htmlspecialchars($_POST ['provincia'], ENT_NOQUOTES, 'UTF-8');
    $area = htmlspecialchars($_POST ['area'], ENT_NOQUOTES, 'UTF-8');
    $operacion = htmlspecialchars($_POST ['operacion'], ENT_NOQUOTES, 'UTF-8');

    $operadores = $cro->filtrarOperadoresPorOperacion($conexion, $provincia, $area, $operacion);

    $contador = 0;
    $itemsFiltrados[] = array();

    while ($operador = pg_fetch_assoc($operadores)) {
        $tmp              = explode('-', $fila['id_documento']);
        $itemsFiltrados[] = array('<tr
		id="' . $operador['identificador'] . '"
		class="item"
		data-rutaAplicacion="operadores"
		data-opcion="abrirOperacion"
        data-idOpcion="'.$area.'.'.$provincia.'"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem">
		<td>' . ++$contador . '</td>
		<td style="white-space:nowrap;"><b>' . $operador['identificador'] . '</b></td>
		<td>' . $operador['razon_social'] .  '</td>
		<td> ' . $operador['operacion'] . ' [' . $operador['id_area'] .  ']</td>
		<td>' . $operador['provincia'] . '</td>
		<td><span class="__operacion_' . '">&nbsp</span></td>
		</tr>');

    }
?>
<div id="paginacion" class="normal">

</div>

<table id="tablaItems">
    <thead>
    <tr>
        <th>#</th>
        <th>RUC</th>
        <th>Razón social</th>
        <th>Operación (IDS)</th>
        <th>Provincia</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>

<script type="text/javascript">
    var itemInicial = 0;

    $(document).ready(function(){
        $("#listadoItems").removeClass("comunes");
        $("#listadoItems").addClass("lista");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un documento para revisarlo.</div>');
        construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
     });



</script>