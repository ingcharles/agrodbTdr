<?php
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorNotificacionEnfermedades.php';
require_once '../../clases/ControladorCatalogos.php';

$conexion = new Conexion();
$cr = new ControladorNotificacionEnfermedades();
$cm = new ControladorNotificacionEnfermedades();

$qReporteEnfermedades = $cr->buscarReporteEnfermedadesDetalle($conexion, $_POST['id']);
$qReporte= $cm->buscarReporteEnfermedades($conexion, $_POST['id']);
$datos = pg_fetch_assoc($qReporte);

//?>

<header>
	<img src='aplicaciones/general/img/encabezado.png'>
	<h1 style="font-size: 24px;">Reporte de Enfermedades Zoonósicas</h1>
</header>
<form id='abrirNotificacionEnfermedades' data-rutaAplicacion='notificacionEnfermedades' data-opcion='abrirNotificacionEnfermedades' data-destino="detalleItem" data-accionEnExito="ACTUALIZAR">
<p>
	<!--  <button id="modificar" type="button" class="editar">Modificar</button>
	<button id="actualizar" type="submit" class="guardar" disabled="disabled">Actualizar</button>-->
</p>

<div id="visualizar">	
	<fieldset>
		<legend>Información del Producto</legend>
		<input type="hidden" name="idRaza" value="" />
		<input type="hidden" id="estados" value="" disabled="disabled"/>	
			<div data-linea="1">
				<label>Animal:</label> <?php echo $datos['nombre_producto'];?> <br/>
			</div>
			<div data-linea="1">
				<label>Nombre:</label> <?php echo $datos['nombre_animal'];?> <br/>
			</div>
			<div data-linea="2">
				<label>Identificador animal:</label> <?php echo $datos['identificador_animal'];?> <br/>
			</div>
			<div data-linea="2">
				<label>Identificador dueño:</label> <?php echo $datos['identificador_duenio'];?>	
			</div>
	</fieldset>

  <fieldset>
 	<legend>Resultado del Diagnóstico</legend>
		<table id="tablaDescripcionEnfermedad">
			<thead>
				<tr>
					<th>N°</th>
					<th>Diagnóstico</th>
					<th>Agente causal</th>
					<th>Fecha diagnóstico</th>	
				</tr>
			</thead>
			<?php 
			while($fila = pg_fetch_assoc($qReporteEnfermedades)){
		       	echo  '<tr>
						<td>'.++$contador.'</td>	
						<td>'.$fila['nombre_tipo_enfermedad'].'</td>
						<td>'.$fila['nombre_enfermedad'].'</td>
						<td>'.date('d/m/Y', strtotime($fila['fecha_reporte'])).'</td>
					</tr>';
			   }
			 ?>
		</table>
	</fieldset>	
	<fieldset>
 	<legend>Descripción del Diagnóstico</legend>
 	<div data-linea="3">
				<label>Descripción:</label> <?php echo $datos['descripcion_enfermedad_zoonosica'];?>	
			</div>
 	</fieldset>
	<?php 
			echo '<label>Descargar: </label><a download="'.Diagnostico.''.$datos['id_enfermedad_zoonosica'].'.pdf" href="'.$datos['ruta_adjunto'].'" target= "_blank">'.Diagnostico.'.pdf</a>
			';
	?>

			

</div>

</form>

<script type="text/javascript">


$(document).ready(function(){
		distribuirLineas();

});

</script>