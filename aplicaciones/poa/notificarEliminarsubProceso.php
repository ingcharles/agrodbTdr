<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$conexion = new Conexion();
$cp = new ControladorPAPP();

$registrosSubProceso =  explode(",",$_POST['elementos']);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

</head>
<body>

<header>
	<h1>Confirmar eliminación</h1>
</header>

<div id="estado"></div>

	<p>Los <b>elementos</b> a ser eliminados son: </p>
	
	<?php

	for ($i = 0; $i < count ($registrosSubProceso); $i++) {
		
		if ($registrosSubProceso[$i] != ''){
		
			$qsubProceso = $cp->abrirSubproceso($conexion, $registrosSubProceso[$i]);
			$subProceso = pg_fetch_assoc($qsubProceso);
			
			/*if($datosRegistroPapp['estado'] == 1){*/
		
			echo'<fieldset>
							<legend>Registro N° </label>' .$registrosSubProceso[$i].'</legend>
							<div data-linea="1"><label>Descripción subproceso: </label>' .$subProceso['descripcion'].'</div>
							<hr>';
			
			$qactividades = $cp->seleccionarActividades($conexion, $subProceso['id_subproceso']);
			
			if(pg_num_rows($qactividades) != 0){
				
				
								
				while ($actividades = pg_fetch_assoc($qactividades)){
					
					echo'<label>Actividades</label>';
					echo '<div><label>-   </label>' .$actividades['descripcion'].'</div>';
					
					$qindicadores = $cp->listarIndicadorXActividad($conexion, $actividades['id_actividad']); 
					
					if(pg_num_rows($qactividades) != 0){
						
						
						echo'<label>Indicadores</label>';
						while ($indicadores = pg_fetch_assoc($qindicadores)){
							
							echo '<div><label>-   </label>' .$indicadores['descripcion'].'</div>';
						}
						echo '<hr>';
					}
				}
				
				
				
			}
			
									
			echo'</fieldset>';
			/*}else{
				echo 'No se puede eliminar el PAPP debido a que ya fue enviado a revisión.';
			}*/
		}
	}
		
	?>
	
	<form id="datosLiquidacionSubProceso" data-rutaAplicacion="poa" data-opcion="eliminarSubProceso" data-accionEnExito="ACTUALIZAR" >

			<?php 
				for ($i = 0; $i < count ($registrosSubProceso); $i++) {
					echo'<input type="hidden" name="registrosSubProceso[]" value="'.$registrosSubProceso[$i].'"/>';
				}
			?>  
				
	<button id="detalle" type="submit" class="guardar" >Eliminar subproceso</button>
	
	</form>
	
	
 

</body>

<script type="text/javascript">
var array_registroSubProceso = <?php echo json_encode($registrosSubProceso); ?>;

$("#datosLiquidacionSubProceso").submit(function(event){
  	event.preventDefault();
	ejecutarJson($(this));

});

$(document).ready(function(){

	distribuirLineas();

	if(array_registroSubProceso == ''){
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una o varias subprocesos  y a continuación presione el boton eliminar.</div>');
	}

});

</script>

</html>
