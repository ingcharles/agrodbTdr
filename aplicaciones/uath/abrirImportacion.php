<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

$conexion = new Conexion();
$ci = new ControladorImportaciones();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();
$crs = new ControladorRevisionSolicitudesVUE();

$qImportacion = $ci->abrirImportacion($conexion, $_SESSION['usuario'], $_POST['id']);

$qDocumentos = $ci->abrirImportacionesArchivos($conexion, $_POST['id']);

//Obtener datos del operador
$qOperador = $cr->buscarOperador($conexion, $qImportacion[0]['identificador']);

$qAmpliacion = $ci->abrirImportacionesArchivoIndividual($conexion, $_POST['id'], 'PEDIDO DE AMPLIACION');


$qRegimenAduanero = $cc->obtenerNombreRegimenAduanero($conexion, $qImportacion[0]['regimenAduanero']);
$regimenAduanero = pg_fetch_result($qRegimenAduanero, 0, 'descripcion');

$qMoneda = $cc->obtenerNombreMoneda($conexion, $qImportacion[0]['moneda']);
$moneda = pg_fetch_result($qMoneda, 0, 'nombre');


//Obtener monto a pagar
$qMonto = $crs->obtenerMontoSolicitud($conexion, $_POST['id'], 'Importación');

?>

<header>
	<h1>Solicitud Importación</h1>
