<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorPAPP.php';

$conexion = new Conexion();
$cp = new ControladorPAPP();

$registrosPAPP =  explode(",",$_POST['elementos']);

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

	for ($i = 0; $i < count ($registrosPAPP); $i++) {
		
		if ($registrosPAPP[$i] != ''){
		
			$qDatosRegistroPapp = $cp->obtenerDatosPOA($conexion, $registrosPAPP[$i]);
			$datosRegistroPapp = pg_fetch_assoc($qDatosRegistroPapp);
			
			/*if($datosRegistroPapp['estado'] == 1){*/
		
			echo'<fieldset>
							<legend>Registro N° </label>' .$registrosPAPP[$i].'</legend>
							<div data-linea="1"><label>Objetivo Estratégico: </label>' .$datosRegistroPapp['objetivo'].'</div>
							<div data-linea="2"><label>Procesos/Proyectos: </label>' .$datosRegistroPapp['subproceso'].'</div>
							<!--div data-linea="3"><label>Objetivo operativo: </label>' .$datosRegistroPapp['componente'].'</div-->
							<div data-linea="4"><label>Proyectos y Actividades: </label>' .$datosRegistroPapp['actividad'].'</div>
							<!--div data-linea="5"><label>Indicadores: </label>' .$datosRegistroPapp['indicador'].'</div>
							<div data-linea="6"><label>Trimestre I: </label>' .$datosRegistroPapp['meta1'].'</div>
							<div data-linea="6"><label>Trimestre II: </label>' .$datosRegistroPapp['meta2'].'</div>
							<div data-linea="7"><label>Trimestre III: </label>' .$datosRegistroPapp['meta3'].'</div>
							<div data-linea="7"><label>Trimestre IV: </label>' .$datosRegistroPapp['meta4'].'</div-->		
				</fieldset>';
			/*}else{
				echo 'No se puede eliminar el PAPP debido a que ya fue enviado a revisión.';
			}*/
		}
	}
		
	?>
	
	<form id="datosLiquidacionPapp" data-rutaAplicacion="poa" data-opcion="eliminarPapp" data-accionEnExito="ACTUALIZAR" >

			<?php 
				for ($i = 0; $i < count ($registrosPAPP); $i++) {
					echo'<input type="hidden" name="registrosPapp[]" value="'.$registrosPAPP[$i].'"/>';
				}
			?>  
				
	<button id="detalle" type="submit" class="guardar" >Eliminar Proforma</button>
	
	</form>
	
	
 

</body>

<script type="text/javascript">
var array_registroPapp = <?php echo json_encode($registrosPAPP); ?>;

$("#datosLiquidacionPapp").submit(function(event){
  	event.preventDefault();
	ejecutarJson($(this));

});

$(document).ready(function(){

	distribuirLineas();

	if(array_registroPapp == ''){
		$("#detalleItem").html('<div class="mensajeInicial">Seleccione una o varias Papp  y a continuación presione el boton eliminar.</div>');
	}

});

</script>

</html>
