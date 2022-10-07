<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';

	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$res = $cc->datosCertificadosTmp($conexion,$_SESSION['usuario']);
	$contador = 0;
	$itemsFiltrados[] = array();
?>

<form id='reporteCertificadosFitosanitarios' data-rutaAplicacion='certificadosFitosanitarios' data-opcion='abrirReporteCertificados' data-destino="detalleItem">
<a id = "seleccionar" class='seleccionarTodos' href='#' >Marcar Todos</a> | <a id = "deseleccionar" class='deseleccionarTodos' href='#' >Desmarcar Todos</a>
 
<div id="paginacion" class="normal">
	</div>
	
<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Agencia</th>
			<th>Exportador</th>
			<th>País destino</th>
			<th>Destinatario</th>
			<th>Bultos</th>
			<th>Descripción</th>
		</tr>
	</thead>
	<tbody id = "prueba">
	</tbody>
 
  	<?php 
  		
  	while($fila = pg_fetch_assoc($res)){
			$itemsFiltrados[] = array('<tr
									id="'.$fila['id_tmp_fitosanitario'].'"
									class="item">
									<td!--> <input type="checkbox" id="c_'.$contador.'" name= "certificados" ><div id="dv_'.$contador.'"></div></--td>
									<td>'.++$contador.'</td>
									<td style="white-space:nowrap;"><b>'.$fila['nombre_agencia'].'</b></td>
									<td>'.$fila['identificador_exportador'].'</td>
									<td>'.$fila['nombre_pais_destino'].'</td>
									<td>'.$fila['nombre_destinatario'].'</td>
									<td>'.$fila['numero_bulto'].'</td>
									<td>'.$fila['descripcion_bulto'].'</td>
								 </tr>');
		}

	?>
</table>

	<div id="valores"></div> 
	<button type="submit" class="guardar">Enviar</button>
</form>


<script>

$("#reporteCertificadosFitosanitarios").submit(function(event){
	abrir($(this),event,false);
});

$(document).ready(function(){
	construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	$("#listadoItems").removeClass("comunes");
	$("#listadoItems").addClass("lista");
});

$('#seleccionar').click(function() {
	$("input[name= certificados]").attr("checked","checked");
	$("#_seleccionar").click();
});

$('#deseleccionar').click(function() {
	$("input[name= certificados]").removeAttr("checked");
});

</script>
