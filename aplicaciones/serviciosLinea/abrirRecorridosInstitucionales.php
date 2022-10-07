<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorServiciosLinea.php';

$conexion = new Conexion ();
$csl = new ControladorServiciosLinea();

$idRutaTransporte=$_POST['id'];
$qDetalleRutaTransporte=$csl->buscarDetalleRutaTransporte($conexion, $idRutaTransporte);
$arrayRecorrido=array();
while($fila=pg_fetch_assoc($qDetalleRutaTransporte)){
	$arrayRecorrido[]=array(lat=>floatval($fila['latitud']) ,lng=>floatval($fila['longitud']),dir=>$fila['referencia_parada'],hor=>$fila['hora_aproximada'],recorrido=>$fila['recorrido']);
}
$qRutaTransporte=$csl->buscarGARutasTransporte($conexion, '', '', '',null,1,$idRutaTransporte);
$rutaTransporte=pg_fetch_assoc($qRutaTransporte);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>
<header>
	<h1>Rutas de Transporte Institucional</h1>
</header>
	<fieldset>
		<legend>Información de Ruta</legend>
		<div data-linea="1">
			<label>Nombre ruta:</label>
			<?php echo $rutaTransporte['nombre_ruta'];?>
		</div>
		<div data-linea="2">
			<label>Provincia:</label>
			<?php echo $rutaTransporte['provincia'];?>
		</div>
		<div data-linea="2">
			<label>Cantón:</label>
			<?php echo $rutaTransporte['canton'];?>
		</div>
		<div data-linea="3">
			<label>Oficina:</label>
			<?php echo $rutaTransporte['oficina'];?>
		</div>
		<div data-linea="3">
			<label>Sector:</label>
			<?php echo $rutaTransporte['sector'];?>
		</div>
		<div data-linea="4">
			<label>Conductor:</label>
			<?php echo $rutaTransporte['conductor'];?>
		</div>
		<div data-linea="4">
			<label>Teléfono:</label>
			<?php echo $rutaTransporte['telefono'];?>
		</div>
		<div data-linea="5">
			<label>Administrador Grupo:</label>
			<?php echo $rutaTransporte['administrador_grupo'];?>
		</div>
		<div data-linea="5">
			<label>Teléfono:</label>
			<?php echo $rutaTransporte['telefono_administrador'];?>
		</div>
		<div data-linea="6">
			<label>Capacidad:</label>
			<?php echo $rutaTransporte['capacidad_vehiculo'];?>
		</div>
		<div data-linea="6">
			<label>Número Pasajeros:</label>
			<?php echo $rutaTransporte['numero_pasajeros'];?>
		</div>
		<div data-linea="7">
			<label>Placa Vehículo:</label>
			<?php echo $rutaTransporte['placa_vehiculo'];?>
		</div>
		<div data-linea="8">
			<label>Descripción Vehículo:</label>
			<?php echo $rutaTransporte['descripcion_vehiculo'];?>
		</div>
	</fieldset>				
  
	<fieldset>
		<legend>Mapa</legend>
			<div id="map" data-linea="1"></div>
	</fieldset>
			
	<fieldset>
		<legend>Rutas del Recorrido</legend>	
			<div data-linea="1">
			
			<?php 
						$qIdRutaTransporte=$csl->buscarDetalleRutaTransporte($conexion, $idRutaTransporte);
						$contadorMañana=1;
						$contadorTarde=1;
						while($fila=pg_fetch_assoc($qIdRutaTransporte)){
							if($fila['recorrido']=='Mañana'){
								$lineaMañana.="<tr>
								<td>".$contadorMañana." </td>
								<td>".$fila['referencia_parada']."</td>
								<td>".$fila['recorrido']."</td>
								<td>".$fila['hora_aproximada']."</td>
								</tr>";
								$contadorMañana++;
							}else{
								$lineaTarde.= "<tr>
								<td>".$contadorTarde." </td>
								<td>".$fila['referencia_parada']."</td>
								<td>".$fila['recorrido']."</td>
								<td>".$fila['hora_aproximada']."</td>
								</tr>";
								$contadorTarde++;
								
						}
							
						}
					?>
			
				<table id="tablaMañana" style="width: 100%;" class="tablaMatriz">
					<thead>
						<tr>
							<th>#</th>
							<th>Dirección</th>
							<th>Recorrido</th>
							<th>Hora Aproximada</th>
						</tr>
					</thead>
					<tbody>
					<?php echo $lineaMañana;?>
				</tbody>
			</table>
			
			<table id="tablaTarde" style="width: 100%;" class="tablaMatriz">
					<thead>
						<tr>
							<th>#</th>
							<th>Dirección</th>
							<th>Recorrido</th>
							<th>Hora Aproximada</th>
						</tr>
					</thead>
					<tbody>
					<?php echo $lineaTarde;?>
					</tbody>
			</table>
		</div>
	</fieldset>
	
<script type="text/javascript">
	var rutasRecorrido= <?php echo json_encode($arrayRecorrido); ?>;
   
    $(document).ready(function(){
    	distribuirLineas();
    	cargarMapa();
    	$("#tablaMañana tbody tr").length == 0 ? $("#tablaMañana").remove():"";
    	$("#tablaTarde tbody tr").length == 0 ? $("#tablaTarde").remove():"";
    });

    function cargarMapa() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 14,
          center: rutasRecorrido[0]
   	});
     
   var manana = new Array();
   var tarde = new Array();
   var punto=0;
   var puntoManana=0;
   var puntoTarde=0;
    for(var i=0; i<rutasRecorrido.length; i++){
		latlon = new google.maps.LatLng(rutasRecorrido[i].lat, rutasRecorrido[i].lng);
		if(rutasRecorrido[i].recorrido=='Mañana'){
			manana.push(rutasRecorrido[i]);
      		puntoManana=puntoManana+1;
      		punto=puntoManana;
        }else{
        	tarde.push(rutasRecorrido[i]);
      		puntoTarde=puntoTarde+1;
      		punto=puntoTarde;
        }
        marker = new google.maps.Marker({
        	map: map,
        	position: latlon,
            hora: '<b>Punto '+punto+'</b><br><b>Hora: </b>'+rutasRecorrido[i].hor+'<br><b>Direccion: </b>'+rutasRecorrido[i].dir,
            title:rutasRecorrido[i].dir,
            label:{
            	text: punto.toString(),
                color: 'white',
                fontSize: "16px",
                fontWeight: "bold"
            }
		});

        marker.info = new google.maps.InfoWindow();

        google.maps.event.addListener(marker, 'click', function() {
        	marker.info.setContent(this.hora);
            marker.info.open(map, this);
		});
        marker.setMap(map);
	}


   	var flightPath = new google.maps.Polyline({
        path: manana,
        geodesic: true,
        strokeColor: '#8416FA',
        strokeOpacity: 0.5,
        strokeWeight: 5
    });

  	flightPath.setMap(map);

  	var flightPathh = new google.maps.Polyline({
        path: tarde,
        geodesic: true,
        strokeColor: '#06c1d6',
        strokeOpacity: 0.5,
        strokeWeight: 5
    });

  	flightPathh.setMap(map);
    }
</script>
</body>
</html>