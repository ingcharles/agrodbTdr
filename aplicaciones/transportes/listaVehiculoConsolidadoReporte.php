<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();


$res = $cv -> filtrarConsolidado($conexion, $_SESSION['nombreLocalizacion'], $_POST['placa'], $_POST['fi'], $_POST['ff']);

$contador = 0;

?>

<form id='reporteHistorialConsolidado' data-rutaAplicacion='transportes' data-opcion='abrirReporteMantenimiento' data-destino="detalleItem">


<table id="tablaConsolidado">
	<thead>
		<tr>
			<th>Placa</th>
			<th># motor</th>
			<th># chasis</th>
			<th>Combustible</th>
			<th>Mantenimiento</th>
			<th>Total</th>
		</tr>
	</thead>
	<tbody>
	</tbody>

<?php 

while($fila = pg_fetch_assoc($res)){

	$valor =  $fila['cantidadcombustible'] + $fila['cantidadmantenimiento'];

			echo '<tr
				id="'.$fila['placa'].'"
				class="item">
				<td style="white-space:nowrap;"><b>'.$fila['placa'].'</b></td>
				<td>'.$fila['motor'].'</td>
				<td>'.$fila['chasis'].'</td>
				<td class="valorCombustible">'.number_format($fila['cantidadcombustible'],2,'.', '').'</td>
				<td class="valorMantenimiento">'.number_format($fila['cantidadmantenimiento'],2,'.', '').'</td>
				<td class="valorTotal">'.number_format($valor,2,'.', '').'</td>
				</tr>';
}

echo '<tr id="total" class="item">
				<td colspan= "3"><b>TOTAL</b></td>
				<td class="combustible"></td>
				<td class="mantenimiento"></td>
				<td class="total"></td>
			</tr>';

?>

</table>


</form>

<script type="text/javascript"> 

	$(document).ready(function(){


		var totalCombustible = 0;
		var totalMantenimiento = 0;
		var valorTotal = 0;


			$('#tablaConsolidado .valorCombustible').each(function(){  
				totalCombustible += Number($(this).html());
				totalCombustible = Math.round((totalCombustible)*100)/100;
		   });

			$('#tablaConsolidado .valorMantenimiento').each(function(){    
				totalMantenimiento += Number($(this).html());
				totalMantenimiento = Math.round((totalMantenimiento)*100)/100;
		   });

			$('#tablaConsolidado .valorTotal').each(function(){    
				valorTotal += Number($(this).html());
				valorTotal = Math.round((valorTotal)*100)/100;
		   });

			$('.combustible').html('<b>'+totalCombustible+'</b>');
			$('.mantenimiento').html('<b>'+totalMantenimiento+'</b>');
			$('.total').html('<b>'+valorTotal+'</b>');

			   
	});
	
	$("#reporteHistorialMantenimiento").submit(function(event){
		abrir($(this),event,false);
	});
	


</script>

