<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCertificados.php';
	require_once '../../clases/ControladorAplicaciones.php';
	
	$conexion = new Conexion();
	$cc = new ControladorCertificados();
	
	$comprobante = $_POST['comprobante'];
	$fechaInicio = $_POST['fechaInicio'];
	$fechaFin = $_POST['fechaFin'];
	$provincia = $_POST['provincia'];
	$establecimiento = $_POST['establecimiento'];
	$opcionReporte = $_POST['opcionReporte'];
	$ruc = $_POST['ruc'];
	
	
	
	if( $opcionReporte == 7 || $opcionReporte == 9 ){
		$res = $cc -> filtrarNotaCreditoPorPuntoEmision($conexion, $comprobante, $fechaInicio, $fechaFin, $establecimiento, $ruc, $valor=1);  //punto de venta
	}else {
		$res = $cc -> filtrarNotaCreditoPorPuntoEmision($conexion, $comprobante, $fechaInicio, $fechaFin, $provincia, $ruc,$valor=0);       //provincia
	}
	
		
?>

<table id="tablaItems">
	<thead>
		<tr>
			<th>#</th>
			<th>Identificador</th>
			<th>Razón social</th>
			<th>Fecha</th>
			<th>Total</th>
			<th># de Orden</th>
		</tr>
	</thead>
		
<?php 
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
					<td>'.$fila['razon_social'].'</td>  
					<td>'.(($comprobante) == 'factura'? date('d/m/Y',strtotime($fila['fecha_nota_credito'])):date('d/m/Y',strtotime($fila['fecha_nota_credito']))).'</td>					<td>'.$fila['total_pagar'].'</td>
					<td> '.(($comprobante)== 'factura'? ($fila['numero_factura']):($fila['numero_nota_credito'])).'</td>
							
				</tr>';
			}
?>			
</table>

<?php 


switch ($opcionReporte){
case '7':

		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteNotaCredito.php" target="_blank" method="post">
	
				<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
				<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
				<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
				<input type="hidden" name="establecimiento" value="'.$establecimiento.'"/>
				<input type="hidden" name="opcionReporte" value="'.$opcionReporte.'"/>
				<input type="hidden" name="ruc" value="'.$ruc.'"/>
							
				<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>
		
	  	    </form>';
	
	break;
	
case '9':
	
		echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteNotaCredito.php" target="_blank" method="post">
	
		<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
		<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
		<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
		<input type="hidden" name="establecimiento" value="'.$establecimiento.'"/>
		<input type="hidden" name="opcionReporte" value="'.$opcionReporte.'"/>
		<input type="hidden" name="ruc" value="'.$ruc.'"/>
			
		<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>
	
		</form>';
	
		break;
		
case '10':
		
			echo'<form id="generarReporte" action="aplicaciones/financiero/generarReporteNotaCredito.php" target="_blank" method="post">
		
			<input type="hidden" name="comprobante" value="'.$comprobante.'"/>
			<input type="hidden" name="fechaInicio" value="'.$fechaInicio.'"/>
			<input type="hidden" name="fechaFin" value="'.$fechaFin.'"/>
			<input type="hidden" name="provincia" value="'.$provincia.'"/>
			<input type="hidden" name="opcionReporte" value="'.$opcionReporte.'"/>
			<input type="hidden" name="ruc" value="'.$ruc.'"/>
				
			<button id="btnReporte" type="submit" class="guardar">Generar reporte excel</button>
		
			</form>';
		
break;

}
	
	?>
		
<script type="text/javascript"> 

$(document).ready(function(){
	$("#listadoItems").removeClass("comunes");
	$("#listadoItems").addClass("lista");
	
});
</script>

