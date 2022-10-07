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
//$qOperador = $cr->buscarOperador($conexion, $qExportacion[0]['identificador']);

$fecha1= date('Y-m-d - H-i-s');
$fecha = str_replace(' ', '', $fecha1);

?>

<header>
	<h1>Solicitud Exportación Zoosanitario</h1>
</header>

	<div id="estado"></div>
	
	<div class="pestania">
	
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
			<legend>Información del importador</legend>
			
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
		
				<?php 
					if($zoosanitario['fecha_inspeccion'] != ''){
						echo '<div data-linea="19">
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

</div>
<!-- SECCION DE REVISIÓN DE PRODUCTOS Y ÁREAS PARA IMPORTACION -->
<div class="pestania">	
	<form id="evaluarDocumentosSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarDocumentosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="tipoSolicitud" value="Zoosanitario"/>
		<input type="hidden" name="tipoInspector" value="Documental"/>
		<input type="hidden" name="idVue" value="<?php echo $zoosanitario['id_vue'];?>"/>
		
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
		
		<button type="submit" class="guardar">Enviar resultado</button>			
	</form> 
	
	<form id="evaluarSolicitud" data-rutaAplicacion="revisionFormularios" data-opcion="evaluarElementosSolicitud" data-accionEnExito="ACTUALIZAR">
		<input type="hidden" name="inspector" value="<?php echo $_SESSION['usuario'];?>"/> <!-- INSPECTOR -->
		<input type="hidden" name="idSolicitud" value="<?php echo $_POST['id'];?>"/>
		<input type="hidden" name="tipoSolicitud" value="Zoosanitario"/>
		<input type="hidden" name="tipoInspector" value="Técnico"/>
		<input type="hidden" name="identificadorOperador" value="<?php echo $zoosanitario['identificador_operador'];?>"/> <!-- USUARIO OPERADOR -->
		<input type="hidden" name="idVue" value="<?php echo $zoosanitario['id_vue'];?>"/>
		<input type="hidden" name="tipoElemento" value="Productos"/>
		<input type="hidden" name="codigoSitio" value="<?php echo $zoosanitario['codigo_sitio']; ?>"/>
		
		 <?php 
		   //Obtener el número de elementos a inspeccionar
		   $historial = $ci->listarHistorialSolicitudes($conexion, $_POST['id']);
		   
		   if (true){
			echo '<fieldset>
			  <legend>Historial de revisión</legend>
			  
			  <table>
			   <tr>
				<th>#</th>
				<th>Producto</th>
				<th>Estado</th>
			   </tr>';
			$i=1;
			while($registrosHistorial = pg_fetch_assoc($historial)){
			//foreach($qZoosanitarioProductos as $registrosHistorial){
			 echo '<tr>
			   <td>'.$i.'</td>
			   <td>'.$registrosHistorial['nombre_producto'].'</td>
			   <td>'.$registrosHistorial['estado'].'</td>
			  </tr>';
			 
			 $i++;
			}
			
			echo ' </table>
			 </fieldset>';
		   }
  ?>
		
		<fieldset>
			<legend>Productos y áreas para revisión</legend>
			
			<p class="nota">Por favor seleccione los productos y las áreas en las que se producen para evaluar.</p>				
				
			<div data-linea="1">
				<label>Productos</label>
					<select id="productos" name="productos">
						<option value="">Seleccione....</option>
						<?php 
							foreach ($qZoosanitarioProductos as $zoo){
								if($zoo['estado']=='' || $zoo['estado']=='rechazado'){
									echo '<option value="'.$zoo['idProducto'].'">'. $zoo['nombreProducto'] . '</option>';
								}
							}
						?>
					</select>
			</div>	
			
			
			<div data-linea="1">
				<label>Áreas</label>
					<select id="areas" name="areas">
						<option value="">Seleccione....</option>
						<?php 
							$qAreaOperacion = $cr->buscarAreasXCodigoSitio($conexion, $zoosanitario['identificador_operador'], $zoosanitario['codigo_sitio']);
						
							foreach ($qAreaOperacion as $areas){
								echo '<option value="'.$areas['idArea'].'" data-tipo="'.$areas['tipoArea'].'" data-nombre="'.$areas['nombreArea'].'">'. $areas['tipoArea'] . ' - ' . $areas['nombreArea'] . '</option>';
							}
						?>
					</select>
			</div>	

			<button type="button" onclick="agregarProductosAreas()" class="mas">Agregar</button>
		</fieldset>	
		
		<fieldset>
			<legend>Productos y Áreas agregadas</legend>
					 <div>
						<table>
							<thead>
								<tr>
									<th></th>
									<th>Producto</th>
									<th>Tipo Área</th>
									<th>Nombre</th>
								<tr>
							</thead> 
							<tbody id="productosareas">
							</tbody>
						</table>
					</div>
		</fieldset>
	
		<fieldset id="subirInforme">
				<legend>Informe de revisión</legend>
					<!-- input type="file" name="informe" id='informe' />
					<input type="hidden" id="archivo" name="archivo" value="0"/-->
					<input type="file" class="archivo" name="informe" accept="application/pdf"/>
					<input type="hidden" class="rutaArchivo" name="archivo" value="0"/>
					<div class="estadoCarga">En espera de archivo... (Tamaño máximo <?php echo ini_get('upload_max_filesize'); ?>B)</div>
					<button type="button" class="subirArchivo adjunto" data-rutaCarga="aplicaciones/exportacionZoosanitario/informeInspeccion" >Subir archivo</button>
					<input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?> "/>
					
		</fieldset>
		
		<fieldset>
			<legend>Resultado de Revisión</legend>
					
				<div data-linea="6">
					<label>Resultado</label>
						<select id="resultado" name="resultado">
							<option value="">Seleccione....</option>
							<option value="aprobado">Aprobado</option>
							<option value="subsanacion">Subsanación</option>
							<option value="rechazado">Rechazado</option>
						</select>
				</div>	
				<div data-linea="2">
					<label>Observaciones</label>
						<input type="text" id="observacion" name="observacion"/>
				</div>
				<div data-linea="3">
					<label>Fecha inspección</label>
						<input type="text" id="fechaInspeccion" name="fechaInspeccion"/>
				</div>
		</fieldset>
		
		<button type="submit" class="guardar">Enviar resultado</button>
	</form>	
</div>    

<script type="text/javascript">
var estado= <?php echo json_encode($zoosanitario['estado']) ?>

	$(document).ready(function(){
		distribuirLineas();
		construirAnimacion($(".pestania"));

		$("#evaluarDocumentosSolicitud").hide();
		$("#evaluarSolicitud").hide();
		
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
	});

	$("#fechaInspeccion").datepicker({
	    changeMonth: true,
	    changeYear: true
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
			subirArchivo('informe',< ?php echo $_SESSION['usuario'];?>+'-'+< ?php echo $_POST['id'];?>+'-'+$('#fecha').val().replace(/ /g,''),'aplicaciones/exportacionZoosanitario/informeInspeccion', 'archivo');
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
	            archivo.val("");
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

		
		if($("#resultadoDocumento").val()=='subsanacion'){
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
	
	function chequearCamposInspeccion(form){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;
	
		if($("#archivo").val() == 0 && $("#resultado").val() != 'subsanacion'){
			error = true;
			$("#informe").addClass("alertaCombo");
		}

		if($("#fechaInspeccion").val() == 0 ){
			error = true;
			$("#fechaInspeccion").addClass("alertaCombo");
		}
		
		
		if(!$.trim($("#resultado").val())){
			error = true;
			$("#resultado").addClass("alertaCombo");
		}

		if($("#resultado").val()!= 'aprobado'){
			if(!$.trim($("#observacion").val()) || !esCampoValido("#observacion")){
				error = true;
				$("#observacion").addClass("alertaCombo");
			}
		}
		
		
		if (error){
			$("#estado").html("Por favor revise la información ingresada.").addClass('alerta');
		}else{
			ejecutarJson(form);
		}
	}

	function agregarProductosAreas(){
		$(".alertaCombo").removeClass("alertaCombo");
		var error = false;

		if(!$.trim($("#productos").val())){
			error = true;
			$("#productos").addClass("alertaCombo");
		}
		
		if(!$.trim($("#areas").val())){
			error = true;
			$("#areas").addClass("alertaCombo");
		}

		if (error){
			$("#estado").html("Por favor revise el formato de la información ingresada.").addClass('alerta');
		}else{
			$("#estado").html("").removeClass('alerta');
			if($("#productosareas #r_"+$("#productos").val()+$("#areas").val()).length==0){
				$("#productosareas").append("<tr id='r_"+$("#productos").val()+$("#areas").val()+"'><td><button type='button' onclick='quitarProductosAreas(\"#r_"+$("#productos").val()+$("#areas").val()+"\")' class='menos'>Quitar</button></td><td>"+$("#productos option:selected").text()+"<input id='listaElementos' name='listaElementos[]' value='"+$("#productos option:selected").val()+"' type='hidden'></td><td>"+$("#areas option:selected").attr('data-tipo')+"</td><td>"+$("#areas option:selected").attr('data-nombre')+"<input id='listaAreas' name='listaAreas[]' value='"+$("#areas option:selected").val()+"' type='hidden'></td></tr>");
			}else{
				$("#estado").html("Por favor verifique su información, ya que no puede repetirse.").addClass('alerta');
			}
		}
	}

	function quitarProductosAreas(fila){
		$("#productosareas tr").eq($(fila).index()).remove();
	}
</script>
