<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Reporte combustible</h1>
</header>

<?php


	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	
	$reporte= ($_POST['valoresFiltrados']);
	
	echo'<table class="soloImpresion">
				<tr>';
	
	for ($i = 0; $i < count ($reporte); $i++) {
		
		$res = $cv->abrirCombustible($conexion, $reporte[$i]);
		$combustible = pg_fetch_assoc($res);
		
		if ($i%3 == 0 && $i!= 0){
			
			echo '</tr><tr>';
		}
		
		echo ' <td>
					<fieldset>
						<legend>N° '.$combustible['id_combustible'].'</legend>
							<div data-linea="1"><label>Placa: </label> '.$combustible['placa'].'</div>
							<div data-linea="1"><label>Kilometraje: </label>'.$combustible['kilometraje'].'</div>
							<div data-linea="1"><label>Fecha: </label>'.$combustible['fecha_liquidacion'].'</div>
							<div data-linea="2"><label>Conductor: </label>'.$combustible['apellido'].' '.$combustible['nombreconductor'].'</div>
							<div data-linea="3"><label>Gasolinera: </label>'.$combustible['nombregasolinera'].'</div>
							<div data-linea="3"><label>Tipo combustible: </label>'.$combustible['tipo_combustible'].'</div>
							<div data-linea="3"><label>Valor cancelado: </label>'.$combustible['valor_liquidacion'].'</div>
							<div data-linea="4"><label>Galones: </label>'.$combustible['cantidad_galones'].'</div>
							<div data-linea="4"><label>Fecha: </label>'.$combustible['fecha_liquidacion'].'</div>
							<div data-linea="5"><label>Observación: </label>'.( $combustible['observacion']!='' ?$combustible['observacion']:'Sin novedad.').'</div>
					        
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
