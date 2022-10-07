<?php
	session_start();
	require_once '../../clases/Conexion.php';
	require_once '../../clases/ControladorSeguridadOcupacional.php';
	
	$conexion = new Conexion();
	$so = new ControladorSeguridadOcupacional();
	
	$qManejoMaterialesPeligrosos= $so->abrirManejoMaterialesPeligrosos($conexion, $_POST['id']);
	$filaManejoMaterialPeligroso = pg_fetch_assoc($qManejoMaterialesPeligrosos);
	
	$qClasificacionRiesgoXMaterialPeligroso= $so->abrirClasificacionRiesgoXMaterialPeligroso($conexion, $filaManejoMaterialPeligroso['id_material_peligroso']);

?>
<!DOCTYPE html>
<html>
<head>
<style type="text/css">
.riesgo {
    float:left;
    padding-right:2%;   
}

.hoja {
    padding-left:16%;
}

#img {
    cursor: pointer;
}
</style>
<meta charset="utf-8">
</head>
<body>
	<header>
		<h1>Material Peligroso</h1>
	</header>
	<div id="estado"></div>
	<form id="abrirManejoMaterialPeligroso" data-rutaAplicacion="seguridadOcupacional" ><!--  data-opcion="actualizarRolEmpleado" data-accionEnExito="ACTUALIZAR">-->
		<fieldset>
			<legend>Datos Generales</legend>
			
			
								
			<div data-linea="1">
				<h1><?php echo $filaManejoMaterialPeligroso['nombre_material_peligroso'];?></h1>
			</div>		
				
			<div data-linea="2">
				<?php echo $filaManejoMaterialPeligroso['descripcion_material_peligroso'];?>
			</div>			
			<p>
			
			<div data-linea="3">
				<label>Coordinación Laboratorio:</label>
				<?php echo $filaManejoMaterialPeligroso['nombre_coordinacion'];?>
			</div>	
			
			<div data-linea="4">
				<label>Laboratorio: </label>
				<?php echo $filaManejoMaterialPeligroso['nombre_laboratorio'];?>
			</div>
			
			<div data-linea="4">
				<label>Número CAS:</label>
				<?php echo $filaManejoMaterialPeligroso['numero_cas_material_peligroso'];?>
			</div>	
				
			<div data-linea="5">
				<label>Número UN: </label>
				<?php echo $filaManejoMaterialPeligroso['numero_un_material_peligroso'];?>	
			</div>
		
			<div data-linea="5">
				<label>Número Guía</label>
				<?php echo $filaManejoMaterialPeligroso['numero_guia_material_peligroso'];?>
			</div>
			<p>
			
			<div data-linea="6" >
				<label>Pictogramas de Riesgo: </label>
			</div>
		
			<div data-linea="7" >
				<?php 
				while($filaClasificacionRiesgo=pg_fetch_assoc($qClasificacionRiesgoXMaterialPeligroso)){
					echo "<div class='riesgo'>
					<img src='".$filaClasificacionRiesgo['ruta_img_clasificacion_riesgo_material_peligroso']."' style='no-repeat; width: 92px;'/>
					</div>";
				}
				?>
			</div>
		</fieldset>	

		<fieldset>
			<legend>Guías de Emergencia</legend>
			
			<div data-linea="1" class='hoja'>
				<a> 
				<img class='img msds' src='aplicaciones/seguridadOcupacional/img/msds.jpg' style='no-repeat; width: 70px;'/>
				</a>
			</div>
	
			<div data-linea="1" class='hoja'>
				<a> 
				<img class='img guia' src='aplicaciones/seguridadOcupacional/img/guia.jpg' style='no-repeat; width: 70px;'/>
				</a>
			</div>
		</fieldset>
	
	<fieldset>
		<legend>Datos Emergencia</legend>
		<div data-linea="1">
			<label>ECU:</label>
			911
		</div>
				
		<div data-linea="2">
			<label>Planta Central: </label>
			(02)2333777
		</div>
	</fieldset>

	<div id="pdf">
		<iframe style='width:550px; height:750px;' >
	    </iframe>
	</div>
   
</form>
</body>

<script type="text/javascript">
var msds= <?php echo json_encode($filaManejoMaterialPeligroso['ruta_msds_material_peligroso']); ?>;
var guia= <?php echo json_encode($filaManejoMaterialPeligroso['ruta_guia_material_peligroso']); ?>;

$(document).ready(function(){
	distribuirLineas();
	$("#pdf").hide();	
});

$(".img").click(function(){
	$("#pdf").show();
	if($(this).hasClass("msds"))
		$("#pdf iframe").attr("src",msds);
	else
		$("#pdf iframe").attr("src",guia);	
});
</script>
</html>