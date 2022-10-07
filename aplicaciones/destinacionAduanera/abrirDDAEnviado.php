<?php 
session_start();

require_once '../../clases/Conexion.php';
require_once '../../clases/ControladorDestinacionAduanera.php';
require_once '../../clases/ControladorCatalogos.php';
require_once '../../clases/ControladorRegistroOperador.php';
require_once '../../clases/ControladorRevisionSolicitudesVUE.php';
require_once '../../clases/ControladorImportaciones.php';

$conexion = new Conexion();
$cd = new ControladorDestinacionAduanera();
$cc = new ControladorCatalogos();
$cr = new ControladorRegistroOperador();
$crs = new ControladorRevisionSolicitudesVUE();
$ci = new ControladorImportaciones();

$usuario = $_SESSION['usuario'];

$qDestinacionAduanera = $cd->abrirDDA($conexion, $_POST['id']);


$importacion = pg_fetch_assoc($ci->buscarVigenciaImportacion($conexion, $qDestinacionAduanera[0]['permisoImportacion']));

$qDocumentos = $cd->abrirDDAArchivos($conexion, $_POST['id']);

//Obtener datos del operador
$qOperador = $cr->buscarOperador($conexion, $qImportacion[0]['identificador']);

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);

$validacionAprobacion = false;

if($importacion['estado_seguimiento']=='t'){
	$validacionSeguimiento = true;
	$datosSitioCuarentena = pg_fetch_assoc($cr->obtenerCodigoSitioAreaXidSitioIdArea($conexion,$importacion['id_area_seguimiento']));
}else{
	$validacionSeguimiento = false;
}
	

?>

<header>
	<h1>Documento de Destinación Aduanera</h1>
