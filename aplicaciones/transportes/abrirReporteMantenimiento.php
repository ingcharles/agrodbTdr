<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
require_once '../../clases/ControladorEmpleados.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Reporte mantenimientos</h1>
</header>

<?php


	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	$ce = new ControladorEmpleados();
	
	$reporte= ($_POST['valoresFiltrados']);
	
	echo'<table class="soloImpresion">
				<tr>';
	
	for ($i = 0; $i < count ($reporte); $i++) {
		
		$res = $cv->abrirMantenimiento($conexion, $reporte[$i]);
		$mantenimiento = pg_fetch_assoc($res);
		
		$detalleMantenimiento = $cv->abrirDetalleMantenimiento($conexion, $reporte[$i]);
		
		
		$per = $ce->obtenerDatosPersonales($conexion, $mantenimiento['conductor']);
		$persona = pg_fetch_assoc($per);
		$tal = $cv->abrirTaller($conexion, $mantenimiento['taller']);
		$taller = pg_fetch_assoc($tal);
		
		if ($i%3 == 0 && $i!= 0){
				
			echo '</tr><tr>';
		}
	
	
	echo ' <td>
					<fieldset>
						<legend>N° '.$mantenimiento['id_mantenimiento'].'</legend>
							<div data-linea="1"><label>Motivo: </label> '.$mantenimiento['motivo'].'</div>
							<div data-linea="2"><label>Placa vehículo: </label>'.$mantenimiento['placa'].'</div>							
							<div data-linea="2"><label>Kilometraje: </label>'.$mantenimiento['kilometraje'].'</div>
							<div data-linea="3"><label>Conductor: </label>'.$persona['apellido'] .' '. $persona['nombre'].'</div>
							<div data-linea="3"><label>Taller: </label>'.$taller['nombre'].'</div>
					        <div data-linea="4"><label>Número factura: </label>'.( $mantenimiento['numero_factura']!='' ?$mantenimiento['numero_factura']:'Número factura no disponible').'</div>
							<div data-linea="4"><label>Valor liquidado: </label>'.( $mantenimiento['valor_liquidacion']!='' ?'$ '.$mantenimiento['valor_liquidacion']:'Valor no disponible').'</div>
							
							<div data-linea="5"><label>Detalle mantenimiento: </label>';
							if(pg_num_rows($detalleMantenimiento)!=0){
								while($fila = pg_fetch_assoc($detalleMantenimiento)){
									echo $fila['detalle'].' $ '. $fila['valor'].', ';
								}
							}else{
								echo 'No existe detalle de mantenimiento.';
							}
									
							echo'</div>
								<div data-linea="6"><label>PDF factura: </label><a href="'.$mantenimiento['imagen_factura'].'">'.$mantenimiento['placa'].'._factura</a></div>
			
		  			</fieldset>			
		   </td>';
	}
	
	echo '</tr></table>
				<table class="firmas">
					<caption>Firmas</caption>
					<tr>
						<td>
							 Solicitante
						</td>
						<td>
							Jefe de transportes
						</td>
						<td>
							Responsable
						</td>		
					</tr>
				</table>';
	

	
	?>
</body>
<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();
	});
</script>
</html>
