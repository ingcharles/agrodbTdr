<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';

$conexion = new Conexion();
$ci = new ControladorImportaciones();
$cc = new ControladorCatalogos();
$crs = new ControladorRevisionSolicitudesVUE();

$qImportacion = $ci->abrirImportacionEnviada($conexion, $_POST['id']);

$qDocumentos = $ci->abrirImportacionesArchivos($conexion, $_POST['id']);

$qAmpliacion = $ci->abrirImportacionesArchivoIndividual($conexion, $_POST['id'], 'PEDIDO DE AMPLIACION');

$qRegimenAduanero = $cc->obtenerNombreRegimenAduanero($conexion, $qImportacion[0]['regimenAduanero']);
$regimenAduanero = pg_fetch_result($qRegimenAduanero, 0, 'descripcion');

$qMoneda = $cc->obtenerNombreMoneda($conexion, $qImportacion[0]['moneda']);
$moneda = pg_fetch_result($qMoneda, 0, 'nombre');

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);
/*echo '<pre>';
print_r($qSolicitud);
echo '</pre>';*/

?>

<header>
	<h1>Solicitud Importación</h1>
</header>
	
<div id="estado"></div>

<div class="pestania">
	
	<fieldset>
			<legend>Certificado de Importación</legend>
			
			<input type="hidden" id="idImportacion" name="idImportacion" value=<?php echo $qImportacion[0]['idImportacion']; ?> />
			<div data-linea="1">
				<label>Tipo Certificado: </label> <?php echo $qImportacion[0]['tipoCertificado']; ?> 
			</div>
			
			<div data-linea="2">
				<label>Razón social: </label> <?php echo $qImportacion[0]['razonSocial']; ?> <br/>
			</div>
			
			<div data-linea="3">
				<label>Representante legal: </label> <?php echo $qImportacion[0]['nombreRepresentante'] . ' ' . $qImportacion[0]['apellidoRepresentante']; ?> <br/>
			</div>
			
			<div data-linea="4">
				<label>Estado de solicitud: </label> <?php echo ($qImportacion[0]['estadoImportacion']=='aprobado'? '<span class="exito">'.$qImportacion[0]['estadoImportacion'].'</span>': (($qImportacion[0]['estadoImportacion']=='enviado' && pg_num_rows($qAmpliacion) != 0) ?'<span class="alerta">Solicitud de ampliación</span>':($qImportacion[0]['estadoImportacion']=='pago' && pg_num_rows($qAmpliacion) == 0 ? '<span class="alerta">Solicitud en proceso de pago</span>' :($qImportacion[0]['estadoImportacion']=='pago' && pg_num_rows($qAmpliacion) != 0?'<span class="alerta">Solicitud en proceso de pago de ampliación</span>':($qImportacion[0]['estadoImportacion']=='enviado'?'<span class="alerta">Solicitud en revisión documental</span>':$qImportacion[0]['estadoImportacion']))))); ?>
			</div>
			<?php 
				$inspectores='';
			
				if($qImportacion[0]['estadoImportacion'] == 'asignado'){
					$res = $crs->listarInspectoresAsignados($conexion, $_POST['id'], 'Importación', 'Documental');

					echo '
						<div data-linea="5">
							<label>Inspectores asignados: </label>';
					
					while($fila = pg_fetch_assoc($res)){
						echo $fila['apellido'].", ".$fila['nombre']."; "; 
					}

					echo '</div>';
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
				<label>Medio transporte: </label> <?php echo $qImportacion[0]['tipoTransporte']; ?> 
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
			<legend>Producto de importación ' . $i . '</legend>';
			
			$qProductoTipoSubtipo = $cc->obtenerTipoSubtipoXProductos($conexion, $importacion['idProducto']);
			$productoTipoSubtipo = pg_fetch_assoc($qProductoTipoSubtipo);
		
		echo '<div data-linea="3">	
					<label>Tipo producto: </label> ' . $productoTipoSubtipo['nombre_tipo'] . ' <br/>
				</div>
				<div data-linea="4">	
					<label>Subtipo producto: </label> ' . $productoTipoSubtipo['nombre_subtipo'] . ' <br/>
				</div>
				<div data-linea="5">	
					<label>Nombre del producto: </label> ' . $importacion['nombreProducto'] . ' <br/>
				</div>
				<div data-linea="5">	
					<label>Presentación producto: </label> ' . $importacion['presentacion'] . ' <br/>
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
				
	/*	echo '<div data-linea="10" >
				<label>Estado: </label> ' . ($importacion['estadoProducto']=='aprobado'? '<span class="exito">'.$importacion['estadoProducto'].'</span>':'<span class="alerta">'.$importacion['estadoProducto'].'</span>'). '<br/>
				</div>';
					   if($importacion['rutaArchivo']!='0' && $importacion['observacionProducto']!= ''){
					    echo   '<div data-linea="10">
							    	<label>Informe: </label>'. ($importacion['archivoProducto']==''? '<span class="alerta">No ha subido ningún archivo</span>':'<a href='.$importacion['archivoProducto'].' target="_blank" class="archivo_cargado" id="archivo_cargado">Archivo Cargado</a>').'
							    </div>
							    		
								<div data-linea="11">
							     	<label>Observación: </label> ' . $importacion['observacionProducto'] . ' <br/>
							     </div>';
				}*/
		echo '</fieldset>';
		
		$i++;
	}
	
	
	?>	
