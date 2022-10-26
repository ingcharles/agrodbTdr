<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVacaciones.php';

try {
	$conexion = new Conexion();
	$cv = new ControladorVacaciones();
	
	$tmp = explode('.',$_POST['id']);
	$identificador=$tmp[0];
	$estado=$tmp[1];		
	
	$listaReporte = $cv->filtroObtenerReporteSaldoUsuario($conexion, $identificador,$estado, '', '', '', 'individual');
	
} catch (Exception $e) {
	echo $e;
}


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>

	<fieldset>
		<legend>Saldo de vacaciones</legend>

		<table style="width: 100%">
			<thead>
				<tr>
					<th>Identificador</th>
					<th>Nombre funcionario</th>
					<th>Año</th>
					<th>Cantidad disponible</th>
				</tr>
			</thead>

			<?php 
			$contador = 0;
			while($fila = pg_fetch_assoc($listaReporte)) {

			$dias=floor(intval($fila['minutos_disponibles'])/480);
			$horas=floor((intval($fila['minutos_disponibles'])-$dias*480)/60);
			$minutos=(intval($fila['minutos_disponibles'])-$dias*480)-$horas*60;

			echo '<tr>
					<td>'.$fila['identificador'].'</td>
					<td>'.$fila['apellido'].' '.$fila['nombre'].'</td>
					<td>'.$fila['anio'].'</td>
					<td>'. $dias.' días '. $horas .' horas '. $minutos .' minutos</td>
				</tr>';

	 	}
	 	?>
		</table>


	</fieldset>
</body>

</html>
