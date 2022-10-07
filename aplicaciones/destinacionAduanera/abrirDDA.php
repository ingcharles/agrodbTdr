<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorImportaciones.php';

$conexion = new Conexion();
$cd = new ControladorDestinacionAduanera();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();
$ci = new ControladorImportaciones();

$usuario = $_SESSION['usuario'];

$qDestinacionAduanera = $cd->abrirDDA($conexion, $_POST['id']);

$importacion = pg_fetch_assoc($ci->buscarVigenciaImportacion($conexion, $qDestinacionAduanera[0]['permisoImportacion']));

$qDocumentos = $cd->abrirDDAArchivos($conexion, $_POST['id']);

//Obtener datos del operador
$qOperador = $cr->buscarOperador($conexion, $qImportacion[0]['identificador']);

?>

<header>
	<h1>Documento de Destinación Aduanera</h1>
</header>


	<div id="estado"></div>
	
	<!-- <div class="pestania"> -->
	
	<fieldset id="resultado">
			<legend>Resultado de Documento destinación aduanera</legend>
			<div data-linea="1">
				<label>Resultado: </label> 
				<?php echo ($qDestinacionAduanera[0]['estado']=='aprobado'? '<span class="exito">'.$qDestinacionAduanera[0]['estado'].'</span>':'<span class="alerta">'.$qDestinacionAduanera[0]['estado'].'</span>');?>
			</div>
			<div data-linea="2">
				<label>Observaciones: </label> <?php echo $qDestinacionAduanera[0]['observacionImportacion']; ?> <br/>
			</div>
	</fieldset>
	
	<fieldset>
			<legend>Certificado de Importación</legend>
			
			<input type="hidden" id="idDestinacionAduanera" name="idDestinacionAduanera" value=<?php echo $qDestinacionAduanera[0]['idDestinacionAduanera']; ?> />
			<div data-linea="1">
				<label>Tipo Certificado: </label> <?php echo $qDestinacionAduanera[0]['tipoCertificado']; ?> 
			</div>
			<div data-linea="1">
				<label>Propósito: </label> <?php echo $qDestinacionAduanera[0]['proposito']; ?> 
			</div>
				
			<div data-linea="3">
				<label>Categoría producto: </label> <?php echo $qDestinacionAduanera[0]['categoriaProducto']; ?> 
			</div>
			<div data-linea="4">
				<label>Razón social importador:</label> <?php echo $qDestinacionAduanera[0]['razonSocial'];?>
			</div>
			<div data-linea="5">
				<label>Representante legal:</label> <?php echo $qDestinacionAduanera[0]['nombreRepresentante'] . " ";
															echo $qDestinacionAduanera[0]['apellidoRepresentante'];?>
			</div>
			<div data-linea="6">
				<label>Dirección:</label> <?php echo $qDestinacionAduanera[0]['provincia']."/".$qDestinacionAduanera[0]['canton']."/".$qDestinacionAduanera[0]['parroquia'];?><br />
					<?php echo $qDestinacionAduanera[0]['direccion']; ?>
			</div>
	</fieldset>
	
	<fieldset>
			<legend>Datos de Importación</legend>
			
			<div data-linea="1">
				<label>Permiso importación: </label> <?php echo  $qDestinacionAduanera[0]['permisoImportacion']; ?>
			</div>
			
			<div data-linea="1">
				<label>Certificado exportación: </label> <?php echo $qDestinacionAduanera[0]['permisoExportacion']; ?>
			</div>	
			
			<div data-linea="2">
				<label>Exportador: </label> <?php echo $qDestinacionAduanera[0]['nombreExportador']; ?>
			</div>	
			<div data-linea="3">
				<label>Dirección: </label> <?php echo $qDestinacionAduanera[0]['direccionExportador']; ?> 
			</div>
			
			<div data-linea="4">
				<label>País origen: </label> <?php echo  $qDestinacionAduanera[0]['paisExportacion']; ?>
			</div>
			
			<div data-linea="5">
				<label># carga: </label> <?php echo $qDestinacionAduanera[0]['numeroCarga']; ?> 
			</div>
			
			<div data-linea="5">
				<label>Puerto destino: </label> <?php echo $qDestinacionAduanera[0]['nombrePuertoDestino']; ?> 
			</div>
			
			<div data-linea="6">
				<label>Medio de transporte: </label> <?php echo $qDestinacionAduanera[0]['tipoTransporte']; ?> 
			</div>
			
			<div data-linea="6">
				<label># Doc. transporte: </label> <?php echo $qDestinacionAduanera[0]['numeroTransporte']; ?> 
			</div>
			
			<div data-linea="7">
				<label>Lugar inspección: </label> <?php echo $qDestinacionAduanera[0]['nombreLugarInspeccion']; ?> 
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
	
	$i=1;
	foreach ($qDestinacionAduanera as $destinacionAduanera){
		echo '
		<fieldset>
			<legend>Producto de importación ' . $i . '</legend>
				<div data-linea="5">	
					<label>Nombre del producto: </label> ' . $destinacionAduanera['nombreProducto'] . ' <br/>
				</div>
				<div data-linea="6">	
					<label>Partida arancelaria: </label> ' . $destinacionAduanera['partidaArancelaria'] . ' <br/>
				</div>
				<div data-linea="7">
					<label>Unidad: </label> ' . $destinacionAduanera['unidad'] . ' ' . $destinacionAduanera['unidadMedida'] . '<br/>
				</div>';
				
				if($destinacionAduanera['estado'] == 'aprobado' || $destinacionAduanera['estado'] == 'rechazado'){
					echo '<div data-linea="10" >
					<label>Estado: </label> ' . ($destinacionAduanera['estadoProducto']=='aprobado'? '<span class="exito">'.$destinacionAduanera['estadoProducto'].'</span>':'<span class="alerta">'.$destinacionAduanera['estadoProducto'].'</span>'). '<br/>
					</div>';
					if($destinacionAduanera['rutaArchivo']!='0' && $destinacionAduanera['observacionProducto']!= ''){
						echo   '<div data-linea="10">
								    	<label>Informe: </label>'. ($destinacionAduanera['rutaArchivo']==''? '<span class="alerta">No ha subido ningún archivo</span>':'<a href='.$destinacionAduanera['rutaArchivo'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>').'
								    </div>
								
									<div data-linea="11">
								     	<label>Observación: </label> ' . $destinacionAduanera['observacionProducto'] . ' <br/>
								     </div>';
					}
				}
		echo '</fieldset>';
		
		$i++;
	}
	
	
?>	
<!-- </div> -->

<script type="text/javascript">
var estado= <?php echo json_encode($estado); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));
		$("#resultado").hide();
		
		if (<?php echo '"'.$qDestinacionAduanera[0]['estado'].'"';?> == "aprobado" || <?php echo '"'.$qDestinacionAduanera[0]['estado'].'"';?> == "rechazado" || <?php echo '"'.$qDestinacionAduanera[0]['estado'].'"';?> == "subsanacion"){
			$("#resultado").show();
		}
	});
</script>