<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorZoosanitarioExportacion.php';

$conexion = new Conexion();
$ci = new ControladorZoosanitarioExportacion();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();

$qZoosanitario = $ci->abrirZoo($conexion, $_POST['id']);
$zoosanitario = pg_fetch_assoc($qZoosanitario);

$qZoosanitarioProductos = $ci->abrirZooProductos($conexion, $_POST['id']);

$qDocumentos = $ci->abrirExportacionesArchivos($conexion, $_POST['id']);
$qOperador = $cr->buscarOperador($conexion, $qExportacion[0]['identificador']);


?>

<header>
	<h1>Solicitud Exportación Zoosanitario</h1>
</header>

	<div id="estado"></div>
	
	<fieldset id="resultado">
		<legend>Resultado de Inspección</legend>
			<div data-linea="1">
				<label>Resultado: </label> 
				<?php //echo ($qExportacion[0]['estadoExportacion']=='enviado'? '<span class="exito">'.$qExportacion[0]['estadoExportacion'].'</span>':'<span class="alerta">'.$qExportacion[0]['estadoExportacion'].'</span>');?>
			</div>
			
			<div data-linea="2">
				<label>Observaciones: </label> <?php //echo $qExportacion[0]['observacionExportacion']; ?> <br/>
			</div>
	</fieldset>
			
	<?php 
		if($zoosanitario['id_vue'] != ''){
			echo '<fieldset>
				<legend>Información de la Solicitud</legend>
					<div data-linea="1">
						<label>Identificación VUE: </label> '. $zoosanitario['id_vue'] .'
					</div>
			</fieldset>';
		}
	?>
	
	<fieldset>
			<legend>Información del exportador</legend>
			
			<div data-linea="4">
				<label>Nombre: </label> <?php echo $zoosanitario['nombre_importador']; ?> 
			</div>
			
			<div data-linea="6">
				<label>Representante técnico: </label> <?php echo $zoosanitario['nombre_tecnico'] . ' ' . $zoosanitario['apellido_tecnico']; ?> 
			</div>
			
	</fieldset>
	
	<fieldset>
		<legend>Datos generales de exportación</legend>
			<div data-linea="5">
				<label>País destino: </label> <?php echo $zoosanitario['pais_destino']; ?> 
			</div>
			
			<div data-linea="6">
				<label>Dirección: </label> <?php echo $zoosanitario['direccion_importador']; ?> 
			</div>
			
			<div data-linea="7">
				<label>Puerto embarque: </label> <?php echo $zoosanitario['puerto_embarque']; ?> 
			</div>
			
			<div data-linea="7">
				<label>Medio de transporte: </label> <?php echo $zoosanitario['transporte']; ?> 
			</div>
			
			<div data-linea="8">
				<label>Uso producto: </label> <?php echo $zoosanitario['nombre_uso']; ?> 
			</div>
			
			<div data-linea="8">
				<label>Bultos: </label> <?php echo $zoosanitario['numero_bultos'] . ' ' . $zoosanitario['descripcion_bultos']; ?> 
			</div>
	</fieldset>
	
	<fieldset>
			<legend>Información de inspección</legend>
			
			<div data-linea="19">
				<label>Código de sitio: </label> <?php echo $zoosanitario['codigo_sitio']; ?> 
			</div>
			<div data-linea="10">
				<table>
					
						<?php 
							foreach ($qZoosanitarioProductos as $productosZoo){
								$qAreaOperacion = $cr->buscarAreaOperacionXCodigoSitio($conexion, $zoosanitario['identificador_operador'], $zoosanitario['codigo_sitio'], $productosZoo['idProducto']);
								
								for ($i=0;$i<count($qAreaOperacion);$i++){
									echo '<tr><td><label>Área de inspección: </label>' . $qAreaOperacion[$i]['nombreArea'].' - '.$qAreaOperacion[$i]['tipoArea'].'</td></tr>';	
								}
							}
						?>
					
				</table>
			</div>
			
				<?php 
					if($zoosanitario['fecha_inspeccion'] != ''){
						echo '<div data-linea="12">
							<label>Fecha de inspección: </label>' . $zoosanitario['fecha_inspeccion'] . 
						'</div>';
					}
				?>
				
			<div data-linea="13">
				<label>Observación: </label> <?php echo $zoosanitario['observacion']; ?> 
			</div>
	</fieldset>
	
