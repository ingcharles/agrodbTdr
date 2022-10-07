<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$fecha = getdate();

$conexion = new Conexion();
$cd = new ControladorPAPP();
$res = $cd->FiltrarSubProceso($conexion, $_SESSION['usuario'], $fecha['year'], 2);
$contador = 0;
$itemsFiltrados[] = array();

?>

<header>
	<h1>Registros Proforma</h1>
	<nav>
		<form id="filtrar" class="desplegar" data-rutaAplicacion="poa" data-opcion="listadoRegistrosPlantaFiltrados" data-destino="tabla">
				<input type="hidden" name="anio" value="<?php echo $fecha['year'];?>"/>
				<table class="filtro">
					<tr>
						<th>Que contenga</th>
						<td>subproceso:</td>
						<td><select name="subProceso" id="subProceso">
						<option value="">Todos</option>
						<?php 
						while($fila = pg_fetch_assoc($res)){
						   
		                   echo '<option value='.$fila['id_subproceso'].'>'.$fila['descripcion'].'</option>';
									
						}?>
					   </select>
					   </td>
						<td>asunto:</td>
						<td><input name="asunto" type="text" /></td>
					</tr>
					<tr>
						<th>Entre las fechas</th>
						<td>inicio:</td>
						<td><input type="text" name="fi" id="fechaInicio" /></td>
						<td>fin:</td>
						<td><input type="text" name="ff" id="fechaFin" /></td>
					</tr>
					<tr>
						<th>Mostrar</th>
						<td></td>
						<td>
						</td>
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
		$("#listadoItems").addClass("lista");
		$("#fechaInicio").datepicker();
		$("#fechaFin").datepicker();
		$("#detalleItem").html('<div class="mensajeInicial">Arrastre aquí un ítem para revisarlo.</div>');
	});
	</script>