</header>


	<div id="estado"></div>
	
	<div class="pestania">
	
	<fieldset>
			<legend>Certificado de Importación</legend>
			
			<input type="hidden" id="idDestinacionAduanera" name="idDestinacionAduanera" value=<?php echo $qDestinacionAduanera[0]['idDestinacionAduanera']; ?> />
			<div data-linea="1">
				<label>Tipo Certificado: </label> <?php echo $qDestinacionAduanera[0]['tipoCertificado']; ?> 
			</div>
			<div data-linea="2">
				<label>Razón social importador:</label> <?php echo $qDestinacionAduanera[0]['razonSocial'];?>
			</div>
			<div data-linea="3">
				<label>Representante legal:</label> <?php echo $qDestinacionAduanera[0]['nombreRepresentante'] . " ";
															echo $qDestinacionAduanera[0]['apellidoRepresentante'];?>
			</div>
			<div data-linea="4">
				<label>Estado de solicitud: </label> <?php echo ($qDestinacionAduanera[0]['estado']=='aprobado'? '<span class="exito">'.$qDestinacionAduanera[0]['estado'].'</span>':'<span class="alerta">'.$qDestinacionAduanera[0]['estado'].'</span>'); ?> <br/>
			</div>
	</fieldset>
	
	<fieldset>
			<legend>Datos de Importación</legend>
			
			<div data-linea="1">
				<label># importación: </label> <?php echo  $qDestinacionAduanera[0]['permisoImportacion']; ?>
			</div>
			
			<div data-linea="12">
				<label>Fecha inicio vigencia: </label><span class="alerta"><?php echo  date('d/m/Y',strtotime($importacion['fecha_inicio'])); ?></span>
			</div>
			
			<div data-linea="12">
				<label>Fecha fin vigencia: </label><span class="alerta"><?php echo  date('d/m/Y',strtotime($importacion['fecha_vigencia'])); ?></span>
			</div>
			
			<div data-linea="2">
				<label>Certificado exportación: </label> <?php echo $qDestinacionAduanera[0]['permisoExportacion']; ?>
			</div>	
			<div data-linea="2">
				<label>Propósito: </label> <?php echo $qDestinacionAduanera[0]['proposito']; ?> 
			</div>
				
			<div data-linea="3">
				<label>Categoría producto: </label> <?php echo $qDestinacionAduanera[0]['categoriaProducto']; ?> 
			</div>			
			
			<div data-linea="4">
				<label>Exportador: </label> <?php echo $qDestinacionAduanera[0]['nombreExportador']; ?>
			</div>	
			<div data-linea="5">
				<label>Dirección: </label> <?php echo $qDestinacionAduanera[0]['direccionExportador']; ?> 
			</div>
			
			<div data-linea="6">
				<label>País origen: </label> <?php echo  $qDestinacionAduanera[0]['paisExportacion']; ?>
			</div>
			
			<div data-linea="6">
				<label># carga: </label> <?php echo $qDestinacionAduanera[0]['numeroCarga']; ?> 
			</div>
			
			<div data-linea="8">
				<label>Puerto destino: </label> <?php echo $qDestinacionAduanera[0]['nombrePuertoDestino']; ?> 
			</div>
			
			<div data-linea="9">
				<label>Medio de transporte: </label> <?php echo $qDestinacionAduanera[0]['tipoTransporte']; ?> 
			</div>
			
			<div data-linea="9">
				<label># Doc. transporte: </label> <?php echo $qDestinacionAduanera[0]['numeroTransporte']; ?> 
			</div>
			
			<div data-linea="10">
				<label>Lugar inspección: </label> <?php echo $qDestinacionAduanera[0]['nombreLugarInspeccion']; ?> 
			</div>
			
			<div data-linea="11">
				<label>Observación: </label> <?php echo $qDestinacionAduanera[0]['observacionImportacion']; ?> 
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
		
	$validacionCantidad = false;
	
		echo '
		<fieldset>
			<legend>Producto de importación ' . $i . '</legend>';
		
		$qProductoTipoSubtipo = $cc->obtenerTipoSubtipoXProductos($conexion, $destinacionAduanera['idProducto']);
		$productoTipoSubtipo = pg_fetch_assoc($qProductoTipoSubtipo);
		
		//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		$importacionProducto = pg_fetch_assoc($ci->buscarImportacionProductoVUE($conexion, $destinacionAduanera['identificador'], $destinacionAduanera['permisoImportacion'] , $destinacionAduanera['idProducto']));
		
		$cantidadProducto = pg_fetch_assoc($cd->obtenerCantidadProductoXimportacion($conexion, $destinacionAduanera['permisoImportacion'], $destinacionAduanera['idProducto']));
		
		$cantidadActualProducto = $importacionProducto['unidad'] - $cantidadProducto['cantidad_producto'];
	
		$pesoActualProducto = $importacionProducto['peso'] - $cantidadProducto['peso_producto'];
		
		if($destinacionAduanera['unidad'] > $cantidadActualProducto){
			$validacionCantidad = true;
			$validacionAprobacion = true;
		}
		
		//---------------------------------------------------------------------------------------------------------------------------------------------------------------------
		
		
		echo'
				<div data-linea="3">	
					<label>Tipo producto: </label> ' . $productoTipoSubtipo['nombre_tipo'] . ' <br/>
				</div>
				<div data-linea="4">	
					<label>Subtipo producto: </label> ' . $productoTipoSubtipo['nombre_subtipo'] . ' <br/>
				</div>
						
				<div data-linea="5">	
					<label>Nombre del producto: </label> ' . $destinacionAduanera['nombreProducto'] . ' <br/>
				</div>
				<div data-linea="6">	
					<label>Partida arancelaria: </label> ' . $destinacionAduanera['partidaArancelaria'] . ' <br/>
				</div>
				<div data-linea="6">
					<label>Cantidad: </label> ' . $destinacionAduanera['unidad'] . ' ' . $destinacionAduanera['unidadMedida'] . '<br/>
				</div>
				<div data-linea="7">
					<label>Peso disponible del permiso: </label> ' . $pesoActualProducto. ' ' . $importacionProducto['unidad_peso'] . '<br/>
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
				
				if($validacionCantidad){
					echo '<p class="alerta">El permiso de importacion cuenta con '.$cantidadActualProducto.' '.$destinacionAduanera['unidadMedida'].' de '.$destinacionAduanera['nombreProducto'].' disponible.</p>';
				}
				
		echo '</fieldset>';
		
		$i++;
	}
	
	
?>	
</div>