<?php 
	
	//IMPRESION DE DOCUMENTOS
	if(count($qDocumentos)>0){
	
			$i=1;
		
			echo'<div id="documentos" >
					<fieldset>
						<legend>Documentos adjuntos</legend>
							
								<table>
									<tr>
										<td><label>#</label></td>
										<td><label>Nombre</label></td>
										<td><label>Enlace</label></td>
									</tr>';
		
				
			foreach ($qDocumentos as $documento){
					echo '<tr>
						  	<td>'.$i.'</td>
							<td>'.$documento['tipoArchivo'].'</td>
							<td>
								<form id="f_'.$i.'" action="aplicaciones/general/accederDocumentoFTP.php" method="post" enctype="multipart/form-data" target="_blank">
									<input name="rutaArchivo" value="'.$documento['rutaArchivo'].'" type="hidden">
									<input name="nombreArchivo" value="'.$documento['tipoArchivo'].'.pdf" type="hidden">
									<input name="idVue" value="'.$documento['idVue'].'" type="hidden">
									<button type="submit" name="boton">Descargar</button>
								</form>
							</td>
						 </tr>';
				$i++;
			}
				
			echo '</table>
			</fieldset>
			</div>';
		}
	
	
	//DETALLE DE PRODUCTOS
	
	$i=1;
		
	echo'<div id="documentos" >
			<fieldset>
				<legend>Datos del producto</legend>
					<form id="f_'.$i.'" data-rutaAplicacion="../general" data-opcion="abrirPdfFtp" data-destino="documentoAdjunto" data-accionEnExito="ACTUALIZAR">
						<table>
							<tr>
								<td><label>#</label></td>
								<td><label>Nombre Producto</label></td>
								<td><label>Partida arancelaria</label></td>';
	
					foreach ($qZoosanitarioProductos as $zooProductos){
						if($zooProductos['sexo'] != '' && $zooProductos['edad'] != 0){
							echo '<td><label>Sexo</label></td>
								 <td><label>Edad</label></td>';
							break;
						}
					}
					
					echo '<td><label>Cantidad física</label></td>';
						echo '</tr>';
	
		foreach ($qZoosanitarioProductos as $zooProductos){
			echo '<tr>
					<td>'.$i.'</td>
					<td>' . $zooProductos['nombreProducto'] . '</td>
					<td>' . $zooProductos['partidaArancelaria'] . '</td>';
			
			if($zooProductos['sexo'] != ''){
				echo '<td>' . $zooProductos['sexo'] . '</td>';
			}
			if($zooProductos['edad'] != 0){
				$qEdad = $cc->buscarRangoEdadesAnimal($conexion, $zooProductos['edad']);
				echo '<td>' . pg_fetch_result($qEdad, 0, 'nombre') . '</td>';
			}
				
			echo   '<td>' . $zooProductos['cantidadFisica'].' '. $zooProductos['unidadFisica'] . '</td>';
			
			$i++;
		}
		
		//</td></tr>
		echo '</fieldset>';
	
	echo '</table>
	</form>
	</fieldset>
	</div>';
?>	

<script type="text/javascript">
	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));
		$("#resultado").hide();
		
		if (<?php echo '"'.$qZoosanitario['estado'].'"';?> == "enviado" || <?php echo '"'.$qZoosanitario['estado'].'"';?> == "rechazado"){
			$("#resultado").show();
		}
	});
</script>