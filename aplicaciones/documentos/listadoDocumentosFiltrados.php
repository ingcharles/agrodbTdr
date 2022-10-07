<?php 
//session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDocumentos.php';

$conexion = new Conexion();
$cd = new ControladorDocumentos();

$conexion->verificarSesion();

function reemplazarCaracteres($cadena){
	$cadena = str_replace('á', 'a', $cadena);
	$cadena = str_replace('é', 'e', $cadena);
	$cadena = str_replace('í', 'i', $cadena);
	$cadena = str_replace('ó', 'o', $cadena);
	$cadena = str_replace('ú', 'u', $cadena);
	$cadena = str_replace('ñ', 'n', $cadena);	
	$cadena = str_replace('à', 'a', $cadena);
	$cadena = str_replace('è', 'e', $cadena);
	$cadena = str_replace('ì', 'i', $cadena);
	$cadena = str_replace('ò', 'o', $cadena);
	$cadena = str_replace('ù', 'u', $cadena);
	$cadena = str_replace('-', ' ', $cadena);
	$cadena = str_replace('.', ' ', $cadena);
	$cadena = str_replace(';', ' ', $cadena);
	$cadena = str_replace('"', ' ', $cadena);
	$cadena = str_replace('“', ' ', $cadena);
	$cadena = str_replace('Á', 'A', $cadena);
	$cadena = str_replace('É', 'E', $cadena);
	$cadena = str_replace('Í', 'I', $cadena);
	$cadena = str_replace('Ó', 'O', $cadena);
	$cadena = str_replace('Ú', 'U', $cadena);
	$cadena = str_replace('Ñ', 'N', $cadena);

	return $cadena;
}

$res = $cd->filtrarDocumentos($conexion, $_POST['identificador'],$_POST['archivo'],$_POST['asunto'],$_POST['fi'],$_POST['ff'],$_POST['estado']);
$contador = 0;
$itemsFiltrados[] = array();

while($fila = pg_fetch_assoc($res)){
	$tmp = explode('-', $fila['id_documento']);
	$asunto = rtrim(reemplazarCaracteres($fila['asunto']));
	$itemsFiltrados[] = array('<tr
		id="'.$fila['id_documento'].'"
		class="item"
		data-rutaAplicacion="documentos"
		data-opcion="abrirDocumentoCerrado"
		ondragstart="drag(event)"
		draggable="true"
		data-destino="detalleItem">
		<td>'.++$contador.'</td>
		<td style="white-space:nowrap;"><b>'.$tmp[0].'-'.$tmp[1].'</b></td>
				<td>'.date('j/n/Y',strtotime($fila['fecha_creacion'])).'</td>
		<td>'.(strlen($asunto)>51?(substr($asunto,0,51).'...'):(strlen($asunto)>0?$asunto:'Sin asunto')).'</td>
					<td><span class="n'.($fila['estado']==3?'Aprobado':(($fila['estado']==2 ||$fila['estado']==1 ) ?'Pendiente':'Rechazado')).'"></span></td>
				</tr>');

}
?>
<div id="paginacion" class="normal">

</div>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Archivo</th>
			<th>Creación</th>
			<th>Asunto</th>
			<th>Estado</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script type="text/javascript"> 
	var itemInicial = 0;
	
	$(document).ready(function(){
		construirPaginacion($("#paginacion"),<?php echo json_encode($itemsFiltrados);?>);
	});
	
	
	
</script>

