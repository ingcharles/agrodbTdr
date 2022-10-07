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
	<h1>Reporte Vehículos</h1>
</header>

<?php


	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	
	$reporte= $_POST['valoresFiltrados'];
	
	echo'<table class="soloImpresion">
				<tr>';
	
	for ($i = 0; $i < count ($reporte); $i++) {
		
	$res = $cv->abrirVehiculo($conexion, $reporte[$i]);
	$vehiculo = pg_fetch_assoc($res);
	
	echo ' <td>
					<fieldset>
						<legend>Vehículo placa N° '.$vehiculo['placa'].'</legend>
							<div><label>Marca: </label> '.$vehiculo['marca'].'</div>
							<div><label>Modelo: </label> '.$vehiculo['modelo'].'</div>
							<div><label>Tipo: </label> '.$vehiculo['tipo'].'</div>
							<div><label>País de origen: </label>'.$vehiculo['pais_origen'].'</div>							
							<div><label>Tipo combustible: </label> '.$vehiculo['combustible'].'</div>
							<div><label>Año fabricación: </label> '.$vehiculo['anio_fabricacion'].'</div>
							<div><label>Tipo carroceria: </label> '.$vehiculo['carroceria'].'</div>
							<div><label>Tonelaje: </label> '.$vehiculo['tonelaje'].'</div>
							<div><label>Cilindraje: </label> '.$vehiculo['cilindraje'].'</div>
							<div><label>Número motor: </label> '.$vehiculo['numero_motor'].'</div>
							<div><label>Número chasis: </label> '.$vehiculo['numero_chasis'].'</div>
							<div><label>Área: </label> '.$vehiculo['area'].'</div>
							<div><label>Conductor: </label> '.$vehiculo['apellido'].' '.$vehiculo['nombre'].' </div>
							<div><label>Fecha de compra: </label> '.date('j/n/Y',strtotime($vehiculo['fecha_compra'])).'</div>
							<div><label>Número factura: </label> '.($vehiculo['factura_compra']!=''?$vehiculo['factura_compra']:'No disponible').'</div>
							<div><label>Valor de compra: </label> '.($vehiculo['valor_compra']!=''?$vehiculo['valor_compra']:'No disponible').'</div>
							<div><label>Kilometraje actual: </label> '.$vehiculo['kilometraje_inicial'].'</div>
			
		  			</fieldset>			
		   </td>';
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
					</tr>
				</table>';
	

	
	?>
</body>
<script type="text/javascript">
</script>
</html>
