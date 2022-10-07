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
	<h1>Reporte movilizaciones</h1>
</header>

<?php


	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	$ce = new ControladorEmpleados();
	
	$reporte= ($_POST['movilizaciones']);
	
	echo'<table class="soloImpresion">
				<tr>';
	
	for ($i = 0; $i < count ($reporte); $i++) {
		
	$res = $cv->abrirMovilizacion($conexion, $reporte[$i]);
	$ocupantes = $cv->abrirMovilizacionOcupantes($conexion, $reporte[$i]);
	$recorrido = $cv->abrirMovilizacionRutas($conexion,$reporte[$i]);
	$movilizacion = pg_fetch_assoc($res);

	$per = $ce->obtenerDatosPersonales($conexion, $movilizacion['conductor']);
	$persona = pg_fetch_assoc($per);
	
	
	echo ' <td>
					<fieldset>
						<legend>Movilización N° '.$movilizacion['id_movilizacion'].'</legend>
							<div><label>Tipo: </label> '.$movilizacion['tipo_movilizacion'].'</div>
							<div><label>Descripción: </label> '.$movilizacion['descripcion'].'</div>
							<div><label>Placa vehículo: </label> '.$movilizacion['placa'].'</div>
							<div><label>Conductor: </label>'.$persona['apellido'].' '.$persona['nombre'].'</div>							
							<div><label>Ocupantes: </label>';
			
							while($fila = pg_fetch_assoc($ocupantes)){
								echo $fila['nombres_completos'].', ';
							}
	
					        echo'</div><div><label>Recorrido: </label> ';

							if(pg_num_rows($recorrido)!=0){
								while($fila = pg_fetch_assoc($recorrido)){
									echo $fila['nombre'].', ';
								}
								if($movilizacion['observacion_ruta'] != "" ){
									echo 'Otro';
								}
							}else{
								echo 'Otro';
							}
	
		
						echo'</div><div><label>Observación de ruta: </label> '.($movilizacion['observacion_ruta']!=''?$movilizacion['observacion_ruta']:'Sin novedad').'</div>
							 <div><label>Observación de movilizión: </label> '.($movilizacion['observacion_movilizacion']!=''?$movilizacion['observacion_movilizacion']:'Sin novedad').'</div>
							 <div><label>Fecha de solicitud: </label> '.date('j/n/Y (G:i)',strtotime($movilizacion['fecha_solicitud'])).'</div>
			
		  			</fieldset>			
		   </td>';
	}
	
	echo '</tr></table>';
	

	
	?>
</body>
<script type="text/javascript">
</script>
</html>
