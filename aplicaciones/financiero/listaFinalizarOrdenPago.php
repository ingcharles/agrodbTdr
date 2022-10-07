<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorAplicaciones.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	
	if($_POST['tipoEstado']!= 'estadoComprobante'){
		$res = $cc -> filtrarOrdenServicioSRI($conexion, $_POST['operador'],$_POST['factura'], $_POST['fechaInicio'], $_POST['fechaFin'], $_POST['estadoSolicitud'], $_SESSION['nombreLocalizacion']);
		//$res = $cc -> filtrarOrdenServicioSRI($conexion, $_POST['operador'],$_POST['factura'], $_POST['fechaInicio'], $_POST['fechaFin'], $_POST['estadoSolicitud'], $_POST['provincia']);
	}else{
		$res = $cc -> filtrarOrdenServicio($conexion, $_POST['operador'],$_POST['factura'], $_POST['fechaInicio'], $_POST['fechaFin'], $_POST['estadoSolicitud'], $_SESSION['nombreLocalizacion'], $_POST['solicitudes']);
		//$res = $cc -> filtrarOrdenServicio($conexion, $_POST['operador'],$_POST['factura'], $_POST['fechaInicio'], $_POST['fechaFin'], $_POST['estadoSolicitud'], $_POST['provincia']);
	}
	
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

<table>
	<thead>
		<tr>
			<th>#</th>
			<th>Identificador</th>
			<th>Razón social</th>
			<th>Fecha</th>
			<th>Total</th>
			<th>Número de orden</th>
			<th>Estado</th>
		</tr>
	</thead>
	
<?php 
		$contador = 0;
		while($fila = pg_fetch_assoc($res)){

			echo '<tr 
						id="'.$fila['id_pago'].'"
						class="item"
						data-rutaAplicacion="financiero"
						data-opcion="abrirFinalizarOrden" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td style="white-space:nowrap;"><b>'.$fila['identificador_operador'].'</b></td>
					<td>'.$fila['razon_social'].'</td>
					<td>'.$fila['fecha_orden_pago'].'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td>'.$fila['numero_solicitud'].'</td>
					<td style="white-space:nowrap;">'.($fila['estado_sri']=='AUTORIZADO'?'Autorizado':($fila['estado_sri']=='NO AUTORIZADO'?'No autorizado':($fila['estado_sri']=='DEVUELTA'?'Devuelta':($fila['estado_sri']=='POR ATENDER'?'Por enviar SRI':($fila['estado_sri']=='RECIBIDA'?'Enviado SRI':($fila['estado_sri']=='FINALIZADO'?'Finalizado, valor cero.':($fila['estado']==3?'Por liquidar':($fila['estado']==9?'Eliminado':'Pendiente')))))))).'</td>
				</tr>';
			}
?>			
</table>

</body>

<script type="text/javascript"> 

$(document).ready(function(){
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
	});

</script>
</html>
