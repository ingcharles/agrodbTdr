<?php 
session_start();
require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorImportaciones.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorRegistroOperador.php';

$conexion = new Conexion();
$ci = new ControladorImportaciones();
$cc = new ControladorCatalogos();
$crs = new ControladorRevisionSolicitudesVUE();
$cro = new ControladorRegistroOperador();

$estadoProducto = array();

$qImportacion = $ci->abrirImportacionEnviada($conexion, $_POST['id']);

$qDocumentos = $ci->abrirImportacionesArchivos($conexion, $_POST['id']);

$qAmpliacion = $ci->abrirImportacionesArchivoIndividual($conexion, $_POST['id'], 'PEDIDO DE AMPLIACION');

$qRegimenAduanero = $cc->obtenerNombreRegimenAduanero($conexion, $qImportacion[0]['regimenAduanero']);
$regimenAduanero = pg_fetch_result($qRegimenAduanero, 0, 'descripcion');

$qMoneda = $cc->obtenerNombreMoneda($conexion, $qImportacion[0]['moneda']);
$moneda = pg_fetch_result($qMoneda, 0, 'nombre');

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);

$numeroCuarentena = $qImportacion[0]['numeroCuarentena'];
$banderaSeguimientoCuarentenario = false;

if($numeroCuarentena != "" || $numeroCuarentena != null){
	$banderaSeguimientoCuarentenario = true;
	$qDatosOperador = $cro->obtenerAreaRegistroCuarentena($conexion, $numeroCuarentena, $qImportacion[0]['identificador']);
	$datosOperador = pg_fetch_assoc($qDatosOperador);	
}

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
				<!-- label>Estado de solicitud: </label> < ?php echo ($qImportacion[0]['estadoImportacion']=='aprobado'? '<span class="exito">'.$qImportacion[0]['estadoImportacion'].'</span>': (($qImportacion[0]['estadoImportacion']=='enviado' && pg_num_rows($qAmpliacion) != 0) ?'<span class="alerta">Solicitud de ampliación</span>':($qImportacion[0]['estadoImportacion']=='pago' && pg_num_rows($qAmpliacion) == 0 ? '<span class="alerta">Solicitud en proceso de pago</span>' :($qImportacion[0]['estadoImportacion']=='pago' && pg_num_rows($qAmpliacion) != 0?'<span class="alerta">Solicitud en proceso de pago de ampliación</span>':($qImportacion[0]['estadoImportacion']=='enviado'?'<span class="alerta">Solicitud en revisión documental</span>':$qImportacion[0]['estadoImportacion']))))); ?-->
				<label>Estado de solicitud: </label><?php echo ($qImportacion[0]['estadoImportacion']=='aprobado'? '<span class="exito">'.$qImportacion[0]['estadoImportacion'].'</span>': ($qImportacion[0]['estadoImportacion']=='enviado'?'<span class="alerta">Solicitud en revisión documental</span>':($qImportacion[0]['estadoImportacion']=='pago'? '<span class="alerta">Solicitud en proceso de pago</span>' :($qImportacion[0]['estadoImportacion']=='verificacion' ?'<span class="alerta">Solicitud en proceso de verificación sistema GUIA</span>':	($qImportacion[0]['estadoImportacion']=='verificacionVUE'?	'<span class="alerta">Solicitud en proceso de verificación de pago en sistema VUE</span>':	$qImportacion[0]['estadoImportacion']))))); ?>
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
			<div data-linea="10">
				<label>Licencia MAGAP: </label><?php echo ($qImportacion[0]['licenciaMagap']==''?'N/A':$qImportacion[0]['licenciaMagap']); ?>
			</div>	
			<div data-linea="11">
				<label>Sitio/Predio de cuarentena: </label><?php echo ($numeroCuarentena==''?'N/A':$numeroCuarentena); ?> 
			</div>
			<div data-linea="12">
				<label>Tipo de solicitud: </label><?php echo ($qImportacion[0]['nombreSolicitudFertilizantes']==''?'N/A':$qImportacion[0]['nombreSolicitudFertilizantes']); ?> 
			</div>						
	</fieldset>
	
	
	<?php 
	//IMPRESION DE DOCUMENTOS
	$i=1;
	if(isset($qDocumentos)){
		
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
		
		$estadoProducto[] = $importacion['estadoVigenciaProducto'];
		
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
				</div>
				<div data-linea="9">
					<label>Registro Semillas: </label> ' . $importacion['registroSemillas'] . ' <br/>
				</div>
				<div data-linea="10">
					<label>Composición: </label>'.($importacion['composicion']==''?'N/A':$importacion['composicion']).' 
				</div>
				<div data-linea="10">
					<label>Producto a formular: </label>'.($importacion['productoFormular']==''?'N/A':$importacion['productoFormular']).' 
				</div>
				<div data-linea="11">
					<label>Nombre producto pais origen: </label>'.($importacion['nombreProductoPaisOrigen']==''?'N/A':$importacion['nombreProductoPaisOrigen']).' 
				</div>';
				
				
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
	
	$estadoProducto = array_unique($estadoProducto);
	
	if(count($estadoProducto) ==1){
		if($estadoProducto[0] == '1'){
			$banderaEstadoProducto = false;
		}else{
			$banderaEstadoProducto = true;
		}
	}else{
		$banderaEstadoProducto = true;
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
		<input type="hidden" name="identificadorOperador" value="<?php echo $qImportacion[0]['identificador'];?>"/>
		<input type="hidden" name="nombreProvincia" id="nombreProvincia"  />
		<input type="hidden" name="idArea" value="<?php echo $qImportacion[0]['idArea'];?>"/>
		<input type="hidden" id="opcion" name="opcion" />
		<input type="hidden" id="requiereSeguimiento" name="requiereSeguimiento" value=""/>
				
		<fieldset id="vistaSeguimientoCuarentenario">
			<legend>Seguimiento Cuarentenario</legend>						
				<div data-linea="1">
					<label>Provincia: </label> <?php echo $datosOperador['provincia']?>			
				</div>
				
				<div data-linea="2" id="resultadoSitios">
					<label>Sitio de cuarentena: </label> <?php echo $datosOperador['nombre_lugar']; ?>
					<input type="hidden" name="sitio" value="<?php echo $datosOperador['id_sitio']; ?>" />
				</div>
				
				<div data-linea="3" id="resultadoAreas">
					<label>Área de cuarentena: </label> <?php echo $datosOperador['nombre_area']; ?>
					<input type="hidden" name="area" value="<?php echo $datosOperador['id_area']; ?>" />
				</div>
		</fieldset>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>					
				<div data-linea="6">
					<label>Resultado: </label>
						<select id="resultadoDocumento" name="resultadoDocumento">
							<option value="">Seleccione....</option>
							<option value="pago">Aprobar revisión documental</option>
							<option value="subsanacion">Subsanación</option>
							<option value="rechazado">Solicitud rechazada</option>
						</select>
				</div>	
				<div data-linea="2">
					<label>Observaciones: </label>
					<input type="text" id="observacionDocumento" name="observacionDocumento"/>
				</div>
		</fieldset>
		
		<button type="submit" class="guardar">Enviar resultado</button>			
	</form> 
</div>

<script type="text/javascript">
var estado= <?php echo json_encode($qImportacion[0]['estadoImportacion']); ?>;
var banderaSeguimientoCuarentenario = <?php echo json_encode($banderaSeguimientoCuarentenario); ?>;
var banderaEstadoProducto = <?php echo json_encode($banderaEstadoProducto); ?>;
var idArea = <?php echo json_encode($qImportacion[0]['idArea']); ?>;

	$(document).ready(function(){
		$("#estado").html("");
		distribuirLineas();
		construirAnimacion($(".pestania"));	

		$("#evaluarDocumentosSolicitud").hide();
		
		if(estado == "enviado" || estado == "asignadoDocumental"){
			$("#evaluarDocumentosSolicitud").show();
		}else{
			$("#evaluarDocumentosSolicitud").hide();
		}

		if(banderaSeguimientoCuarentenario){
			$("#vistaSeguimientoCuarentenario").show();
			$("#requiereSeguimiento").val("SI");
		}else{
			$("#vistaSeguimientoCuarentenario").hide();
			$("#requiereSeguimiento").val("NO");
		}

		if(banderaEstadoProducto){
			if(idArea == 'IAV' || idArea == 'IAP' || idArea == 'IAF'){
				$("#estado").html("Por favor verificar el estado de los productos.").addClass('alerta');
			}
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
			$('#evaluarDocumentosSolicitud').attr('data-opcion','evaluarDocumentosSolicitud');
			ejecutarJson(form);
		}
	}
	
	
</script>
