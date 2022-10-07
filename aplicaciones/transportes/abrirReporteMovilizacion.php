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
	<h1>Reporte movilizaciones</h1>
</header>

<?php


	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	$ce = new ControladorEmpleados();
	
	$reporte= ($_POST['valoresFiltrados']);
	
	echo'<table class="soloImpresion">
				<tr>';
	
	for ($i = 0; $i < count ($reporte); $i++) {
		
	$res = $cv->abrirMovilizacion($conexion, $reporte[$i]);
	$ocupantes = $cv->abrirMovilizacionOcupantes($conexion, $reporte[$i]);
	$recorrido = $cv->abrirMovilizacionRutas($conexion,$reporte[$i]);
	$movilizacion = pg_fetch_assoc($res);

	$per = $ce->obtenerDatosPersonales($conexion, $movilizacion['conductor']);
	$persona = pg_fetch_assoc($per);
	
	if ($i%3 == 0 && $i!= 0){
			
		echo '</tr><tr>';
	}
	
	
	echo ' <td>
					<fieldset>
						<legend>N° '.$movilizacion['id_movilizacion'].'</legend>
							<div data-linea="1"><label>Tipo: </label> '.$movilizacion['tipo_movilizacion'].'</div>
							<div data-linea="2"><label>Descripción: </label> '.$movilizacion['descripcion'].'</div>
							<div data-linea="3"><label>Placa: </label> '.$movilizacion['placa'].'</div>
							<div data-linea="3"><label>Kilometraje inicial: </label> '.$movilizacion['kilometraje_inicial'].'</div>
							<div data-linea="3"><label>Kilometraje final: </label> '.$movilizacion['kilometraje_final'].'</div>
							<div data-linea="4"><label>Conductor: </label>'.$persona['apellido'].' '.$persona['nombre'].'</div>							
							<div data-linea="5"><label>Ocupantes: </label>';
			
							while($fila = pg_fetch_assoc($ocupantes)){
								$vOcupante .= $fila['nombres_completos'].', ';
							}
							
							echo trim($vOcupante, ", ");
							
							$vOcupante = '';
	
					        echo'</div>
								 <div data-linea="5"><label>Observación ocupantes: </label> '.($movilizacion['observacion_ocupante']!=''?$movilizacion['observacion_ocupante']:'No aplica').'</div>
								 <div data-linea="6"><label>Recorrido: </label> ';

							if(pg_num_rows($recorrido)!=0){
								while($fila = pg_fetch_assoc($recorrido)){
									$vRecorrido .= $fila['localizacion'].', ';
								}
								if($movilizacion['observacion_ruta'] != "" ){
									echo 'Otro';
								}
							}else{
								echo 'Otro';
							}
							
							echo trim($vRecorrido, ", ");
							
							$vRecorrido = '';
	
		
						echo'</div>
							 <div data-linea="6"><label>Observación de ruta: </label> '.($movilizacion['observacion_ruta']!=''?$movilizacion['observacion_ruta']:'Sin novedad').'</div>
							 <div data-linea="8"><label>Observación de movilización: </label> '.($movilizacion['observacion_movilizacion']!=''?$movilizacion['observacion_movilizacion']:'Sin novedad').'</div>
							<div data-linea="8"><label>Fecha de solicitud: </label> '.date('j/n/Y (G:i)',strtotime($movilizacion['fecha_solicitud'])).'</div>
			
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
