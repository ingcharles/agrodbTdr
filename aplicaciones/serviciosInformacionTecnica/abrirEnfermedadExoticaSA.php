<?php 
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorServiciosInformacionTecnica.php';
	
	$conexion = new Conexion();
	$csit = new ControladorServiciosInformacionTecnica();
	$idEnfermedadExotica=$_POST['id'];
	$qEnfermedad=$csit->abrirFiltroEnfermedadExotica($conexion, $idEnfermedadExotica);
	$enfermedad= pg_fetch_assoc($qEnfermedad);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Enfermedades Exóticas</h1>
	</header>
	<div id="estado"></div>
	<fieldset>
		<legend>Enfermedad Reportada</legend>
		<div data-linea="1">
			<label>Enfermedad:</label>
			<?php echo $enfermedad['nombre_enfermedad'];?>
		</div>
		
		<div data-linea="2">
			<label>Fecha Inicio:</label>
			<?php echo $enfermedad['inicio_vigencia'];?>
		</div>
		<div data-linea="2">
			<label>Fecha Fin:</label>
			<?php echo $enfermedad['fin_vigencia'];?>
		</div>
		<div data-linea="3">
			<label>Zona Origen:</label>
			<?php echo $enfermedad['zonas'];?>
		</div>
		<div data-linea="4">
			<label>País:</label>
			<?php echo $enfermedad['paises'];?>
		</div>
		<div data-linea="5">
			<label>Observaciones:</label>
			<?php echo $enfermedad['observacion'];?>
		</div>
	</fieldset>
	<fieldset>
		<legend>Productos Aplicables</legend>
		<?php if($enfermedad['productos']!=''){?>
		<div data-linea="1">
		<?php 
			$qEnfermedadExoticaTipoProducto=$csit->abrirFiltroEnfermedadExoticaTipoProducto($conexion, $idEnfermedadExotica);
			while($filaTipoProducto=pg_fetch_assoc($qEnfermedadExoticaTipoProducto)){
				echo '<label>Tipo de Producto: </label>'.$filaTipoProducto['nombre'].'<br>';
				$qEnfermedadExoticaSubTipoProducto=$csit->abrirFiltroEnfermedadExoticaSubTipoProducto($conexion, $idEnfermedadExotica, $filaTipoProducto['id_tipo_producto']);
				while($filaSubTipoProducto=pg_fetch_assoc($qEnfermedadExoticaSubTipoProducto)){
					echo '<br><label>Subtipo de Producto: </label>'.$filaSubTipoProducto['nombre'].'<br>';
					$qEnfermedadExoticaProducto=$csit->abrirFiltroEnfermedadExoticaProducto($conexion, $idEnfermedadExotica, $filaSubTipoProducto['id_subtipo_producto']);
					echo '<label>Productos: </label><br>';
				
					while($filaProducto=pg_fetch_assoc($qEnfermedadExoticaProducto)){
						if($filaProducto['partida_arancelaria']==''){
							$partida='S/N';
						}else{
							$partida=$filaProducto['partida_arancelaria'];
						}
						echo $partida.' - '.$filaProducto['nombre'].'<br>';
					}
					echo '<br>';
				}
				echo '<hr/>';
			}
		?>
		</div>
		<?php 
			}else{
				echo '<div data-linea="1">
					<label>No existe datos de productos registrados</label>
		    	   	</div>';
			}
		?>
	</fieldset>
	<fieldset>
		<legend>Requerimiento de Revisión/Ingreso</legend>
		<?php
			$qRequerimiento=$csit->listaEnfermedadExoticaRequerimiento($conexion, $idEnfermedadExotica);
			$contador=1;
			while ($fila = pg_fetch_assoc($qRequerimiento)){
				echo  '<div data-linea='.$contador.'>
						'.$fila['nombre_requerimiento'].' - '.$fila['nombre_elemento_revision'] .'
						</div>';
				$contador++;
			}			
		?>
	</fieldset> 
</body>
<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();
	});
</script>