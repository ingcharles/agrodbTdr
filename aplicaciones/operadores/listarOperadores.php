<?php
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$cro = new ControladorRegistroOperador();

$tipoDeBusqueda = htmlspecialchars($_POST['tipo'], ENT_NOQUOTES, 'UTF-8');
$textoDeBusqueda = htmlspecialchars($_POST['textoDeBusqueda'], ENT_NOQUOTES, 'UTF-8');
$provincia = htmlspecialchars($_POST['provincia'], ENT_NOQUOTES, 'UTF-8');
$area = htmlspecialchars($_POST['area'], ENT_NOQUOTES, 'UTF-8');
$idTipoOperacion = htmlspecialchars($_POST['tipoOperacion'], ENT_NOQUOTES, 'UTF-8');

$idTipoOperacion = ($idTipoOperacion == '' ? 'Todas' : $idTipoOperacion);

$operadores = $cro->filtrarOperadoresPorTexto($conexion, $tipoDeBusqueda, $textoDeBusqueda, $provincia, $area, $idTipoOperacion);

$contador = 0;
$itemsFiltrados[] = array();

while ($operador = pg_fetch_assoc($operadores)) {
    $tmp = explode('-', $fila['id_documento']);
    $itemsFiltrados[] = array(
        '<tr
		id="' . $operador['identificador'] . '"
		class="item"
		data-rutaAplicacion="operadores"
		data-opcion="abrirOperador"
        data-idOpcion="' . $area . '.' . $provincia . '.' . $idTipoOperacion . '"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem"
		>
		<td>' . ++ $contador . '</td>
		<td style="white-space:nowrap;"><b>' . $operador['identificador'] . '</b></td>
		<td>' . $operador['razon_social'] . '</td>
		<td>' . $operador['apellido_representante'] . ', ' . $operador['nombre_representante'] . '</td>
		<td>' . $operador['apellido_tecnico'] . ', ' . $operador['nombre_tecnico'] . '</td>
				</tr>'
    );
}

?>
<div id="paginacion" class="normal"></div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>RUC</th>
			<th>Razón social</th>
			<th>Representante</th>
			<th>Técnico</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>

<?php
if (pg_num_rows($operadores) == 0) {
    echo 'No existen resultados para los parametros ingresados.';
}
?>

<script type="text/javascript">
    var itemInicial = 0;

    $(document).ready(function(){
        $("#listadoItems").removeClass("comunes");
        $("#listadoItems").addClass("lista");
        $("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui un documento para revisarlo.</div>');
        construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);

    });



</script>