<!-- SECCION DE REVISIÓN DE PRODUCTOS PARA DDA -->
<div class="pestania">	
	<form id="evaluarDocumentosSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarDocumentosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="tipoSolicitud" value="DDA"/>
		<input type="hidden" name="tipoInspector" value="Documental"/>
		<input type="hidden" name="idVue" value="<?php echo $qDestinacionAduanera[0]['idVue'];?>"/>
		<input type="hidden" name="tipoCertificado" value="<?php echo $qDestinacionAduanera[0]['tipoCertificado'];?>"/>
		<input type="hidden" name="requiereSeguimientoCuarentenario" value="<?php echo $importacion['estado_seguimiento'];?>"/>
		
		<fieldset id="vistaSeguimientoSAD">
			<legend>Seguimiento Cuarentenario</legend>
				<div data-linea="1">
				<label>Provincia: </label>
					<input type="text" name="provincia" readOnly value="<?php echo $datosSitioCuarentena['provincia'];?>"/>
				</div>
				<div data-linea="2" id="resultadoSitios">
					<label>Sitio de cuarentena: </label>
					<input type="text" name="sitio" readOnly value="<?php echo $datosSitioCuarentena['sitio'];?>"/>
				</div>
				<div data-linea="3" id="resultadoAreas">
					<label>Área de cuarentena: </label>
					<input type="text" name="area" readOnly value="<?php echo $datosSitioCuarentena['area'];?>"/>
				</div>
		</fieldset>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
					
				<div data-linea="6">
					<label>Resultado</label>
						<select id="resultadoDocumento" name="resultadoDocumento">
							<option value="">Seleccione....</option>
							<option value="inspeccion">Aprobar revisión documental</option>
							<option value="subsanacion">Subsanación</option>
						</select>
				</div>	
				<div data-linea="2">
					<label>Observaciones</label>
					<input type="text" id="observacionDocumento" name="observacionDocumento"/>
				</div>
		</fieldset>
		
		<fieldset>
			<legend>Información adicional de respuesta</legend>
					
				<div data-linea="7">
					<label>F. embarque</label>
					<input type="text"	id="fechaEmbarque"  name="fechaEmbarque"/>
				</div>	
				<div data-linea="7">
					<label>F. arribo</label>
					<input type="text" id="fechaArribo" name="fechaArribo"/>
				</div>
				<div data-linea="7">
					<label># contenedores</label>
					<input type="text" id="numeroContenedores" name="numeroContenedores" placeholder="Ej: 123" data-er="^[0-9]+$"/>
				</div>
		</fieldset>
		
		<fieldset>
			<legend>Documento adjunto</legend>
			<div data-linea="1">
				<input type="file" class="archivo" name="informeDocumental" id="informeDocumental" accept="application/pdf"/>
				<input type="hidden" class="rutaArchivo" name="archivoDocumental" id="archivoDocumental" value="0"/>
				<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
				<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/destinacionAduanera/informeDocumental" >Subir archivo</button>
			</div>
		</fieldset>
		
		<button type="submit" class="guardar">Enviar resultado</button>			
	</form> 
	
	<form id="evaluarSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarElementosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="tipoSolicitud" value="DDA"/>
		<input type="hidden" name="tipoInspector" value="Técnico"/>
		<input type="hidden" name="identificadorOperador" value="<?php echo $qDestinacionAduanera[0]['identificador'];?>"/> <!-- USUARIO OPERADOR -->
		<input type="hidden" name="idVue" value="<?php echo $qDestinacionAduanera[0]['idVue'];?>"/>
		<input type="hidden" name="tipoElemento" value="Productos"/>
		<input type="hidden" name="tipoCertificado" value="<?php echo $qDestinacionAduanera[0]['tipoCertificado'];?>"/>
		<input type="hidden" name="requiereSeguimientoCuarentenario" value="<?php echo $importacion['estado_seguimiento'];?>"/>
		<?php 
			//Obtener el número de elementos a inspeccionar
			$historial = $cd->listarHistorialSolicitudes($conexion, $_POST['id']);
			
			if (true){
				echo '<fieldset>
						<legend>Historial de revisión</legend>
						
						<table>
							<tr>
								<th>#</th>
								<th>Producto</th>
							</tr>';
				$i=1;
				while($registrosHistorial = pg_fetch_assoc($historial)){
				
					echo '<tr>
							<td>'.$i.'</td>
							<td>'.$registrosHistorial['nombre_producto'].'</td>
							<td>'.$registrosHistorial['estado'].'</td>
						</tr>';
					
					$i++;
				}
				
				echo '	</table>
					</fieldset>';
			}
		?>
		
		<fieldset>
			<legend>Productos para revisión</legend>
			
			<p class="nota">Por favor marque solamente los productos que va a evaluar.</p>
			<table>
				<tr>
					<th>#</th>
					<th>Producto</th>
					<th>Cantidad</th>
					<th>Peso</th>
				</tr>	
				<?php 
				$contadorDiv=13;
					foreach ($qDestinacionAduanera as $destinacionAduanera){
						//if($destinacionAduanera['estadoProducto']=='' || $destinacionAduanera['estadoProducto']=='rechazado'){
						if($destinacionAduanera['estadoProducto']=='' ){
							echo '<tr>
									<td>
										<input type="checkbox" id="'.$destinacionAduanera['idProducto'].'" name="listaElementos[]" value="'.$destinacionAduanera['idProducto'].'">
									</td>
									<td >
										<label for="'.$destinacionAduanera['idProducto'].'">'. $destinacionAduanera['nombreProducto'] . '</label>
									</td>
									<td >
										<label>'. $destinacionAduanera['unidad'].' '.$destinacionAduanera['unidadMedida'] . '</label>
									</td>
									<td >
										<input type="text" id="p'.$destinacionAduanera['idProducto'].'" name="pesoProducto[]" data-er="^[0-9]+(\.[0-9]{1,3})?$" disabled = "disabled"/><label> KG</label>
									</td>
								</tr>';
						}
					}
				?>
			</table>
		</fieldset>	
	
		<fieldset id="subirInforme">
				<legend>Informe de revisión</legend>
					<div>
						<input type="file" class="archivo" name="informe" id="informe" accept="application/pdf"/>
						<input type="hidden" class="rutaArchivo" name="archivo" id="archivo" value="0"/>
						<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
						<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/destinacionAduanera/informeInspeccion" >Subir archivo</button>
						<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?> "/>
					</div>
		</fieldset>
		
		<fieldset id="vistaSeguimientoSAI">
			<legend>Seguimiento Cuarentenario</legend>
				<div data-linea="1">
				<label>Provincia: </label>
					<input type="text" name="provincia" readOnly value="<?php echo $datosSitioCuarentena['provincia'];?>"/>
				</div>
				<div data-linea="2" id="resultadoSitios">
					<label>Sitio de cuarentena: </label>
					<input type="text" name="sitio" readOnly value="<?php echo $datosSitioCuarentena['sitio'];?>"/>
				</div>
				<div data-linea="3" id="resultadoAreas">
					<label>Área de cuarentena: </label>
					<input type="text" name="area" readOnly value="<?php echo $datosSitioCuarentena['area'];?>"/>
				</div>
		</fieldset>
		<fieldset>
			<legend>Resultado de Revisión</legend>
					
				<div data-linea="6">
					<label>Resultado</label>
						<select id="resultado" name="resultado">
							<option value="">Seleccione....</option>
							<option value="aprobado">Aprobado</option>
							<option value="subsanacion">Subsanación</option>
							<option value="rechazado">No aprobado</option>
						</select>
				</div>	
				<div data-linea="2">
					<label>Observaciones</label>
						<input type="text" id="observacion" name="observacion"/>
				</div>
		</fieldset>
		
		<button type="submit" class="guardar">Enviar resultado</button>
	</form>	
