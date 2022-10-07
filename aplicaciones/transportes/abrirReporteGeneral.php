<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<?php 
		switch($_POST['tipo']){					
			case '2':
				echo '<h1>Mantenimiento (Costo)</h1>';
				break;
			
			case '3':
				echo '<h1>Km. Recorridos (Movilizaciones)</h1>';
				break;
				
			case '4':
				echo '<h1>Siniestros</h1>';
				break;
				
			case '5':
				echo '<h1>Combustible (Mayor consumo)</h1>';
				break;
				
			case '6':
				echo '<h1>Vehículos más antiguos</h1>';
				break;
				
			case '7':
				echo '<h1>Menor rendimiento</h1>';
				break;			
				
			case '9':
				echo '<h1>Vehículos dados de baja</h1>';
				break;
				
			case '10':
				echo '<h1>Gasolineras</h1>';
				break;
				
			case '11':
				echo '<h1>Talleres</h1>';
				break;
				
			case '12':
				echo '<h1>Vehículos registrados</h1>';
				break;
					
			default:
				echo 'Reporte Generales';
		}
	?>
</header>

<?php 
$datos = array(	'fechaInicio' => htmlspecialchars ($_POST['fechaInicio'],ENT_NOQUOTES,'UTF-8'),
				'fechaFin' => htmlspecialchars ($_POST['fechaFin'],ENT_NOQUOTES,'UTF-8'),
				'sitio' => htmlspecialchars ($_POST['sitio'],ENT_NOQUOTES,'UTF-8'),
				'tipo' => htmlspecialchars ($_POST['tipo'],ENT_NOQUOTES,'UTF-8'));

