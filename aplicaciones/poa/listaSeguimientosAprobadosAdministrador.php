<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';
$conexion = new Conexion();
$cpoa1 = new ControladorPAPP();

function uniqueArrayMultidimensional($array)
{
	$result = array_map("unserialize", array_unique(array_map("serialize", $array)));
	foreach ($result as $key => $value)
	{
		if ( is_array($value) )
		{
			$result[$key] = uniqueArrayMultidimensional($value);
		}
	}
	return $result;
}
?>

<header>
	<h1>Seguimientos Trimestrales Aprobados</h1>
	<nav>
	<form id="filtrar" class="desplegar" data-rutaAplicacion="poa" data-opcion="listadoSeguimientosAprobados" data-destino="tabla">
			<table class="filtro">
				<tr>
					<th>Que contenga</th>
					<td>Dirección:</td>
					<td>
						<select name="areaDireccion" id="areaDireccion">
						<?php
							$res= $cpoa1->listarArea($conexion);
							
							while($fila = pg_fetch_assoc($res)){
								if($_POST['id'] == $fila['id_area']){
									echo '<option value="' . $fila['id_area'] . '">' . $fila['nombre'] .'</option>';
								}
							}
						?>
						</select>
					</td>
				</tr>
				<tr>	
					<td></td>
					<td>subproceso:</td>
					<td>
						<select name='subproceso' id='subproceso'>
							<?php 
								$res = $cpoa1->listarSeguimientosRemitidosAdministrador($conexion, $_POST['id'], '', '', '', '', 4);
								while ($fila = pg_fetch_assoc($res)){
									$arraySubprocesos[] = array(
											idSubproceso=>$fila['id_subproceso'],
											subproceso=>$fila['subproceso']
									);
								}
								
								$subprocesos = uniqueArrayMultidimensional($arraySubprocesos);
								
								foreach ($subprocesos as $fila){
									echo '<option value="' . $fila['idSubproceso'] . '">' . $fila['subproceso'] .'</option>';
								}
							?>
						</select>
					</td>
				</tr>
				<tr>	
					<td></td>
					<td>asunto:</td>
					<td><input name="asunto" type="text" /></td>
				</tr>
				<tr>
					<th>Entre las fechas</th>
					<td>inicio:</td>
					<td><input type="text" name="fi" id="fechaInicio" /></td>
				</tr>
				<tr>	
					<td></td>
					<td>fin:</td>
					<td><input type="text" name="ff" id="fechaFin" /></td>
				</tr>
				<tr>
					<th>Mostrar</th>
					<td>estado:</td>
					<td><select name="estadoRegistro" id="estadoRegistro">
						<option value="3">Enviados por coordinador</option>
						<option value="4">Aprobados planta central</option>
					</select>
					</td>
				</tr>
				<tr>	
					<td></td>
					<td colspan="5"><button>Filtrar lista</button></td>
				</tr>
			</table>	
	</form>
	</nav>
</header>
<div id="tabla"></div>
<script>
	$("#filtrar").submit(function(e){
		abrir($(this),e,false);
	});
	$(document).ready(function(){
		distribuirLineas();
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#fechaInicio").datepicker();
		$("#fechaFin").datepicker();
		$("#detalleItem").html('<div class="mensajeInicial">Seguimientos Trimestrales</div>');
	});
	</script>
