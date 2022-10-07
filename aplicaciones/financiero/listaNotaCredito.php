<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/ControladorCertificados.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<header>
		<h1>Nota Crédito</h1>
		<nav>
		<?php 

			$conexion = new Conexion();
			$ca = new ControladorAplicaciones();
			$res = $ca->obtenerAccionesPermitidas($conexion, $_POST["opcion"], $_SESSION['usuario']);
			//data-rutaAplicacion="' . $fila['ruta'] .'"
			while($fila = pg_fetch_assoc($res)){
				echo '<a href="#"
						id="' . $fila['estilo'] . '"
						data-destino="detalleItem"
						data-opcion="' . $fila['pagina'] . '"
						data-rutaAplicacion="' . $fila['ruta'] . '"
						>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
				
			}
		?>
		</nav>
</header>

<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Identificador</th>
			<th>Fecha</th>
			<th>Número de orden</th>
			<th>Estado SRI</th>
		</tr>
	</thead>

	<?php 
		$cc = new ControladorCertificados();
		$res = $cc->listarNotaCredito($conexion,'ABIERTOS', $_SESSION['nombreLocalizacion']);
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){
			echo '<tr 
						id="'.$fila['id_nota_credito'].'"
						class="item"
						data-rutaAplicacion="financiero"
						data-opcion="abrirNotaCredito"
						ondragstart="drag(event)"  
						draggable="true" 
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.date('j/n/Y (G:i)',strtotime($fila['fecha_nota_credito'])).'</td>
					<td>'.$fila['numero_nota_credito'].'</td>
					<td style="white-space:nowrap;">'.($fila['estado_sri']=='AUTORIZADO'?'Autorizado':($fila['estado_sri']=='NO AUTORIZADO'?'No autorizado':($fila['estado_sri']=='DEVUELTA'?'Devuelta':($fila['estado_sri']=='POR ATENDER'?'Por enviar SRI':($fila['estado_sri']=='RECIBIDA'?'Enviado SRI':($fila['estado_sri']=='ANULADO'?'Anulado':($fila['estado']==3?'Por liquidar':'Pendiente'))))))).'</td>
				</tr>';
		
		}
	?>
</table>
	
</body>
<script>
$(document).ready(function(){
	$("#listadoItems").removeClass("comunes");
	$("#listadoItems").addClass("lista");
	

	if(!$("#detalleItem #visor").length){
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aqui una nota de crédito para revisarla.</div>');
	}
});


$("#_eliminar").click(function(){
	if($("#cantidadItemsSeleccionados").text()>1){
			alert('Por favor seleccione una nota de crédito a la vez');
			return false;
		}
	});
</script>
</html>