</header>


	<div id="estado"></div>
	
	<!-- <div class="pestania"> -->
	
	<fieldset id="resultado">
			<legend>Resultado de Inspección</legend>
			<div data-linea="1">
				<label>Resultado: </label> 
				<?php echo ($qImportacion[0]['estadoImportacion']=='aprobado'? '<span class="exito">'.$qImportacion[0]['estadoImportacion'].'</span>': (($qImportacion[0]['estadoImportacion']=='enviado' && pg_num_rows($qAmpliacion) != 0) ?'<span class="alerta">Solicitud de ampliación</span>':($qImportacion[0]['estadoImportacion']=='pago' && pg_num_rows($qAmpliacion) == 0 ? '<span class="alerta">Solicitud en proceso de pago</span>' :($qImportacion[0]['estadoImportacion']=='pago' && pg_num_rows($qAmpliacion) != 0?'<span class="alerta">Solicitud en proceso de pago de ampliación</span>':($qImportacion[0]['estadoImportacion']=='enviado'?'<span class="alerta">Solicitud en revisión documental</span>':$qImportacion[0]['estadoImportacion']))))); ?>
			</div>
			<!-- div data-linea="2">
				<label>Observaciones: </label--> 
				<?php //echo $qImportacion[0]['observacionImportacion']; ?> <br/>
			<!--/div-->
			
			<?php 
				if($qImportacion[0]['informeRequisitos']!=''){
					echo '<div data-linea="3">
								<label>Requisitos de comercialización: </label><a href="'. $qImportacion[0]['informeRequisitos'].'" target="_blank">Documento cargado</a>
						</div>';
				}
			?>
			
			<?php 
				/*$mi_pdf = 'archivosRequisitos/1713335188-22.pdf'; 
				header('Content-type: application/pdf');
				header('Content-Disposition: attachment; filename="'.$mi_pdf.'"');
				readfile($mi_pdf);*/
				/*$file = fopen("archivosRequisitos/1713335188-22.pdf","r");
				fread($file);*/
			?>
	</fieldset>
	
	<fieldset>
			<legend>Certificado de Importación</legend>
			
			<input type="hidden" id="idImportacion" name="idImportacion" value=<?php echo $qImportacion[0]['idImportacion']; ?> />
			<div data-linea="3">
				<label>Tipo Certificado: </label> <?php echo $qImportacion[0]['tipoCertificado']; ?> 
			</div>
			<div data-linea="4">
				<label>Razón social importador:</label> <?php echo pg_fetch_result($qOperador, 0, 'razon_social');?>
			</div>
			<div data-linea="5">
				<label>Representante legal:</label> <?php echo pg_fetch_result($qOperador, 0, 'nombre_representante') . " ";
															echo pg_fetch_result($qOperador, 0, 'apellido_representante');?>
			</div>
			<div data-linea="6">
				<label>Dirección:</label> <?php echo pg_fetch_result($qOperador, 0, 'provincia')."/".pg_fetch_result($qOperador, 0, 'canton')."/".pg_fetch_result($qOperador, 0, 'parroquia');?><br />
					<?php echo pg_fetch_result($qOperador, 0, 'direccion'); ?>
			</div>
			<?php
				if($qImportacion[0]['estadoImportacion']=='verificacion'){
					echo '<div data-linea="7">
						<label>Monto a pagar:</label> $'.pg_fetch_result($qMonto, 0, 'monto').'
					</div>';
				}
			?>
	</fieldset>
	
	<fieldset>
			<legend>Datos de Importación</legend>
					
			<div data-linea="4">
				<label>Nombre exportador: </label> <?php echo $qImportacion[0]['nombreExportador']; ?> 
			</div>
			
			<div data-linea="10">
				<label>Dirección exportador: </label> <?php echo $qImportacion[0]['direccionExportador']; ?> 
			</div>
			
			<div data-linea="5">
				<label>País origen: </label> <?php echo $qImportacion[0]['paisExportacion']; ?> 
			</div>
			
			<div data-linea="5">
				<label>País embarque: </label> <?php echo $qImportacion[0]['paisEmbarque']; ?> 
			</div>
			
			<div data-linea="6">
				<label>Nombre embarcador: </label> <?php echo $qImportacion[0]['nombreEmbarcador']; ?> 
			</div>
			
			<div data-linea="7">
				<label>Régimen aduanero: </label> <?php echo $regimenAduanero; ?> 
			</div>
			
			<div data-linea="8">
				<label>Moneda: </label> <?php echo $moneda; ?> 
			</div>
			
			<div data-linea="8">
				<label>Medio de transporte: </label> <?php echo $qImportacion[0]['tipoTransporte']; ?> 
			</div>
			
			<div data-linea="9">
				<label>Puerto embarque: </label> <?php echo $qImportacion[0]['puertoEmbarque']; ?> 
			</div>
			
			<div data-linea="9">
				<label>Puerto destino: </label> <?php echo $qImportacion[0]['puertoDestino']; ?> 
			</div>
	</fieldset>
	
	
	<?php 
	//IMPRESION DE DOCUMENTOS
	$i=1;
	if(count($qDocumentos)>0){
		
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
	foreach ($qImportacion as $importacion){

		echo '
		<fieldset>
			<legend>Producto de importación ' . $i . '</legend>
				<div data-linea="5">	
					<label>Nombre del producto: </label> ' . $importacion['nombreProducto'] . ' <br/>
				</div>
				<div data-linea="5">	
					<label>presentación producto: </label> ' . $importacion['presentacion'] . ' <br/>
				</div>
				<div data-linea="6">	
					<label>Partida arancelaria: </label> ' . $importacion['partidaArancelaria'] . ' <br/>
				</div>
				<div data-linea="7">
					<label>Cantidad: </label> ' . $importacion['unidad'] . ' '.$importacion['unidadMedida']. ' <br/>
				</div>
				<div data-linea="7">
					<label>Peso neto: </label> ' . $importacion['peso'] . ' kgs <br/>
				</div>
				<div data-linea="8">
					<label>Valor FOB: </label> ' . $importacion['valorFob'] . ' <br/>
				</div>
				<div data-linea="8">
					<label>Valor CIF: </label> ' . $importacion['valorCif'] . ' <br/>
				</div>';
				if($importacion['licenciaMagap']!=''){
					echo '
							<div data-linea="9">
								<label>Licencia MAGAP: </label> ' . $importacion['licenciaMagap'] . ' <br/>
							</div>';
				}
				
				if($importacion['registroSemillas']!=''){
					echo '
							<div data-linea="9">
								<label>Registro Semillas: </label> ' . $importacion['registroSemillas'] . ' <br/>
							</div>';
				}
				
				if($importacion['estadoImportacion'] == 'aprobado' || $importacion['estadoImportacion'] == 'rechazado' || $importacion['estadoImportacion'] == 'subsanacion'){
				/*	echo '<div data-linea="10" >
					<label>Estado: </label> ' . ($importacion['estadoProducto']=='aprobado'? '<span class="exito">'.$importacion['estadoProducto'].'</span>':'<span class="alerta">'.$importacion['estadoProducto'].'</span>'). '<br/>
					</div>';*/
					if($importacion['rutaArchivo']!='0' && $importacion['observacionProducto']!= ''){
						echo   '<div data-linea="10">
								    	<label>Informe: </label>'. ($importacion['archivoProducto']==''? '<span class="alerta">No ha subido ningún archivo</span>':'<a href='.$importacion['archivoProducto'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>').'
								    </div>
								
									<div data-linea="11">
								     	<label>Observación: </label> ' . $importacion['observacionProducto'] . ' <br/>
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
		
		if (<?php echo '"'.$qImportacion[0]['estadoImportacion'].'"';?> == "aprobado" || <?php echo '"'.$qImportacion[0]['estadoImportacion'].'"';?> == "rechazado" || <?php echo '"'.$qImportacion[0]['estadoImportacion'].'"';?> == "subsanacion"){
			$("#resultado").show();
		}
	});

	/*$("#documentos").on("click","form",function(event){
		if($(this).find("a").attr('data-id')!=''){
			abrir($(this),event,false); //Se ejecuta ajax, busqueda de documentos	
			$(this).find("a").attr('target','_blank');			 		
		}
	});*/
</script>