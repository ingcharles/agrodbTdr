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
	<h1>Reporte gasolinera</h1>
</header>

<?php


	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	$ce = new ControladorEmpleados();
	
	$reporte= ($_POST['valoresFiltrados']);
	
	echo'<table class="soloImpresion">
				<tr>';
	
	for ($i = 0; $i < count ($reporte); $i++) {
		
		$res = $cv->abrirGasolinera($conexion, $reporte[$i]);
		$gasolinera = pg_fetch_assoc($res);
		
		if ($i%3 == 0 && $i!= 0){
				
			echo '</tr><tr>';
		}
	
	
	echo ' <td>
					<fieldset>
						<legend>N° '.$gasolinera['id_gasolinera'].'</legend>
							<div data-linea="1"><label>Nombre gasolinera: </label> '.$gasolinera['nombre'].'</div>
							<div data-linea="2"><label>Dirección: </label>'.$gasolinera['direccion'].'</div>							
							<div data-linea="3"><label>Persona de contacto: </label>'.$gasolinera['contacto'].'</div>
							<div data-linea="3"><label>Teléfono: </label>'.$gasolinera['telefono'].'</div>
							<div data-linea="4"><label>Saldo mensual: </label>'.$gasolinera['cupo'].'</div>
					        <div data-linea="4"><label>Saldo disponible: </label>'.$gasolinera['saldo_disponible'].'</div>
							<div data-linea="5"><label>Precio extra: </label>'.( $gasolinera['extra']!=0 ?$gasolinera['extra']:'Combustible no disponible').'</div>
							<div data-linea="5"><label>Precio super: </label>'.($gasolinera['super']!=0 ?$gasolinera['super']:'Combustible no disponible').'</div>
					        <div data-linea="6"><label>Precio diesel: </label>'.($gasolinera['diesel']!=0 ?$gasolinera['diesel']:'Combustible no disponible').'</div>
					        <div data-linea="6"><label>Precio ecopaís: </label>'.($gasolinera['ecopais']!=0 ?$gasolinera['ecopais']:'Combustible no disponible').'</div>
							<div data-linea="7"><label>Observación: </label>'.($gasolinera['observacion']!=''?$gasolinera['observacion']:'Sin novedad').'</div>
			
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
