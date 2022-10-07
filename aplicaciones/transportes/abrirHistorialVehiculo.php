<?php
session_start();
$_SESSION['placa'] = $_POST['id'];
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';


$conexion = new Conexion();
$cv = new ControladorVehiculos();


$res = $cv->historialVehiculo($conexion,$_POST['id']);


?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>


<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Datos del vehículo</h1>
</header>

<form id="historialVehiculo" >
		
	<table style="margin-left: 20px;">
		<tr>
		
			<td>
				<input type="checkbox" id="movilizacion" name="movilizacion" checked="checked">
			</td>
			<td>
				<label>Movilización</label>
			</td>
			
			<td>
				<input type="checkbox" id="mantenimiento" name="mantenimiento" checked="checked">
			</td>
			<td>
				<label>Mantenimiento</label>
			</td>
		
			<td>
				<input type="checkbox" id="combustible" name="combustible" checked="checked">
			</td>
			<td>
				<label>Combustibles</label>
			</td>
		
			<td>
				<input type="checkbox" id="siniestro" name="siniestro" checked="checked">
			</td>
			<td>
				<label>Siniestros</label>
			</td>
		
		</tr>
	</table>
	
	
	<table style="margin-left: 45px;">
		<thead>
			<tr>
				<th>Tipo</th>
				<th>Número</th>
				<th>Valor total</th>
				<th>Otros</th>
			</tr>
		</thead>
	
		<tr  class="movilizacion">
			<td>
				<label>Movilización</label>
			</td>
			<td>
				<label id="cantidadMovilizacion"></label>
			</td>
			<td>
				<label>0</label>
			</td>
			<td>
				<label>Ninguno</label>
			</td>
		</tr>
		<tr class="mantenimiento">
			<td>
				<label>Mantenimiento</label>
			</td>
			<td>
				<label id="cantidadMantenimiento"></label>
			</td>
			<td>
				<label id="totalMantenimiento">0</label>
			</td>
			<td>
				<label>Ninguno.</label>
			</td>
		</tr>
		<tr class="combustible">
			<td>
				<label>Combustibles</label>
			</td>
			<td>
				<label id="cantidadCombustible"></label>
			</td>
			<td>
				<label id="totalCombustible">0</label>
			</td>
			<td>
				<div id="kilometrosRecorridos"></div>
				<div id="redimientoKilometros"></div>
			</td>
		</tr>
		<tr class="siniestro">
			<td>
				<label>Siniestros</label>
			</td>
			<td>
				<label id="cantidadSiniestro"></label>
			</td>
			<td>
				<label>0</label>
			</td>
			<td>
				<label>Ninguno.</label>
			</td>
		</tr>
	</table>
	
	<table class="soloImpresion">
	<tr><td>

	<fieldset >
		<legend>Información</legend>
		
			<ol>
		
			<table id="tablaHistorial">
				<thead>
					<tr>
						<th></th>
						<th>Tipo</th>
						<th>Fecha</th>
						<th>Km</th>
						<th>Valor</th>
						<th>Concepto</th>
						<th>Detalle</th>
					</tr>
				</thead>
				<?php
			
					$contador = 0;
					while($vehiculo = pg_fetch_assoc($res)){
			
					switch ($vehiculo['tipo']){
						
						case 'Combustible': 
			
							echo '<tr class="combustible">
								<td><li></li></td>
								<td class = "icono"></td>
								<td>'.date('j/n/Y',strtotime($vehiculo['fecha'])).'</td>
								<td class="kilometraje">'.$vehiculo['kilometraje_uno'].'</td>
								<td class= "valorCombustible"> ' . $vehiculo['numero'] . '</td>
								<td><label>Gasolinera: </label>' . $vehiculo['concepto'] . '<br/>
									<label>Combustible: </label>' . $vehiculo['descripcion'] . '<br/>
									<label>Galones: </label><div class="galones">' . $vehiculo['kilometraje_dos'] . '</div><br/>
								</td>
								<td><label>Conductor: </label>' . $vehiculo['conductor'] . '<br/>
									<label>Fecha liquidación: </label>' . $vehiculo['detalle'] . '<br/>
								</td>
								<td></td>
							</tr>';
							break;
							
						case 'Movilización': 
			
							echo '<tr class="movilizacion">
								<td><li></li></td>
								<td class = "icono"></td>
								<td>'.date('j/n/Y',strtotime($vehiculo['fecha'])).'</td>
								<td> <b>Inicial:</b>'.$vehiculo['kilometraje_dos'].' <br/> <b>Final:</b>'.$vehiculo['kilometraje_uno'].' </td>
								<td>0.00</td>
								<td><label>Motivo: </label>' . $vehiculo['concepto'] . '<br/>
									<label>Destino: </label>' . $vehiculo['descripcion'] . '<br/>
									<label>Ocupantes: </label>' . $vehiculo['modelo'] . '<br/>
								</td>
								<td><label>Tipo: </label>' . $vehiculo['detalle'] . '<br/>
									<label>Conductor: </label>' . $vehiculo['conductor'] . '<br/>
								</td>
								<td></td>
							</tr>';
							break;
							
						case 'Mantenimiento': 
			
							echo '<tr class="mantenimiento">
								<td><li></li></td>
								<td class = "icono"></td>
								<td>'.date('j/n/Y',strtotime($vehiculo['fecha'])).'</td>
								<td>'.$vehiculo['kilometraje_uno'].'</td>
								<td class= "valorMantenimiento">' . $vehiculo['numero'] . '</td>
								<td><label>Motivo: </label>' . $vehiculo['concepto'] . '<br/>
									<label>Taller: </label>' . $vehiculo['descripcion'] . '<br/>
								</td>
								<td><label># factura: </label>' . $vehiculo['detalle'] . '<br/>
									<label>Conductor: </label>' . $vehiculo['conductor'] . '<br/>
								</td>
								<td></td>
							</tr>';
							break;	
							
							case 'Siniestro':
							
								echo '<tr class="siniestro">
								     <td><li></li></td>
								     <td class = "icono"></td>
									 <td>'.date('j/n/Y',strtotime($vehiculo['fecha'])).'</td>
									<td>0.00</td>
									<td>0</td>
								     <td><label>Motivo: </label>' . $vehiculo['concepto'] . '<br/>
									      <label>Magnitud: </label>' . $vehiculo['descripcion'] . '<br/>
									      <label>Conductor: </label>' . $vehiculo['modelo'] . '<br/>
								     </td>
									 <td><label>Lugar: </label>' . $vehiculo['detalle'] . '<br/>
									 <label>Observacion: </label>' . $vehiculo['conductor'] . '<br/>
								     </td>
									<td></td>
								    </tr>';
								break;
						}
					}
					
				?>
			</table>
		</ol>		
	</fieldset>	
	</td>
