<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorVehiculos.php';

$identificadorUsuarioRegistro = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1>Orden Generada</h1>
</header>

<?php

	$conexion = new Conexion();
	$cv = new ControladorVehiculos();
	
	$numeroOrden=$_POST['id'];
	
	if( strpos( $numeroOrden, 'COM' ) !== false ) {
	    $tipoOrden="Combustible";
	}else if( strpos( $numeroOrden, 'MAN' ) !== false ) {
	    $tipoOrden="Mantenimiento";
	}else if( strpos( $numeroOrden, 'MOV' ) !== false ) {
	    $tipoOrden="Movilizacion";
	}
	
	
	switch ($tipoOrden){
		case 'Combustible':{
			
			$combustible = pg_fetch_assoc($cv->abrirCombustible($conexion, $numeroOrden));
			
			echo'<table>
					<tr>
						<fieldset>
							<legend>'.$tipoOrden.' N° '.$combustible['id_combustible'].'</legend>
								<div data-linea="1"><label>Placa: </label> '.$combustible['placa'].'</div>
								<div data-linea="1"><label>Kilometraje: </label>'.$combustible['kilometraje'].' Kms.</div>
								<div data-linea="2"><label>Fecha de Solicitud: </label>'.$combustible['fecha_solicitud'].'</div>
								<div data-linea="2"><label>Fecha de Liquidación: </label>'.$combustible['fecha_liquidacion'].'</div>
								<div data-linea="3"><label>Gasolinera: </label>'.$combustible['nombregasolinera'].'</div>
								<div data-linea="3"><label>Tipo combustible: </label>'.$combustible['tipo_combustible'].'</div>
								<div data-linea="4"><label>Monto solicitado: </label>$'.$combustible['monto_solicitado'].'</div>
								<div data-linea="4"><label>Galones solicitados: </label>'.$combustible['galones_solicitados'].'</div>
								<div data-linea="5"><label>Valor cancelado: </label>$'.$combustible['valor_liquidacion'].'</div>
								<div data-linea="5"><label>Galones: </label>'.$combustible['cantidad_galones'].'</div>
								<div data-linea="6"><label>Conductor: </label>'.$combustible['apellido'].' '.$combustible['nombreconductor'].'</div>
								<div data-linea="7"><label>Observaciones: </label>'.( $combustible['observacion']!='' ?$combustible['observacion']:'Sin novedad.').'</div>
								<div data-linea="8"><label>Orden Generada: </label> <a href='.$combustible['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">PDF</a></div>
						</fieldset>
					</tr>
				</table>';
			
			break;
		}
		
		case 'Mantenimiento':{
				
			$mantenimiento = pg_fetch_assoc($cv->abrirMantenimientoDetalle($conexion, $numeroOrden));
			
			echo'<table>
						<tr>
							<fieldset>
								<legend>'.$tipoOrden.' N° '.$mantenimiento['id_mantenimiento'].'</legend>
									<div data-linea="1"><label>Placa: </label> '.$mantenimiento['placa'].'</div>
									<div data-linea="1"><label>Fecha de Solicitud: </label>'.$mantenimiento['fecha_solicitud'].'</div>
									<div data-linea="2"><label>Kilometraje inicial: </label>'.$mantenimiento['kilometraje'].' Kms.</div>
									<div data-linea="2"><label>Kilometraje final: </label>'.$mantenimiento['kilometraje_final'].' Kms.</div>
									<div data-linea="3"><label>Fecha de Liquidación: </label>'.$mantenimiento['fecha_liquidacion'].'</div>
									<div data-linea="3"><label>Taller: </label>'.$mantenimiento['nombre_taller'].'</div>
									<div data-linea="4"><label>Tipo: </label>'.$mantenimiento['tipo_mantenimiento'].'</div>
									<div data-linea="4"><label>Monto: </label>$'.$mantenimiento['valor_liquidacion'].'</div>
									<div data-linea="5"><label>Motivo mantenimiento: </label>'.$mantenimiento['motivo'].'</div>
									<div data-linea="6"><label>Orden de Trabajo: </label>'.$mantenimiento['orden_trabajo'].'</div>
									<div data-linea="7"><label>Número de Factura: </label>'.$mantenimiento['numero_factura'].'</div>
									<div data-linea="8"><label>Conductor: </label>'.$mantenimiento['apellido'].' '.$mantenimiento['nombreconductor'].'</div>
									<div data-linea="9"><label>Observaciones: </label>'.( $mantenimiento['observacion']!='' ?$mantenimiento['observacion']:'Sin novedad.').'</div>
									<div data-linea="10"><label>Orden Generada: </label> <a href='.$mantenimiento['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">PDF</a></div>
							</fieldset>
						</tr>
					</table>';
			
			break;
		}
		
		case 'Movilizacion':{
				
			$movilizacion = pg_fetch_assoc($cv->abrirMovilizacionDetalle($conexion, $numeroOrden));
			$ruta = $cv->abrirMovilizacionRutas($conexion, $numeroOrden);
			
			echo'<table>
					<tr>
						<fieldset>
							<legend>'.$tipoOrden.' N° '.$movilizacion['id_combustible'].'</legend>
								<div data-linea="1"><label>Placa: </label> '.$movilizacion['placa'].'</div>
								<div data-linea="1"><label>Fecha de Solicitud: </label>'.$movilizacion['fecha_solicitud'].'</div>
								<div data-linea="2"><label>Kilometraje inicial: </label>'.$movilizacion['kilometraje_inicial'].' Kms.</div>
								<div data-linea="2"><label>Kilometraje final: </label>'.$movilizacion['kilometraje_final'].' Kms.</div>
								<div data-linea="3"><label>Tipo movilización: </label>'.$movilizacion['tipo_movilizacion'].'</div>
								<div data-linea="4"><label>Ruta: </label>';
									while($fila = pg_fetch_assoc($ruta)){
										echo $fila['localizacion'] . ', ';
									}								
						  echo '</div>
								<div data-linea="5"><label>Motivo: </label>'.$movilizacion['descripcion'].'</div>
								<div data-linea="6"><label>Conductor: </label>'.$movilizacion['apellido'].' '.$movilizacion['nombreconductor'].'</div>
								<div data-linea="7"><label>Observaciones </label></div>
								<div data-linea="8"><label> - Movilización: </label>'.( $movilizacion['observacion_movilizacion']!='' ?$movilizacion['observacion']:'Sin novedad.').'</div>
								<div data-linea="9"><label> - Ruta: </label>'.( $movilizacion['observacion_ruta']!='' ?$movilizacion['observacion']:'Sin novedad.').'</div>
								<div data-linea="10"><label> - Ocupante: </label>'.( $movilizacion['observacion_ocupante']!='' ?$movilizacion['observacion']:'Sin novedad.').'</div>
								<div data-linea="11"><label>Orden Generada: </label> <a href='.$movilizacion['ruta_archivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">PDF</a></div>
						</fieldset>
					</tr>
				</table>';
			
			break;
		}
		
		default:{
			echo 'No se encuentra a opción seleccionada';
			
			break;
		}
	}

?>
	
</body>

	<script type="text/javascript">
		$(document).ready(function(){
			distribuirLineas();
		});
	</script>
</html>