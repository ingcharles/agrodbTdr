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
	<h1>Reporte Siniestros</h1>
</header>

<?php

	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	$ce = new ControladorEmpleados();
	
	$reporte= ($_POST['valoresFiltrados']);
	
	echo'<table class="soloImpresion">
				<tr>';
	
	for ($i = 0; $i < count ($reporte); $i++) {
		
		$res = $cv->abrirSiniestro($conexion, $reporte[$i]);
		$siniestro = pg_fetch_assoc($res);
		$detalleSiniestro = $cv->abrirDetalleSiniestro($conexion, $reporte[$i]);

		
		$per = $ce->obtenerDatosPersonales($conexion, $siniestro['conductor']);
		$persona = pg_fetch_assoc($per);

	
	echo ' <td>
					<fieldset>
						<legend>N° '.$siniestro['id_siniestro'].'</legend>
							<div data-linea="1"><label>Tipo: </label> '.$siniestro['tipo_siniestro'].'</div>
							<div data-linea="2"><label>Placa: </label>'.$siniestro['placa'].'</div>							
							<div data-linea="2"><label>Conductor: </label>'.$persona['apellido'] .' '. $persona['nombre'].'</div>
					        <div data-linea="3"><label>Tipo siniestro: </label>'.$siniestro['tipo_siniestro'].'</div>
							<div data-linea="3"><label>Magnitud del daño siniestro: </label>'.$siniestro['magnitud_danio_siniestro'].'</div>
							<div data-linea="4"><label>Lugar siniestro: </label>'.$siniestro['lugar_siniestro'].'</div>
							<div data-linea="4"><label>Fecha: </label>'.$siniestro['fecha_registro'].'</div>
							<div data-linea="5"><label>PDF factura: </label><a href="'.$siniestro['imagen_factura'].'">'.$siniestro['id_siniestro'].'._factura</a></div>
			
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