</tr>
</table>
</form>

</body>

<script type="text/javascript">

$("#movilizacion").change(function(){
	if ($("#movilizacion").is(':checked')){
		$(".movilizacion").show();
	}else{
		$(".movilizacion").hide();
	}
});

$("#mantenimiento").change(function(){
	if ($("#mantenimiento").is(':checked')){
		$(".mantenimiento").show();
	}else{
		$(".mantenimiento").hide();
	}
});

$("#combustible").change(function(){
	if ($("#combustible").is(':checked')){
		$(".combustible").show();
	}else{
		$(".combustible").hide();
	}
});

$("#siniestro").change(function(){
	if ($("#siniestro").is(':checked')){
		$(".siniestro").show();
	}else{
		$(".siniestro").hide();
	}
});


$(document).ready(function(){

	var totalCombustible = 0;
	var totalMantenimiento = 0;
	var kilometrosRecorridos = 0;
	var totalGalones = 0;
	var rendimientokilometros = 0;
	
	$('#cantidadMovilizacion').html($('#tablaHistorial .movilizacion').length);
	$('#cantidadMantenimiento').html($('#tablaHistorial .mantenimiento').length);
	$('#cantidadCombustible').html($('#tablaHistorial .combustible').length);
	$('#cantidadSiniestro').html($('#tablaHistorial .siniestro').length);

	$('#tablaHistorial .valorCombustible').each(function(){    
		totalCombustible += Number($(this).html());
		totalCombustible = Math.round((totalCombustible)*100)/100;
    });

	$('#tablaHistorial .valorMantenimiento').each(function(){    
		totalMantenimiento += Number($(this).html());
		totalMantenimiento = Math.round((totalMantenimiento)*100)/100;
   });

	 $('#totalCombustible').html(totalCombustible);
	 $('#totalMantenimiento').html(totalMantenimiento);

	kilometrosRecorridos = Number($('.kilometraje').first().html()) - Number($('.kilometraje').last().html());

	if(isNaN(kilometrosRecorridos)){
		kilometrosRecorridos = 0;
	}

	$('#kilometrosRecorridos').html('<b>Kilometros recorridos:  </b>' + kilometrosRecorridos);
	

	$('#tablaHistorial .galones').each(function(){    
		totalGalones += Number($(this).html());
   });
	   
	totalGalones = Math.round((totalGalones)*100)/100;

	rendimientokilometros = Math.round((kilometrosRecorridos/totalGalones)*100)/100;

	if(isNaN(rendimientokilometros)){
		rendimientokilometros = 0;
	}
	
	$('#redimientoKilometros').html('<b>Rendimiento Km/Galón:  </b>' +rendimientokilometros);

});


</script>
</html>
