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
	<h1>Reporte talleres</h1>
</header>

<?php


	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	
	$reporte= ($_POST['valoresFiltrados']);

	echo'<table class="soloImpresion">
				<tr>';
	
	for ($i = 0; $i < count ($reporte); $i++) {
		
		$res = $cv->abrirTaller($conexion, $reporte[$i]);
		$talleres = pg_fetch_assoc($res);
	
	
	echo ' <td>
					<fieldset>
						<legend>N° '.$talleres['id_taller'].'</legend>
							<div data-linea="1"><label>Nombre taller: </label> '.$talleres['nombre'].'</div>
							<div data-linea="2"><label>Dirección: </label>'.$talleres['direccion'].'</div>							
							<div data-linea="3"><label>Persona de contacto: </label>'.$talleres['contacto'].'</div>
							<div data-linea="3"><label>Teléfono: </label>'.$talleres['telefono'].'</div>
							<div data-linea="4"><label>Observación: </label>'.($talleres['observacion']!=''?$talleres['observacion']:'Sin novedad.').'</div>
			
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
