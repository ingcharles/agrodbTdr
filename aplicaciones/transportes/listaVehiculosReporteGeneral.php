<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$conexion = new Conexion();
$cv = new ControladorVehiculos();

$datos = array( 'placa' => htmlspecialchars ($_POST['placa'],ENT_NOQUOTES,'UTF-8'), 
				'cantidad' => htmlspecialchars ($_POST['cantidad'],ENT_NOQUOTES,'UTF-8'),
				'fechaInicio' => htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8'),
				'fechaFin' => htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8'),
				'sitio' => htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8'),
				'tipo' => htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8'));


switch ($datos['tipo']){
		case '1':
			echo 'Reporte vehículos';
			break;
			
		case '2':
			//Mantenimientos
			$res = $cv -> filtrarMantenimientoGeneral($conexion, $datos['placa'], $datos['sitio'], $datos['fechaInicio'], $datos['fechaFin'], $datos['cantidad']);
				
			echo '
					<form id="reporteGeneral" data-rutaAplicacion="transportes" data-opcion="abrirReporteGeneral" data-destino="detalleItem">
			
					<input type= "hidden" id="fechaInicio" name="fechaInicio" value="'.$datos['fechaInicio'].'" />
					<input type= "hidden" id="fechaFin" name="fechaFin" value="'.$datos['fechaFin'].'" />
					<input type= "hidden" id="localizacion" name="localizacion" value="'.$datos['sitio'].'" />
					<input type= "hidden" id="tipo" name="tipo" value="'.$datos['tipo'].'" />
			
						<table id="tablaItems">
							<thead>
								<tr>
									<th>Placa</th>
									<th>Marca</th>
									<th>Modelo</th>
									<th>Localización</th>
									<th>Valor</th>
								</tr>
							</thead>
							<tbody>
							</tbody>';
				
							while($fila = pg_fetch_assoc($res)){
								echo '<tr
														id="'.$fila['placa'].'"
														class="item">
															<td style="white-space:nowrap;"><b>'.$fila['placa'].'</b></td>
															<td>'.$fila['marca'].'</td>
															<td>'.$fila['modelo']	.'</td>
															<td>'.$fila['localizacion'].'</td>
															<td>'.number_format($fila['valor'],2).'</td>
														</tr>
														<input type="hidden" id="placas" name="placas[]" value="'.$fila['placa'].'" />
														<input type="hidden" id="valores" name="valores[]" value="'.$fila['valor'].'" />';
							}
			
			echo '</table>
						<button id="enviarFormulario" type="submit">Ver detalle</button>
					</form>';
			
			break;
			
		case '3': //ok 
			$res = $cv -> filtrarMovilizacionGeneral($conexion, $datos['placa'], $datos['sitio'], $datos['fechaInicio'], $datos['fechaFin'], $datos['cantidad']);
				
			echo '
					<form id="reporteGeneral" data-rutaAplicacion="transportes" data-opcion="abrirReporteGeneral" data-destino="detalleItem">
			
					<input type= "hidden" id="fechaInicio" name="fechaInicio" value="'.$datos['fechaInicio'].'" />
					<input type= "hidden" id="fechaFin" name="fechaFin" value="'.$datos['fechaFin'].'" />
					<input type= "hidden" id="localizacion" name="localizacion" value="'.$datos['sitio'].'" />
					<input type= "hidden" id="tipo" name="tipo" value="'.$datos['tipo'].'" />
			
						<table id="tablaItems">
							<thead>
								<tr>
									<th>Placa</th>
									<th>Marca</th>
									<th>Modelo</th>
									<th>Localización</th>
									<th>Cantidad Recorrida (km)</th>
								</tr>
							</thead>
							<tbody>
							</tbody>';
				
							while($fila = pg_fetch_assoc($res)){
								echo '<tr
														id="'.$fila['placa'].'"
														class="item">
															<td style="white-space:nowrap;"><b>'.$fila['placa'].'</b></td>
															<td>'.$fila['marca'].'</td>
															<td>'.$fila['modelo']	.'</td>
															<td>'.$fila['localizacion'].'</td>
															<td align="center">'.$fila['valor'].'</td>
														</tr>
														<input type="hidden" id="placas" name="placas[]" value="'.$fila['placa'].'" />
														<input type="hidden" id="valores" name="valores[]" value="'.$fila['valor'].'" />';
							}
			
			echo '</table>

						<button id="enviarFormulario" type="submit">Ver detalle</button>
					</form>';
			
			break;
			
		case '4':
			//Siniestros
			$res = $cv -> filtrarSiniestroGeneral($conexion, $datos['placa'], $datos['sitio'], $datos['fechaInicio'], $datos['fechaFin'], $datos['cantidad']);
			
			echo '
					<form id="reporteGeneral" data-rutaAplicacion="transportes" data-opcion="abrirReporteGeneral" data-destino="detalleItem">
					
					<input type= "hidden" id="fechaInicio" name="fechaInicio" value="'.$datos['fechaInicio'].'" />
					<input type= "hidden" id="fechaFin" name="fechaFin" value="'.$datos['fechaFin'].'" />
					<input type= "hidden" id="localizacion" name="localizacion" value="'.$datos['sitio'].'" />
					<input type= "hidden" id="tipo" name="tipo" value="'.$datos['tipo'].'" />
						
						<table id="tablaItems">
							<thead>
								<tr>
									<th>Placa</th>
									<th>Marca</th>
									<th>Modelo</th>
									<th>Localización</th>
									<th>Monto generado</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
							</tbody>';
			
								while($fila = pg_fetch_assoc($res)){
									echo '<tr
										id="'.$fila['placa'].'"
										class="item">
											<td style="white-space:nowrap;"><b>'.$fila['placa'].'</b></td>
											<td>'.$fila['marca'].'</td>
											<td>'.$fila['modelo']	.'</td>
											<td>'.$fila['localizacion'].'</td>
											<td>$'.number_format($fila['valor_total'],2).'</td>
											<td>'.($fila['estado']==1?'Registrada':($fila['estado']==2?'En trámite':'Finalizada')).'</td>
										</tr>
										<input type="hidden" id="placas" name="placas[]" value="'.$fila['placa'].'" />
										<input type="hidden" id="valores" name="valores[]" value="'.$fila['valor_total'].'" />';
								}

					echo '</table>

						<button id="enviarFormulario" type="submit">Ver detalle</button>
					</form>';
			
			break;
			
		case '5'://ok
			//Combustible
			$res = $cv -> filtrarCombustibleGeneral($conexion, $datos['placa'], $datos['sitio'], $datos['fechaInicio'], $datos['fechaFin'], $datos['cantidad']);
			
			echo '
					<form id="reporteGeneral" data-rutaAplicacion="transportes" data-opcion="abrirReporteGeneral" data-destino="detalleItem">
					
					<input type= "hidden" id="fechaInicio" name="fechaInicio" value="'.$datos['fechaInicio'].'" />
					<input type= "hidden" id="fechaFin" name="fechaFin" value="'.$datos['fechaFin'].'" />
					<input type= "hidden" id="localizacion" name="localizacion" value="'.$datos['sitio'].'" />
					<input type= "hidden" id="tipo" name="tipo" value="'.$datos['tipo'].'" />
						
						<table id="tablaItems">
							<thead>
								<tr>
									<th>Placa</th>
									<th>Marca</th>
									<th>Modelo</th>
									<th>Localización</th>
									<th>Valor</th>
								</tr>
							</thead>
							<tbody>
							</tbody>';
			
								while($fila = pg_fetch_assoc($res)){
									echo '<tr
										id="'.$fila['placa'].'"
										class="item">
											<td style="white-space:nowrap;"><b>'.$fila['placa'].'</b></td>
											<td>'.$fila['marca'].'</td>
											<td>'.$fila['modelo']	.'</td>
											<td>'.$fila['localizacion'].'</td>
											<td>'.number_format($fila['valor'],2).'</td>
										</tr>
										<input type="hidden" id="placas" name="placas[]" value="'.$fila['placa'].'" />
										<input type="hidden" id="valores" name="valores[]" value="'.$fila['valor'].'" />';
								}

					echo '</table>

						<button id="enviarFormulario" type="submit">Ver detalle</button>
					</form>';
			
			break;
			
		case '6': //ok

			$res = $cv -> filtrarVehiculosAntiguos($conexion, $datos['placa'], $datos['sitio'], $datos['cantidad']);
			
			echo '
					<form id="reporteGeneral" data-rutaAplicacion="transportes" data-opcion="abrirReporteGeneral" data-destino="detalleItem">
			
					<input type= "hidden" id="localizacion" name="localizacion" value="'.$datos['sitio'].'" />
					<input type= "hidden" id="tipo" name="tipo" value="'.$datos['tipo'].'" />
			
						<table id="tablaItems">
							<thead>
								<tr>
									<th>Placa</th>
									<th>Marca</th>
									<th>Modelo</th>
									<th>Localización</th>
									<th>Año de fabricación</th>
								</tr>
							</thead>
							<tbody>
							</tbody>';
				
			while($fila = pg_fetch_assoc($res)){
				echo '<tr
										id="'.$fila['placa'].'"
										class="item">
											<td style="white-space:nowrap;"><b>'.$fila['placa'].'</b></td>
											<td>'.$fila['marca'].'</td>
											<td>'.$fila['modelo']	.'</td>
											<td>'.$fila['localizacion'].'</td>
											<td align="center">'.$fila['anio_fabricacion'].'</td>
										</tr>
										<input type="hidden" id="placas" name="placas[]" value="'.$fila['placa'].'" />
										<input type="hidden" id="valores" name="valores[]" value="'.$fila['anio_fabricacion'].'" />';
			}
			
			echo '</table>

						<button id="enviarFormulario" type="submit">Ver detalle</button>
					</form>';
			
			break;
			
			case '7':
									
				$res = $cv -> filtrarRendimientoGeneral($conexion, $datos['placa'], $datos['sitio'], $datos['fechaInicio'], $datos['fechaFin'], $datos['cantidad']);
					
				echo '
					<form id="reporteGeneral" data-rutaAplicacion="transportes" data-opcion="abrirReporteGeneral" data-destino="detalleItem">
			
					<input type= "hidden" id="fechaInicio" name="fechaInicio" value="'.$datos['fechaInicio'].'" />
					<input type= "hidden" id="fechaFin" name="fechaFin" value="'.$datos['fechaFin'].'" />
					<input type= "hidden" id="localizacion" name="localizacion" value="'.$datos['sitio'].'" />
					<input type= "hidden" id="tipo" name="tipo" value="'.$datos['tipo'].'" />
			
						<table id="tablaItems">
							<thead>
								<tr>
									<th>Placa</th>
									<th>Marca</th>
									<th>Modelo</th>
									<th>Localización</th>
									<th>Rendimiento (Km/Gal)</th>
								</tr>
							</thead>
							<tbody>
							</tbody>';
					
				while($fila = pg_fetch_assoc($res)){
					echo '<tr
										id="'.$fila['placa'].'"
										class="item">
											<td style="white-space:nowrap;"><b>'.$fila['placa'].'</b></td>
											<td>'.$fila['marca'].'</td>
											<td>'.$fila['modelo']	.'</td>
											<td>'.$fila['localizacion'].'</td>
											<td align="center">'.number_format($fila['valor'],2).'</td>
										</tr>
										<input type="hidden" id="placas" name="placas[]" value="'.$fila['placa'].'" />
										<input type="hidden" id="valores" name="valores[]" value="'.$fila['valor'].'" />';
				}
			
				echo '</table>

						<button id="enviarFormulario" type="submit">Ver detalle</button>
					</form>';
					
				break;
			
		case '9': //OK
			
			$res = $cv -> filtrarVehiculosDeBaja($conexion, $datos['placa'], $datos['sitio'], $datos['cantidad']);
				
			echo '
					<form id="reporteGeneral" data-rutaAplicacion="transportes" data-opcion="abrirReporteGeneral" data-destino="detalleItem">
		
					<input type= "hidden" id="localizacion" name="localizacion" value="'.$datos['sitio'].'" />
					<input type= "hidden" id="tipo" name="tipo" value="'.$datos['tipo'].'" />
		
						<table id="tablaItems">
							<thead>
								<tr>
									<th>Placa</th>
									<th>Marca</th>
									<th>Modelo</th>
									<th>Localización</th>
									<th>Motivo</th>
								</tr>
							</thead>
							<tbody>
							</tbody>';
			
			while($fila = pg_fetch_assoc($res)){
				echo '<tr
										id="'.$fila['placa'].'"
										class="item">
											<td style="white-space:nowrap;"><b>'.$fila['placa'].'</b></td>
											<td>'.$fila['marca'].'</td>
											<td>'.$fila['modelo']	.'</td>
											<td>'.$fila['localizacion'].'</td>
											<td>'.$fila['concepto_baja'].'</td>
										</tr>
										<input type="hidden" id="placas" name="placas[]" value="'.$fila['placa'].'" />
										<input type="hidden" id="valores" name="valores[]" value="'.$fila['concepto_baja'].'" />';
			}
				
			echo '</table>

						<button id="enviarFormulario" type="submit">Ver detalle</button>
					</form>';
			
			break;

		case '10': //Gasolineras
			$res = $cv -> filtrarGasolinerasGeneral($conexion, $datos['sitio'], $datos['fechaInicio'], $datos['fechaFin'], $datos['cantidad']);
			
			echo '
			<form id="reporteGeneral" data-rutaAplicacion="transportes" data-opcion="abrirReporteGeneral" data-destino="detalleItem">
				
			<input type= "hidden" id="fechaInicio" name="fechaInicio" value="'.$datos['fechaInicio'].'" />
			<input type= "hidden" id="fechaFin" name="fechaFin" value="'.$datos['fechaFin'].'" />
			<input type= "hidden" id="localizacion" name="localizacion" value="'.$datos['sitio'].'" />
			<input type= "hidden" id="tipo" name="tipo" value="'.$datos['tipo'].'" />
				
			<table id="tablaItems">
			<thead>
			<tr>
			<th>Gasolinera</th>
			<th>Localización</th>
			<th>Valor</th>
			</tr>
			</thead>
			<tbody>
			</tbody>';
			
			while($fila = pg_fetch_assoc($res)){
				echo '<tr
				id="'.$fila['id_gasolinera'].'"
				class="item">
				<td style="white-space:nowrap;"><b>'.$fila['nombre'].'</b></td>
				<td>'.$fila['localizacion'].'</td>
				<td>'.number_format($fila['valor'],2).'</td>
				</tr>
				<input type="hidden" id="placa" name="placas[]" value="'.$fila['id_gasolinera'].'" />
				<input type="hidden" id="valores" name="valores[]" value="'.$fila['valor'].'" />';
			}
				
			echo '</table>

						<button id="enviarFormulario" type="submit">Ver detalle</button>
					</form>';
		break;
		
		case '11': //Talleres 
			$res = $cv -> filtrarTalleresGeneral($conexion, $datos['sitio'], $datos['fechaInicio'], $datos['fechaFin'], $datos['cantidad']);
			
			echo '
				<form id="reporteGeneral" data-rutaAplicacion="transportes" data-opcion="abrirReporteGeneral" data-destino="detalleItem">
					
				<input type= "hidden" id="fechaInicio" name="fechaInicio" value="'.$datos['fechaInicio'].'" />
				<input type= "hidden" id="fechaFin" name="fechaFin" value="'.$datos['fechaFin'].'" />
				<input type= "hidden" id="localizacion" name="localizacion" value="'.$datos['sitio'].'" />
				<input type= "hidden" id="tipo" name="tipo" value="'.$datos['tipo'].'" />
					
				<table id="tablaItems">
				<thead>
				<tr>
				<th>Taller</th>
				<th>Localización</th>
				<th>Valor</th>
				</tr>
				</thead>
				<tbody>
				</tbody>';
				
				while($fila = pg_fetch_assoc($res)){
					echo '<tr
					id="'.$fila['id_taller'].'"
					class="item">
					<td style="white-space:nowrap;"><b>'.$fila['nombre'].'</b></td>
					<td>'.$fila['localizacion'].'</td>
					<td>'.number_format($fila['valor'],2).'</td>
					</tr>
					<input type="hidden" id="placa" name="placas[]" value="'.$fila['id_taller'].'" />
					<input type="hidden" id="valores" name="valores[]" value="'.$fila['valor'].'" />';
				}
					
				echo '</table>

						<button id="enviarFormulario" type="submit">Ver detalle</button>
					</form>';
				
		break;
		
		case '12': //Vehículos
			$res = $cv -> filtrarVehiculosRegistrados($conexion, $datos['placa'], $datos['sitio'], $datos['cantidad']);
				
			echo '
			<form id="reporteGeneral" data-rutaAplicacion="transportes" data-opcion="abrirReporteGeneral" data-destino="detalleItem">
				
			<input type= "hidden" id="localizacion" name="localizacion" value="'.$datos['sitio'].'" />
			<input type= "hidden" id="tipo" name="tipo" value="'.$datos['tipo'].'" />
				
			<table id="tablaItems">
			<thead>
			<tr>
			<th>Placa</th>
			<th>Marca</th>
			<th>Modelo</th>
			<th>Localización</th>
			<th>Kilometraje</th>
			</tr>
			</thead>
			<tbody>
			</tbody>';
			
			while($fila = pg_fetch_assoc($res)){
				echo '<tr
				id="'.$fila['placa'].'"
				class="item">
				<td style="white-space:nowrap;"><b>'.$fila['placa'].'</b></td>
				<td>'.$fila['marca'].'</td>
				<td>'.$fila['modelo']	.'</td>
				<td>'.$fila['localizacion'].'</td>
				<td align="center">'.number_format($fila['kilometraje_actual'],0).'</td>
				</tr>
				<input type="hidden" id="placas" name="placas[]" value="'.$fila['placa'].'" />
				<input type="hidden" id="valores" name="valores[]" value="'.$fila['kilometraje_actual'].'" />';
			}
				
			echo '</table>

						<button id="enviarFormulario" type="submit">Ver detalle</button>
					</form>';
				
		break;
		
		default:
			echo 'Reporte desconocido';
}

?>

<script type="text/javascript"> 

var tipo= <?php echo json_encode($datos['tipo']); ?>;

	/*$("#reporteHistorialVehiculos").submit(function(event){
		abrir($(this),event,false);
	});*/
	
	/*$(document).ready(function(){		
		abrir($("#reporteGeneral"),event,false);	
	});*/

	$("#reporteGeneral").submit(function(event){
		abrir($("#reporteGeneral"),event,false);	
	});

</script>
