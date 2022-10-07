<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorCatalogos.php';
	require_once '../../clases/ControladorServiciosLinea.php';

	$conexion = new Conexion();;
	$cc = new ControladorCatalogos();
	$csl = new ControladorServiciosLinea();
	$idRutaTransporte=$_POST['elementos'];
	$res = $csl->buscarGARutasTransporte($conexion, '', '', '',null,1,$idRutaTransporte);
	$ruta = pg_fetch_assoc($res);
?>
<header>
	<h1>Eliminación de Rutas</h1>
</header>
	<form id='notificarRutaTransporte' data-rutaAplicacion='serviciosLinea' data-opcion='eliminarGARecorridosInstitucionales' data-destino="detalleItem" >
		<div id="estado"></div>
		<input type="hidden" name="idRutaTransporte" value="<?php echo $idRutaTransporte;?>" />	
		<input type="hidden" id="identificadorResponsable" name="identificadorResponsable" value="<?php echo $_SESSION['usuario'];?>" />
		
		<p style="text-align: center">
				<button type="submit" id="EliminarRutaInstitucional" name="EliminarRutaInstitucional" >Eliminar Ruta</button>
		</p>	
		<table>
			<tr style=" border: 0px;">
				<td>
					<fieldset>
						<legend>Información de Ruta</legend>
						<div data-linea="1">
							<label>Nombre ruta:</label>
								<?php echo $ruta['nombre_ruta'];?>
							</div>
							<div data-linea="2">
								<label>Provincia:</label>
								<?php echo $ruta['provincia'];?>
							</div>
							<div data-linea="2">
								<label>Cantón</label>
								<?php echo $ruta['canton'];?>
							</div>
							<div data-linea="3">
								<label>Oficina:</label>
								<?php echo $ruta['oficina'];?>
							</div>
							<div data-linea="3">
								<label>Sector:</label>
								<?php echo $ruta['sector'];?>
							</div>
							<div data-linea="4">
								<label>Conductor:</label>
								<?php echo $ruta['conductor'];?>
							</div>
							<div data-linea="4">
								<label>Teléfono:</label>
								<?php echo $ruta['telefono'];?>
							</div>
					</fieldset>
				</td>
			</tr>
		
			<tr style=" border: 0px;">
				<td>
					<fieldset>
						<legend>Rutas del Recorrido</legend>
						<div data-linea="1">
							<table class="tablaMatriz" style="width: 100%;">
								<thead>
									<tr>
										<th>#</th>
										<th>Latitud</th>
										<th>Longitud</th>
										<th>Dirección</th>
										<th>Hora Aproximada</th>
									</tr>
								</thead>
								<tbody >
									<?php 
									$qIdRutaTransporte=$csl->buscarDetalleRutaTransporte($conexion, $idRutaTransporte);
									$contador=1;
									while($fila=pg_fetch_assoc($qIdRutaTransporte)){
										echo "<tr>
											<td>".$contador++." </td>
											<td>".$fila['latitud']." </td>
											<td>".$fila['longitud']." </td>
											<td>".$fila['referencia_parada']." </td>
											<td>".$fila['hora_aproximada']."</td>";
										echo " </tr>";
									}
									?>
								</tbody>
							</table>
						</div>
					</fieldset>
				</td>
			</tr>
		</table>
	</form>		
<script type="text/javascript">
								
	$(document).ready(function(){
		distribuirLineas();
	});
	
	$("#notificarRutaTransporte").submit(function(event){
		event.preventDefault();
		ejecutarJson($(this));	
		if( $('#estado').html()=='Los datos han sido eliminados satisfactoriamente')
			$('#_actualizarSubListadoItems').click();
	});
</script>