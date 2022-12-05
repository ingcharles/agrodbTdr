<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCronogramaVacaciones.php';

$conexion = new Conexion();
$ccv = new ControladorCronogramaVacaciones();

$idCronogramaVacacion = $_POST['id'];

$qDatosCronogramaVacacion = $ccv->obtenerCronogramaVacacionesPorIdCronogramaVacacion($conexion, $idCronogramaVacacion);
$datosCronogramaVacacion = pg_fetch_assoc($qDatosCronogramaVacacion);

$qDatosPeriodoCronogramaVacacion = $ccv->obtenerPeriodoCronogramaVacacionesPorIdCronogramaVacacion($conexion, $idCronogramaVacacion); 

$datosPlanificarPeriodos = "";

$datosPlanificarPeriodos = '<header>
	<h1>Cronograma de vacaciones</h1>
</header>

	<fieldset>
		<legend>Cronograma de planificación</legend>

		<div data-linea="1">
			<label>Identificador: </label>'
			. $datosCronogramaVacacion['identificador_funcionario'] .
		'</div>
		<div data-linea="2">
			<label>Apellidos y nombres: </label>' 
			. $datosCronogramaVacacion['nombre_funcionario'] .
		'</div>
		<div data-linea="3">
			<label>Puesto institucional: </label>' 
			. $datosCronogramaVacacion['nombre_puesto'] .
		'</div>
		
	</fieldset>
	<fieldset>
		<legend>Detalle de periodos</legend>';
		$totalDias = 0;
					
		$datosPlanificarPeriodos .= '<table id="tPeriodosPlanificar" style="width: 100%;">
										<thead>
											<tr>
												<th>Periodo</th>
												<th>Fecha inicio</th>
												<th>Fecha fin</th>
												<th>Número días</th>
											</tr>
										</thead>
										<tbody>';

		while($datosPeriodoCronogramaVacacion = pg_fetch_assoc($qDatosPeriodoCronogramaVacacion)){
			$totalDias = $totalDias + $datosPeriodoCronogramaVacacion['total_dias'];

			$datosPlanificarPeriodos .= '<tr>	
			<td>Periodo ' . $item->numero_periodo . '</td>
			<td style="text-align: center;">' . $datosPeriodoCronogramaVacacion['fecha_inicio'] . '</td>
			<td style="text-align: center;">' . $datosPeriodoCronogramaVacacion['fecha_fin'] . '</td>
			<td style="text-align: center;">' . $datosPeriodoCronogramaVacacion['total_dias'] . '</td><tr>';
		}

		$datosPlanificarPeriodos .= '<tr>>
										<td>Total días</td>
										<td></td>
										<td></td>
										<td style="text-align: center;">' . $totalDias . '</td>
									</td>
									</tbody>
									</table>
									</fieldset>';

		echo $datosPlanificarPeriodos;

?>

<script type="text/javascript">

$(document).ready(function(){
	distribuirLineas();
	construirValidador();
});

</script>