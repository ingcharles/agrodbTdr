<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCertificados.php';

$conexion = new Conexion();
$cc = new ControladorCertificados();

$res = $cc -> filtrarCertificadosSeleccionados($conexion, $_POST['agencia'], $_POST['fi'], $_POST['ff'], $_POST['estado']);

$contador = 0;
$itemsFiltrados[] = array();


?>

<form id='reporteCertificadosGenerados' data-rutaAplicacion='certificadosFitosanitarios' data-opcion='abrirCertificadosGenerados' data-destino="detalleItem">

<div id="paginacion" class="normal">

</div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>Seleccione</th>
			<th>#</th>
			<th>Exportador</th>
			<th>País destino</th>
			<th>Destinatario</th>
			<th>Bultos</th>
			<th>Descripción</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
	
	
<?php 

while($fila = pg_fetch_assoc($res)){

	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_fitosanitario'].'"
		class="item">
			<td> <input type="checkbox" id="c_'.$contador.'" name= "certificados" ><div id="dv_'.$contador.'"></div></td>
			<td>'.++$contador.'</td>
			<td style="white-space:nowrap;"><b>'.$fila['identificador_exportador'].'</b></td>
			<td>'.$fila['nombre_pais_destino'].'</td>
			<td>'.$fila['nombre_destinatario']	.'</td>
			<td>'.$fila['numero_bulto']	.'</td>
			<td>'.$fila['descripcion_bulto']	.'</td>
		</tr>');

}
?>

</table>
	<div id="valores"></div>
	
	<button type="submit" class="guardar">Generar informe</button>
	
</form>

<script type="text/javascript"> 

	$("#reporteCertificadosGenerados").submit(function(event){
			abrir($(this),event,false);
	});
		
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});

</script>