$placa = $_POST['placas'];
$valorTotal = $_POST['valores'];


	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	
	
	
	echo'<table class="soloImpresion">
				<tr>';
	
	for ($i = 0; $i < count ($placa); $i++) {
		
		switch ($datos['tipo']){
			case '1':
				echo 'Reporte vehículos';
				break;
					
			case '2':
				//Mantenimientos realizados
				$res = $cv->abrirHistorialIndividualMantenimiento($conexion, $placa[$i], $datos['fechaInicio'], $datos['fechaFin'], $datos['sitio']);
				$mantenimiento = pg_fetch_assoc($res);
				break;
					
			case '3':
				$res = $cv->abrirHistorialIndividualMovilizacion($conexion, $placa[$i], $datos['fechaInicio'], $datos['fechaFin'], $datos['sitio']);
				$movilizacion = pg_fetch_assoc($res);
				break;
					
			case '4':
				$res = $cv->abrirHistorialIndividualSiniestro($conexion, $placa[$i], $datos['fechaInicio'], $datos['fechaFin'], $datos['sitio']);
				$siniestro = pg_fetch_assoc($res);
				break;
					
			case '5':
				$res = $cv->abrirHistorialIndividualCombustible($conexion, $placa[$i], $datos['fechaInicio'], $datos['fechaFin'], $datos['sitio']);
				$combustible = pg_fetch_assoc($res);
				break;
					
			case '6':
				//Reporte antiguos
				$res = $cv -> abrirVehiculo($conexion, $placa[$i]);
				$vehiculos = pg_fetch_assoc($res);
				break;
			
			case '7':
				$res = $cv->abrirHistorialIndividualCombustible($conexion, $placa[$i], $datos['fechaInicio'], $datos['fechaFin'], $datos['sitio']);
				$combustible = pg_fetch_assoc($res);
				break;
					
			case '9':
				//Reporte baja
				$res = $cv -> abrirVehiculo($conexion, $placa[$i]);
				$vehiculos = pg_fetch_assoc($res);
				break;

			case '10':
				//Reporte Gasolineras
				$res = $cv->abrirHistorialIndividualGasolinera($conexion, $placa[$i], $datos['fechaInicio'], $datos['fechaFin'], $datos['sitio']);
				$gasolinera = pg_fetch_assoc($res);
				break;
				
			case '11':
				//Reporte Talleres
				$res = $cv->abrirHistorialIndividualTaller($conexion, $placa[$i], $datos['fechaInicio'], $datos['fechaFin'], $datos['sitio']);
				$taller = pg_fetch_assoc($res);
				break;
				
			case '12':
				//Reporte vehiculos
				$res = $cv -> abrirVehiculo($conexion, $placa[$i]);
				$vehiculos = pg_fetch_assoc($res);
				break;
				
			default:
				echo 'Reporte desconocido';
		}
		
		
		
		if ($i%3 == 0 && $i!= 0){
			
			echo '</tr><tr>';
		}
		
		
		echo ' <td>';
		
		switch ($datos['tipo']){
			case '1':
				echo 'Reporte vehículos';
				break;
					
			case '2':
				//Mantenimientos realizados
				//$valorTotal = $mantenimiento['suma_preventivo'] + $mantenimiento['suma_correctivo'] + $mantenimiento['suma_lavada']; 
				echo '<fieldset>
						<legend>Información adicional</legend>
							<div data-linea="1"><label>Placa: </label> '.$mantenimiento['placa'].'</div>
							<div data-linea="1"><label>Marca: </label>'.$mantenimiento['marca'].'</div>
							<div data-linea="6"><label>Modelo: </label>'.$mantenimiento['modelo'].'</div>
							<div data-linea="6"><label>Año fabricación: </label>'.$mantenimiento['anio_fabricacion'].'</div>
							<hr/>
							<div data-linea="2"><label># Total órdenes: </label>'.$mantenimiento['numero'].'</div>
							<div data-linea="2"><label>Valor total: </label>'.number_format($valorTotal[$i],2).'</div>
							<hr>
							<div data-linea="3"><label># Órdenes preventivos : </label>'.$mantenimiento['numero_preventivo'].'</div>
							<div data-linea="3"><label>Valor total: </label>'.number_format($mantenimiento['suma_preventivo'],2).'</div>
							<hr>
							<div data-linea="4"><label># Órdenes correctivos : </label>'.$mantenimiento['numero_correctivo'].'</div>
							<div data-linea="4"><label>Valor total: </label>'.number_format($mantenimiento['suma_correctivo'],2).'</div>
							<hr>
							<div data-linea="5"><label># Órdenes lavadas : </label>'.$mantenimiento['numero_lavada'].'</div>
							<div data-linea="5"><label>Valor total: </label>'.number_format($mantenimiento['suma_lavada'],2).'</div>
							<hr>
							<div data-linea="7"><label># Órdenes pendientes : </label>'.$mantenimiento['numero_pendientes'].'</div>
							<div data-linea="7"><label># Órdenes eliminadas : </label>'.$mantenimiento['numero_eliminados'].'</div>				        
		  			</fieldset>';
				break;
					
			case '3':
				echo '<fieldset>
						<legend>Información adicional</legend>
							<div data-linea="1"><label>Placa: </label> '.$movilizacion['placa'].'</div>
							<div data-linea="1"><label>Marca: </label>'.$movilizacion['marca'].'</div>
							<div data-linea="1"><label>Modelo: </label>'.$movilizacion['modelo'].'</div>
							<div><br/></div>
							<div data-linea="3"><label># órdenes cancelado: </label>'.$movilizacion['numero'].'</div>
							<div data-linea="4"><label>Km. recorridos: </label>'.number_format($valorTotal[$i],2).'</div>				        
		  			</fieldset>';
				break;
					
			case '4':
				echo '<fieldset>
						<legend>Información siniestros</legend>
							<div data-linea="1"><label>Placa: </label> '.$siniestro['placa'].'</div>
							<div data-linea="1"><label>Marca: </label>'.$siniestro['marca'].'</div>
							<div data-linea="2"><label>Modelo: </label>'.$siniestro['modelo'].'</div>
							<hr/>
							<div data-linea="3"><label>Id orden: </label>'.$siniestro['id'].'</div>
							<div data-linea="3"><label>Fecha: </label>'.$siniestro['fecha_siniestro'].'</div>
							<div data-linea="4"><label>Tipo: </label>'.$siniestro['tipo'].'</div>
							<div data-linea="4"><label>Magnitud daño: </label>'.$siniestro['magnitud_danio'].'</div>
							<div data-linea="5"><label>Detalle: </label> '.$siniestro['observacion'].'</div>
							<div data-linea="6"><label>Monto: </label> $'.number_format($siniestro['valor_total'],2).'</div>
							<div data-linea="6"><label>Daño a terceros: </label>$'.number_format($siniestro['monto_danio_terceros'],2).'</div>
							<div data-linea="7"><label>Informe: </label><a href="'.$siniestro['documentacion'].'" download="'.$siniestro['id_siniestro'].'.pdf">Documentación generada</a></div>					        
							<div data-linea="7"><label>Estado: </label>'.($siniestro['estado']==1?'Registrada':($siniestro['estado']==2?'En trámite':'Finalizada')).'</div>
		  			</fieldset>';
					
				break;
					
			case '5':
				
				$kilometrosRecorridos = $combustible['maximo'] - $combustible['minimo'];
				
				echo '<fieldset>
						<legend>Información adicional</legend>
							<div data-linea="1"><label>Placa: </label> '.$combustible['placa'].'</div>
							<div data-linea="1"><label>Marca: </label>'.$combustible['marca'].'</div>
							<div data-linea="2"><label>Modelo: </label>'.$combustible['modelo'].'</div>
							<div data-linea="2"><label>Año fabricación: </label>'.$combustible['anio_fabricacion'].'</div>
							<hr/>
							<div data-linea="3"><label># Órdenes: </label>'.$combustible['numero'].'</div>
							<div data-linea="3"><label>Km recorridos : </label>'.number_format($kilometrosRecorridos,2).'</div>
							<div data-linea="4"><label>Valor cancelado: </label>'.number_format($valorTotal[$i],2).'</div>
							<div data-linea="4"><label>Galones: </label>'.number_format($combustible['galones'],2).'</div>
							<div data-linea="4"><label>Rendimiento Km/Gal : </label>'.number_format($kilometrosRecorridos/$combustible['galones'],2).'</div>					        
		  			</fieldset>';
					
				break;
					
			case '6':
				echo '<fieldset>
						<legend>Información vehículos antiguos</legend>
							<div data-linea="1"><label>Placa: </label> '.$vehiculos['placa'].'</div>
							<div data-linea="1"><label>Marca: </label>'.$vehiculos['marca'].'</div>
							<div data-linea="1"><label>Modelo: </label>'.$vehiculos['modelo'].'</div>
							<div><br/></div>
							<div data-linea="2"><label>Año fabricación: </label>'.$vehiculos['anio_fabricacion'].'</div>
							<div data-linea="2"><label>Tipo combustible: </label>'.$vehiculos['combustible'].'</div>
							<div data-linea="2"><label>Kilometraje: </label>'.number_format($vehiculos['kilometraje_actual'],0).'</div>
							<div><br/></div>
							<div data-linea="3"><label>Localización: </label>'.$vehiculos['localizacion'].'</div>
							<div data-linea="3"><label>Avalúo: </label>'.$vehiculos['avaluo'].'</div>
		  			</fieldset>';
				break;
				
			case '7':
				
					$kilometrosRecorridos = $combustible['maximo'] - $combustible['minimo'];
				
					echo '<fieldset>
						<legend>Información adicional</legend>
							<div data-linea="1"><label>Placa: </label> '.$combustible['placa'].'</div>
							<div data-linea="1"><label>Marca: </label>'.$combustible['marca'].'</div>
							<div data-linea="1"><label>Modelo: </label>'.$combustible['modelo'].'</div>
							<div><br/></div>
							<div data-linea="3"><label># Órdenes: </label>'.$combustible['numero'].'</div>
							<div data-linea="3"><label>Km recorridos : </label>'.number_format($kilometrosRecorridos,2).'</div>
							<div data-linea="3"><label>Valor cancelado: </label>'.number_format($valorTotal[$i],2).'</div>
							<div><br/></div>
							<div data-linea="4"><label>Galones: </label>'.number_format($combustible['galones'],2).'</div>
							<div data-linea="4"><label>Rendimiento Km/Galónes : </label>'.number_format($kilometrosRecorridos/$combustible['galones'],2).'</div>
		  			</fieldset>';
						
					break;
					
			case '9': //ok
				echo '<fieldset>
						<legend>Información vehículos dados de baja</legend>
							<div data-linea="1"><label>Placa: </label> '.$vehiculos['placa'].'</div>
							<div data-linea="1"><label>Marca: </label>'.$vehiculos['marca'].'</div>
							<div data-linea="1"><label>Modelo: </label>'.$vehiculos['modelo'].'</div>
							<div><br/></div>
							<div data-linea="2"><label>Año fabricación: </label>'.$vehiculos['anio_fabricacion'].'</div>
							<div data-linea="2"><label>Kilometraje: </label>'.number_format($vehiculos['kilometraje_actual'],2).'</div>
							<div><br/></div>
							<div data-linea="3"><label>Localización: </label>'.$vehiculos['localizacion'].'</div>
							<div data-linea="3"><label>Avalúo: </label>'.number_format($vehiculos['avaluo'], 2).'</div>
							<div><br/></div>
							<div data-linea="4"><label>Concepto baja: </label>'.$vehiculos['concepto_baja'].'</div>
		  			</fieldset>';
				break;
				
			case '10'://Gasolineras
					echo '<fieldset>
							<legend>Información adicional</legend>
								<div data-linea="1"><label>Gasolinera: </label> '.$gasolinera['nombre'].'</div>
								<div data-linea="8"><label>Localización: </label>'.$gasolinera['localizacion'].'</div>
								<div data-linea="6"><label>Dirección: </label>'.$gasolinera['direccion'].'</div>
								<hr/>
								<div data-linea="2"><label># Total órdenes: </label>'.$gasolinera['numero'].'</div>
								<div data-linea="2"><label>Valor total: </label>'.number_format($valorTotal[$i],2).'</div>
								<hr>
								<div data-linea="3"><label># Órdenes Super : </label>'.$gasolinera['numero_super'].'</div>
								<div data-linea="3"><label>Valor total: </label>'.number_format($gasolinera['suma_super'],2).'</div>
								<hr>
								<div data-linea="4"><label># Órdenes Diesel : </label>'.$gasolinera['numero_diesel'].'</div>
								<div data-linea="4"><label>Valor total: </label>'.number_format($gasolinera['suma_diesel'],2).'</div>
								<hr>
								<div data-linea="5"><label># Órdenes Extra : </label>'.$gasolinera['numero_extra'].'</div>
								<div data-linea="5"><label>Valor total: </label>'.number_format($gasolinera['suma_extra'],2).'</div>
								<hr>
								<div data-linea="9"><label># Órdenes Ecopaís : </label>'.$gasolinera['numero_ecopais'].'</div>
								<div data-linea="9"><label>Valor total: </label>'.number_format($gasolinera['suma_ecopais'],2).'</div>
								<hr>
								<div data-linea="7"><label># Órdenes pendientes : </label>'.$gasolinera['numero_pendientes'].'</div>
								<div data-linea="7"><label># Órdenes eliminadas : </label>'.$gasolinera['numero_eliminados'].'</div>
						</fieldset>';
				break;
				
			case '11'://Talleres
					echo '<fieldset>
							<legend>Información adicional</legend>
							<div data-linea="1"><label>Taller: </label> '.$taller['nombre'].'</div>
							<div data-linea="8"><label>Localización: </label>'.$taller['localizacion'].'</div>
							<div data-linea="6"><label>Dirección: </label>'.$taller['direccion'].'</div>
							<hr/>
							<div data-linea="2"><label># Total órdenes: </label>'.$taller['numero'].'</div>
							<div data-linea="2"><label>Valor total: </label>'.number_format($valorTotal[$i],2).'</div>
							<hr>
							<div data-linea="3"><label># Órdenes preventivos : </label>'.$taller['numero_preventivo'].'</div>
							<div data-linea="3"><label>Valor total: </label>'.number_format($taller['suma_preventivo'],2).'</div>
							<hr>
							<div data-linea="4"><label># Órdenes correctivos : </label>'.$taller['numero_correctivo'].'</div>
							<div data-linea="4"><label>Valor total: </label>'.number_format($taller['suma_correctivo'],2).'</div>
							<hr>
							<div data-linea="5"><label># Órdenes lavadas : </label>'.$taller['numero_lavada'].'</div>
							<div data-linea="5"><label>Valor total: </label>'.number_format($taller['suma_lavada'],2).'</div>
							<hr>
							<div data-linea="7"><label># Órdenes pendientes : </label>'.$taller['numero_pendientes'].'</div>
							<div data-linea="7"><label># Órdenes eliminadas : </label>'.$taller['numero_eliminados'].'</div>
						</fieldset>';
				break;
				
			case '12'://Vehículos registrados
				echo '<fieldset>
						<legend>Información vehículos antiguos</legend>
						<div data-linea="1"><label>Placa: </label> '.$vehiculos['placa'].'</div>
						<div data-linea="1"><label>Marca: </label>'.$vehiculos['marca'].'</div>
						<div data-linea="1"><label>Modelo: </label>'.$vehiculos['modelo'].'</div>
						<div><br/></div>
						<div data-linea="2"><label>Año fabricación: </label>'.$vehiculos['anio_fabricacion'].'</div>
						<div data-linea="2"><label>Tipo combustible: </label>'.$vehiculos['combustible'].'</div>
						<div data-linea="2"><label>Kilometraje: </label>'.number_format($vehiculos['kilometraje_actual'],0).'</div>
						<div><br/></div>
						<div data-linea="3"><label>Localización: </label>'.$vehiculos['localizacion'].'</div>
						<div data-linea="3"><label>Avalúo: </label>'.$vehiculos['avaluo'].'</div>
					</fieldset>';
				break;
					
			default:
				echo 'Reporte desconocido';
		}
		 echo '</td>';
	
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
