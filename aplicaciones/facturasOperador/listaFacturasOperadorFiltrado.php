<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorAplicaciones.php';
	require_once '../../clases/GoogleAnalitica.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	$ca = new ControladorAplicaciones();
	
	$identificador=$_SESSION['usuario'];
	
	$opcion = htmlspecialchars ($_POST['opcion'],ENT_NOQUOTES,'UTF-8');
	$numeroFactura = htmlspecialchars ($_POST['numeroFactura'],ENT_NOQUOTES,'UTF-8');
	$tipoSolicitud = htmlspecialchars ($_POST['tipoSolicitud'],ENT_NOQUOTES,'UTF-8');
	$numeroSolicitud = htmlspecialchars ($_POST['numeroSolicitud'],ENT_NOQUOTES,'UTF-8');
	$numeroOrdenVue = htmlspecialchars ($_POST['numeroOrdenVue'],ENT_NOQUOTES,'UTF-8');
	$numeroOrdenGuia = htmlspecialchars ($_POST['numeroOrdenGuia'],ENT_NOQUOTES,'UTF-8');
	
	$numeroFactura = ($numeroFactura == '' ? '': str_pad($_POST['numeroFactura'], 9, "0", STR_PAD_LEFT));	
	
	
	echo'<header> <nav>';
	$res = $ca->obtenerAccionesPermitidas($conexion, $opcion, $identificador);
	while($fila = pg_fetch_assoc($res)){
	
		echo '<a href="#"
							id="' . $fila['estilo'] . '"
							data-destino="detalleItem"
							data-opcion="' . $fila['pagina'] . '"
							data-rutaAplicacion="' . $fila['ruta'] . '"
							>'.(($fila['estilo']=='_seleccionar')?'<div id="cantidadItemsSeleccionados">0</div>':''). $fila['descripcion'] . '</a>';
	
	
	}
	echo'</nav></header>';
	
	$qFactura = $cc->obtenerFacturaPorOperador($conexion, $identificador, $numeroFactura, $_POST['fechaInicio'], $_POST['fechaFin'], $tipoSolicitud, $numeroSolicitud, $numeroOrdenVue, $numeroOrdenGuia);
	
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
			<th>Número factura</th>
			<th>Fecha</th>
			<th>Total</th>
			<th>Número de orden</th>
			<th>Estado</th>
		</tr>
	</thead>
	
<?php 
		$contador = 0;
		while($fila = pg_fetch_assoc($qFactura)){

			echo '<tr 
						id="'.$fila['id_pago'].'"
						class="item"
						data-rutaAplicacion="facturasOperador"
						data-opcion="abrirFacturasOperador" 
						ondragstart="drag(event)" 
						draggable="true" 
						data-destino="detalleItem">
					<td>'.++$contador.'</td>
					<td><b>'.$fila['numero_establecimiento'].'-'.$fila['punto_emision'].'-'.$fila['numero_factura'].'</b></td>
					<td>'.$fila['fecha_facturacion'].'</td>
					<td>'.$fila['total_pagar'].'</td>
					<td>'.$fila['numero_solicitud'].'</td>
					<td>'.($fila['estado_sri']=='AUTORIZADO'?'Autorizado':($fila['estado_sri']=='NO AUTORIZADO'?'No autorizado':($fila['estado_sri']=='DEVUELTA'?'Devuelta':($fila['estado_sri']=='POR ATENDER'?'Por enviar SRI':($fila['estado_sri']=='RECIBIDA'?'Enviado SRI':($fila['estado_sri']=='FINALIZADO'?'Finalizado, valor cero.':($fila['estado']==3?'Por liquidar':($fila['estado']==9?'Eliminado':'Pendiente')))))))).'</td>
				</tr>';			
			}
			
?>			
</table>

</body>

<script type="text/javascript"> 

$("#listadoItems").removeClass("comunes");

//$("#_agrupar").click(function(){
	//alert("dd");
	//alert($('#_agrupar').attr('data-elementos'));
	//if($('#_agrupar').attr('data-elementos')==true){
		$('#_agrupar').attr('data-rutaaplicacion','facturasOperador');
		$('#_agrupar').attr('data-opcion','abrirFacturasOperador');
	//}	
//});



</script>
</html>