</div>

<!-- SECCION DE REVISIÓN DE PRODUCTOS PARA IMPORTACION -->
<div class="pestania">	
	<!-- <form id="evaluarDocumentosSolicitud" data-rutaAplicacion="importaciones" data-opcion="evaluarDocumentosSolicitud" data-accionEnExito="ACTUALIZAR">-->
	<form id="evaluarDocumentosSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarDocumentosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="tipoSolicitud" value="Importación"/>
		<input type="hidden" name="tipoInspector" value="Documental"/>
		<input type="hidden" name="idVue" value="<?php echo $qImportacion[0]['idVue'];?>"/>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
					
				<div data-linea="6">
					<label>Resultado</label>
						<select id="resultadoDocumento" name="resultadoDocumento">
							<option value="">Seleccione....</option>
							<option value="pago">Aprobar revisión documental</option>
							<option value="subsanacion">Subsanación</option>
							<option value="rechazado">Solicitud rechazada</option>
						</select>
				</div>	
				<div data-linea="2">
					<label>Observaciones</label>
					<input type="text" id="observacionDocumento" name="observacionDocumento"/>
				</div>
		</fieldset>
		
		<button type="submit" class="guardar">Enviar resultado</button>			
	</form> 
</div>

<script type="text/javascript">
var estado= <?php echo json_encode($qImportacion[0]['estadoImportacion']); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));	

		$("#evaluarDocumentosSolicitud").hide();
		
		if(estado == "enviado" || estado == "asignadoDocumental"){
			$("#evaluarDocumentosSolicitud").show();
		}else{
			$("#evaluarDocumentosSolicitud").hide();
		}

	});

	$("#evaluarDocumentosSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccionDocumental(this);
	});

	function esCampoValido(elemento){
		var patron = new RegExp($(elemento).attr("data-er"),"g");
		return patron.test($(elemento).val());
	}

	function chequearCamposInspeccionDocumental(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#resultadoDocumento").val()) || !esCampoValido("#resultadoDocumento")){
			error = true;
			$("#resultadoDocumento").addClass("alertaCombo");
		}

		if($("#resultadoDocumento").val()!= 'pago'){
			if(!$.trim($("#observacionDocumento").val()) || !esCampoValido("#observacionDocumento")){
				error = true;
				$("#observacionDocumento").addClass("alertaCombo");
			}
		}
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}
</script>
