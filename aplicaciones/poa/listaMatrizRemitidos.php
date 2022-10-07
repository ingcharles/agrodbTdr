<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorPAPP.php';
	
	$fecha = getdate();
	
	$conexion = new Conexion();
	$cd = new ControladorPAPP();
	$res = $cd->FiltrarSubProceso($conexion, $_SESSION['usuario'], $fecha['year'], 4);
	$contador = 0;
	$itemsFiltrados[] = array();
?>

<header>
	<h1>Registros matriz presupuesto</h1>
	<nav>
	<form id="filtrar" class="desplegar" data-rutaAplicacion="poa" data-opcion="listadoMPresupuestoFiltrados" data-destino="tabla">
		<table class="filtro">
			<tr>
				<th>Que contenga</th>
				<td>subproceso:</td>
				<td>
				<select id="subProceso" name="subProceso">
				<option value="null">Todos</option>
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
				<td>estado:</td>
				<td><select name="estado">
					<option value="">Todos</option>
					<!-- option value="1">Sin notificar</option>
					<option value="1">Pendientes</option>
					<option value="1">Aprobados</option-->
				</select>
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
		$("#listadoItems").removeClass("comunes");
		$("#listadoItems").addClass("lista");
		$("#fechaInicio").datepicker();
		$("#fechaFin").datepicker();
		$("#detalleItem").html('<div class="mensajeInicial">Registros de matriz de presupuestos.</div>');
	});
	</script>