</div>
<script type="text/javascript">
				
var estado= <?php echo json_encode($qDestinacionAduanera[0]['estado']); ?>;
var validacionAprobacion= <?php echo json_encode($validacionAprobacion); ?>;
var validacionSeguimiento= <?php echo json_encode($validacionSeguimiento); ?>;

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));
		
		$("#evaluarDocumentosSolicitud").hide();
		$("#evaluarSolicitud").hide();

		$("#fechaEmbarque").datepicker({
		    changeMonth: true,
		    changeYear: true
		  });

		$("#fechaArribo").datepicker({
		    changeMonth: true,
		    changeYear: true
		  });
		
		if(estado == "enviado" || estado == "asignadoDocumental"){
			$("#evaluarDocumentosSolicitud").show();
		}else{
			$("#evaluarDocumentosSolicitud").hide();
		}
		
		if(estado == "inspeccion" || estado == "asignadoInspeccion"){
			$("#evaluarSolicitud").show();
		}else{
			$("#evaluarSolicitud").hide();
		}

		if(validacionAprobacion){
			$("#resultado").find("option[value='aprobado']").remove(); 
		}
		
		if(validacionSeguimiento){
			$("#vistaSeguimientoSAD").show();
			$("#vistaSeguimientoSAI").show();
		}else{
			$("#vistaSeguimientoSAD").hide();
			$("#vistaSeguimientoSAI").hide();
		}
		
	});

	$("#evaluarDocumentosSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccionDocumental(this);
	});

	$("#evaluarSolicitud").submit(function(event){
		event.preventDefault();
		chequearCamposInspeccion(this);
	});

	/*$("#archivo").click(function(){
		$("#subirInforme button").removeAttr("disabled");
	});*/

	/*$('#informe').change(function(event){

		var archivo = $("#informe").val();
		var extension = archivo.split('.');
		
		if(extension[extension.length-1].toUpperCase() == 'PDF'){
			subirArchivo($('#informe'),
						< ?php echo $_SESSION['usuario'];?>+'-'+< ?php echo $_POST['id'];?>+'-'+$('#fecha').val().replace(/ /g,''),
						'aplicaciones/destinacionAduanera/informeInspeccion', 
						'archivo',
						new carga($(".estadoCarga"), $('#archivo'), $('#informe')));
		}else{
			$("#estado").html('Formato incorrecto, por favor solo se permiten archivos en formato PDF').addClass("alerta");
			$('#informe').val('');
			$('#archivo').val('0');
		}
	});*/

	 var usuario = <?php echo json_encode($_SESSION['usuario']);?>;
	 var idSolicitud = <?php echo json_encode($_POST['id']);?>

	   $('button.subirArchivo').click(function (event) {
	        var boton = $(this);
	        var archivo = boton.parent().find(".archivo");
	        var rutaArchivo = boton.parent().find(".rutaArchivo");
	        var extension = archivo.val().split('.');
	        var estado = boton.parent().find(".estadoCarga");

	        if (extension[extension.length - 1].toUpperCase() == 'PDF') {

	            subirArchivo(
	                archivo
	                , usuario + "_" + idSolicitud+$('#fecha').val().replace(/ /g,'')
	                , boton.attr("data-rutaCarga")
	                , rutaArchivo
	                , new carga(estado, archivo, boton)
	            );
	        } else {
	            estado.html('Formato incorrecto, solo se admite archivos en formato PDF');
	            archivo.val("0");
	        }
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

		if($("#resultadoDocumento").val() == 'subsanacion'){
			if(!$.trim($("#observacionDocumento").val()) || !esCampoValido("#observacionDocumento")){
				error = true;
				$("#observacionDocumento").addClass("alertaCombo");
			}
		}

		if(!$.trim($("#fechaEmbarque").val())){
			error = true;
			$("#fechaEmbarque").addClass("alertaCombo");
		}
		
		if(!$.trim($("#fechaArribo").val())){
			error = true;
			$("#fechaArribo").addClass("alertaCombo");
		}
		
		/*if(!$.trim($("#numeroContenedores").val()) || !esCampoValido("#numeroContenedores")){
			error = true;
			$("#numeroContenedores").addClass("alertaCombo");
		}*/
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}

	function chequearCamposInspeccion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		/*if($("#archivo").val() == 0){
			error = true;
			$("#informe").addClass("alertaCombo");
		}*/
		
		if(!$.trim($("#resultado").val()) || !esCampoValido("#resultado")){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}

		if($("#resultado").val()=='rechazado'){
			if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
				error = true;
				$("#observacion").addClass("alertaCombo");
			}
		}

		$("input[type='checkbox']").each(function(e){   
			if($(this).is(':checked')){
				if(!$.trim($('#p'+$(this).val())) || !esCampoValido('#p'+$(this).val())){
					error = true;
					$('#p'+$(this).val()).addClass("alertaCombo");
				}
			}
	    });
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}

	$("input[type='checkbox']").change(function(){

		if($(this).is(':checked')){
			$('#p'+$(this).val()).removeAttr("disabled");
		}else{
			$('#p'+$(this).val()).attr("disabled","disabled");
		}
		
	});
</script>
