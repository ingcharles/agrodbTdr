<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatastro.php';

$conexion = new Conexion();
$cc = new ControladorCatastro();

$res = $cc->obtenerInformacionFuncionarioContratoActivo($conexion, $_SESSION['usuario']);
$usuario = pg_fetch_assoc($res);



$detalleResultados = $cc->obtenerResultadosEvaluacionDesempenioAnual($conexion, $_SESSION['usuario']);
?>

<header>
	<h1>Resultados de Evaluación de Desempeño Anual</h1>
</header>

	<div id="estado"></div>
	<fieldset>

		<legend>Funcionario</legend>
			<div data-linea="1">
				<label>Cédula: </label><?php echo $usuario['identificador'];?>
			</div>
			
			<div data-linea="2">
				<label>Nombre: </label><?php echo $usuario['nombre'] . ' ' . $usuario['apellido'];?>
			</div>
			
			<div data-linea="3">
				<label>Puesto: </label> <?php echo $usuario['nombre_puesto'];?>
			</div>
			
			<div data-linea="4">
				<label>Unidad: </label> <?php echo $usuario['direccion'] . ' - ' . $usuario['gestion'];?>
			</div>
			
			<div data-linea="5">
				<label>Ubicación: </label> <?php echo $usuario['provincia'] . ' - ' . $usuario['canton'];?>
			</div>

	</fieldset>

	<fieldset>

		<legend>Resultados</legend>
		
		   <table id="tablaResultadosEvaluacion" style="width: 100%;">
				<thead>
					<tr>
					<th>Año</th>
					<!-- th>Cliente Externo 20%</th>
					<th>Cliente Interno 15%</th>
					<th>Indicadores Operativos 35%</th>
					<th>Calif. Individual 30%</th>
					<th>Régimen Disciplinario</th-->
					<th>Calificación Final 100%</th>
					<th>Escala de Calificación</th>
					<th >Observación</th>								
					</tr>
				</thead> 
				<?php
					while($fila = pg_fetch_assoc($detalleResultados)){ 
						echo'<tr>
							<td>'.$fila['anio'].'</td>	
							<!--td>'.($fila['calificacion_ciudadano']== 0 ? 'N/A': $fila['calificacion_ciudadano']).'</td>
                            <td>'.($fila['calificacion_cliente_interno']== ''? 'N/A': $fila['calificacion_cliente_interno']).'</td>
                            <td>'.($fila['indicador_operativo']== ''? 'N/A': $fila['indicador_operativo']).'</td>
                            <td>'.($fila['calificacion_individual']== ''? 'N/A': $fila['calificacion_individual']).'</td>
                            <td>'.($fila['regimen_disciplinario']== ''? 'N/A': $fila['regimen_disciplinario']).'</td-->
							<td>'.number_format($fila['calificacion_total'],3).'</td>
							<td>'.$fila['escala_calificacion'].'</td>
							<td>'.$fila['observacion'].'</td>
							</tr>';
					}
				 ?> 
			</table>

	</fieldset>


<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();
	});
</script>
